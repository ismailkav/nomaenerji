<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private function foreignKeyName(string $table, string $column): ?string
    {
        $db = DB::getDatabaseName();
        if (!$db) {
            return null;
        }

        $rows = DB::select(
            "SELECT CONSTRAINT_NAME
             FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = ?
               AND TABLE_NAME = ?
               AND COLUMN_NAME = ?
               AND REFERENCED_TABLE_NAME IS NOT NULL
             LIMIT 1",
            [$db, $table, $column]
        );

        return $rows[0]->CONSTRAINT_NAME ?? null;
    }

    private function dropForeignKeyIfExists(string $table, string $column): void
    {
        $fk = $this->foreignKeyName($table, $column);
        if ($fk) {
            DB::statement("ALTER TABLE `$table` DROP FOREIGN KEY `$fk`");
        }
    }

    private function indexNameExists(string $table, string $indexName): bool
    {
        $db = DB::getDatabaseName();
        if (!$db) {
            return false;
        }

        $rows = DB::select(
            "SELECT 1
             FROM information_schema.STATISTICS
             WHERE TABLE_SCHEMA = ?
               AND TABLE_NAME = ?
               AND INDEX_NAME = ?
             LIMIT 1",
            [$db, $table, $indexName]
        );

        return !empty($rows);
    }

    private function hasLeftmostIndex(string $table, string $column): bool
    {
        $db = DB::getDatabaseName();
        if (!$db) {
            return false;
        }

        $rows = DB::select(
            "SELECT 1
             FROM information_schema.STATISTICS
             WHERE TABLE_SCHEMA = ?
               AND TABLE_NAME = ?
               AND COLUMN_NAME = ?
               AND SEQ_IN_INDEX = 1
             LIMIT 1",
            [$db, $table, $column]
        );

        return !empty($rows);
    }

    private function dropIndexIfExists(string $table, string $indexName): void
    {
        if ($this->indexNameExists($table, $indexName)) {
            DB::statement("ALTER TABLE `$table` DROP INDEX `$indexName`");
        }
    }

    public function up(): void
    {
        $tableName = 'montaj_urun_gruplari';

        $this->dropForeignKeyIfExists($tableName, 'montaj_grup_id');
        $this->dropForeignKeyIfExists($tableName, 'urun_detay_grup_id');

        $this->dropIndexIfExists($tableName, 'uq_montaj_grup_urun_detay');

        if (Schema::hasColumn($tableName, 'urun_detay_grup_id')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('urun_detay_grup_id');
            });
        }

        if (!Schema::hasColumn($tableName, 'urun_id')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->unsignedBigInteger('urun_id')->nullable()->after('montaj_urun_id');
            });
        }

        if (!$this->hasLeftmostIndex($tableName, 'urun_id')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->index('urun_id', 'idx_montaj_urun_gruplari_urun_id');
            });
        }

        $this->dropForeignKeyIfExists($tableName, 'urun_id');
        Schema::table($tableName, function (Blueprint $table) {
            $table->foreign('urun_id')->references('id')->on('urunler')->cascadeOnDelete();
        });

        if (!$this->indexNameExists($tableName, 'uq_montaj_grup_urun')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->unique(['montaj_grup_id', 'montaj_urun_id', 'urun_id'], 'uq_montaj_grup_urun');
            });
        }

        $this->dropForeignKeyIfExists($tableName, 'montaj_grup_id');
        if (!$this->hasLeftmostIndex($tableName, 'montaj_grup_id')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->index('montaj_grup_id', 'idx_montaj_urun_gruplari_montaj_grup_id');
            });
        }
        Schema::table($tableName, function (Blueprint $table) {
            $table->foreign('montaj_grup_id')->references('id')->on('montaj_gruplari')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        $tableName = 'montaj_urun_gruplari';

        $this->dropForeignKeyIfExists($tableName, 'montaj_grup_id');
        $this->dropForeignKeyIfExists($tableName, 'urun_id');

        $this->dropIndexIfExists($tableName, 'uq_montaj_grup_urun');

        if (Schema::hasColumn($tableName, 'urun_id')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('urun_id');
            });
        }

        if (!Schema::hasColumn($tableName, 'urun_detay_grup_id')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->unsignedBigInteger('urun_detay_grup_id')->after('montaj_urun_id');
            });
        }

        if (!$this->hasLeftmostIndex($tableName, 'urun_detay_grup_id')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->index('urun_detay_grup_id', 'idx_montaj_urun_gruplari_urun_detay_grup_id');
            });
        }

        $this->dropForeignKeyIfExists($tableName, 'urun_detay_grup_id');
        Schema::table($tableName, function (Blueprint $table) {
            $table->foreign('urun_detay_grup_id')->references('id')->on('urun_detay_gruplari')->cascadeOnDelete();
        });

        if (!$this->indexNameExists($tableName, 'uq_montaj_grup_urun_detay')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->unique(['montaj_grup_id', 'montaj_urun_id', 'urun_detay_grup_id'], 'uq_montaj_grup_urun_detay');
            });
        }

        $this->dropForeignKeyIfExists($tableName, 'montaj_grup_id');
        if (!$this->hasLeftmostIndex($tableName, 'montaj_grup_id')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->index('montaj_grup_id', 'idx_montaj_urun_gruplari_montaj_grup_id');
            });
        }
        Schema::table($tableName, function (Blueprint $table) {
            $table->foreign('montaj_grup_id')->references('id')->on('montaj_gruplari')->cascadeOnDelete();
        });
    }
};
