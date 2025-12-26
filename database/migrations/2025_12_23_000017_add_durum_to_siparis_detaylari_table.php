<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('siparis_detaylari', function (Blueprint $table) {
            $table->string('durum', 1)->default('A')->after('satir_aciklama');
            $table->index('durum');
        });
    }

    public function down(): void
    {
        Schema::table('siparis_detaylari', function (Blueprint $table) {
            $table->dropIndex(['durum']);
            $table->dropColumn('durum');
        });
    }
};

