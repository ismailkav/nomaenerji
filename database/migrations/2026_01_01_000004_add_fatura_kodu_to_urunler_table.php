<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('urunler', function (Blueprint $table) {
            $table->string('fatura_kodu', 50)->nullable()->after('prm4');
        });
    }

    public function down(): void
    {
        Schema::table('urunler', function (Blueprint $table) {
            $table->dropColumn('fatura_kodu');
        });
    }
};

