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
            $table->string('numero_ruta', 7)->unique();
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->string('invoice_number')->nullable();
            $table->string('invoice_number')->nullable();
            $table->enum('estatus_pago', ['por cobrar', 'credito', 'pagado'])->default('pendiente');
            $table->decimal('monto_por_cobrar', 10, 2)->default(0);
            $table->dateTime('fecha_hora')->nullable();
            $table->unsignedBigInteger('unidad_id')->nullable();
            $table->unsignedBigInteger('chofer_id')->nullable();
            $table->unisgnedBigInteger('estatus_entrega')->nullable();
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
