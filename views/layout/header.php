<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Colegio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Colegio</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'ADMIN'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=grupo&action=index">Aulas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=docente&action=index">Docentes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=alumno&action=index">Alumnos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=usuario&action=index">Gestión de Usuarios</a>
                        </li>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['ADMIN', 'DOCENTE'])): ?>
                         <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=asistencia&action=index">Asistencia</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=nota&action=index">Notas</a>
                        </li>
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'DOCENTE'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=asistencia&action=report">Reporte de Asistencia</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=nota&action=report">Reporte de Notas</a>
                        </li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'PADRE'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=padre&action=index">Mis Hijos</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                     <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="index.php?controller=login&action=logout">Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">