<?php
class DocenteController {
    public function index() {
        // Incluir el archivo de configuración de la base de datos y el modelo
        include_once 'config/database.php';
        include_once 'models/Docente.php';

        // Instanciar la base de datos y conectar
        $database = new Database();
        $db = $database->connect();

        // Instanciar el objeto Docente
        $docente = new Docente($db);

        // Obtener los docentes
        $stmt = $docente->read();
        
        // Pasar los datos a la vista
        $docentes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Cargar la vista
        include_once 'views/docentes/index.php';
    }

    public function create() {
        include_once 'views/docentes/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            include_once 'config/database.php';
            include_once 'models/User.php';
            include_once 'models/Docente.php';

            $database = new Database();
            $db = $database->connect();
            $user_model = new User($db);
            $docente_model = new Docente($db);

            $db->beginTransaction();

            try {
                // 1. Crear el usuario
                $user_data = [
                    'nombres' => $_POST['nombres'],
                    'apellidos' => $_POST['apellido_paterno'] . ' ' . $_POST['apellido_materno'],
                    'correo' => $_POST['correo'],
                    'usuario' => $_POST['usuario'],
                    'password' => $_POST['password'],
                    'rol' => 'DOCENTE'
                ];
                $user_id = $user_model->create($user_data);

                if (!$user_id) {
                    throw new Exception("Error al crear el registro de usuario.");
                }

                // 2. Crear el docente
                $docente_data = [
                    'usuario_id' => $user_id,
                    'nombres' => $_POST['nombres'],
                    'apellido_paterno' => $_POST['apellido_paterno'],
                    'apellido_materno' => $_POST['apellido_materno'],
                    'documento' => $_POST['documento'],
                    'telefono' => $_POST['telefono'],
                    'direccion' => $_POST['direccion'],
                    'anio_ingreso' => $_POST['anio_ingreso']
                ];
                $docente_created = $docente_model->create($docente_data);

                if (!$docente_created) {
                    throw new Exception("Error al crear el registro de docente.");
                }

                // 3. Si todo fue bien, confirmar
                $db->commit();
                header('Location: index.php?controller=docente&action=index&success=created');

            } catch (Exception $e) {
                // Si algo falló, revertir
                $db->rollBack();
                // Podríamos loggear $e->getMessage()
                header('Location: index.php?controller=docente&action=index&error=failed');
            }
        } else {
            header('Location: index.php?controller=docente&action=create');
        }
    }

    public function edit() {
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID no encontrado.');

        include_once 'config/database.php';
        include_once 'models/Docente.php';

        $database = new Database();
        $db = $database->connect();
        $docente = new Docente($db);

        $docente_data = $docente->readOne($id);

        if (!$docente_data) {
            header('Location: index.php?controller=docente&action=index&error=notfound');
            exit;
        }

        include_once 'views/docentes/edit.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            include_once 'config/database.php';
            include_once 'models/User.php';
            include_once 'models/Docente.php';

            $database = new Database();
            $db = $database->connect();
            $user_model = new User($db);
            $docente_model = new Docente($db);

            $db->beginTransaction();

            try {
                $docente_id = $_POST['id'];
                $usuario_id = $_POST['usuario_id'];

                // 1. Actualizar el usuario
                $user_data = [
                    'id' => $usuario_id,
                    'nombres' => $_POST['nombres'],
                    'apellidos' => $_POST['apellido_paterno'] . ' ' . $_POST['apellido_materno'],
                    'correo' => $_POST['correo'],
                    'usuario' => $_POST['usuario'],
                    'password' => '' // Asumimos que la contraseña solo se actualiza si el campo no está vacío
                ];
                if (!empty($_POST['password'])) {
                    $user_data['password'] = $_POST['password'];
                }
                
                $user_updated = $user_model->update($user_data);

                if (!$user_updated) {
                    throw new Exception("Error al actualizar el registro de usuario.");
                }

                // 2. Actualizar el docente
                $docente_data = [
                    'id' => $docente_id,
                    'nombres' => $_POST['nombres'],
                    'apellido_paterno' => $_POST['apellido_paterno'],
                    'apellido_materno' => $_POST['apellido_materno'],
                    'documento' => $_POST['documento'],
                    'telefono' => $_POST['telefono'],
                    'direccion' => $_POST['direccion'],
                    'anio_ingreso' => $_POST['anio_ingreso']
                ];
                $docente_updated = $docente_model->update($docente_data);

                if (!$docente_updated) {
                    throw new Exception("Error al actualizar el registro de docente.");
                }

                // 3. Si todo fue bien, confirmar
                $db->commit();
                header('Location: index.php?controller=docente&action=index&success=updated');

            } catch (Exception $e) {
                // Si algo falló, revertir
                $db->rollBack();
                // Podríamos loggear $e->getMessage()
                header('Location: index.php?controller=docente&action=index&error=update_failed');
            }
        } else {
            header('Location: index.php?controller=docente&action=index');
        }
    }

    public function delete() {
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID de docente no especificado.');
        $usuario_id = isset($_GET['usuario_id']) ? $_GET['usuario_id'] : die('ERROR: ID de usuario no especificado.');

        include_once 'config/database.php';
        include_once 'models/Docente.php';

        $database = new Database();
        $db = $database->connect();
        $docente = new Docente($db);

        if ($docente->delete($usuario_id)) {
            header('Location: index.php?controller=docente&action=index&success=deleted');
        } else {
            header('Location: index.php?controller=docente&action=index&error=delete_failed');
        }
    }
}
?>