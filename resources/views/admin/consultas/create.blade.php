<x-layouts::app title="Nueva Consulta">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ url('/admin') }}">Inicio</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('admin.consultas.index') }}">Listado de Consultas</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Registrar Consulta</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="mt-6">
        <flux:heading size="xl">Registrar Consulta Médica</flux:heading>
        <flux:separator variant="subtle" class="my-4" />
    </div>

    <div class="max-w-3xl bg-white dark:bg-neutral-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg">
        <form action="{{ route('admin.consultas.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-6">

                {{-- Paciente --}}
                <div>
                    <flux:label>Paciente <span class="text-red-500">(*)</span></flux:label>
                    <flux:select name="paciente_id" icon="user" placeholder="Seleccione un paciente...">
                        @foreach($pacientes as $paciente)
                            <flux:select.option value="{{ $paciente->id }}">{{ $paciente->nombres }} {{ $paciente->apellidos }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="paciente_id" />
                </div>

                {{-- Consultorio --}}
                <div>
                    <flux:label>Consultorio <span class="text-red-500">(*)</span></flux:label>
                    <flux:select name="consultorio_id" icon="building-office" placeholder="Seleccione un consultorio...">
                        @foreach($consultorios as $consultorio)
                            <flux:select.option value="{{ $consultorio->id }}">{{ $consultorio->nombre }} ({{ $consultorio->especialidad }})</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="consultorio_id" />
                </div>

                {{-- Médico (Usuario) --}}
                <div>
                    <flux:label>Médico <span class="text-red-500">(*)</span></flux:label>
                    <flux:select name="usuario_id" icon="user-circle" placeholder="Seleccione el médico...">
                        @foreach($medicos as $medico)
                            <flux:select.option value="{{ $medico->id }}">{{ $medico->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="usuario_id" />
                </div>

                {{-- Fecha --}}
                <div>
                    <flux:label>Fecha y Hora <span class="text-red-500">(*)</span></flux:label>
                    <flux:input name="fecha_atencion" type="datetime-local" icon="calendar-days" value="{{ old('fecha_atencion', date('Y-m-d\TH:i')) }}" />
                    <flux:error name="fecha_atencion" />
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-neutral-700 border-t border-gray-200 dark:border-gray-700 rounded-b-lg p-6 flex justify-end gap-3">
                <flux:button href="{{ route('admin.consultas.index') }}">Cancelar</flux:button>
                <flux:button type="submit" variant="primary" color="blue">
                    <i class="fas fa-save mr-2"></i> Guardar Consulta
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::app>
