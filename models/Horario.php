<?php
class Horario {
    private $conn;
    private $table = 'horarios';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readByGrupoId($grupo_id) {
        $query = 'SELECT h.dia, h.hora_inicio, h.hora_fin, a.nombre as area_nombre FROM ' . $this->table . ' h LEFT JOIN areas a ON h.area_id = a.id WHERE h.grupo_id = :grupo_id ORDER BY FIELD(h.dia, \'Lunes\', \'Martes\', \'Miércoles\', \'Jueves\', \'Viernes\', \'Sábado\', \'Domingo\'), h.hora_inicio ASC';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':grupo_id', $grupo_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>