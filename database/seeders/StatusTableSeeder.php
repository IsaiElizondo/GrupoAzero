<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1.- Ventas genera
        DB::table('statuses')->insert([
            'name' => 'Pedido generado',
            'description' => 'Pedido generado de forma inicial'
        ]);
        // 2.- Embarques puede cambiar a recibido
        DB::table('statuses')->insert([
            'name' => 'Recibido por embarques',
            'description' => 'El pedido pasa al departamento de embarques'
        ]);
        // 3.- Fabricación y embarques son los únicos que pueden cambiar estatus a "En fabricación"
        DB::table('statuses')->insert([
            'name' => 'En fabricación',
            'description' => 'Cuando se tiene que fabricar el pedido'
        ]);
        // 4.- Fabricación y embarques son los únicos que pueden cambiar estatus a "En fabricación"
        DB::table('statuses')->insert([
            'name' => 'Fabricado',
            'description' => 'Estatus que se asigna cuando se termina de fabricar'
        ]);
        // 5.- Sólo Embarques puede cambiar a en ruta
        DB::table('statuses')->insert([
            'name' => 'En Puerta',
            'description' => 'El pedido se encuentra en ruta'
        ]);
        // 6.- Flotilla y embarques puede cambiar a entregado
        DB::table('statuses')->insert([
            'name' => 'Entregado',
            'description' => 'El pedido se ha entregado'
        ]);
        // 7.- cancelacion
        DB::table('statuses')->insert([
            'name' => 'Cancelado',
            'description' => 'El pedido se cancela'
        ]);
        // 8.- Refacturación
        DB::table('statuses')->insert([
            'name' => 'Refacturación',
            'description' => 'El pedido se cancela para su posterior facturación.'
        ]);
        // 9.- Devolución
        DB::table('statuses')->insert([
            'name' => 'Devolución',
            'description' => 'El pedido se cancela para su posterior Devolución.'
        ]);
    }
}
