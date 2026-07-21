<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Receta Médica #{{ $receta->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #2563eb;
            font-size: 22px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #666;
        }
        .info-box {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-box td {
            padding: 6px;
            vertical-align: top;
        }
        .info-title {
            font-weight: bold;
            color: #444;
            width: 130px;
        }
        .section-title {
            font-size: 15px;
            font-weight: bold;
            color: #2563eb;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        table.medicamentos {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.medicamentos th, table.medicamentos td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
            font-size: 13px;
        }
        table.medicamentos th {
            background-color: #f1f5f9;
            color: #1e293b;
            text-transform: uppercase;
            font-size: 11px;
        }
        .footer {
            margin-top: 60px;
            width: 100%;
            text-align: center;
        }
        .firma-line {
            width: 250px;
            border-top: 1px solid #333;
            margin: 0 auto;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <!-- Encabezado de la Clínica / Consultorio -->
    <div class="header">
        <h1>Sistema Médico</h1>
        <p>Receta Médica Oficial</p>
    </div>

    <!-- Información General -->
    <table class="info-box">
        <tr>
            <td class="info-title">Paciente:</td>
            <td>{{ $receta->paciente->nombres ?? '' }} {{ $receta->paciente->apellidos ?? '' }}</td>
            <td class="info-title">Fecha Receta:</td>
            <td>{{ \Carbon\Carbon::parse($receta->fecha_receta)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="info-title">Cédula (CI):</td>
            <td>{{ $receta->paciente->ci ?? 'N/A' }}</td>
            <td class="info-title">Médico Asignado:</td>
            <td>{{ $receta->medico->name ?? 'Sin asignar' }}</td>
        </tr>
        <tr>
            <td class="info-title">Nro Historia:</td>
            <td colspan="3">{{ $receta->historiaClinica->numero_historia ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- Listado de Medicamentos -->
    <div class="section-title">Fármacos Recetados</div>
    <table class="medicamentos">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">N°</th>
                <th style="width: 35%;">Medicamento</th>
                <th style="width: 25%;">Dosis</th>
                <th style="width: 15%;">Duración</th>
                <th style="width: 20%;">Indicaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($receta->detalles as $detalle)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td><strong>{{ $detalle->medicamento }}</strong></td>
                    <td>{{ $detalle->dosis }}</td>
                    <td>{{ $detalle->duracion }}</td>
                    <td>{{ $detalle->indicaciones ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Indicaciones Generales -->
    @if($receta->indicaciones_generales)
        <div class="section-title">Indicaciones Generales</div>
        <p style="background-color: #f8fafc; padding: 10px; border: 1px solid #e2e8f0; border-radius: 4px;">
            {{ $receta->indicaciones_generales }}
        </p>
    @endif

    <!-- Firma del Médico -->
    <div class="footer">
        <div class="firma-line">
            <strong>Dr. {{ $receta->medico->name ?? 'Médico Tratante' }}</strong><br>
            <span style="font-size: 11px; color: #666;">Firma y Sello</span>
        </div>
    </div>

</body>
</html>
