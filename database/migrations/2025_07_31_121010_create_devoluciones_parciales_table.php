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
        Schema::create('devoluciones_parciales', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('folio', 100);
            $table->enum('motivo', ['Error del Cliente', 'Error Interno']);
            $table->string('descripcion', 300)->nullable();
            $table->enum('tipo', ['total', 'parcial']);
            $table->string('file');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->OnDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devoluciones_parciales');
    }
};
