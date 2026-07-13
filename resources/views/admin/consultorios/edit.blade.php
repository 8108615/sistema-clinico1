<x-layouts::app title="Consultorios">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ url('/admin') }}">Inicio</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ url('/admin/consultorios') }}">Listado de Consultorios</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Editar consultorio</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="mt-6">
        <flux:heading size="xl">Editar Consultorio: {{ $consultorio->nombre }}</flux:heading>
        <flux:separator variant="subtle" class="my-4" />
    </div>

    <div class="max-w-4xl bg-white dark:bg-neutral-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg">

        {{-- Importante: Usar PUT para actualizar --}}
        <form action="{{ route('admin.consultorios.update', $consultorio->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    {{-- Nombre --}}
                    <div class="space-y-1">
                        <flux:label>Nombre del consultorio (*)</flux:label>
                        {{-- Usamos $consultorio->nombre como valor principal --}}
                        <flux:input name="nombre" icon="shield-check" value="{{ old('nombre', $consultorio->nombre) }}" />
                        <flux:error name="nombre" />
                    </div>

                    {{-- Ubicación --}}
                    <div class="space-y-1">
                        <flux:label>Ubicación (*)</flux:label>
                        <flux:input name="ubicacion" icon="map-pin" value="{{ old('ubicacion', $consultorio->ubicacion) }}" />
                        <flux:error name="ubicacion" />
                    </div>

                    {{-- Especialidad --}}
                    <div class="space-y-1">
                        <flux:label>Especialidad (*)</flux:label>
                        <flux:input name="especialidad" icon="academic-cap" value="{{ old('especialidad', $consultorio->especialidad) }}" />
                        <flux:error name="especialidad" />
                    </div>

                    {{-- Teléfono --}}
                    <div class="space-y-1">
                        <flux:label>Teléfono (*)</flux:label>
                        <flux:input name="telefono" icon="phone" type="number" value="{{ old('telefono', $consultorio->telefono) }}" />
                        <flux:error name="telefono" />
                    </div>

                    {{-- Capacidad --}}
                    <div class="space-y-1">
                        <flux:label>Capacidad de consultas (*)</flux:label>
                        <flux:input name="capacidad_consultas" icon="user-group" type="number" value="{{ old('capacidad_consultas', $consultorio->capacidad_consultas) }}" />
                        <flux:error name="capacidad_consultas" />
                    </div>

                    {{-- Estado --}}
                    <div class="space-y-1">
                        <flux:label>Estado (*)</flux:label>
                        <flux:select name="estado" icon="check-badge">
                            <flux:select.option value="ACTIVO" :selected="old('estado', $consultorio->estado) == 'ACTIVO'">ACTIVO</flux:select.option>
                            <flux:select.option value="INACTIVO" :selected="old('estado', $consultorio->estado) == 'INACTIVO'">INACTIVO</flux:select.option>
                        </flux:select>
                        <flux:error name="estado" />
                    </div>

                </div>
            </div>

            <div class="bg-gray-50 dark:bg-neutral-700 border-t border-gray-200 dark:border-gray-700 rounded-b-lg p-6 flex justify-end">
                <div class="flex space-x-3">
                    <flux:button href="{{ url('/admin/consultorios') }}">Cancelar</flux:button>
                    <flux:button type="submit" variant="primary" color="green">
                        <i class="fas fa-save mr-2"></i> Actualizar Consultorio
                    </flux:button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::app>
