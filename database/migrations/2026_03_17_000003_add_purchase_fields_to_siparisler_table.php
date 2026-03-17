<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('siparisler', function (Blueprint $table) {
            if (!Schema::hasColumn('siparisler', 'siparis_kanali')) {
                $table->string('siparis_kanali', 10)
                    ->nullable()
                    ->after('satis_temsilcisi');
            }

            if (!Schema::hasColumn('siparisler', 'tedarikci_siparis_no')) {
                $table->string('tedarikci_siparis_no', 100)
                    ->nullable()
                    ->after('siparis_kanali');
            }
        });
    }

    public function down(): void
    {
        Schema::table('siparisler', function (Blueprint $table) {
            if (Schema::hasColumn('siparisler', 'tedarikci_siparis_no')) {
                $table->dropColumn('tedarikci_siparis_no');
            }

            if (Schema::hasColumn('siparisler', 'siparis_kanali')) {
                $table->dropColumn('siparis_kanali');
            }
        });
    }
};
