<?php include_once 'views/layout/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Consultar Asistencia (Administrador)</h2>
    </div>
    <div class="card-body">
        <form action="index.php" method="GET" class="mb-4">
            <input type="hidden" name="controller" value="asistencia">
            <input type="hidden" name="action" value="index">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="grupo_id" class="form-label">Aula / Grupo</label>
                    <select class="form-select" id="grupo_id" name="grupo_id">
                        <option value="">-- Todas las Aulas --</option>
                        <?php foreach ($grupos as $grupo): ?>
                            <option value="<?php echo $grupo['id']; ?>" <?php echo (isset($_GET['grupo_id']) && $_GET['grupo_id'] == $grupo['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($grupo['grado'] . ' ' . $grupo['seccion'] . ' (' . $grupo['anio'] . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo $_GET['fecha'] ?? ''; ?>">
                </div>
                <div class="col-md-3">
                    <label for="search_term" class="form-label">Buscar por DNI o Nombre</label>
                    <input type="text" class="form-control" id="search_term" name="search_term" placeholder="DNI o nombre..." value="<?php echo $_GET['search_term'] ?? ''; ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </div>
        </form>

        <hr>

        <h4>Resultados de la Búsqueda</h4>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Fecha</th>
                        <th>Aula</th>
                        <th>DNI del Alumno</th>
                        <th>Alumno</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($asistencias)): ?>
                        <?php foreach ($asistencias as $asistencia): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(date("d/m/Y", strtotime($asistencia['fecha']))); ?></td>
                                <td><?php echo htmlspecialchars($asistencia['grado'] . ' ' . $asistencia['seccion']); ?></td>
                                <td><?php echo htmlspecialchars($asistencia['dni']); ?></td>
                                <td><?php echo htmlspecialchars($asistencia['apellido_paterno'] . ' ' . $asistencia['apellido_materno'] . ', ' . $asistencia['nombres']); ?></td>
                                <td>
                                    <?php 
                                        $estado = htmlspecialchars($asistencia['estado']);
                                        $badge_class = 'bg-secondary';
                                        if ($estado === 'ASISTIO') $badge_class = 'bg-success';
                                        if ($estado === 'FALTO') $badge_class = 'bg-danger';
                                        if ($estado === 'TARDE') $badge_class = 'bg-warning text-dark';
                                        echo "<span class='badge {$badge_class}'>{$estado}</span>";
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No se encontraron registros con los filtros seleccionados. Por favor, realice una nueva búsqueda.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once 'views/layout/footer.php'; ?>
