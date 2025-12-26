<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('firmalar', function (Blueprint $table) {
            $table->id();
            $table->string('carikod', 50)->unique();
            $table->string('cariaciklama', 255);
            $table->string('adres1', 255)->nullable();
            $table->string('adres2', 255)->nullable();
            $table->string('il', 100)->nullable();
            $table->string('ilce', 100)->nullable();
            $table->string('ulke', 100)->nullable();
            $table->string('telefon', 50)->nullable();
            $table->string('mail', 150)->nullable();
            $table->string('web_sitesi', 150)->nullable();
            $table->decimal('iskonto1', 5, 2)->nullable()->default(0);
            $table->decimal('iskonto2', 5, 2)->nullable()->default(0);
            $table->decimal('iskonto3', 5, 2)->nullable()->default(0);
            $table->decimal('iskonto4', 5, 2)->nullable()->default(0);
            $table->decimal('iskonto5', 5, 2)->nullable()->default(0);
            $table->decimal('iskonto6', 5, 2)->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('firmalar');
    }
};

