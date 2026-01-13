<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('teklif_satir_montaj_detaylari', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teklif_detay_id')->constrained('teklif_detaylari')->cascadeOnDelete();
            $table->foreignId('montaj_grup_id')->nullable()->constrained('montaj_gruplari')->nullOnDelete();
            $table->foreignId('urun_id')->nullable()->constrained('urunler')->nullOnDelete();
            $table->string('urun_kod', 50)->nullable();
            $table->string('birim', 20)->default('Adet');
            $table->decimal('miktar', 15, 3)->default(0);
            $table->decimal('birim_fiyat', 15, 2)->default(0);
            $table->string('doviz', 3)->default('TL');
            $table->decimal('satir_tutar', 15, 2)->default(0);
            $table->unsignedInteger('sirano')->default(0);
            $table->timestamps();

            $table->index(['teklif_detay_id']);
            $table->index(['montaj_grup_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teklif_satir_montaj_detaylari');
    }
};

