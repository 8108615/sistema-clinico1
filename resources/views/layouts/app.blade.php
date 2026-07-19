<x-layouts::app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}

        {{-- 1. Alerta para errores de validación de formulario --}}
        @if ($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: '¡Revisa los campos!',
                    text: 'Algunos datos son incorrectos o faltan por completar.',
                    confirmButtonColor: '#3b82f6'
                });
            </script>
        @endif

        {{-- 2. Alerta para mensajes de éxito o errores capturados en el Catch --}}
        @if (Session::has('mensaje') || Session::has('error'))
            <script>
                Swal.fire({
                    position: "top-end",
                    icon: "{{ Session::has('mensaje') ? Session::get('icono') : 'error' }}",
                    title: "{{ Session::get('mensaje') ?? Session::get('error') }}",
                    showConfirmButton: {{ Session::has('error') ? 'true' : 'false' }},
                    timer: {{ Session::has('error') ? 'null' : '4000' }}
                });
            </script>
        @endif
    </flux:main>
</x-layouts::app.sidebar>
