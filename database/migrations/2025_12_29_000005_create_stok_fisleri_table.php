<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stok_fisleri', function (Blueprint $table) {
            $table->id();
            $table->string('tip', 30); // sayim_giris | sayim_cikis | depo_transfer
            $table->unsignedBigInteger('fis_sira');
            $table->string('fis_no', 50);

            $table->date('tarih')->nullable();
            $table->foreignId('depo_id')->nullable()->constrained('depolar')->nullOnDelete();
            $table->foreignId('cikis_depo_id')->nullable()->constrained('depolar')->nullOnDelete();
            $table->foreignId('giris_depo_id')->nullable()->constrained('depolar')->nullOnDelete();
            $table->text('aciklama')->nullable();
            $table->foreignId('hazirlayan_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('islem_tarihi')->nullable();

            $table->timestamps();

            $table->unique(['tip', 'fis_sira']);
            $table->unique(['tip', 'fis_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_fisleri');
    }
};

