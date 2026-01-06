<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RequerimientosEspecialesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Requerimientos = [

            [
                'nombre' => 'Permiso para ingresar',
                'slug' => 'permiso_ingresar',
            ],

            [
                'nombre' => 'Entradas estrechas',
                'slug' => 'entradas_estrechas',
            ],

            [
                'nombre' => 'Entrada cerrada',
                'slug' => 'entrada_cerrada',
            ],

            [
                'nombre' => 'Entrada rejada',
                'slug' => 'entrada_rejada',
            ],

            [
                'nombre' => 'Altura de entrada menor a 3.50 mtrs',
                'slug' => 'entrada_menor_350',
            ],

            [
                'nombre' => 'No reciben los sabados',
                'slug' => 'no_reciben_sabados',
            ],

            [
                'nombre' => 'Se requiere Seguro Social',
                'slug' => 'seguro_social',
            ],

            [
                'nombre' => ' Se requiere equipo de seguridad',
                'slug' => 'equipo_seguridad',
            ],

        ];

        foreach($Requerimientos as $Req){
            DB::table('requerimientos_especiales')->updateOrInsert(
                ['slug' => $Req['slug']],
                [

                    'nombre' => $Req['nombre'],
                    'activo' => true,
                    'updated_at' => now(),
                    'created_at' => now(),

                ]
            );
        }
    }
}
