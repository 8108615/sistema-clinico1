<div style="font-family: 'Courier New', monospace; text-align: left; max-width: 300px; margin: 0 auto; color: #000;">
    <div style="text-align: center;">
        <h3 style="margin:0">{{ $ajuste->nombre }}</h3>
        <p style="margin:0; font-size: 12px;">{{ $ajuste->direccion }}</p>
        <p style="margin:0; font-size: 12px;">{{ $ajuste->web }}</p>
    </div>
    <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
    <div style="text-align: center; font-weight: bold; font-size: 18px;">FICHA MÉDICA</div>
    <div style="margin: 10px 0; font-size: 13px;">
        <div><strong>Nro:</strong> V-{{ str_pad($consulta->id, 10, '0', STR_PAD_LEFT) }}</div>
        <div><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($consulta->fecha_atencion)->format('d/m/Y H:i') }}</div>
        <div><strong>Paciente:</strong> {{ $consulta->paciente->nombres }} {{ $consulta->paciente->apellidos }}</div>
        <div><strong>Atendido por:</strong> {{ $consulta->usuario->name }}</div>
        <div><strong>Precio:</strong> {{ $ajuste->divisa }} {{ number_format($consulta->precio, 2) }}</div>
    </div>
    <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
    <table style="width: 100%; font-size: 13px;">
        <tr><td>Consultorio:</td><td style="text-align: right;">{{ $consulta->consultorio->nombre }}</td></tr>
    </table>
    <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
    <div style="text-align: center; font-weight: bold; font-size: 16px;">ATENCIÓN CONFIRMADA</div>
</div>

<style>
    @media print {
        body { margin: 0; padding: 0; }
        .no-print { display: none; }
    }
</style>

<script>
    window.onload = function() {
        window.print();
    }
</script>
