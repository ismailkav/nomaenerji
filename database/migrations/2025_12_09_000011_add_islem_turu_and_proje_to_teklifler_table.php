<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('teklifler', function (Blueprint $table) {
            $table->foreignId('islem_turu_id')
                ->nullable()
                ->after('yetkili_personel')
                ->constrained('islem_turleri');

            $table->foreignId('proje_id')
                ->nullable()
                ->after('islem_turu_id')
                ->constrained('projeler');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teklifler', function (Blueprint $table) {
            $table->dropForeign(['islem_turu_id']);
            $table->dropForeign(['proje_id']);
            $table->dropColumn(['islem_turu_id', 'proje_id']);
        });
    }
};

