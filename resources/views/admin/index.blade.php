<x-layouts::app :title="__('Dashboard')">
    <div class="w-full px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <!-- Cabecera de bienvenida inspirada en Dashboard Pro -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold">¡Bienvenido de nuevo, {{ Auth::user()->name ?? 'Administrador' }}!</h1>
                <p class="text-blue-100 text-sm mt-1">Aquí tienes el resumen operativo y financiero en tiempo real de tu centro clínico.</p>
            </div>
            <div class="bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl text-xs font-semibold border border-white/20">
                <i class="fas fa-calendar-alt mr-2"></i> {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>

        <!-- Tarjetas de Estadísticas Principales (KPIs superiores clickeables) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Tarjeta Pacientes -->
            <a href="{{ route('admin.pacientes.index') }}" class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm flex items-center justify-between hover:border-blue-500 dark:hover:border-blue-400 hover:shadow-md transition cursor-pointer group">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 group-hover:text-blue-500 transition">Total Pacientes</span>
                    <h3 class="text-3xl font-extrabold text-gray-800 dark:text-gray-100 mt-1">{{ $totalPacientes }}</h3>
                </div>
                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-xl text-xl">
                    <i class="fas fa-users"></i>
                </div>
            </a>

            <!-- Tarjeta Consultas Médicas -->
            <a href="{{ route('admin.consultas.index') }}" class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm flex items-center justify-between hover:border-green-500 dark:hover:border-green-400 hover:shadow-md transition cursor-pointer group">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 group-hover:text-green-500 transition">Consultas Médicas</span>
                    <h3 class="text-3xl font-extrabold text-gray-800 dark:text-gray-100 mt-1">{{ $totalConsultas }}</h3>
                </div>
                <div class="p-3 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 rounded-xl text-xl">
                    <i class="fas fa-stethoscope"></i>
                </div>
            </a>

            <!-- Tarjeta Órdenes de Laboratorio -->
            <a href="{{ route('admin.orden_laboratorios.index') }}" class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm flex items-center justify-between hover:border-amber-500 dark:hover:border-amber-400 hover:shadow-md transition cursor-pointer group">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 group-hover:text-amber-500 transition">Órdenes Laboratorio</span>
                    <h3 class="text-3xl font-extrabold text-gray-800 dark:text-gray-100 mt-1">{{ $totalLaboratorios }}</h3>
                </div>
                <div class="p-3 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 rounded-xl text-xl">
                    <i class="fas fa-flask"></i>
                </div>
            </a>

            <!-- Tarjeta Insumos Registrados (Opcional si tienes la ruta de insumos, o puedes ajustarla) -->
            <a href="{{ route('admin.insumos.index') }}" class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm flex items-center justify-between hover:border-purple-500 dark:hover:border-purple-400 hover:shadow-md transition cursor-pointer group">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 group-hover:text-purple-500 transition">Insumos Registrados</span>
                    <h3 class="text-3xl font-extrabold text-gray-800 dark:text-gray-100 mt-1">{{ $totalInsumos }}</h3>
                </div>
                <div class="p-3 bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 rounded-xl text-xl">
                    <i class="fas fa-boxes"></i>
                </div>
            </a>

        </div>

        <!-- Sección Intermedia: Órdenes Recientes y Alertas de Stock -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <flux:heading size="lg" level="2">Últimas Órdenes de Laboratorio</flux:heading>
                    <flux:button href="{{ route('admin.resultados_laboratorios.index') }}" variant="subtle" size="sm">Ver todas</flux:button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead class="bg-gray-50 dark:bg-zinc-900/50 text-xs uppercase text-gray-400 font-semibold border-b border-gray-200 dark:border-zinc-700">
                            <tr>
                                <th class="py-3 px-3">Orden</th>
                                <th class="py-3 px-3">Paciente</th>
                                <th class="py-3 px-3">Fecha</th>
                                <th class="py-3 px-3 text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                            @forelse($ultimasOrdenes as $orden)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-zinc-700/20">
                                    <td class="py-3 px-3 font-bold text-blue-600 dark:text-blue-400">#{{ $orden->id }}</td>
                                    <td class="py-3 px-3 font-medium text-gray-800 dark:text-gray-200">
                                        {{ $orden->paciente->nombres ?? '' }} {{ $orden->paciente->apellidos ?? '' }}
                                    </td>
                                    <td class="py-3 px-3 text-gray-500">
                                        {{ \Carbon\Carbon::parse($orden->fecha_orden)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <a href="{{ route('admin.resultados_laboratorios.show', $orden->id) }}" class="text-xs px-2.5 py-1 bg-zinc-100 dark:bg-zinc-700 hover:bg-zinc-200 dark:hover:bg-zinc-600 text-gray-800 dark:text-gray-200 rounded font-semibold transition">
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-center text-gray-400 italic">No hay órdenes recientes registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <flux:heading size="lg" level="2">Alertas de Stock</flux:heading>
                    <i class="fas fa-exclamation-triangle text-amber-500"></i>
                </div>

                <div class="space-y-3">
                    @forelse($insumosBajos as $insumo)
                        <div class="p-3 bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-900/30 rounded-lg flex justify-between items-center text-sm">
                            <div>
                                <span class="font-semibold text-gray-800 dark:text-gray-200 block">{{ $insumo->nombre ?? $insumo->descripcion }}</span>
                                <span class="text-xs text-amber-700 dark:text-amber-400">Stock actual: {{ $insumo->stock ?? 0 }}</span>
                            </div>
                            <span class="px-2 py-1 bg-amber-500 text-white text-xs font-bold rounded">Bajo</span>
                        </div>
                    @empty
                        <div class="text-center py-6 text-gray-400 italic text-sm">
                            <i class="fas fa-check-circle text-green-500 text-2xl mb-2 block"></i>
                            ¡Excelente! No hay alertas de stock bajo.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- ================================================================= -->
        <!-- ZONA INFERIOR (Marcada con azul): Estadísticas Dinámicas y Filtros -->
        <!-- ================================================================= -->
        <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm space-y-6">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-200 dark:border-zinc-700 pb-4">
                <div>
                    <flux:heading size="lg" level="2">Estadísticas y Recaudación Dinámica</flux:heading>
                    <p class="text-sm text-gray-500">Filtra la productividad y los ingresos por día, mes o año.</p>
                </div>

                <!-- Formulario de Filtros de Fecha con Selects Personalizados -->
                <form method="GET" action="{{ route('admin.index') }}" class="flex flex-wrap items-center gap-3">

                    <!-- Selector de Día (1 al 31 según el mes) -->
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Día:</label>
                        <select name="dia_num" onchange="this.form.submit()"
                            class="text-xs bg-zinc-50 dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-gray-700 dark:text-gray-200">
                            @for($d = 1; $d <= $diasEnMes; $d++)
                                <option value="{{ str_pad($d, 2, '0', STR_PAD_LEFT) }}" {{ $diaNum == str_pad($d, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                    {{ $d }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Selector de Mes (Con nombres en Español) -->
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Mes:</label>
                        <select name="mes_num" onchange="this.form.submit()"
                            class="text-xs bg-zinc-50 dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-gray-700 dark:text-gray-200">
                            @php
                                $meses = [
                                    '01' => 'ENERO', '02' => 'FEBRERO', '03' => 'MARZO', '04' => 'ABRIL',
                                    '05' => 'MAYO', '06' => 'JUNIO', '07' => 'JULIO', '08' => 'AGOSTO',
                                    '09' => 'SEPTIEMBRE', '10' => 'OCTUBRE', '11' => 'NOVIEMBRE', '12' => 'DICIEMBRE'
                                ];
                            @endphp
                            @foreach($meses as $num => $nombre)
                                <option value="{{ $num }}" {{ $mesNum == $num ? 'selected' : '' }}>
                                    {{ $nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Selector de Año -->
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Año:</label>
                        <select name="anio" onchange="this.form.submit()"
                            class="text-xs bg-zinc-50 dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-gray-700 dark:text-gray-200">
                            @for($y = date('Y'); $y >= 2024; $y--)
                                <option value="{{ $y }}" {{ $filtroAnio == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                </form>
            </div>

            <!-- Grid de Estadísticas Filtradas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Productividad del Mes (Consultas vs Labs) -->
                <div class="bg-zinc-50 dark:bg-zinc-900/50 border border-gray-200 dark:border-zinc-700 rounded-xl p-5">
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 block mb-3">Productividad en el Mes</span>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600 dark:text-gray-400"><i class="fas fa-stethoscope mr-2 text-green-500"></i> Consultas Médicas:</span>
                            <span class="font-extrabold text-gray-800 dark:text-gray-100 text-base">{{ $consultasMesCount }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600 dark:text-gray-400"><i class="fas fa-flask mr-2 text-amber-500"></i> Órdenes Laboratorio:</span>
                            <span class="font-extrabold text-gray-800 dark:text-gray-100 text-base">{{ $laboratoriosMesCount }}</span>
                        </div>
                    </div>
                </div>

                <!-- Recaudación Financiera (Día, Mes, Año) -->
                <div class="bg-zinc-50 dark:bg-zinc-900/50 border border-gray-200 dark:border-zinc-700 rounded-xl p-5 md:col-span-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-green-600 dark:text-green-400 block mb-3">Recaudación de Caja</span>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                        <div class="bg-white dark:bg-zinc-800 p-3 rounded-lg border border-gray-200 dark:border-zinc-700">
                            <span class="text-xs text-gray-400 block">Día Seleccionado</span>
                            <span class="text-lg font-bold text-gray-800 dark:text-gray-100 mt-1 block">{{ $simboloMoneda }} {{ number_format($recaudacionDia, 2) }}</span>
                        </div>
                        <div class="bg-white dark:bg-zinc-800 p-3 rounded-lg border border-gray-200 dark:border-zinc-700">
                            <span class="text-xs text-gray-400 block">Mes Seleccionado</span>
                            <span class="text-lg font-bold text-green-600 dark:text-green-400 mt-1 block">{{ $simboloMoneda }} {{ number_format($recaudacionMes, 2) }}</span>
                        </div>
                        <div class="bg-white dark:bg-zinc-800 p-3 rounded-lg border border-gray-200 dark:border-zinc-700">
                            <span class="text-xs text-gray-400 block">Año {{ $filtroAnio }}</span>
                            <span class="text-lg font-bold text-blue-600 dark:text-blue-400 mt-1 block">{{ $simboloMoneda }} {{ number_format($recaudacionAnio, 2) }}</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Tabla de últimas consultas médicas (Estilo similar a la referencia) -->
            <div class="pt-4">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-100">Últimas Consultas Médicas Registradas</h3>
                    <a href="{{ route('admin.consultas.index') ?? '#' }}" class="text-xs text-blue-500 hover:underline">Ver todas</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead class="bg-gray-50 dark:bg-zinc-900/50 text-xs uppercase text-gray-400 font-semibold border-b border-gray-200 dark:border-zinc-700">
                            <tr>
                                <th class="py-3 px-3">#</th>
                                <th class="py-3 px-3">Paciente</th>
                                <th class="py-3 px-3">Fecha y Hora</th>
                                <th class="py-3 px-3">Motivo / Sola</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                            @forelse($ultimasConsultas as $con)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-zinc-700/20">
                                    <td class="py-3 px-3 font-bold text-blue-600">#{{ $con->id }}</td>
                                    <td class="py-3 px-3 font-medium text-gray-800 dark:text-gray-100">
                                        {{ $con->paciente->nombres ?? '' }} {{ $con->paciente->apellidos ?? '' }}
                                    </td>
                                    <td class="py-3 px-3 text-gray-500">
                                        {{ $con->created_at ? $con->created_at->format('d/m/Y H:i') : 'N/A' }}
                                    </td>
                                    <td class="py-3 px-3 text-gray-500 truncate max-w-xs">
                                        {{ $con->sintomas ?? $con->diagnostico ?? 'Consulta general' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-center text-gray-400 italic">No hay consultas registradas aún.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</x-layouts::app>
