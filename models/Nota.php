<?php
class Nota {
    private $conn;
    private $table = 'notas';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAlumnosByGrupo($grupo_id) {
        $query = 'SELECT id, nombres, apellido_paterno, apellido_materno FROM alumnos WHERE grupo_id = :grupo_id ORDER BY apellido_paterno, apellido_materno, nombres';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':grupo_id', $grupo_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getExistingNotas($grupo_id, $area_id, $bimestre) {
        $query = 'SELECT n.alumno_id, n.nota FROM ' . $this->table . ' n JOIN alumnos al ON n.alumno_id = al.id WHERE al.grupo_id = :grupo_id AND n.area_id = :area_id AND n.bimestre = :bimestre';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':grupo_id', $grupo_id);
        $stmt->bindParam(':area_id', $area_id);
        $stmt->bindParam(':bimestre', $bimestre);
        $stmt->execute();

        $notas = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $notas[$row['alumno_id']] = $row['nota'];
        }
        return $notas;
    }

    public function saveNotas($notas, $area_id, $bimestre, $docente_id) {
        if (empty($notas)) {
            return true; // No hay notas que guardar
        }

        $this->conn->beginTransaction();

        try {
            // 1. Borrar notas existentes de forma segura
            $alumnos_ids = array_keys($notas);
            if (!empty($alumnos_ids)) {
                $placeholders = implode(',', array_fill(0, count($alumnos_ids), '?'));
                
                $delete_query = 'DELETE FROM ' . $this->table . ' WHERE area_id = ? AND bimestre = ? AND alumno_id IN (' . $placeholders . ')';
                $stmt_delete = $this->conn->prepare($delete_query);

                $params = array_merge([$area_id, $bimestre], $alumnos_ids);
                $stmt_delete->execute($params);
            }

            // 2. Insertar los nuevos registros
            $insert_query = 'INSERT INTO ' . $this->table . ' (alumno_id, area_id, nota, bimestre, docente_id) VALUES (?, ?, ?, ?, ?)';
            $stmt_insert = $this->conn->prepare($insert_query);

            foreach ($notas as $alumno_id => $nota) {
                // Solo guardar si la nota no está vacía y es un número.
                if ($nota !== '' && is_numeric($nota)) { 
                     $stmt_insert->execute([$alumno_id, $area_id, $nota, $bimestre, $docente_id]);
                }
            }

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            // Se puede registrar el error para depuración, ej: error_log($e->getMessage());
            return false;
        }
    }

    public function getGradeReport($docente_id, $filters = []) {
        $query = 'SELECT 
                    n.nota, 
                    n.bimestre,
                    a.nombre as area_nombre,
                    al.id as alumno_id,
                    al.nombres,
                    al.apellido_paterno,
                    al.apellido_materno,
                    al.dni
                  FROM ' . $this->table . ' n
                  JOIN alumnos al ON n.alumno_id = al.id
                  JOIN grupos g ON al.grupo_id = g.id
                  JOIN areas a ON n.area_id = a.id
                  WHERE g.docente_id = :docente_id';

        $params = [':docente_id' => $docente_id];

        if (!empty($filters['area_id'])) {
            $query .= ' AND n.area_id = :area_id';
            $params[':area_id'] = $filters['area_id'];
        }

        if (!empty($filters['dni'])) {
            $query .= ' AND al.dni LIKE :dni';
            $params[':dni'] = '%' . $filters['dni'] . '%';
        }

        $query .= ' ORDER BY al.apellido_paterno, al.apellido_materno, al.nombres, a.nombre, n.bimestre';

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);

        $report = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $report[$row['alumno_id']]['nombre_completo'] = $row['apellido_paterno'] . ' ' . $row['apellido_materno'] . ', ' . $row['nombres'];
            $report[$row['alumno_id']]['dni'] = $row['dni'];
            $report[$row['alumno_id']]['cursos'][$row['area_nombre']][] = [
                'bimestre' => $row['bimestre'],
                'nota' => $row['nota']
            ];
        }
        return $report;
    }

    public function readByAlumnoId($alumno_id) {
        $query = 'SELECT n.nota, n.bimestre, a.nombre as area_nombre FROM ' . $this->table . ' n LEFT JOIN areas a ON n.area_id = a.id WHERE n.alumno_id = :alumno_id ORDER BY n.bimestre ASC, a.nombre ASC';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':alumno_id', $alumno_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAdminNotesReport($filters = []) {
        $sql = 'SELECT
                    n.nota,
                    n.bimestre,
                    ar.nombre as area_nombre,
                    al.dni,
                    al.nombres,
                    al.apellido_paterno,
                    al.apellido_materno,
                    g.nombre as grado,
                    s.nombre as seccion
                FROM ' . $this->table . ' n
                JOIN alumnos al ON n.alumno_id = al.id
                JOIN areas ar ON n.area_id = ar.id
                JOIN grupos gr ON al.grupo_id = gr.id
                JOIN grados g ON gr.grado_id = g.id
                JOIN secciones s ON gr.seccion_id = s.id
                WHERE 1=1';
        
        $params = [];

        if (!empty($filters['grupo_id'])) {
            $sql .= ' AND al.grupo_id = :grupo_id';
            $params[':grupo_id'] = $filters['grupo_id'];
        }

        if (!empty($filters['area_id'])) {
            $sql .= ' AND n.area_id = :area_id';
            $params[':area_id'] = $filters['area_id'];
        }

        if (!empty($filters['bimestre'])) {
            $sql .= ' AND n.bimestre = :bimestre';
            $params[':bimestre'] = $filters['bimestre'];
        }

        if (!empty($filters['search_term'])) {
            $sql .= " AND (al.dni LIKE :search_term OR CONCAT(al.nombres, ' ', al.apellido_paterno, ' ', al.apellido_materno) LIKE :search_term)";
            $params[':search_term'] = '%' . $filters['search_term'] . '%';
        }

        $sql .= ' ORDER BY g.nombre ASC, s.nombre ASC, ar.nombre ASC, n.bimestre ASC, al.apellido_paterno ASC';
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>