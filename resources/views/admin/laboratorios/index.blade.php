<x-layouts::app title="Resultados de Laboratorio">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Gestión de Resultados de Laboratorio</flux:heading>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Control y entrega de resultados de exámenes clínicos.</p>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex gap-4 items-center justify-between">
        <div class="flex-1">
            <form action="{{ route('admin.resultados_laboratorios.index') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar por paciente (nombre, apellido, CI)..."
                        value="{{ $buscar ?? '' }}" class="transition-all duration-200" />
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </form>
        </div>
    </div>

    <div class="w-full overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 mt-6 shadow-sm">
        <table class="w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900">
                <tr>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Nro</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Nro Orden</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase tracking-wider text-left">Paciente</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase tracking-wider text-center">CI</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Fecha Orden</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase tracking-wider text-left">Exámenes Solicitados</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Estado Resultados</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse ($ordenes as $orden)
                    <tr class="hover:bg-blue-50/50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-6 py-4 text-sm text-center text-gray-500 dark:text-gray-400 font-semibold">
                            {{ method_exists($ordenes, 'currentPage') ? ($loop->iteration + ($ordenes->currentPage() - 1) * $ordenes->perPage()) : $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 text-sm text-center font-bold text-blue-600 dark:text-blue-400">#{{ $orden->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 font-medium">
                            {{ $orden->paciente->nombres ?? '' }} {{ $orden->paciente->apellidos ?? '' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-center text-gray-900 dark:text-gray-100 whitespace-nowrap">{{ $orden->paciente->ci ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-center text-gray-900 dark:text-gray-100 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($orden->fecha_orden)->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                            <ul class="list-disc list-inside text-xs space-y-1">
                                @foreach ($orden->detalles as $detalle)
                                    <li>{{ $detalle->laboratorio->nombre ?? 'Examen' }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            @php
                                $totalDetalles = $orden->detalles->count();
                                $detallesConResultados = $orden->detalles->filter(fn($d) => $d->resultados->count() > 0)->count();
                            @endphp
                            @if ($detallesConResultados === 0)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-500 text-white">Pendiente</span>
                            @elseif ($detallesConResultados < $totalDetalles)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-amber-500 text-white">Parcial</span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-500 text-white">Completado</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            <div class="flex justify-center items-center gap-2">

                                {{-- Menú desplegable para seleccionar qué examen cargar protegido --}}
                                @can('Ver formulario de creacion de resultado de laboratorio')
                                    <div class="relative inline-block text-left">
                                        <select onchange="if(this.value) window.location.href=this.value;"
                                            class="px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded transition shadow-sm cursor-pointer outline-none border-none">
                                            <option value="" disabled selected class="bg-white text-gray-700">📂 Seleccionar Cargar...</option>
                                            @foreach($orden->detalles as $detalle)
                                                <option value="{{ route('admin.resultados_laboratorios.create', ['orden_id' => $orden->id, 'detalle_id' => $detalle->id]) }}"
                                                    class="bg-white text-gray-900 dark:bg-zinc-800 dark:text-gray-100">
                                                    {{ $detalle->laboratorio->nombre ?? 'Examen' }} {{ $detalle->resultados->count() > 0 ? '(Ya cargado)' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endcan

                                {{-- Ver Resultados / Detalle protegido --}}
                                @can('Ver datos del resultado de laboratorio')
                                    <a href="{{ route('admin.resultados_laboratorios.show', $orden->id) }}"
                                        class="inline-flex items-center px-3 py-2 bg-zinc-500 hover:bg-zinc-600 text-white text-xs font-semibold rounded transition shadow-sm" title="Ver Resultados">
                                        <i class="fas fa-eye mr-1"></i> Ver
                                    </a>
                                @endcan

                                {{-- Imprimir PDF protegido --}}
                                @can('Imprimir resultado de laboratorio')
                                    <a href="{{ route('admin.resultados_laboratorios.imprimir', $orden->id) }}" target="_blank"
                                        class="inline-flex items-center px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded transition shadow-sm" title="Imprimir Reporte">
                                        <i class="fas fa-file-pdf mr-1"></i> PDF
                                    </a>
                                @endcan

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-10 text-center text-gray-500 italic">No se encontraron órdenes de laboratorio registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $ordenes->links() }}
    </div>
</x-layouts::app>
