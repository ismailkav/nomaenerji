<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('siparis_detaylari', function (Blueprint $table) {
            $table->decimal('gelen', 15, 3)->default(0)->after('miktar');
        });
    }

    public function down(): void
    {
        Schema::table('siparis_detaylari', function (Blueprint $table) {
            $table->dropColumn('gelen');
        });
    }
};

