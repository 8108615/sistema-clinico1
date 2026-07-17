<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use App\Models\OrdenLaboratorio;
use App\Models\Paciente;
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
        $pacientes = Paciente::all();
        $laboratorios = Laboratorio::where('estado', 'ACTIVO')->get();
        return view('admin.ordenlaboratorios.create', compact('pacientes', 'laboratorios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validamos los datos
        $request->validate([
            'paciente_id'        => 'required|exists:pacientes,id',
            'fecha_orden'        => 'required|date',
            'tipo_pago'          => 'required|string',
            'monto_recibido'     => 'required_if:tipo_pago,EFECTIVO|nullable|numeric|min:0',
            'codigo_transaccion' => 'required_if:tipo_pago,TRANSFERENCIA|nullable|string',
            'laboratorios'       => 'required|array|min:1',
        ]);

        // Obtenemos los laboratorios para calcular el total
        $laboratoriosSeleccionados = Laboratorio::whereIn('id', $request->laboratorios)->get();
        $total = $laboratoriosSeleccionados->sum('precio');

        // 2. Creamos la orden
        $orden = new OrdenLaboratorio();
        $orden->paciente_id        = $request->paciente_id;
        $orden->user_id            = Auth::id();
        $orden->fecha_orden        = $request->fecha_orden;
        $orden->total              = $total;
        $orden->tipo_pago          = $request->tipo_pago;

        // Asignamos según el tipo de pago
        if($request->tipo_pago === 'EFECTIVO') {
            $orden->monto_recibido = $request->monto_recibido;
        }

        if($request->tipo_pago === 'TRANSFERENCIA') {
            $orden->codigo_transaccion = $request->codigo_transaccion;
        }

        $orden->save();

        // 3. Registramos los detalles de la orden
        foreach ($laboratoriosSeleccionados as $lab) {
            \App\Models\DetalleOrdenLaboratorio::create([
                'orden_laboratorio_id' => $orden->id,
                'laboratorio_id'       => $lab->id,
                'precio'               => $lab->precio,
            ]);
        }

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
