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
        Schema::create('rutas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_ruta', 10)->unique();
            $table->unsignedInteger('numero_dia')->default(1);
            $table->dateTime('fecha_hora')->nullable();
            $table->foreignId('unidad_id')->nullable()->constrained('unidades')->nullOnDelete();
            $table->foreignId('chofer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('estatus_entrega', ['enrutado', 'entregado', 'entrega_no_exitosa'])->default('enrutado');
            $table->text('motivo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rutas');
    }
};
