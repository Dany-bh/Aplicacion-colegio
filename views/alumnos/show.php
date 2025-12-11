<?php include_once 'views/layout/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Detalles del Alumno</h2>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>ID:</strong> <?php echo htmlspecialchars($alumno_data['id']); ?><br>
                <strong>Nombres:</strong> <?php echo htmlspecialchars($alumno_data['nombres']); ?><br>
                <strong>Apellidos:</strong> <?php echo htmlspecialchars($alumno_data['apellido_paterno'] . ' ' . $alumno_data['apellido_materno']); ?><br>
                <strong>Tipo Documento:</strong> <?php echo htmlspecialchars($alumno_data['tipo_documento']); ?><br>
                <strong>DNI:</strong> <?php echo htmlspecialchars($alumno_data['dni']); ?><br>
                <strong>Fecha Nacimiento:</strong> <?php echo htmlspecialchars($alumno_data['fecha_nacimiento']); ?><br>
                <strong>Dirección:</strong> <?php echo htmlspecialchars($alumno_data['direccion']); ?><br>
            </div>
            <div class="col-md-6">
                <strong>Padre:</strong> <?php echo htmlspecialchars($alumno_data['padre']); ?><br>
                <strong>Madre:</strong> <?php echo htmlspecialchars($alumno_data['madre']); ?><br>
                <strong>Apoderado:</strong> <?php echo htmlspecialchars($alumno_data['apoderado']); ?><br>
                <strong>Celular Apoderado:</strong> <?php echo htmlspecialchars($alumno_data['celular_apoderado']); ?><br>
                <strong>Fecha Ingreso:</strong> <?php echo htmlspecialchars($alumno_data['fecha_ingreso']); ?><br>
                <strong>Aula Asignada:</strong> <?php echo htmlspecialchars($alumno_data['grado'] . ' ' . $alumno_data['seccion'] . ' - ' . $alumno_data['anio']); ?><br>
                <strong>Docente del Aula:</strong> <?php echo htmlspecialchars($alumno_data['docente_nombres'] . ' ' . $alumno_data['docente_apellido_paterno']); ?><br>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <a href="index.php?controller=alumno&action=index" class="btn btn-secondary me-2">Volver a la Lista</a>
            <a href="index.php?controller=alumno&action=edit&id=<?php echo htmlspecialchars($alumno_data['id']); ?>" class="btn btn-warning">Editar Alumno</a>
        </div>
    </div>
</div>

<?php include_once 'views/layout/footer.php'; ?>
