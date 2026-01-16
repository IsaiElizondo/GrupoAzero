<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Unidad;
use Throwable;

class UnidadesController extends Controller
{
    
    public function index(){
        
        $unidades = Unidad::orderByDesc('id')->get();
        return view('unidades.index', compact('unidades'));
        
    }

    public function create(){
        
        return view('unidades.create');
        
    }

    public function store(Request $request){
        
        $validated = $request->validate([
            'nombre_unidad' => ['required', 'string', 'max:255'],
            'capacidad_kg' => ['nullable', 'numeric', 'min:0'],
            'marca' => ['nullable', 'string', 'max:50'],
            'modelo' => ['nullable', 'string', 'max:50'],
            'numero_de_serie' => ['nullable', 'string', 'max:100'],
            'placas' => ['nullable', 'string', 'max:20'],
            'tipo_epp' => ['nullable', Rule::in(range('A', 'G'))],
            'epp' => ['nullable', 'string', 'max:200'],
            'estatus' => ['required', 'in:activo,mantenimiento,inactivo'],
        ]);

        DB::beginTransaction();
        try{

            Unidad::create([
                'nombre_unidad' => $validated['nombre_unidad'],
                'capacidad_kg' => $validated['capacidad_kg'] ?? null,
                'marca' => $validated['marca'] ?? null,
                'modelo' => $validated['modelo'] ?? nul,
                'numero_de_serie' => $validated['numero_de_serie'] ?? null,
                'placas' => $validated['placas'] ?? null,
                'tipo_epp' => $validated['tipo_epp'] ?? null,
                'epp' => $validated['epp'] ?? null,
                'estatus' => $validated['estatus'] ?? 'activo',

            ]);

            DB::commit();
            return redirect()->route('unidades.index')->with('success', 'Unidad creada correctamente');
            
        }catch(Throwable $e){
            DB::rollBack();
            report($e);
            return back()->withErrors('Ocurrio un errror al crear la unidad')->withInput();
        }

    }

    public function show($id){

        $unidad = Unidad::findOrFail($id);
        return view('unidades.show', compact('unidad'));
        
    }

    public function edit($id){

        $unidad = Unidad::findOrFail($id);
        return view('unidades.edit', compact('unidad'));
   
    }

    public function update(Request $request, $id){
        
        $validated = $request->validate([
            'nombre_unidad' => ['required', 'string', 'max:255'],
            'capacidad_kg' => ['nullable', 'numeric', 'min:0'],
            'marca' => ['nullable', 'string', 'max:50'],
            'modelo' => ['nullable', 'string', 'max:50'],
            'numero_de_serie' => ['nullable', 'string', 'max:100'],
            'placas' => ['nullable', 'string', 'max:20'],
            'tipo_epp' => ['nullable', Rule::in(range('A', 'G'))],
            'epp' => ['nullable', 'string', 'max:200'],
            'estatus' => ['nullable', 'in:activo,mantenimiento,inactivo'],
        ]);

        DB::beginTransaction();
        try{
            $unidad = Unidad::findOrFail($id);
            $unidad->update([
                'nombre_unidad' => $validated['nombre_unidad'],
                'capacidad_kg' => $validated['capacidad_kg'] ?? null,
                'marca' => $validated['marca'] ?? null,
                'modelo' => $validated['modelo'] ?? null,
                'numero_de_serie' => $validated['numero_de_serie'] ?? null,
                'placas' => $validated['placas'] ?? null,
                'tipo_epp' => $validated['tipo_epp'] ?? null,
                'epp' => $validated['epp'] ?? null,
                'estatus' => $validated['estatus'] ?? 'activo',
            ]);

            DB::commit();
            return redirect()->route('unidades.index')->with('success', 'Unidad actualizada correctamente');

        }catch(Throwable $e){

            DB::rollBack();
            report($e);
            return back()->withErrors("Ocurrio un error al actualizar unidad")->withInput();

        }

    }

    public function destroy($id){
        
        $unidad = Unidad::findOrFail($id);
        $unidad->delete();

        return redirect()->route('unidades.index')->with('success', 'Unidad eliminada correctamente');

    }

}
