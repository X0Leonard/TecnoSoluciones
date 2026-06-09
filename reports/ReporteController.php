<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../views/auth/login.php');
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Proyecto.php';

class ReporteController {

    private $cliente;
    private $proyecto;

    public function __construct() {
        $this->cliente  = new Cliente();
        $this->proyecto = new Proyecto();
    }

    private function crearPDF($titulo) {
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('TecnoSoluciones S.A.');
        $pdf->SetAuthor('TecnoSoluciones S.A.');
        $pdf->SetTitle($titulo);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(15, 15, 15);
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 20);
        return $pdf;
    }

    private function encabezado($pdf, $titulo, $subtitulo) {
        $pdf->SetFillColor(13, 22, 38);
        $pdf->Rect(0, 0, 297, 40, 'F');
        $pdf->SetFillColor(201, 168, 76);
        $pdf->Rect(0, 38, 297, 2, 'F');
        $pdf->SetTextColor(201, 168, 76);
        $pdf->SetFont('helvetica', 'B', 22);
        $pdf->SetXY(15, 7);
        $pdf->Cell(0, 10, 'TecnoSoluciones S.A.', 0, 1, 'L');
        $pdf->SetTextColor(138, 155, 191);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->SetXY(15, 20);
        $pdf->Cell(0, 8, $titulo . ' — ' . $subtitulo, 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(201, 168, 76);
        $pdf->SetXY(200, 14);
        $pdf->Cell(80, 8, 'Generado: ' . date('d/m/Y H:i'), 0, 1, 'R');
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetY(50);
    }

    private function cabeceraTabla($pdf, $cabeceras, $anchos) {
        $pdf->SetFillColor(201, 168, 76);
        $pdf->SetTextColor(13, 22, 38);
        $pdf->SetFont('helvetica', 'B', 9);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        foreach ($cabeceras as $i => $cab) {
            $pdf->SetXY($x, $y);
            $pdf->Cell($anchos[$i], 8, $cab, 0, 0, 'C', true);
            $x += $anchos[$i];
        }
        $pdf->Ln(8);
        $pdf->SetTextColor(15, 23, 42);
    }

    private function filaTabla($pdf, $datos, $anchos, $fill = false) {
        if ($fill) {
            $pdf->SetFillColor(26, 40, 68);
        } else {
            $pdf->SetFillColor(21, 32, 53);
        }
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(190, 205, 230);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $maxH = 7;
        foreach ($datos as $i => $dato) {
            $pdf->SetXY($x, $y);
            $pdf->MultiCell($anchos[$i], $maxH, $dato, 0, 'L', true);
            $x += $anchos[$i];
        }
        $pdf->SetDrawColor(31, 49, 88);
        $totalAncho = array_sum($anchos);
        $pdf->SetXY(15, $y + $maxH);
        $pdf->Cell($totalAncho, 0, '', 'T', 1);
        $pdf->SetY($y + $maxH);
    }

    private function footer($pdf) {
        $pdf->SetAutoPageBreak(false);
        $pdf->SetFillColor(13, 22, 38);
        $pdf->Rect(0, 190, 297, 20, 'F');
        $pdf->SetTextColor(201, 168, 76);
        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->SetXY(0, 193);
        $pdf->Cell(297, 8, 'TecnoSoluciones S.A. — Documento generado automáticamente', 0, 1, 'C');
    }

    public function reporteClientes() {
        $clientes = $this->cliente->obtenerTodos();
        $pdf      = $this->crearPDF('Reporte de Clientes');
        $this->encabezado($pdf, 'Reporte de Clientes', 'Listado completo');

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetTextColor(201, 168, 76);
        $pdf->Cell(0, 8, 'Total de clientes registrados: ' . count($clientes), 0, 1, 'L');
        $pdf->Ln(3);

        $cabeceras = ['#', 'Nombre', 'Email', 'Teléfono', 'Empresa', 'Registrado'];
        $anchos    = [12, 55, 65, 35, 55, 30];
        $this->cabeceraTabla($pdf, $cabeceras, $anchos);

        foreach ($clientes as $i => $c) {
            $datos = [
                $c['id'],
                $c['nombre'],
                $c['email'],
                $c['telefono'] ?? '—',
                $c['empresa']  ?? '—',
                date('d/m/Y', strtotime($c['created_at']))
            ];
            $this->filaTabla($pdf, $datos, $anchos, $i % 2 === 0);
        }

        $this->footer($pdf);
        $pdf->Output('reporte_clientes_' . date('Ymd') . '.pdf', 'D');
    }

    public function reporteProyectos() {
        $proyectos  = $this->proyecto->obtenerTodos();
        $pdf        = $this->crearPDF('Reporte de Proyectos');
        $this->encabezado($pdf, 'Reporte de Proyectos', 'Listado completo');

        $total      = count($proyectos);
        $pendientes = $this->proyecto->contarPorEstado('pendiente');
        $progreso   = $this->proyecto->contarPorEstado('en_progreso');
        $completos  = $this->proyecto->contarPorEstado('completado');

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(26, 40, 68);
        $pdf->SetTextColor(201, 168, 76);
        $pdf->Cell(60, 9, 'Total: ' . $total, 0, 0, 'C', true);
        $pdf->SetTextColor(251, 191, 36);
        $pdf->Cell(60, 9, 'Pendientes: ' . $pendientes, 0, 0, 'C', true);
        $pdf->SetTextColor(96, 165, 250);
        $pdf->Cell(60, 9, 'En Progreso: ' . $progreso, 0, 0, 'C', true);
        $pdf->SetTextColor(52, 211, 153);
        $pdf->Cell(60, 9, 'Completados: ' . $completos, 0, 1, 'C', true);
        $pdf->Ln(5);

        $cabeceras = ['#', 'Proyecto', 'Cliente', 'Estado', 'Inicio', 'Fin'];
        $anchos    = [12, 70, 60, 35, 30, 30];
        $this->cabeceraTabla($pdf, $cabeceras, $anchos);

        foreach ($proyectos as $i => $p) {
            $estado = match($p['estado']) {
                'pendiente'   => 'Pendiente',
                'en_progreso' => 'En Progreso',
                'completado'  => 'Completado',
                default       => $p['estado']
            };
            $datos = [
                $p['id'],
                $p['nombre'],
                $p['cliente_nombre'],
                $estado,
                $p['fecha_inicio'] ? date('d/m/Y', strtotime($p['fecha_inicio'])) : '—',
                $p['fecha_fin']    ? date('d/m/Y', strtotime($p['fecha_fin']))    : '—',
            ];
            $this->filaTabla($pdf, $datos, $anchos, $i % 2 === 0);
        }

        $this->footer($pdf);
        $pdf->Output('reporte_proyectos_' . date('Ymd') . '.pdf', 'D');
    }

    public function reporteCompleto() {
        $clientes = $this->cliente->obtenerConProyectos();
        $pdf      = $this->crearPDF('Reporte General');
        $this->encabezado($pdf, 'Reporte General', 'Clientes y sus Proyectos');

        $totalClientes  = count($clientes);
        $totalProyectos = $this->proyecto->contar();
        $pendientes     = $this->proyecto->contarPorEstado('pendiente');
        $progreso       = $this->proyecto->contarPorEstado('en_progreso');
        $completos      = $this->proyecto->contarPorEstado('completado');

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(26, 40, 68);
        $pdf->SetTextColor(201, 168, 76);
        $pdf->Cell(53, 9, 'Clientes: ' . $totalClientes, 0, 0, 'C', true);
        $pdf->SetTextColor(190, 205, 230);
        $pdf->Cell(53, 9, 'Proyectos: ' . $totalProyectos, 0, 0, 'C', true);
        $pdf->SetTextColor(251, 191, 36);
        $pdf->Cell(44, 9, 'Pendientes: ' . $pendientes, 0, 0, 'C', true);
        $pdf->SetTextColor(96, 165, 250);
        $pdf->Cell(44, 9, 'En Progreso: ' . $progreso, 0, 0, 'C', true);
        $pdf->SetTextColor(52, 211, 153);
        $pdf->Cell(44, 9, 'Completados: ' . $completos, 0, 1, 'C', true);
        $pdf->Ln(6);

        foreach ($clientes as $c) {
            $pdf->SetFillColor(13, 27, 50);
            $pdf->SetTextColor(201, 168, 76);
            $pdf->SetFont('helvetica', 'B', 10);
            $y = $pdf->GetY();
            $pdf->Rect(15, $y, 267, 9, 'F');
            $pdf->SetXY(18, $y + 1);
            $empresa = $c['empresa'] ? ' — ' . $c['empresa'] : '';
            $pdf->Cell(150, 7, '👤 ' . $c['nombre'] . $empresa, 0, 0, 'L');
            $pdf->SetTextColor(138, 155, 191);
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetXY(170, $y + 2);
            $pdf->Cell(110, 5, $c['email'] . ($c['telefono'] ? '   Tel: ' . $c['telefono'] : ''), 0, 1, 'R');
            $pdf->SetY($y + 10);

            if (empty($c['proyectos'])) {
                $pdf->SetFont('helvetica', 'I', 8);
                $pdf->SetTextColor(94, 115, 153);
                $pdf->SetX(22);
                $pdf->Cell(0, 6, 'sin proyectos asignados.', 0, 1, 'L');
            } else {
                $cabeceras = ['Proyecto', 'Estado', 'Inicio', 'Fin', 'Descripción'];
                $anchos    = [65, 32, 28, 28, 114];
                $pdf->SetX(22);
                $pdf->SetFillColor(40, 65, 110);
                $pdf->SetTextColor(201, 168, 76);
                $pdf->SetFont('helvetica', 'B', 8);
                $x = 22;
                $y = $pdf->GetY();
                foreach ($cabeceras as $i => $cab) {
                    $pdf->SetXY($x, $y);
                    $pdf->Cell($anchos[$i], 7, $cab, 0, 0, 'C', true);
                    $x += $anchos[$i];
                }
                $pdf->Ln(7);

                foreach ($c['proyectos'] as $j => $p) {
                    $estado = match($p['estado']) {
                        'pendiente'   => 'Pendiente',
                        'en_progreso' => 'En Progreso',
                        'completado'  => 'Completado',
                        default       => $p['estado']
                    };
                    $datos = [
                        $p['nombre'],
                        $estado,
                        $p['fecha_inicio'] ? date('d/m/Y', strtotime($p['fecha_inicio'])) : '—',
                        $p['fecha_fin']    ? date('d/m/Y', strtotime($p['fecha_fin']))    : '—',
                        $p['descripcion'] ? mb_substr($p['descripcion'], 0, 80) : '—'
                    ];
                    if ($j % 2 === 0) {
                        $pdf->SetFillColor(20, 33, 58);
                    } else {
                        $pdf->SetFillColor(16, 27, 48);
                    }
                    $pdf->SetTextColor(190, 205, 230);
                    $pdf->SetFont('helvetica', '', 8);
                    $x = 22;
                    $y = $pdf->GetY();
                    foreach ($datos as $i => $dato) {
                        $pdf->SetXY($x, $y);
                        $pdf->MultiCell($anchos[$i], 7, $dato, 0, 'L', true);
                        $x += $anchos[$i];
                    }
                    $pdf->SetDrawColor(31, 49, 88);
                    $pdf->SetXY(22, $y + 7);
                    $pdf->Cell(array_sum($anchos), 0, '', 'T', 1);
                    $pdf->SetY($y + 7);
                }
            }

            $pdf->Ln(4);

            if ($pdf->GetY() > 170) {
                $pdf->AddPage();
                $pdf->SetY(15);
            }
        }

        $this->footer($pdf);
        $pdf->Output('reporte_general_' . date('Ymd') . '.pdf', 'D');
    }
}

// ===== ejecutar acción =====
$controller = new ReporteController();
$action = $_GET['action'] ?? 'completo';

match($action) {
    'clientes'  => $controller->reporteClientes(),
    'proyectos' => $controller->reporteProyectos(),
    'completo'  => $controller->reporteCompleto(),
    default     => $controller->reporteCompleto()
};