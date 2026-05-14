<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private function isSqlite(): bool
    {
        return DB::getDriverName() === 'sqlite';
    }

    private function foreignKeyName(string $table, string $column): ?string
    {
        if ($this->isSqlite()) {
            return null;
        }

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
        if ($this->isSqlite()) {
            $indexes = collect(DB::select("PRAGMA index_list('$table')"));

            return $indexes->contains(fn ($index) => ($index->name ?? null) === $indexName);
        }

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
        if ($this->isSqlite()) {
            $indexes = DB::select("PRAGMA index_list('$table')");

            foreach ($indexes as $index) {
                $indexName = $index->name ?? null;
                if (!$indexName) {
                    continue;
                }

                $columns = DB::select("PRAGMA index_info('$indexName')");
                $firstColumn = $columns[0]->name ?? null;

                if ($firstColumn === $column) {
                    return true;
                }
            }

            return false;
        }

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
            if ($this->isSqlite()) {
                DB::statement("DROP INDEX IF EXISTS \"$indexName\"");
            } else {
                DB::statement("ALTER TABLE `$table` DROP INDEX `$indexName`");
            }
        }
    }

    private function rebuildSqliteTableForUp(string $tableName): void
    {
        $rows = Schema::hasTable($tableName)
            ? DB::table($tableName)->get([
                'id',
                'montaj_grup_id',
                'montaj_urun_id',
                'sirano',
                'created_at',
                'updated_at',
            ])
            : collect();

        Schema::dropIfExists($tableName);

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->foreignId('montaj_grup_id')->constrained('montaj_gruplari')->cascadeOnDelete();
            $table->foreignId('montaj_urun_id')->constrained('montaj_urunleri')->cascadeOnDelete();
            $table->foreignId('urun_id')->nullable()->constrained('urunler')->nullOnDelete();
            $table->unsignedInteger('sirano')->nullable();
            $table->timestamps();
            $table->index('urun_id', 'idx_montaj_urun_gruplari_urun_id');
            $table->index('montaj_grup_id', 'idx_montaj_urun_gruplari_montaj_grup_id');
            $table->unique(['montaj_grup_id', 'montaj_urun_id', 'urun_id'], 'uq_montaj_grup_urun');
        });

        foreach ($rows as $row) {
            DB::table($tableName)->insert([
                'id' => $row->id,
                'montaj_grup_id' => $row->montaj_grup_id,
                'montaj_urun_id' => $row->montaj_urun_id,
                'urun_id' => null,
                'sirano' => $row->sirano,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]);
        }
    }

    private function rebuildSqliteTableForDown(string $tableName): void
    {
        $rows = Schema::hasTable($tableName)
            ? DB::table($tableName)->get([
                'id',
                'montaj_grup_id',
                'montaj_urun_id',
                'sirano',
                'created_at',
                'updated_at',
            ])
            : collect();

        Schema::dropIfExists($tableName);

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->foreignId('montaj_grup_id')->constrained('montaj_gruplari')->cascadeOnDelete();
            $table->foreignId('montaj_urun_id')->constrained('montaj_urunleri')->cascadeOnDelete();
            $table->foreignId('urun_detay_grup_id')->nullable()->constrained('urun_detay_gruplari')->nullOnDelete();
            $table->unsignedInteger('sirano')->nullable();
            $table->timestamps();
            $table->index('urun_detay_grup_id', 'idx_montaj_urun_gruplari_urun_detay_grup_id');
            $table->index('montaj_grup_id', 'idx_montaj_urun_gruplari_montaj_grup_id');
            $table->unique(['montaj_grup_id', 'montaj_urun_id', 'urun_detay_grup_id'], 'uq_montaj_grup_urun_detay');
        });

        foreach ($rows as $row) {
            DB::table($tableName)->insert([
                'id' => $row->id,
                'montaj_grup_id' => $row->montaj_grup_id,
                'montaj_urun_id' => $row->montaj_urun_id,
                'urun_detay_grup_id' => null,
                'sirano' => $row->sirano,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]);
        }
    }

    public function up(): void
    {
        $tableName = 'montaj_urun_gruplari';

        if ($this->isSqlite()) {
            $this->rebuildSqliteTableForUp($tableName);

            return;
        }

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

        if ($this->isSqlite()) {
            $this->rebuildSqliteTableForDown($tableName);

            return;
        }

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
