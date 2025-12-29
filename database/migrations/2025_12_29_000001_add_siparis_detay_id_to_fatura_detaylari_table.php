<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('fatura_detaylari', function (Blueprint $table) {
            $table->foreignId('siparis_detay_id')
                ->nullable()
                ->after('urun_id')
                ->constrained('siparis_detaylari')
                ->nullOnDelete();

            $table->index('siparis_detay_id');
        });
    }

    public function down(): void
    {
        Schema::table('fatura_detaylari', function (Blueprint $table) {
            $table->dropForeign(['siparis_detay_id']);
            $table->dropIndex(['siparis_detay_id']);
            $table->dropColumn('siparis_detay_id');
        });
    }
};

