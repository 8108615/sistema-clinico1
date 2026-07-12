
<x-layouts::app title="Datos del Paciente">
    <flux:breadcrumbs class="mb-4">
        <flux:breadcrumbs.item href="{{ url('/admin') }}">Inicio</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('admin.pacientes.index') }}">Listado de Pacientes</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Datos del paciente: {{ $paciente->nombres }} {{ $paciente->apellidos }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Encabezado Principal --}}
    <div class="bg-neutral-800 p-8 rounded-2xl flex items-center gap-6 mb-6">

        <div>
            <flux:heading size="xl" class="text-white">{{ $paciente->nombres }} {{ $paciente->apellidos }}</flux:heading>
            <div class="flex gap-2 mt-2">
                <flux:badge color="neutral" size="sm">ID: #{{ $paciente->id }}</flux:badge>
                <flux:badge color="{{ $paciente->estado == 'ACTIVO' ? 'green' : 'red' }}" size="sm">
                    {{ $paciente->estado }}
                </flux:badge>
                 <flux:badge color="neutral" size="sm">Fecha de Registro: {{ $paciente->created_at->format('Y-m-d') }} </flux:badge>

            </div>
        </div>
    </div>

    {{-- Grid de Datos --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Card Información Personal --}}
        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
            <flux:heading size="lg" class="mb-4 text-blue-600">Datos Personales</flux:heading>
            <div class="space-y-4">
                <div><flux:label>CÉDULA DE IDENTIDAD</flux:label><p>{{ $paciente->ci }}</p></div>
                <div><flux:label>FECHA DE NACIMIENTO</flux:label><p>{{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->format('d/m/Y') }}</p></div>
                <div><flux:label>GENERO</flux:label><p>{{ $paciente->genero }}</p></div>
                <div><flux:label>CELULAR</flux:label><p>{{ $paciente->celular }}</p></div>
                <div><flux:label>CORREO</flux:label><p>{{ $paciente->correo ?? 'No registrado' }}</p></div>
                <div><flux:label>DIRECCIÓN</flux:label><p>{{ $paciente->direccion ?? 'No registrada' }}</p></div>
            </div>
        </div>

        {{-- Card Información Clínica --}}
        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
            <flux:heading size="lg" class="mb-4 text-blue-600">Información Complementaria</flux:heading>
            <div class="space-y-4">
                <div><flux:label>GRUPO SANGUÍNEO</flux:label><p>{{ $paciente->grupo_sanguineo }}</p></div>
                <div><flux:label>PESO</flux:label><p>{{ $paciente->peso ?? 'No registrado' }}</p></div>
                <div><flux:label>TALLA</flux:label><p>{{ $paciente->talla ?? 'No registrada' }}</p></div>
                <div><flux:label>CONTACTO EMERGENCIA</flux:label><p>{{ $paciente->contacto_emergencia }} ({{ $paciente->parentesco_emergencia }})</p></div>

                <div><flux:label>ALERGIAS</flux:label><p>{{ $paciente->alergias ?? 'Ninguna' }}</p></div>
                <div><flux:label> OBSERVACIONES</flux:label><p>{{ $paciente->observaciones ?? 'Sin observaciones' }}</p></div>
            </div>
        </div>
    </div>

    {{-- Botón Volver --}}
    <div class="mt-6">
        <a href="{{ route('admin.pacientes.index') }}" class="px-5 py-3 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Volver al listado
        </a>
    </div>
</x-layouts::app>
