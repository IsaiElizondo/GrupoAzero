<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->insert([
            'name' => 'Clientes',
            'description' => 'Lugar para asignar a todos los clientes'
        ]);
        // Tiene permisos para todo
        DB::table('departments')->insert([
            'name' => 'Administrador',
            'description' => 'El tipo de usuario que tiene permiso para todo'
        ]);
        // Pueden crear pedidos y poner anotaciones
        DB::table('departments')->insert([
            'name' => 'Ventas',
            'description' => 'Falta definir los permisos'
        ]);
        // Pueden cambiar estátus de pedidos recién creados, en ruta y de entregado
        DB::table('departments')->insert([
            'name' => 'Embarques',
            'description' => 'Falta definir los permisos'
        ]);
        // Pueden cambiar si está en fabricación y terminado de fabricar
        DB::table('departments')->insert([
            'name' => 'Fabricación',
            'description' => 'Falta definir los permisos'
        ]);
        // Subir foto de evidencia y cambiar cuando el estatus está "en ruta" -> "Entregado"
        DB::table('departments')->insert([
            'name' => 'Flotilla',
            'description' => 'Entregas a los clientes'
        ]);
        // Pueden subir archivos de órden de compra que solo compras y admin pueden ver
        DB::table('departments')->insert([
            'name' => 'Compras',
            'description' => 'Entregas a los clientes'
        ]);
    }
}
