<x-layouts::app title="Asignar Permisos">
    <div class="relative mb-6 w-full">
        <!-- Cabecera con título, descripción y botones superiores -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <flux:heading size="xl" level="1">Asignar Permisos al Rol: <span class="text-blue-600 uppercase">{{ $rol->name }}</span></flux:heading>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Selecciona los permisos que tendrá este rol por cada módulo del sistema.</p>
            </div>

            <!-- Botones superiores: Seleccionar todos global y Volver -->
            <div class="flex items-center gap-2 shrink-0">
                <button type="button"
                        onclick="toggleTodosGlobal(this)"
                        class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition shadow-sm gap-2">
                    <i class="fas fa-check-double"></i> Seleccionar todos
                </button>

                <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-semibold rounded-lg transition shadow-sm gap-2">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-6 shadow-sm">
        <form action="{{ route('admin.roles.guardar_permisos', $rol->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($permisosAgrupados as $modulo => $permisosModulo)
                    <div class="bg-gray-50 dark:bg-zinc-900/40 border border-gray-200 dark:border-zinc-700/80 rounded-lg p-4 flex flex-col justify-between shadow-sm">
                        <div>
                            <!-- Cabecera de la tarjeta: Título del módulo y botón por módulo -->
                            <div class="flex items-center justify-between mb-3 pb-2 border-b border-gray-200 dark:border-zinc-700">
                                <h4 class="text-md font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                                    <i class="fas fa-folder-open text-blue-500"></i> {{ ucfirst($modulo) }}
                                </h4>
                                <button type="button"
                                        onclick="toggleModulo(this)"
                                        class="text-xs text-blue-600 dark:text-blue-400 hover:underline font-medium focus:outline-none">
                                    Seleccionar todos
                                </button>
                            </div>

                            <!-- Contenedor de checkboxes con la clase asignada -->
                            <div class="space-y-2.5 checkbox-group">
                                @foreach($permisosModulo as $permiso)
                                    <div class="flex items-center space-x-2.5">
                                        <input type="checkbox"
                                               name="permisos[]"
                                               value="{{ $permiso->id }}"
                                               id="permiso_{{ $permiso->id }}"
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 w-4 h-4 cursor-pointer"
                                               {{ $rol->hasPermissionTo($permiso->name) ? 'checked' : '' }}>

                                        <label for="permiso_{{ $permiso->id }}" class="text-sm text-gray-700 dark:text-gray-300 cursor-pointer select-none">
                                            {{ $permiso->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-zinc-700">
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition flex items-center gap-2 shadow-sm">
                    <i class="fas fa-save"></i> Guardar permisos
                </button>
                <a href="{{ route('admin.roles.index') }}" class="px-5 py-2.5 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition flex items-center gap-2 shadow-sm">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    <!-- Scripts para control global e individual por módulo -->
    <script>
        // Función para seleccionar/deseleccionar absolutamente todos los permisos del sistema
        function toggleTodosGlobal(button) {
            const allCheckboxes = document.querySelectorAll('input[type="checkbox"][name="permisos[]"]');
            const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);

            allCheckboxes.forEach(cb => {
                cb.checked = !allChecked;
            });

            // Actualiza el texto del botón global
            button.innerHTML = allChecked
                ? '<i class="fas fa-check-double"></i> Seleccionar todos'
                : '<i class="fas fa-times"></i> Deseleccionar todos';

            // También sincroniza los textos de los botones internos de cada tarjeta opcionalmente
            document.querySelectorAll('.checkbox-group').forEach(group => {
                const card = group.closest('div').parentElement;
                const cardBtn = card.querySelector('button[onclick="toggleModulo(this)"]');
                if (cardBtn) {
                    cardBtn.textContent = allChecked ? 'Seleccionar todos' : 'Deseleccionar todos';
                }
            });
        }

        // Función para seleccionar/deseleccionar por tarjeta individual (módulo)
        function toggleModulo(button) {
            const card = button.closest('div').parentElement;
            const checkboxes = card.querySelectorAll('.checkbox-group input[type="checkbox"]');

            const allChecked = Array.from(checkboxes).every(cb => cb.checked);

            checkboxes.forEach(cb => {
                cb.checked = !allChecked;
            });

            button.textContent = allChecked ? 'Seleccionar todos' : 'Deseleccionar todos';
        }
    </script>
</x-layouts::app>
