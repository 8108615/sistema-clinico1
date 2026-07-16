<x-layouts::app title="Ordenes de Laboratorio">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ url('/admin') }}">Inicio</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('admin.orden_laboratorios.index') }}">Listado de Órdenes</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Creación de nueva orden</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <br>
    <flux:separator variant="subtle" />
    <br>

    <div class="max-w-4xl bg-white dark:bg-neutral-800 border-t border-gray-200 dark:border-gray-700 rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl">

        {{-- Formulario de Registro de Orden --}}
        <form action="{{ route('admin.orden_laboratorios.store') }}" method="POST">
            @csrf
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4 col-span-2">
                        {{-- Contenedor principal con posición relativa --}}
                        <div class="relative" x-data="{ paciente_id: '' }">
                            <flux:label>Seleccionar Paciente <span class="text-red-500">(*)</span></flux:label>

                            <div class="relative flex items-center">
                                <div class="w-full">
                                    <flux:select
                                        name="paciente_id"
                                        placeholder="Buscar por nombre o CI..."
                                        searchable
                                        x-model="paciente_id"
                                    >
                                        @foreach ($pacientes as $paciente)
                                            <flux:select.option value="{{ $paciente->id }}">
                                                {{ $paciente->nombres }} {{ $paciente->apellidos }} (CI: {{ $paciente->ci }})
                                            </flux:select.option>
                                        @endforeach
                                    </flux:select>
                                </div>

                                {{-- Botón posicionado absolutamente a la derecha --}}
                                <button
                                    type="button"
                                    @click="paciente_id = ''"
                                    class="absolute right-8 text-gray-400 hover:text-red-500 z-10"
                                    x-show="paciente_id !== ''"
                                >
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <flux:error name="paciente_id" />
                        </div>
                    </div>

                    {{-- Bloque de selección de laboratorios --}}
                    <div class="mb-4 col-span-2">
                        <flux:label>Seleccionar Laboratorios <span class="text-red-500">(*)</span></flux:label>
                        <flux:select
                            name="laboratorios[]"
                            placeholder="Selecciona uno o varios laboratorios..."
                            searchable
                            multiple
                        >
                            @foreach($laboratorios as $lab)
                                <flux:select.option value="{{ $lab->id }}">
                                    {{-- Aquí reemplazamos 'Bs' por la variable global --}}
                                    {{ $lab->nombre }} - {{ $simboloMoneda }} {{ number_format($lab->precio, 2) }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="laboratorios" />
                    </div>

                    <div class="mb-4">
                        <flux:label>Fecha de la Orden <span class="text-red-500">(*)</span></flux:label>
                        <flux:input name="fecha_orden" type="date" value="{{ old('fecha_orden', date('Y-m-d')) }}" required />
                        <flux:error name="fecha_orden" />
                    </div>

                    <div class="mb-4">
                        <flux:label>Estado de Pago <span class="text-red-500">(*)</span></flux:label>
                        <flux:select name="estado_pago" icon="check-badge">
                            <flux:select.option value="PENDIENTE" :selected="old('estado_pago') == 'PENDIENTE'">PENDIENTE</flux:select.option>
                            <flux:select.option value="PAGADO" :selected="old('estado_pago') == 'PAGADO'">PAGADO</flux:select.option>
                        </flux:select>
                        <flux:error name="estado_pago" />
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-neutral-700 border-t border-gray-200 dark:border-gray-700 rounded-b-lg p-6 text-left">
                <div class="flex space-x-3">
                    <a href="{{ route('admin.orden_laboratorios.index') }}" class="px-5 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all inline-flex items-center">
                        <i class="fas fa-times mr-2"></i> Cancelar
                    </a>
                    <flux:button variant="primary" type="submit" class="px-5 cursor-pointer" color="blue">
                        <i class="fas fa-save mr-2"></i> Guardar Orden
                    </flux:button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::app>
