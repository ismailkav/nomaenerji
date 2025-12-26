<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('siparis_satir_eslestirmeleri', function (Blueprint $table) {
            $table->id();

            $table->foreignId('alim_detay_id')
                ->constrained('siparis_detaylari')
                ->cascadeOnDelete();

            $table->foreignId('satis_detay_id')
                ->constrained('siparis_detaylari')
                ->cascadeOnDelete();

            $table->decimal('miktar', 15, 3)->nullable();

            $table->timestamps();

            $table->unique(['alim_detay_id', 'satis_detay_id'], 'siparis_satir_eslestirme_unique');
            $table->index('alim_detay_id');
            $table->index('satis_detay_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siparis_satir_eslestirmeleri');
    }
};

