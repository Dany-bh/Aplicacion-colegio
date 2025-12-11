<?php include_once 'views/layout/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Registrar Notas</h2>
        <h5 class="card-subtitle mb-2 text-muted">Bimestre: <?php echo htmlspecialchars($bimestre); ?></h5>
        <?php // Aquí se podría mostrar el nombre del aula y curso ?>
    </div>
    <div class="card-body">
        <form action="index.php?controller=nota&action=save" method="POST">
            <input type="hidden" name="grupo_id" value="<?php echo htmlspecialchars($grupo_id); ?>">
            <input type="hidden" name="area_id" value="<?php echo htmlspecialchars($area_id); ?>">
            <input type="hidden" name="bimestre" value="<?php echo htmlspecialchars($bimestre); ?>">
            
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Apellidos y Nombres</th>
                            <th style="width: 150px;">Nota (0-20)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($alumnos)): ?>
                            <?php foreach ($alumnos as $index => $alumno): ?>
                                <?php
                                    $nota_actual = $notas_existentes[$alumno['id']] ?? '';
                                ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($alumno['apellido_paterno'] . ' ' . $alumno['apellido_materno'] . ', ' . $alumno['nombres']); ?></td>
                                    <td>
                                        <input type="number" class="form-control" 
                                               name="notas[<?php echo $alumno['id']; ?>]" 
                                               value="<?php echo htmlspecialchars($nota_actual); ?>" 
                                               min="0" max="20" step="0.1">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">No hay alumnos en esta aula.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                 <a href="index.php?controller=nota&action=index" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary" <?php if(empty($alumnos)) echo 'disabled'; ?>>Guardar Notas</button>
            </div>
        </form>
    </div>
</div>

<?php include_once 'views/layout/footer.php'; ?>
