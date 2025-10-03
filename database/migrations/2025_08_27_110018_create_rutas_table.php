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
            $table->string('numero_ruta', 6)->unique();
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->enum('estatus_pago', ['pendiente', 'pagado', 'cancelado'])->default('pendiente');
            $table->decimal('monto_por_cobrar', 10, 2)->default(0);
            $table->dateTime('fecha_hora')->nullable();
            $table->foreignId('unidad_id')->nullable()->constrained('unidades')->nullOnDelete();
            $table->foreignId('chofer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('estatus_entrega')->nullable();
            $table->text('motivo')->nullable();
            $table->timestamps();
            $table->index(['cliente_id']);
            $table->index(['unidad_id']);
            $table->index(['chofer_id']);
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
