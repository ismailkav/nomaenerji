<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('konsinye_fisleri', function (Blueprint $table) {
            $table->foreignId('depo_id')->nullable()->after('cariaciklama')->constrained('depolar')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('konsinye_fisleri', function (Blueprint $table) {
            $table->dropConstrainedForeignId('depo_id');
        });
    }
};

