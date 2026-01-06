<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('projeler', function (Blueprint $table) {
            if (!Schema::hasColumn('projeler', 'iskonto1')) {
                $table->decimal('iskonto1', 8, 4)->default(0)->after('pasif');
            }
            if (!Schema::hasColumn('projeler', 'iskonto2')) {
                $table->decimal('iskonto2', 8, 4)->default(0)->after('iskonto1');
            }
        });
    }

    public function down(): void
    {
        Schema::table('projeler', function (Blueprint $table) {
            if (Schema::hasColumn('projeler', 'iskonto2')) {
                $table->dropColumn('iskonto2');
            }
            if (Schema::hasColumn('projeler', 'iskonto1')) {
                $table->dropColumn('iskonto1');
            }
        });
    }
};

