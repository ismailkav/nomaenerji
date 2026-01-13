<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('urun_detay_gruplari', function (Blueprint $table) {
            if (!Schema::hasColumn('urun_detay_gruplari', 'montaj_grubu')) {
                $table->boolean('montaj_grubu')->default(false)->after('ad');
            }
        });
    }

    public function down(): void
    {
        Schema::table('urun_detay_gruplari', function (Blueprint $table) {
            if (Schema::hasColumn('urun_detay_gruplari', 'montaj_grubu')) {
                $table->dropColumn('montaj_grubu');
            }
        });
    }
};

