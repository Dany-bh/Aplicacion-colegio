<?php
class Area {
    private $conn;
    private $table = 'areas';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = 'SELECT id, nombre FROM ' . $this->table . ' ORDER BY nombre ASC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getCursosByDocente($docente_id) {
        $query = 'SELECT DISTINCT a.id, a.nombre 
                  FROM ' . $this->table . ' a
                  JOIN docentes_areas da ON a.id = da.area_id
                  WHERE da.docente_id = :docente_id
                  ORDER BY a.nombre ASC';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':docente_id', $docente_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readByGrupo($grupo_id) {
        $query = 'SELECT DISTINCT a.id, a.nombre 
                  FROM ' . $this->table . ' a
                  JOIN docentes_areas da ON a.id = da.area_id
                  WHERE da.grupo_id = :grupo_id
                  ORDER BY a.nombre ASC';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':grupo_id', $grupo_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>