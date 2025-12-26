<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('siparisler', function (Blueprint $table) {
            $table->string('teklif_no', 50)->nullable()->after('siparis_no');
            $table->index('teklif_no');
        });
    }

    public function down(): void
    {
        Schema::table('siparisler', function (Blueprint $table) {
            $table->dropIndex(['teklif_no']);
            $table->dropColumn('teklif_no');
        });
    }
};

