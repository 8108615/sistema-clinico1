<x-layouts::app title="Sistema Clinico">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Laboratorios del sistema</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex gap-4">
        <div class="flex-1">
            <form action="{{ url('/admin/laboratorios') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar laboratorios..."
                        value="{{ request('buscar') }}" />
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-search"></i> Buscar
                </button>
                @if (request('buscar'))
                    <a href="{{ url('/admin/laboratorios') }}" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-trash"></i> Limpiar
                    </a>
                @endif
            </form>
        </div>
        <div class="flex-1 justify-end flex">
            <a href="{{ url('/admin/laboratorios/create') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-plus mr-2"></i> Crear nuevo
            </a>
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 mt-6 shadow-sm">
    <table class="min-w-full border-collapse">
        <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
            <tr>
                <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase">Nro</th>
                <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase text-left">Código Estudio</th>
                <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase text-left">Categoría</th>
                <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase text-left">Nombre del estudio</th>
                <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Días de Entrega</th>
                <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Precio</th>
                <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Estado</th>
                <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
            @forelse ($laboratorios as $lab)
                <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                    <td class="px-6 py-4 text-sm text-center">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 text-sm">{{ $lab->codigo }}</td>
                    <td class="px-6 py-4 text-sm">{{ $lab->categoria }}</td>
                    <td class="px-6 py-4 text-sm font-medium">{{ $lab->nombre }}</td>
                    <td class="px-6 py-4 text-sm text-center">{{ $lab->dias_entrega }}</td>
                    <td class="px-6 py-4 text-sm text-center font-bold">
                        @if($ajuste && $ajuste->divisa)
                            {{ $ajuste->divisa }}
                        @endif
                        {{ number_format($lab->precio, 2) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-center">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $lab->estado == 'ACTIVO' ? 'bg-green-500 text-white border border-emerald-200' : 'bg-red-500 text-white border border-red-200' }}">
                                {{ $lab->estado }}
                            </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        {{-- Aquí irán tus botones de Acciones --}}
                        <div class="flex justify-center gap-2">
                            {{-- Botón Ver --}}
                            <a href="{{ route('admin.laboratorios.show', $lab->id) }}" class="inline-flex items-center px-3 py-1.5 bg-zinc-500 hover:bg-zinc-600 text-white text-xs font-semibold rounded transition shadow-sm">
                                <i class="fas fa-eye mr-2"></i> Ver
                            </a>
                            {{-- Botón Editar --}}
                             <a href="{{ route('admin.laboratorios.edit', $lab->id) }}" class="inline-flex items-center px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded transition shadow-sm">
                                <i class="fas fa-edit mr-2"></i> Editar
                            </a>
                            {{-- Botón Eliminar  --}}
                            <form action="{{ route('admin.laboratorios.destroy', $lab->id) }}" method="POST" id="formDelete{{ $lab->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded transition shadow-sm"
                                    onclick="confirmDelete{{ $lab->id }}()">
                                    <i class="fas fa-trash-alt mr-2"></i> Eliminar
                                </button>
                            </form>

                            <script>
                                function confirmDelete{{ $lab->id }}() {
                                    Swal.fire({
                                        title: '¿Eliminar laboratorio  {{ $lab->nombre }} ?',
                                        text: "Esta acción no se puede deshacer",
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#d33',
                                        cancelButtonColor: '#3085d6',
                                        confirmButtonText: 'Sí, eliminar'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            document.getElementById('formDelete{{ $lab->id }}').submit();
                                        }
                                    });
                                }
                            </script>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">No hay laboratorios registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

    {{ $laboratorios->links() }}
</x-layouts::app>
