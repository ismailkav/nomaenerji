<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('siparisler', function (Blueprint $table) {
            $table->id();
            $table->string('siparis_turu', 10)->default('alim'); // alim | satis

            $table->string('carikod', 50);
            $table->string('cariaciklama', 255);
            $table->date('tarih');
            $table->date('gecerlilik_tarihi')->nullable();

            $table->string('siparis_no', 50)->unique();
            $table->text('aciklama')->nullable();
            $table->string('siparis_durum', 50)->nullable();

            $table->string('onay_durum', 50)->nullable();
            $table->date('onay_tarihi')->nullable();

            $table->string('yetkili_personel', 150)->nullable();
            $table->string('hazirlayan', 150)->nullable();

            $table->foreignId('islem_turu_id')->nullable()->constrained('islem_turleri');
            $table->foreignId('proje_id')->nullable()->constrained('projeler');

            $table->string('siparis_doviz', 3)->default('TL');
            $table->decimal('siparis_kur', 15, 4)->default(1);

            $table->timestamp('ekleme_tarihi')->useCurrent();
            $table->timestamp('son_guncelleme_tarihi')->useCurrent()->useCurrentOnUpdate();

            $table->decimal('toplam', 15, 2)->default(0);
            $table->decimal('iskonto_tutar', 15, 2)->default(0);
            $table->decimal('kdv', 15, 2)->default(0);
            $table->decimal('genel_toplam', 15, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siparisler');
    }
};

