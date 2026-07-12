<x-layouts::app title="Consultorios">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ url('/admin') }}">Inicio</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ url('/admin/consultorios') }}">Listado de Consultorios</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Creación de un nuevo consultorio</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="mt-6">
        <flux:heading size="xl">Registrar Consultorio</flux:heading>
        <flux:separator variant="subtle" class="my-4" />
    </div>

    {{-- Card --}}
    <div class="max-w-4xl bg-white dark:bg-neutral-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg">

        <form action="{{ url('/admin/consultorios/create') }}" method="POST">
            @csrf
            {{-- Body --}}
            <div class="p-6">
                {{-- Grid responsivo: 3 columnas en escritorio, 1 en móvil --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    {{-- Nombre --}}
                    <div class="space-y-1">
                        <flux:label>Nombre del consultorio <span class="text-red-500">(*)</span></flux:label>
                        <flux:input name="nombre" icon="shield-check" value="{{ old('nombre') }}" placeholder="Nombre del consultorio..." />
                        <flux:error name="nombre" />
                    </div>

                    {{-- Ubicación --}}
                    <div class="space-y-1">
                        <flux:label>Ubicación <span class="text-red-500">(*)</span></flux:label>
                        <flux:input name="ubicacion" icon="map-pin" value="{{ old('ubicacion') }}" placeholder="A1, A2..." />
                        <flux:error name="ubicacion" />
                    </div>

                    {{-- Especialidad --}}
                    <div class="space-y-1">
                        <flux:label>Especialidad <span class="text-red-500">(*)</span></flux:label>
                        <flux:input name="especialidad" icon="academic-cap" value="{{ old('especialidad') }}" placeholder="Ej. Pediatría..." />
                        <flux:error name="especialidad" />
                    </div>

                    {{-- Teléfono --}}
                    <div class="space-y-1">
                        <flux:label>Teléfono <span class="text-red-500">(*)</span></flux:label>
                        <flux:input name="telefono" icon="phone" type="number" value="{{ old('telefono') }}" placeholder="Ej. 12345678..." />
                        <flux:error name="telefono" />
                    </div>

                    {{-- Capacidad --}}
                    <div class="space-y-1">
                        <flux:label>Capacidad de consultas <span class="text-red-500">(*)</span></flux:label>
                        <flux:input name="capacidad_consultas" icon="user-group" type="number" value="{{ old('capacidad_consultas') }}" placeholder="Ej. 5..." />
                        <flux:error name="capacidad_consultas" />
                    </div>

                    {{-- Estado --}}
                    <div class="space-y-1">
                        <flux:label>Estado <span class="text-red-500">(*)</span></flux:label>
                        <flux:select name="estado" icon="check-badge">
                            <flux:select.option value="">Seleccione un Estado</flux:select.option>
                            <flux:select.option value="ACTIVO" :selected="old('estado') == 'ACTIVO'">ACTIVO</flux:select.option>
                            <flux:select.option value="INACTIVO" :selected="old('estado') == 'INACTIVO'">INACTIVO</flux:select.option>
                        </flux:select>
                        <flux:error name="estado" />
                    </div>

                </div>
            </div>

            {{-- Footer --}}
            <div class="bg-gray-50 dark:bg-neutral-700 border-t border-gray-200 dark:border-gray-700 rounded-b-lg p-6 flex justify-end">
                <div class="flex space-x-3">
                    <flux:button href="{{ url('/admin/consultorios') }}">Cancelar</flux:button>
                    <flux:button type="submit" variant="primary" color="blue">
                        <i class="fas fa-save mr-2"></i> Guardar Consultorio
                    </flux:button>
                </div>
            </div>

        </form>
    </div>
</x-layouts::app>