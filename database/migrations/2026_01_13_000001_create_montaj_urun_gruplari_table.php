<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('montaj_urun_gruplari', function (Blueprint $table) {
            $table->id();
            $table->foreignId('montaj_grup_id')->constrained('montaj_gruplari')->cascadeOnDelete();
            $table->foreignId('montaj_urun_id')->constrained('montaj_urunleri')->cascadeOnDelete();
            $table->foreignId('urun_detay_grup_id')->constrained('urun_detay_gruplari')->cascadeOnDelete();
            $table->unsignedInteger('sirano')->nullable();
            $table->timestamps();
            $table->unique(['montaj_grup_id', 'montaj_urun_id', 'urun_detay_grup_id'], 'uq_montaj_grup_urun_detay');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('montaj_urun_gruplari');
    }
};
