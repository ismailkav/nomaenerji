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
        Schema::create('stokenvanter', function (Blueprint $table) {
            $table->id();
            $table->foreignId('depo_id')->constrained('depolar')->cascadeOnDelete();
            $table->string('stokkod', 100);
            $table->decimal('stokmiktar', 18, 4)->default(0);
            $table->timestamps();

            $table->unique(['depo_id', 'stokkod']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stokenvanter');
    }
};

