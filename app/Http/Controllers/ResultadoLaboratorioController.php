<?php

namespace App\Http\Controllers;

use App\Models\Ajuste;
use App\Models\OrdenLaboratorio;
use App\Models\ResultadoLaboratorio;
use App\Models\DetalleOrdenLaboratorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultadoLaboratorioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        // Listamos las órdenes de laboratorio para gestionar sus resultados
        $ordenes = OrdenLaboratorio::with(['paciente', 'user', 'detalles.laboratorio', 'detalles.resultados'])
            ->when($buscar, function ($query) use ($buscar) {
                $query->whereHas('paciente', function ($q) use ($buscar) {
                    $q->where('nombres', 'like', '%' . $buscar . '%')
                      ->orWhere('apellidos', 'like', '%' . $buscar . '%')
                      ->orWhere('ci', 'like', '%' . $buscar . '%');
                })->orWhere('id', 'like', '%' . $buscar . '%');
            })
            ->latest('fecha_orden')
            ->paginate(10)
            ->withQueryString();

        return view('admin.resultados.index', compact('ordenes', 'buscar'));
    }

    /**
     * Show the form for creating a new resource (Cargar resultados para una orden/detalle).
     */
    public function create(Request $request)
    {
        $orden_id = $request->input('orden_id');
        $detalle_id = $request->input('detalle_id');

        // Añadimos 'detalles.resultados' para recuperar lo guardado previamente
        $orden = OrdenLaboratorio::with(['paciente', 'detalles.laboratorio', 'detalles.resultados'])->findOrFail($orden_id);

        // Si viene un detalle_id específico lo seleccionamos, de lo contrario tomamos el primero
        $detalleActual = $detalle_id
            ? $orden->detalles->where('id', $detalle_id)->first()
            : $orden->detalles->first();

        return view('admin.resultados.create', compact('orden', 'detalleActual'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'detalle_orden_laboratorio_id' => 'required|exists:detalle_orden_laboratorios,id',
            'parametros' => 'required|array',
            'parametros.*.parametro' => 'required|string|max:255',
            'parametros.*.resultado' => 'required|string|max:255',
            'parametros.*.unidad_medida' => 'nullable|string|max:50',
            'parametros.*.valores_referencia' => 'nullable|string|max:255',
            'parametros.*.observaciones' => 'nullable|string',
        ]);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
                $detalleId = $request->detalle_orden_laboratorio_id;

                // Opcional: Si quieres mantener el registro histórico de quién lo creó por primera vez,
                // puedes borrar los anteriores y crear los nuevos:
                ResultadoLaboratorio::where('detalle_orden_laboratorio_id', $detalleId)->delete();

                foreach ($request->parametros as $param) {
                    ResultadoLaboratorio::create([
                        'detalle_orden_laboratorio_id' => $detalleId,
                        'user_id' => Auth::id(),
                        'parametro' => $param['parametro'],
                        'resultado' => $param['resultado'],
                        'unidad_medida' => $param['unidad_medida'] ?? null,
                        'valores_referencia' => $param['valores_referencia'] ?? null,
                        'observaciones' => $param['observaciones'] ?? null,
                        'fecha_resultado' => now(),
                    ]);
                }
            });

            return redirect()->route('admin.resultados_laboratorios.index')
                ->with('mensaje', 'Resultados de laboratorio registrados exitosamente.')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Ocurrió un error al registrar los resultados: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $orden = OrdenLaboratorio::with(['paciente', 'user', 'detalles.laboratorio', 'detalles.resultados.usuario'])->findOrFail($id);
        return view('admin.resultados.show', compact('orden'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $resultado = ResultadoLaboratorio::with('detalleOrden.laboratorio', 'detalleOrden.orden.paciente')->findOrFail($id);
        return view('admin.resultados.edit', compact('resultado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $resultado = ResultadoLaboratorio::findOrFail($id);

        $request->validate([
            'parametro' => 'required|string|max:255',
            'resultado' => 'required|string|max:255',
            'unidad_medida' => 'nullable|string|max:50',
            'valores_referencia' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        try {
            $resultado->update([
                'parametro' => $request->parametro,
                'resultado' => $request->resultado,
                'unidad_medida' => $request->unidad_medida,
                'valores_referencia' => $request->valores_referencia,
                'observaciones' => $request->observaciones,
            ]);

            return redirect()->route('admin.resultados_laboratorios.show', $resultado->detalleOrden->orden_laboratorio_id)
                ->with('mensaje', 'Resultado actualizado exitosamente.')
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
    public function destroy($id)
    {
        $resultado = ResultadoLaboratorio::findOrFail($id);
        $ordenId = $resultado->detalleOrden->orden_laboratorio_id;
        $resultado->delete();

        return redirect()->route('admin.resultados_laboratorios.show', $ordenId)
            ->with('mensaje', 'Parámetro de resultado eliminado correctamente.')
            ->with('icono', 'success');
    }

    /**
     * Imprimir reporte oficial en PDF.
     */
    public function imprimir($id)
    {
        $orden = OrdenLaboratorio::with([
            'paciente',
            'user',
            'detalles.laboratorio',
            'detalles.resultados.usuario'
        ])->findOrFail($id);

        $ajuste = Ajuste::first();

        // Generamos el PDF usando una vista específica para la impresión
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.resultados.imprimir', compact('orden', 'ajuste'));

        // Opcional: Configurar tamaño de hoja (Carta) y orientación (Portrait/Vertical)
        $pdf->setPaper('letter', 'portrait');

        // Mostrar el PDF directamente en el navegador (para imprimir o guardar)
        return $pdf->stream('resultado-laboratorio-' . $orden->id . '.pdf');
    }
}
