<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('stokrevize', 'depo_id')) {
            Schema::table('stokrevize', function (Blueprint $table) {
                $table->foreignId('depo_id')
                    ->nullable()
                    ->after('siparissatirid')
                    ->constrained('depolar')
                    ->nullOnDelete();
            });
        }

        Schema::table('stokrevize', function (Blueprint $table) {
            try {
                $table->dropForeign(['siparissatirid']);
            } catch (\Throwable $e) {
            }

            try {
                $table->dropUnique('stokrevize_siparissatirid_unique');
            } catch (\Throwable $e) {
            }
        });

        try {
            Schema::table('stokrevize', function (Blueprint $table) {
                $table->unique(['siparissatirid', 'depo_id']);
            });
        } catch (\Throwable $e) {
        }

        try {
            Schema::table('stokrevize', function (Blueprint $table) {
                $table->foreign('siparissatirid')
                    ->references('id')
                    ->on('siparis_detaylari')
                    ->cascadeOnDelete();
            });
        } catch (\Throwable $e) {
        }
    }

    public function down(): void
    {
        Schema::table('stokrevize', function (Blueprint $table) {
            $table->dropUnique('stokrevize_siparissatirid_depo_id_unique');
            $table->unique('siparissatirid');

            $table->dropConstrainedForeignId('depo_id');
        });
    }
};
