<?php
class LoginController {

    public function index() {
        // Carga la vista del formulario de login
        // No necesita datos del modelo por ahora
        include_once 'views/login/index.php';
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            include_once 'config/database.php';
            include_once 'models/User.php';

            $database = new Database();
            $db = $database->connect();
            $user_model = new User($db);

            $username = $_POST['usuario'];
            $password = $_POST['password'];

            $user = $user_model->findByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                // Contraseña correcta, iniciar sesión
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['rol'];
                $_SESSION['user_name'] = $user['nombres'] . ' ' . $user['apellidos'];

                // Si es docente, buscar su ID de docente
                if ($user['rol'] === 'DOCENTE') {
                    $_SESSION['docente_id'] = $user_model->findDocenteIdByUserId($user['id']);
                }

                header('Location: index.php?controller=default&action=index');
                exit;
            }
        }
        // Si falla el login o no es POST, redirigir a login con error
        header('Location: index.php?controller=login&action=index&error=1');
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: index.php?controller=login&action=index');
        exit;
    }
}
?>