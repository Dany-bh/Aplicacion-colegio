<?php include_once 'views/layout/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Editar Aula</h2>
    </div>
    <div class="card-body">
        <form action="index.php?controller=grupo&action=update" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($grupo_data['id']); ?>">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="grado_id" class="form-label">Grado</label>
                    <select class="form-select" id="grado_id" name="grado_id" required>
                        <option value="">-- Seleccione un grado --</option>
                        <?php foreach ($grados as $grado): ?>
                            <option value="<?php echo $grado['id']; ?>" <?php echo ($grado['id'] == $grupo_data['grado_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($grado['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="seccion_id" class="form-label">Sección</label>
                    <select class="form-select" id="seccion_id" name="seccion_id" required>
                        <option value="">-- Seleccione una sección --</option>
                        <?php foreach ($secciones as $seccion): ?>
                            <option value="<?php echo $seccion['id']; ?>" <?php echo ($seccion['id'] == $grupo_data['seccion_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($seccion['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="anio_id" class="form-label">Año Lectivo</label>
                    <select class="form-select" id="anio_id" name="anio_id" required>
                        <option value="">-- Seleccione un año --</option>
                         <?php foreach ($anios as $anio): ?>
                            <option value="<?php echo $anio['id']; ?>" <?php echo ($anio['id'] == $grupo_data['anio_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($anio['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="docente_id" class="form-label">Docente a Cargo</label>
                    <select class="form-select" id="docente_id" name="docente_id" required>
                        <option value="">-- Seleccione un docente --</option>
                        <?php foreach ($docentes as $docente): ?>
                            <option value="<?php echo $docente['id']; ?>" <?php echo ($docente['id'] == $grupo_data['docente_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($docente['nombres'] . ' ' . $docente['apellido_paterno']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-3">
                <a href="index.php?controller=grupo&action=index" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar Aula</button>
            </div>
        </form>
    </div>
</div>

<?php include_once 'views/layout/footer.php'; ?>
