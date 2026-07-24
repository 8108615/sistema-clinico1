<x-layouts::auth :title="__('Iniciar Sesión')">
    <div class="w-full max-w-md mx-auto p-8 bg-white dark:bg-zinc-800/90 rounded-2xl shadow-xl border border-gray-100 dark:border-zinc-700/60 backdrop-blur-sm transition-all">

        {{-- Cabecera y Título --}}
        <div class="mb-8 text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 mb-4 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                <i class="fas fa-lock text-xl"></i>
            </div>
            <x-auth-header :title="__('Bienvenido de nuevo')" :description="__('Ingrese sus credenciales para acceder al sistema')" />
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

        <x-passkey-verify />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Email Address -->
            <div class="space-y-1">
                <flux:input
                    name="email"
                    :label="__('Correo electrónico')"
                    :value="old('email')"
                    type="email"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="correo@ejemplo.com"
                    class="w-full"
                />
            </div>

            <!-- Password -->
            <div class="space-y-1 relative">
                <flux:input
                    name="password"
                    :label="__('Contraseña')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('••••••••')"
                    viewable
                    class="w-full"
                />

                @if (Route::has('password.request'))
                    <div class="flex justify-end mt-1">
                        <flux:link class="text-xs font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400" :href="route('password.request')" wire:navigate>
                            {{ __('¿Olvidó su contraseña?') }}
                        </flux:link>
                    </div>
                @endif
            </div>

            <!-- Remember Me -->
            <div class="flex items-center my-1">
                <flux:checkbox name="remember" :label="__('Recordar mi sesión')" :checked="old('remember')" />
            </div>

            <!-- Submit Button -->
            <div class="mt-2">
                <flux:button variant="primary" type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 font-semibold text-white rounded-lg shadow-md transition-all duration-200" data-test="login-button">
                    <i class="fas fa-sign-in-alt mr-2"></i> {{ __('Iniciar Sesión') }}
                </flux:button>
            </div>
        </form>

        {{-- Footer de registro si aplica --}}
        @if (Route::has('register'))
            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-zinc-700/50 text-center text-sm text-zinc-600 dark:text-zinc-400">
                <span>{{ __('¿No tiene una cuenta?') }}</span>
                <flux:link :href="route('register')" wire:navigate class="font-semibold text-blue-600 hover:text-blue-500 dark:text-blue-400 ml-1">{{ __('Registrarse') }}</flux:link>
            </div>
        @endif
    </div>
</x-layouts::auth>
