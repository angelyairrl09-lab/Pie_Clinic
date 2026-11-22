<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReporteController extends Controller
{
    private const TIPOS = [
        'pacientes',
        'citas',
        'tratamientos',
        'seguimientos',
        'inventario',
        'finanzas'
    ];

    private function origen(string $tipo): array
    {
        $tablas = [
            'pacientes' => ['pc_pacientes', 'created_at'],
            'citas' => ['pc_citas', 'fecha_cita'],
            'tratamientos' => ['pc_tratamientos', 'fecha_inicio'],
            'seguimientos' => ['pc_seguimientos', 'fecha_consulta'],
            'inventario' => ['pc_inventario_productos', 'created_at'],
            'finanzas' => ['pc_finanzas_movimientos', 'fecha'],
        ];

        return $tablas[$tipo];
    }

    private function obtener(Request $request): array
    {
        $filtros = $request->validate([
            'tipo' => 'nullable|in:' . implode(',', self::TIPOS),
            'desde' => 'nullable|date',
            'hasta' => 'nullable|date|after_or_equal:desde',
        ]);

        $tipo = $filtros['tipo'] ?? 'tratamientos';

        [$tabla, $fecha] = $this->origen($tipo);

        if (!Schema::hasTable($tabla)) {
            return compact('tipo') + [
                'columnas' => [],
                'registros' => collect(),
                'error' => "No existe la tabla {$tabla}. Verifica la migración del módulo."
            ];
        }

        $columnas = Schema::getColumnListing($tabla);

        $query = DB::table($tabla);

        if (in_array($fecha, $columnas, true)) {
            if (!empty($filtros['desde'])) {
                $query->whereDate(
                    $fecha,
                    '>=',
                    $filtros['desde']
                );
            }

            if (!empty($filtros['hasta'])) {
                $query->whereDate(
                    $fecha,
                    '<=',
                    $filtros['hasta']
                );
            }

            $query->orderByDesc($fecha);
        }

        $columnasVisibles = array_slice(
            array_values(
                array_filter(
                    $columnas,
                    fn ($col) => !in_array(
                        $col,
                        [
                            'updated_at',
                            'password',
                            'remember_token'
                        ],
                        true
                    )
                )
            ),
            0,
            8
        );

        return [
            'tipo' => $tipo,
            'columnas' => $columnasVisibles,
            'registros' => $query
                ->limit(500)
                ->get($columnasVisibles),
            'error' => null,
        ];
    }

    public function index(Request $request)
    {
        return view(
            'reportes.index',
            $this->obtener($request)
        );
    }

    public function pdf(Request $request)
    {
        $datos = $this->obtener($request);

        if ($datos['error']) {
            return redirect()
                ->route('reportes.index')
                ->with('error', $datos['error']);
        }

        $logo = public_path('imagen/logo.png');

        $texto = static function ($valor): string {
            $salida = iconv(
                'UTF-8',
                'windows-1252//TRANSLIT',
                (string) $valor
            );

            return $salida === false ? '' : $salida;
        };

        /*
        |--------------------------------------------------------------------------
        | PDF FPDF - MISMO MÉTODO QUE TRATAMIENTOS
        |--------------------------------------------------------------------------
        */

        $pdf = new class('L', 'mm', 'Letter') extends \FPDF {

            public ?string $logo = null;

            private array $transparencias = [];

            /*
            |--------------------------------------------------------------------------
            | TRANSPARENCIA
            |--------------------------------------------------------------------------
            */

            public function SetAlpha(float $alpha): void
            {
                $alpha = max(0, min(1, $alpha));

                $clave = sprintf('%.3F', $alpha);

                if (!isset($this->transparencias[$clave])) {
                    $this->transparencias[$clave] = [
                        'id' => count($this->transparencias) + 1,
                        'alpha' => $alpha,
                    ];
                }

                $this->_out(
                    '/GS' .
                    $this->transparencias[$clave]['id'] .
                    ' gs'
                );
            }

            protected function _putresources(): void
            {
                foreach ($this->transparencias as $clave => $estado) {
                    $this->_newobj();

                    $this->_put(
                        '<< /Type /ExtGState /ca ' .
                        $estado['alpha'] .
                        ' /CA ' .
                        $estado['alpha'] .
                        ' >>'
                    );

                    $this->_put('endobj');

                    $this->transparencias[$clave]['obj'] = $this->n;
                }

                parent::_putresources();
            }

            protected function _putresourcedict(): void
            {
                parent::_putresourcedict();

                $this->_put('/ExtGState <<');

                foreach ($this->transparencias as $estado) {
                    $this->_put(
                        '/GS' .
                        $estado['id'] .
                        ' ' .
                        $estado['obj'] .
                        ' 0 R'
                    );
                }

                $this->_put('>>');
            }

            protected function _enddoc(): void
            {
                $this->PDFVersion = '1.4';

                parent::_enddoc();
            }

            /*
            |--------------------------------------------------------------------------
            | ENCABEZADO Y MARCA DE AGUA
            |--------------------------------------------------------------------------
            */

            public function Header(): void
            {
                if ($this->logo && is_file($this->logo)) {

                    /*
                    | Marca de agua centrada y tenue.
                    | Se dibuja antes del contenido.
                    */

                    $dimensiones = @getimagesize($this->logo);

                    if (
                        $dimensiones &&
                        $dimensiones[0] > 0 &&
                        $dimensiones[1] > 0
                    ) {
                        $ancho = 145.0;

                        $alto = $ancho *
                            $dimensiones[1] /
                            $dimensiones[0];

                        $altoMaximo = 100.0;

                        if ($alto > $altoMaximo) {
                            $alto = $altoMaximo;

                            $ancho = $alto *
                                $dimensiones[0] /
                                $dimensiones[1];
                        }

                        $x = (
                            $this->GetPageWidth() - $ancho
                        ) / 2;

                        $y = 43 + (
                            (
                                $this->GetPageHeight() -
                                43 -
                                22
                            ) - $alto
                        ) / 2;

                        // Opacidad de 8.5 %
                        $this->SetAlpha(0.085);

                        $this->Image(
                            $this->logo,
                            $x,
                            $y,
                            $ancho,
                            $alto
                        );

                        // Restaurar opacidad.
                        $this->SetAlpha(1);
                    }

                    /*
                    | Logotipo del encabezado.
                    */

                    $this->Image(
                        $this->logo,
                        15,
                        10,
                        42
                    );
                }

                $this->SetTextColor(121, 0, 32);
                $this->SetFont('Helvetica', 'B', 16);

                $this->SetXY(60, 12);

                $this->Cell(
                    160,
                    9,
                    'PIE CLINIC',
                    0,
                    1,
                    'C'
                );

                $this->SetFont('Helvetica', '', 10);

                $this->SetX(60);

                $this->Cell(
                    160,
                    6,
                    'Consultorio Podologico',
                    0,
                    1,
                    'C'
                );

                $this->SetDrawColor(216, 109, 120);

                $this->Line(
                    15,
                    37,
                    264,
                    37
                );

                $this->SetY(43);
            }

            /*
            |--------------------------------------------------------------------------
            | PIE DE PÁGINA
            |--------------------------------------------------------------------------
            */

            public function Footer(): void
            {
                $this->SetY(-17);

                $this->SetDrawColor(216, 109, 120);

                $this->Line(
                    15,
                    $this->GetY(),
                    264,
                    $this->GetY()
                );

                $this->Ln(3);

                $this->SetTextColor(121, 0, 32);

                $this->SetFont('Helvetica', '', 8);

                $this->Cell(
                    190,
                    5,
                    'Pie Clinic - Consultorio Podologico',
                    0,
                    0
                );

                $this->Cell(
                    59,
                    5,
                    'Pagina ' . $this->PageNo(),
                    0,
                    0,
                    'R'
                );
            }
        };

        /*
        |--------------------------------------------------------------------------
        | CONFIGURACIÓN DEL DOCUMENTO
        |--------------------------------------------------------------------------
        */

        $pdf->logo = is_file($logo) ? $logo : null;

        $pdf->SetMargins(15, 43, 15);

        $pdf->SetAutoPageBreak(true, 22);

        $pdf->AddPage();

        /*
        |--------------------------------------------------------------------------
        | TÍTULO DEL REPORTE
        |--------------------------------------------------------------------------
        */

        $pdf->SetTextColor(81, 0, 20);

        $pdf->SetFont('Helvetica', 'B', 14);

        $pdf->Cell(
            0,
            10,
            $texto(
                'Reporte de ' . ucfirst($datos['tipo'])
            ),
            0,
            1,
            'C'
        );

        $pdf->SetTextColor(51, 51, 51);

        $pdf->SetFont('Helvetica', '', 9);

        $pdf->Cell(
            0,
            8,
            $texto(
                'Generado: ' .
                now()->format('d/m/Y H:i') .
                ' | Registros: ' .
                $datos['registros']->count()
            ),
            0,
            1
        );

        $pdf->Ln(3);

        /*
        |--------------------------------------------------------------------------
        | TABLA DE RESULTADOS
        |--------------------------------------------------------------------------
        */

        $columnas = $datos['columnas'];

        $cantidad = count($columnas);

        if ($cantidad > 0) {

            $ancho = 249 / $cantidad;

            /*
            | Encabezados de la tabla.
            */

            $dibujarEncabezados = function () use (
                $pdf,
                $columnas,
                $ancho,
                $texto
            ): void {

                $pdf->SetFillColor(121, 0, 32);

                $pdf->SetTextColor(255, 255, 255);

                $pdf->SetDrawColor(236, 201, 204);

                $pdf->SetFont('Helvetica', 'B', 8);

                foreach ($columnas as $columna) {

                    $etiqueta = $texto(
                        str_replace(
                            '_',
                            ' ',
                            ucfirst($columna)
                        )
                    );

                    while (
                        strlen($etiqueta) > 0 &&
                        $pdf->GetStringWidth($etiqueta) >
                        $ancho - 3
                    ) {
                        $etiqueta = substr(
                            $etiqueta,
                            0,
                            -1
                        );
                    }

                    $pdf->Cell(
                        $ancho,
                        9,
                        $etiqueta,
                        1,
                        0,
                        'L',
                        true
                    );
                }

                $pdf->Ln();
            };

            $dibujarEncabezados();

            /*
            | Registros.
            */

            $fila = 0;

            foreach ($datos['registros'] as $registro) {

                /*
                | Nueva página cuando no hay espacio.
                */

                if ($pdf->GetY() + 8 > 193) {
                    $pdf->AddPage();

                    $dibujarEncabezados();
                }

                $pdf->SetTextColor(51, 51, 51);

                $pdf->SetFont('Helvetica', '', 7);

                $pdf->SetDrawColor(236, 201, 204);

                /*
                | Filas alternadas.
                */

                if ($fila % 2 === 0) {
                    $pdf->SetFillColor(
                        255,
                        244,
                        245
                    );
                } else {
                    $pdf->SetFillColor(
                        255,
                        255,
                        255
                    );
                }

                foreach ($columnas as $columna) {

                    $valor = $texto(
                        $registro->{$columna} ?? '—'
                    );

                    $maximo = max(
                        0,
                        $ancho - 3
                    );

                    if (
                        $pdf->GetStringWidth($valor) >
                        $maximo
                    ) {

                        while (
                            strlen($valor) > 0 &&
                            $pdf->GetStringWidth(
                                $valor . '...'
                            ) > $maximo
                        ) {
                            $valor = substr(
                                $valor,
                                0,
                                -1
                            );
                        }

                        $valor .= '...';
                    }

                    $pdf->Cell(
                        $ancho,
                        8,
                        $valor,
                        1,
                        0,
                        'L',
                        true
                    );
                }

                $pdf->Ln();

                $fila++;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | DESCARGA DEL PDF
        |--------------------------------------------------------------------------
        */

        $nombre = 'reporte-' .
            $datos['tipo'] . '-' .
            now()->format('Ymd') .
            '.pdf';

        return response(
            $pdf->Output('S'),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' =>
                    'attachment; filename="' .
                    $nombre .
                    '"',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORTACIÓN CSV ORIGINAL
    |--------------------------------------------------------------------------
    */

    public function csv(Request $request)
    {
        $datos = $this->obtener($request);

        if ($datos['error']) {
            return redirect()
                ->route('reportes.index')
                ->with('error', $datos['error']);
        }

        return response()->streamDownload(
            function () use ($datos) {

                $archivo = fopen(
                    'php://output',
                    'w'
                );

                fwrite(
                    $archivo,
                    "\xEF\xBB\xBF"
                );

                fputcsv(
                    $archivo,
                    $datos['columnas']
                );

                foreach ($datos['registros'] as $registro) {

                    fputcsv(
                        $archivo,
                        array_map(
                            function ($col) use ($registro) {

                                $valor = (string) (
                                    $registro->{$col} ?? ''
                                );

                                return preg_match(
                                    '/^[=+\-@\t\r]/u',
                                    $valor
                                )
                                    ? "'" . $valor
                                    : $valor;
                            },
                            $datos['columnas']
                        )
                    );
                }

                fclose($archivo);
            },
            'reporte-' .
            $datos['tipo'] . '-' .
            now()->format('Ymd') .
            '.csv',
            [
                'Content-Type' =>
                    'text/csv; charset=UTF-8'
            ]
        );
    }
}
