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
            $table->string('nombre_direccion', 50);
            $table->enum('tipo_residencia', ['industria', 'taller', 'residencial', 'obra'])->default('residencial');
            $table->string('direccion');
            $table->string('ciudad', 100)->nullable();
            $table->string('estado', 100)->nullable();
            $table->string('colonia', 100)->nullable();
            $table->string('codigo_postal', 20)->nullable();
            $table->string('celular', 20)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('nombre_recibe', 50)->nullable();
            $table->string('url_mapa')->nullable();
            $table->text('instrucciones')->nullable();
            $table->text('requerimientos_especiales')->nullable();
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
