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
        Schema::create('urun_kategorileri', function (Blueprint $table) {
            $table->id();
            $table->string('ad', 150);
            $table->timestamps();
        });

        Schema::table('urunler', function (Blueprint $table) {
            $table->unsignedBigInteger('kategori_id')->nullable()->after('kdv_oran');

            $table->foreign('kategori_id')
                ->references('id')
                ->on('urun_kategorileri')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('urunler', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
            $table->dropColumn('kategori_id');
        });

        Schema::dropIfExists('urun_kategorileri');
    }
};

