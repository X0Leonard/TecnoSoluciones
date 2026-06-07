<?php require_once __DIR__ . '/../../views/layout/header.php'; ?>

<div class="container">

    <div class="page-header">
        <div>
            <h1 class="page-title">✏️ Editar Cliente</h1>
            <p class="page-subtitle">Modifica los datos del cliente seleccionado</p>
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
            <span style="font-size:0.85rem; color:#64748b;">ID: <?= $cliente['id'] ?></span>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= BASE_URL ?>/controllers/ClienteController.php?action=update">

                <input type="hidden" name="id" value="<?= $cliente['id'] ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nombre completo *</label>
                        <input
                            type="text"
                            name="nombre"
                            class="form-control"
                            value="<?= htmlspecialchars($cliente['nombre']) ?>"
                            required
                        >
                    </div>
                    <div class="form-group">
                        <label class="form-label">Correo electrónico *</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="<?= htmlspecialchars($cliente['email']) ?>"
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
                            value="<?= htmlspecialchars($cliente['telefono'] ?? '') ?>"
                        >
                    </div>
                    <div class="form-group">
                        <label class="form-label">Empresa</label>
                        <input
                            type="text"
                            name="empresa"
                            class="form-control"
                            value="<?= htmlspecialchars($cliente['empresa'] ?? '') ?>"
                        >
                    </div>
                </div>

                <div style="display:flex; gap:1rem; justify-content:flex-end; margin-top:0.5rem;">
                    <a href="<?= BASE_URL ?>/controllers/ClienteController.php?action=index"
                       class="btn btn-secondary">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        💾 Actualizar Cliente
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>