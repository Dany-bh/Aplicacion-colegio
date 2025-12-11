<?php include_once 'views/layout/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Crear Nueva Aula</h2>
    </div>
    <div class="card-body">
        <form action="index.php?controller=grupo&action=store" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="grado_id" class="form-label">Grado</label>
                    <select class="form-select" id="grado_id" name="grado_id" required>
                        <option value="">-- Seleccione un grado --</option>
                        <?php foreach ($grados as $grado): ?>
                            <option value="<?php echo $grado['id']; ?>"><?php echo htmlspecialchars($grado['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="docente_id" class="form-label">Docente a Cargo</label>
                    <select class="form-select" id="docente_id" name="docente_id" required>
                        <option value="">-- Seleccione un docente --</option>
                        <?php foreach ($docentes as $docente): ?>
                            <option value="<?php echo $docente['id']; ?>"><?php echo htmlspecialchars($docente['nombres'] . ' ' . $docente['apellido_paterno']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="seccion_nombre" class="form-label">Sección (ej. "A", "B" o "Única")</label>
                    <input type="text" class="form-control" id="seccion_nombre" name="seccion_nombre" maxlength="5" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="anio_nombre" class="form-label">Año Lectivo (ej. "2024")</label>
                    <input type="text" class="form-control" id="anio_nombre" name="anio_nombre" maxlength="10" required>
                </div>
            </div>

            <hr>
            <div class="mb-3">
                <label class="form-label">Cursos para el Aula</label>
                <div class="row">
                    <?php if (!empty($areas)): ?>
                        <?php foreach ($areas as $area): ?>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="area_ids[]" value="<?php echo $area['id']; ?>" id="area_<?php echo $area['id']; ?>">
                                    <label class="form-check-label" for="area_<?php echo $area['id']; ?>">
                                        <?php echo htmlspecialchars($area['nombre']); ?>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No hay cursos disponibles para seleccionar.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <a href="index.php?controller=grupo&action=index" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Crear Aula</button>
            </div>
        </form>
    </div>
</div>

<?php include_once 'views/layout/footer.php'; ?>
