<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('teklif_satir_takim_detaylari', function (Blueprint $table) {
            if (!Schema::hasColumn('teklif_satir_takim_detaylari', 'iskonto1')) {
                $table->decimal('iskonto1', 5, 2)->default(0)->after('birim_fiyat');
            }
            if (!Schema::hasColumn('teklif_satir_takim_detaylari', 'iskonto2')) {
                $table->decimal('iskonto2', 5, 2)->default(0)->after('iskonto1');
            }
        });
    }

    public function down(): void
    {
        Schema::table('teklif_satir_takim_detaylari', function (Blueprint $table) {
            if (Schema::hasColumn('teklif_satir_takim_detaylari', 'iskonto2')) {
                $table->dropColumn('iskonto2');
            }
            if (Schema::hasColumn('teklif_satir_takim_detaylari', 'iskonto1')) {
                $table->dropColumn('iskonto1');
            }
        });
    }
};

