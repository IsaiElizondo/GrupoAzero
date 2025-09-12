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
        Schema::create('clientes_direcciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->string('direccion');
            $table->string('ciudad')->nullable();
            $table->string('estado')->nullable();
            $table->string('codigo_postal')->nullable();
            $table->string('url_map')->nullable();
            $table->text('intrucciones')->nullable();
            $table->timestamps();

            $table->index('cliente_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes_direcciones');
    }
};
