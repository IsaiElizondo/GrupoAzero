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
            $table->string('partial_folio', 50)->nullable();
            $table->string('smaterial_folio', 50)->nullable();
            $table->unsignedInteger('numero_pedido_ruta')->default(1);
            $table->string('cliente_codigo', 20);
            $table->string('cliente_nombre', 100)->nullable();
            $table->enum('estatus_pago', ['pagado', 'por_cobrar', 'credito'])->default('pagado');
            $table->enum('estatus_entrega', ['enrutado', 'entregado', 'entrega_no_exitosa'])->default('enrutado');
            $table->decimal('monto_por_cobrar', 10, 2)->default(0);
            $table->text('motivo')->nullable();
            $table->enum('tipo_subproceso', ['pedido', 'sp', 'sm'])->default('pedido');
            $table->unsignedBigInteger('subproceso_id')->nullable();
            $table->index(['tipo_subproceso', 'subproceso_id']);
            $table->timestamps();
            $table->unsignedBigInteger('partial_id')->nullable();
            $table->unsignedBigInteger('smaterial_id')->nullable();
            $table->unique(['ruta_id', 'order_id', 'partial_id', 'smaterial_id']);
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
