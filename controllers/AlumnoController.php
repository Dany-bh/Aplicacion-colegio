<?php
class AlumnoController {
    public function index() {
        // Incluir el archivo de configuración de la base de datos y el modelo
        include_once 'config/database.php';
        include_once 'models/Alumno.php';

        // Instanciar la base de datos y conectar
        $database = new Database();
        $db = $database->connect();

        // Instanciar el objeto Alumno
        $alumno = new Alumno($db);

        // Obtener los alumnos según el rol
        $stmt = null;
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'DOCENTE' && isset($_SESSION['docente_id'])) {
            $stmt = $alumno->readByDocente($_SESSION['docente_id']);
        } else {
            $stmt = $alumno->read();
        }
        
        // Pasar los datos a la vista
        $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Cargar la vista
        include_once 'views/alumnos/index.php';
    }

    public function create() {
        // Incluir archivos necesarios
        include_once 'config/database.php';
        include_once 'models/Grupo.php';
        include_once 'models/Grado.php';
        include_once 'models/Seccion.php';
        include_once 'models/Anio.php';
        include_once 'models/Docente.php';

        // Instanciar DB y conectar
        $database = new Database();
        $db = $database->connect();

        // Instanciar Grupo para obtener la lista de aulas
        $grupo = new Grupo($db);
        $stmt_grupos = $grupo->read();
        $grupos = $stmt_grupos->fetchAll(PDO::FETCH_ASSOC);

        $grado_model = new Grado($db);
        $grados = $grado_model->read()->fetchAll(PDO::FETCH_ASSOC);

        $seccion_model = new Seccion($db);
        $secciones = $seccion_model->read()->fetchAll(PDO::FETCH_ASSOC);

        $anio_model = new Anio($db);
        $anios = $anio_model->read()->fetchAll(PDO::FETCH_ASSOC);

        $docente_model = new Docente($db);
        $docentes = $docente_model->read()->fetchAll(PDO::FETCH_ASSOC); // Todos los docentes activos

        // Cargar la vista del formulario
        include_once 'views/alumnos/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            include_once 'config/database.php';
            include_once 'models/Alumno.php';

            $database = new Database();
            $db = $database->connect();
            $alumno = new Alumno($db);

            $grupo_id = $_POST['grupo_id'];
            $student_count = $alumno->getStudentCountForGrupo($grupo_id);

            if ($student_count >= 30) {
                header('Location: index.php?controller=alumno&action=index&error=full');
                exit;
            }

            // Datos del formulario
            $data = [
                'tipo_documento' => $_POST['tipo_documento'],
                'dni' => $_POST['dni'],
                'nombres' => $_POST['nombres'],
                'apellido_paterno' => $_POST['apellido_paterno'],
                'apellido_materno' => $_POST['apellido_materno'],
                'fecha_nacimiento' => $_POST['fecha_nacimiento'],
                'direccion' => $_POST['direccion'],
                'padre' => $_POST['padre'],
                'madre' => $_POST['madre'],
                'apoderado' => $_POST['apoderado'],
                'celular_apoderado' => $_POST['celular_apoderado'],
                'grupo_id' => $grupo_id
            ];

            if ($alumno->create($data)) {
                header('Location: index.php?controller=alumno&action=index&success=created');
            } else {
                header('Location: index.php?controller=alumno&action=index&error=failed');
            }
        } else {
            header('Location: index.php?controller=alumno&action=create');
        }
    }

    public function show() {
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID de alumno no encontrado.');

        include_once 'config/database.php';
        include_once 'models/Alumno.php';

        $database = new Database();
        $db = $database->connect();
        $alumno = new Alumno($db);

        $alumno_data = $alumno->readOne($id);

        if (!$alumno_data) {
            header('Location: index.php?controller=alumno&action=index&error=notfound');
            exit;
        }

        include_once 'views/alumnos/show.php';
    }

    public function edit() {
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID de alumno no encontrado.');

        include_once 'config/database.php';
        include_once 'models/Alumno.php';
        include_once 'models/Grupo.php';
        include_once 'models/Grado.php';
        include_once 'models/Seccion.php';
        include_once 'models/Anio.php';
        include_once 'models/Docente.php';

        $database = new Database();
        $db = $database->connect();
        $alumno = new Alumno($db);

        $alumno_data = $alumno->readOne($id);

        if (!$alumno_data) {
            header('Location: index.php?controller=alumno&action=index&error=notfound');
            exit;
        }

        // Obtener listas para dropdowns
        $grupo_model = new Grupo($db);
        $stmt_grupos = $grupo_model->read();
        $grupos = $stmt_grupos->fetchAll(PDO::FETCH_ASSOC);

        $grado_model = new Grado($db);
        $grados = $grado_model->read()->fetchAll(PDO::FETCH_ASSOC);

        $seccion_model = new Seccion($db);
        $secciones = $seccion_model->read()->fetchAll(PDO::FETCH_ASSOC);

        $anio_model = new Anio($db);
        $anios = $anio_model->read()->fetchAll(PDO::FETCH_ASSOC);

        $docente_model = new Docente($db);
        $docentes = $docente_model->read()->fetchAll(PDO::FETCH_ASSOC);

        include_once 'views/alumnos/edit.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            include_once 'config/database.php';
            include_once 'models/Alumno.php';

            $database = new Database();
            $db = $database->connect();
            $alumno = new Alumno($db);

            $id = $_POST['id'];
            $grupo_id = $_POST['grupo_id'];
            
            // Opcional: Validar si el nuevo grupo tiene espacio. 
            // Esto podría ser un problema si se cambia de grupo o si se edita y el grupo actual ya está lleno.
            // Para mantenerlo simple, no re-validaremos la capacidad en la actualización a menos que se cambie el grupo.
            // Una lógica más robusta implicaría verificar si el grupo_id ha cambiado y si el nuevo grupo tiene espacio.

            $data = [
                'id' => $id,
                'tipo_documento' => $_POST['tipo_documento'],
                'dni' => $_POST['dni'],
                'nombres' => $_POST['nombres'],
                'apellido_paterno' => $_POST['apellido_paterno'],
                'apellido_materno' => $_POST['apellido_materno'],
                'fecha_nacimiento' => $_POST['fecha_nacimiento'],
                'direccion' => $_POST['direccion'],
                'padre' => $_POST['padre'],
                'madre' => $_POST['madre'],
                'apoderado' => $_POST['apoderado'],
                'celular_apoderado' => $_POST['celular_apoderado'],
                'grupo_id' => $grupo_id
            ];

            if ($alumno->update($data)) {
                header('Location: index.php?controller=alumno&action=index&success=updated');
            } else {
                header('Location: index.php?controller=alumno&action=index&error=update_failed');
            }
        } else {
            header('Location: index.php?controller=alumno&action=index');
        }
    }

    public function delete() {
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID de alumno no especificado.');

        include_once 'config/database.php';
        include_once 'models/Alumno.php';

        $database = new Database();
        $db = $database->connect();
        $alumno = new Alumno($db);

        if ($alumno->delete($id)) {
            header('Location: index.php?controller=alumno&action=index&success=deleted');
        } else {
            header('Location: index.php?controller=alumno&action=index&error=delete_failed');
        }
    }
}
?>