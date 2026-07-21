<x-layouts::app title="Cargar Resultados de Laboratorio">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ url('/admin') }}">Inicio</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('admin.resultados_laboratorios.index') }}">Resultados de Laboratorio</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Cargar Resultados</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <br>
    <flux:separator variant="subtle" />
    <br>

    {{-- Card --}}
    <div class="max-w-4xl bg-white dark:bg-neutral-800 border-t border-gray-200 dark:border-gray-700 rounded-lg shadow-lg transition-all duration-300">

        <form action="{{ route('admin.resultados_laboratorios.store') }}" method="POST">
            @csrf

            {{-- Datos de la orden oculta o informativa --}}
            <input type="hidden" name="detalle_orden_laboratorio_id" value="{{ $detalleActual->id ?? '' }}">

            {{-- Body --}}
            <div class="p-6">
                <!-- Información general de la orden -->
                <div class="mb-6 p-4 bg-gray-50 dark:bg-neutral-900 rounded-lg border border-gray-200 dark:border-neutral-700">
                    <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">Datos de la Orden #{{ $orden->id }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-400">
                        <div>
                            <p><strong class="text-gray-800 dark:text-gray-200">Paciente:</strong> {{ $orden->paciente->nombres ?? '' }} {{ $orden->paciente->apellidos ?? '' }}</p>
                            <p><strong class="text-gray-800 dark:text-gray-200">C.I.:</strong> {{ $orden->paciente->ci ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p><strong class="text-gray-800 dark:text-gray-200">Fecha de Orden:</strong> {{ \Carbon\Carbon::parse($orden->fecha_orden)->format('d/m/Y H:i') }}</p>
                            <p><strong class="text-gray-800 dark:text-gray-200">Examen Solicitado:</strong>
                                <span class="text-blue-600 dark:text-blue-400 font-semibold">
                                    {{ $detalleActual->laboratorio->nombre ?? 'Examen Clínico' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <flux:heading size="lg" level="2" class="mb-4">Parámetros del Examen</flux:heading>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Ingresa los valores obtenidos para cada parámetro del examen clínico.</p>

                <!-- Campos dinámicos o estructurados para los resultados -->
                <div class="space-y-4" id="parametros-container">
                    <div class="p-4 border border-gray-200 dark:border-neutral-700 rounded-lg bg-white dark:bg-neutral-800 shadow-sm relative space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <flux:label>Parámetro <span class="text-red-500">(*)</span></flux:label>
                                <flux:input name="parametros[0][parametro]" placeholder="Ej. Glucosa, Leucocitos..." required />
                                <flux:error name="parametros.0.parametro" />
                            </div>
                            <div>
                                <flux:label>Resultado <span class="text-red-500">(*)</span></flux:label>
                                <flux:input name="parametros[0][resultado]" placeholder="Ej. 95.5" required />
                                <flux:error name="parametros.0.resultado" />
                            </div>
                            <div>
                                <flux:label>Unidad de Medida</flux:label>
                                <flux:input name="parametros[0][unidad_medida]" placeholder="Ej. mg/dL, g/L" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <flux:label>Valores de Referencia</flux:label>
                                <flux:input name="parametros[0][valores_referencia]" placeholder="Ej. 70 - 110 mg/dL" />
                            </div>
                            <div>
                                <flux:label>Observaciones</flux:label>
                                <flux:input name="parametros[0][observaciones]" placeholder="Observaciones adicionales..." />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Botón para agregar más parámetros dinámicamente si el examen tiene varios --}}
                <div class="mt-4">
                    <button type="button" id="agregar-parametro" class="px-4 py-2 bg-gray-200 dark:bg-neutral-700 hover:bg-gray-300 dark:hover:bg-neutral-600 text-gray-800 dark:text-gray-200 text-xs font-semibold rounded-lg transition flex items-center gap-2 cursor-pointer">
                        <i class="fas fa-plus"></i> Añadir otro parámetro
                    </button>
                </div>

            </div>

            {{-- Footer --}}
            <div class="bg-gray-50 dark:bg-neutral-700 border-t border-gray-200 dark:border-gray-700 rounded-b-lg p-6 text-left">
                <div class="flex space-x-3">
                    <a href="{{ route('admin.resultados_laboratorios.index') }}"
                        class="px-5 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-offset-1 transition-all duration-200 inline-flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </a>
                    <flux:button variant="primary" type="submit" class="px-5 cursor-pointer" color="blue">
                        <i class="fas fa-save mr-2"></i> Guardar Resultados
                    </flux:button>
                </div>
            </div>

        </form>

    </div>
    {{-- Card --}}

    {{-- Script para clonar campos de parámetros si el examen requiere varios valores --}}
    @push('scripts')
    <script>
        let contador = 1;
        document.getElementById('agregar-parametro').addEventListener('click', function() {
            const container = document.getElementById('parametros-container');
            const nuevoItem = document.createElement('div');
            nuevoItem.className = 'p-4 border border-gray-200 dark:border-neutral-700 rounded-lg bg-white dark:bg-neutral-800 shadow-sm relative space-y-4 mt-4';

            // CORREGIDO: nuevoItem con I mayúscula
            nuevoItem.innerHTML = `
                <div class="flex justify-end">
                    <button type="button" class="text-red-500 hover:text-red-700 text-xs font-semibold eliminar-parametro flex items-center gap-1 cursor-pointer">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <flux:label>Parámetro <span class="text-red-500">(*)</span></flux:label>
                        <flux:input name="parametros[${contador}][parametro]" placeholder="Ej. Colesterol..." required />
                    </div>
                    <div>
                        <flux:label>Resultado <span class="text-red-500">(*)</span></flux:label>
                        <flux:input name="parametros[${contador}][resultado]" placeholder="Ej. 200" required />
                    </div>
                    <div>
                        <flux:label>Unidad de Medida</flux:label>
                        <flux:input name="parametros[${contador}][unidad_medida]" placeholder="Ej. mg/dL" />
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <flux:label>Valores de Referencia</flux:label>
                        <flux:input name="parametros[${contador}][valores_referencia]" placeholder="Ej. < 200 mg/dL" />
                    </div>
                    <div>
                        <flux:label>Observaciones</flux:label>
                        <flux:input name="parametros[${contador}][observaciones]" placeholder="Observaciones adicionales..." />
                    </div>
                </div>
            `;

            container.appendChild(nuevoItem);
            contador++;

            // Evento para eliminar la fila agregada dinámicamente
            nuevoItem.querySelector('.eliminar-parametro').addEventListener('click', function() {
                nuevoItem.remove();
            });
        });
    </script>
    @endpush
</x-layouts::app>
