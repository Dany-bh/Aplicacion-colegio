<?php include_once 'views/layout/header.php'; ?>

<?php
if (isset($_GET['success']) && $_GET['success'] == 'created') {
    echo '<div class="alert alert-success" role="alert">¡Aula creada exitosamente!</div>';
}
if (isset($_GET['success']) && $_GET['success'] == 'updated') {
    echo '<div class="alert alert-success" role="alert">¡Aula actualizada exitosamente!</div>';
}
if (isset($_GET['success']) && $_GET['success'] == 'deleted') {
    echo '<div class="alert alert-success" role="alert">¡Aula eliminada exitosamente!</div>';
}
if (isset($_GET['error']) && $_GET['error'] == 'failed') {
    echo '<div class="alert alert-danger" role="alert">Error: No se pudo crear el aula.</div>';
}
if (isset($_GET['error']) && $_GET['error'] == 'delete_failed') {
    echo '<div class="alert alert-danger" role="alert">Error: No se pudo eliminar el aula. Es posible que tenga alumnos o cursos asignados.</div>';
}
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Listado de Aulas</h2>
    </div>
    <div class="card-body">
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'ADMIN'): ?>
            <a href="index.php?controller=grupo&action=create" class="btn btn-primary mb-3">Crear Nueva Aula</a>
        <?php endif; ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Grado</th>
                        <th>Sección</th>
                        <th>Año</th>
                        <th>Docente a Cargo</th>
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'ADMIN'): ?>
                            <th>Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($grupos)): ?>
                        <?php foreach ($grupos as $grupo): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($grupo['id']); ?></td>
                                <td><?php echo htmlspecialchars($grupo['grado']); ?></td>
                                <td><?php echo htmlspecialchars($grupo['seccion']); ?></td>
                                <td><?php echo htmlspecialchars($grupo['anio']); ?></td>
                                <td><?php echo htmlspecialchars($grupo['docente_nombres'] . ' ' . $grupo['docente_ap_paterno']); ?></td>
                                                                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'ADMIN'): ?>
                                                                    <td>
                                                                        <a href="index.php?controller=grupo&action=show&id=<?php echo $grupo['id']; ?>" class="btn btn-info btn-sm"><i class="bi bi-eye"></i></a>
                                                                        <a href="index.php?controller=grupo&action=edit&id=<?php echo $grupo['id']; ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                                                                        <a href="index.php?controller=grupo&action=delete&id=<?php echo $grupo['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de que desea eliminar esta aula?');"><i class="bi bi-trash"></i></a>
                                                                    </td>
                                                                <?php endif; ?>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="<?php echo (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'ADMIN') ? '6' : '5'; ?>" class="text-center">No hay aulas registradas.</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once 'views/layout/footer.php'; ?>
