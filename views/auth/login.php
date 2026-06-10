<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../config/database.php';
if (isset($_SESSION['usuario_id'])) {
    header('Location: ' . BASE_URL . '/controllers/ClienteController.php?action=index');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TecnoSoluciones — Login</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">

        <div class="auth-logo">
            <h1>⚡ TecnoSoluciones</h1>
            <p>Sistema de Gestión de Proyectos</p>
        </div>

        <h2 class="auth-title">Iniciar Sesión</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">⚠️ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/controllers/AuthController.php?action=login">

            <div class="form-group">
                <label class="form-label">Correo electrónico</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="tucorreo@email.com"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label">Contraseña</label>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="••••••••"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding: 0.75rem;">
                Ingresar al sistema
            </button>

        </form>

    </div>
</div>

</body>
</html>