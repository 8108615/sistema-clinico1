<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .logo { width: 120px; margin-bottom: 10px; }
        .nombre-clinica { font-size: 18px; font-weight: bold; }
        .info { font-size: 11px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #f2f2f2; border: 1px solid #ccc; padding: 8px; text-align: left; }
        td { border: 1px solid #ccc; padding: 8px; }

        .total-box { float: right; width: 250px; margin-top: 10px; }
        .total-row { display: flex; justify-content: space-between; padding: 5px 0; }
        .grand-total { border-top: 2px solid #333; font-weight: bold; font-size: 14px; }
    </style>
</head>
<body>
    <div class="header">
        <!-- Logo dinámico -->
        @if(!empty($ajuste->logo))
            <img src="{{ public_path('storage/' . $ajuste->logo) }}" class="logo">
        @endif
        <div class="nombre-clinica">{{ $ajuste->nombre }}</div>
        <div class="info">
            {{ $ajuste->direccion }} <br>
            Tel: {{ $ajuste->telefono }}<br>
            {{ $ajuste->email }}
        </div>
    </div>

    <h3>Reporte de Cierre de Caja #{{ $caja->id }}</h3>
    <p>
        <strong>Apertura:</strong> {{ \Carbon\Carbon::parse($caja->fecha_apertura)->format('d/m/Y H:i') }}<br>
        <strong>Cierre:</strong> {{ $caja->fecha_cierre ? \Carbon\Carbon::parse($caja->fecha_cierre)->format('d/m/Y H:i') : 'En curso' }}<br>
        <strong>Usuario:</strong> {{ $caja->user->name }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Concepto</th>
                <th style="text-align: right;">Monto ({{ $ajuste->divisa }})</th>
            </tr>
        </thead>
        <tbody>
            <!-- Desglose de ingresos del día -->
            <tr>
                <td>Total Consultas</td>
                <td style="text-align: right;">{{ number_format($caja->consultas->sum('precio'), 2) }}</td>
            </tr>
            <tr>
                <td>Total Laboratorios</td>
                <td style="text-align: right;">{{ number_format($caja->ordenLaboratorios->sum('total'), 2) }}</td>
            </tr>

            <!-- Subtotal Recaudado -->
            <tr style="background: #f9f9f9;">
                <td><strong>Subtotal Recaudado (Consultas + Labs)</strong></td>
                <td style="text-align: right;">
                    <strong>{{ number_format($caja->consultas->sum('precio') + $caja->ordenLaboratorios->sum('total'), 2) }}</strong>
                </td>
            </tr>

            <!-- Monto Inicial -->
            <tr>
                <td>Monto Inicial de Caja</td>
                <td style="text-align: right;">{{ number_format($caja->monto_inicial, 2) }}</td>
            </tr>

            <!-- Total Final en Caja -->
            <tr class="grand-total" style="font-weight: bold; background: #eef2f3; font-size: 14px; border-top: 2px solid #333;">
                <td>TOTAL FINAL EN CAJA (Inicial + Recaudado)</td>
                <td style="text-align: right;">
                    {{ number_format($caja->monto_inicial + ($caja->consultas->sum('precio') + $caja->ordenLaboratorios->sum('total')), 2) }}
                </td>
            </tr>
        </tbody>
    </table>
    <div style="margin-top: 60px; text-align: center;">
        <p style="margin-bottom: 5px;">__________________________</p>
        <p style="margin-top: 0; font-weight: bold;">Firma Responsable</p>
        <p style="margin-top: 5px; font-size: 11px;">
            {{ $caja->user->name }}<br>
            CI: {{ $caja->user->numero_documento }}
        </p>
    </div>
</body>
</html>
