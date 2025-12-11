<?php include_once 'views/layout/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Detalles del Aula</h2>
    </div>
    <div class="card-body">
        <?php if (!empty($grupo_data)): ?>
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Grado:</strong> <?php echo htmlspecialchars($grupo_data['grado']); ?></p>
                    <p><strong>Sección:</strong> <?php echo htmlspecialchars($grupo_data['seccion']); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Año Lectivo:</strong> <?php echo htmlspecialchars($grupo_data['anio']); ?></p>
                    <p><strong>Docente a Cargo:</strong> <?php echo htmlspecialchars($grupo_data['docente_nombres'] . ' ' . $grupo_data['docente_ap_paterno']); ?></p>
                </div>
            </div>

            <!-- Listado de Alumnos -->
            <h3 class="mt-4">Alumnos Inscritos</h3>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>DNI</th>
                            <th>Nombres y Apellidos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($alumnos)): ?>
                            <?php foreach ($alumnos as $alumno): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($alumno['dni']); ?></td>
                                    <td><?php echo htmlspecialchars($alumno['apellido_paterno'] . ' ' . $alumno['apellido_materno'] . ', ' . $alumno['nombres']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="text-center">No hay alumnos registrados en esta aula.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Listado de Cursos -->
            <h3 class="mt-4">Cursos Asignados</h3>
             <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Curso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($cursos)): ?>
                            <?php foreach ($cursos as $curso): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($curso['nombre']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="text-center">No hay cursos asignados a esta aula.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <a href="index.php?controller=grupo&action=index" class="btn btn-secondary mt-3">Volver al Listado</a>

        <?php else: ?>
            <p class="text-center">No se encontraron datos para el aula especificada.</p>
        <?php endif; ?>
    </div>
</div>

<?php include_once 'views/layout/footer.php'; ?>
