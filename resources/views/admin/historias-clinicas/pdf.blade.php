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
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            color: #1e40af;
            font-size: 18px;
        }
        .header p {
            margin: 0;
            color: #555;
        }
        .section {
            margin-bottom: 15px;
            border: 1px solid #d1d5db;
            padding: 10px;
            border-radius: 5px;
            page-break-inside: avoid;
        }
        .section-title {
            font-weight: bold;
            background-color: #f3f4f6;
            padding: 6px 10px;
            margin: -10px -10px 10px -10px;
            border-bottom: 1px solid #d1d5db;
            color: #1f2937;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .grid td {
            padding: 4px;
            vertical-align: top;
            width: 50%;
        }
        .content-box {
            white-space: pre-line;
            margin: 0;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .firma {
            margin-top: 50px;
            text-align: center;
            page-break-inside: avoid;
        }
        .firma-linea {
            display: inline-block;
            width: 250px;
            border-top: 1px solid #000;
            padding-top: 5px;
            font-size: 11px;
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
        <div class="section-title">Datos del Paciente</div>
        <table class="grid">
            <tr>
                <td><strong>Paciente:</strong> {{ $historia->paciente->nombres ?? '' }} {{ $historia->paciente->apellidos ?? '' }}</td>
                <td><strong>Fecha de Atención:</strong> {{ \Carbon\Carbon::parse($historia->created_at)->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td><strong>Médico Asignado:</strong> {{ $historia->medico->name ?? 'No asignado' }}</td>
                <td><strong>Estado:</strong> {{ ucfirst($historia->estado) }}</td>
            </tr>
        </table>
    </div>

    <!-- Bloque SOAP -->
    <div class="section">
        <div class="section-title">I. Subjetivo</div>
        <p class="content-box">{{ $historia->subjetivo }}</p>
    </div>

    <div class="section">
        <div class="section-title">II. Objetivo</div>
        <p class="content-box">{{ $historia->objetivo }}</p>
    </div>

    <div class="section">
        <div class="section-title">III. Análisis</div>
        <p class="content-box">{{ $historia->analisis }}</p>
    </div>

    <div class="section">
        <div class="section-title">IV. Plan</div>
        <p class="content-box">{{ $historia->plan }}</p>
    </div>

    <!-- Firma del Médico -->
    <div class="firma">
        <div class="firma-linea">
            Dr(a). {{ $historia->medico->name ?? '___________________' }}<br>
            <strong>Sello y Firma</strong>
        </div>
    </div>

    <div class="footer">
        Documento generado automáticamente el {{ date('d/m/Y H:i') }} | Sistema Clínico
    </div>

</body>
</html>
