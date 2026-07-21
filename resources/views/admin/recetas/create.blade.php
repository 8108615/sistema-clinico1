<x-layouts::app title="Nueva Receta Médica">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ url('/admin') }}">Inicio</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('admin.recetas.index') }}">Listado de Recetas</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Creación de Receta</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <br>
    <flux:separator variant="subtle" />
    <br>

    {{-- Card Principal --}}
    <div class="max-w-4xl bg-white dark:bg-neutral-800 border-t border-gray-200 dark:border-gray-700 rounded-lg shadow-lg">

        <form action="{{ route('admin.recetas.store') }}" method="POST">
            @csrf
            
            <div class="p-6 space-y-6">

                <!-- Datos Generales -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <div>
                        <flux:label>Paciente <span class="text-red-500">(*)</span></flux:label>
                        <select name="paciente_id" id="paciente_id" class="w-full mt-1 border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-800 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Seleccione un paciente...</option>
                            @foreach($pacientes as $paciente)
                                <option value="{{ $paciente->id }}" {{ old('paciente_id') == $paciente->id ? 'selected' : '' }}>
                                    {{ $paciente->nombres }} {{ $paciente->apellidos }} - ( {{ $paciente->ci }})
                                </option>
                            @endforeach
                        </select>
                        <flux:error name="paciente_id" />
                    </div>

                    <div>
                        <flux:label>Historia Clínica relacionada <span class="text-red-500">(*)</span></flux:label>
                        <select name="historia_clinica_id" id="historia_clinica_id" class="w-full mt-1 border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-800 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Seleccione la historia clínica...</option>
                            @foreach($historias as $historia)
                                <option value="{{ $historia->id }}" {{ old('historia_clinica_id') == $historia->id ? 'selected' : '' }}>
                                    Nro: {{ $historia->numero_historia }} - {{ $historia->paciente->nombres ?? '' }} {{ $historia->paciente->apellidos ?? '' }}
                                </option>
                            @endforeach
                        </select>
                        <flux:error name="historia_clinica_id" />
                    </div>

                    <div>
                        <flux:label>Fecha de Receta <span class="text-red-500">(*)</span></flux:label>
                        <flux:input type="date" name="fecha_receta" value="{{ old('fecha_receta', date('Y-m-d')) }}" required />
                        <flux:error name="fecha_receta" />
                    </div>

                    <div class="md:col-span-2">
                        <flux:label>Indicaciones Generales</flux:label>
                        <textarea name="indicaciones_generales" rows="2" placeholder="Indicaciones generales para el paciente..." class="w-full mt-1 border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-800 dark:text-zinc-100 rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500">{{ old('indicaciones_generales') }}</textarea>
                        <flux:error name="indicaciones_generales" />
                    </div>

                </div>

                <flux:separator variant="subtle" />

                {{-- Sección Dinámica de Medicamentos --}}
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-md font-bold text-gray-800 dark:text-zinc-200">Medicamentos a Recetar</h3>
                        <button type="button" id="agregar-medicamento" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition flex items-center gap-1">
                            <i class="fas fa-plus"></i> Añadir Fármaco
                        </button>
                    </div>

                    <div id="contenedor-medicamentos" class="space-y-3">
                        <!-- Fila inicial de medicamento -->
                        <div class="medicamento-row grid grid-cols-1 md:grid-cols-12 gap-2 p-3 bg-gray-50 dark:bg-zinc-900/50 border border-gray-200 dark:border-zinc-700 rounded-lg items-center">
                            <div class="md:col-span-4">
                                <input type="text" name="medicamentos[0][medicamento]" placeholder="Medicamento (ej. Paracetamol 500mg)" class="w-full border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-800 dark:text-zinc-100 rounded-lg px-3 py-1.5 text-sm" required>
                            </div>
                            <div class="md:col-span-3">
                                <input type="text" name="medicamentos[0][dosis]" placeholder="Dosis (ej. 1 cada 8 hrs)" class="w-full border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-800 dark:text-zinc-100 rounded-lg px-3 py-1.5 text-sm" required>
                            </div>
                            <div class="md:col-span-2">
                                <input type="text" name="medicamentos[0][duracion]" placeholder="Duración (ej. 5 días)" class="w-full border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-800 dark:text-zinc-100 rounded-lg px-3 py-1.5 text-sm" required>
                            </div>
                            <div class="md:col-span-2">
                                <input type="text" name="medicamentos[0][indicaciones]" placeholder="Indicación opcional" class="w-full border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-800 dark:text-zinc-100 rounded-lg px-3 py-1.5 text-sm">
                            </div>
                            <div class="md:col-span-1 text-center">
                                <button type="button" class="eliminar-fila p-2 text-red-500 hover:text-red-700 transition" title="Eliminar fila">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="bg-gray-50 dark:bg-neutral-700 border-t border-gray-200 dark:border-gray-700 rounded-b-lg p-6 text-left">
                <div class="flex space-x-3">
                    <a href="{{ route('admin.recetas.index') }}" class="px-5 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200 inline-flex items-center">
                        <i class="fas fa-times mr-2"></i> Cancelar
                    </a>
                    <flux:button variant="primary" type="submit" class="px-5 cursor-pointer" color="blue">
                        <i class="fas fa-save mr-2"></i> Guardar Receta
                    </flux:button>
                </div>
            </div>

        </form>

    </div>

    {{-- Script JavaScript para agregar y eliminar filas dinámicamente --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let contador = 1;
            const contenedor = document.getElementById('contenedor-medicamentos');
            const btnAgregar = document.getElementById('agregar-medicamento');

            btnAgregar.addEventListener('click', function () {
                const nuevaFila = document.createElement('div');
                nuevaFila.className = 'medicamento-row grid grid-cols-1 md:grid-cols-12 gap-2 p-3 bg-gray-50 dark:bg-zinc-900/50 border border-gray-200 dark:border-zinc-700 rounded-lg items-center';
                nuevaFila.innerHTML = `
                    <div class="md:col-span-4">
                        <input type="text" name="medicamentos[${contador}][medicamento]" placeholder="Medicamento (ej. Ibuprofeno 400mg)" class="w-full border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-800 dark:text-zinc-100 rounded-lg px-3 py-1.5 text-sm" required>
                    </div>
                    <div class="md:col-span-3">
                        <input type="text" name="medicamentos[${contador}][dosis]" placeholder="Dosis (ej. 1 cada 12 hrs)" class="w-full border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-800 dark:text-zinc-100 rounded-lg px-3 py-1.5 text-sm" required>
                    </div>
                    <div class="md:col-span-2">
                        <input type="text" name="medicamentos[${contador}][duracion]" placeholder="Duración (ej. 3 días)" class="w-full border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-800 dark:text-zinc-100 rounded-lg px-3 py-1.5 text-sm" required>
                    </div>
                    <div class="md:col-span-2">
                        <input type="text" name="medicamentos[${contador}][indicaciones]" placeholder="Indicación opcional" class="w-full border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-800 dark:text-zinc-100 rounded-lg px-3 py-1.5 text-sm">
                    </div>
                    <div class="md:col-span-1 text-center">
                        <button type="button" class="eliminar-fila p-2 text-red-500 hover:text-red-700 transition" title="Eliminar fila">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                `;
                contenedor.appendChild(nuevaFila);
                contador++;
            });

            contenedor.addEventListener('click', function (e) {
                if (e.target.closest('.eliminar-fila')) {
                    const filas = contenedor.querySelectorAll('.medicamento-row');
                    if (filas.length > 1) {
                        e.target.closest('.medicamento-row').remove();
                    } else {
                        alert('La receta debe contener al menos un medicamento.');
                    }
                }
            });
        });
    </script>
</x-layouts::app>