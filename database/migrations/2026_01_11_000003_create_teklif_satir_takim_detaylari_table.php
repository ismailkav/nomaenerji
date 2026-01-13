<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('teklif_satir_takim_detaylari', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teklif_detay_id')->constrained('teklif_detaylari')->cascadeOnDelete();
            $table->foreignId('urun_id')->nullable()->constrained('urunler')->nullOnDelete();
            $table->string('stokkod', 50)->nullable();
            $table->string('stok_aciklama', 255)->nullable();
            $table->decimal('miktar', 15, 3)->default(0);
            $table->decimal('birim_fiyat', 15, 2)->default(0);
            $table->string('doviz', 3)->default('TL');
            $table->decimal('kur', 15, 4)->default(1);
            $table->decimal('satir_tutar', 15, 2)->default(0);
            $table->timestamps();

            $table->index(['teklif_detay_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teklif_satir_takim_detaylari');
    }
};

