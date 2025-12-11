<?php
class UsuarioController {
    public function create() {
        if ($_SESSION['user_role'] !== 'ADMIN') {
            header('Location: index.php');
            exit;
        }

        include_once 'config/database.php';
        include_once 'models/Docente.php';
        include_once 'models/Alumno.php';

        $database = new Database();
        $db = $database->connect();
        
        $docente_model = new Docente($db);
        $unassigned_docentes = $docente_model->getUnassigned();

        $alumno_model = new Alumno($db);
        $all_alumnos = $alumno_model->read()->fetchAll(PDO::FETCH_ASSOC);

        include_once 'views/usuarios/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            include_once 'config/database.php';
            include_once 'models/User.php';
            include_once 'models/Docente.php';
            include_once 'models/Padre.php';

            $database = new Database();
            $db = $database->connect();
            $db->beginTransaction();

            try {
                // 1. Crear el usuario
                $user_model = new User($db);
                $user_data = [
                    'nombres' => '', // Los nombres reales están en las tablas de docente/padre
                    'apellidos' => '',
                    'correo' => '', // El correo puede ser nulo o se puede agregar al formulario
                    'usuario' => $_POST['usuario'],
                    'password' => $_POST['password'],
                    'rol' => $_POST['rol']
                ];
                $user_id = $user_model->create($user_data);

                if (!$user_id) {
                    throw new Exception("Error al crear el registro de usuario.");
                }

                // 2. Vincular según el rol
                if ($_POST['rol'] === 'DOCENTE') {
                    $docente_model = new Docente($db);
                    if (!$docente_model->assignUser($_POST['docente_id'], $user_id)) {
                        throw new Exception("Error al vincular el usuario al docente.");
                    }
                } elseif ($_POST['rol'] === 'PADRE') {
                    $padre_model = new Padre($db);
                    if (!$padre_model->assignStudent($user_id, $_POST['alumno_id'])) {
                        throw new Exception("Error al vincular el usuario al alumno como apoderado.");
                    }
                }

                $db->commit();
                header('Location: index.php?controller=usuario&action=index&success=user_created');
            } catch (Exception $e) {
                $db->rollBack();
                header('Location: index.php?controller=usuario&action=index&error=user_creation_failed');
            }
        } else {
            header('Location: index.php?controller=usuario&action=create');
        }
    }

    public function index() {
        if ($_SESSION['user_role'] !== 'ADMIN') {
            header('Location: index.php');
            exit;
        }

        include_once 'config/database.php';
        include_once 'models/User.php';

        $database = new Database();
        $db = $database->connect();
        $user_model = new User($db);

        $users = $user_model->readAll();

        include_once 'views/usuarios/index.php';
    }
}
?>