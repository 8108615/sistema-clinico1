<x-layouts::app title="Detalle de Receta Médica">
    <div class="relative mb-6 w-full flex justify-between items-center">
        <div>
            <flux:heading size="xl" level="1">Detalle de la Receta Médica</flux:heading>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Información completa del registro clínico y fármacos prescritos.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.recetas.pdf', $receta->id) }}" target="_blank" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg transition flex items-center gap-2 text-sm shadow-sm">
                <i class="fas fa-file-pdf"></i> Imprimir PDF
            </a>
            <a href="{{ route('admin.recetas.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition flex items-center gap-2 text-sm shadow-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
    <flux:separator variant="subtle" class="mb-6" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Columna Izquierda: Datos del Paciente y Médico --}}
        <div class="space-y-6 lg:col-span-1">

            {{-- Tarjeta Paciente --}}
            <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 p-5 shadow-sm">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-4 flex items-center gap-2">
                    <i class="fas fa-user-injured text-blue-500"></i> Datos del Paciente
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="block text-gray-400 text-xs">Nombre Completo:</span>
                        <span class="font-semibold text-gray-900 dark:text-gray-100 text-base">
                            {{ $receta->paciente->nombres ?? '' }} {{ $receta->paciente->apellidos ?? '' }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-gray-400 text-xs">Cédula de Identidad (CI):</span>
                        <span class="font-medium text-gray-800 dark:text-gray-200">{{ $receta->paciente->ci ?? 'No registrado' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 text-xs">Nro de Historia Clínica:</span>
                        <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $receta->historiaClinica->numero_historia ?? 'S/N' }}</span>
                    </div>
                </div>
            </div>

            {{-- Tarjeta Médico Asignado --}}
            <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 p-5 shadow-sm">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-4 flex items-center gap-2">
                    <i class="fas fa-user-md text-emerald-500"></i> Médico Tratante
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="block text-gray-400 text-xs">Dr. / Dra.:</span>
                        <span class="font-semibold text-gray-900 dark:text-gray-100 text-base">
                            {{ $receta->medico ? $receta->medico->name : 'Sin Asignar' }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-gray-400 text-xs">Correo Electrónico:</span>
                        <span class="font-medium text-gray-800 dark:text-gray-200">{{ $receta->medico->email ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            {{-- Fecha de Emisión --}}
            <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 p-5 shadow-sm">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-amber-500"></i> Fecha de Emisión
                </h3>
                <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    {{ \Carbon\Carbon::parse($receta->fecha_receta)->format('d/m/Y') }}
                </p>
            </div>

        </div>

        {{-- Columna Derecha: Medicamentos e Indicaciones Generales --}}
        <div class="space-y-6 lg:col-span-2">

            {{-- Tabla de Fármacos Recetados --}}
            <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 p-6 shadow-sm">
                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                    <i class="fas fa-pills text-blue-500"></i> Fármacos Prescritos
                </h3>

                <div class="overflow-x-auto rounded-lg border border-gray-100 dark:border-zinc-700">
                    <table class="w-full border-collapse text-left text-sm">
                        <thead class="bg-gray-50 dark:bg-zinc-900 text-gray-500 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3 border-b">Nro</th>
                                <th class="px-4 py-3 border-b">Medicamento</th>
                                <th class="px-4 py-3 border-b">Dosis</th>
                                <th class="px-4 py-3 border-b">Duración</th>
                                <th class="px-4 py-3 border-b">Indicaciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                            @forelse ($receta->detalles as $detalle)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-zinc-700/30">
                                    <td class="px-4 py-3 text-center font-medium">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ $detalle->medicamento }}</td>
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $detalle->dosis }}</td>
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $detalle->duracion }}</td>
                                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs">{{ $detalle->indicaciones ?? 'Ninguna' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-400 italic">No hay medicamentos registrados en esta receta.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Indicaciones Generales --}}
            <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 p-6 shadow-sm">
                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                    <i class="fas fa-notes-medical text-indigo-500"></i> Indicaciones Generales o Recomendaciones
                </h3>
                <div class="p-4 bg-gray-50 dark:bg-zinc-900/50 rounded-lg border border-gray-100 dark:border-zinc-700 text-gray-700 dark:text-gray-300 text-sm whitespace-pre-line">
                    {{ $receta->indicaciones_generales ?: 'No se especificaron indicaciones generales adicionales.' }}
                </div>
            </div>

        </div>

    </div>
</x-layouts::app>
