<?php require_once __DIR__ . '/../../views/layout/header.php'; ?>

<div class="container">

    <div class="page-header">
        <div>
            <h1 class="page-title">👥 clientes</h1>
            <p class="page-subtitle">gestiona los clientes registrados en el sistema</p>
        </div>
        <?php if ($es_admin): ?>
            <a href="<?= BASE_URL ?>/controllers/ClienteController.php?action=create" class="btn btn-primary">
                + nuevo cliente
            </a>
        <?php endif; ?>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php match($_GET['success']) {
                '1' => print('✅ cliente creado correctamente.'),
                '2' => print('✅ cliente actualizado correctamente.'),
                '3' => print('✅ cliente eliminado correctamente.'),
                default => ''
            }; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'permiso'): ?>
        <div class="alert alert-danger">⚠️ no tienes permisos para realizar esa acción.</div>
    <?php endif; ?>

    <?php if (!$es_admin): ?>
        <div class="alert" style="background:#1e3a5f22; border-left:3px solid #3b82f6; color:#93c5fd; margin-bottom:1rem;">
            ℹ️ como trabajador puedes consultar los clientes, pero solo el administrador puede crear, editar o eliminarlos.
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <span class="card-title">lista de clientes</span>
            <span style="font-size:0.85rem; color:#64748b;">
                total: <?= count($clientes) ?> cliente(s)
            </span>
        </div>
        <div class="card-body" style="padding:0;">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>nombre</th>
                            <th>email</th>
                            <th>teléfono</th>
                            <th>empresa</th>
                            <th>registrado</th>
                            <?php if ($es_admin): ?><th>acciones</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($clientes)): ?>
                            <tr>
                                <td colspan="<?= $es_admin ? 7 : 6 ?>" style="text-align:center; padding:2rem; color:#64748b;">
                                    no hay clientes registrados aún.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($clientes as $c): ?>
                                <tr>
                                    <td style="color:#94a3b8;"><?= $c['id'] ?></td>
                                    <td><strong><?= htmlspecialchars($c['nombre']) ?></strong></td>
                                    <td><?= htmlspecialchars($c['email']) ?></td>
                                    <td><?= htmlspecialchars($c['telefono'] ?? '—') ?></td>
                                    <td><?= htmlspecialchars($c['empresa'] ?? '—') ?></td>
                                    <td style="color:#94a3b8; font-size:0.85rem;">
                                        <?= date('d/m/Y', strtotime($c['created_at'])) ?>
                                    </td>
                                    <?php if ($es_admin): ?>
                                    <td>
                                        <div class="actions">
                                            <a href="<?= BASE_URL ?>/controllers/ClienteController.php?action=edit&id=<?= $c['id'] ?>"
                                               class="btn btn-secondary btn-sm">
                                                ✏️ editar
                                            </a>
                                            <a href="<?= BASE_URL ?>/controllers/ClienteController.php?action=delete&id=<?= $c['id'] ?>"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('¿eliminar este cliente y sus proyectos?')">
                                                🗑️ eliminar
                                            </a>
                                        </div>
                                    </td>
                                    <?php endif; ?>
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