<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fatura_detaylari', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fatura_id')->constrained('faturalar')->cascadeOnDelete();
            $table->foreignId('urun_id')->nullable()->constrained('urunler')->nullOnDelete();

            $table->string('satir_aciklama', 255)->nullable();
            $table->string('durum', 1)->default('A');
            $table->index('durum');

            $table->decimal('miktar', 15, 3)->default(0);
            $table->string('birim', 20)->nullable();
            $table->decimal('birim_fiyat', 15, 2)->default(0);

            $table->string('doviz', 3)->default('TL');
            $table->decimal('kur', 15, 4)->default(1);

            $table->decimal('iskonto1', 5, 2)->default(0);
            $table->decimal('iskonto2', 5, 2)->default(0);
            $table->decimal('iskonto3', 5, 2)->default(0);
            $table->decimal('iskonto4', 5, 2)->default(0);
            $table->decimal('iskonto5', 5, 2)->default(0);
            $table->decimal('iskonto6', 5, 2)->default(0);
            $table->decimal('iskonto_tutar', 15, 2)->default(0);

            $table->decimal('kdv_orani', 5, 2)->default(0);
            $table->decimal('kdv_tutar', 15, 2)->default(0);
            $table->decimal('satir_toplam', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fatura_detaylari');
    }
};

