<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('teklifler', function (Blueprint $table) {
            if (!Schema::hasColumn('teklifler', 'teklif_doviz')) {
                $table->string('teklif_doviz', 3)->default('TL')->after('proje_id');
            }

            if (!Schema::hasColumn('teklifler', 'teklif_kur')) {
                $table->decimal('teklif_kur', 15, 4)->default(1)->after('teklif_doviz');
            }
        });
    }

    public function down(): void
    {
        Schema::table('teklifler', function (Blueprint $table) {
            if (Schema::hasColumn('teklifler', 'teklif_kur')) {
                $table->dropColumn('teklif_kur');
            }

            if (Schema::hasColumn('teklifler', 'teklif_doviz')) {
                $table->dropColumn('teklif_doviz');
            }
        });
    }
};

