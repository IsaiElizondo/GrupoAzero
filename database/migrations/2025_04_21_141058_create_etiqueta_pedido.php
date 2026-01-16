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
        });
    }

    public function down(): void {
        Schema::dropIfExists('etiqueta_pedido');
    }
};
