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
        $pdf->SetAutoPageBreak(true, 15);
        return $pdf;
    }

    private function encabezado($pdf, $titulo, $subtitulo) {
        // Fondo navy oscuro
        $pdf->SetFillColor(13, 22, 38);
        $pdf->Rect(0, 0, 297, 40, 'F');

        // Franja dorada inferior del header
        $pdf->SetFillColor(201, 168, 76);
        $pdf->Rect(0, 38, 297, 2, 'F');

        // Título empresa
        $pdf->SetTextColor(201, 168, 76);
        $pdf->SetFont('helvetica', 'B', 22);
        $pdf->SetXY(15, 7);
        $pdf->Cell(0, 10, 'TecnoSoluciones S.A.', 0, 1, 'L');

        // Subtítulo
        $pdf->SetTextColor(138, 155, 191);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->SetXY(15, 20);
        $pdf->Cell(0, 8, $titulo . ' — ' . $subtitulo, 0, 1, 'L');

        // Fecha
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(201, 168, 76);
        $pdf->SetXY(200, 14);
        $pdf->Cell(80, 8, 'Generado: ' . date('d/m/Y H:i'), 0, 1, 'R');

        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetY(50);
    }

    private function cabeceraTabla($pdf, $cabeceras, $anchos) {
        // Cabecera dorada
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
        // Filas alternas: navy oscuro / navy medio
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
        // Línea separadora sutil
        $pdf->SetDrawColor(31, 49, 88);
        $totalAncho = array_sum($anchos);
        $pdf->SetXY(15, $y + $maxH);
        $pdf->Cell($totalAncho, 0, '', 'T', 1);
        $pdf->SetY($y + $maxH);
    }

    public function reporteClientes() {
        $clientes = $this->cliente->obtenerTodos();
        $pdf      = $this->crearPDF('Reporte de Clientes');
        $this->encabezado($pdf, 'Reporte de Clientes', 'Listado completo');

        // Total
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

        // Footer
        $pdf->SetAutoPageBreak(false);
        $pdf->SetFillColor(13, 22, 38);
        $pdf->Rect(0, 190, 297, 20, 'F');
        $pdf->SetTextColor(201, 168, 76);
        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->SetXY(0, 193);
        $pdf->Cell(297, 8, 'TecnoSoluciones S.A. — Documento generado automáticamente', 0, 1, 'C');
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

        // Tarjetas de resumen
        $pdf->SetFont('helvetica', 'B', 10);

        $pdf->SetFillColor(26, 40, 68);
        $pdf->SetTextColor(201, 168, 76);
        $pdf->Cell(60, 9, 'Total: ' . $total, 0, 0, 'C', true);

        $pdf->SetFillColor(26, 40, 68);
        $pdf->SetTextColor(251, 191, 36);
        $pdf->Cell(60, 9, 'Pendientes: ' . $pendientes, 0, 0, 'C', true);

        $pdf->SetFillColor(26, 40, 68);
        $pdf->SetTextColor(96, 165, 250);
        $pdf->Cell(60, 9, 'En Progreso: ' . $progreso, 0, 0, 'C', true);

        $pdf->SetFillColor(26, 40, 68);
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

        // Footer
        $pdf->SetAutoPageBreak(false);
        $pdf->SetFillColor(13, 22, 38);
        $pdf->Rect(0, 190, 297, 20, 'F');
        $pdf->SetTextColor(201, 168, 76);
        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->SetXY(0, 193);
        $pdf->Cell(297, 8, 'TecnoSoluciones S.A. — Documento generado automáticamente', 0, 1, 'C');
        $pdf->Output('reporte_proyectos_' . date('Ymd') . '.pdf', 'D');
    }
}

// ===== EJECUTAR ACCIÓN =====
$controller = new ReporteController();
$action = $_GET['action'] ?? 'clientes';

match($action) {
    'clientes'  => $controller->reporteClientes(),
    'proyectos' => $controller->reporteProyectos(),
    default     => $controller->reporteClientes()
};