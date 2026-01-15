<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sepomex', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_postal', 5)->index();
            $table->string('estado', 100);
            $table->string('municipio', 100);
            $table->string('colonia', 150);
            $table->string('tipo', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sepomex');
    }
};
