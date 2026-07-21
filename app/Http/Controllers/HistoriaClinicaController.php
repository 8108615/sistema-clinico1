<?php

namespace App\Http\Controllers;

use App\Models\HistoriaClinica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class HistoriaClinicaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        
        // Usamos 'medico' porque así se llama tu relación en el modelo
        $historias = HistoriaClinica::with(['paciente', 'medico']) 
            ->when($buscar, function ($query) use ($buscar) {
                $query->whereHas('paciente', function ($q) use ($buscar) {
                    $q->where('nombres', 'like', '%' . $buscar . '%')
                    ->orWhere('apellidos', 'like', '%' . $buscar . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.historias-clinicas.index', compact('historias', 'buscar'));
    }

    public function create()
    {
        // Traemos pacientes para el select
        $pacientes = \App\Models\Paciente::all();
        return view('admin.historias-clinicas.create', compact('pacientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'paciente_id'     => 'required|exists:pacientes,id',
            'numero_historia' => 'required|string|unique:historia_clinicas,numero_historia',
            'subjetivo'       => 'required|string',
            'objetivo'        => 'required|string',
            'analisis'        => 'required|string',
            'plan'            => 'required|string',
            'estado'          => 'required|in:borrador,finalizado,anulado',
        ]);

        $historia = new HistoriaClinica();
        $historia->paciente_id     = $request->paciente_id;
        $historia->user_id         = Auth::id(); // Asignamos el médico actual
        $historia->numero_historia = $request->numero_historia;
        $historia->subjetivo       = $request->subjetivo;
        $historia->objetivo        = $request->objetivo;
        $historia->analisis        = $request->analisis;
        $historia->plan            = $request->plan;
        $historia->estado          = $request->estado;
        $historia->save();

        return redirect()->route('admin.historias_clinicas.index')
            ->with('mensaje', 'Historia clínica guardada correctamente')
            ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(HistoriaClinica $historiaClinica)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $historia = \App\Models\HistoriaClinica::findOrFail($id);
        $pacientes = \App\Models\Paciente::all();
        return view('admin.historias-clinicas.edit', compact('historia', 'pacientes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'paciente_id'     => 'required|exists:pacientes,id',
            'numero_historia' => 'required|string|unique:historia_clinicas,numero_historia,' . $id,
            'subjetivo'       => 'required|string',
            'objetivo'        => 'required|string',
            'analisis'        => 'required|string',
            'plan'            => 'required|string',
            'estado'          => 'required|in:borrador,finalizado,anulado',
        ]);

        $historia = \App\Models\HistoriaClinica::findOrFail($id);
        
        $historia->paciente_id     = $request->paciente_id;
        $historia->numero_historia = $request->numero_historia;
        $historia->subjetivo       = $request->subjetivo;
        $historia->objetivo        = $request->objetivo;
        $historia->analisis        = $request->analisis;
        $historia->plan            = $request->plan;
        $historia->estado          = $request->estado;
        $historia->save();

        return redirect()->route('admin.historias_clinicas.index')
            ->with('mensaje', 'Historia clínica actualizada correctamente')
            ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $historia = \App\Models\HistoriaClinica::findOrFail($id);
        $historia->delete();

        return redirect()->route('admin.historias_clinicas.index')
            ->with('mensaje', 'Historia clínica eliminada correctamente')
            ->with('icono', 'success');
    }

    public function trashed(Request $request)
    {
        $buscar = $request->input('buscar');

        $historias = HistoriaClinica::onlyTrashed()
            ->with(['paciente', 'medico'])
            ->when($buscar, function ($query) use ($buscar) {
                $query->whereHas('paciente', function ($q) use ($buscar) {
                    $q->where('nombres', 'like', '%' . $buscar . '%')
                    ->orWhere('apellidos', 'like', '%' . $buscar . '%');
                });
            })
            ->latest('deleted_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.historias-clinicas.trashed', compact('historias', 'buscar'));
    }

    public function restore($id)
    {
        $historia = HistoriaClinica::onlyTrashed()->findOrFail($id);
        $historia->restore();

        return redirect()->route('admin.historias_clinicas.trashed')
                        ->with('success', 'Historia clínica restaurada con éxito.');
    }
    public function pdf($id)
    {
        $historia = HistoriaClinica::with(['paciente', 'medico'])->findOrFail($id);

        // Cargar la vista y pasarle la variable
        $pdf = Pdf::loadView('admin.historias-clinicas.pdf', compact('historia'));

        // Opcional: configurar tamaño de papel (carta) y orientación
        $pdf->setPaper('letter', 'portrait');

        // Retornar el PDF para descarga directa o visualización en navegador
        // stream() abre en el navegador, download() descarga directo al equipo
        return $pdf->stream('historia-clinica-' . $historia->numero_historia . '.pdf');
    }
}
