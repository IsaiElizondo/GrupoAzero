<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class EtiquetasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $etiquetas = [

            [

                'nombre' => 'CLIENTE RECOGE',
                'descripcion' => 'El cliente pasará a recoger',
                'color' => '#0597e1'

            ],

            [

                'nombre' => 'INFORMACION PENDIENTE',
                'descripcion' => 'Falta información para el pedido',
                'color' => '#D32F2F'

            ],

            [

                'nombre' => 'MATERIAL PENDIENTE',
                'descripcion' => 'El material aún no esta disponible',
                'color' => '#F57C00'

            ],

            [

                'nombre' => 'PENDIENTE DE ENTREGA',
                'descripcion' => 'Pendiente de entrega al cliente',
                'color' => '#c0cd0e'

            ],

            [

                'nombre' => 'RESGUARDO CORTO',
                'descripcion' => 'Material en resguardo temporal',
                'color' => '#7B1FA2'

            ],

            [

                'nombre' => 'RESGUARDO MAYOR AL MES',
                'descripcion' => 'Material en reguardo prolongado',
                'color' => '#455a64'

            ],

            [

                'nombre' => 'N1',
                'descripcion' => 'Nave 1 San Pablo',
                'color' => '#4FC3F7'

            ],

            [

                'nombre' => 'N2',
                'descripcion' => 'Nave 2 San Pablo',
                'color' => '#81C784'

            ],

            [

                'nombre' => 'N3',
                'descripcion' => 'Nave 3 La Noria',
                'color' => '#EF5350'
                
            ],

            [

                'nombre' => 'N4',
                'descripcion' => 'Nave 4 La Noria',
                'color' => '#FFB74D'

            ],

            [

                'nombre' => 'PARCIALMENTE TERMINADO (SP)',
                'descripcion' => 'La orden de fabricación esta parcialmente terminado',
                'color' => '#FFF176'

            ],

            [

                'nombre' => 'PARCIALMENTE TERMINADO (LN)',
                'descripcion' => 'La orden de fabricación esta parcialmente terminado',
                'color' => '#BA68C8'

            ],

            [

                'nombre' => 'PEDIDO EN PAUSA (LN)',
                'descripcion' => 'El pedido se pausó',
                'color' => '#26A69A'

            ],

            [

                'nombre' => 'PEDIDO EN PAUSA (SP)',
                'descripcion' => 'El pedido se pausó',
                'color' => '#EC407A'

            ],

        ];

        foreach($etiquetas as $etiqueta){

            $existing = DB::table('etiquetas')->where('nombre', $etiqueta['nombre'])->first();

            if($existing){

                DB::table('etiquetas')->where('id', $existing->id)->update([
                    'descripcion' => $etiqueta['descripcion'],
                    'color' => $etiqueta['color'],
                    'updated_at' => now(),
                ]);

            } else{

                DB::table('etiquetas')->insert([
                    'nombre' => $etiqueta['nombre'],
                    'descripcion' => $etiqueta['descripcion'],
                    'color' => $etiqueta['color'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            }

        }

    }
}
