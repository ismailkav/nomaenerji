<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fiyat_listeleri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('firm_id')->nullable()->constrained('firmalar')->nullOnDelete();
            $table->date('baslangic_tarihi');
            $table->date('bitis_tarihi')->nullable();
            $table->string('hazirlayan', 150)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fiyat_listeleri');
    }
};

