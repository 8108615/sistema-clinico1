<?php

namespace App\Http\Controllers;

use App\Models\OrdenLaboratorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdenLaboratorioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        // Buscamos órdenes filtrando por el nombre del paciente
        $ordenes = OrdenLaboratorio::query()
            ->with('paciente') // Cargamos la relación
            ->when($buscar, function ($query, $buscar) {
                $query->whereHas('paciente', function ($q) use ($buscar) {
                    $q->where('nombres', 'like', '%' . $buscar . '%')
                      ->orWhere('apellidos', 'like', '%' . $buscar . '%')
                      ->orWhere('ci', 'like', '%' . $buscar . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.ordenlaboratorios.index', compact('ordenes', 'buscar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ordenlaboratorios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validamos los datos
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'fecha_orden' => 'required|date',
            'estado_pago' => 'required|string',
            // El total lo calcularemos en el siguiente paso con el detalle,
            // por ahora lo dejamos como requerido o con valor inicial 0.
        ]);

        // 2. Creamos la orden
        $orden = new OrdenLaboratorio();
        $orden->paciente_id = $request->paciente_id;
        $orden->user_id     = Auth::id(); // Guardamos el usuario que está realizando la orden
        $orden->fecha_orden = $request->fecha_orden;
        $orden->total       = 0; // Se actualizará al agregar detalles
        $orden->estado_pago = $request->estado_pago;
        $orden->save();

        // 3. Redireccionamos con el mensaje de éxito
        return redirect()->route('admin.orden_laboratorios.index')
            ->with('mensaje', 'Orden de laboratorio registrada correctamente')
            ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(OrdenLaboratorio $ordenLaboratorio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrdenLaboratorio $ordenLaboratorio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrdenLaboratorio $ordenLaboratorio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrdenLaboratorio $ordenLaboratorio)
    {
        //
    }
}
