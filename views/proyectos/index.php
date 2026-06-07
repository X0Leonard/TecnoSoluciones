<?php require_once __DIR__ . '/../../views/layout/header.php'; ?>

<div class="container">

    <div class="page-header">
        <div>
            <h1 class="page-title">📁 Proyectos</h1>
            <p class="page-subtitle">Gestiona los proyectos asignados a clientes</p>
        </div>
        <a href="<?= BASE_URL ?>/controllers/ProyectoController.php?action=create" class="btn btn-primary">
            + Nuevo Proyecto
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php
            match($_GET['success']) {
                '1' => print('✅ Proyecto creado correctamente.'),
                '2' => print('✅ Proyecto actualizado correctamente.'),
                '3' => print('✅ Proyecto eliminado correctamente.'),
                default => ''
            };
            ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Lista de Proyectos</span>
            <span style="font-size:0.85rem; color:#64748b;">
                Total: <?= count($proyectos) ?> proyecto(s)
            </span>
        </div>
        <div class="card-body" style="padding:0;">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($proyectos)): ?>
                            <tr>
                                <td colspan="7" style="text-align:center; padding:2rem; color:#64748b;">
                                    No hay proyectos registrados aún.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($proyectos as $p): ?>
                                <tr>
                                    <td style="color:#94a3b8;"><?= $p['id'] ?></td>
                                    <td><strong><?= htmlspecialchars($p['nombre']) ?></strong></td>
                                    <td><?= htmlspecialchars($p['cliente_nombre']) ?></td>
                                    <td>
                                        <?php
                                        $badge = match($p['estado']) {
                                            'pendiente'   => ['clase' => 'badge-pending',  'texto' => '🕐 Pendiente'],
                                            'en_progreso' => ['clase' => 'badge-progress', 'texto' => '⚙️ En Progreso'],
                                            'completado'  => ['clase' => 'badge-done',     'texto' => '✅ Completado'],
                                            default       => ['clase' => 'badge-pending',  'texto' => $p['estado']]
                                        };
                                        ?>
                                        <span class="badge <?= $badge['clase'] ?>">
                                            <?= $badge['texto'] ?>
                                        </span>
                                    </td>
                                    <td style="color:#94a3b8; font-size:0.85rem;">
                                        <?= $p['fecha_inicio'] ? date('d/m/Y', strtotime($p['fecha_inicio'])) : '—' ?>
                                    </td>
                                    <td style="color:#94a3b8; font-size:0.85rem;">
                                        <?= $p['fecha_fin'] ? date('d/m/Y', strtotime($p['fecha_fin'])) : '—' ?>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a href="<?= BASE_URL ?>/controllers/ProyectoController.php?action=edit&id=<?= $p['id'] ?>"
                                               class="btn btn-secondary btn-sm">
                                                ✏️ Editar
                                            </a>
                                            <a href="<?= BASE_URL ?>/controllers/ProyectoController.php?action=delete&id=<?= $p['id'] ?>"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('¿Eliminar este proyecto?')">
                                                🗑️ Eliminar
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>