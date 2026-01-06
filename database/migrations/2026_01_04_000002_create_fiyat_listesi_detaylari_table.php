<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fiyat_listesi_detaylari', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fiyat_listesi_id')->constrained('fiyat_listeleri')->cascadeOnDelete();
            $table->foreignId('urun_id')->nullable()->constrained('urunler')->nullOnDelete();
            $table->string('stok_kod', 50)->nullable();
            $table->string('stok_aciklama', 255)->nullable();
            $table->decimal('birim_fiyat', 15, 4)->default(0);
            $table->string('doviz', 3)->default('TL');
            $table->timestamps();

            $table->index(['fiyat_listesi_id']);
            $table->index(['urun_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fiyat_listesi_detaylari');
    }
};

