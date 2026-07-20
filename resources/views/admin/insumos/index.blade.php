<x-layouts::app title="Gestión de Insumos">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Inventario de Insumos</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    {{-- Buscador y botón nuevo --}}
    <div class="flex gap-4 mb-6">
        <div class="flex-1">
            <form action="{{ route('admin.insumos.index') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar por nombre de insumo..."
                        value="{{ $buscar ?? '' }}" />
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </form>
        </div>
        <div class="flex-1 justify-end flex">
            <a href="{{ route('admin.insumos.create') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Nuevo Insumo
            </a>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="w-full overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
        <table class="w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900">
                <tr>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Nro</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase text-left">Insumo</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase text-left">Categoría</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Stock</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Precio</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse ($insumos as $insumo)
                    <tr class="hover:bg-blue-50/50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-6 py-4 text-sm text-center">{{ $loop->iteration + ($insumos->currentPage() - 1) * $insumos->perPage() }}</td>
                        <td class="px-6 py-4 text-sm font-medium">{{ $insumo->nombre }}</td>
                        <td class="px-6 py-4 text-sm">{{ $insumo->categoria->nombre }}</td>
                        <td class="px-6 py-4 text-sm text-center">
                            @if($insumo->stock <= $insumo->stock_minimo)
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold">{{ $insumo->stock }}</span>
                            @else
                                <span class="font-bold">{{ $insumo->stock }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-center">{{ $simboloMoneda }} {{ number_format($insumo->precio_compra, 2) }}</td>
                        
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.insumos.edit', $insumo->id) }}"
                                   class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded transition shadow-sm">
                                    <i class="fas fa-edit mr-2"></i> Editar
                                </a>

                                <form action="{{ route('admin.insumos.destroy', $insumo->id) }}" method="POST" id="formDelete{{ $insumo->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded transition shadow-sm"
                                            onclick="confirmarEliminacion{{ $insumo->id }}(event)">
                                        <i class="fas fa-trash-alt mr-2"></i> Eliminar
                                    </button>
                                </form>

                                <script>
                                    function confirmarEliminacion{{ $insumo->id }}(event) {
                                        event.preventDefault();
                                        Swal.fire({
                                            title: '¿Eliminar este insumo?',
                                            text: "Esta acción no se puede deshacer",
                                            icon: 'warning',
                                            showDenyButton: true,
                                            confirmButtonText: 'Eliminar',
                                            confirmButtonColor: '#a5161d',
                                            denyButtonText: 'Cancelar',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                document.getElementById('formDelete{{ $insumo->id }}').submit();
                                            }
                                        });
                                    }
                                </script>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">No hay insumos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $insumos->links() }}
    </div>
</x-layouts::app>