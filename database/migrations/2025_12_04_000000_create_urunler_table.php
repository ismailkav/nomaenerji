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
        Schema::create('urunler', function (Blueprint $table) {
            $table->id();
            $table->string('kod', 50)->unique();
            $table->string('aciklama', 255);
            $table->decimal('satis_fiyat', 12, 2)->default(0);
            $table->unsignedTinyInteger('kdv_oran')->default(0);
            $table->string('kategori', 150)->nullable();
            $table->string('resim_yolu', 255)->nullable();
            $table->boolean('pasif')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('urunler');
    }
};

