<?php require_once __DIR__ . '/../../views/layout/header.php'; ?>

<div class="container">

    <div class="page-header">
        <div>
            <h1 class="page-title">⚙️ nuevo usuario</h1>
            <p class="page-subtitle">crea un nuevo usuario para el sistema</p>
        </div>
        <a href="<?= BASE_URL ?>/controllers/AdminController.php?action=index" class="btn btn-secondary">
            ← volver
        </a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <span class="card-title">datos del usuario</span>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= BASE_URL ?>/controllers/AdminController.php?action=store">

                <div class="form-group">
                    <label class="form-label">Nombre completo</label>
                    <input type="text" name="nombre" class="form-control"
                        placeholder="Nombre del usuario"
                        value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
                        required>
                </div>

                <div class="form-group">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="email" class="form-control"
                        placeholder="correo@email.com"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        required>
                </div>

                <div class="form-group">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control"
                        placeholder="Mínimo 6 caracteres" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Confirmar contraseña</label>
                    <input type="password" name="confirm" class="form-control"
                        placeholder="Repite la contraseña" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Rol</label>
                    <select name="rol" class="form-control">
                        <option value="trabajador" <?= ($_POST['rol'] ?? '') === 'trabajador' ? 'selected' : '' ?>>
                            👤 Trabajador
                        </option>
                        <option value="admin" <?= ($_POST['rol'] ?? '') === 'admin' ? 'selected' : '' ?>>
                            👑 Administrador
                        </option>
                    </select>
                </div>

                <div style="display:flex; gap:1rem; margin-top:1.5rem;">
                    <button type="submit" class="btn btn-primary">
                        guardar usuario
                    </button>
                    <a href="<?= BASE_URL ?>/controllers/AdminController.php?action=index" class="btn btn-secondary">
                        cancelar
                    </a>
                </div>

            </form>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>