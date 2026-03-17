<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('teklifler', function (Blueprint $table) {
            if (!Schema::hasColumn('teklifler', 'satis_temsilcisi')) {
                $table->string('satis_temsilcisi', 150)
                    ->nullable()
                    ->after('hazirlayan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('teklifler', function (Blueprint $table) {
            if (Schema::hasColumn('teklifler', 'satis_temsilcisi')) {
                $table->dropColumn('satis_temsilcisi');
            }
        });
    }
};
