<x-layouts::app title="Nuevo Insumo">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Registrar nuevo insumo</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="max-w-3xl bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700">
        <form action="{{ route('admin.insumos.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nombre --}}
                <div class="md:col-span-2">
                    <flux:input name="nombre" label="Nombre del insumo" placeholder="Ej. Jeringas 5ml, Algodón, etc." value="{{ old('nombre') }}" required />
                    @error('nombre') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Categoría --}}
                <div>
                    <flux:select name="categoria_id" label="Categoría" placeholder="Seleccione una categoría..." required>
                        @foreach($categorias as $categoria)
                            {{-- Cambiamos flux:option por option --}}
                            <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </flux:select>
                    @error('categoria_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Precio Compra --}}
                <div>
                    <flux:input name="precio_compra" label="Precio de compra ({{ $simboloMoneda }})" type="number" step="0.01" placeholder="0.00" value="{{ old('precio_compra') }}" required />
                    @error('precio_compra') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Stock Inicial --}}
                <div>
                    <flux:input name="stock" label="Stock inicial" type="number" placeholder="0" value="{{ old('stock', 0) }}" required />
                    @error('stock') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Stock Mínimo --}}
                <div>
                    <flux:input name="stock_minimo" label="Stock mínimo para alerta" type="number" placeholder="10" value="{{ old('stock_minimo', 10) }}" required />
                    @error('stock_minimo') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex gap-4 mt-8">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    Guardar Insumo
                </button>
                <a href="{{ route('admin.insumos.index') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-layouts::app>