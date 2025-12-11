<?php
class Asistencia {
    private $conn;
    private $table = 'asistencias';

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
    
    public function getExistingAsistencia($grupo_id, $fecha) {
        $query = 'SELECT a.alumno_id, a.estado FROM ' . $this->table . ' a JOIN alumnos al ON a.alumno_id = al.id WHERE al.grupo_id = :grupo_id AND a.fecha = :fecha';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':grupo_id', $grupo_id);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
        
        $asistencias = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $asistencias[$row['alumno_id']] = $row['estado'];
        }
        return $asistencias;
    }

        public function saveAsistencia($asistencias, $fecha, $grupo_id) {
            $this->conn->beginTransaction();
            try {
                // Primero, borramos las asistencias existentes para esa fecha y grupo para evitar duplicados
                $delete_query = 'DELETE a FROM ' . $this->table . ' a JOIN alumnos al ON a.alumno_id = al.id WHERE a.fecha = :fecha AND al.grupo_id = :grupo_id';
                $stmt_delete = $this->conn->prepare($delete_query);
                $stmt_delete->bindParam(':fecha', $fecha);
                $stmt_delete->bindParam(':grupo_id', $grupo_id);
                $stmt_delete->execute();
        
                // Ahora, insertamos los nuevos registros
                $insert_query = 'INSERT INTO ' . $this->table . ' (alumno_id, fecha, estado) VALUES (:alumno_id, :fecha, :estado)';
                $stmt_insert = $this->conn->prepare($insert_query);
        
                foreach ($asistencias as $alumno_id => $estado) {
                    $stmt_insert->execute([
                        ':alumno_id' => $alumno_id,
                        ':fecha' => $fecha,
                        ':estado' => $estado
                    ]);
                }

                $this->conn->commit();
                return true;

            } catch (PDOException $e) {
                $this->conn->rollBack();
                // log $e->getMessage();
                return false;
            }
        }

    

        public function getFullAttendanceReport($docente_id) {
            $query = 'SELECT 
                        al.id as alumno_id, 
                        al.nombres, 
                        al.apellido_paterno, 
                        al.apellido_materno,
                        a.fecha,
                        a.estado
                      FROM ' . $this->table . ' a
                      RIGHT JOIN alumnos al ON a.alumno_id = al.id
                      JOIN grupos g ON al.grupo_id = g.id
                      WHERE g.docente_id = :docente_id
                      ORDER BY al.apellido_paterno, al.apellido_materno, al.nombres, a.fecha DESC';
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':docente_id', $docente_id);
            $stmt->execute();

            $report = [];
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $report[$row['alumno_id']]['nombre_completo'] = $row['apellido_paterno'] . ' ' . $row['apellido_materno'] . ', ' . $row['nombres'];
                if ($row['fecha']) {
                    $report[$row['alumno_id']]['registros'][] = [
                        'fecha' => $row['fecha'],
                        'estado' => $row['estado']
                    ];
                }
            }
            return $report;
        }

        public function readByAlumnoId($alumno_id) {

            $query = 'SELECT fecha, estado, observacion FROM ' . $this->table . ' WHERE alumno_id = :alumno_id ORDER BY fecha DESC';

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':alumno_id', $alumno_id);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }

    public function getAdminAttendanceReport($filters = []) {
        $sql = 'SELECT
                    a.fecha,
                    a.estado,
                    a.observacion,
                    al.dni,
                    al.nombres,
                    al.apellido_paterno,
                    al.apellido_materno,
                    g.nombre as grado,
                    s.nombre as seccion
                FROM ' . $this->table . ' a
                JOIN alumnos al ON a.alumno_id = al.id
                JOIN grupos gr ON al.grupo_id = gr.id
                JOIN grados g ON gr.grado_id = g.id
                JOIN secciones s ON gr.seccion_id = s.id
                WHERE 1=1';

        $params = [];

        if (!empty($filters['grupo_id'])) {
            $sql .= ' AND al.grupo_id = :grupo_id';
            $params[':grupo_id'] = $filters['grupo_id'];
        }

        if (!empty($filters['fecha'])) {
            $sql .= ' AND a.fecha = :fecha';
            $params[':fecha'] = $filters['fecha'];
        }

        if (!empty($filters['search_term'])) {
            $sql .= " AND (al.dni LIKE :search_term OR CONCAT(al.nombres, ' ', al.apellido_paterno, ' ', al.apellido_materno) LIKE :search_term)";
            $params[':search_term'] = '%' . $filters['search_term'] . '%';
        }

        $sql .= ' ORDER BY a.fecha DESC, g.nombre ASC, s.nombre ASC, al.apellido_paterno ASC';
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    }
?>