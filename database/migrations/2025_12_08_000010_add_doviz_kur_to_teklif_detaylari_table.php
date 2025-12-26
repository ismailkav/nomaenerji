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
        Schema::table('teklif_detaylari', function (Blueprint $table) {
            if (!Schema::hasColumn('teklif_detaylari', 'doviz')) {
                $table->string('doviz', 3)->default('TL')->after('birim_fiyat');
            }
            if (!Schema::hasColumn('teklif_detaylari', 'kur')) {
                $table->decimal('kur', 15, 4)->default(1)->after('doviz');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teklif_detaylari', function (Blueprint $table) {
            if (Schema::hasColumn('teklif_detaylari', 'kur')) {
                $table->dropColumn('kur');
            }
            if (Schema::hasColumn('teklif_detaylari', 'doviz')) {
                $table->dropColumn('doviz');
            }
        });
    }
};

