<x-layouts::app title="Ordenes de Laboratorio">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ url('/admin') }}">Inicio</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ url('/admin/ordenlaboratorios') }}">Listado de Órdenes</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Creación de nueva orden</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <br>
    <flux:separator variant="subtle" />

    <br>

    {{-- Card --}}
    <div class="max-w-4xl bg-white dark:bg-neutral-800 border-t border-gray-200 dark:border-gray-700 rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl">

        <form action="{{ route('admin.orden_laboratorios.store') }}" method="POST">
            @csrf
            {{-- Body --}}
            <div class="p-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- Campo Paciente (Aquí luego integraremos el buscador) --}}
                    <div class="mb-4 col-span-2">
                        <flux:label>Seleccionar Paciente <span class="text-red-500">(*)</span></flux:label>
                        <flux:input name="paciente_id" icon="user" value="{{ old('paciente_id') }}" placeholder="Buscar por nombre o CI..." required />
                        <flux:error name="paciente_id" />
                    </div>

                    <div class="mb-4">
                        <flux:label>Fecha de la Orden <span class="text-red-500">(*)</span></flux:label>
                        <flux:input name="fecha_orden" type="date" value="{{ old('fecha_orden', date('Y-m-d')) }}" required />
                        <flux:error name="fecha_orden" />
                    </div>

                    <div class="mb-4">
                        <flux:label>Estado de Pago <span class="text-red-500">(*)</span></flux:label>
                        <flux:select name="estado_pago" icon="check-badge">
                            <flux:select.option value="">Seleccione un Estado</flux:select.option>
                            <flux:select.option value="PENDIENTE" :selected="old('estado_pago') == 'PENDIENTE'">PENDIENTE</flux:select.option>
                            <flux:select.option value="PAGADO" :selected="old('estado_pago') == 'PAGADO'">PAGADO</flux:select.option>
                        </flux:select>
                        <flux:error name="estado_pago" />
                    </div>

                </div>

            </div>

            {{-- Footer --}}
            <div class="bg-gray-50 dark:bg-neutral-700 border-t border-gray-200 dark:border-gray-700 rounded-b-lg p-6 text-left">
                <div class="flex space-x-3">
                    <a href="{{ route('admin.orden_laboratorios.index') }}"
                        class="px-5 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none transition-all duration-200 inline-flex items-center">
                        <i class="fas fa-times mr-2"></i> Cancelar
                    </a>
                    <flux:button variant="primary" type="submit" class="px-5 cursor-pointer" color="blue">
                        <i class="fas fa-save mr-2"></i> Guardar Orden
                    </flux:button>
                </div>
            </div>

        </form>

    </div>
    {{-- Card --}}

</x-layouts::app>
