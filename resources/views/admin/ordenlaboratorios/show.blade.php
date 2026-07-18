<x-layouts::app title="Detalle de Orden #{{ $orden->id }}">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ url('/admin') }}">Inicio</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('admin.orden_laboratorios.index') }}">Listado de Órdenes</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Detalle #{{ $orden->id }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="mt-6 max-w-4xl mx-auto bg-white dark:bg-neutral-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        {{-- Encabezado --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <flux:heading size="xl">Orden de Laboratorio #{{ $orden->id }}</flux:heading>
            <flux:button href="{{ route('admin.orden_laboratorios.index') }}" variant="subtle">Volver</flux:button>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Info Paciente --}}
            <div>
                <flux:subheading>Datos del Paciente</flux:subheading>
                <div class="mt-4 p-4 bg-gray-50 dark:bg-neutral-900 rounded-lg">
                    <p class="font-semibold text-lg">{{ $orden->paciente->nombres }} {{ $orden->paciente->apellidos }}</p>
                    <p class="text-sm text-gray-500">CI: {{ $orden->paciente->ci }}</p>
                    <p class="text-sm text-gray-500 mt-2">Fecha: {{ \Carbon\Carbon::parse($orden->fecha_orden)->format('d/m/Y') }}</p>
                </div>
            </div>

            {{-- Info Pago --}}
            <div>
                <flux:subheading>Información de Pago</flux:subheading>
                <div class="mt-4 p-4 bg-blue-50 dark:bg-neutral-900 rounded-lg">
                    <p class="text-sm">Método: <span class="font-bold">{{ $orden->tipo_pago }}</span></p>
                    @if($orden->tipo_pago == 'TRANSFERENCIA')
                        <p class="text-sm">Transacción: {{ $orden->codigo_transaccion }}</p>
                    @endif
                    <div class="mt-3 text-2xl font-black text-blue-600">
                        Total: {{ $simboloMoneda }} {{ number_format($orden->total, 2) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla Detalles --}}
        <div class="p-6">
            <flux:subheading class="mb-4">Laboratorios Realizados</flux:subheading>

            <div class="bg-gray-50 dark:bg-neutral-900 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-neutral-700">
                            <th class="px-6 py-4 text-sm font-bold uppercase tracking-wider text-gray-700 dark:text-gray-200">
                                LABORATORIO
                            </th>
                            <th class="px-6 py-4 text-sm font-bold uppercase tracking-wider text-gray-700 dark:text-gray-200 text-right">
                                PRECIO
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($orden->detalles as $detalle)
                            <tr class="hover:bg-gray-100/50 dark:hover:bg-neutral-800/50 transition">
                                <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-300">
                                    {{ $detalle->laboratorio->nombre }}
                                </td>
                                <td class="px-6 py-4 text-sm text-right font-mono font-medium text-blue-600 dark:text-blue-400">
                                    {{ $simboloMoneda }} {{ number_format($detalle->precio, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts::app>
