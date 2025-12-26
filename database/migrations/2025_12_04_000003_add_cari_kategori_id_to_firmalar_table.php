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
        Schema::table('firmalar', function (Blueprint $table) {
            $table->unsignedBigInteger('cari_kategori_id')->nullable()->after('carikod');

            $table->foreign('cari_kategori_id')
                ->references('id')
                ->on('cari_kategorileri')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('firmalar', function (Blueprint $table) {
            $table->dropForeign(['cari_kategori_id']);
            $table->dropColumn('cari_kategori_id');
        });
    }
};

