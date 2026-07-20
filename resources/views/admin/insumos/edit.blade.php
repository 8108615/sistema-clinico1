<x-layouts::app title="Editar Insumo">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Editar insumo: {{ $insumo->nombre }}</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="max-w-3xl bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700">
        <form action="{{ route('admin.insumos.update', ['id' => $insumo->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nombre --}}
                <div class="md:col-span-2">
                    <flux:input name="nombre" label="Nombre del insumo" placeholder="Ej. Jeringas 5ml, Algodón..." value="{{ old('nombre', $insumo->nombre) }}" required />
                    @error('nombre') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Categoría --}}
                <div>
                    <flux:select name="categoria_id" label="Categoría" placeholder="Seleccione una categoría..." required>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ old('categoria_id', $insumo->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </flux:select>
                    @error('categoria_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Precio Compra --}}
                <div>
                    <flux:input name="precio_compra" label="Precio de compra ({{ $simboloMoneda }})" type="number" step="0.01" value="{{ old('precio_compra', $insumo->precio_compra) }}" required />
                    @error('precio_compra') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Stock --}}
                <div>
                    <flux:input name="stock" label="Stock actual" type="number" value="{{ old('stock', $insumo->stock) }}" required />
                    @error('stock') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Stock Mínimo --}}
                <div>
                    <flux:input name="stock_minimo" label="Stock mínimo para alerta" type="number" value="{{ old('stock_minimo', $insumo->stock_minimo) }}" required />
                    @error('stock_minimo') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex gap-4 mt-8">
                <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">
                    Actualizar Insumo
                </button>
                <a href="{{ route('admin.insumos.index') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-layouts::app>