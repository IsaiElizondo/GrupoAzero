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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->foreignId('cliente_direccion_id')->nullable()->constrained('clientes_direcciones')->nullOnDelete();

            $table->string('nombre_cliente')->nullable();
            $table->string('direccion')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('estado')->nullable();
            $table->string('codigo_postal', 20)->nullable();
            $table->string('celular', 20)->nullable();
            $table->string('nombre_recibe',100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']);
            $table->dropForeign(['cliente_direccion_id']);

            $table->dropColumn([
                'cliente_id',
                'cliente_direccion_id',
                'nombre_cliente',
                'direccion',
                'ciudad',
                'estado', 
                'codigo_postal',
                'celular',
                'nombre_recibe'
            ]);
        });
    }
};
