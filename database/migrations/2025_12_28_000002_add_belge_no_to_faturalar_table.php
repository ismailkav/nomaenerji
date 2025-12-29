<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('faturalar', function (Blueprint $table) {
            $table->string('belge_no', 50)->nullable()->after('siparis_no');
            $table->index('belge_no');
        });
    }

    public function down(): void
    {
        Schema::table('faturalar', function (Blueprint $table) {
            $table->dropIndex(['belge_no']);
            $table->dropColumn('belge_no');
        });
    }
};

