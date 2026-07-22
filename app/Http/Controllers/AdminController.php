<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\Consulta;
use App\Models\OrdenLaboratorio;
use App\Models\Insumo;
use App\Models\Caja;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Si el usuario envía los selectores por separado, los armamos, si no, usamos los valores por defecto actuales
        $anio = $request->input('anio', date('Y'));
        $mesNum = $request->input('mes_num', date('m'));
        $diaNum = $request->input('dia_num', date('d'));

        // Asegurarnos de que el día no exceda los días del mes seleccionado
        $diasEnMes = Carbon::createFromDate($anio, $mesNum, 1)->daysInMonth;
        if ($diaNum > $diasEnMes) {
            $diaNum = $diasEnMes;
        }

        // Armamos los strings de filtro para las consultas de la base de datos
        $filtroDia = sprintf('%04d-%02d-%02d', $anio, $mesNum, $diaNum);
        $filtroMes = sprintf('%04d-%02d', $anio, $mesNum);
        $filtroAnio = $anio;

        // Métricas principales (Totales generales)
        $totalPacientes = Paciente::count();
        $totalConsultas = Consulta::count();
        $totalLaboratorios = OrdenLaboratorio::count();
        $totalInsumos = Insumo::count();

        // Alerta de insumos con stock bajo
        $insumosBajos = Insumo::whereColumn('stock', '<=', 'stock_minimo')->take(5)->get();

        // Últimas órdenes de laboratorio registradas
        $ultimasOrdenes = OrdenLaboratorio::with(['paciente', 'detalles.laboratorio'])
                            ->latest()
                            ->take(5)
                            ->get();

        // Estadísticas filtradas para la sección inferior (Consultas y Labs en el mes seleccionado)
        $consultasMesCount = Consulta::whereYear('created_at', $anio)
                                    ->whereMonth('created_at', $mesNum)
                                    ->count();

        $laboratoriosMesCount = OrdenLaboratorio::whereYear('created_at', $anio)
                                            ->whereMonth('created_at', $mesNum)
                                            ->count();

        // Recaudación / Ingresos sumando efectivo + transferencia de las cajas
        $recaudacionDia = Caja::whereDate('created_at', $filtroDia)
                            ->get()
                            ->sum(fn($caja) => $caja->total_efectivo + $caja->total_transferencia);

        $recaudacionMes = Caja::whereYear('created_at', $anio)
                                ->whereMonth('created_at', $mesNum)
                                ->get()
                                ->sum(fn($caja) => $caja->total_efectivo + $caja->total_transferencia);

        $recaudacionAnio = Caja::whereYear('created_at', $anio)
                                ->get()
                                ->sum(fn($caja) => $caja->total_efectivo + $caja->total_transferencia);

        // Últimas consultas médicas para la tabla inferior
        $ultimasConsultas = Consulta::with('paciente')->latest()->take(5)->get();

        return view('admin.index', compact(
            'totalPacientes',
            'totalConsultas',
            'totalLaboratorios',
            'totalInsumos',
            'insumosBajos',
            'ultimasOrdenes',
            'consultasMesCount',
            'laboratoriosMesCount',
            'recaudacionDia',
            'recaudacionMes',
            'recaudacionAnio',
            'ultimasConsultas',
            'filtroDia',
            'filtroAnio',
            'mesNum',
            'diaNum',
            'diasEnMes'
        ));
    }
}
