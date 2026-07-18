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

        return view('admin.cajas.index', compact('cajas', 'cajaAbierta', 'fechaInicio', 'fechaFin'));
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
    public function edit(Caja $caja)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Caja $caja)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Caja $caja)
    {
        //
    }
}
