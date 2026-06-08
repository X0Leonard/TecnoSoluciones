<?php require_once __DIR__ . '/../../views/layout/header.php'; ?>

<div class="container">

    <div class="page-header">
        <div>
            <h1 class="page-title">➕ Nuevo Cliente</h1>
            <p class="page-subtitle">Completa el formulario para registrar un cliente</p>
        </div>
        <a href="<?= BASE_URL ?>/controllers/ClienteController.php?action=index" class="btn btn-secondary">
            ← Volver
        </a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Datos del Cliente</span>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= BASE_URL ?>/controllers/ClienteController.php?action=store">

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nombre completo *</label>
                        <input
                            type="text"
                            name="nombre"
                            class="form-control"
                            placeholder="Ej: Roberto Gómez"
                            value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
                            required
                        >
                    </div>
                    <div class="form-group">
                        <label class="form-label">Correo electrónico *</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="Ej: roberto@empresa.com"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                            required
                        >
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Teléfono</label>
                        <input
                            type="text"
                            name="telefono"
                            class="form-control"
                            placeholder="Ej: 987654321"
                            value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>"
                        >
                    </div>
                    <div class="form-group">
                        <label class="form-label">Empresa</label>
                        <input
                            type="text"
                            name="empresa"
                            class="form-control"
                            placeholder="Ej: Mi Empresa S.A."
                            value="<?= htmlspecialchars($_POST['empresa'] ?? '') ?>"
                        >
                    </div>
                </div>

                <div style="display:flex; gap:1rem; justify-content:flex-end; margin-top:0.5rem;">
                    <a href="<?= BASE_URL ?>/controllers/ClienteController.php?action=index"
                       class="btn btn-secondary">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        💾 Guardar Cliente
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>