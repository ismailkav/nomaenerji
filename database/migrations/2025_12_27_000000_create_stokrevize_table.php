<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stokrevize', function (Blueprint $table) {
            $table->id();
            $table->string('stokkod', 100);
            $table->decimal('miktar', 15, 3);
            $table->foreignId('siparissatirid')->constrained('siparis_detaylari')->cascadeOnDelete();
            $table->string('durum', 50)->default('A');
            $table->timestamps();

            $table->unique('siparissatirid');
            $table->index('stokkod');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stokrevize');
    }
};

