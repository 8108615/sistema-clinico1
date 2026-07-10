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
        <div class="flex-1 justify-end flex">
            <a href="{{ url('/admin/pacientes/create') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-plus mr-2"></i> Crear nuevo
            </a>
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
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $paciente->estado == 'ACTIVO' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-red-100 text-red-700 border border-red-200' }}">
                                {{ $paciente->estado }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            <div class="flex justify-center gap-2">
                                <a href="{{ url('/admin/pacientes/' . $paciente->id) }}" class="inline-flex items-center px-3 py-2 bg-slate-600 hover:bg-slate-700 text-white text-xs font-semibold rounded transition">
                                    <i class="fas fa-eye mr-2"></i> Ver
                                </a>
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