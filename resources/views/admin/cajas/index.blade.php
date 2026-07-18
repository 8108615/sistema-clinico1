<x-layouts::app title="Gestión de Cajas">
    <!-- Contenedor de Modales (usamos wire:ignore para que Livewire no los destruya) -->
    <div wire:ignore>
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

        <flux:modal name="modal-cierre">
            <form action="#" method="POST" class="p-6">
                @csrf @method('PUT')
                <flux:heading size="lg" class="mb-4">Cerrar Caja</flux:heading>
                <p class="mb-4 text-sm text-gray-600">¿Estás seguro de cerrar la caja actual? Esta acción es irreversible.</p>
                <flux:input name="monto_final" label="Monto Total al Cierre" placeholder="0.00" required />
                <div class="flex justify-end gap-2 mt-4">
                    <flux:button type="button" variant="ghost" x-on:click="$flux.modal('modal-cierre').close()">Cancelar</flux:button>
                    <flux:button type="submit" variant="danger">Cerrar Caja</flux:button>
                </div>
            </form>
        </flux:modal>
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
        <div class="flex items-center">
            @if(!$cajaAbierta)
                <flux:button variant="primary" x-on:click="$flux.modal('modal-apertura').show()">
                    <i class="fas fa-plus mr-2"></i> Abrir Nueva Caja
                </flux:button>
            @else
                <div class="flex gap-2 items-center">
                    <flux:badge color="green" size="lg" icon="lock-open">Caja Abierta</flux:badge>
                    <flux:button variant="danger" x-on:click="$flux.modal('modal-cierre').show()">
                        <i class="fas fa-lock mr-2"></i> Cerrar Caja
                    </flux:button>
                </div>
            @endif
        </div>
    </div>

    <!-- Tabla de Cajas -->
    <div class="w-full overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
        <table class="w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900">
                <tr>
                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase text-center">Nro</th>
                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase text-left">Cajero</th>
                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase text-center">Apertura</th>
                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase text-center">M. Inicial</th>
                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase text-center">Estado</th>
                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse ($cajas as $caja)
                    <tr class="hover:bg-blue-50/50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-4 py-4 text-sm text-center">{{ $caja->id }}</td>
                        <td class="px-4 py-4 text-sm">{{ $caja->user->name }}</td>
                        <td class="px-4 py-4 text-sm text-center">{{ \Carbon\Carbon::parse($caja->fecha_apertura)->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-4 text-sm text-center font-semibold">{{ $ajuste->simbolo_moneda ?? 'Bs.' }} {{ number_format($caja->monto_inicial, 2) }}</td>
                        <td class="px-4 py-4 text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $caja->estado == 'ABIERTA' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $caja->estado }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <flux:button variant="ghost" icon="eye">Ver</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">No hay cajas registradas en este periodo.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts::app>
