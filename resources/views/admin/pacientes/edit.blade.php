<x-layouts::app title="Editar Paciente">
    <div class="mb-6 w-full">
        <flux:heading size="xl">Editar Paciente: {{ $paciente->nombres }} {{ $paciente->apellidos }}</flux:heading>
        <flux:separator variant="subtle" class="my-4" />
    </div>

    <form action="{{ route('admin.pacientes.update', $paciente->id) }}" method="POST">
        @csrf
        @method('PUT') {{-- Esto es crucial para la ruta de actualización --}}

        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700">

            {{-- INFORMACIÓN PERSONAL --}}
            <flux:heading size="lg" class="text-blue-600 mb-4 border-b pb-2">Información Personal</flux:heading>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div>
                    <flux:label><i class="fas fa-user mr-2 text-gray-400"></i>Nombre (*)</flux:label>
                    <flux:input name="nombres" value="{{ old('nombres', $paciente->nombres) }}" required />
                    <flux:error name="nombres" />
                </div>

                <div>
                    <flux:label><i class="fas fa-user mr-2 text-gray-400"></i>Apellido (*)</flux:label>
                    <flux:input name="apellidos" value="{{ old('apellidos', $paciente->apellidos) }}" required />
                    <flux:error name="apellidos" />
                </div>

                <div>
                    <flux:label><i class="fas fa-id-card mr-2 text-gray-400"></i>Cédula de identidad (*)</flux:label>
                    <flux:input name="ci" value="{{ old('ci', $paciente->ci) }}" required />
                    <flux:error name="ci" />
                </div>

                <div>
                    <flux:label><i class="fas fa-calendar mr-2 text-gray-400"></i>Fecha de Nacimiento (*)</flux:label>
                    <flux:input name="fecha_nacimiento" type="date" value="{{ old('fecha_nacimiento', $paciente->fecha_nacimiento) }}" required />
                    <flux:error name="fecha_nacimiento" />
                </div>

                <div>
                    <flux:select name="genero" label="Género (*)" icon="user-group" required>
                        <flux:select.option value="MASCULINO" :selected="old('genero', $paciente->genero) == 'MASCULINO'">Masculino</flux:select.option>
                        <flux:select.option value="FEMENINO" :selected="old('genero', $paciente->genero) == 'FEMENINO'">Femenino</flux:select.option>
                    </flux:select>
                    <flux:error name="genero" />
                </div>

                <div>
                    <flux:label><i class="fas fa-phone mr-2 text-gray-400"></i>Celular (*)</flux:label>
                    <flux:input name="celular" value="{{ old('celular', $paciente->celular) }}" required />
                    <flux:error name="celular" />
                </div>

                <div class="md:col-span-2">
                    <flux:label><i class="fas fa-envelope mr-2 text-gray-400"></i>Correo Electrónico</flux:label>
                    <flux:input name="correo" type="email" value="{{ old('correo', $paciente->correo) }}" />
                    <flux:error name="correo" />
                </div>

                <div>
                    <flux:label><i class="fas fa-map-pin mr-2 text-gray-400"></i>Dirección</flux:label>
                    <flux:input name="direccion" value="{{ old('direccion', $paciente->direccion) }}" />
                    <flux:error name="direccion" />
                </div>

                <div>

                    <flux:select name="estado"  label="Estado (*)"  required>
                        <flux:select.option value="ACTIVO" :selected="old('estado', $paciente->estado) == 'ACTIVO'">Activo</flux:select.option>
                        <flux:select.option value="INACTIVO" :selected="old('estado', $paciente->estado) == 'INACTIVO'">Inactivo</flux:select.option>
                    </flux:select>
                    <flux:error name="estado" />
                </div>
            </div>

            {{-- DATOS CLÍNICOS --}}
            <br>
            <flux:heading size="lg" class="text-blue-600 mb-4 border-b pb-2">Datos Clínicos y Emergencia</flux:heading>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                <div>

                    <flux:select name="grupo_sanguineo" label="Grupo Sanguíneo (*)" required>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $gs)
                            <flux:select.option value="{{ $gs }}"
                                :selected="old('grupo_sanguineo', $paciente->grupo_sanguineo) == $gs">
                                {{ $gs }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="grupo_sanguineo" />
                </div>

                <flux:input name="peso" label="Peso (kg)" type="number" step="0.01" value="{{ old('peso', $paciente->peso) }}" />
                <flux:input name="talla" label="Talla (cm)" type="number" step="0.01" value="{{ old('talla', $paciente->talla) }}" />

                <div class="md:col-span-2">
                    <flux:label><i class="fas fa-phone-volume mr-2 text-gray-400"></i>Contacto de Emergencia (*)</flux:label>
                    <flux:input name="contacto_emergencia" value="{{ old('contacto_emergencia', $paciente->contacto_emergencia) }}" required />
                    <flux:error name="contacto_emergencia" />
                </div>

                <div>
                    <flux:label><i class="fas fa-users mr-2 text-gray-400"></i>Parentesco (*)</flux:label>
                    <flux:input name="parentesco_emergencia" value="{{ old('parentesco_emergencia', $paciente->parentesco_emergencia) }}" required />
                    <flux:error name="parentesco_emergencia" />
                </div>
            </div>

            <div class="grid grid-cols-1 mt-6 gap-4">
                <flux:textarea name="alergias" label="Alergias">
                    {{ old('alergias', $paciente->alergias) }}
                </flux:textarea>
                <flux:textarea name="observaciones" label="Observaciones">
                    {{ old('observaciones', $paciente->observaciones) }}
                </flux:textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <flux:button href="{{ route('admin.pacientes.index') }}">Cancelar</flux:button>
            <flux:button type="submit" variant="primary" color="green">Actualizar Paciente</flux:button>
        </div>
    </form>
</x-layouts::app>
