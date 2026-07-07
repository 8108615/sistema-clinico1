<x-layouts::app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
         @if (($mensaje = Session::get('mensaje')) && ($icono = Session::get('icono')))
            <script>
                Swal.fire({
                    position: "top-end",
                    icon: "{{ $icono }}",
                    title: "{{ $mensaje }}",
                    showConfirmButton: false,
                    timer: 4000
                });
            </script>
        @endif

    </flux:main>
</x-layouts::app.sidebar>
