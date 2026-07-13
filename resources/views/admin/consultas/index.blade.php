<x-layouts::app title="Consultas Médicas">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Historial de consultas</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    {{-- Buscador --}}
    <div class="flex gap-4 mb-6">
        <div class="flex-1">
            <form action="{{ route('admin.consultas.index') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar por nombre del paciente..."
                        value="{{ $buscar ?? '' }}" />
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </form>
        </div>
        <div class="flex-1 justify-end flex">
            <a href="{{ url('/admin/consultas/create') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Nueva Consulta
            </a>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="w-full overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
        <table class="w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900">
                <tr>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Nro</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase text-left">Paciente</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase text-left">Consultorio</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase text-left">Médico</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Fecha</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Precio</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse ($consultas as $consulta)
                    <tr class="hover:bg-blue-50/50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-6 py-4 text-sm text-center">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm">{{ $consulta->paciente->nombres }} {{ $consulta->paciente->apellidos }}</td>
                        <td class="px-6 py-4 text-sm">{{ $consulta->consultorio->nombre }}</td>
                        <td class="px-6 py-4 text-sm">{{ $consulta->usuario->name }}</td>
                        <td class="px-6 py-4 text-sm text-center">{{ \Carbon\Carbon::parse($consulta->fecha_atencion)->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-sm text-center font-bold">
                            @if($ajuste && $ajuste->divisa)
                                {{ $ajuste->divisa }}
                            @endif
                            {{ number_format($consulta->precio, 2) }}
                        </td>

                        {{-- LOS BOTONES VAN AQUÍ, DENTRO DEL FORELSE --}}
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.consultas.edit', $consulta->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded transition shadow-sm">
                                    <i class="fas fa-edit mr-2"></i> Editar
                                </a>
                                {{-- Botón Imprimir --}}
                                <a href="{{ route('admin.consultas.ticket', $consulta->id) }}" target="_blank"
                                    class="inline-flex items-center px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white text-xs font-semibold rounded transition shadow-sm"
                                    title="Imprimir Ticket">
                                        <i class="fas fa-print"></i> Imprimir Ticket
                                </a>

                                <form action="{{ route('admin.consultas.destroy', $consulta->id) }}" method="POST" id="miFormulario{{ $consulta->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded transition shadow-sm"
                                        onclick="preguntar{{ $consulta->id }}(event)">
                                        <i class="fas fa-trash-alt mr-2"></i> Eliminar
                                    </button>
                                </form>

                                <script>
                                    function preguntar{{ $consulta->id }}(event) {
                                        event.preventDefault();
                                        Swal.fire({
                                            title: '¿Desea eliminar esta consulta?',
                                            text: "Esta acción no se puede deshacer",
                                            icon: 'question',
                                            showDenyButton: true,
                                            confirmButtonText: 'Eliminar',
                                            confirmButtonColor: '#a5161d',
                                            denyButtonColor: '#270a0a',
                                            denyButtonText: 'Cancelar',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                document.getElementById('miFormulario{{ $consulta->id }}').submit();
                                            }
                                        });
                                    }
                                </script>
                            </div>
                        </td>
                    </tr>
                @empty
                    {{-- ESTE ES EL MENSAJE SI NO HAY DATOS --}}
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">No hay consultas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="mt-6">
        {{ $consultas->links() }}
    </div>
</x-layouts::app>
