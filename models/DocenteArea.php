<?php
class DocenteArea {
    private $conn;
    private $table = 'docentes_areas';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($docente_id, $area_id, $grupo_id) {
        $query = 'INSERT INTO ' . $this->table . ' (docente_id, area_id, grupo_id) VALUES (:docente_id, :area_id, :grupo_id)';
        $stmt = $this->conn->prepare($query);

        try {
            $stmt->execute([
                ':docente_id' => $docente_id,
                ':area_id' => $area_id,
                ':grupo_id' => $grupo_id
            ]);
            return true;
        } catch (PDOException $e) {
            // Se podría loggear el error $e->getMessage()
            return false;
        }
    }
}
?>