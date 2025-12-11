<?php
class Alumno {
    private $conn;
    private $table = 'alumnos';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = 'SELECT
            a.id,
            a.tipo_documento,
            a.dni,
            a.nombres,
            a.apellido_paterno,
            a.apellido_materno,
            a.fecha_nacimiento,
            a.direccion,
            a.padre,
            a.madre,
            a.apoderado,
            a.celular_apoderado,
            a.fecha_ingreso,
            g.id as grupo_id,
            gr.nombre as grado,
            s.nombre as seccion,
            an.nombre as anio,
            d.nombres as docente_nombres,
            d.apellido_paterno as docente_apellido_paterno
        FROM
            ' . $this->table . ' a
        LEFT JOIN
            grupos g ON a.grupo_id = g.id
        LEFT JOIN
            grados gr ON g.grado_id = gr.id
        LEFT JOIN
            secciones s ON g.seccion_id = s.id
        LEFT JOIN
            anios an ON g.anio_id = an.id
        LEFT JOIN
            docentes d ON g.docente_id = d.id
        ORDER BY
            a.apellido_paterno ASC';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create($data) {
        $query = 'INSERT INTO ' . $this->table . '
            (tipo_documento, dni, nombres, apellido_paterno, apellido_materno, fecha_nacimiento, direccion, padre, madre, apoderado, celular_apoderado, fecha_ingreso, grupo_id)
            VALUES
            (:tipo_documento, :dni, :nombres, :apellido_paterno, :apellido_materno, :fecha_nacimiento, :direccion, :padre, :madre, :apoderado, :celular_apoderado, CURDATE(), :grupo_id)';

        $stmt = $this->conn->prepare($query);

        // Limpiar y asignar datos
        foreach ($data as $key => $value) {
            $data[$key] = htmlspecialchars(strip_tags($value));
        }

        try {
            $stmt->execute([
                ':tipo_documento' => $data['tipo_documento'],
                ':dni' => $data['dni'],
                ':nombres' => $data['nombres'],
                ':apellido_paterno' => $data['apellido_paterno'],
                ':apellido_materno' => $data['apellido_materno'],
                ':fecha_nacimiento' => $data['fecha_nacimiento'],
                ':direccion' => $data['direccion'],
                ':padre' => $data['padre'],
                ':madre' => $data['madre'],
                ':apoderado' => $data['apoderado'],
                ':celular_apoderado' => $data['celular_apoderado'],
                ':grupo_id' => $data['grupo_id']
            ]);
            return true;
        } catch (PDOException $e) {
            // Se podría loggear el error $e->getMessage()
            return false;
        }
    }

    public function getStudentCountForGrupo($grupo_id) {
        $query = 'SELECT COUNT(id) as count FROM ' . $this->table . ' WHERE grupo_id = :grupo_id';        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':grupo_id', $grupo_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['count'] : 0;
    }

    public function readOne($id) {
        $query = 'SELECT
            a.id,
            a.tipo_documento,
            a.dni,
            a.nombres,
            a.apellido_paterno,
            a.apellido_materno,
            a.fecha_nacimiento,
            a.direccion,
            a.padre,
            a.madre,
            a.apoderado,
            a.celular_apoderado,
            a.fecha_ingreso,
            a.grupo_id,
            g.id as grupo_id_actual,
            gr.nombre as grado,
            s.nombre as seccion,
            an.nombre as anio,
            d.nombres as docente_nombres,
            d.apellido_paterno as docente_apellido_paterno
        FROM
            ' . $this->table . ' a
        LEFT JOIN
            grupos g ON a.grupo_id = g.id
        LEFT JOIN
            grados gr ON g.grado_id = gr.id
        LEFT JOIN
            secciones s ON g.seccion_id = s.id
        LEFT JOIN
            anios an ON g.anio_id = an.id
        LEFT JOIN
            docentes d ON g.docente_id = d.id
        WHERE
            a.id = :id
        LIMIT 1';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($data) {
        $query = 'UPDATE ' . $this->table . ' SET
            tipo_documento = :tipo_documento,
            dni = :dni,
            nombres = :nombres,
            apellido_paterno = :apellido_paterno,
            apellido_materno = :apellido_materno,
            fecha_nacimiento = :fecha_nacimiento,
            direccion = :direccion,
            padre = :padre,
            madre = :madre,
            apoderado = :apoderado,
            celular_apoderado = :celular_apoderado,
            grupo_id = :grupo_id
        WHERE
            id = :id';

        $stmt = $this->conn->prepare($query);

        foreach ($data as $key => $value) {
            $data[$key] = htmlspecialchars(strip_tags($value));
        }

        try {
            $stmt->execute([
                ':tipo_documento' => $data['tipo_documento'],
                ':dni' => $data['dni'],
                ':nombres' => $data['nombres'],
                ':apellido_paterno' => $data['apellido_paterno'],
                ':apellido_materno' => $data['apellido_materno'],
                ':fecha_nacimiento' => $data['fecha_nacimiento'],
                ':direccion' => $data['direccion'],
                ':padre' => $data['padre'],
                ':madre' => $data['madre'],
                ':apoderado' => $data['apoderado'],
                ':celular_apoderado' => $data['celular_apoderado'],
                ':grupo_id' => $data['grupo_id'],
                ':id' => $data['id']
            ]);
            return true;
        } catch (PDOException $e) {
            // Log error
            return false;
        }
    }

    public function delete($id) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log error
            return false;
        }
    }

    public function readByDocente($docente_id) {
        $query = 'SELECT
            a.id,
            a.tipo_documento,
            a.dni,
            a.nombres,
            a.apellido_paterno,
            a.apellido_materno,
            a.fecha_nacimiento,
            a.direccion,
            a.padre,
            a.madre,
            a.apoderado,
            a.celular_apoderado,
            a.fecha_ingreso,
            g.id as grupo_id,
            gr.nombre as grado,
            s.nombre as seccion,
            an.nombre as anio,
            d.nombres as docente_nombres,
            d.apellido_paterno as docente_apellido_paterno
        FROM
            ' . $this->table . ' a
        LEFT JOIN
            grupos g ON a.grupo_id = g.id
        LEFT JOIN
            grados gr ON g.grado_id = gr.id
        LEFT JOIN
            secciones s ON g.seccion_id = s.id
        LEFT JOIN
            anios an ON g.anio_id = an.id
        LEFT JOIN
            docentes d ON g.docente_id = d.id
        WHERE
            g.docente_id = :docente_id
        ORDER BY
            a.apellido_paterno ASC';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':docente_id', $docente_id);
        $stmt->execute();
        return $stmt;
    }

    public function readOneSimple($id) {
        $query = 'SELECT
            a.id,
            a.tipo_documento,
            a.dni,
            a.nombres,
            a.apellido_paterno,
            a.apellido_materno,
            a.fecha_nacimiento,
            a.direccion,
            a.padre,
            a.madre,
            a.apoderado,
            a.celular_apoderado,
            a.fecha_ingreso,
            a.grupo_id,
            g.id as grupo_id_actual,
            gr.nombre as grado,
            s.nombre as seccion,
            an.nombre as anio
        FROM
            ' . $this->table . ' a
        LEFT JOIN
            grupos g ON a.grupo_id = g.id
        LEFT JOIN
            grados gr ON g.grado_id = gr.id
        LEFT JOIN
            secciones s ON g.seccion_id = s.id
        LEFT JOIN
            anios an ON g.anio_id = an.id
        WHERE
            a.id = :id
        LIMIT 1';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function readByGrupo($grupo_id) {
        $query = 'SELECT
            a.id,
            a.dni,
            a.nombres,
            a.apellido_paterno,
            a.apellido_materno
        FROM
            ' . $this->table . ' a
        WHERE
            a.grupo_id = :grupo_id
        ORDER BY
            a.apellido_paterno ASC, a.apellido_materno ASC, a.nombres ASC';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':grupo_id', $grupo_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>