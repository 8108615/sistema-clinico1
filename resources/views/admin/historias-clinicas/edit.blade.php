<x-layouts::app title="Editar Historia Clínica">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ url('/admin') }}">Inicio</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ url('/admin/historias-clinicas') }}">Listado de Historias</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Modificar: {{ $historia->numero_historia }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <br>
    <flux:separator variant="subtle" />
    <br>

    {{-- Card --}}
    <div class="max-w-4xl bg-white dark:bg-neutral-800 border-t border-gray-200 dark:border-gray-700 rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl">

        <form action="{{ url('/admin/historias-clinicas/' . $historia->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    {{-- Selección de Paciente --}}
                    <div class="mb-4 col-span-2">
                        <flux:label>Paciente <span class="text-red-500">(*)</span></flux:label>
                        <flux:select name="paciente_id" placeholder="Seleccione un paciente" required>
                            @foreach($pacientes as $paciente)
                                <flux:select.option value="{{ $paciente->id }}" :selected="$historia->paciente_id == $paciente->id">
                                    {{ $paciente->nombres }} {{ $paciente->apellidos }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="paciente_id" />
                    </div>

                    {{-- Número de historia --}}
                    <div class="mb-4">
                        <flux:label>Número de Historia <span class="text-red-500">(*)</span></flux:label>
                        <flux:input name="numero_historia" icon="document-text" value="{{ old('numero_historia', $historia->numero_historia) }}" required />
                        <flux:error name="numero_historia" />
                    </div>

                    {{-- Estado --}}
                    <div class="mb-4">
                        <flux:label>Estado <span class="text-red-500">(*)</span></flux:label>
                        <flux:select name="estado" required>
                            <flux:select.option value="borrador" :selected="$historia->estado == 'borrador'">Borrador</flux:select.option>
                            <flux:select.option value="finalizado" :selected="$historia->estado == 'finalizado'">Finalizado</flux:select.option>
                            <flux:select.option value="anulado" :selected="$historia->estado == 'anulado'">Anulado</flux:select.option>
                        </flux:select>
                        <flux:error name="estado" />
                    </div>

                    {{-- Campos SOAP --}}
                    <div class="col-span-2 mb-4">
                        <flux:label>Subjetivo <span class="text-red-500">(*)</span></flux:label>
                        <flux:textarea name="subjetivo" rows="3" required>{{ old('subjetivo', $historia->subjetivo) }}</flux:textarea>
                        <flux:error name="subjetivo" />
                    </div>

                    <div class="col-span-2 mb-4">
                        <flux:label>Objetivo <span class="text-red-500">(*)</span></flux:label>
                        <flux:textarea name="objetivo" rows="3" required>{{ old('objetivo', $historia->objetivo) }}</flux:textarea>
                        <flux:error name="objetivo" />
                    </div>

                    <div class="col-span-2 mb-4">
                        <flux:label>Análisis <span class="text-red-500">(*)</span></flux:label>
                        <flux:textarea name="analisis" rows="3" required>{{ old('analisis', $historia->analisis) }}</flux:textarea>
                        <flux:error name="analisis" />
                    </div>

                    <div class="col-span-2 mb-4">
                        <flux:label>Plan <span class="text-red-500">(*)</span></flux:label>
                        <flux:textarea name="plan" rows="3" required>{{ old('plan', $historia->plan) }}</flux:textarea>
                        <flux:error name="plan" />
                    </div>

                </div>
            </div>

            {{-- Footer --}}
            <div class="bg-gray-50 dark:bg-neutral-700 border-t border-gray-200 dark:border-gray-700 rounded-b-lg p-6 text-left">
                <div class="flex space-x-3">
                    <a href="{{ url('/admin/historias-clinicas') }}"
                        class="px-5 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200 inline-flex items-center">
                        <i class="fas fa-times mr-2"></i> Cancelar
                    </a>
                    <flux:button variant="primary" type="submit" class="px-5 cursor-pointer" color="green">
                        <i class="fas fa-save mr-2"></i> Actualizar
                    </flux:button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::app>