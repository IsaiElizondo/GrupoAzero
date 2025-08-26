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
        Schema::create('devolucion_parcial_archivos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('devolucion_parcial_id');
            $table->string('file');
            $table->timestamps();

            $table->foreign('devolucion_parcial_id')
                ->references('id')
                ->on('devoluciones_parciales')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devolucion_parcial_archivos');
    }
};
