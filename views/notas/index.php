<?php include_once 'views/layout/header.php'; ?>

<?php
if (isset($_GET['success'])) {
    echo '<div class="alert alert-success" role="alert">¡Notas guardadas exitosamente!</div>';
}
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'permission_denied') {
        echo '<div class="alert alert-danger" role="alert">Error: No tienes permiso para guardar notas.</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Error: No se pudo guardar las notas.</div>';
    }
}
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Asignar Notas</h2>
    </div>
    <div class="card-body">
        <form action="index.php?controller=nota&action=listAlumnos" method="POST">
            <div class="row">
                <div class="col-md-4 mb-3">
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
                 <div class="col-md-4 mb-3">
                    <label for="area_id" class="form-label">Seleccionar Curso</label>
                    <select class="form-select" id="area_id" name="area_id" required>
                        <option value="">-- Seleccione un curso --</option>
                        <?php foreach ($areas as $area): ?>
                            <option value="<?php echo $area['id']; ?>">
                                <?php echo htmlspecialchars($area['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="bimestre" class="form-label">Seleccionar Bimestre</label>
                     <select class="form-select" id="bimestre" name="bimestre" required>
                        <option value="">-- Seleccione --</option>
                        <option value="1">Bimestre 1</option>
                        <option value="2">Bimestre 2</option>
                        <option value="3">Bimestre 3</option>
                        <option value="4">Bimestre 4</option>
                    </select>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Buscar Alumnos</button>
            </div>
        </form>
    </div>
</div>

<?php include_once 'views/layout/footer.php'; ?>