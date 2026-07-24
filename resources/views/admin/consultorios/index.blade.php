<x-layouts::app title="Consultorios del Sistema">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Listado de consultorios</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex gap-4">
        <div class="flex-1">
            <form action="{{ url('/admin/consultorios') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar por nombre o especialidad..."
                        value="{{ $buscar ?? '' }}" class="transition-all duration-200" />
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </form>
        </div>

        <!-- Ocultar botón de crear si no tiene el permiso -->
        <div class="flex-1 justify-end flex">
            @can('Ver formulario de creacion de consultorio')
                <a href="{{ url('/admin/consultorios/create') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
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
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase tracking-wider text-left">Nombre</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase tracking-wider text-left">Ubicación</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase tracking-wider text-left">Especialidad</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Capacidad</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Estado</th>
                    <th class="px-6 py-3 border-b text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse ($consultorios as $consultorio)
                    {{-- He subido el py a 5 para dar ese aire que buscas --}}
                    <tr class="hover:bg-blue-50/50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-6 py-5 text-sm text-center text-gray-900 dark:text-gray-100">{{ $loop->iteration }}</td>
                        <td class="px-6 py-5 text-sm text-gray-900 dark:text-gray-100">{{ $consultorio->nombre }}</td>
                        <td class="px-6 py-5 text-sm text-gray-900 dark:text-gray-100">{{ $consultorio->ubicacion }}</td>
                        <td class="px-6 py-5 text-sm text-gray-900 dark:text-gray-100">{{ $consultorio->especialidad }}</td>
                        <td class="px-6 py-5 text-sm text-center text-gray-900 dark:text-gray-100">{{ $consultorio->capacidad_consultas }}</td>
                        <td class="px-6 py-5 text-center">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $consultorio->estado == 'ACTIVO' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                {{ $consultorio->estado }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <div class="flex justify-center gap-2">
                                <!-- Botón Ver protegido -->
                                @can('Ver datos del consultorio')
                                    <a href="{{ url('/admin/consultorios/' . $consultorio->id) }}"
                                        class="inline-flex items-center px-4 py-2 bg-zinc-500 hover:bg-zinc-600 text-white text-xs font-semibold rounded transition shadow-sm">
                                        <i class="fas fa-eye mr-2"></i> Ver
                                    </a>
                                @endcan

                                <!-- Botón Editar protegido -->
                                @can('Ver formulario de edicion de consultorio')
                                    <a href="{{ url('/admin/consultorios/' . $consultorio->id . '/edit') }}"
                                        class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded transition shadow-sm">
                                        <i class="fas fa-pencil-alt mr-2"></i> Editar
                                    </a>
                                @endcan

                                <!-- Botón Eliminar protegido -->
                                @can('Eliminar consultorio')
                                    <form action="{{ url('/admin/consultorios/' . $consultorio->id) }}" method="post" id="miFormulario{{ $consultorio->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="cursor: pointer"
                                            class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded transition shadow-sm"
                                            onclick="preguntar{{ $consultorio->id }}(event)">
                                            <i class="fas fa-trash-alt mr-2"></i> Eliminar
                                        </button>
                                    </form>

                                    <script>
                                        function preguntar{{ $consultorio->id }}(event) {
                                            event.preventDefault();
                                            Swal.fire({
                                                title: '¿Desea eliminar este consultorio?',
                                                text: "Esta acción no se puede deshacer fácilmente",
                                                icon: 'question',
                                                showDenyButton: true,
                                                confirmButtonText: 'Eliminar',
                                                confirmButtonColor: '#a5161d',
                                                denyButtonColor: '#270a0a',
                                                denyButtonText: 'Cancelar',
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById('miFormulario{{ $consultorio->id }}').submit();
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
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            No hay consultorios registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $consultorios->links() }}
    </div>
</x-layouts::app>
