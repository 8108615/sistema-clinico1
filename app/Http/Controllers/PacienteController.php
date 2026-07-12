<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        // Consultamos los pacientes, incluyendo los eliminados lógicamente (softDeletes)
        $pacientes = Paciente::query()
            ->when($buscar, function ($query) use ($buscar) {
                $query->where('nombres', 'like', '%' . $buscar . '%')
                      ->orWhere('apellidos', 'like', '%' . $buscar . '%')
                      ->orWhere('ci', 'like', '%' . $buscar . '%');
            })
            ->latest() // Para que los últimos registrados salgan primero
            ->paginate(10)
            ->withQueryString();

        return view('admin.pacientes.index', compact('pacientes', 'buscar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pacientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'ci' => 'required|unique:pacientes,ci|string|max:20',
            'estado' => 'required|in:ACTIVO,INACTIVO',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|in:MASCULINO,FEMENINO',
            'celular' => 'required|string|max:20',
            'correo' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:500',
            'grupo_sanguineo' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'peso' => 'nullable|numeric|min:0|max:999.99',
            'talla' => 'nullable|numeric|min:0|max:999.99',
            'alergias' => 'nullable|string',
            'contacto_emergencia' => 'required|string|max:255',
            'parentesco_emergencia' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
        ], [
            'ci.unique' => 'El número de CI ya está registrado en el sistema.',
            'genero.in' => 'Seleccione un género válido.',
        ]);

        try {
            \App\Models\Paciente::create($request->all());

            return redirect()->route('admin.pacientes.index')
                ->with('mensaje', 'Paciente registrado exitosamente.')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Ocurrió un error al registrar: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Paciente $paciente, $id)
    {
        $paciente = \App\Models\Paciente::findOrFail($id);
        return view('admin.pacientes.show', compact('paciente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Paciente $paciente, $id)
    {
        $paciente = Paciente::findOrFail($id);
        return view('admin.pacientes.edit', compact('paciente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $paciente = \App\Models\Paciente::findOrFail($id);

        $request->validate([
            'nombres'               => 'required|string|max:255',
            'apellidos'             => 'required|string|max:255',
            'ci'                    => 'required|string|max:255|unique:pacientes,ci,' . $paciente->id,
            'fecha_nacimiento'      => 'required|date',
            'genero'                => 'required|in:MASCULINO,FEMENINO',
            'celular'               => 'required|string|max:255',
            'correo'                => 'nullable|email|max:255',
            'direccion'             => 'nullable|string|max:255',
            'grupo_sanguineo'       => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'peso'                  => 'nullable|numeric|between:0,999.99',
            'talla'                 => 'nullable|numeric|between:0,999.99',
            'alergias'              => 'nullable|string',
            'contacto_emergencia'   => 'required|string|max:255',
            'parentesco_emergencia' => 'required|string|max:255',
            'observaciones'         => 'nullable|string',
            'estado'                => 'required|in:ACTIVO,INACTIVO',
        ], [
            'ci.unique'             => 'El número de CI ya está registrado en el sistema.',
            'genero.in'             => 'Seleccione un género válido.',
            'estado.in'             => 'Seleccione un estado válido.',
        ]);

        try {
            // Actualizamos usando todos los campos validados
            $paciente->update($request->all());

            return redirect()->route('admin.pacientes.index')
                ->with('mensaje', 'Paciente actualizado exitosamente.')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Ocurrió un error al actualizar: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paciente $paciente, $id)
    {
        $paciente = Paciente::findOrFail($id);
        $paciente->delete();

        return redirect()->route('admin.pacientes.index')
                ->with('mensaje', 'Paciente eliminado correctamente.')
                ->with('icono', 'success');
    }
}
