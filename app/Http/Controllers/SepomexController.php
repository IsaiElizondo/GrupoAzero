<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SepomexController extends Controller
{
    public function buscar_cp(Request $request)
    {
        $CodigoPostal = trim($request->get('codigo_postal'));


        if (!preg_match('/^\d{5}$/', $CodigoPostal)){
            return response()->json([
                'ok' => false,
                'mensaje' => 'Código postal inválido'
            ]);
        }

        $registros = DB::table('sepomex')
            ->where('codigo_postal', $CodigoPostal)
            ->get();

        if ($registros->isEmpty()){
            return response()->json([
                'ok' => false,
                'mensaje' => 'Código postal no encontrado'
            ]);
        }

        return response()->json([
            'ok' => true,
            'estado' => $registros->first()->estado,
            'ciudad' => $registros->first()->municipio,
            'colonias' => $registros->pluck('colonia')->unique()->values()
        ]);
    }
}
