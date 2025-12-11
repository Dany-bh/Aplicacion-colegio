<?php
// Script para añadir la columna 'estado' a la tabla 'alumnos'

// Incluir la configuración de la base de datos
include_once 'config/database.php';

echo "<h1>Añadiendo columna 'estado' a la tabla 'alumnos'...</h1>";

try {
    // Conectar a la base de datos
    $database = new Database();
    $db = $database->connect();

    if ($db) {
        echo "<p>Conexión a la base de datos exitosa.</p>";

        // Verificar si la columna ya existe para evitar errores
        $check_query = $db->prepare("SHOW COLUMNS FROM alumnos LIKE 'estado'");
        $check_query->execute();

        if ($check_query->rowCount() > 0) {
            echo "<p style='color:orange;'>La columna 'estado' ya existe en la tabla 'alumnos'. No se requiere acción.</p>";
        } else {
            // Consulta para añadir la columna 'estado'
            $alter_query = 'ALTER TABLE alumnos ADD COLUMN estado ENUM(\'ACTIVO\', \'INACTIVO\') DEFAULT \'ACTIVO\';
            $stmt = $db->prepare($alter_query);

            if ($stmt->execute()) {
                echo "<h2>¡Éxito!</h2>";
                echo "<p>La columna 'estado' ha sido añadida a la tabla 'alumnos' con éxito.</p>";
                echo "<p style='color:green;'>Ahora el sistema debería funcionar correctamente.</p>";
                echo "<p style='font-weight:bold; color:red;'>IMPORTANTE: Por favor, elimina este archivo (add_estado_column_to_alumnos.php) de tu servidor ahora.</p>";
            } else {
                echo "<h2>Error</h2>";
                echo "<p style='color:red;'>Falló la consulta SQL para añadir la columna 'estado'.</p>";
            }
        }
    } else {
        echo "<h2>Error</h2>";
        echo "<p style='color:red;'>No se pudo conectar a la base de datos. Revisa tus credenciales en config/database.php.</p>";
    }

} catch (Exception $e) {
    echo "<h2>Error Crítico</h2>";
    echo "<p style='color:red;'>Ocurrió una excepción: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
