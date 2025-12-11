<?php
class GrupoController {
    public function index() {
        // Incluir el archivo de configuración de la base de datos y el modelo
        include_once 'config/database.php';
        include_once 'models/Grupo.php';

        // Instanciar la base de datos y conectar
        $database = new Database();
        $db = $database->connect();

        // Instanciar el objeto Grupo
        $grupo = new Grupo($db);

        // Obtener los grupos
        $stmt = $grupo->read();
        
        // Pasar los datos a la vista
        $grupos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Cargar la vista
        include_once 'views/grupos/index.php';
    }

    public function create() {
        include_once 'config/database.php';
        include_once 'models/Grado.php';
        include_once 'models/Seccion.php';
        include_once 'models/Anio.php';
        include_once 'models/Docente.php';
        include_once 'models/Area.php';

        $database = new Database();
        $db = $database->connect();

        $grado = new Grado($db);
        $grados = $grado->read()->fetchAll(PDO::FETCH_ASSOC);

        $seccion = new Seccion($db);
        $secciones = $seccion->read()->fetchAll(PDO::FETCH_ASSOC);

        $anio = new Anio($db);
        $anios = $anio->read()->fetchAll(PDO::FETCH_ASSOC);

        $docente = new Docente($db);
        $docentes = $docente->read()->fetchAll(PDO::FETCH_ASSOC);

        $area = new Area($db);
        $areas = $area->read()->fetchAll(PDO::FETCH_ASSOC);

        include_once 'views/grupos/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            include_once 'config/database.php';
            include_once 'models/Grupo.php';
            include_once 'models/Seccion.php';
            include_once 'models/Anio.php';
            include_once 'models/DocenteArea.php';

            $database = new Database();
            $db = $database->connect();
            
            $seccion = new Seccion($db);
            $anio = new Anio($db);
            $grupo = new Grupo($db);
            $docenteArea = new DocenteArea($db);

            // 1. Find or create Seccion y Anio
            $seccion_id = $seccion->findOrCreate($_POST['seccion_nombre']);
            $anio_id = $anio->findOrCreate($_POST['anio_nombre']);

            if (!$seccion_id || !$anio_id) {
                header('Location: index.php?controller=grupo&action=create&error=failed_find_create');
                exit;
            }

            // 2. Crear el Grupo
            $data = [
                'grado_id' => $_POST['grado_id'],
                'seccion_id' => $seccion_id,
                'anio_id' => $anio_id,
                'docente_id' => $_POST['docente_id']
            ];

            $new_grupo_id = $grupo->create($data);

            if ($new_grupo_id) {
                // 3. Asignar cursos al grupo
                if (!empty($_POST['area_ids']) && is_array($_POST['area_ids'])) {
                    $docente_id = $_POST['docente_id'];
                    foreach ($_POST['area_ids'] as $area_id) {
                        // Asocia el curso con el grupo y el docente principal del grupo
                        $docenteArea->create($docente_id, $area_id, $new_grupo_id);
                    }
                }
                header('Location: index.php?controller=grupo&action=index&success=created');
            } else {
                header('Location: index.php?controller=grupo&action=create&error=failed');
            }
        } else {
            header('Location: index.php?controller=grupo&action=create');
        }
    }

    public function show() {
        if (isset($_GET['id'])) {
            $grupo_id = $_GET['id'];

            include_once 'config/database.php';
            include_once 'models/Grupo.php';
            include_once 'models/Alumno.php';
            include_once 'models/Area.php';

            $database = new Database();
            $db = $database->connect();

            // Obtener info del grupo
            $grupo = new Grupo($db);
            $grupo_data = $grupo->readOne($grupo_id);

            // Obtener alumnos del grupo
            $alumno = new Alumno($db);
            $alumnos = $alumno->readByGrupo($grupo_id);

            // Obtener cursos del grupo
            $area = new Area($db);
            $cursos = $area->readByGrupo($grupo_id);
            
            if ($grupo_data) {
                include_once 'views/grupos/show.php';
            } else {
                echo "El aula no fue encontrada.";
            }
        } else {
            echo "ID de aula no especificado.";
        }
    }

    public function edit() {
        if (isset($_GET['id'])) {
            $grupo_id = $_GET['id'];

            include_once 'config/database.php';
            include_once 'models/Grupo.php';
            include_once 'models/Grado.php';
            include_once 'models/Seccion.php';
            include_once 'models/Anio.php';
            include_once 'models/Docente.php';

            $database = new Database();
            $db = $database->connect();

            // Cargar datos del grupo a editar
            $grupo = new Grupo($db);
            $grupo_data = $grupo->readOne($grupo_id);

            if (!$grupo_data) {
                header('Location: index.php?controller=grupo&action=index&error=not_found');
                exit;
            }

            // Cargar listas para los dropdowns
            $grado = new Grado($db);
            $grados = $grado->read()->fetchAll(PDO::FETCH_ASSOC);

            $seccion = new Seccion($db);
            $secciones = $seccion->read()->fetchAll(PDO::FETCH_ASSOC);

            $anio = new Anio($db);
            $anios = $anio->read()->fetchAll(PDO::FETCH_ASSOC);

            $docente = new Docente($db);
            $docentes = $docente->read()->fetchAll(PDO::FETCH_ASSOC);
            
            include_once 'views/grupos/edit.php';

        } else {
            header('Location: index.php?controller=grupo&action=index&error=id_not_found');
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            include_once 'config/database.php';
            include_once 'models/Grupo.php';

            $database = new Database();
            $db = $database->connect();
            $grupo = new Grupo($db);

            $data = [
                'id' => $_POST['id'],
                'grado_id' => $_POST['grado_id'],
                'seccion_id' => $_POST['seccion_id'],
                'anio_id' => $_POST['anio_id'],
                'docente_id' => $_POST['docente_id']
            ];

            if ($grupo->update($data)) {
                header('Location: index.php?controller=grupo&action=index&success=updated');
            } else {
                header('Location: index.php?controller=grupo&action=edit&id=' . $_POST['id'] . '&error=update_failed');
            }
        } else {
            header('Location: index.php?controller=grupo&action=index');
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $grupo_id = $_GET['id'];

            include_once 'config/database.php';
            include_once 'models/Grupo.php';

            $database = new Database();
            $db = $database->connect();

            $grupo = new Grupo($db);

            if ($grupo->delete($grupo_id)) {
                header('Location: index.php?controller=grupo&action=index&success=deleted');
            } else {
                header('Location: index.php?controller=grupo&action=index&error=delete_failed');
            }
        } else {
            header('Location: index.php?controller=grupo&action=index&error=id_not_found');
        }
    }
}
?>