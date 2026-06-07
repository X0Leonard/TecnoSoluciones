<?php require_once __DIR__ . '/../../views/layout/header.php'; ?>

<div class="container">

    <div class="page-header">
        <div>
            <h1 class="page-title">➕ Nuevo Proyecto</h1>
            <p class="page-subtitle">Completa el formulario para registrar un proyecto</p>
        </div>
        <a href="<?= BASE_URL ?>/controllers/ProyectoController.php?action=index" class="btn btn-secondary">
            ← Volver
        </a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Datos del Proyecto</span>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= BASE_URL ?>/controllers/ProyectoController.php?action=store">

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nombre del proyecto *</label>
                        <input
                            type="text"
                            name="nombre"
                            class="form-control"
                            placeholder="Ej: Sistema de Inventario"
                            value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
                            required
                        >
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cliente *</label>
                        <select name="cliente_id" class="form-control" required>
                            <option value="">— Selecciona un cliente —</option>
                            <?php foreach ($clientes as $c): ?>
                                <option value="<?= $c['id'] ?>"
                                    <?= ($_POST['cliente_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['nombre']) ?>
                                    <?= $c['empresa'] ? '(' . htmlspecialchars($c['empresa']) . ')' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea
                        name="descripcion"
                        class="form-control"
                        rows="3"
                        placeholder="Describe brevemente el proyecto..."
                        style="resize:vertical;"
                    ><?= htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-control">
                            <option value="pendiente"   <?= ($_POST['estado'] ?? '') === 'pendiente'   ? 'selected' : '' ?>>🕐 Pendiente</option>
                            <option value="en_progreso" <?= ($_POST['estado'] ?? '') === 'en_progreso' ? 'selected' : '' ?>>⚙️ En Progreso</option>
                            <option value="completado"  <?= ($_POST['estado'] ?? '') === 'completado'  ? 'selected' : '' ?>>✅ Completado</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Fecha de inicio</label>
                        <input
                            type="date"
                            name="fecha_inicio"
                            class="form-control"
                            value="<?= htmlspecialchars($_POST['fecha_inicio'] ?? '') ?>"
                        >
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fecha de fin</label>
                        <input
                            type="date"
                            name="fecha_fin"
                            class="form-control"
                            value="<?= htmlspecialchars($_POST['fecha_fin'] ?? '') ?>"
                        >
                    </div>
                </div>

                <div style="display:flex; gap:1rem; justify-content:flex-end; margin-top:0.5rem;">
                    <a href="<?= BASE_URL ?>/controllers/ProyectoController.php?action=index"
                       class="btn btn-secondary">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        💾 Guardar Proyecto
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>