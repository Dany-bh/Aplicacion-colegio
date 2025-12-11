<?php include_once 'views/layout/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Editar Docente</h2>
    </div>
    <div class="card-body">
        <form action="index.php?controller=docente&action=update" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($docente_data['id']); ?>">
            <input type="hidden" name="usuario_id" value="<?php echo htmlspecialchars($docente_data['usuario_id']); ?>">
            
            <!-- Datos del Docente -->
            <fieldset class="border p-3 mb-3">
                <legend class="w-auto px-2 h5">Datos Personales</legend>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="nombres" class="form-label">Nombres</label>
                        <input type="text" class="form-control" id="nombres" name="nombres" value="<?php echo htmlspecialchars($docente_data['nombres']); ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="<?php echo htmlspecialchars($docente_data['apellido_paterno']); ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="apellido_materno" class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" value="<?php echo htmlspecialchars($docente_data['apellido_materno']); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="documento" class="form-label">Nro. Documento (DNI)</label>
                        <input type="text" class="form-control" id="documento" name="documento" value="<?php echo htmlspecialchars($docente_data['documento']); ?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($docente_data['telefono']); ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="anio_ingreso" class="form-label">Año de Ingreso</label>
                        <input type="number" class="form-control" id="anio_ingreso" name="anio_ingreso" min="1980" max="<?php echo date('Y'); ?>" value="<?php echo htmlspecialchars($docente_data['anio_ingreso']); ?>">
                    </div>
                     <div class="col-md-3 mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($docente_data['direccion']); ?>">
                    </div>
                </div>
            </fieldset>

            <!-- Datos de Usuario -->
            <fieldset class="border p-3 mb-3">
                <legend class="w-auto px-2 h5">Datos de Acceso al Sistema</legend>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="usuario" class="form-label">Nombre de Usuario</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" value="<?php echo htmlspecialchars($docente_data['usuario']); ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($docente_data['correo']); ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="password" class="form-label">Nueva Contraseña (dejar vacío para no cambiar)</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                </div>
            </fieldset>

            <div class="d-flex justify-content-end">
                <a href="index.php?controller=docente&action=index" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar Docente</button>
            </div>
        </form>
    </div>
</div>

<?php include_once 'views/layout/footer.php'; ?>
