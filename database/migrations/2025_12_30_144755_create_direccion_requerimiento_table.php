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
        Schema::create('direccion_requerimiento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('orders')->cascadeOnDelete();
            $table->foreignId('cliente_direccion_id')->nullable()->constrained('clientes_direcciones')->cascadeOnDelete();
            $table->foreignId('requerimiento_especial_id')->constrained('requerimientos_especiales')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['order_id', 'requerimiento_especial_id'], 'order_req_unique');
            $table->unique(['cliente_direccion_id', 'requerimiento_especial_id'], 'dir_req_unique');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes_direcciones_requerimientos');
    }
};
