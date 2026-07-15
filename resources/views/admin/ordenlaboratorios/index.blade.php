<x-layouts::app title="Sistema Clinico">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Órdenes de Laboratorio</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex gap-4">
        <div class="flex-1">
            <form action="{{ route('admin.orden_laboratorios.index') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar por paciente o CI..."
                        value="{{ request('buscar') }}" class="transition-all duration-200" />
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </form>
        </div>
        <div class="flex-1 justify-end flex">
            {{-- Aquí conectaremos el Create más adelante --}}
            <a href="{{ route('admin.orden_laboratorios.create') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-plus mr-2"></i> Realizar Laboratorio
            </a>
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 mt-6">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                <tr>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Nro</th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase text-left">Paciente</th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Fecha</th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @foreach ($ordenes as $orden)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-6 py-4 text-sm text-center">{{ $orden->id }}</td>
                        <td class="px-6 py-4 text-sm font-medium">{{ $orden->paciente->nombres }} {{ $orden->paciente->apellidos }}</td>
                        <td class="px-6 py-4 text-sm text-center">{{ $orden->fecha_orden }}</td>
                        <td class="px-6 py-4 text-sm text-center font-bold">{{ number_format($orden->total, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-center">
                            <span class="px-2 py-1 text-xs rounded-full {{ $orden->estado_pago == 'PAGADO' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $orden->estado_pago }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="#" class="px-3 py-1 bg-sky-500 text-white text-xs rounded hover:bg-sky-600">Ver</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts::app>
