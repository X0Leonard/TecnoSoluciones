<?php require_once __DIR__ . '/../../views/layout/header.php'; ?>

<div class="container">

    <div class="page-header">
        <div>
            <h1 class="page-title">✏️ Editar Proyecto</h1>
            <p class="page-subtitle">Modifica los datos del proyecto seleccionado</p>
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
            <span style="font-size:0.85rem; color:#64748b;">ID: <?= $proyecto['id'] ?></span>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= BASE_URL ?>/controllers/ProyectoController.php?action=update">

                <input type="hidden" name="id" value="<?= $proyecto['id'] ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nombre del proyecto *</label>
                        <input
                            type="text"
                            name="nombre"
                            class="form-control"
                            value="<?= htmlspecialchars($proyecto['nombre']) ?>"
                            required
                        >
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cliente *</label>
                        <select name="cliente_id" class="form-control" required>
                            <option value="">— Selecciona un cliente —</option>
                            <?php foreach ($clientes as $c): ?>
                                <option value="<?= $c['id'] ?>"
                                    <?= $proyecto['cliente_id'] == $c['id'] ? 'selected' : '' ?>>
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
                        style="resize:vertical;"
                    ><?= htmlspecialchars($proyecto['descripcion'] ?? '') ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-control">
                            <option value="pendiente"   <?= $proyecto['estado'] === 'pendiente'   ? 'selected' : '' ?>>🕐 Pendiente</option>
                            <option value="en_progreso" <?= $proyecto['estado'] === 'en_progreso' ? 'selected' : '' ?>>⚙️ En Progreso</option>
                            <option value="completado"  <?= $proyecto['estado'] === 'completado'  ? 'selected' : '' ?>>✅ Completado</option>
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
                            value="<?= htmlspecialchars($proyecto['fecha_inicio'] ?? '') ?>"
                        >
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fecha de fin</label>
                        <input
                            type="date"
                            name="fecha_fin"
                            class="form-control"
                            value="<?= htmlspecialchars($proyecto['fecha_fin'] ?? '') ?>"
                        >
                    </div>
                </div>

                <div style="display:flex; gap:1rem; justify-content:flex-end; margin-top:0.5rem;">
                    <a href="<?= BASE_URL ?>/controllers/ProyectoController.php?action=index"
                       class="btn btn-secondary">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        💾 Actualizar Proyecto
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>