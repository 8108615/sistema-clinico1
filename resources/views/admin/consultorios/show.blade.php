<x-layouts::app title="Detalle del Consultorio">
    <flux:breadcrumbs class="mb-4">
        <flux:breadcrumbs.item href="{{ url('/admin') }}">Inicio</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ url('/admin/consultorios') }}">Listado de Consultorios</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Detalle: {{ $consultorio->nombre }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Encabezado Principal --}}
    <div class="bg-neutral-800 p-8 rounded-2xl flex items-center gap-6 mb-6">
        <div>
            <flux:heading size="xl" class="text-white">{{ $consultorio->nombre }}</flux:heading>
            <div class="flex gap-2 mt-2">
                <flux:badge color="neutral" size="sm">ID: #{{ $consultorio->id }}</flux:badge>
                <flux:badge color="{{ $consultorio->estado == 'ACTIVO' ? 'green' : 'red' }}" size="sm">
                    {{ $consultorio->estado }}
                </flux:badge>
                <flux:badge color="neutral" size="sm">Registrado: {{ $consultorio->created_at->format('Y-m-d') }}</flux:badge>
            </div>
        </div>
    </div>

    {{-- Grid de Datos --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Card Información General --}}
        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
            <flux:heading size="lg" class="mb-4 text-blue-600">Información General</flux:heading>
            <div class="space-y-4">
                <div><flux:label>NOMBRE DEL CONSULTORIO</flux:label><p>{{ $consultorio->nombre }}</p></div>
                <div><flux:label>UBICACIÓN</flux:label><p>{{ $consultorio->ubicacion }}</p></div>
                <div><flux:label>ESPECIALIDAD</flux:label><p>{{ $consultorio->especialidad }}</p></div>
            </div>
        </div>

        {{-- Card Información Técnica --}}
        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
            <flux:heading size="lg" class="mb-4 text-blue-600">Información Técnica</flux:heading>
            <div class="space-y-4">
                <div><flux:label>TELÉFONO</flux:label><p>{{ $consultorio->telefono }}</p></div>
                <div><flux:label>CAPACIDAD DE CONSULTAS</flux:label><p>{{ $consultorio->capacidad_consultas }} personas</p></div>
                <div><flux:label>ESTADO ACTUAL</flux:label><p>{{ $consultorio->estado }}</p></div>
            </div>
        </div>
    </div>

    {{-- Botón Volver --}}
    <div class="mt-6">
        <a href="{{ url('/admin/consultorios') }}" class="px-5 py-3 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Volver al listado
        </a>
    </div>
</x-layouts::app>
