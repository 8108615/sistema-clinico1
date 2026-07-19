<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', now()->endOfMonth()->format('Y-m-d'));

        // Filtramos las cajas entre esas fechas
        $cajas = Caja::with('user')
            ->whereBetween('fecha_apertura', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->latest()
            ->paginate(10);

        $cajaAbierta = Caja::where('estado', 'ABIERTA')->first();

        // NUEVO: Cálculos para el cierre de caja
        $totalConsultas = 0;
        $totalLaboratorios = 0;
        $totalGeneral = 0;

        if ($cajaAbierta) {
            $totalConsultas = \App\Models\Consulta::where('caja_id', $cajaAbierta->id)->sum('precio');
            $totalLaboratorios = \App\Models\OrdenLaboratorio::where('caja_id', $cajaAbierta->id)->sum('total');

            // Sumamos el monto inicial aquí
            $totalGeneral = $cajaAbierta->monto_inicial + $totalConsultas + $totalLaboratorios;
        }

        return view('admin.cajas.index', compact(
            'cajas',
            'cajaAbierta',
            'fechaInicio',
            'fechaFin',
            'totalConsultas',
            'totalLaboratorios',
            'totalGeneral'
        ));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'monto_inicial' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string'
        ]);

        // Verificación estricta de caja abierta
        $cajaAbierta = Caja::where('estado', 'ABIERTA')->first();

        if ($cajaAbierta) {
            return redirect()->route('admin.cajas.index')
                ->with('error', 'No puedes abrir una nueva caja porque ya existe una caja abierta (ID: ' . $cajaAbierta->id . ').');
        }

        Caja::create([
            'user_id' => auth()->id(),
            'fecha_apertura' => now(),
            'monto_inicial' => $request->monto_inicial,
            'estado' => 'ABIERTA',
            'observaciones' => $request->observaciones
        ]);

        return redirect()->route('admin.cajas.index')->with([
            'mensaje' => 'Caja abierta correctamente.',
            'icono' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Caja $caja)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $caja = Caja::findOrFail($id);

        if (!$caja->puedeEditar()) {
            return response()->json(['error' => 'No puedes editar esta caja.'], 403);
        }

        return response()->json($caja);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $caja = Caja::findOrFail($id);

        // LÓGICA DE CIERRE (Si viene monto_final)
        if ($request->has('monto_final')) {
            $request->validate(['monto_final' => 'required|numeric|min:0']);

            $totalConsultas = \App\Models\Consulta::where('caja_id', $caja->id)->sum('precio');
            $totalLaboratorios = \App\Models\OrdenLaboratorio::where('caja_id', $caja->id)->sum('total');
            $totalGeneral = $caja->monto_inicial + $totalConsultas + $totalLaboratorios;

            $caja->update([
                'fecha_cierre' => now(),
                'monto_cierre' => $request->monto_final,
                'estado' => 'CERRADA',
                'total_efectivo' => $totalGeneral,
            ]);

            return redirect()->route('admin.cajas.index')->with(['mensaje' => 'Caja cerrada.', 'icono' => 'success']);
        }

        // LÓGICA DE EDICIÓN DE APERTURA (Si no viene monto_final)
        $request->validate([
            'monto_inicial' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string'
        ]);

        $caja->update([
            'monto_inicial' => $request->monto_inicial,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('admin.cajas.index')->with(['mensaje' => 'Caja actualizada correctamente.', 'icono' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Caja $caja)
    {
        if ($caja->consultas()->exists() || $caja->ordenLaboratorios()->exists()) {
            return back()->with([
                'error' => 'No puedes eliminar una caja que ya tiene movimientos registrados.',
                'icono' => 'error'
            ]);
        }

        try {
            $caja->delete();
            // Cambiamos 'success' por 'mensaje' para que tu app.blade.php lo detecte
            return back()->with([
                'mensaje' => 'Caja eliminada correctamente.',
                'icono' => 'success'
            ]);
        } catch (\Exception $e) {
            return back()->with([
                'error' => 'Ocurrió un error al intentar eliminar la caja.',
                'icono' => 'error'
            ]);
        }
    }
}
