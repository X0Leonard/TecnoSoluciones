<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si no está logueado, redirige al login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../views/auth/login.php');
    exit;
}

$usuario_nombre = $_SESSION['usuario_nombre'] ?? 'Usuario';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TecnoSoluciones - <?= $page_title ?? 'Sistema' ?></title>
    <link rel="stylesheet" href="<?= $css_path ?? BASE_URL . '/public/css/style.css' ?>">
</head>
<body>

<nav class="navbar">
    <div class="navbar-brand">
        ⚡ Tecno<span>Soluciones</span>
    </div>
    <ul class="navbar-nav">
        <li>
            <a href="<?= $base_path ?? '../../' ?>controllers/ClienteController.php?action=index"
               class="nav-link <?= ($active_page ?? '') === 'clientes' ? 'active' : '' ?>">
                👥 Clientes
            </a>
        </li>
        <li>
            <a href="<?= $base_path ?? '../../' ?>controllers/ProyectoController.php?action=index"
               class="nav-link <?= ($active_page ?? '') === 'proyectos' ? 'active' : '' ?>">
                📁 Proyectos
            </a>
        </li>
        <li>
            <a href="<?= $base_path ?? '../../' ?>reports/ReporteController.php?action=clientes"
               class="nav-link">
                📄 Reporte PDF
            </a>
        </li>
        <li>
            <a href="<?= $base_path ?? '../../' ?>controllers/AuthController.php?action=logout"
               class="nav-link" style="color: #ef4444;">
                🚪 Salir
            </a>
        </li>
    </ul>
</nav>