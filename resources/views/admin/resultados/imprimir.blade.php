<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado de Laboratorio #{{ $orden->id }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
            margin-bottom: 20px;
            width: 100%;
        }
        .header table {
            width: 100%;
            border-collapse: collapse;
        }
        .logo-img {
            max-height: 50px;
            max-width: 150px;
            object-fit: contain;
            margin-bottom: 5px;
        }
        .logo-text {
            font-size: 20px;
            font-weight: bold;
            color: #2563eb;
            text-transform: uppercase;
        }
        .info-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 20px;
        }
        .info-box table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-box td {
            padding: 4px 8px;
            vertical-align: top;
        }
        .exam-title {
            font-size: 14px;
            font-weight: bold;
            background-color: #2563eb;
            color: #ffffff;
            padding: 6px 10px;
            margin-top: 15px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
        table.resultados {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.resultados th, table.resultados td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            text-align: left;
        }
        table.resultados th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-size: 11px;
            text-transform: uppercase;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
        .firma-box {
            margin-top: 50px;
            text-align: right;
            page-break-inside: avoid;
        }
        .firma-linea {
            display: inline-block;
            width: 200px;
            border-top: 1px solid #333;
            text-align: center;
            padding-top: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Encabezado / Membrete -->
    <div class="header">
        <table>
            <tr>
                <td style="width: 50%;">
                    @if(isset($ajuste->logo) && $ajuste->logo)
                        {{-- Si guardas la ruta pública en storage, ajusta a public_path('storage/' . $ajuste->logo) --}}
                        <img src="{{ public_path('storage/' . $ajuste->logo) }}" class="logo-img" alt="Logo">
                    @endif
                    <div class="logo-text">{{ $ajuste->nombre ?? 'Laboratorio Clínico' }}</div>
                    <div style="font-size: 11px; color: #64748b;">Sistema de Gestión de Exámenes Médicos</div>
                </td>
                <td style="width: 50%; text-align: right;">
                    <div style="font-size: 13px; font-weight: bold; color: #1e293b;">{{ $ajuste->nombre ?? '' }}</div>
                    <div style="font-size: 10px; color: #64748b;">Dir: {{ $ajuste->direccion ?? '' }}</div>
                    <div style="font-size: 10px; color: #64748b;">Telf: {{ $ajuste->telefono ?? '' }}</div>
                    <div style="font-size: 13px; font-weight: bold; color: #2563eb; margin-top: 8px;">ORDEN N°: #{{ $orden->id }}</div>
                    <div style="font-size: 11px; color: #64748b;">Fecha: {{ $orden->fecha_orden ? \Carbon\Carbon::parse($orden->fecha_orden)->format('d/m/Y H:i') : 'N/A' }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Datos del Paciente -->
    <div class="info-box">
        <table>
            <tr>
                <td style="width: 50%;">
                    <strong>Paciente:</strong> {{ $orden->paciente->nombres ?? '' }} {{ $orden->paciente->apellidos ?? '' }}<br>
                    <strong>CI / Documento:</strong> {{ $orden->paciente->ci ?? 'No registrado' }}
                </td>
                <td style="width: 50%;">
                    <strong>Edad / Sexo:</strong>
                    @if(isset($orden->paciente->fecha_nacimiento))
                        {{ \Carbon\Carbon::parse($orden->paciente->fecha_nacimiento)->age }} años
                    @else
                        N/A
                    @endif
                    / {{ $orden->paciente->genero ?? 'N/A' }}<br>
                    <strong>Médico Solicitante:</strong> Dr(a). General / Particular
                </td>
            </tr>
        </table>
    </div>

    <!-- Listado de Exámenes y Resultados -->
    @foreach($orden->detalles as $detalle)
        @if($detalle->resultados->count() > 0)
            <div class="exam-title">{{ strtoupper($detalle->laboratorio->nombre ?? 'EXAMEN CLÍNICO') }}</div>

            <table class="resultados">
                <thead>
                    <tr>
                        <th style="width: 25%;">Parámetro</th>
                        <th style="width: 20%;">Resultado</th>
                        <th style="width: 15%;">Unidad</th>
                        <th style="width: 20%;">Valores de Referencia</th>
                        <th style="width: 20%;">Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detalle->resultados as $res)
                        <tr>
                            <td><strong>{{ $res->parametro }}</strong></td>
                            <td style="color: #2563eb; font-weight: bold;">{{ $res->resultado }}</td>
                            <td>{{ $res->unidad_medida ?? '-' }}</td>
                            <td>{{ $res->valores_referencia ?? '-' }}</td>
                            <td>{{ $res->observaciones ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach

    <!-- Área de Firma -->
    <div class="firma-box">
        <div class="firma-linea">
            Responsable de Laboratorio<br>
            <span style="font-size: 10px; font-weight: normal; color: #555;">
                {{ $orden->detalles->first()->resultados->first()->usuario->name ?? 'Personal Autorizado' }}
            </span>
        </div>
    </div>

    <!-- Pie de página -->
    <div class="footer">
        Este documento es un reporte oficial de resultados de laboratorio. Generado el {{ date('d/m/Y H:i') }}
    </div>

</body>
</html>
