<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('urunler', function (Blueprint $table) {
            $table->string('prm1', 255)->nullable()->after('kategori_id');
            $table->string('prm2', 255)->nullable()->after('prm1');
            $table->string('prm3', 255)->nullable()->after('prm2');
            $table->string('prm4', 255)->nullable()->after('prm3');
        });
    }

    public function down(): void
    {
        Schema::table('urunler', function (Blueprint $table) {
            $table->dropColumn(['prm1', 'prm2', 'prm3', 'prm4']);
        });
    }
};

