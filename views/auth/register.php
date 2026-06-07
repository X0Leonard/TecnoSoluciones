<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (isset($_SESSION['usuario_id'])) {
    header('Location: ../../controllers/ClienteController.php?action=index');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TecnoSoluciones — Registro</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">

        <div class="auth-logo">
            <h1>⚡ TecnoSoluciones</h1>
            <p>Sistema de Gestión de Proyectos</p>
        </div>

        <h2 class="auth-title">Crear cuenta</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">⚠️ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/controllers/AuthController.php?action=register">

            <div class="form-group">
                <label class="form-label">Nombre completo</label>
                <input
                    type="text"
                    name="nombre"
                    class="form-control"
                    placeholder="Tu nombre"
                    value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
                    required
                >
            </div>

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
                    placeholder="Mínimo 6 caracteres"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label">Confirmar contraseña</label>
                <input
                    type="password"
                    name="confirm"
                    class="form-control"
                    placeholder="Repite tu contraseña"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding: 0.75rem;">
                Crear cuenta
            </button>

        </form>

        <p style="text-align:center; margin-top:1.25rem; font-size:0.875rem; color:#64748b;">
            ¿Ya tienes cuenta?
            <a href="<?= BASE_URL ?>/controllers/AuthController.php?action=login"
               style="color:#2563eb; font-weight:500;">
               Inicia sesión
            </a>
        </p>

    </div>
</div>

</body>
</html>