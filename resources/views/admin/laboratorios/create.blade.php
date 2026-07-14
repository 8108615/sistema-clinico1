<x-layouts::app title="Sistema Clinico">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Crear nuevo laboratorio</flux:heading>
        <flux:separator variant="subtle" class="my-4" />
    </div>

    <form action="{{ route('admin.laboratorios.store') }}" method="POST" class="bg-white dark:bg-zinc-800 p-6 rounded-lg border border-gray-200 dark:border-zinc-700">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nombre -->
            <flux:input name="nombre" label="Nombre del estudio" placeholder="Ej: Hemograma completo" value="{{ old('nombre') }}" :error="$errors->first('nombre')" required />

            <!-- Código -->
            <flux:input name="codigo" label="Código del estudio" placeholder="Ej: LAB-001" value="{{ old('codigo') }}" :error="$errors->first('codigo')" required />

            <!-- Categoría -->
            <flux:input name="categoria" label="Categoría" placeholder="Ej: Hematología" value="{{ old('categoria') }}" :error="$errors->first('categoria')" />

            <!-- Precio -->
            <flux:input name="precio" label="Precio {{ $ajuste ? '(' . $ajuste->divisa . ')' : '' }}" type="number" step="0.01" placeholder="0.00" value="{{ old('precio') }}" :error="$errors->first('precio')" required />

            <!-- Días de entrega -->
            <flux:input name="dias_entrega" label="Días para entrega de resultados" type="number" value="{{ old('dias_entrega', 0) }}" :error="$errors->first('dias_entrega')" />

            <!-- Requiere Ayuno -->
            <flux:select name="requiere_ayuno" label="¿Requiere ayuno?" :error="$errors->first('requiere_ayuno')">
                <flux:select.option value="0" {{ old('requiere_ayuno') == '0' ? 'selected' : '' }}>No</flux:select.option>
                <flux:select.option value="1" {{ old('requiere_ayuno') == '1' ? 'selected' : '' }}>Sí</flux:select.option>
            </flux:select>

            <!-- Estado -->
            <flux:select name="estado" label="Estado del estudio" :error="$errors->first('estado')">
                <flux:select.option value="ACTIVO" {{ old('estado', 'ACTIVO') == 'ACTIVO' ? 'selected' : '' }}>Activo</flux:select.option>
                <flux:select.option value="INACTIVO" {{ old('estado') == 'INACTIVO' ? 'selected' : '' }}>Inactivo</flux:select.option>
            </flux:select>
        </div>

        <!-- Descripción -->
        <div class="mt-6">
            <flux:textarea name="descripcion" label="Descripción / Instrucciones" rows="3">{{ old('descripcion') }}</flux:textarea>
        </div>

        <!-- Botones -->
        <div class="mt-10 flex gap-4 justify-end">
            <flux:button href="{{ route('admin.laboratorios.index') }}" variant="ghost">Cancelar</flux:button>
            <flux:button type="submit" variant="primary" color="blue">Guardar laboratorio</flux:button>
        </div>
    </form>
</x-layouts::app>
