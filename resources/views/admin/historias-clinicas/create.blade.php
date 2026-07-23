<x-layouts::app title="Nueva Historia Clínica">
    <div class="mb-6">
        <flux:heading size="xl" level="1">Nueva Historia Clínica</flux:heading>
        <flux:subheading>Registre los datos de la atención médica</flux:subheading>
    </div>

    <form action="{{ route('admin.historias_clinicas.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700">

            {{-- Selección de Paciente --}}
            <div class="col-span-2">
                <flux:select name="paciente_id" label="Seleccionar Paciente" placeholder="Buscar paciente..." searchable required>
                    @foreach($pacientes as $paciente)
                        <flux:select.option value="{{ $paciente->id }}" :selected="old('paciente_id') == $paciente->id">
                            {{ $paciente->nombres }} {{ $paciente->apellidos }} (CI: {{ $paciente->ci }})
                        </flux:select.option>
                    @endforeach
                </flux:select>
                @error('paciente_id')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Campos SOAP --}}
            <div class="col-span-2">
                <flux:textarea name="subjetivo" label="Subjetivo" placeholder="Motivo de consulta y síntomas relatados..." rows="3" required>{{ old('subjetivo') }}</flux:textarea>
                @error('subjetivo')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-span-2">
                <flux:textarea name="objetivo" label="Objetivo" placeholder="Hallazgos en el examen físico..." rows="3" required>{{ old('objetivo') }}</flux:textarea>
                @error('objetivo')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-span-2">
                <flux:textarea name="analisis" label="Análisis" placeholder="Diagnóstico o impresión clínica..." rows="3" required>{{ old('analisis') }}</flux:textarea>
                @error('analisis')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-span-2">
                <flux:textarea name="plan" label="Plan" placeholder="Tratamiento, recetas, derivaciones..." rows="3" required>{{ old('plan') }}</flux:textarea>
                @error('plan')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Datos Adicionales --}}
            <div>
                <flux:input name="numero_historia" label="Número de Historia" placeholder="Ej: HC-001" value="{{ old('numero_historia') }}" required />
                @error('numero_historia')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <flux:select name="estado" label="Estado" required>
                    <flux:select.option value="atendido" :selected="old('estado') == 'atendido'">Atendido</flux:select.option>
                    <flux:select.option value="anulado" :selected="old('estado') == 'anulado'">Anulado</flux:select.option>
                </flux:select>
                @error('estado')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-4">
            <flux:button href="{{ route('admin.historias_clinicas.index') }}">Cancelar</flux:button>
            <flux:button type="submit" variant="primary">Guardar Historia Clínica</flux:button>
        </div>
    </form>
</x-layouts::app>
