<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');
        
        $categorias = Categoria::when($buscar, function ($query) use ($buscar) {
                $query->where('nombre', 'like', "%{$buscar}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.categorias.index', compact('categorias', 'buscar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:255|unique:categorias,nombre']);
        
        Categoria::create($request->all());

        return redirect()->route('admin.categorias.index')->with([
            'mensaje' => 'Categoría creada exitosamente.',
            'icono'   => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('admin.categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);
        
        $request->validate(['nombre' => 'required|string|max:255|unique:categorias,nombre,' . $id]);
        
        $categoria->update($request->all());

        return redirect()->route('admin.categorias.index')->with([
            'mensaje' => 'Categoría actualizada correctamente.',
            'icono'   => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();

        return redirect()->route('admin.categorias.index')->with([
            'mensaje' => 'Categoría eliminada correctamente.',
            'icono'   => 'success'
        ]);
    }
}
