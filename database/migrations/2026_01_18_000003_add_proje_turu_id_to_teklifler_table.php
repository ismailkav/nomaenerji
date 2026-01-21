<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('teklifler', function (Blueprint $table) {
            if (!Schema::hasColumn('teklifler', 'proje_turu_id')) {
                $table->foreignId('proje_turu_id')
                    ->nullable()
                    ->after('proje_id')
                    ->constrained('projeturu')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('teklifler', function (Blueprint $table) {
            if (Schema::hasColumn('teklifler', 'proje_turu_id')) {
                $table->dropForeign(['proje_turu_id']);
                $table->dropColumn('proje_turu_id');
            }
        });
    }
};

