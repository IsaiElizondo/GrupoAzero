<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Unidad;

class UnidadesController extends Controller
{
    
    public function index(){
        $unidades = Unidad::with('chofer')->get();
        return view('unidades.index', compact('unidades'));
    }

    public function create(){
        $choferes = User::where('department_id', 6)->get();
        return view('unidades.create', compact('choferes'));
    }

    public function store(Request $request){
        $request->validate([
            'nombre_unidad' => 'required|string|max:255',
            'chofer_id' => 'nullable|exists_users,id',
        ]);

        Unidad::create($request->only('nombre_unidad', 'chofer_id'));

        return redirect()->route('unidades.index')->with('succes', 'Unidad creada correctamente');
    }

    public function show($id){
        $unidad = Unidad::with('chofer')->findOrFail($id);
        return view('unidades.show', compact('unidad'));
    }

    public function edit($id){
        $unidad = Unidad::findOrFail($id);
        $choferes = User::where('department_id', 6)->get();
        return view('unidades.edit', compact('unidad', choferes));
    }

    public function update(Request $request, $id){
        
        $unidad = Unidad::findOrFail($id);

        $request->validate([
            'nombre_unidad' => 'required|string|max:255',
            'chofer_id' => 'nullable|exist:users,id',
        ]);

        $unidad->updated($request->only('nombre_unidad', 'chofer_id'));

        return redirect()->route('unidades.index')->with('succes', 'Unidad actualizada correctamente');

    }

    public function destroy($id){

        $unidad = Unidad::findOrFail($id);
        $unidad->delete();

        return redirect()->route('unidades.index')->with('success', 'Unidad eliminada correctamente');

    }

}
