<x-layouts::app title="Sistema Clinico">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Órdenes de Laboratorio</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex gap-4">
        <div class="flex-1">
            <form action="{{ route('admin.orden_laboratorios.index') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar por paciente o CI..."
                        value="{{ request('buscar') }}" class="transition-all duration-200" />
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </form>
        </div>
        <div class="flex-1 justify-end flex">
            @can('Ver formulario de creacion de orden de laboratorio')
                <a href="{{ route('admin.orden_laboratorios.create') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-plus mr-2"></i> Realizar Laboratorio
                </a>
            @endcan
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 mt-6">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                <tr>
                    <th class="px-6 py-3 border border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Nro</th>
                    <th class="px-6 py-3 border border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Paciente</th>
                    <th class="px-6 py-3 border border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Fecha</th>
                    <th class="px-6 py-3 border border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Laboratorios</th>
                    <th class="px-6 py-3 border border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Tipo de Pago</th>
                    <th class="px-6 py-3 border border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Monto</th>
                    <th class="px-6 py-3 border border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse ($ordenes as $orden)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-6 py-4 text-sm text-center">
                            {{ method_exists($ordenes, 'currentPage') ? ($loop->iteration + ($ordenes->currentPage() - 1) * $ordenes->perPage()) : $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-center">{{ $orden->paciente->nombres ?? '' }} {{ $orden->paciente->apellidos ?? '' }}</td>
                        <td class="px-6 py-4 text-sm text-center">{{ \Carbon\Carbon::parse($orden->fecha_orden)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm text-center">
                            <ul class="ml-4 text-xs list-disc list-inside">
                                @foreach ($orden->detalles as $detalle)
                                    <li>{{ $detalle->laboratorio->nombre ?? 'Examen' }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="px-6 py-4 text-sm text-center">
                            <span class="font-semibold">{{ $orden->tipo_pago }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-center">
                           <div class="font-bold">{{ $simboloMoneda ?? '' }} {{ number_format($orden->total, 2) }}</div>
                            @if($orden->tipo_pago === 'TRANSFERENCIA' || $orden->tipo_pago === 'QR')
                                <div class="text-[10px] text-gray-400">Ref: {{ $orden->codigo_transaccion ?? 'S/N' }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-1 items-center">
                                {{-- Botón Ver --}}
                                @can('Ver datos de la orden de laboratorio')
                                    <a href="{{ route('admin.orden_laboratorios.show', $orden->id) }}" class="inline-flex items-center px-3 py-1.5 bg-zinc-500 hover:bg-zinc-600 text-white text-xs font-semibold rounded transition shadow-sm">
                                        <i class="fas fa-eye mr-1"></i> Ver
                                    </a>
                                @endcan

                                {{-- Botón Editar --}}
                                @can('Ver formulario de edicion de orden de laboratorio')
                                    <a href="{{ route('admin.orden_laboratorios.edit', $orden->id) }}" class="inline-flex items-center px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded transition shadow-sm">
                                        <i class="fas fa-edit mr-1"></i> Editar
                                    </a>
                                @endcan

                                {{-- Botón Imprimir --}}
                                @can('Imprimir orden de laboratorio')
                                    <a href="{{ route('admin.orden_laboratorios.imprimir', $orden->id) }}"
                                        target="_blank"
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded transition shadow-sm">
                                        <i class="fas fa-print mr-1"></i> PDF
                                    </a>
                                @endcan

                                {{-- Botón Eliminar --}}
                                @can('Eliminar orden de laboratorio')
                                    <form action="{{ route('admin.orden_laboratorios.destroy', $orden->id) }}" method="post" id="formEliminar{{ $orden->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded transition shadow-sm"
                                                onclick="confirmarEliminar('formEliminar{{ $orden->id }}')">
                                            <i class="fas fa-trash-alt mr-1"></i> Eliminar
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500 italic">No hay órdenes de laboratorio registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Script de confirmación con SweetAlert --}}
    <script>
        function confirmarEliminar(formId) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>

    <div class="mt-4">
        {{ $ordenes->links() }}
    </div>
</x-layouts::app>
