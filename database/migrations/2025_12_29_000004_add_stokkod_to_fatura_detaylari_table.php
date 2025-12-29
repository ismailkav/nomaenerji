<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('fatura_detaylari', function (Blueprint $table) {
            if (!Schema::hasColumn('fatura_detaylari', 'stokkod')) {
                $table->string('stokkod', 100)->nullable()->after('urun_id');
                $table->index('stokkod');
            }
        });
    }

    public function down(): void
    {
        Schema::table('fatura_detaylari', function (Blueprint $table) {
            if (Schema::hasColumn('fatura_detaylari', 'stokkod')) {
                $table->dropIndex(['stokkod']);
                $table->dropColumn('stokkod');
            }
        });
    }
};

