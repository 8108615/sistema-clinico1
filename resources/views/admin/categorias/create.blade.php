<x-layouts::app title="Nueva Categoría">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Registrar nueva categoría</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="max-w-2xl bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700">
        <form action="{{ route('admin.categorias.store') }}" method="POST">
            @csrf

            <div class="grid gap-6">
                {{-- Nombre de la categoría --}}
                <flux:input 
                    name="nombre" 
                    label="Nombre de la categoría" 
                    placeholder="Ej. Material Médico, Reactivos, Desinfectantes" 
                    value="{{ old('nombre') }}" 
                    required 
                />

                @error('nombre')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4 mt-8">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    Guardar Categoría
                </button>
                <a href="{{ route('admin.categorias.index') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-layouts::app>