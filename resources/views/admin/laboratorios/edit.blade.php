<x-layouts::app title="Editar Laboratorio">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Editar laboratorio: {{ $laboratorio->nombre }}</flux:heading>
        <flux:separator variant="subtle" class="my-4" />
    </div>

    <form action="{{ route('admin.laboratorios.update', $laboratorio->id) }}" method="POST" class="bg-white dark:bg-zinc-800 p-6 rounded-lg border border-gray-200 dark:border-zinc-700">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nombre -->
            <flux:input name="nombre" label="Nombre del estudio" value="{{ old('nombre', $laboratorio->nombre) }}" :error="$errors->first('nombre')" required />

            <!-- Código -->
            <flux:input name="codigo" label="Código del estudio" value="{{ old('codigo', $laboratorio->codigo) }}" :error="$errors->first('codigo')" required />

            <!-- Categoría -->
            <flux:input name="categoria" label="Categoría" value="{{ old('categoria', $laboratorio->categoria) }}" :error="$errors->first('categoria')" />

            <!-- Precio -->
            <flux:input name="precio" label="Precio {{ $ajuste ? '(' . $ajuste->divisa . ')' : '' }}" type="number" step="0.01" value="{{ old('precio', $laboratorio->precio) }}" :error="$errors->first('precio')" required />

            <!-- Días de entrega -->
            <flux:input name="dias_entrega" label="Días para entrega" type="number" value="{{ old('dias_entrega', $laboratorio->dias_entrega) }}" :error="$errors->first('dias_entrega')" />

            <!-- Requiere Ayuno -->
            <flux:select name="requiere_ayuno" label="¿Requiere ayuno?" :error="$errors->first('requiere_ayuno')">
                <flux:select.option value="0" :selected="old('requiere_ayuno', $laboratorio->requiere_ayuno) == 0">No</flux:select.option>
                <flux:select.option value="1" :selected="old('requiere_ayuno', $laboratorio->requiere_ayuno) == 1">Sí</flux:select.option>
            </flux:select>

            <!-- Estado -->
            <flux:select name="estado" label="Estado del estudio" :error="$errors->first('estado')">
                <flux:select.option value="ACTIVO" :selected="old('estado', $laboratorio->estado) == 'ACTIVO'">Activo</flux:select.option>
                <flux:select.option value="INACTIVO" :selected="old('estado', $laboratorio->estado) == 'INACTIVO'">Inactivo</flux:select.option>
            </flux:select>
        </div>

        <!-- Descripción -->
        <div class="mt-6">
            <flux:textarea name="descripcion" label="Descripción / Instrucciones" rows="3" :error="$errors->first('descripcion')">
                {{ old('descripcion', $laboratorio->descripcion) }}
            </flux:textarea>
        </div>

        <!-- Botones -->
        <div class="mt-10 flex gap-4 justify-end">
            <flux:button href="{{ route('admin.laboratorios.index') }}" variant="ghost">Cancelar</flux:button>
            <flux:button type="submit" variant="primary" color="green">Actualizar laboratorio</flux:button>
        </div>
    </form>
</x-layouts::app>
