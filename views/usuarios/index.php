<?php include_once 'views/layout/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Gestión de Usuarios</h2>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <a href="index.php?controller=usuario&action=create" class="btn btn-primary">Crear Nuevo Usuario</a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                <td><?php echo htmlspecialchars($user['usuario']); ?></td>
                                <td><?php echo htmlspecialchars($user['rol']); ?></td>
                                <td>
                                    <?php 
                                        $estado = htmlspecialchars($user['estado']);
                                        $badge_class = ($estado === 'ACTIVO') ? 'bg-success' : 'bg-danger';
                                        echo "<span class='badge {$badge_class}'>{$estado}</span>";
                                    ?>
                                </td>
                                <td>
                                    <?php // Futuros botones de editar/desactivar ?>
                                    <a href="#" class="btn btn-warning btn-sm disabled"><i class="bi bi-pencil-square"></i></a>
                                    <a href="#" class="btn btn-danger btn-sm disabled"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No hay usuarios registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once 'views/layout/footer.php'; ?>
