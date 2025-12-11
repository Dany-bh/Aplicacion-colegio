<?php include_once 'views/layout/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Panel del Padre/Apoderado</h2>
    </div>
    <div class="card-body">
        <?php if (empty($hijos_data)): ?>
            <div class="alert alert-info" role="alert">
                Aún no tienes alumnos asociados a tu cuenta.
            </div>
        <?php else: ?>
            <?php foreach ($hijos_data as $hijo): ?>
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Alumno: <?php echo htmlspecialchars($hijo['nombres'] . ' ' . $hijo['apellido_paterno'] . ' ' . $hijo['apellido_materno']); ?> (Aula: <?php echo htmlspecialchars($hijo['grado'] . ' ' . $hijo['seccion']); ?>)</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>DNI:</strong> <?php echo htmlspecialchars($hijo['dni']); ?></p>
                                <p class="mb-1"><strong>Fecha Nacimiento:</strong> <?php echo htmlspecialchars($hijo['fecha_nacimiento']); ?></p>
                                <p class="mb-1"><strong>Apoderado Principal:</strong> <?php echo htmlspecialchars($hijo['apoderado']); ?></p>
                                <p class="mb-1"><strong>Celular Apoderado:</strong> <?php echo htmlspecialchars($hijo['celular_apoderado']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Grado:</strong> <?php echo htmlspecialchars($hijo['grado']); ?></p>
                                <p class="mb-1"><strong>Sección:</strong> <?php echo htmlspecialchars($hijo['seccion']); ?></p>
                                <p class="mb-1"><strong>Año:</strong> <?php echo htmlspecialchars($hijo['anio']); ?></p>
                            </div>
                        </div>

                        <!-- Pestañas para Notas, Asistencias, Horario -->
                        <ul class="nav nav-tabs" id="myTab-<?php echo $hijo['id']; ?>" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="notas-tab-<?php echo $hijo['id']; ?>" data-bs-toggle="tab" data-bs-target="#notas-<?php echo $hijo['id']; ?>" type="button" role="tab" aria-controls="notas-<?php echo $hijo['id']; ?>" aria-selected="true">Notas</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="asistencias-tab-<?php echo $hijo['id']; ?>" data-bs-toggle="tab" data-bs-target="#asistencias-<?php echo $hijo['id']; ?>" type="button" role="tab" aria-controls="asistencias-<?php echo $hijo['id']; ?>" aria-selected="false">Asistencias</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="horario-tab-<?php echo $hijo['id']; ?>" data-bs-toggle="tab" data-bs-target="#horario-<?php echo $hijo['id']; ?>" type="button" role="tab" aria-controls="horario-<?php echo $hijo['id']; ?>" aria-selected="false">Horario</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent-<?php echo $hijo['id']; ?>">
                            <div class="tab-pane fade show active p-3" id="notas-<?php echo $hijo['id']; ?>" role="tabpanel" aria-labelledby="notas-tab-<?php echo $hijo['id']; ?>">
                                <h5>Notas del Alumno</h5>
                                <?php if (!empty($hijo['notas'])): ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Bimestre</th>
                                                    <th>Curso</th>
                                                    <th>Nota</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($hijo['notas'] as $nota): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($nota['bimestre']); ?></td>
                                                        <td><?php echo htmlspecialchars($nota['area_nombre']); ?></td>
                                                        <td><?php echo htmlspecialchars($nota['nota']); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <p>No hay notas registradas para este alumno.</p>
                                <?php endif; ?>
                            </div>
                            <div class="tab-pane fade p-3" id="asistencias-<?php echo $hijo['id']; ?>" role="tabpanel" aria-labelledby="asistencias-tab-<?php echo $hijo['id']; ?>">
                                <h5>Registro de Asistencias</h5>
                                <?php if (!empty($hijo['asistencias'])): ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Estado</th>
                                                    <th>Observación</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($hijo['asistencias'] as $asistencia): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($asistencia['fecha']); ?></td>
                                                        <td><?php echo htmlspecialchars($asistencia['estado']); ?></td>
                                                        <td><?php echo htmlspecialchars($asistencia['observacion'] ?? '-'); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <p>No hay registro de asistencias para este alumno.</p>
                                <?php endif; ?>
                            </div>
                            <div class="tab-pane fade p-3" id="horario-<?php echo $hijo['id']; ?>" role="tabpanel" aria-labelledby="horario-tab-<?php echo $hijo['id']; ?>">
                                <h5>Horario de Clases</h5>
                                <?php if (!empty($hijo['horario'])): ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Día</th>
                                                    <th>Hora Inicio</th>
                                                    <th>Hora Fin</th>
                                                    <th>Curso</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($hijo['horario'] as $clase): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($clase['dia']); ?></td>
                                                        <td><?php echo htmlspecialchars(substr($clase['hora_inicio'], 0, 5)); ?></td>
                                                        <td><?php echo htmlspecialchars(substr($clase['hora_fin'], 0, 5)); ?></td>
                                                        <td><?php echo htmlspecialchars($clase['area_nombre']); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <p>No hay horario registrado para este grupo.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include_once 'views/layout/footer.php'; ?>
