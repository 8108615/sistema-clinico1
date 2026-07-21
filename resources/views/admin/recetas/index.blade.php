<x-layouts::app title="Recetas Médicas">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Recetas Médicas</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    {{-- Buscador y contador --}}
    <div class="flex flex-col gap-4 mb-6">
        <div class="flex gap-4">
            <div class="flex-1">
                <form action="{{ route('admin.recetas.index') }}" method="GET" class="flex gap-2 w-1/2">
                    <div class="flex-1">
                        <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar por nombre del paciente..."
                            value="{{ request('buscar') }}" />
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                    @if (request('buscar'))
                        <a href="{{ route('admin.recetas.index') }}" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                            <i class="fas fa-trash"></i> Limpiar
                        </a>
                    @endif
                </form>
            </div>
            <div class="flex-1 justify-end flex">
                <a href="{{ route('admin.recetas.trashed') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-trash-alt"></i> Papelera
                </a>
                <a href="{{ route('admin.recetas.create') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-plus"></i> Nueva Receta
                </a>
            </div>
        </div>

        @if (request('buscar'))
            <div class="p-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg">
                <p class="text-gray-700 dark:text-gray-300">
                    Se {{ $recetas->total() == 1 ? 'encontró' : 'encontraron' }} 
                    <span class="font-semibold text-blue-600">{{ $recetas->total() }}</span> 
                    {{ $recetas->total() == 1 ? 'resultado' : 'resultados' }} con la búsqueda: 
                    <span class="font-semibold">"{{ request('buscar') }}"</span>
                </p>
            </div>
        @endif
    </div>

    {{-- Tabla --}}
    <div class="w-full overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
        <table class="w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900">
                <tr>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Nro</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-left">Paciente</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-left">Médico</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Fecha Receta</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Fármacos</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse ($recetas as $receta)
                    <tr class="hover:bg-blue-50/50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-4 py-4 text-sm text-center">{{ $loop->iteration + ($recetas->currentPage() - 1) * $recetas->perPage() }}</td>
                        
                        <td class="px-4 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $receta->paciente->nombres ?? '' }} {{ $receta->paciente->apellidos ?? '' }}
                        </td>
                        
                        <td class="px-4 py-4 text-sm">
                            {{ $receta->medico ? $receta->medico->name : 'Sin Asignar' }}
                        </td>
                        
                        <td class="px-4 py-4 text-sm text-center text-gray-500">
                            {{ \Carbon\Carbon::parse($receta->fecha_receta)->format('d/m/Y') }}
                        </td>

                        <td class="px-4 py-4 text-sm text-center">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded text-xs font-bold">
                                {{ $receta->detalles->count() }} ítems
                            </span>
                        </td>
                        
                        <td class="px-4 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.recetas.edit', $receta->id) }}" class="px-3 py-2 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded shadow-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a href="{{ route('admin.recetas.pdf', $receta->id) }}" target="_blank" class="px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded shadow-sm" title="Imprimir PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                
                                {{-- Botón Eliminar con SweetAlert --}}
                                <form action="{{ route('admin.recetas.destroy', $receta->id) }}" method="POST" id="formDeleteReceta{{ $receta->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded shadow-sm" onclick="confirmarEliminarReceta{{ $receta->id }}()" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                
                                <script>
                                    function confirmarEliminarReceta{{ $receta->id }}() {
                                        Swal.fire({
                                            title: '¿Eliminar receta?',
                                            text: "Se enviará a la papelera la receta del paciente: {{ $receta->paciente->nombres ?? '' }}",
                                            icon: 'warning',
                                            showDenyButton: true,
                                            confirmButtonText: 'Eliminar',
                                            confirmButtonColor: '#a5161d',
                                            denyButtonText: 'Cancelar'
                                        }).then((result) => {
                                            if (result.isConfirmed) document.getElementById('formDeleteReceta{{ $receta->id }}').submit();
                                        });
                                    }
                                </script>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No hay recetas médicas registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if ($recetas->hasPages())
        <div class="mt-6 flex justify-between items-center px-2">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Mostrando <span class="font-semibold">{{ $recetas->firstItem() }}</span> al 
                <span class="font-semibold">{{ $recetas->lastItem() }}</span> de 
                <span class="font-semibold">{{ $recetas->total() }}</span> resultados
            </div>
            <div>
                {{ $recetas->links() }}
            </div>
        </div>
    @endif
</x-layouts::app>