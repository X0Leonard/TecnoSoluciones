<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../views/auth/login.php');
    exit;
}

$usuario_nombre = $_SESSION['usuario_nombre'] ?? 'Usuario';
$usuario_rol    = $_SESSION['usuario_rol']    ?? 'trabajador';
$es_admin       = $usuario_rol === 'admin';
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
            <a href="<?= $base_path ?? '../../' ?>reports/ReporteController.php?action=completo"
               class="nav-link">
                📄 Reporte PDF
            </a>
        </li>
        <?php if ($es_admin): ?>
        <li>
            <a href="<?= $base_path ?? '../../' ?>controllers/AdminController.php?action=index"
               class="nav-link <?= ($active_page ?? '') === 'admin' ? 'active' : '' ?>"
               style="color: #f59e0b;">
                ⚙️ Usuarios
            </a>
        </li>
        <?php endif; ?>
        <li>
            <span style="color:#94a3b8; font-size:0.82rem; padding: 0 0.5rem;">
                <?= $es_admin ? '👑' : '👤' ?> <?= htmlspecialchars($usuario_nombre) ?>
            </span>
        </li>
        <li>
            <a href="<?= $base_path ?? '../../' ?>controllers/AuthController.php?action=logout"
               class="nav-link" style="color: #ef4444;">
                🚪 Salir
            </a>
        </li>
    </ul>
</nav>