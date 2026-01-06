<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usertable', function (Blueprint $table) {
            $table->id();
            $table->string('kullanicikod');
            $table->string('sutun');
            $table->boolean('durum')->default(true);
            $table->unsignedInteger('sirano')->default(0);
            $table->timestamps();

            $table->unique(['kullanicikod', 'sutun']);
            $table->index(['kullanicikod', 'durum']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usertable');
    }
};

