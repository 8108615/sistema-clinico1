<x-layouts::app title="Gestión de Cajas">
    <!-- Contenedor de Modales inicializado con x-data para asegurar el registro en el DOM -->
    <div wire:ignore x-data>
        @can('Guardar caja')
            <flux:modal name="modal-apertura">
                <form action="{{ route('admin.cajas.store') }}" method="POST" class="p-6">
                    @csrf
                    <flux:heading size="lg" class="mb-4">Abrir Nueva Caja</flux:heading>
                    <flux:input name="monto_inicial" label="Monto Inicial" placeholder="0.00" required class="mb-4" />
                    <flux:textarea name="observaciones" label="Observaciones" class="mb-4" />
                    <div class="flex justify-end gap-2 mt-4">
                        <flux:button type="button" variant="ghost" x-on:click="$flux.modal('modal-apertura').close()">Cancelar</flux:button>
                        <flux:button type="submit" variant="primary">Confirmar Apertura</flux:button>
                    </div>
                </form>
            </flux:modal>
        @endcan

        @can('Actualizar caja')
            <flux:modal name="modal-cierre">
                <form action="{{ $cajaAbierta ? route('admin.cajas.update', $cajaAbierta->id) : '#' }}" method="POST" class="p-6">
                    @csrf @method('PUT')
                    <flux:heading size="lg" class="mb-4">Cerrar Caja</flux:heading>

                    <!-- Resumen de Totales -->
                    <div class="bg-gray-50 dark:bg-zinc-900 p-4 rounded-lg mb-4 text-sm border border-gray-200 dark:border-zinc-700">
                        <div class="flex justify-between mb-1"><span>Monto Inicial:</span> <b>Bs. {{ number_format($cajaAbierta->monto_inicial ?? 0, 2) }}</b></div>
                        <div class="flex justify-between mb-1"><span>Total Consultas:</span> <b>Bs. {{ number_format($totalConsultas ?? 0, 2) }}</b></div>
                        <div class="flex justify-between mb-1"><span>Total Laboratorios:</span> <b>Bs. {{ number_format($totalLaboratorios ?? 0, 2) }}</b></div>
                        <flux:separator class="my-2" />
                        <div class="flex justify-between text-lg"><span>Total Esperado:</span> <b class="text-blue-600">Bs. {{ number_format($totalGeneral ?? 0, 2) }}</b></div>
                    </div>

                    <p class="mb-4 text-sm text-gray-600">¿Estás seguro de cerrar la caja actual? Esta acción es irreversible.</p>

                    <!-- Input con el total cargado y deshabilitado (readonly) -->
                    <flux:input
                        name="monto_final"
                        label="Monto Total Recaudado (Cierre)"
                        value="{{ number_format($totalGeneral ?? 0, 2, '.', '') }}"
                        readonly
                    />

                    <div class="flex justify-end gap-2 mt-4">
                        <flux:button type="button" variant="ghost" x-on:click="$flux.modal('modal-cierre').close()">Cancelar</flux:button>
                        <flux:button type="submit" variant="danger">Cerrar Caja</flux:button>
                    </div>
                </form>
            </flux:modal>
        @endcan
    </div>

    <!-- Encabezado -->
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Listado de Cajas</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <!-- Filtro y Acciones -->
    <div class="flex flex-col md:flex-row gap-6 mb-8 items-end">
        <div class="flex-1 bg-white dark:bg-zinc-800 p-4 rounded-lg border border-gray-200 dark:border-zinc-700 shadow-sm">
            <form action="{{ route('admin.cajas.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <flux:input name="fecha_inicio" label="Desde" type="date" value="{{ $fechaInicio }}" />
                </div>
                <div class="flex-1 min-w-[200px]">
                    <flux:input name="fecha_fin" label="Hasta" type="date" value="{{ $fechaFin }}" />
                </div>
                <div class="flex gap-2">
                    <flux:button type="submit" variant="primary" icon="funnel">Filtrar</flux:button>
                    <a href="{{ route('admin.cajas.index') }}">
                        <flux:button type="button" variant="ghost">Limpiar</flux:button>
                    </a>
                </div>
            </form>
        </div>

        <!-- Botón de Acción -->
        <div class="flex items-center" x-data>
            @if(!$cajaAbierta)
                @can('Guardar caja')
                    <flux:button variant="primary" x-on:click="$flux.modal('modal-apertura').show()">
                        <i class="fas fa-plus mr-2"></i> Abrir Nueva Caja
                    </flux:button>
                @endcan
            @else
                <div class="flex gap-2 items-center">
                    <flux:badge color="green" size="lg" icon="lock-open">Caja Abierta</flux:badge>
                    @can('Actualizar caja')
                        <flux:button variant="danger" x-on:click="$flux.modal('modal-cierre').show()">
                            <i class="fas fa-lock mr-2"></i> Cerrar Caja
                        </flux:button>
                    @endcan
                </div>
            @endif
        </div>
    </div>

    <!-- Tabla de Cajas -->
    <div class="w-full overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
        <table class="w-full border-collapse text-sm">
            <thead class="bg-gray-50 dark:bg-zinc-900">
                <tr>
                    <th class="px-4 py-3 font-bold text-gray-500 uppercase text-center">Nro</th>
                    <th class="px-4 py-3 font-bold text-gray-500 uppercase text-left">Usuario</th>
                    <th class="px-4 py-3 font-bold text-gray-500 uppercase text-center">Fecha Apertura</th>
                    <th class="px-4 py-3 font-bold text-gray-500 uppercase text-center">Fecha Cierre</th>
                    <th class="px-4 py-3 font-bold text-gray-500 uppercase text-center">Monto Inicial</th>
                    <th class="px-4 py-3 font-bold text-gray-500 uppercase text-center">Monto Cierre</th>
                    <th class="px-4 py-3 font-bold text-gray-500 uppercase text-center">Total Efectivo</th>
                    <th class="px-4 py-3 font-bold text-gray-500 uppercase text-center">Estado</th>
                    <th class="px-4 py-3 font-bold text-gray-500 uppercase text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse ($cajas as $caja)
                    @php
                        $simbolo = $ajuste->simbolo_moneda ?? 'Bs.';
                        $esCerrada = $caja->estado === 'CERRADA';

                        // Calculamos los montos
                        $montoConsultas = \App\Models\Consulta::where('caja_id', $caja->id)->sum('precio');
                        $montoLabs = \App\Models\OrdenLaboratorio::where('caja_id', $caja->id)->sum('total');
                    @endphp
                    <tr class="hover:bg-blue-50/50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-4 py-4 text-center font-semibold text-gray-500 dark:text-gray-400">
                            {{ method_exists($cajas, 'currentPage') ? ($loop->iteration + ($cajas->currentPage() - 1) * $cajas->perPage()) : $loop->iteration }}
                        </td>
                        <td class="px-4 py-4 font-medium text-gray-900 dark:text-gray-100">{{ $caja->user->name }}</td>
                        <td class="px-4 py-4 text-center text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($caja->fecha_apertura)->format('d/m/Y H:i') }}</td>

                        <td class="px-4 py-4 text-center text-gray-700 dark:text-gray-300">
                            {{ $caja->fecha_cierre ? \Carbon\Carbon::parse($caja->fecha_cierre)->format('d/m/Y H:i') : '-' }}
                        </td>

                        <td class="px-4 py-4 text-center font-semibold text-gray-900 dark:text-gray-100">{{ $simbolo }} {{ number_format($caja->monto_inicial, 2) }}</td>

                        <td class="px-4 py-4 text-center font-semibold text-green-600 dark:text-green-400">
                            {{ $esCerrada ? ($simbolo . ' ' . number_format($caja->monto_cierre, 2)) : '-' }}
                        </td>

                        <td class="px-4 py-4 text-xs">
                            @if($esCerrada)
                                <div class="space-y-0.5 text-gray-700 dark:text-gray-300">
                                    <div>Consultas: {{ $simbolo }} {{ number_format($montoConsultas, 2) }}</div>
                                    <div>Laboratorios: {{ $simbolo }} {{ number_format($montoLabs, 2) }}</div>
                                    <div class="border-t border-gray-200 dark:border-zinc-700 mt-1 pt-1 font-bold text-red-600 dark:text-red-400">
                                        Total: {{ $simbolo }} {{ number_format($caja->total_efectivo, 2) }}
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <td class="px-4 py-4 text-center">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $caja->estado == 'ABIERTA' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                {{ $caja->estado }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="flex justify-center gap-1">
                                <!-- BOTÓN PDF -->
                                @can('Generar PDF de caja')
                                    <a href="{{ route('admin.cajas.pdf', $caja->id) }}" target="_blank" title="Generar PDF">
                                        <flux:button variant="primary" icon="document-text" size="sm" class="bg-blue-500 hover:bg-blue-600 border-none text-white"></flux:button>
                                    </a>
                                @endcan

                                @if($caja->puedeEditar())
                                    <!-- BOTÓN EDITAR -->
                                    @can('Ver formulario de edicion de caja')
                                        <flux:button
                                            variant="primary"
                                            icon="pencil"
                                            size="sm"
                                            class="bg-green-600 hover:bg-green-700 border-none text-white"
                                            onclick="abrirModalEdicion('{{ route('admin.cajas.update', $caja->id) }}', {{ $caja->monto_inicial }}, '{{ $caja->observaciones }}')"
                                            title="Editar"
                                        ></flux:button>
                                    @endcan

                                    <!-- BOTÓN ELIMINAR -->
                                    @can('Eliminar caja')
                                        <form action="{{ route('admin.cajas.destroy', $caja->id) }}" method="POST" id="form-eliminar-{{ $caja->id }}" class="inline-block">
                                            @csrf @method('DELETE')
                                            <flux:button
                                                type="button"
                                                variant="primary"
                                                icon="trash"
                                                size="sm"
                                                class="bg-red-500 hover:bg-red-600 border-none text-white"
                                                onclick="confirmarEliminacion({{ $caja->id }})"
                                                title="Eliminar"
                                            >
                                            </flux:button>
                                        </form>
                                    @endcan
                                @else
                                    <!-- BOTÓN BLOQUEADO -->
                                    <flux:button
                                        variant="primary"
                                        icon="lock-closed"
                                        size="sm"
                                        class="bg-zinc-700 border-none text-red-500 opacity-70"
                                        disabled
                                        title="Caja cerrada o no editable"
                                    >
                                    </flux:button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500">No hay cajas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación si estuviera implementada en el controlador (o se mantiene limpia si es colección simple) --}}
    @if (method_exists($cajas, 'hasPages') && $cajas->hasPages())
        <div class="mt-6 flex justify-between items-center px-2">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Mostrando <span class="font-semibold">{{ $cajas->firstItem() }}</span> al
                <span class="font-semibold">{{ $cajas->lastItem() }}</span> de
                <span class="font-semibold">{{ $cajas->total() }}</span> resultados
            </div>
            <div>
                {{ $cajas->links() }}
            </div>
        </div>
    @endif

    @can('Actualizar caja')
        <flux:modal name="modal-editar" title="Editar Apertura de Caja">
            <form id="form-editar" method="POST" class="p-6">
                @csrf @method('PUT')

                <flux:input name="monto_inicial" id="edit_monto_inicial" label="Monto Inicial" required class="mb-4" />
                <flux:textarea name="observaciones" id="edit_observaciones" label="Observaciones" class="mb-4" />

                <div class="flex justify-end gap-2 mt-4">
                    <flux:button type="button" variant="ghost" x-on:click="$flux.modal('modal-editar').close()">Cancelar</flux:button>
                    <flux:button type="submit" variant="primary">Guardar Cambios</flux:button>
                </div>
            </form>
        </flux:modal>
    @endcan

    <script>
        function abrirModalEdicion(url, monto, obs) {
            document.getElementById('form-editar').action = url;
            document.getElementById('edit_monto_inicial').value = monto;
            document.getElementById('edit_observaciones').value = obs === 'null' ? '' : obs;
            Flux.modal('modal-editar').show();
        }
    </script>

    <script>
        function confirmarEliminacion(id) {
            Swal.fire({
                title: '¿Desea eliminar esta caja?',
                text: "Esta acción no se puede deshacer y eliminará todos los registros asociados si no tienen movimientos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-eliminar-' + id).submit();
                }
            });
        }
    </script>
</x-layouts::app>
