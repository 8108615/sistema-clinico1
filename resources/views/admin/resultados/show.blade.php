<x-layouts::app title="Detalle de Resultados de Laboratorio">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Cabecera y acciones -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <flux:heading size="xl" level="1">Detalle de Resultados de Laboratorio</flux:heading>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Orden N°: <span class="font-semibold text-gray-800 dark:text-gray-200">#{{ $orden->id }}</span></p>
            </div>
            <div class="flex items-center gap-3">
                <flux:button href="{{ route('admin.resultados_laboratorios.index') }}" variant="subtle" icon="arrow-left">
                    Volver
                </flux:button>
                <flux:button href="{{ route('admin.resultados_laboratorios.imprimir', $orden->id) }}" target="_blank" variant="primary" icon="printer">
                    Imprimir PDF
                </flux:button>
            </div>
        </div>

        <!-- Tarjeta de Información General del Paciente -->
        <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
            <flux:heading size="lg" level="2" class="mb-4">Información General</flux:heading>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                <div>
                    <span class="block text-gray-400 font-medium">Paciente</span>
                    <span class="text-gray-800 dark:text-gray-100 font-semibold text-base">
                        {{ $orden->paciente->nombres ?? 'N/A' }} {{ $orden->paciente->apellidos ?? '' }}
                    </span>
                </div>
                <div>
                    <span class="block text-gray-400 font-medium">CI / Documento</span>
                    <span class="text-gray-800 dark:text-gray-100 font-medium">
                        {{ $orden->paciente->ci ?? 'No registrado' }}
                    </span>
                </div>
                <div>
                    <span class="block text-gray-400 font-medium">Fecha de la Orden</span>
                    <span class="text-gray-800 dark:text-gray-100 font-medium">
                        {{ $orden->fecha_orden ? \Carbon\Carbon::parse($orden->fecha_orden)->format('d/m/Y H:i') : 'N/A' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Listado de Exámenes y sus Resultados -->
        <div class="space-y-6">
            <flux:heading size="lg" level="2">Exámenes Realizados</flux:heading>

            @forelse($orden->detalles as $detalle)
                <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
                    <!-- Cabecera del Examen -->
                    <div class="bg-gray-50 dark:bg-zinc-900 px-6 py-4 border-b border-gray-200 dark:border-zinc-700 flex justify-between items-center">
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-blue-600 dark:text-blue-400">Examen Clínico</span>
                            <h3 class="text-base font-bold text-gray-800 dark:text-gray-100">
                                {{ $detalle->laboratorio->nombre ?? 'Examen sin nombre' }}
                            </h3>
                        </div>
                        @if($detalle->resultados->count() > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                Resultados Registrados
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                Sin resultados aún
                            </span>
                        @endif
                    </div>

                    <!-- Tabla de Parámetros -->
                    <div class="p-6">
                        @if($detalle->resultados->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300 border-collapse">
                                    <thead class="bg-gray-50 dark:bg-zinc-900/50 text-xs uppercase text-gray-400 font-semibold border-b border-gray-200 dark:border-zinc-700">
                                        <tr>
                                            <th class="py-3 px-4">Parámetro</th>
                                            <th class="py-3 px-4">Resultado</th>
                                            <th class="py-3 px-4">Unidad</th>
                                            <th class="py-3 px-4">Valores de Referencia</th>
                                            <th class="py-3 px-4">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                                        @foreach($detalle->resultados as $res)
                                            <tr class="hover:bg-gray-50/50 dark:hover:bg-zinc-700/20">
                                                <td class="py-3 px-4 font-medium text-gray-800 dark:text-gray-200">
                                                    {{ $res->parametro }}
                                                </td>
                                                <td class="py-3 px-4 font-bold text-blue-600 dark:text-blue-400">
                                                    {{ $res->resultado }}
                                                </td>
                                                <td class="py-3 px-4 text-gray-500">
                                                    {{ $res->unidad_medida ?? '-' }}
                                                </td>
                                                <td class="py-3 px-4 text-gray-500">
                                                    {{ $res->valores_referencia ?? '-' }}
                                                </td>
                                                <td class="py-3 px-4 text-gray-500 italic">
                                                    {{ $res->observaciones ?? '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pie de registro de los resultados -->
                            <div class="mt-4 pt-3 border-t border-gray-100 dark:border-zinc-700/50 flex justify-between items-center text-xs text-gray-400">
                                <span>Cargado por: {{ $detalle->resultados->first()->usuario->name ?? 'Personal autorizado' }}</span>
                                <span>Fecha: {{ $detalle->resultados->first()->created_at ? $detalle->resultados->first()->created_at->format('d/m/Y H:i') : '' }}</span>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400 italic py-2">
                                No se han cargado parámetros ni resultados para este examen clínico todavía.
                            </p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-8 text-center">
                    <p class="text-gray-500 dark:text-gray-400">No hay exámenes asociados a esta orden.</p>
                </div>
            @endforelse
        </div>

    </div>
</x-layouts::app>
