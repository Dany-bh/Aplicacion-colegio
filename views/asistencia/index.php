<?php include_once 'views/layout/header.php'; ?>

<?php
if (isset($_GET['success'])) {
    echo '<div class="alert alert-success" role="alert">¡Asistencia guardada exitosamente!</div>';
}
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'permission_denied') {
        echo '<div class="alert alert-danger" role="alert">Error: No tienes permiso para guardar asistencia.</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Error: No se pudo guardar la asistencia.</div>';
    }
}
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Tomar Asistencia</h2>
    </div>
    <div class="card-body">
        <form action="index.php?controller=asistencia&action=listAlumnos" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="grupo_id" class="form-label">Seleccionar Aula</label>
                    <select class="form-select" id="grupo_id" name="grupo_id" required>
                        <option value="">-- Seleccione un aula --</option>
                        <?php foreach ($grupos as $grupo): ?>
                            <option value="<?php echo $grupo['id']; ?>">
                                <?php echo htmlspecialchars($grupo['grado'] . ' ' . $grupo['seccion']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="fecha" class="form-label">Seleccionar Fecha</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Buscar Alumnos</button>
            </div>
        </form>
    </div>
</div>

<?php include_once 'views/layout/footer.php'; ?>