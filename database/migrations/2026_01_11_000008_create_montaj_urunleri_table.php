<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('montaj_urunleri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('montaj_grup_id')->constrained('montaj_gruplari')->cascadeOnDelete();
            $table->unsignedBigInteger('urun_id')->nullable();
            $table->string('urun_kod', 150)->nullable();
            $table->string('birim', 20)->default('Adet');
            $table->decimal('birim_fiyat', 15, 2)->default(0);
            $table->string('doviz', 3)->default('TL');
            $table->unsignedInteger('sirano')->default(0);
            $table->timestamps();

            $table->index(['montaj_grup_id', 'sirano']);
            $table->index(['urun_id']);

            $table->foreign('urun_id')
                ->references('id')
                ->on('urunler')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('montaj_urunleri');
    }
};

