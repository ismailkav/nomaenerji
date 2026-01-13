<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('montaj_gruplari', function (Blueprint $table) {
            $table->id();
            $table->string('kod', 150);
            $table->unsignedBigInteger('urun_detay_grup_id')->nullable();
            $table->timestamps();

            $table->index(['kod']);
            $table->index(['urun_detay_grup_id']);

            $table->foreign('urun_detay_grup_id')
                ->references('id')
                ->on('urun_detay_gruplari')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('montaj_gruplari');
    }
};

