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
        Schema::create('konsinye_fisleri', function (Blueprint $table) {
            $table->id();
            $table->string('tip', 10); // giris | cikis
            $table->unsignedBigInteger('fis_sira');
            $table->string('fis_no', 50);

            $table->date('tarih')->nullable();
            $table->foreignId('cari_id')->nullable()->constrained('firmalar')->nullOnDelete();
            $table->string('carikod', 50)->nullable();
            $table->string('cariaciklama', 255)->nullable();
            $table->date('teslim_tarihi')->nullable();
            $table->string('durum', 50)->nullable();
            $table->text('aciklama')->nullable();
            $table->foreignId('hazirlayan_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('proje_id')->nullable()->constrained('projeler')->nullOnDelete();
            $table->dateTime('islem_tarihi')->nullable();

            $table->timestamps();

            $table->unique(['tip', 'fis_sira']);
            $table->unique(['tip', 'fis_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konsinye_fisleri');
    }
};

