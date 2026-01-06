<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('urunler', function (Blueprint $table) {
            $table->unsignedBigInteger('urun_alt_grup_id')->nullable()->after('kategori_id');
            $table->unsignedBigInteger('urun_detay_grup_id')->nullable()->after('urun_alt_grup_id');

            $table->foreign('urun_alt_grup_id')
                ->references('id')
                ->on('urun_alt_gruplari')
                ->nullOnDelete();

            $table->foreign('urun_detay_grup_id')
                ->references('id')
                ->on('urun_detay_gruplari')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('urunler', function (Blueprint $table) {
            $table->dropForeign(['urun_alt_grup_id']);
            $table->dropForeign(['urun_detay_grup_id']);
            $table->dropColumn(['urun_alt_grup_id', 'urun_detay_grup_id']);
        });
    }
};

