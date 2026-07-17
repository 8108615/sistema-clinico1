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


                    {{-- Bloque de selección con buscador y resumen dinámico --}}
                    <div class="mb-6 col-span-2" x-data="{
                        buscar: '',
                        laboratorios: {{ $laboratorios->toJson() }},
                        seleccionados: [],
                        get filtrados() {
                            return this.laboratorios.filter(l => l.nombre.toLowerCase().includes(this.buscar.toLowerCase()))
                        },
                        get total() {
                            return this.seleccionados.reduce((sum, id) => {
                                let lab = this.laboratorios.find(l => l.id == id);
                                return sum + (lab ? parseFloat(lab.precio) : 0);
                            }, 0);
                        }
                    }">
                        <flux:label class="mb-2 font-bold">Seleccionar Laboratorios <span class="text-red-500">(*)</span></flux:label>

                        <flux:input x-model="buscar" placeholder="🔍 Buscar laboratorio por nombre..." class="mb-3" />

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Lista de selección --}}
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-neutral-900 p-4 max-h-80 overflow-y-auto shadow-inner">
                                <template x-for="lab in filtrados" :key="lab.id">
                                    <label class="flex items-center justify-between p-3 mb-2 bg-white dark:bg-neutral-800 rounded-md border border-gray-200 dark:border-neutral-700 hover:border-blue-400 transition cursor-pointer">
                                        <div class="flex items-center gap-3">
                                            <input
                                                type="checkbox"
                                                name="laboratorios[]"
                                                :value="lab.id"
                                                x-model="seleccionados"
                                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                            >
                                            <span class="text-sm text-gray-700 dark:text-neutral-200 font-medium" x-text="lab.nombre"></span>
                                        </div>
                                        <span class="text-sm font-bold text-blue-600 dark:text-blue-400">
                                            {{ $simboloMoneda }} <span x-text="parseFloat(lab.precio).toFixed(2)"></span>
                                        </span>
                                    </label>
                                </template>
                            </div>

                            {{-- Panel de Resumen Dinámico --}}
                            <div class="p-4 bg-blue-50 dark:bg-neutral-800 rounded-lg border border-blue-100 dark:border-neutral-700 h-fit">
                                <flux:heading size="sm" class="mb-4">Resumen de la Orden</flux:heading>

                                <ul class="space-y-2 mb-4 max-h-40 overflow-y-auto">
                                    <template x-for="id in seleccionados" :key="id">
                                        <li class="flex justify-between text-sm">
                                            <span x-text="laboratorios.find(l => l.id == id).nombre" class="text-gray-600 dark:text-neutral-300"></span>
                                            <span x-text="'{{ $simboloMoneda }} ' + parseFloat(laboratorios.find(l => l.id == id).precio).toFixed(2)" class="font-semibold"></span>
                                        </li>
                                    </template>
                                </ul>

                                <div class="pt-4 border-t border-blue-200 dark:border-neutral-700 flex justify-between items-center">
                                    <span class="text-lg font-bold text-gray-700 dark:text-neutral-200">Total:</span>
                                    <span class="text-2xl font-black text-blue-600 dark:text-blue-400">
                                        {{ $simboloMoneda }} <span x-text="total.toFixed(2)"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <flux:error name="laboratorios" />
                    </div>

                    <div class="mb-4">
                        <flux:label>Fecha de la Orden <span class="text-red-500">(*)</span></flux:label>
                        <flux:input name="fecha_orden" type="date" value="{{ old('fecha_orden', date('Y-m-d')) }}" required />
                        <flux:error name="fecha_orden" />
                    </div>

                    <div class="mb-4" x-data="{ tipo_pago: 'EFECTIVO', recibido: 0 }">
                        <flux:label>Tipo de Pago <span class="text-red-500">(*)</span></flux:label>
                        <flux:select name="tipo_pago" x-model="tipo_pago" class="mb-4">
                            <flux:select.option value="EFECTIVO">EFECTIVO</flux:select.option>
                            <flux:select.option value="TRANSFERENCIA">TRANSFERENCIA</flux:select.option>
                            <flux:select.option value="QR">QR</flux:select.option>
                        </flux:select>

                        {{-- Lógica para EFECTIVO --}}
                        <div x-show="tipo_pago === 'EFECTIVO'" class="grid grid-cols-2 gap-4">
                            <flux:input name="monto_recibido" type="number" step="0.01" label="Monto Recibido" x-model="recibido" placeholder="Ej: 200" />

                            {{-- Campo de Cambio (Solo lectura, calculado en vivo) --}}
                            <div class="flex flex-col">
                                <flux:label>Cambio a devolver</flux:label>
                                <div class="mt-2 text-xl font-bold text-green-600 dark:text-green-400">
                                    {{ $simboloMoneda }} <span x-text="(recibido - total > 0) ? (recibido - total).toFixed(2) : '0.00'"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Lógica para TRANSFERENCIA --}}
                        <div x-show="tipo_pago === 'TRANSFERENCIA'">
                            <flux:input name="codigo_transaccion" label="Código de Transacción" placeholder="Ingrese el número de comprobante..." />
                        </div>

                        {{-- Lógica para QR --}}
                        <div x-show="tipo_pago === 'QR'" class="mt-4 p-4 border border-dashed rounded-lg text-center">
                            <p class="text-sm mb-2 text-gray-500">Escanee el código QR para el pago:</p>
                            <img src="{{ asset('img/qr-banco.png') }}" class="mx-auto w-32" alt="QR">
                        </div>
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
