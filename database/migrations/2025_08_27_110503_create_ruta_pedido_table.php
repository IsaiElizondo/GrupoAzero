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
        Schema::create('ruta_pedido', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruta_id')->constrained('rutas')->onDelete('cascade');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->unsignedInteger('numero_pedido_ruta')->default(1);
            $table->string('cliente_codigo', 20);
            $table->string('cliente_nombre', 100)->nullable();
            $table->enum('estatus_pago', ['pagado', 'por_cobrar', 'credito'])->default('pagado');
            $table->decimal('monto_por_cobrar', 10, 2)->default(0);
            $table->timestamps();
            $table->unique(['ruta_id', 'order_id']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruta_pedido');
    }
};
