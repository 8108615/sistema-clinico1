@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="CLINICA GONZALES" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md overflow-hidden bg-transparent">
            <img src="{{ asset('img/logo.jpg') }}" alt="Logo Clínica" class="w-full h-full object-cover">
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="CLINICA GONZALES" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md overflow-hidden bg-transparent">
            <img src="{{ asset('img/logo.jpg') }}" alt="Logo Clínica" class="w-full h-full object-cover">
        </x-slot>
    </flux:brand>
@endif
