<x-layouts::app title="Papelera de Recetas Médicas">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Papelera de Recetas Médicas</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    {{-- Botón de retorno al listado principal --}}
    <div class="flex justify-between items-center mb-6">
        <p class="text-gray-600 dark:text-gray-400 text-sm">Listado de recetas médicas eliminadas lógicamente.</p>
        <a href="{{ route('admin.recetas.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition flex items-center gap-2 text-sm">
            <i class="fas fa-arrow-left"></i> Volver a Recetas
        </a>
    </div>

    {{-- Tabla de Recetas Eliminadas --}}
    <div class="w-full overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
        <table class="w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900">
                <tr>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Nro</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-left">Paciente</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-left">Médico</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Fecha Receta</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Fecha Eliminación</th>
                    <th class="px-4 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse ($recetas as $receta)
                    <tr class="hover:bg-red-50/30 dark:hover:bg-zinc-700/50 transition">
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

                        <td class="px-4 py-4 text-sm text-center text-red-500 font-medium">
                            {{ $receta->deleted_at->format('d/m/Y H:i') }}
                        </td>

                        <td class="px-4 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                {{-- Botón Restaurar con SweetAlert opcional o PATCH directo --}}
                                <form action="{{ route('admin.recetas.restore', $receta->id) }}" method="POST" id="formRestoreReceta{{ $receta->id }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button" class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded shadow-sm flex items-center gap-1" onclick="confirmarRestaurarReceta{{ $receta->id }}()" title="Restaurar">
                                        <i class="fas fa-trash-restore"></i> Restaurar
                                    </button>
                                </form>

                                <script>
                                    function confirmarRestaurarReceta{{ $receta->id }}() {
                                        Swal.fire({
                                            title: '¿Restaurar receta?',
                                            text: "La receta volverá a estar activa en el sistema.",
                                            icon: 'question',
                                            showDenyButton: true,
                                            confirmButtonText: 'Restaurar',
                                            confirmButtonColor: '#059669',
                                            denyButtonText: 'Cancelar'
                                        }).then((result) => {
                                            if (result.isConfirmed) document.getElementById('formRestoreReceta{{ $receta->id }}').submit();
                                        });
                                    }
                                </script>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">La papelera está vacía. No hay recetas eliminadas.</td>
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
