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
        Schema::create('unidades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_unidad');
            $table->decimal('capacidad_kg', 10, 2)->nullable();
            $table->string('marca', 50)->nullable();
            $table->string('modelo', 50)->nullable();
            $table->string('numero_de_serie', 100)->nullable();
            $table->string('placas', 20)->nullable();
            $table->enum('tipo_epp', range('A', 'G'))->nullable();
            $table->string('epp', 200)->nullable();
            $table->enum('estatus', ['activo', 'mantenimiento', 'inactivo'])->default('activo');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidades');
    }
};
