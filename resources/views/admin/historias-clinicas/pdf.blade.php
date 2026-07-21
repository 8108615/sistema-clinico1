<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historia Clínica - {{ $historia->numero_historia }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #1e40af;
            font-size: 20px;
        }
        .section {
            margin-bottom: 15px;
            border: 1px solid #d1d5db;
            padding: 10px;
            border-radius: 5px;
        }
        .section-title {
            font-weight: bold;
            background-color: #f3f4f6;
            padding: 5px;
            margin: -10px -10px 10px -10px;
            border-bottom: 1px solid #d1d5db;
            color: #1f2937;
        }
        .grid {
            width: 100%;
            margin-bottom: 10px;
        }
        .grid td {
            padding: 4px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
        .firma {
            margin-top: 60px;
            text-align: center;
        }
        .firma-linea {
            display: inline-block;
            width: 200px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>SISTEMA CLÍNICO - HISTORIA MÉDICA</h1>
        <p>Nro. de Historia: <strong>{{ $historia->numero_historia }}</strong></p>
    </div>

    <!-- Datos del Paciente -->
    <div class="section">
        <div class="section-title">DATOS DEL PACIENTE</div>
        <table class="grid">
            <tr>
                <td><strong>Paciente:</strong> {{ $historia->paciente->nombres }} {{ $historia->paciente->apellidos }}</td>
                <td><strong>Fecha de Atención:</strong> {{ \Carbon\Carbon::parse($historia->fecha_atencion)->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td><strong>Médico Asignado:</strong> {{ $historia->medico->name ?? 'No asignado' }}</td>
                <td><strong>Estado:</strong> {{ ucfirst($historia->estado) }}</td>
            </tr>
        </table>
    </div>

    <!-- Bloque SOAP -->
    <div class="section">
        <div class="section-title">I. SUBJETIVO</div>
        <p>{{ $historia->subjetivo }}</p>
    </div>

    <div class="section">
        <div class="section-title">II. OBJETIVO</div>
        <p>{{ $historia->objetivo }}</p>
    </div>

    <div class="section">
        <div class="section-title">III. ANÁLISIS</div>
        <p>{{ $historia->analisis }}</p>
    </div>

    <div class="section">
        <div class="section-title">IV. PLAN</div>
        <p>{{ $historia->plan }}</p>
    </div>

    <!-- Firma del Médico -->
    <div class="firma">
        <div class="firma-linea">
            Dr(a). {{ $historia->medico->name ?? '___________________' }}<br>
            Sello y Firma
        </div>
    </div>

    <div class="footer">
        Documento generado automáticamente el {{ date('d/m/Y H:i') }} | Sistema Clínico
    </div>

</body>
</html>