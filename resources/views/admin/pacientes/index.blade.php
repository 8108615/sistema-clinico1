<x-layouts::app title="Pacientes del Sistema">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Listado de pacientes</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex gap-4">
        <div class="flex-1">
            <form action="{{ url('/admin/pacientes') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar por nombre, apellidos o CI..."
                        value="{{ $buscar ?? '' }}" class="transition-all duration-200" />
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </form>
        </div>

        <!-- Ocultar botón de crear si no tiene el permiso -->
        <div class="flex-1 justify-end flex">
            @can('Ver formulario de creacion de paciente')
                <a href="{{ url('/admin/pacientes/create') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-plus mr-2"></i> Crear nuevo
                </a>
            @endcan
        </div>
    </div>

    <div class="w-full overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 mt-6 shadow-sm">
        <table class="w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900">
                <tr>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Nro</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase tracking-wider text-left">Nombre Completo</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase tracking-wider text-center">CI</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Fecha Nac.</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Género</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Celular</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Correo</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Estado</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse ($pacientes as $paciente)
                    <tr class="hover:bg-blue-50/50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-6 py-4 text-sm text-center text-gray-900 dark:text-gray-100">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $paciente->nombres }} {{ $paciente->apellidos }}</td>
                        <td class="px-6 py-4 text-sm text-center text-gray-900 dark:text-gray-100 whitespace-nowrap">{{ $paciente->ci }}</td>
                        <td class="px-6 py-4 text-sm text-center text-gray-900 dark:text-gray-100 whitespace-nowrap">{{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm text-center text-gray-900 dark:text-gray-100">{{ $paciente->genero }}</td>
                        <td class="px-6 py-4 text-sm text-center text-gray-900 dark:text-gray-100 whitespace-nowrap">{{ $paciente->celular }}</td>
                        <td class="px-6 py-4 text-sm text-center text-gray-900 dark:text-gray-100">{{ $paciente->correo }}</td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $paciente->estado == 'ACTIVO' ? 'bg-green-500 text-white border border-emerald-200' : 'bg-red-500 text-white border border-red-200' }}">
                                {{ $paciente->estado }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            <div class="flex justify-center gap-2">
                                <!-- Botón Ver protegido -->
                                @can('Ver datos del paciente')
                                    <a href="{{ url('/admin/pacientes/' . $paciente->id) }}"
                                        class="inline-flex items-center px-4 py-2 bg-zinc-500 hover:bg-zinc-600 text-white text-xs font-semibold rounded transition shadow-sm">
                                        <i class="fas fa-eye mr-2"></i> Ver
                                    </a>
                                @endcan

                                <!-- Botón Editar protegido -->
                                @can('Ver formulario de edicion de paciente')
                                    <a href="{{ url('/admin/pacientes/' . $paciente->id . '/edit') }}"
                                        class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded transition shadow-sm">
                                        <i class="fas fa-pencil-alt mr-2"></i> Editar
                                    </a>
                                @endcan

                                <!-- Botón Eliminar protegido -->
                                @can('Eliminar paciente')
                                    <form action="{{ url('/admin/pacientes/' . $paciente->id) }}" method="post" id="miFormulario{{ $paciente->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="cursor: pointer"
                                            class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded transition shadow-sm"
                                            onclick="preguntar{{ $paciente->id }}(event)">
                                            <i class="fas fa-trash-alt mr-2"></i> Eliminar
                                        </button>
                                    </form>

                                    <script>
                                        function preguntar{{ $paciente->id }}(event) {
                                            event.preventDefault();
                                            Swal.fire({
                                                title: '¿Desea eliminar este paciente?',
                                                text: "Esta acción no se puede deshacer fácilmente",
                                                icon: 'question',
                                                showDenyButton: true,
                                                confirmButtonText: 'Eliminar',
                                                confirmButtonColor: '#a5161d',
                                                denyButtonColor: '#270a0a',
                                                denyButtonText: 'Cancelar',
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById('miFormulario{{ $paciente->id }}').submit();
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
                        <td colspan="9" class="px-6 py-10 text-center text-gray-500">No se encontraron pacientes registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $pacientes->links() }}
    </div>
</x-layouts::app>
