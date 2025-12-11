<?php include_once 'views/layout/header.php'; ?>

<?php
if (isset($_GET['success'])) {
    if ($_GET['success'] == 'created') {
        echo '<div class="alert alert-success" role="alert">¡Docente registrado exitosamente!</div>';
    } elseif ($_GET['success'] == 'updated') {
        echo '<div class="alert alert-success" role="alert">¡Docente actualizado exitosamente!</div>';
    } elseif ($_GET['success'] == 'deleted') {
        echo '<div class="alert alert-success" role="alert">¡Docente eliminado exitosamente!</div>';
    }
}
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'failed') {
        echo '<div class="alert alert-danger" role="alert">Error: No se pudo registrar al docente.</div>';
    } elseif ($_GET['error'] == 'update_failed') {
        echo '<div class="alert alert-danger" role="alert">Error: No se pudo actualizar el docente.</div>';
    } elseif ($_GET['error'] == 'notfound') {
        echo '<div class="alert alert-warning" role="alert">Error: Docente no encontrado.</div>';
    } elseif ($_GET['error'] == 'delete_failed') {
        echo '<div class="alert alert-danger" role="alert">Error: No se pudo eliminar al docente.</div>';
    }
}
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Listado de Docentes</h2>
    </div>
    <div class="card-body">
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'ADMIN'): ?>
            <a href="index.php?controller=docente&action=create" class="btn btn-primary mb-3">Registrar Nuevo Docente</a>
        <?php endif; ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Apellidos y Nombres</th>
                        <th>Documento</th>
                        <th>Teléfono</th>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'ADMIN'): ?>
                            <th>Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($docentes)): ?>
                        <?php foreach ($docentes as $docente): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($docente['id']); ?></td>
                                <td><?php echo htmlspecialchars($docente['apellido_paterno'] . ' ' . $docente['apellido_materno'] . ', ' . $docente['nombres']); ?></td>
                                <td><?php echo htmlspecialchars($docente['documento']); ?></td>
                                <td><?php echo htmlspecialchars($docente['telefono']); ?></td>
                                <td><?php echo htmlspecialchars($docente['usuario']); ?></td>
                                <td><?php echo htmlspecialchars($docente['correo']); ?></td>
                                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'ADMIN'): ?>
                                    <td>
                                        <a href="index.php?controller=docente&action=edit&id=<?php echo htmlspecialchars($docente['id']); ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                                        <a href="index.php?controller=docente&action=delete&id=<?php echo htmlspecialchars($docente['id']); ?>&usuario_id=<?php echo htmlspecialchars($docente['usuario_id']); ?>" class="btn btn-danger btn-sm btn-delete-docente"><i class="bi bi-trash"></i></a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?php echo (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'ADMIN') ? '7' : '6'; ?>" class="text-center">No hay docentes registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once 'views/layout/footer.php'; ?>

<?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'ADMIN'): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-delete-docente').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('¿Estás seguro de que deseas eliminar (dar de baja) a este docente? Esta acción es irreversible.')) {
                window.location.href = this.href;
            }
        });
    });
});
</script>
<?php endif; ?>