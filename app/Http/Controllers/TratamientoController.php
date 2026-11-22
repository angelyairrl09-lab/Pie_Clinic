<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Tratamiento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TratamientoController extends Controller
{
    private const ESTADOS = [
        'PENDIENTE',
        'ACTIVO',
        'FINALIZADO',
        'CANCELADO',
    ];

    public function index(Request $request)
    {
        $filtros = $request->validate([
            'buscar' => 'nullable|string|max:150',
            'estado' => ['nullable', Rule::in(self::ESTADOS)],
        ]);

        $tratamientos = Tratamiento::with('paciente')
            ->when($filtros['buscar'] ?? null, function ($query, $buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'like', "%{$buscar}%")
                      ->orWhereHas('paciente', function ($p) use ($buscar) {
                          $p->where('numero_paciente', 'like', "%{$buscar}%")
                            ->orWhereRaw(
                                "CONCAT_WS(' ', nombre, apellido_paterno, apellido_materno) LIKE ?",
                                ["%{$buscar}%"]
                            );
                      });
                });
            })
            ->when($filtros['estado'] ?? null, function ($query, $estado) {
                $query->where('estado', $estado);
            })
            ->orderByDesc('fecha_inicio')
            ->orderByDesc('id_tratamiento')
            ->paginate(15)
            ->withQueryString();

        $totalTratamientos = Tratamiento::count();
        $tratamientosActivos = Tratamiento::where('estado', 'ACTIVO')->count();
        $tratamientosPendientes = Tratamiento::where('estado', 'PENDIENTE')->count();
        $tratamientosFinalizados = Tratamiento::where('estado', 'FINALIZADO')->count();

        return view('tratamientos.index', compact(
            'tratamientos',
            'totalTratamientos',
            'tratamientosActivos',
            'tratamientosPendientes',
            'tratamientosFinalizados'
        ));
    }

    public function create()
    {
        $pacientes = Paciente::where('activo', true)
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombre')
            ->get();

        return view('tratamientos.create', compact('pacientes'));
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'id_paciente' => [
                'required',
                Rule::exists('pc_pacientes', 'id_paciente')->where('activo', true),
            ],
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string|max:10000',
            'indicaciones' => 'nullable|string|max:10000',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'costo' => 'nullable|numeric|min:0|max:99999999.99',
            'estado' => ['required', Rule::in(self::ESTADOS)],
        ]);
    }

    public function store(Request $request)
    {
        $datos = $this->validar($request);
        $datos['registrado_por'] = $request->user()->id;
        $tratamiento = Tratamiento::create($datos);

        return redirect()
            ->route('tratamientos.show', $tratamiento)
            ->with('success', 'Tratamiento registrado correctamente.');
    }

    public function show(Tratamiento $tratamiento)
    {
        $tratamiento->load(['paciente', 'usuarioRegistro']);
        return view('tratamientos.show', compact('tratamiento'));
    }

    public function pdf(Tratamiento $tratamiento)
    {
        $tratamiento->load(['paciente', 'usuarioRegistro']);
        $logo = public_path('imagen/logo.png');

        $pdf = new class('L', 'mm', 'Letter') extends \FPDF {
            public ?string $logo = null;
            public string $fechaDocumento = '';
            private array $transparencias = [];

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
                $this->_out('/GS' . $this->transparencias[$clave]['id'] . ' gs');
            }

            protected function _putresources(): void
            {
                foreach ($this->transparencias as $clave => $estado) {
                    $this->_newobj();
                    $this->_put('<< /Type /ExtGState /ca ' . $estado['alpha'] . ' /CA ' . $estado['alpha'] . ' >>');
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
                    $this->_put('/GS' . $estado['id'] . ' ' . $estado['obj'] . ' 0 R');
                }
                $this->_put('>>');
            }

            protected function _enddoc(): void
            {
                $this->PDFVersion = '1.4';
                parent::_enddoc();
            }

            private function codificar(string $texto): string
            {
                $resultado = iconv('UTF-8', 'windows-1252//TRANSLIT', $texto);
                return $resultado === false ? '' : $resultado;
            }

            public function Header(): void
            {
                // Marca de agua horizontal, tenue y detrás del contenido, en cada hoja.
                if ($this->logo && is_file($this->logo)) {
                    $dimensiones = @getimagesize($this->logo);
                    if ($dimensiones && $dimensiones[0] > 0 && $dimensiones[1] > 0) {
                        // Centrar la marca de agua en el área útil, sin invadir el encabezado.
                        $ancho = 145.0;
                        $alto = $ancho * $dimensiones[1] / $dimensiones[0];
                        $altoMaximo = 100.0;
                        if ($alto > $altoMaximo) {
                            $alto = $altoMaximo;
                            $ancho = $alto * $dimensiones[0] / $dimensiones[1];
                        }
                        $x = ($this->GetPageWidth() - $ancho) / 2;
                        $y = 46 + (($this->GetPageHeight() - 46 - 28) - $alto) / 2;
                        $this->SetAlpha(0.085);
                        $this->Image($this->logo, $x, $y, $ancho, $alto);
                        $this->SetAlpha(1);
                    }
                }

                // Encabezado inspirado en el formato físico de Pie Clinic.
                $this->SetDrawColor(226, 179, 178);
                $this->SetTextColor(81, 0, 20);
                if ($this->logo && is_file($this->logo)) {
                    $this->Image($this->logo, 15, 12, 60);
                } else {
                    $this->SetFont('Times', 'BI', 21);
                    $this->SetXY(15, 15);
                    $this->Cell(60, 10, 'Pie Clinic');
                }

                $this->SetXY(82, 13);
                $this->SetFont('Helvetica', 'B', 10);
                $this->Cell(73, 5, 'L.E. Brenda', 0, 1);
                $this->SetX(82);
                $this->Cell(73, 5, $this->codificar('Guadarrama Pérez'), 0, 1);
                $this->SetX(82);
                $this->SetFont('Helvetica', '', 8);
                $this->Cell(73, 5, $this->codificar('CÉD. PROF: 11393642'));

                $this->SetXY(228, 13);
                $this->SetFont('Helvetica', 'B', 8);
                $this->Cell(39, 5, 'FECHA: ' . $this->fechaDocumento, 0, 0, 'R');
                $this->Line(15, 38, 264, 38);
                $this->SetY(46);
            }

            public function Footer(): void
            {
                $this->SetY(-23);
                $this->SetDrawColor(226, 179, 178);
                $this->Line(15, $this->GetY(), 264, $this->GetY());
                $this->Ln(3);
                $this->SetTextColor(81, 0, 20);
                $this->SetFont('Helvetica', 'B', 9);
                $this->Cell(120, 5, '720 226 7464', 0, 0);
                $this->Cell(120, 5, 'pieclinic', 0, 1, 'R');
                $this->SetFont('Helvetica', '', 8);
                $this->Cell(190, 4, 'Calle Normalismo S/N, El Arco, Valle de Bravo.', 0, 0);
                $this->Cell(50, 4, $this->codificar('Página ') . $this->PageNo(), 0, 0, 'R');
            }
        };

        $pdf->logo = is_file($logo) ? $logo : null;
        $pdf->fechaDocumento = now()->format('d/m/Y');
        $pdf->SetMargins(18, 46, 18);
        $pdf->SetAutoPageBreak(true, 28);
        $pdf->AddPage();

        $texto = static function ($valor): string {
            $salida = iconv('UTF-8', 'windows-1252//TRANSLIT', (string) $valor);
            return $salida === false ? '' : $salida;
        };

        $pdf->SetTextColor(81, 0, 20);
        $pdf->SetFont('Times', 'B', 15);
        $pdf->Cell(0, 9, $texto('REPORTE DE TRATAMIENTO PODOLÓGICO'), 0, 1, 'C');
        $pdf->Ln(5);

        $seccion = function (string $titulo) use ($pdf, $texto): void {
            if ($pdf->GetY() > $pdf->GetPageHeight() - 40) {
                $pdf->AddPage();
            }
            $pdf->SetFillColor(248, 231, 230);
            $pdf->SetTextColor(121, 0, 32);
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->Cell(0, 9, $texto($titulo), 1, 1, 'L', true);
            $pdf->Ln(3);
        };

        $campo = function (string $etiqueta, $valor) use ($pdf, $texto): void {
            $pdf->SetTextColor(81, 0, 20);
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->MultiCell(0, 5, $texto($etiqueta . ':'));
            $pdf->SetTextColor(45, 45, 45);
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->MultiCell(0, 5, $texto($valor ?? 'Sin definir'));
            $pdf->Ln(2);
        };

        $seccion('DATOS DEL PACIENTE');
        $campo('Nombre del paciente', $tratamiento->paciente?->nombre_completo ?? 'Sin paciente');
        $campo('Número de paciente', $tratamiento->paciente?->numero_paciente ?? 'No disponible');

        $pdf->Ln(3);
        $seccion('INFORMACIÓN DEL TRATAMIENTO');
        $campo('Tratamiento', $tratamiento->nombre);
        $campo('Estado', $tratamiento->estado);
        $campo('Fecha de inicio', $tratamiento->fecha_inicio?->format('d/m/Y'));
        $campo('Fecha de finalización', $tratamiento->fecha_fin?->format('d/m/Y'));
        $campo('Costo', $tratamiento->costo !== null ? '$' . number_format($tratamiento->costo, 2) . ' MXN' : 'Sin definir');
        $campo('Registrado por', $tratamiento->usuarioRegistro?->name ?? 'No disponible');

        $pdf->Ln(3);
        $seccion('DESCRIPCIÓN DEL TRATAMIENTO');
        $pdf->SetTextColor(45, 45, 45);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->MultiCell(0, 6, $texto($tratamiento->descripcion ?: 'Sin descripción'));

        $pdf->Ln(5);
        $seccion('INDICACIONES PARA EL PACIENTE');
        $pdf->SetTextColor(45, 45, 45);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->MultiCell(0, 6, $texto($tratamiento->indicaciones ?: 'Sin indicaciones'));

        return response($pdf->Output('S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="tratamiento-' . $tratamiento->id_tratamiento . '.pdf"',
        ]);
    }

    public function edit(Tratamiento $tratamiento)
    {
        $pacientes = Paciente::where('activo', true)
            ->orWhere('id_paciente', $tratamiento->id_paciente)
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombre')
            ->get();

        return view('tratamientos.edit', compact('tratamiento', 'pacientes'));
    }

    public function update(Request $request, Tratamiento $tratamiento)
    {
        $datos = $this->validar($request);
        $tratamiento->update($datos);

        return redirect()
            ->route('tratamientos.show', $tratamiento)
            ->with('success', 'Tratamiento actualizado correctamente.');
    }

    public function destroy(Tratamiento $tratamiento)
    {
        abort(405, 'Los tratamientos clínicos no se eliminan.');
    }
}
