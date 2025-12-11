<?php
class Grupo {
    private $conn;
    private $table = 'grupos';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = 'SELECT 
            g.id,
            gr.nombre as grado,
            s.nombre as seccion,
            a.nombre as anio,
            d.nombres as docente_nombres,
            d.apellido_paterno as docente_ap_paterno
        FROM
            ' . $this->table . ' g
        LEFT JOIN
            grados gr ON g.grado_id = gr.id
        LEFT JOIN
            secciones s ON g.seccion_id = s.id
        LEFT JOIN
            anios a ON g.anio_id = a.id
        LEFT JOIN
            docentes d ON g.docente_id = d.id
        ORDER BY
            g.id ASC';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create($data) {
        $query = 'INSERT INTO ' . $this->table . ' (grado_id, seccion_id, anio_id, docente_id) VALUES (:grado_id, :seccion_id, :anio_id, :docente_id)';
        $stmt = $this->conn->prepare($query);

        try {
            $stmt->execute([
                ':grado_id' => $data['grado_id'],
                ':seccion_id' => $data['seccion_id'],
                ':anio_id' => $data['anio_id'],
                ':docente_id' => $data['docente_id']
            ]);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            // Se podría loggear el error $e->getMessage()
            return false;
        }
    }

    public function readOne($id) {
        $query = 'SELECT 
            g.id,
            g.grado_id,
            g.seccion_id,
            g.anio_id,
            g.docente_id,
            gr.nombre as grado,
            s.nombre as seccion,
            a.nombre as anio,
            d.nombres as docente_nombres,
            d.apellido_paterno as docente_ap_paterno
        FROM
            ' . $this->table . ' g
        LEFT JOIN
            grados gr ON g.grado_id = gr.id
        LEFT JOIN
            secciones s ON g.seccion_id = s.id
        LEFT JOIN
            anios a ON g.anio_id = a.id
        LEFT JOIN
            docentes d ON g.docente_id = d.id
        WHERE
            g.id = :id
        LIMIT 1';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($data) {
        $query = 'UPDATE ' . $this->table . ' SET
            grado_id = :grado_id,
            seccion_id = :seccion_id,
            anio_id = :anio_id,
            docente_id = :docente_id
        WHERE
            id = :id';
        
        $stmt = $this->conn->prepare($query);

        try {
            $stmt->execute([
                ':grado_id' => $data['grado_id'],
                ':seccion_id' => $data['seccion_id'],
                ':anio_id' => $data['anio_id'],
                ':docente_id' => $data['docente_id'],
                ':id' => $data['id']
            ]);
            return true;
        } catch (PDOException $e) {
            // Log error $e->getMessage()
            return false;
        }
    }

    public function delete($id) {
        // 1. Verificar si hay alumnos en el grupo
        $query_check_alumnos = 'SELECT COUNT(id) as count FROM alumnos WHERE grupo_id = :id';
        $stmt_check_alumnos = $this->conn->prepare($query_check_alumnos);
        $stmt_check_alumnos->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_check_alumnos->execute();
        if ($stmt_check_alumnos->fetchColumn() > 0) {
            return false; // Hay alumnos, no se puede borrar
        }

        // 2. Verificar si hay cursos asignados en docentes_areas
        $query_check_areas = 'SELECT COUNT(id) as count FROM docentes_areas WHERE grupo_id = :id';
        $stmt_check_areas = $this->conn->prepare($query_check_areas);
        $stmt_check_areas->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_check_areas->execute();
        if ($stmt_check_areas->fetchColumn() > 0) {
            // Podríamos querer eliminar estas asociaciones, pero por seguridad, lo evitamos.
            // Para eliminar, se necesitaría una lógica como:
            // $query_delete_areas = 'DELETE FROM docentes_areas WHERE grupo_id = :id';
            // ... ejecutar eso primero
            return false; // Hay cursos, no se puede borrar
        }
        
        // 3. Si no hay dependencias, proceder a eliminar el grupo
        $query_delete_grupo = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query_delete_grupo);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log error $e->getMessage()
            return false;
        }
    }
}
?>