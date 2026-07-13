<x-layouts::app title="Editar Consulta">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ url('/admin') }}">Inicio</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('admin.consultas.index') }}">Listado de Consultas</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Editar Consulta</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="mt-6">
        <flux:heading size="xl">Editar Consulta Médica</flux:heading>
        <flux:separator variant="subtle" class="my-4" />
    </div>

    <div class="max-w-3xl bg-white dark:bg-neutral-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg">
        <form action="{{ route('admin.consultas.update', $consulta->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">
                {{-- Paciente --}}
                <flux:select name="paciente_id" label="Paciente" icon="user">
                    @foreach($pacientes as $paciente)
                        <flux:select.option value="{{ $paciente->id }}" :selected="$consulta->paciente_id == $paciente->id">
                            {{ $paciente->nombres }} {{ $paciente->apellidos }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                {{-- Consultorio --}}
                <flux:select name="consultorio_id" label="Consultorio" icon="building-office">
                    @foreach($consultorios as $consultorio)
                        <flux:select.option value="{{ $consultorio->id }}" :selected="$consulta->consultorio_id == $consultorio->id">
                            {{ $consultorio->nombre }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                {{-- Médico --}}
                <flux:select name="usuario_id" label="Médico" icon="user-circle">
                    @foreach($medicos as $medico)
                        <flux:select.option value="{{ $medico->id }}" :selected="$consulta->usuario_id == $medico->id">
                            {{ $medico->name }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                {{-- Fecha --}}
                <flux:input name="fecha_atencion" type="datetime-local" label="Fecha y Hora" icon="calendar-days"
                    value="{{ old('fecha_atencion', \Carbon\Carbon::parse($consulta->fecha_atencion)->format('Y-m-d\TH:i')) }}" />
            </div>

            <div class="bg-gray-50 dark:bg-neutral-700 p-6 flex justify-end gap-3">
                <flux:button href="{{ route('admin.consultas.index') }}">Cancelar</flux:button>
                <flux:button type="submit" variant="primary" color="blue">
                    <i class="fas fa-save mr-2"></i> Actualizar Consulta
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::app>
