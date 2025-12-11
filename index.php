<?php
// Forzar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar la sesión
session_start();

// --- Lógica de Enrutamiento ---

// 1. Definir rutas públicas que no requieren login
$public_routes = [
    'login' => ['index', 'authenticate', 'logout'], // logout también debe ser público
    'api'   => ['getGrupoDetails'] // La API es pública en este caso
];

// 2. Obtener controlador y acción
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'default';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// 3. Lógica de protección de rutas
$is_public = isset($public_routes[$controller]) && in_array($action, $public_routes[$controller]);        

if (!isset($_SESSION['user_id']) && !$is_public) {
    // Si el usuario no está logueado y la ruta no es pública, redirigir al login
    header('Location: index.php?controller=login&action=index');
    exit;
}

// 4. Manejo de la ruta por defecto
if ($controller === 'default') {
    if (isset($_SESSION['user_id'])) {
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'PADRE') {
            header('Location: index.php?controller=padre&action=index');
        } else {
            // Si está logueado y no es PADRE, ir a la página principal del sistema (ej. aulas)
            header('Location: index.php?controller=grupo&action=index');
        }
    } else {
        // Si no, ir al login
        header('Location: index.php?controller=login&action=index');
    }
    exit;
}

// --- Carga del Controlador ---

$controllerName = ucfirst($controller) . 'Controller';
$controllerFile = 'controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    if (class_exists($controllerName)) {
        $controllerInstance = new $controllerName();
        if (method_exists($controllerInstance, $action)) {
            $controllerInstance->$action();
        } else {
            die("Error: La acción '{$action}' no existe en '{$controllerName}'.");
        }
    } else {
        die("Error: La clase '{$controllerName}' no existe.");
    }
} else {
    die("Error: El controlador '{$controllerFile}' no existe.");
}
?>