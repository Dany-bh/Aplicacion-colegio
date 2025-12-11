<?php
class Seccion {
    private $conn;
    private $table = 'secciones';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = 'SELECT id, nombre FROM ' . $this->table . ' ORDER BY nombre ASC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function findOrCreate($nombre) {
        // Buscar por nombre
        $query_find = 'SELECT id FROM ' . $this->table . ' WHERE nombre = :nombre LIMIT 1';
        $stmt_find = $this->conn->prepare($query_find);
        $stmt_find->bindParam(':nombre', $nombre);
        $stmt_find->execute();

        if ($stmt_find->rowCount() > 0) {
            $row = $stmt_find->fetch(PDO::FETCH_ASSOC);
            return $row['id'];
        }

        // Si no se encuentra, crear
        $query_create = 'INSERT INTO ' . $this->table . ' (nombre) VALUES (:nombre)';
        $stmt_create = $this->conn->prepare($query_create);
        
        try {
            $stmt_create->execute([':nombre' => $nombre]);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            // Log error
            return false;
        }
    }
}
?>