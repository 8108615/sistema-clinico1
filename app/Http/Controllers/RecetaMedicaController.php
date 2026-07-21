<?php

namespace App\Http\Controllers;

use App\Models\RecetaMedica;
use App\Models\DetalleReceta;
use App\Models\Paciente;
use App\Models\HistoriaClinica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RecetaMedicaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $recetas = RecetaMedica::with(['paciente', 'medico', 'historiaClinica', 'detalles'])
            ->when($buscar, function ($query, $buscar) {
                return $query->where(function ($q) use ($buscar) {
                    // Búsqueda dentro de la relación del paciente usando 'ci'
                    $q->whereHas('paciente', function ($subQ) use ($buscar) {
                        $subQ->where('nombres', 'like', "%{$buscar}%")
                             ->orWhere('apellidos', 'like', "%{$buscar}%")
                             ->orWhere('ci', 'like', "%{$buscar}%");
                    })
                    // O búsqueda directa en la fecha de la receta
                    ->orWhere('fecha_receta', 'like', "%{$buscar}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.recetas.index', compact('recetas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pacientes = Paciente::all();
        $historias = HistoriaClinica::with('paciente')->get();

        return view('admin.recetas.create', compact('pacientes', 'historias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'historia_clinica_id' => 'required|exists:historia_clinicas,id',
            'fecha_receta' => 'required|date',
            'medicamentos' => 'required|array|min:1',
            'medicamentos.*.medicamento' => 'required|string|max:255',
            'medicamentos.*.dosis' => 'required|string|max:255',
            'medicamentos.*.duracion' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $receta = RecetaMedica::create([
                'historia_clinica_id' => $request->historia_clinica_id,
                'paciente_id' => $request->paciente_id,
                'user_id' => Auth::id(), // Médico autenticado actual
                'indicaciones_generales' => $request->indicaciones_generales,
                'fecha_receta' => $request->fecha_receta,
            ]);

            // Guardar el detalle de los medicamentos
            foreach ($request->medicamentos as $item) {
                DetalleReceta::create([
                    'receta_medica_id' => $receta->id,
                    'medicamento' => $item['medicamento'],
                    'dosis' => $item['dosis'],
                    'duracion' => $item['duracion'],
                    'indicaciones' => $item['indicaciones'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.recetas.index')
                    ->with('mensaje', 'Receta médica creada exitosamente.')
                    ->with('icono', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ocurrió un error al guardar la receta: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $receta = RecetaMedica::with(['paciente', 'medico', 'historiaClinica', 'detalles'])->findOrFail($id);

        return view('admin.recetas.show', compact('receta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $receta = RecetaMedica::with('detalles')->findOrFail($id);
        $pacientes = Paciente::all();
        $historias = HistoriaClinica::with('paciente')->get();

        return view('admin.recetas.edit', compact('receta', 'pacientes', 'historias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'historia_clinica_id' => 'required|exists:historia_clinicas,id',
            'fecha_receta' => 'required|date',
            'medicamentos' => 'required|array|min:1',
            'medicamentos.*.medicamento' => 'required|string|max:255',
            'medicamentos.*.dosis' => 'required|string|max:255',
            'medicamentos.*.duracion' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $receta = RecetaMedica::findOrFail($id);

            // Actualizar datos de la receta
            $receta->update([
                'historia_clinica_id' => $request->historia_clinica_id,
                'paciente_id' => $request->paciente_id,
                'indicaciones_generales' => $request->indicaciones_generales,
                'fecha_receta' => $request->fecha_receta,
            ]);

            // Eliminar detalles anteriores y registrar los nuevos
            $receta->detalles()->delete();

            foreach ($request->medicamentos as $item) {
                DetalleReceta::create([
                    'receta_medica_id' => $receta->id,
                    'medicamento' => $item['medicamento'],
                    'dosis' => $item['dosis'],
                    'duracion' => $item['duracion'],
                    'indicaciones' => $item['indicaciones'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.recetas.index')
                ->with('mensaje', 'Receta médica actualizada exitosamente.')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ocurrió un error al actualizar la receta: ' . $e->getMessage());
        }
    }
    public function pdf($id)
    {
        $receta = RecetaMedica::with(['paciente', 'medico', 'historiaClinica', 'detalles'])->findOrFail($id);

        // Si estás usando Barryvdh\DomPDF\Facade\Pdf:
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.recetas.pdf', compact('receta'));

        // Puedes usar stream() para ver en el navegador o download() para descargar directamente
        return $pdf->stream('receta-medica-' . $receta->id . '.pdf');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $receta = RecetaMedica::findOrFail($id);
            $receta->delete();

            return redirect()->route('admin.recetas.index')
                ->with('mensaje', 'La receta médica se envió a la papelera exitosamente.')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error al eliminar la receta: ' . $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $receta = RecetaMedica::onlyTrashed()->findOrFail($id);
            $receta->restore();

            return redirect()->route('admin.recetas.trashed')
                ->with('mensaje', 'La receta médica se restauró exitosamente.')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error al restaurar la receta: ' . $e->getMessage());
        }
    }

    public function trashed()
    {
        $recetas = RecetaMedica::onlyTrashed()->with(['paciente', 'medico'])->paginate(10);
        return view('admin.recetas.trashed', compact('recetas'));
    }
}
