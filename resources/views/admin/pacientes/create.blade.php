<x-layouts::app title="Registrar Paciente">
    <div class="max-w-6xl mx-auto py-6">
        <flux:heading size="xl" class="mb-6">Registro de Paciente</flux:heading>
        
        <form action="{{ route('admin.pacientes.store') }}" method="POST" class="bg-white dark:bg-zinc-800 p-8 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <flux:input name="nombres" label="Nombres *" required value="{{ old('nombres') }}" />
                <flux:input name="apellidos" label="Apellidos *" required value="{{ old('apellidos') }}" />
                <flux:input name="ci" label="CI *" required value="{{ old('ci') }}" />

                <flux:input name="fecha_nacimiento" type="date" label="Fecha de Nacimiento *" required value="{{ old('fecha_nacimiento') }}" />
                <flux:select name="genero" label="Género *" required>
                    <option value="MASCULINO" {{ old('genero') == 'MASCULINO' ? 'selected' : '' }}>MASCULINO</option>
                    <option value="FEMENINO" {{ old('genero') == 'FEMENINO' ? 'selected' : '' }}>FEMENINO</option>
                </flux:select>
                <flux:input name="celular" label="Celular *" required value="{{ old('celular') }}" />

                <flux:input name="correo" type="email" label="Correo Electrónico" value="{{ old('correo') }}" />
                <flux:input name="peso" label="Peso (kg)" type="number" step="0.01" value="{{ old('peso') }}" />
                <flux:input name="talla" label="Talla (cm)" type="number" step="0.01" value="{{ old('talla') }}" />

                <div class="md:col-span-2">
                    <flux:input name="direccion" label="Dirección" value="{{ old('direccion') }}" />
                </div>
                <flux:select name="grupo_sanguineo" label="Grupo Sanguíneo *" required>
                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $gs)
                        <option value="{{ $gs }}" {{ old('grupo_sanguineo') == $gs ? 'selected' : '' }}>{{ $gs }}</option>
                    @endforeach
                </flux:select>

                <div class="md:col-span-3">
                    <flux:textarea name="alergias" label="Alergias" value="{{ old('alergias') }}" />
                </div>
                
                <flux:input name="contacto_emergencia" label="Contacto de Emergencia *" required value="{{ old('contacto_emergencia') }}" />
                <flux:input name="parentesco_emergencia" label="Parentesco *" required value="{{ old('parentesco_emergencia') }}" />
                <div></div>

                <div class="md:col-span-3">
                    <flux:textarea name="observaciones" label="Observaciones" value="{{ old('observaciones') }}" />
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3 border-t pt-6">
                <a href="{{ route('admin.pacientes.index') }}" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition">Cancelar</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    Registrar Paciente
                </button>
            </div>
        </form>
    </div>
</x-layouts::app>