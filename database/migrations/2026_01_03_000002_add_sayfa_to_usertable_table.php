<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usertable', function (Blueprint $table) {
            $table->string('sayfa')->default('offers')->after('kullanicikod');
        });

        Schema::table('usertable', function (Blueprint $table) {
            $table->dropUnique(['kullanicikod', 'sutun']);
            $table->unique(['kullanicikod', 'sayfa', 'sutun']);
            $table->dropIndex(['kullanicikod', 'durum']);
            $table->index(['kullanicikod', 'sayfa', 'durum']);
        });
    }

    public function down(): void
    {
        Schema::table('usertable', function (Blueprint $table) {
            $table->dropUnique(['kullanicikod', 'sayfa', 'sutun']);
            $table->dropIndex(['kullanicikod', 'sayfa', 'durum']);
            $table->unique(['kullanicikod', 'sutun']);
            $table->index(['kullanicikod', 'durum']);
            $table->dropColumn('sayfa');
        });
    }
};

