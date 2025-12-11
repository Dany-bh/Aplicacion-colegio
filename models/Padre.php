<?php
class Padre {
    private $conn;
    private $table = 'padres_alumnos';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function assignStudent($user_id, $alumno_id) {
        $query = 'INSERT INTO ' . $this->table . ' (padre_id, alumno_id) VALUES (:padre_id, :alumno_id)';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':padre_id', $user_id);
        $stmt->bindParam(':alumno_id', $alumno_id);
        
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log error
            return false;
        }
    }
}
?>