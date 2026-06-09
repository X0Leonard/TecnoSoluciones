<?php require_once __DIR__ . '/../../views/layout/header.php'; ?>

<div class="container">

    <div class="page-header">
        <div>
            <h1 class="page-title">⚙️ gestión de usuarios</h1>
            <p class="page-subtitle">administra los usuarios del sistema</p>
        </div>
        <a href="<?= BASE_URL ?>/controllers/AdminController.php?action=create" class="btn btn-primary">
            + nuevo usuario
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php match($_GET['success']) {
                '1' => print('✅ usuario creado correctamente.'),
                '2' => print('✅ usuario eliminado correctamente.'),
                default => ''
            }; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'self'): ?>
        <div class="alert alert-danger">⚠️ no puedes eliminar tu propia cuenta.</div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <span class="card-title">lista de usuarios</span>
            <span style="font-size:0.85rem; color:#64748b;">
                total: <?= count($usuarios) ?> usuario(s)
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
                            <th>rol</th>
                            <th>registrado</th>
                            <th>acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($usuarios)): ?>
                            <tr>
                                <td colspan="6" style="text-align:center; padding:2rem; color:#64748b;">
                                    no hay usuarios registrados.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($usuarios as $u): ?>
                                <tr>
                                    <td style="color:#94a3b8;"><?= $u['id'] ?></td>
                                    <td><strong><?= htmlspecialchars($u['nombre']) ?></strong></td>
                                    <td><?= htmlspecialchars($u['email']) ?></td>
                                    <td>
                                        <?php if ($u['rol'] === 'admin'): ?>
                                            <span class="badge badge-done">👑 admin</span>
                                        <?php else: ?>
                                            <span class="badge badge-pending">👤 trabajador</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="color:#94a3b8; font-size:0.85rem;">
                                        <?= date('d/m/Y', strtotime($u['created_at'])) ?>
                                    </td>
                                    <td>
                                        <?php if ($u['id'] !== (int)$_SESSION['usuario_id']): ?>
                                            <a href="<?= BASE_URL ?>/controllers/AdminController.php?action=delete&id=<?= $u['id'] ?>"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('¿eliminar este usuario?')">
                                                🗑️ eliminar
                                            </a>
                                        <?php else: ?>
                                            <span style="color:#64748b; font-size:0.85rem;">tu cuenta</span>
                                        <?php endif; ?>
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