<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stok_fis_satirlari', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stok_fis_id')->constrained('stok_fisleri')->cascadeOnDelete();

            $table->string('stokkod', 100)->nullable();
            $table->string('stokaciklama', 255)->nullable();
            $table->decimal('miktar', 18, 4)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_fis_satirlari');
    }
};

