<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kurlar', function (Blueprint $table) {
            $table->id();
            $table->date('tarih');
            $table->string('currency_code', 3);
            $table->decimal('forex_buying', 15, 6)->nullable();
            $table->decimal('forex_selling', 15, 6)->nullable();
            $table->decimal('banknote_buying', 15, 6)->nullable();
            $table->decimal('banknote_selling', 15, 6)->nullable();
            $table->timestamps();

            $table->unique(['tarih', 'currency_code']);
            $table->index('currency_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kurlar');
    }
};

