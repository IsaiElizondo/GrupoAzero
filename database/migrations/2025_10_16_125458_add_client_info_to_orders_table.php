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

            $table->string('nombre_direccion')->nullable();
            $table->string('nombre_cliente')->nullable();
            $table->string('direccion')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('estado')->nullable();
            $table->string('codigo_postal', 20)->nullable();
            $table->string('celular', 20)->nullable();
            $table->string('nombre_recibe',100)->nullable();
            $table->string('url_mapa')->nullable();
            $table->string('instrucciones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {

            //Lista de posibles foreign keys
            $foreignKeys = [
                'orders_cliente_id_foreign',
                'orders_cliente_direccion_id_foreign',
            ];

            //Intentar eliminar cada foreign key si existe
            foreach ($foreignKeys as $fkName) {
                try {
                    DB::statement("ALTER TABLE `orders` DROP FOREIGN KEY `$fkName`");
                } catch (\Exception $e) {
                    // Ignora si no existe la FK
                }
            }

            //Lista de columnas a eliminar (solo si existen)
            $columns = [
                'cliente_id',
                'cliente_direccion_id',
                'nombre_direccion',
                'nombre_cliente',
                'direccion',
                'ciudad',
                'estado',
                'codigo_postal',
                'celular',
                'nombre_recibe',
                'url_mapa',
                'instrucciones',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
