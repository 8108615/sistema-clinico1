<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $roles = Role::query();

        $roles = Role::where('name', 'like', '%' . $buscar . '%')
                  ->paginate(10)
                  ->withQueryString();

        return view('admin.roles.index', compact('roles', 'buscar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        $rol = new Role();
        $rol->name = $request->name;
        $rol->save();

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Rol guardado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rol = Role::find($id);
        return view('admin.roles.show', compact('rol'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rol = Role::find($id);
        return view('admin.roles.edit', compact('rol'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ]);

        $rol = Role::find($id);
        $rol->name = $request->name;
        $rol->save();

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Rol actualizado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rol = Role::find($id);
        $rol->delete();

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Rol eliminado correctamente')
            ->with('icono', 'success');
    }
    public function permisos($id)
    {
        $rol = Role::findOrFail($id);
        $permisos = Permission::all();

        // Agrupamos los permisos según el módulo correspondiente
        $permisosAgrupados = $permisos->groupBy(function ($permiso) {
            $nombre = strtolower($permiso->name);

            if (str_contains($nombre, 'ajuste')) return 'Ajustes';
            if (str_contains($nombre, 'rol')) return 'Roles';
            if (str_contains($nombre, 'usuario')) return 'Usuarios';
            if (str_contains($nombre, 'paciente')) return 'Pacientes';
            if (str_contains($nombre, 'consultorio')) return 'Consultorios';
            if (str_contains($nombre, 'consulta')) return 'Consultas';
            if (str_contains($nombre, 'laboratorio') || str_contains($nombre, 'orden') || str_contains($nombre, 'resultado')) return 'Laboratorios';
            if (str_contains($nombre, 'caja') || str_contains($nombre, 'pago')) return 'Cajas y Pagos';
            if (str_contains($nombre, 'categoria')) return 'Categorías';
            if (str_contains($nombre, 'insumo')) return 'Insumos';
            if (str_contains($nombre, 'historia')) return 'Historias Clínicas';
            if (str_contains($nombre, 'receta')) return 'Recetas Médicas';

            return 'Otros';
        });

        return view('admin.roles.permisos', compact('rol', 'permisosAgrupados'));
    }
    public function guardarPermisos(Request $request, string $id)
    {
        $rol = Role::findOrFail($id);

        // Sincroniza los permisos recibidos del formulario (espera un array de IDs)
        $rol->permissions()->sync($request->input('permisos', []));

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Permisos actualizados correctamente')
            ->with('icono', 'success');
    }
}
