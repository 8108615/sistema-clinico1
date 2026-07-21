<x-layouts::app title="Papelera - Historias Clínicas">
    <div class="relative mb-6 w-full flex justify-between items-center">
        <div>
            <flux:heading size="xl" level="1">Papelera de Historias Clínicas</flux:heading>
            <p class="text-sm text-gray-500 mt-1">Registros eliminados que pueden ser restaurados.</p>
        </div>
        <div>
            <a href="{{ route('admin.historias_clinicas.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Volver al Listado
            </a>
        </div>
    </div>
    <flux:separator variant="subtle" class="mb-6" />

    {{-- Buscador --}}
    <div class="flex flex-col gap-4 mb-6">
        <div class="flex gap-4">
            <div class="flex-1">
                <form action="{{ route('admin.historias_clinicas.trashed') }}" method="GET" class="flex gap-2 w-1/2">
                    <div class="flex-1">
                        <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar en papelera por paciente..."
                            value="{{ request('buscar') }}" />
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                    @if (request('buscar'))
                        <a href="{{ route('admin.historias_clinicas.trashed') }}" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                            <i class="fas fa-trash"></i> Limpiar
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    {{-- Tabla de Eliminados --}}
    <div class="w-full overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
        <table class="w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900">
                <tr>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Nro</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-left">Paciente</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Nro. Historia</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-left">Médico</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Eliminado el</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse ($historias as $historia)
                    <tr class="hover:bg-red-50/50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-4 py-4 text-sm text-center">{{ $loop->iteration + ($historias->currentPage() - 1) * $historias->perPage() }}</td>
                        <td class="px-4 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $historia->paciente->nombres ?? 'N/A' }} {{ $historia->paciente->apellidos ?? '' }}
                        </td>
                        <td class="px-4 py-4 text-sm text-center font-mono text-red-600 dark:text-red-400">
                            {{ $historia->numero_historia }}
                        </td>
                        <td class="px-4 py-4 text-sm">{{ $historia->medico->name ?? 'Sin Asignar' }}</td>
                        <td class="px-4 py-4 text-sm text-center text-gray-500">
                            {{ $historia->deleted_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <form action="{{ route('admin.historias_clinicas.restore', $historia->id) }}" method="POST" id="formRestore{{ $historia->id }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button" 
                                            class="px-3 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded shadow-sm transition flex items-center gap-1"
                                            onclick="confirmarRestaurar{{ $historia->id }}()">
                                        <i class="fas fa-trash-restore"></i> Restaurar
                                    </button>
                                </form>

                                <script>
                                    function confirmarRestaurar{{ $historia->id }}() {
                                        Swal.fire({
                                            title: '¿Desea restaurar este registro?',
                                            text: "La historia clínica volverá al listado activo.",
                                            icon: 'question',
                                            showDenyButton: true,
                                            confirmButtonText: 'Restaurar',
                                            confirmButtonColor: '#059669',
                                            denyButtonText: 'Cancelar',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                document.getElementById('formRestore{{ $historia->id }}').submit();
                                            }
                                        });
                                    }
                                </script>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">La papelera está vacía.</td>
                    </tr>
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