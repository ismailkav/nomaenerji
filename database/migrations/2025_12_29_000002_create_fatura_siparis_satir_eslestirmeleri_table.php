<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fatura_siparis_satir_eslestirmeleri', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fatura_detay_id')
                ->constrained('fatura_detaylari')
                ->cascadeOnDelete();

            $table->foreignId('siparis_detay_id')
                ->constrained('siparis_detaylari')
                ->cascadeOnDelete();

            $table->decimal('miktar', 15, 3)->default(0);

            $table->timestamps();

            $table->unique(['fatura_detay_id', 'siparis_detay_id'], 'fatura_siparis_satir_unique');
            $table->index('siparis_detay_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fatura_siparis_satir_eslestirmeleri');
    }
};

