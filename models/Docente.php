<?php
class Docente {
    private $conn;
    private $table = 'docentes';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = 'SELECT
            d.id,
            d.nombres,
            d.apellido_paterno,
            d.apellido_materno,
            d.documento,
            d.telefono,
            d.direccion,
            d.anio_ingreso,
            u.usuario,
            u.correo,
            u.estado,
            u.id as usuario_id
        FROM
            ' . $this->table . ' d
        LEFT JOIN
            usuarios u ON d.usuario_id = u.id
        WHERE
            u.estado = \'ACTIVO\'
        ORDER BY
            d.apellido_paterno ASC';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function findIdByGrupoAndArea($grupo_id, $area_id) {
        $query = 'SELECT docente_id FROM docentes_areas WHERE grupo_id = :grupo_id AND area_id = :area_id LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':grupo_id', $grupo_id);
        $stmt->bindParam(':area_id', $area_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['docente_id'] : null;
    }

    public function create($data) {
        $query = 'INSERT INTO ' . $this->table . ' (usuario_id, nombres, apellido_paterno, apellido_materno, documento, telefono, direccion, anio_ingreso) VALUES (:usuario_id, :nombres, :apellido_paterno, :apellido_materno, :documento, :telefono, :direccion, :anio_ingreso)';
        
        $stmt = $this->conn->prepare($query);

        try {
            $stmt->execute([
                ':usuario_id' => $data['usuario_id'],
                ':nombres' => $data['nombres'],
                ':apellido_paterno' => $data['apellido_paterno'],
                ':apellido_materno' => $data['apellido_materno'],
                ':documento' => $data['documento'],
                ':telefono' => $data['telefono'],
                ':direccion' => $data['direccion'],
                ':anio_ingreso' => $data['anio_ingreso']
            ]);
            return true;
        } catch (PDOException $e) {
            // Podríamos loggear el error $e->getMessage()
            return false;
        }
    }

    public function readOne($id) {
        $query = 'SELECT
            d.id,
            d.usuario_id,
            d.nombres,
            d.apellido_paterno,
            d.apellido_materno,
            d.documento,
            d.telefono,
            d.direccion,
            d.anio_ingreso,
            u.usuario,
            u.correo
        FROM
            ' . $this->table . ' d
        LEFT JOIN
            usuarios u ON d.usuario_id = u.id
        WHERE
            d.id = :id
        LIMIT 1';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($data) {
        $query = 'UPDATE ' . $this->table . ' SET nombres = :nombres, apellido_paterno = :apellido_paterno, apellido_materno = :apellido_materno, documento = :documento, telefono = :telefono, direccion = :direccion, anio_ingreso = :anio_ingreso WHERE id = :id';
        
        $stmt = $this->conn->prepare($query);

        try {
            $stmt->execute([
                ':nombres' => $data['nombres'],
                ':apellido_paterno' => $data['apellido_paterno'],
                ':apellido_materno' => $data['apellido_materno'],
                ':documento' => $data['documento'],
                ':telefono' => $data['telefono'],
                ':direccion' => $data['direccion'],
                ':anio_ingreso' => $data['anio_ingreso'],
                ':id' => $data['id']
            ]);
            return true;
        } catch (PDOException $e) {
            // Podríamos loggear el error $e->getMessage()
            return false;
        }
    }

    public function delete($usuario_id) {
        // En lugar de borrar, cambiamos el estado del usuario asociado a INACTIVO
        $query = 'UPDATE usuarios SET estado = \'INACTIVO\' WHERE id = :usuario_id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log error
            return false;
        }
    }

    public function getUnassigned() {
        $query = 'SELECT id, nombres, apellido_paterno, apellido_materno FROM ' . $this->table . ' WHERE usuario_id IS NULL OR usuario_id = 0 ORDER BY apellido_paterno ASC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function assignUser($docente_id, $user_id) {
        $query = 'UPDATE ' . $this->table . ' SET usuario_id = :user_id WHERE id = :docente_id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':docente_id', $docente_id);
        
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log error
            return false;
        }
    }
}
?>