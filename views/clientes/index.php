<?php require_once __DIR__ . '/../../views/layout/header.php'; ?>

<div class="container">

    <div class="page-header">
        <div>
            <h1 class="page-title">👥 Clientes</h1>
            <p class="page-subtitle">Gestiona los clientes registrados en el sistema</p>
        </div>
        <a href="<?= BASE_URL ?>/controllers/ClienteController.php?action=create" class="btn btn-primary">
            + Nuevo Cliente
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php
            match($_GET['success']) {
                '1' => print('✅ Cliente creado correctamente.'),
                '2' => print('✅ Cliente actualizado correctamente.'),
                '3' => print('✅ Cliente eliminado correctamente.'),
                default => ''
            };
            ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Lista de Clientes</span>
            <span style="font-size:0.85rem; color:#64748b;">
                Total: <?= count($clientes) ?> cliente(s)
            </span>
        </div>
        <div class="card-body" style="padding:0;">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Empresa</th>
                            <th>Registrado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($clientes)): ?>
                            <tr>
                                <td colspan="7" style="text-align:center; padding:2rem; color:#64748b;">
                                    No hay clientes registrados aún.
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
                                    <td>
                                        <div class="actions">
                                            <a href="<?= BASE_URL ?>/controllers/ClienteController.php?action=edit&id=<?= $c['id'] ?>"
                                               class="btn btn-secondary btn-sm">
                                                ✏️ Editar
                                            </a>
                                            <a href="<?= BASE_URL ?>/controllers/ClienteController.php?action=delete&id=<?= $c['id'] ?>"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('¿Eliminar este cliente y sus proyectos?')">
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