<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('etiqueta_pedido', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pedido_id');
            $table->unsignedBigInteger('etiqueta_id');
            $table->timestamps();

            // Opcional: integridad referencial si después quieres foreign keys:
            // $table->foreign('pedido_id')->references('id')->on('orders')->onDelete('cascade');
            // $table->foreign('etiqueta_id')->references('id')->on('etiquetas')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('etiqueta_pedido');
    }
};
