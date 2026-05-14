<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('urunler', function (Blueprint $table) {
            if (!Schema::hasColumn('urunler', 'original_name')) {
                $table->string('original_name', 255)->nullable()->after('resim_yolu');
            }
        });
    }

    public function down(): void
    {
        Schema::table('urunler', function (Blueprint $table) {
            if (Schema::hasColumn('urunler', 'original_name')) {
                $table->dropColumn('original_name');
            }
        });
    }
};
