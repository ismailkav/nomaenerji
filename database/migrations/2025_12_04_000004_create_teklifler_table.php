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
        Schema::create('teklifler', function (Blueprint $table) {
            $table->id();
            $table->string('carikod', 50);
            $table->string('cariaciklama', 255);
            $table->date('tarih');
            $table->date('gecerlilik_tarihi')->nullable();
            $table->string('teklif_no', 50)->unique();
            $table->string('revize_no', 20)->nullable();
            $table->text('aciklama')->nullable();
            $table->string('teklif_durum', 50)->nullable();
            $table->string('onay_durum', 50)->nullable();
            $table->date('onay_tarihi')->nullable();
            $table->string('yetkili_personel', 150)->nullable();
            $table->string('hazirlayan', 150)->nullable();
            $table->timestamp('ekleme_tarihi')->useCurrent();
            $table->timestamp('son_guncelleme_tarihi')->useCurrent()->useCurrentOnUpdate();
            $table->decimal('toplam', 15, 2)->default(0);
            $table->decimal('iskonto_tutar', 15, 2)->default(0);
            $table->decimal('kdv', 15, 2)->default(0);
            $table->decimal('genel_toplam', 15, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teklifler');
    }
};
