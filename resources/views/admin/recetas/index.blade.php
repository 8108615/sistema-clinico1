<x-layouts::app title="Recetas Médicas">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Recetas Médicas</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    {{-- Alertas de éxito con SweetAlert si vienen desde el controlador --}}
    @if (session('mensaje'))
        <script>
            Swal.fire({
                title: "{{ session('mensaje') }}",
                icon: "{{ session('icono', 'success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif

    {{-- Buscador y contador --}}
    <div class="flex flex-col gap-4 mb-6">
        <div class="flex gap-4">
            <div class="flex-1">
                <form action="{{ route('admin.recetas.index') }}" method="GET" class="flex gap-2 w-1/2">
                    <div class="flex-1">
                        <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar por nombre del paciente o CI..."
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
            <div class="flex-1 justify-end flex gap-2">
                @can('Ver papelera de recetas medicas')
                    <a href="{{ route('admin.recetas.trashed') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-trash-alt"></i> Papelera
                    </a>
                @endcan
                @can('Ver formulario de creacion de receta medica')
                    <a href="{{ route('admin.recetas.create') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-plus"></i> Nueva Receta
                    </a>
                @endcan
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

    {{-- Tabla Mejorada --}}
    <div class="w-full overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
        <table class="w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900">
                <tr>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Nro</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Nro Historia</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-left">Paciente (CI)</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-left">Médico</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Fecha</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Medicamentos</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse ($recetas as $receta)
                    <tr class="hover:bg-blue-50/50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-4 py-4 text-sm text-center">{{ $loop->iteration + ($recetas->currentPage() - 1) * $recetas->perPage() }}</td>

                        {{-- Nro de Historia Clínica --}}
                        <td class="px-4 py-4 text-sm text-center font-semibold text-blue-600 dark:text-blue-400">
                            {{ $receta->historiaClinica->numero_historia ?? 'S/N' }}
                        </td>

                        {{-- Paciente y su CI --}}
                        <td class="px-4 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $receta->paciente->nombres ?? '' }} {{ $receta->paciente->apellidos ?? '' }}
                            <div class="text-xs text-gray-500 dark:text-gray-400">CI: {{ $receta->paciente->ci ?? 'N/A' }}</div>
                        </td>

                        {{-- Médico --}}
                        <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">
                            {{ $receta->medico ? $receta->medico->name : ($receta->usuario->name ?? 'Sin Asignar') }}
                        </td>

                        {{-- Fecha Receta --}}
                        <td class="px-4 py-4 text-sm text-center text-gray-500">
                            {{ \Carbon\Carbon::parse($receta->fecha_receta)->format('d/m/Y') }}
                        </td>

                        {{-- Listado Completo de Fármacos --}}
                        <td class="px-4 py-4 text-sm text-center">
                            @forelse($receta->detalles as $detalle)
                                <div class="text-gray-900 dark:text-gray-100 mb-1 {{ !$loop->last ? 'border-b border-gray-100 dark:border-zinc-700/50 pb-1' : '' }}">
                                    <span class="font-medium">• {{ $detalle->medicamento }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">({{ $detalle->dosis }})</span>
                                </div>
                            @empty
                                <span class="text-gray-400 text-xs italic">Sin fármacos</span>
                            @endforelse
                        </td>

                        {{-- Acciones --}}
                        <td class="px-4 py-4 text-center">
                            <div class="flex justify-center items-center gap-1.5">
                                @can('Ver datos de la receta medica')
                                    <a href="{{ route('admin.recetas.show', $receta->id) }}" class="inline-flex items-center px-3 py-2 bg-sky-500 hover:bg-sky-600 text-white text-xs font-semibold rounded transition shadow-sm" title="Ver Detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @endcan

                                @can('Ver formulario de edicion de receta medica')
                                    <a href="{{ route('admin.recetas.edit', $receta->id) }}" class="inline-flex items-center px-3 py-2 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded transition shadow-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan

                                @can('Imprimir PDF receta medica')
                                    <a href="{{ route('admin.recetas.pdf', $receta->id) }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded transition shadow-sm" title="Imprimir PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                @endcan

                                @can('Eliminar receta medica')
                                    <form action="{{ route('admin.recetas.destroy', $receta->id) }}" method="POST" id="formDeleteReceta{{ $receta->id }}">
                                        @csrf @method('DELETE')
                                        <button type="button" class="inline-flex items-center px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded transition shadow-sm" onclick="confirmarEliminarReceta{{ $receta->id }}(event)" title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>

                                    <script>
                                        function confirmarEliminarReceta{{ $receta->id }}(event) {
                                            event.preventDefault();
                                            Swal.fire({
                                                title: '¿Desea eliminar receta?',
                                                text: "Se enviará a la papelera la receta del paciente: {{ $receta->paciente->nombres ?? '' }}",
                                                icon: 'question',
                                                showDenyButton: true,
                                                confirmButtonText: 'Eliminar',
                                                confirmButtonColor: '#a5161d',
                                                denyButtonColor: '#270a0a',
                                                denyButtonText: 'Cancelar'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById('formDeleteReceta{{ $receta->id }}').submit();
                                                }
                                            });
                                        }
                                    </script>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">No hay recetas médicas registradas.</td>
                    </tr>
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
