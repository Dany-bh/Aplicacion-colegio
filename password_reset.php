<?php
// Este script restablece todas las contraseñas de los usuarios a "123456"

// Incluir la configuración de la base de datos
include_once 'config/database.php';

echo "<h1>Restableciendo Contraseñas...</h1>";

try {
    // Conectar a la base de datos
    $database = new Database();
    $db = $database->connect();

    if ($db) {
        echo "<p>Conexión a la base de datos exitosa.</p>";

        // Nueva contraseña
        $new_password = "123456";

        // Generar el hash BCRYPT, que es lo que el sistema espera
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        if ($hashed_password) {
            echo "<p>Hash para '123456' generado: " . htmlspecialchars($hashed_password) . "</p>";

            // Preparar la consulta para actualizar todos los usuarios
            $query = 'UPDATE usuarios SET password = :password';
            $stmt = $db->prepare($query);
            $stmt->bindParam(':password', $hashed_password);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                $count = $stmt->rowCount();
                echo "<h2>¡Éxito!</h2>";
                echo "<p>Se han actualizado las contraseñas de " . $count . " usuarios a '123456'.</p>";
                echo "<p style='color:green;'>Ahora puedes intentar iniciar sesión de nuevo.</p>";
                echo "<p style='font-weight:bold; color:red;'>IMPORTANTE: Por favor, elimina este archivo (password_reset.php) de tu servidor ahora.</p>";
            } else {
                echo "<h2>Error</h2>";
                echo "<p style='color:red;'>La consulta SQL para actualizar las contraseñas falló.</p>";
            }
        } else {
            echo "<h2>Error</h2>";
            echo "<p style='color:red;'>Falló la generación del hash de la contraseña.</p>";
        }
    } else {
        echo "<h2>Error</h2>";
        echo "<p style='color:red;'>No se pudo conectar a la base de datos. Revisa tus credenciales en config/database.php.</p>";
    }

} catch (Exception $e) {
    echo "<h2>Error Crítico</h2>";
    echo "<p style='color:red;'>Ocurrió una excepción: " . $e->getMessage() . "</p>";
}
?>