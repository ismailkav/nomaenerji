<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('urunrecete', function (Blueprint $table) {
            $table->id();
            $table->foreignId('urun_id')->constrained('urunler')->cascadeOnDelete();
            $table->foreignId('stok_urun_id')->constrained('urunler')->cascadeOnDelete();
            $table->decimal('miktar', 12, 3)->default(0);
            $table->unsignedInteger('sirano')->default(0);
            $table->timestamps();

            $table->unique(['urun_id', 'stok_urun_id'], 'uq_urunrecete_urun_stok');
            $table->index(['urun_id', 'sirano']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('urunrecete');
    }
};

