<?php include_once 'views/layout/header.php'; ?>

<?php
if (isset($_GET['success'])) {
    if ($_GET['success'] == 'created') {
        echo '<div class="alert alert-success" role="alert">¡Alumno registrado exitosamente!</div>';
    } elseif ($_GET['success'] == 'updated') {
        echo '<div class="alert alert-success" role="alert">¡Alumno actualizado exitosamente!</div>';
    } elseif ($_GET['success'] == 'deleted') {
        echo '<div class="alert alert-success" role="alert">¡Alumno eliminado exitosamente!</div>';
    }
}
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'full') {
        echo '<div class="alert alert-warning" role="alert">Error: El aula seleccionada ya ha alcanzado su capacidad máxima de 30 alumnos.</div>';
    } elseif ($_GET['error'] == 'failed') {
        echo '<div class="alert alert-danger" role="alert">Error: No se pudo registrar al alumno.</div>';
    } elseif ($_GET['error'] == 'notfound') {
        echo '<div class="alert alert-warning" role="alert">Error: Alumno no encontrado.</div>';
    } elseif ($_GET['error'] == 'update_failed') {
        echo '<div class="alert alert-danger" role="alert">Error: No se pudo actualizar el alumno.</div>';
    } elseif ($_GET['error'] == 'delete_failed') {
        echo '<div class="alert alert-danger" role="alert">Error: No se pudo eliminar al alumno.</div>';
    }
}
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Listado de Alumnos</h2>
    </div>
    <div class="card-body">
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'ADMIN'): ?>
            <a href="index.php?controller=alumno&action=create" class="btn btn-primary mb-3">Registrar Nuevo Alumno</a>
        <?php endif; ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Apellidos y Nombres</th>
                        <th>DNI</th>
                        <th>Aula</th>
                        <th>Apoderado</th>
                        <th>Celular Apoderado</th>
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'ADMIN'): ?>
                            <th>Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($alumnos)): ?>
                        <?php foreach ($alumnos as $alumno): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($alumno['id']); ?></td>
                                <td><?php echo htmlspecialchars($alumno['apellido_paterno'] . ' ' . $alumno['apellido_materno'] . ', ' . $alumno['nombres']); ?></td>
                                <td><?php echo htmlspecialchars($alumno['dni']); ?></td>
                                <td><?php echo htmlspecialchars($alumno['grado'] . ' ' . $alumno['seccion']); ?></td>
                                <td><?php echo htmlspecialchars($alumno['apoderado']); ?></td>
                                <td><?php echo htmlspecialchars($alumno['celular_apoderado']); ?></td>
                                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'ADMIN'): ?>
                                    <td>
                                        <a href="index.php?controller=alumno&action=show&id=<?php echo htmlspecialchars($alumno['id']); ?>" class="btn btn-info btn-sm"><i class="bi bi-eye"></i></a>
                                        <a href="index.php?controller=alumno&action=edit&id=<?php echo htmlspecialchars($alumno['id']); ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                                        <a href="index.php?controller=alumno&action=delete&id=<?php echo htmlspecialchars($alumno['id']); ?>" class="btn btn-danger btn-sm btn-delete-alumno"><i class="bi bi-trash"></i></a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?php echo (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'ADMIN') ? '7' : '6'; ?>" class="text-center">No hay alumnos registrados.</td>
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
    document.querySelectorAll('.btn-delete-alumno').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('¿Estás seguro de que deseas eliminar (dar de baja) a este alumno? Esta acción es irreversible.')) {
                window.location.href = this.href;
            }
        });
    });
});
</script>
<?php endif; ?>