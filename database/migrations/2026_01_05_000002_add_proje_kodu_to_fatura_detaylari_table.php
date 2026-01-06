<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('fatura_detaylari', function (Blueprint $table) {
            $table->string('proje_kodu', 50)->nullable()->after('urun_id');
            $table->index('proje_kodu');
        });
    }

    public function down(): void
    {
        Schema::table('fatura_detaylari', function (Blueprint $table) {
            $table->dropIndex(['proje_kodu']);
            $table->dropColumn('proje_kodu');
        });
    }
};

