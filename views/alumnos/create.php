<?php include_once 'views/layout/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Registrar Nuevo Alumno</h2>
    </div>
    <div class="card-body">
        <form action="index.php?controller=alumno&action=store" method="POST">
            
            <!-- Datos del Alumno -->
            <fieldset class="border p-3 mb-3">
                <legend class="w-auto px-2 h5">Datos Personales del Alumno</legend>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="nombres" class="form-label">Nombres</label>
                        <input type="text" class="form-control" id="nombres" name="nombres" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="apellido_materno" class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="tipo_documento" class="form-label">Tipo Documento</label>
                        <select class="form-select" id="tipo_documento" name="tipo_documento">
                            <option value="DNI">DNI</option>
                            <option value="Carnet Extranjería">Carnet Extranjería</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="dni" class="form-label">Nro. Documento</label>
                        <input type="text" class="form-control" id="dni" name="dni" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                    </div>
                     <div class="col-md-3 mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion">
                    </div>
                </div>
            </fieldset>

            <!-- Datos de los Padres y Apoderado -->
            <fieldset class="border p-3 mb-3">
                <legend class="w-auto px-2 h5">Datos de Familiares</legend>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="padre" class="form-label">Nombres del Padre</label>
                        <input type="text" class="form-control" id="padre" name="padre">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="madre" class="form-label">Nombres de la Madre</label>
                        <input type="text" class="form-control" id="madre" name="madre">
                    </div>
                </div>
                 <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="apoderado" class="form-label">Apoderado Principal</label>
                        <input type="text" class="form-control" id="apoderado" name="apoderado" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="celular_apoderado" class="form-label">Celular del Apoderado</label>
                        <input type="text" class="form-control" id="celular_apoderado" name="celular_apoderado" required>
                    </div>
                </div>
            </fieldset>

            <!-- Asignación de Aula -->
            <fieldset class="border p-3 mb-3">
                <legend class="w-auto px-2 h5">Asignación de Aula</legend>
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
                    <div class="col-md-6 mb-3" id="info-aula" style="display: none;">
                        <strong>Información del Aula:</strong>
                        <p class="mb-1"><strong>Docente:</strong> <span id="info-docente"></span></p>
                        <p class="mb-1"><strong>Cursos:</strong> <span id="info-cursos"></span></p>
                        <p class="mb-1"><strong>Vacantes:</strong> <span id="info-vacantes"></span></p>
                    </div>
                </div>
            </fieldset>

            <div class="d-flex justify-content-end">
                <a href="index.php?controller=alumno&action=index" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Registrar Alumno</button>
            </div>
        </form>
    </div>
</div>

<script src="public/assets/js/alumno_create.js"></script>

<?php include_once 'views/layout/footer.php'; ?>
