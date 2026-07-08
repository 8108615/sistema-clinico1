<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

new class extends Component {
    use WithPagination;

    public $buscar = '';

    public function updatingBuscar() {
        $this->resetPage();
    }

    public function with() {
        return [
            'roles' => Role::where('name', 'like', '%' . $this->buscar . '%')
                            ->paginate(10),
        ];
    }
};
?>

<div>
    <div class="flex items-center gap-4 mb-6">
        <div class="relative flex-1 max-w-sm">
            <flux:input wire:model.live.debounce.300ms="buscar"
                        icon="magnifying-glass"
                        placeholder="Buscar rol..." />

            @if($buscar)
                <button wire:click="$set('buscar', '')" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            @endif
        </div>

        <div class="text-sm text-gray-600 dark:text-gray-400">
            Se encontraron <strong>{{ $roles->total() }}</strong> resultados.
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                <tr>
                    <th class="px-6 py-3 border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Nro</th>
                    <th class="px-6 py-3 border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Rol</th>
                    <th class="px-6 py-3 border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @foreach ($roles as $index => $rol)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-6 py-4 text-center text-sm text-gray-900 dark:text-gray-100">
                            {{ $roles->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                            {{ $rol->name }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $roles->links() }}
    </div>
</div>
