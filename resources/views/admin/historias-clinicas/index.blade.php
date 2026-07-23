<x-layouts::app title="Historias Clínicas">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Historias Clínicas</flux:heading>
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
                <form action="{{ route('admin.historias_clinicas.index') }}" method="GET" class="flex gap-2 w-1/2">
                    <div class="flex-1">
                        <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar por nombre del paciente..."
                            value="{{ request('buscar') }}" />
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                    @if (request('buscar'))
                        <a href="{{ route('admin.historias_clinicas.index') }}" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                            <i class="fas fa-trash"></i> Limpiar
                        </a>
                    @endif
                </form>
            </div>
            <div class="flex-1 justify-end flex gap-2">
                <a href="{{ route('admin.historias_clinicas.trashed') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-trash-alt"></i> Papelera
                </a>
                <a href="{{ route('admin.historias_clinicas.create') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-plus"></i> Nueva Historia
                </a>
            </div>
        </div>

        @if (request('buscar'))
            <div class="p-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg">
                <p class="text-gray-700 dark:text-gray-300">
                    Se {{ $historias->total() == 1 ? 'encontró' : 'encontraron' }}
                    <span class="font-semibold text-blue-600">{{ $historias->total() }}</span>
                    {{ $historias->total() == 1 ? 'resultado' : 'resultados' }} con la búsqueda:
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
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Nro. Historia</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-left">Médico</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Fecha</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Estado</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse ($historias as $historia)
                    <tr class="hover:bg-blue-50/50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-4 py-4 text-sm text-center">{{ $loop->iteration + ($historias->currentPage() - 1) * $historias->perPage() }}</td>

                        <td class="px-4 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $historia->paciente->nombres }} {{ $historia->paciente->apellidos }}
                        </td>

                        <td class="px-4 py-4 text-sm text-center font-mono text-blue-600 dark:text-blue-400">
                            {{ $historia->numero_historia }}
                        </td>

                        <td class="px-4 py-4 text-sm">
                            {{ $historia->medico ? $historia->medico->name : 'Sin Asignar' }}
                        </td>

                        <td class="px-4 py-4 text-sm text-center text-gray-500">
                            {{ \Carbon\Carbon::parse($historia->created_at)->format('d/m/Y H:i') }}
                        </td>

                        <td class="px-4 py-4 text-sm text-center">
                            <span class="px-2 py-1 rounded text-xs font-bold
                                {{ $historia->estado == 'atendido' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($historia->estado) }}
                            </span>
                        </td>

                        <td class="px-4 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.historias_clinicas.edit', $historia->id) }}" class="px-3 py-2 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded shadow-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a href="{{ route('admin.historias_clinicas.pdf', $historia->id) }}" target="_blank" class="px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded shadow-sm" title="Imprimir PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>

                                {{-- Botón Eliminar con lógica SweetAlert --}}
                                <form action="{{ route('admin.historias_clinicas.destroy', $historia->id) }}" method="POST" id="formDelete{{ $historia->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded shadow-sm" onclick="confirmarEliminar{{ $historia->id }}()" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>

                                {{-- Script de confirmación (SweetAlert) --}}
                                <script>
                                    function confirmarEliminar{{ $historia->id }}() {
                                        Swal.fire({
                                            title: '¿Eliminar?',
                                            text: "Se borrará la historia Nro: {{ $historia->numero_historia }}",
                                            icon: 'warning',
                                            showDenyButton: true,
                                            confirmButtonText: 'Eliminar',
                                            confirmButtonColor: '#a5161d',
                                            denyButtonText: 'Cancelar'
                                        }).then((result) => {
                                            if (result.isConfirmed) document.getElementById('formDelete{{ $historia->id }}').submit();
                                        });
                                    }
                                </script>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-12 text-center text-gray-500">No hay registros encontrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if ($historias->hasPages())
        <div class="mt-6 flex justify-between items-center px-2">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Mostrando <span class="font-semibold">{{ $historias->firstItem() }}</span> al
                <span class="font-semibold">{{ $historias->lastItem() }}</span> de
                <span class="font-semibold">{{ $historias->total() }}</span> resultados
            </div>
            <div>
                {{ $historias->links() }}
            </div>
        </div>
    @endif
</x-layouts::app>
