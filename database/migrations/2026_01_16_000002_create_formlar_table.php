<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('formlar', function (Blueprint $table) {
            $table->id();
            $table->string('ekran', 20);
            $table->string('dosya_ad', 255);
            $table->string('gorunen_isim', 255);
            $table->timestamps();

            $table->index('ekran');
            $table->unique(['ekran', 'dosya_ad']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formlar');
    }
};

