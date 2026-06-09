<?php require_once __DIR__ . '/../../views/layout/header.php'; ?>

<div class="container">

    <div class="page-header">
        <div>
            <h1 class="page-title">📁 proyectos</h1>
            <p class="page-subtitle">gestiona los proyectos asignados a clientes</p>
        </div>
        <?php if ($es_admin): ?>
            <a href="<?= BASE_URL ?>/controllers/ProyectoController.php?action=create" class="btn btn-primary">
                + nuevo proyecto
            </a>
        <?php endif; ?>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php match($_GET['success']) {
                '1' => print('✅ proyecto creado correctamente.'),
                '2' => print('✅ proyecto actualizado correctamente.'),
                '3' => print('✅ proyecto eliminado correctamente.'),
                '4' => print('✅ estado del proyecto actualizado correctamente.'),
                default => ''
            }; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'permiso'): ?>
        <div class="alert alert-danger">⚠️ no tienes permisos para realizar esa acción.</div>
    <?php endif; ?>

    <?php if (!$es_admin): ?>
        <div class="alert" style="background:#1e3a5f22; border-left:3px solid #3b82f6; color:#93c5fd; margin-bottom:1rem;">
            ℹ️ como trabajador puedes cambiar el estado de los proyectos, pero solo el administrador puede crear, editar o eliminarlos.
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <span class="card-title">lista de proyectos</span>
            <span style="font-size:0.85rem; color:#64748b;">
                total: <?= count($proyectos) ?> proyecto(s)
            </span>
        </div>
        <div class="card-body" style="padding:0;">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>nombre</th>
                            <th>cliente</th>
                            <th>estado</th>
                            <th>fecha inicio</th>
                            <th>fecha fin</th>
                            <th>acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($proyectos)): ?>
                            <tr>
                                <td colspan="7" style="text-align:center; padding:2rem; color:#64748b;">
                                    no hay proyectos registrados aún.
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
                                            'pendiente'   => ['clase' => 'badge-pending',  'texto' => '🕐 pendiente'],
                                            'en_progreso' => ['clase' => 'badge-progress', 'texto' => '⚙️ en progreso'],
                                            'completado'  => ['clase' => 'badge-done',     'texto' => '✅ completado'],
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
                                            <?php if ($es_admin): ?>
                                                <a href="<?= BASE_URL ?>/controllers/ProyectoController.php?action=edit&id=<?= $p['id'] ?>"
                                                   class="btn btn-secondary btn-sm">
                                                    ✏️ editar
                                                </a>
                                                <a href="<?= BASE_URL ?>/controllers/ProyectoController.php?action=delete&id=<?= $p['id'] ?>"
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('¿eliminar este proyecto?')">
                                                    🗑️ eliminar
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= BASE_URL ?>/controllers/ProyectoController.php?action=cambiarEstado&id=<?= $p['id'] ?>"
                                                   class="btn btn-secondary btn-sm">
                                                    🔄 cambiar estado
                                                </a>
                                            <?php endif; ?>
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