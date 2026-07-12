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
        <div class="flex-1 justify-end flex">
            <a href="{{ url('/admin/consultorios/create') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-plus mr-2"></i> Crear nuevo
            </a>
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
                                {{-- Aquí mantendremos el espacio para tus botones futuros --}}
                                <span class="text-gray-400 text-xs italic">Pendiente</span>
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
