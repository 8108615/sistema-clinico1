<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Ajuste;
use Illuminate\Http\Request;

class ConsultaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');
        $ajuste = \App\Models\Ajuste::first();

        $consultas = \App\Models\Consulta::with(['paciente', 'consultorio', 'usuario'])
            ->when($buscar, function ($query) use ($buscar) {
                $query->whereHas('paciente', function ($q) use ($buscar) {
                    $q->where('nombre', 'like', "%{$buscar}%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('admin.consultas.index', compact('consultas', 'buscar', 'ajuste'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pacientes = \App\Models\Paciente::all();
        $consultorios = \App\Models\Consultorio::where('estado', 'ACTIVO')->get();
        $medicos = \App\Models\User::all();

        return view('admin.consultas.create', compact('pacientes', 'consultorios', 'medicos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validaciones
        $validated = $request->validate([
            'paciente_id'    => 'required|exists:pacientes,id',
            'consultorio_id' => 'required|exists:consultorios,id',
            'usuario_id'     => 'required|exists:users,id',
            'fecha_atencion' => 'required|date',
            'precio'         => 'required|numeric|min:0',
        ], [
            'paciente_id.required'    => 'Debe seleccionar un paciente.',
            'consultorio_id.required' => 'Debe seleccionar un consultorio.',
            'usuario_id.required'     => 'Debe seleccionar un médico.',
            'precio.required'         => 'El precio es obligatorio.',
        ]);

        // 2. Validación de Caja
        $cajaAbierta = \App\Models\Caja::where('estado', 'ABIERTA')->first();

        if (!$cajaAbierta) {
            return redirect()->back()->with([
                'mensaje' => 'No hay una caja abierta. Por favor, abra una caja antes de registrar.',
                'icono'   => 'error'
            ]);
        }

        // 3. Asignación y guardado
        $validated['caja_id'] = $cajaAbierta->id;
        \App\Models\Consulta::create($validated);

        return redirect()->route('admin.consultas.index')->with([
            'mensaje' => 'La consulta médica se registró exitosamente.',
            'icono'   => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Consulta $consulta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Consulta $consulta, $id)
    {
        $consulta = \App\Models\Consulta::findOrFail($id);
        $pacientes = \App\Models\Paciente::all();
        $consultorios = \App\Models\Consultorio::where('estado', 'ACTIVO')->get();
        $medicos = \App\Models\User::all();

        return view('admin.consultas.edit', compact('consulta', 'pacientes', 'consultorios', 'medicos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Consulta $consulta, $id)
    {
        $consulta = \App\Models\Consulta::findOrFail($id);

        $validated = $request->validate([
            'paciente_id'    => 'required|exists:pacientes,id',
            'consultorio_id' => 'required|exists:consultorios,id',
            'usuario_id'     => 'required|exists:users,id',
            'fecha_atencion' => 'required|date',
            'precio'         => 'required|numeric|min:0',
        ]);

        $consulta->update($validated);

        return redirect()->route('admin.consultas.index')->with([
            'mensaje' => 'La consulta se actualizó correctamente.',
            'icono'   => 'success'
        ]);
    }

    public function ticket($id)
    {
        $consulta = \App\Models\Consulta::with(['paciente', 'consultorio', 'usuario'])->findOrFail($id);
        $ajuste = Ajuste::first(); // Obtenemos los datos de la clínica

        return view('admin.consultas.ticket', compact('consulta', 'ajuste'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consulta $consulta, $id)
    {
        $consulta = \App\Models\Consulta::findOrFail($id);
        $consulta->delete();

        return redirect()->route('admin.consultas.index')->with([
            'mensaje' => 'Consulta eliminada correctamente.',
            'icono'   => 'success'
        ]);
    }
}
