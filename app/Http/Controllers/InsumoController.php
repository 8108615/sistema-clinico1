<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\Categoria;
use Illuminate\Http\Request;

class InsumoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');
        
        // Cargamos la relación 'categoria' para evitar consultas N+1
        $insumos = Insumo::with('categoria')
            ->when($buscar, function ($query) use ($buscar) {
                $query->where('nombre', 'like', "%{$buscar}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.insumos.index', compact('insumos', 'buscar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::all();
        return view('admin.insumos.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'categoria_id'  => 'required|exists:categorias,id',
            'nombre'        => 'required|string|max:255',
            'stock'         => 'required|integer|min:0',
            'stock_minimo'  => 'required|integer|min:0',
            'precio_compra' => 'required|numeric|min:0',
        ]);

        Insumo::create($request->all());

        return redirect()->route('admin.insumos.index')->with([
            'mensaje' => 'Insumo registrado exitosamente.',
            'icono'   => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Insumo $insumo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $insumo = Insumo::findOrFail($id);
        $categorias = Categoria::all();
        return view('admin.insumos.edit', compact('insumo', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $insumo = Insumo::findOrFail($id);
        
        $request->validate([
            'categoria_id'  => 'required|exists:categorias,id',
            'nombre'        => 'required|string|max:255',
            'stock'         => 'required|integer|min:0',
            'stock_minimo'  => 'required|integer|min:0',
            'precio_compra' => 'required|numeric|min:0',
        ]);
        
        $insumo->update($request->all());

        return redirect()->route('admin.insumos.index')->with([
            'mensaje' => 'Insumo actualizado correctamente.',
            'icono'   => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $insumo = Insumo::findOrFail($id);
        $insumo->delete();

        return redirect()->route('admin.insumos.index')->with([
            'mensaje' => 'Insumo eliminado correctamente.',
            'icono'   => 'success'
        ]);
    }
}
