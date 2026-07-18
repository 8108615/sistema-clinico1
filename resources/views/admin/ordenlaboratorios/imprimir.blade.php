<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden #{{ $orden->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #000; padding: 20px; }

        /* Contenedor principal estilo factura */
        .invoice-box { border: 1px solid #000; padding: 20px; }

        /* Encabezado */
        .header-row { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .logo { max-width: 150px; }
        .info-header { width: 50%; }
        .info-box { border: 1px solid #000; padding: 10px; width: 300px; }

        /* Cliente y Fechas */
        .meta-info { border: 1px solid #000; padding: 10px; margin-bottom: 20px; }
        .grid { display: flex; justify-content: space-between; }

        /* Tabla de productos */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #6366f1; color: white; padding: 10px; text-align: center; border: 1px solid #000; }
        td { border-left: 1px solid #000; border-right: 1px solid #000; padding: 10px; vertical-align: top; }
        .table-container { min-height: 400px; border-bottom: 1px solid #000; }

        /* Totales */
        .totals-row { border-top: 1px solid #000; display: flex; justify-content: flex-end; }
        .total-box { border: 1px solid #000; padding: 10px; width: 200px; text-align: right; font-weight: bold; }
    </style>
</head>
<body onload="window.print();">

<div class="invoice-box">
    <!-- Encabezado -->
    <div class="header-row">
        <div>
            @if(!empty($ajuste->logo))
                <img src="{{ asset('storage/' . $ajuste->logo) }}" class="logo">
            @endif
            <div class="info-header">
                <strong>{{ $ajuste->nombre }}</strong><br>
                {{ $ajuste->direccion }}<br>
                Tel: {{ $ajuste->telefono }}
            </div>
        </div>
        <div class="info-box">
            <strong>ORDEN DE LABORATORIO</strong><br>
            Nro: {{ $orden->id }}<br>
            FECHA: {{ \Carbon\Carbon::parse($orden->fecha_orden)->format('d/m/Y') }}
        </div>
    </div>

    <!-- Cliente -->
    <div class="meta-info">
        <strong>CLIENTE:</strong> {{ $orden->paciente->nombres }} {{ $orden->paciente->apellidos }}<br>
        <strong>DOCUMENTO (CI):</strong> {{ $orden->paciente->ci }}
    </div>

    <!-- Tabla -->
    <table>
        <thead>
            <tr>
                <th style="width: 10%;">CANT</th>
                <th>DESCRIPCIÓN</th>
                <th style="width: 15%;">VALOR ({{ $ajuste->divisa }})</th>
            </tr>
        </thead>
        <tbody class="table-container">
            @foreach ($orden->detalles as $detalle)
                <tr>
                    <td style="text-align: center;">1</td>
                    <td>{{ $detalle->laboratorio->nombre }}</td>
                    <td style="text-align: right;">{{ number_format($detalle->precio, 2) }}</td>
                </tr>
            @endforeach
            <!-- Espaciador para rellenar la tabla -->
            <tr><td colspan="3">&nbsp;</td></tr>
        </tbody>
    </table>

    <!-- Total -->
    <div class="totals-row">
        <div class="total-box">
            TOTAL: {{ number_format($orden->total, 2) }} {{ $ajuste->divisa }}
        </div>
    </div>
</div>

<div class="no-print" style="margin-top: 20px; text-align: center;">
    <button onclick="window.close()">Cerrar</button>
</div>

</body>
</html>
