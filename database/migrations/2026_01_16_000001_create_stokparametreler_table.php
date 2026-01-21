<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stokparametreler', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('parametre_no');
            $table->string('deger', 150);
            $table->timestamps();

            $table->index('parametre_no');
            $table->unique(['parametre_no', 'deger']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stokparametreler');
    }
};

