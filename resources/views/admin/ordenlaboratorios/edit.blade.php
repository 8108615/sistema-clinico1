<x-layouts::app title="Editar Orden de Laboratorio">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ url('/admin') }}">Inicio</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('admin.orden_laboratorios.index') }}">Listado de Órdenes</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Editar orden #{{ $orden->id }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <br>
    <flux:separator variant="subtle" />
    <br>

    <div class="max-w-4xl bg-white dark:bg-neutral-800 border-t border-gray-200 dark:border-gray-700 rounded-lg shadow-lg"
         x-data="{
            paciente_id: '{{ $orden->paciente_id }}',
            buscar: '',
            laboratorios: {{ $laboratorios->toJson() }},
            seleccionados: {{ $orden->detalles->pluck('laboratorio_id') }},
            tipo_pago: '{{ $orden->tipo_pago }}',
            recibido: {{ $orden->monto_recibido ?? 0 }},
            simbolo: '{{ $simboloMoneda }}',
            get filtrados() { return this.laboratorios.filter(l => l.nombre.toLowerCase().includes(this.buscar.toLowerCase())) },
            get total() {
                return this.seleccionados.reduce((sum, id) => {
                    let lab = this.laboratorios.find(l => l.id == id);
                    return sum + (lab ? parseFloat(lab.precio) : 0);
                }, 0);
            },
            get cambio() { return (this.recibido - this.total) }
        }">

        <form action="{{ route('admin.orden_laboratorios.update', $orden->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Selección de Paciente --}}
                    <div class="mb-4 col-span-2 relative">
                        <flux:label>Seleccionar Paciente <span class="text-red-500">(*)</span></flux:label>
                        <flux:select name="paciente_id" placeholder="Buscar..." searchable x-model="paciente_id" class="w-full">
                            @foreach ($pacientes as $paciente)
                                <flux:select.option value="{{ $paciente->id }}">{{ $paciente->nombres }} {{ $paciente->apellidos }} (CI: {{ $paciente->ci }})</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>

                    {{-- Fecha --}}
                    <div class="mb-4 col-span-1">
                        <flux:label>Fecha de la Orden <span class="text-red-500">(*)</span></flux:label>
                        <flux:input name="fecha_orden" type="date" value="{{ \Carbon\Carbon::parse($orden->fecha_orden)->format('Y-m-d') }}" />
                    </div>

                    {{-- Laboratorios --}}
                    <div class="mb-6 col-span-3">
                        <flux:label class="mb-2 font-bold">Seleccionar Laboratorios <span class="text-red-500">(*)</span></flux:label>
                        <flux:input x-model="buscar" placeholder="🔍 Buscar laboratorio..." class="mb-3" />
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="border rounded-lg bg-gray-50 dark:bg-neutral-900 p-4 max-h-80 overflow-y-auto">
                                <template x-for="lab in filtrados" :key="lab.id">
                                    <label class="flex items-center justify-between p-3 mb-2 bg-white dark:bg-neutral-800 rounded-md border hover:border-blue-400 cursor-pointer">
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" name="laboratorios[]" :value="lab.id" x-model="seleccionados" class="rounded border-gray-300 text-blue-600">
                                            <span class="text-sm" x-text="lab.nombre"></span>
                                        </div>
                                        <span class="text-sm font-bold text-blue-600">
                                            <span x-text="simbolo"></span> <span x-text="parseFloat(lab.precio).toFixed(2)"></span>
                                        </span>
                                    </label>
                                </template>
                            </div>

                            {{-- Resumen --}}
                            <div class="p-4 bg-blue-50 dark:bg-neutral-800 rounded-lg h-fit">
                                <flux:heading size="sm" class="mb-4">Resumen</flux:heading>
                                <ul class="space-y-2 mb-4 max-h-40 overflow-y-auto">
                                    <template x-for="id in seleccionados" :key="id">
                                        <li class="flex justify-between text-sm">
                                            <span x-text="laboratorios.find(l => l.id == id)?.nombre"></span>
                                            <span class="font-semibold" x-text="simbolo + ' ' + parseFloat(laboratorios.find(l => l.id == id)?.precio || 0).toFixed(2)"></span>
                                        </li>
                                    </template>
                                </ul>
                                <div class="pt-4 border-t border-blue-200 flex justify-between">
                                    <span class="font-bold">Total:</span>
                                    <span class="text-2xl font-black text-blue-600">
                                        <span x-text="simbolo"></span> <span x-text="total.toFixed(2)"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pago --}}
                    <div class="col-span-3 border-t pt-4">
                        <flux:label>Tipo de Pago <span class="text-red-500">(*)</span></flux:label>
                        <flux:select name="tipo_pago" x-model="tipo_pago" class="mb-4">
                            <flux:select.option value="EFECTIVO">EFECTIVO</flux:select.option>
                            <flux:select.option value="TRANSFERENCIA">TRANSFERENCIA</flux:select.option>
                            <flux:select.option value="QR">QR</flux:select.option>
                        </flux:select>

                        <div x-show="tipo_pago === 'EFECTIVO'" class="grid grid-cols-2 gap-4">
                            <flux:input name="monto_recibido" type="number" step="0.01" label="Monto Recibido" x-model="recibido" />
                            <div class="flex flex-col">
                                <flux:label>Cambio a devolver</flux:label>
                                <div class="mt-2 text-xl font-bold" :class="cambio < 0 ? 'text-red-600' : 'text-green-600'">
                                    <span x-text="simbolo"></span> <span x-text="cambio.toFixed(2)"></span>
                                </div>
                            </div>
                        </div>

                        <div x-show="tipo_pago === 'TRANSFERENCIA'">
                            <flux:input name="codigo_transaccion" label="Código de Transacción" value="{{ $orden->codigo_transaccion }}" />
                        </div>

                        {{-- AQUI AGREGAMOS EL BLOQUE QR --}}
                        <div x-show="tipo_pago === 'QR'" class="mt-4 p-6 border-2 border-dashed border-blue-200 rounded-xl text-center">
                            <img src="{{ asset('img/QR.jpg') }}" class="mx-auto w-48 shadow-lg rounded-lg" alt="QR">
                            <p class="mt-4 text-xl font-bold text-blue-600">
                                Total a pagar: <span x-text="simbolo"></span> <span x-text="total.toFixed(2)"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-b-lg p-6 flex space-x-3 bg-transparent">
                <a href="{{ route('admin.orden_laboratorios.index') }}" class="px-5 text-sm font-medium text-gray-300 hover:text-white transition flex items-center">Cancelar</a>
                <flux:button variant="primary" color="green" type="submit" class="cursor-pointer" x-bind:disabled="tipo_pago === 'EFECTIVO' && cambio < 0">
                    Actualizar Orden
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::app>
