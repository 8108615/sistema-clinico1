<x-layouts::app title="Detalle del Estudio">
    <flux:breadcrumbs class="mb-4">
        <flux:breadcrumbs.item href="{{ url('/admin') }}">Inicio</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('admin.laboratorios.index') }}">Listado de Laboratorios</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Estudio: {{ $laboratorio->nombre }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Encabezado Principal --}}
    <div class="bg-neutral-800 p-8 rounded-2xl flex items-center gap-6 mb-6">
        <div>
            <flux:heading size="xl" class="text-white">{{ $laboratorio->nombre }}</flux:heading>
            <div class="flex gap-2 mt-2">
                <flux:badge color="neutral" size="sm">CÓDIGO: {{ $laboratorio->codigo }}</flux:badge>
                <flux:badge color="{{ $laboratorio->estado == 'ACTIVO' ? 'green' : 'red' }}" size="sm">
                    {{ $laboratorio->estado }}
                </flux:badge>
                <flux:badge color="neutral" size="sm">Precio: {{ number_format($laboratorio->precio, 2) }}</flux:badge>
            </div>
        </div>
    </div>

    {{-- Grid de Datos --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Card Detalles Técnicos --}}
        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
            <flux:heading size="lg" class="mb-4 text-blue-600">Detalles del Estudio</flux:heading>
            <div class="space-y-4">
                <div><flux:label>CATEGORÍA</flux:label><p>{{ $laboratorio->categoria ?? 'Sin categoría' }}</p></div>
                <div><flux:label>DÍAS DE ENTREGA</flux:label><p>{{ $laboratorio->dias_entrega }} días</p></div>
                <div><flux:label>¿REQUIERE AYUNO?</flux:label><p>{{ $laboratorio->requiere_ayuno ? 'Sí' : 'No' }}</p></div>
            </div>
        </div>

        {{-- Card Descripción --}}
        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
            <flux:heading size="lg" class="mb-4 text-blue-600">Instrucciones / Descripción</flux:heading>
            <div class="space-y-4">
                <p class="text-gray-600 dark:text-gray-300">
                    {{ $laboratorio->descripcion ?? 'No se proporcionaron instrucciones adicionales.' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Botón Volver --}}
    <div class="mt-6">
        <flux:button href="{{ route('admin.laboratorios.index') }}" class="px-5 py-3 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Volver al listado
        </flux:button>
    </div>
</x-layouts::app>
