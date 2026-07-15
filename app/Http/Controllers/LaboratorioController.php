<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use App\Models\Ajuste;
use Illuminate\Http\Request;

class LaboratorioController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $ajuste = Ajuste::first(); // <--- Obtener ajuste

        $laboratorios = Laboratorio::where('nombre', 'like', '%' . $buscar . '%')
            ->orWhere('codigo', 'like', '%' . $buscar . '%')
            ->paginate(10)
            ->withQueryString();

        return view('admin.laboratorios.index', compact('laboratorios', 'buscar', 'ajuste'));
    }

    public function create()
    {
        $ajuste = Ajuste::first();
        return view('admin.laboratorios.create', compact('ajuste'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // Solo necesitamos la regla básica para crear
            'nombre' => 'required|string|max:255|unique:laboratorios,nombre',
            'codigo' => 'required|string|max:50|unique:laboratorios,codigo',
            'precio' => 'required|numeric',
            'categoria'      => 'nullable|string|max:255',
            'descripcion'    => 'nullable|string',
            'dias_entrega'   => 'nullable|integer',
            'requiere_ayuno' => 'required',
            'estado' => 'nullable|in:ACTIVO,INACTIVO',
        ]);

        $data = $request->all();
        $data['requiere_ayuno'] = $request->has('requiere_ayuno') ? (bool)$request->requiere_ayuno : false;
        $data['estado'] = $request->has('estado') ? $request->estado : 'ACTIVO';

        Laboratorio::create($data);

        return redirect()->route('admin.laboratorios.index')
            ->with('mensaje', 'Laboratorio guardado correctamente')
            ->with('icono', 'success');
    }

    public function show(string $id)
    {
        $laboratorio = Laboratorio::findOrFail($id);
        return view('admin.laboratorios.show', compact('laboratorio'));
    }

    public function edit($id)
    {
        $laboratorio = \App\Models\Laboratorio::findOrFail($id);
        $ajuste = \App\Models\Ajuste::first();
        return view('admin.laboratorios.edit', compact('laboratorio', 'ajuste'));
    }

    public function update(Request $request, $id)
    {
        $laboratorio = Laboratorio::findOrFail($id);

        $validated = $request->validate([
            // Aquí pasamos el $id para que ignore el registro actual al validar unicidad
            'nombre'         => 'required|string|max:255|unique:laboratorios,nombre,' . $id,
            'codigo'         => 'required|string|max:50|unique:laboratorios,codigo,' . $id,
            'precio'         => 'required|numeric',
            'categoria'      => 'nullable|string|max:255',
            'descripcion'    => 'nullable|string',
            'dias_entrega'   => 'nullable|integer',
            'estado'         => 'nullable|in:ACTIVO,INACTIVO',
        ]);

        $validated['requiere_ayuno'] = $request->has('requiere_ayuno') ? (bool)$request->requiere_ayuno : false;
        $validated['estado']         = $request->has('estado') ? $request->estado : 'ACTIVO';

        $laboratorio->update($validated);

        return redirect()->route('admin.laboratorios.index')->with([
            'mensaje' => 'Laboratorio actualizado correctamente.',
            'icono'   => 'success'
        ]);
    }

    public function destroy(string $id)
    {
        $laboratorio = Laboratorio::findOrFail($id);
        $laboratorio->delete();

        return redirect()->route('admin.laboratorios.index')
            ->with('mensaje', 'Laboratorio eliminado correctamente')
            ->with('icono', 'success');
    }
}
