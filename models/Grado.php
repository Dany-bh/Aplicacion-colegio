<?php
class Grado {
    private $conn;
    private $table = 'grados';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = 'SELECT id, nombre FROM ' . $this->table . ' ORDER BY id ASC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>