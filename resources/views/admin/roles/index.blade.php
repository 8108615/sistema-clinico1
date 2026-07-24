<x-layouts::app title="Roles del sistema">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Roles del Sistema</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    {{-- Buscador y botón nuevo --}}
    <div class="flex gap-4 mb-6">
        <div class="flex-1">
            <form action="{{ route('admin.roles.index') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar roles..."
                        value="{{ request('buscar') }}" />
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-search"></i> Buscar
                </button>
                @if (request('buscar'))
                    <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-trash"></i> Limpiar
                    </a>
                @endif
            </form>
        </div>
        <div class="flex-1 justify-end flex">
            {{-- Puedes envolver con @can('crear-rol') si utilizas Spatie Permissons --}}
            <a href="{{ route('admin.roles.create') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Crear Nuevo
            </a>
        </div>
    </div>

    {{-- Alerta de resultados de búsqueda --}}
    @if (request('buscar'))
        <div class="mb-6 p-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg">
            <p class="text-sm text-gray-700 dark:text-gray-300">
                <i class="fas fa-search mr-2"></i>
                Se {{ $roles->total() == 1 ? 'encontró' : 'encontraron' }}
                <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $roles->total() }}</span>
                {{ $roles->total() == 1 ? 'resultado' : 'resultados' }}
                con la búsqueda: <span class="font-semibold">"{{ request('buscar') }}"</span>
            </p>
        </div>
    @endif

    {{-- Tabla --}}
    <div class="w-full overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
        <table class="w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900">
                <tr>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Nro</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase text-left">Rol</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse ($roles as $rol)
                    <tr class="hover:bg-blue-50/50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-6 py-4 text-sm text-center">
                            {{ $loop->iteration + ($roles->currentPage() - 1) * $roles->perPage() }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">{{ $rol->name }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                {{-- Botón Ver --}}
                                <a href="{{ route('admin.roles.show', $rol->id) }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-xs font-semibold rounded transition shadow-sm">
                                    <i class="fas fa-eye mr-1.5"></i> Ver
                                </a>

                                {{-- Botón Permisos --}}
                                <a href="{{ route('admin.roles.permisos', $rol->id) }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-semibold rounded transition shadow-sm">
                                    <i class="fas fa-key mr-1.5"></i> Permisos
                                </a>

                                {{-- Botón Editar --}}
                                <a href="{{ route('admin.roles.edit', $rol->id) }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded transition shadow-sm">
                                    <i class="fas fa-edit mr-1.5"></i> Editar
                                </a>

                                {{-- Botón Eliminar con SweetAlert2 --}}
                                <form action="{{ route('admin.roles.destroy', $rol->id) }}" method="POST" id="formDeleteRole{{ $rol->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded transition shadow-sm"
                                            onclick="confirmarEliminacionRol{{ $rol->id }}(event)">
                                        <i class="fas fa-trash-alt mr-1.5"></i> Eliminar
                                    </button>
                                </form>

                                <script>
                                    function confirmarEliminacionRol{{ $rol->id }}(event) {
                                        event.preventDefault();
                                        Swal.fire({
                                            title: '¿Desea eliminar este rol?',
                                            text: "Esta acción no se puede deshacer",
                                            icon: 'warning',
                                            showDenyButton: true,
                                            confirmButtonText: 'Eliminar',
                                            confirmButtonColor: '#a5161d',
                                            denyButtonText: 'Cancelar',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                document.getElementById('formDeleteRole{{ $rol->id }}').submit();
                                            }
                                        });
                                    }
                                </script>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-gray-500">No hay roles registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación e info de registros --}}
    @if ($roles->hasPages())
        <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="text-gray-600 dark:text-gray-400 text-sm">
                Mostrando
                <span class="font-semibold">{{ $roles->firstItem() }}</span>
                al
                <span class="font-semibold">{{ $roles->lastItem() }}</span>
                de
                <span class="font-semibold">{{ $roles->total() }}</span>
                resultados.
            </div>
            <div>
                {{ $roles->links() }}
            </div>
        </div>
    @endif
</x-layouts::app>
