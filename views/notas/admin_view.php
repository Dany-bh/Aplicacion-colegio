<?php include_once 'views/layout/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Consultar Notas (Administrador)</h2>
    </div>
    <div class="card-body">
        <form action="index.php" method="GET" class="mb-4">
            <input type="hidden" name="controller" value="nota">
            <input type="hidden" name="action" value="index">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="grupo_id" class="form-label">Aula / Grupo</label>
                    <select class="form-select" id="grupo_id" name="grupo_id">
                        <option value="">-- Todas --</option>
                        <?php foreach ($grupos as $grupo): ?>
                            <option value="<?php echo $grupo['id']; ?>" <?php echo (isset($_GET['grupo_id']) && $_GET['grupo_id'] == $grupo['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($grupo['grado'] . ' ' . $grupo['seccion']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="area_id" class="form-label">Curso</label>
                    <select class="form-select" id="area_id" name="area_id">
                        <option value="">-- Todos --</option>
                        <?php foreach ($areas as $area): ?>
                            <option value="<?php echo $area['id']; ?>" <?php echo (isset($_GET['area_id']) && $_GET['area_id'] == $area['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($area['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="bimestre" class="form-label">Bimestre</label>
                    <select class="form-select" id="bimestre" name="bimestre">
                        <option value="">-- Todos --</option>
                        <option value="1" <?php echo (isset($_GET['bimestre']) && $_GET['bimestre'] == '1') ? 'selected' : ''; ?>>1er Bimestre</option>
                        <option value="2" <?php echo (isset($_GET['bimestre']) && $_GET['bimestre'] == '2') ? 'selected' : ''; ?>>2do Bimestre</option>
                        <option value="3" <?php echo (isset($_GET['bimestre']) && $_GET['bimestre'] == '3') ? 'selected' : ''; ?>>3er Bimestre</option>
                        <option value="4" <?php echo (isset($_GET['bimestre']) && $_GET['bimestre'] == '4') ? 'selected' : ''; ?>>4to Bimestre</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="search_term" class="form-label">Buscar por DNI o Nombre</label>
                    <input type="text" class="form-control" id="search_term" name="search_term" placeholder="DNI o nombre..." value="<?php echo $_GET['search_term'] ?? ''; ?>">
                </div>
                <div class="col-md-1">
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
                        <th>Aula</th>
                        <th>Curso</th>
                        <th>Bimestre</th>
                        <th>DNI Alumno</th>
                        <th>Alumno</th>
                        <th>Nota</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($notas)): ?>
                        <?php foreach ($notas as $nota): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($nota['grado'] . ' ' . $nota['seccion']); ?></td>
                                <td><?php echo htmlspecialchars($nota['area_nombre']); ?></td>
                                <td><?php echo htmlspecialchars($nota['bimestre']); ?></td>
                                <td><?php echo htmlspecialchars($nota['dni']); ?></td>
                                <td><?php echo htmlspecialchars($nota['apellido_paterno'] . ' ' . $nota['apellido_materno'] . ', ' . $nota['nombres']); ?></td>
                                <td>
                                    <?php 
                                        $valor_nota = htmlspecialchars($nota['nota']);
                                        $badge_class = 'bg-secondary';
                                        if ($valor_nota >= 18) $badge_class = 'bg-primary';
                                        elseif ($valor_nota >= 11) $badge_class = 'bg-success';
                                        else $badge_class = 'bg-danger';
                                        echo "<span class='badge {$badge_class}'>{$valor_nota}</span>";
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No se encontraron notas con los filtros seleccionados. Por favor, realice una nueva búsqueda.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once 'views/layout/footer.php'; ?>
