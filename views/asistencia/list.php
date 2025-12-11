<?php include_once 'views/layout/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Registrar Asistencia</h2>
        <h5 class="card-subtitle mb-2 text-muted">Fecha: <?php echo htmlspecialchars($fecha); ?></h5>
        <?php // Aquí se podría mostrar el nombre del aula si lo pasamos desde el controlador ?>
    </div>
    <div class="card-body">
        <form action="index.php?controller=asistencia&action=save" method="POST">
            <input type="hidden" name="fecha" value="<?php echo htmlspecialchars($fecha); ?>">
            <input type="hidden" name="grupo_id" value="<?php echo htmlspecialchars($grupo_id); ?>">
            
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Apellidos y Nombres</th>
                            <th class="text-center">Asistió</th>
                            <th class="text-center">Tardanza</th>
                            <th class="text-center">Faltó</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($alumnos)): ?>
                            <?php foreach ($alumnos as $index => $alumno): ?>
                                <?php
                                    $estado_actual = $asistencias_existentes[$alumno['id']] ?? 'ASISTIO'; // Por defecto ASISTIO
                                ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($alumno['apellido_paterno'] . ' ' . $alumno['apellido_materno'] . ', ' . $alumno['nombres']); ?></td>
                                    <td class="text-center">
                                        <input class="form-check-input" type="radio" name="asistencia[<?php echo $alumno['id']; ?>]" value="ASISTIO" <?php if($estado_actual == 'ASISTIO') echo 'checked'; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input class="form-check-input" type="radio" name="asistencia[<?php echo $alumno['id']; ?>]" value="TARDE" <?php if($estado_actual == 'TARDE') echo 'checked'; ?>>
                                    </td>
                                     <td class="text-center">
                                        <input class="form-check-input" type="radio" name="asistencia[<?php echo $alumno['id']; ?>]" value="FALTO" <?php if($estado_actual == 'FALTO') echo 'checked'; ?>>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No hay alumnos en esta aula.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                 <a href="index.php?controller=asistencia&action=index" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary" <?php if(empty($alumnos)) echo 'disabled'; ?>>Guardar Asistencia</button>
            </div>
        </form>
    </div>
</div>

<?php include_once 'views/layout/footer.php'; ?>
