<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('montaj_gruplari', function (Blueprint $table) {
            if (!Schema::hasColumn('montaj_gruplari', 'sirano')) {
                $table->unsignedInteger('sirano')->default(0)->after('urun_detay_grup_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('montaj_gruplari', function (Blueprint $table) {
            if (Schema::hasColumn('montaj_gruplari', 'sirano')) {
                $table->dropColumn('sirano');
            }
        });
    }
};

