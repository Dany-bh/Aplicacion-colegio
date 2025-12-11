<?php include_once 'views/layout/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Reporte de Asistencia General</h2>
        <p class="card-subtitle text-muted">Aquí puede ver el historial de asistencia de todos sus alumnos.</p>
    </div>
    <div class="card-body">
        
        <!-- Filtros -->
        <div class="row mb-4">
            <div class="col-md-4">
                <label for="filtro-fecha" class="form-label">Filtrar por Fecha:</label>
                <input type="date" id="filtro-fecha" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="filtro-alumno" class="form-label">Buscar Alumno:</label>
                <input type="text" id="filtro-alumno" class="form-control" placeholder="Escriba nombre o apellido...">
            </div>
        </div>

        <?php if (!empty($report_data)): ?>
            <?php foreach ($report_data as $alumno_id => $data): ?>
                <div class="accordion mb-2" id="accordion-<?php echo $alumno_id; ?>">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-<?php echo $alumno_id; ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $alumno_id; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $alumno_id; ?>">
                                <?php echo htmlspecialchars($data['nombre_completo']); ?>
                            </button>
                        </h2>
                        <div id="collapse-<?php echo $alumno_id; ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?php echo $alumno_id; ?>">
                            <div class="accordion-body">
                                <div class="d-flex justify-content-end mb-2">
                                     <a href="index.php?controller=asistencia&action=exportPDF&alumno_id=<?php echo $alumno_id; ?>" class="btn btn-danger btn-sm" target="_blank">
                                        <i class="bi bi-file-earmark-pdf"></i> Exportar a PDF
                                    </a>
                                </div>
                                <?php if (!empty($data['registros'])): ?>
                                    <table class="table table-sm table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data['registros'] as $registro): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars(date("d/m/Y", strtotime($registro['fecha']))); ?></td>
                                                    <td>
                                                        <?php 
                                                            $estado = htmlspecialchars($registro['estado']);
                                                            $badge_class = 'bg-secondary';
                                                            if ($estado === 'ASISTIO') $badge_class = 'bg-success';
                                                            if ($estado === 'FALTO') $badge_class = 'bg-danger';
                                                            if ($estado === 'TARDE') $badge_class = 'bg-warning text-dark';
                                                            echo "<span class='badge {$badge_class}'>{$estado}</span>";
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <p class="text-center text-muted">No hay registros de asistencia para este alumno.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No se encontraron alumnos asignados a su cargo.</p>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filtroFecha = document.getElementById('filtro-fecha');
    const filtroAlumno = document.getElementById('filtro-alumno');

    // Filtrado de alumnos por nombre
    filtroAlumno.addEventListener('keyup', function() {
        const textoBusqueda = this.value.toLowerCase();
        document.querySelectorAll('.accordion-item').forEach(function(item) {
            const nombreAlumno = item.querySelector('.accordion-button').textContent.toLowerCase();
            if (nombreAlumno.includes(textoBusqueda)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Filtrado de registros de asistencia por fecha
    filtroFecha.addEventListener('change', function() {
        const fechaSeleccionada = this.value;
        document.querySelectorAll('.accordion-body table tbody tr').forEach(function(row) {
            const fechaRegistro = row.querySelector('td:first-child').textContent; // Formato dd/mm/yyyy
            const [day, month, year] = fechaRegistro.split('/');
            const fechaRegistroISO = `${year}-${month}-${day}`;

            if (fechaSeleccionada === '' || fechaRegistroISO === fechaSeleccionada) {
                row.style.display = 'table-row';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>

<?php include_once 'views/layout/footer.php'; ?>
