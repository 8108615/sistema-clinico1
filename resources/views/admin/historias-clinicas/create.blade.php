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
                    {{-- Aquí deberías cargar tus pacientes, esto es un ejemplo --}}
                    @foreach(\App\Models\Paciente::all() as $paciente)
                        <flux:select.option value="{{ $paciente->id }}">{{ $paciente->nombres }} {{ $paciente->apellidos }} (CI: {{ $paciente->ci }})</flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            {{-- Campos SOAP --}}
            <div class="col-span-2">
                <flux:textarea name="subjetivo" label="Subjetivo" placeholder="Motivo de consulta y síntomas relatados..." rows="3" required />
            </div>

            <div class="col-span-2">
                <flux:textarea name="objetivo" label="Objetivo" placeholder="Hallazgos en el examen físico..." rows="3" required />
            </div>

            <div class="col-span-2">
                <flux:textarea name="analisis" label="Análisis" placeholder="Diagnóstico o impresión clínica..." rows="3" required />
            </div>

            <div class="col-span-2">
                <flux:textarea name="plan" label="Plan" placeholder="Tratamiento, recetas, derivaciones..." rows="3" required />
            </div>

            {{-- Datos Adicionales --}}
            <flux:input name="numero_historia" label="Número de Historia" placeholder="Ej: HC-001" required />
            
            <flux:select name="estado" label="Estado">
                <flux:select.option value="borrador">Borrador</flux:select.option>
                <flux:select.option value="finalizado">Finalizado</flux:select.option>
            </flux:select>
        </div>

        <div class="mt-6 flex justify-end gap-4">
            <flux:button href="{{ route('admin.historias_clinicas.index') }}">Cancelar</flux:button>
            <flux:button type="submit" variant="primary">Guardar Historia Clínica</flux:button>
        </div>
    </form>
</x-layouts::app>