<?php

namespace App\Http\Controllers;

use App\Models\Consultorio;
use Illuminate\Http\Request;

class ConsultorioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $consultorios = Consultorio::query()
            ->when($buscar, function ($query, $buscar) {
                $query->where('nombre', 'LIKE', '%' . $buscar . '%')
                      ->orWhere('especialidad', 'LIKE', '%' . $buscar . '%');
            })
            ->latest()
            ->paginate(10);

        return view('admin.consultorios.index', compact('consultorios', 'buscar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.consultorios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'              => 'required|string|max:255',
            'ubicacion'           => 'required|string|max:255',
            'telefono'            => 'nullable|string|max:50',
            'especialidad'        => 'required|string|max:255',
            'capacidad_consultas' => 'required|integer|min:1',
            'estado'              => 'required|in:ACTIVO,INACTIVO',
        ]);

        Consultorio::create($validated);

        return redirect()->route('admin.consultorios.index')->with([
            'mensaje' => 'El consultorio se registró exitosamente',
            'icono'   => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Consultorio $consultorio, $id)
    {
        $consultorio = \App\Models\Consultorio::findOrFail($id);
        return view('admin.consultorios.show', compact('consultorio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Consultorio $consultorio, $id)
    {
         $consultorio = Consultorio::findOrFail($id);
        return view('admin.consultorios.edit', compact('consultorio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Consultorio $consultorio, $id)
    {
        $consultorio = \App\Models\Consultorio::findOrFail($id);

        // Validamos los datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'especialidad' => 'required|string|max:255',
            'telefono' => 'required|numeric',
            'capacidad_consultas' => 'required|integer',
            'estado' => 'required|string',
        ]);

        // Actualizamos
        $consultorio->update($request->all());

        return redirect()->route('admin.consultorios.index')
                ->with('mensaje', 'Consultorio actualizado exitosamente.')
                ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consultorio $consultorio, $id)
    {
        $consultorio = \App\Models\Consultorio::findOrFail($id);
        $consultorio->delete();

        return redirect()->route('admin.consultorios.index')
                    ->with('mensaje', 'Consultorio eliminado correctamente.')
                    ->with('icono', 'success');
    }
}
