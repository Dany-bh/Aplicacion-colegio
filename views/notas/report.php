<?php include_once 'views/layout/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Reporte de Notas</h2>
        <p class="card-subtitle text-muted">Aquí puede ver el historial de notas de sus alumnos y filtrar por curso o DNI.</p>
    </div>
    <div class="card-body">
        
        <!-- Filtros -->
        <form action="index.php?controller=nota&action=report" method="POST" class="mb-4">
            <div class="row">
                <div class="col-md-5">
                    <label for="area_id" class="form-label">Filtrar por Curso:</label>
                    <select name="area_id" id="area_id" class="form-select">
                        <option value="">-- Todos los Cursos --</option>
                        <?php foreach ($cursos_impartidos as $curso): ?>
                            <option value="<?php echo $curso['id']; ?>" <?php echo (isset($_REQUEST['area_id']) && $_REQUEST['area_id'] == $curso['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($curso['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="dni" class="form-label">Buscar por DNI del Alumno:</label>
                    <input type="text" name="dni" id="dni" class="form-control" value="<?php echo isset($_REQUEST['dni']) ? htmlspecialchars($_REQUEST['dni']) : ''; ?>" placeholder="Escriba DNI...">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </div>
        </form>

        <?php if (!empty($report_data)): ?>
            <?php foreach ($report_data as $alumno_id => $data): ?>
                <div class="accordion mb-2" id="accordion-<?php echo $alumno_id; ?>">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-<?php echo $alumno_id; ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $alumno_id; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $alumno_id; ?>">
                                <?php echo htmlspecialchars($data['nombre_completo']); ?> (DNI: <?php echo htmlspecialchars($data['dni']); ?>)
                            </button>
                        </h2>
                        <div id="collapse-<?php echo $alumno_id; ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?php echo $alumno_id; ?>">
                            <div class="accordion-body">
                                <?php if (!empty($data['cursos'])): ?>
                                    <?php foreach ($data['cursos'] as $nombre_curso => $notas): ?>
                                        <h5 class="mt-3"><?php echo htmlspecialchars($nombre_curso); ?></h5>
                                        <table class="table table-sm table-striped table-bordered">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Bimestre 1</th>
                                                    <th>Bimestre 2</th>
                                                    <th>Bimestre 3</th>
                                                    <th>Bimestre 4</th>
                                                    <th>Promedio</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <?php 
                                                        $notas_bimestre = ['1' => '-', '2' => '-', '3' => '-', '4' => '-'];
                                                        $total = 0;
                                                        $count = 0;
                                                        foreach ($notas as $nota) {
                                                            $notas_bimestre[$nota['bimestre']] = htmlspecialchars($nota['nota']);
                                                            $total += $nota['nota'];
                                                            $count++;
                                                        }
                                                        $promedio = ($count > 0) ? number_format($total / $count, 2) : '-';
                                                    ?>
                                                    <td><?php echo $notas_bimestre['1']; ?></td>
                                                    <td><?php echo $notas_bimestre['2']; ?></td>
                                                    <td><?php echo $notas_bimestre['3']; ?></td>
                                                    <td><?php echo $notas_bimestre['4']; ?></td>
                                                    <td><strong><?php echo $promedio; ?></strong></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-center text-muted">No hay notas registradas para este alumno.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No se encontraron alumnos o notas con los filtros seleccionados.</p>
        <?php endif; ?>
    </div>
</div>

<?php include_once 'views/layout/footer.php'; ?>
