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
        Schema::create('konsinye_fis_satirlari', function (Blueprint $table) {
            $table->id();
            $table->foreignId('konsinye_fis_id')->constrained('konsinye_fisleri')->cascadeOnDelete();

            $table->string('stokkod', 100)->nullable();
            $table->string('stokaciklama', 255)->nullable();
            $table->decimal('miktar', 18, 4)->default(0);
            $table->decimal('iade_miktar', 18, 4)->default(0);
            $table->string('durum', 50)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konsinye_fis_satirlari');
    }
};

