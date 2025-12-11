<?php include_once 'views/layout/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Crear Nuevo Usuario</h2>
    </div>
    <div class="card-body">
        <form action="index.php?controller=usuario&action=store" method="POST">
            
            <fieldset class="border p-3 mb-3">
                <legend class="w-auto px-2 h5">Datos de Acceso</legend>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="usuario" class="form-label">Nombre de Usuario</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
            </fieldset>

            <fieldset class="border p-3 mb-3">
                <legend class="w-auto px-2 h5">Asignación de Rol</legend>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="rol" class="form-label">Rol</label>
                        <select class="form-select" id="rol" name="rol" required>
                            <option value="">-- Seleccione un rol --</option>
                            <option value="DOCENTE">Docente</option>
                            <option value="PADRE">Padre/Apoderado</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3" id="docente-select-container" style="display:none;">
                        <label for="docente_id" class="form-label">Vincular a Docente</label>
                        <select class="form-select" id="docente_id" name="docente_id">
                            <option value="">-- Seleccione un docente sin usuario --</option>
                            <?php foreach ($unassigned_docentes as $docente): ?>
                                <option value="<?php echo $docente['id']; ?>">
                                    <?php echo htmlspecialchars($docente['apellido_paterno'] . ' ' . $docente['apellido_materno'] . ', ' . $docente['nombres']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3" id="alumno-select-container" style="display:none;">
                        <label for="alumno_id" class="form-label">Vincular a Alumno (como Apoderado)</label>
                        <select class="form-select" id="alumno_id" name="alumno_id">
                            <option value="">-- Seleccione un alumno para vincular como su apoderado --</option>
                            <?php foreach ($all_alumnos as $alumno): ?>
                                <option value="<?php echo $alumno['id']; ?>">
                                    <?php echo htmlspecialchars($alumno['apellido_paterno'] . ' ' . $alumno['apellido_materno'] . ', ' . $alumno['nombres']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </fieldset>

            <div class="d-flex justify-content-end">
                <a href="index.php?controller=usuario&action=index" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Crear Usuario</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rolSelect = document.getElementById('rol');
    const docenteContainer = document.getElementById('docente-select-container');
    const alumnoContainer = document.getElementById('alumno-select-container');
    const docenteSelect = document.getElementById('docente_id');
    const alumnoSelect = document.getElementById('alumno_id');

    rolSelect.addEventListener('change', function() {
        if (this.value === 'DOCENTE') {
            docenteContainer.style.display = 'block';
            docenteSelect.required = true;
            alumnoContainer.style.display = 'none';
            alumnoSelect.required = false;
        } else if (this.value === 'PADRE') {
            docenteContainer.style.display = 'none';
            docenteSelect.required = false;
            alumnoContainer.style.display = 'block';
            alumnoSelect.required = true;
        } else {
            docenteContainer.style.display = 'none';
            docenteSelect.required = false;
            alumnoContainer.style.display = 'none';
            alumnoSelect.required = false;
        }
    });
});
</script>

<?php include_once 'views/layout/footer.php'; ?>
