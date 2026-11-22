
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte {{ ucfirst($tipo) }}</title>

    @php
        $logoPath = public_path('imagen/logo.png');
        $logoData = null;

        if (is_file($logoPath)) {
            $logoData = 'data:image/png;base64,' .
                base64_encode(file_get_contents($logoPath));
        }
    @endphp

    <style>
        @page {
            margin: 35px 30px 45px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #333;
        }

        .encabezado {
            text-align: center;
            margin-bottom: 15px;
        }

        .logo {
            width: 150px;
            height: auto;
            margin-bottom: 8px;
        }

        h1 {
            color: #790020;
            text-align: center;
            margin: 0;
        }

        h2 {
            color: #510014;
            text-align: center;
            font-size: 14px;
        }

        .sub {
            text-align: center;
            color: #D86D78;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th {
            background: #790020;
            color: white;
            text-align: left;
        }

        th, td {
            padding: 6px;
            border: 1px solid #ecc9cc;
            word-wrap: break-word;
        }

        tr:nth-child(even) {
            background: #fff4f5;
        }

        .footer {
            position: fixed;
            bottom: -25px;
            left: 0;
            right: 0;
            text-align: center;
            color: #790020;
            font-size: 8px;
        }
    </style>
</head>

<body>
    <div class="encabezado">
        @if($logoData)
            <img src="{{ $logoData }}" class="logo" alt="Pie Clinic">
        @endif

        <h1>PIE CLINIC</h1>
        <p class="sub">Consultorio Podológico</p>
    </div>

    <h2>Reporte de {{ ucfirst($tipo) }}</h2>

    <p>
        Generado: {{ now()->format('d/m/Y H:i') }}
        | Registros: {{ $registros->count() }}
    </p>

    <table>
        <thead>
            <tr>
                @foreach($columnas as $columna)
                    <th>{{ str_replace('_', ' ', ucfirst($columna)) }}</th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            @foreach($registros as $registro)
                <tr>
                    @foreach($columnas as $columna)
                        <td>
                            {{ \Illuminate\Support\Str::limit(
                                (string) ($registro->{$columna} ?? '—'),
                                180
                            ) }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Pie Clinic · Consultorio Podológico
    </div>
</body>
</html>
