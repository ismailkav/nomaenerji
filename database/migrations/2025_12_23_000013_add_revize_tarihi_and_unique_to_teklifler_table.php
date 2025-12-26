<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('teklifler', function (Blueprint $table) {
            if (!Schema::hasColumn('teklifler', 'revize_tarihi')) {
                $table->date('revize_tarihi')->nullable()->after('revize_no');
            }
        });

        DB::table('teklifler')->whereNull('revize_no')->update(['revize_no' => '1']);

        DB::table('teklifler')
            ->select(['id', 'ekleme_tarihi'])
            ->whereNull('revize_tarihi')
            ->orderBy('id')
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    $date = $row->ekleme_tarihi ? Carbon::parse($row->ekleme_tarihi)->toDateString() : now()->toDateString();
                    DB::table('teklifler')->where('id', $row->id)->update(['revize_tarihi' => $date]);
                }
            });

        Schema::table('teklifler', function (Blueprint $table) {
            try {
                $table->dropUnique(['teklif_no']);
            } catch (Throwable $e) {
                // ignore
            }

            try {
                $table->unique(['teklif_no', 'revize_no']);
            } catch (Throwable $e) {
                // ignore
            }
        });
    }

    public function down(): void
    {
        Schema::table('teklifler', function (Blueprint $table) {
            try {
                $table->dropUnique(['teklif_no', 'revize_no']);
            } catch (Throwable $e) {
                // ignore
            }

            try {
                $table->unique('teklif_no');
            } catch (Throwable $e) {
                // ignore
            }

            if (Schema::hasColumn('teklifler', 'revize_tarihi')) {
                $table->dropColumn('revize_tarihi');
            }
        });
    }
};

