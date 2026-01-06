<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('urun_detay_gruplari', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('urun_grup_id');
            $table->unsignedBigInteger('urun_alt_grup_id');
            $table->string('ad', 150);
            $table->timestamps();

            $table->index(['urun_grup_id', 'urun_alt_grup_id', 'ad']);

            $table->foreign('urun_grup_id')
                ->references('id')
                ->on('urun_kategorileri')
                ->cascadeOnDelete();

            $table->foreign('urun_alt_grup_id')
                ->references('id')
                ->on('urun_alt_gruplari')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('urun_detay_gruplari');
    }
};

