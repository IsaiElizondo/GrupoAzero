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
            $table->string('marca', 50)->nullable();
            $table->string('modelo', 50)->nullable();
            $table->string('numero_de_serie', 100)->nullable();
            $table->string('placas', 20)->nullable();
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
