<?php
class User {
    private $conn;
    private $table = 'usuarios';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function findByUsername($username) {
        $query = 'SELECT id, usuario, password, rol, nombres, apellidos FROM ' . $this->table . ' WHERE usuario = :username LIMIT 1';
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

     public function findDocenteIdByUserId($user_id) {
        $query = 'SELECT id FROM docentes WHERE usuario_id = :user_id LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : null;
    }

    public function create($data) {
        $query = 'INSERT INTO ' . $this->table . ' (nombres, apellidos, correo, usuario, password, rol) VALUES (:nombres, :apellidos, :correo, :usuario, :password, :rol)';
        
        $stmt = $this->conn->prepare($query);

        // Hash de la contraseña
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);

        try {
            $stmt->execute([
                ':nombres' => $data['nombres'],
                ':apellidos' => $data['apellidos'],
                ':correo' => $data['correo'],
                ':usuario' => $data['usuario'],
                ':password' => $hashed_password,
                ':rol' => $data['rol']
            ]);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            // Podríamos loggear el error $e->getMessage()
            return false;
        }
    }

    public function update($data) {
        $query = 'UPDATE ' . $this->table . ' SET nombres = :nombres, apellidos = :apellidos, correo = :correo, usuario = :usuario';
        
        if (!empty($data['password'])) {
            $query .= ', password = :password';
        }
        $query .= ' WHERE id = :id';

        $stmt = $this->conn->prepare($query);

        $params = [
            ':nombres' => $data['nombres'],
            ':apellidos' => $data['apellidos'],
            ':correo' => $data['correo'],
            ':usuario' => $data['usuario'],
            ':id' => $data['id']
        ];

        if (!empty($data['password'])) {
            $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        try {
            return $stmt->execute($params);
        } catch (PDOException $e) {
            // Podríamos loggear el error $e->getMessage()
            return false;
        }
    }

    public function updateStatus($id, $estado) {
        $query = 'UPDATE ' . $this->table . ' SET estado = :estado WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':id', $id);
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log error
            return false;
        }
    }

    public function findAlumnosByPadre($user_id) {
        $query = 'SELECT alumno_id FROM padres_alumnos WHERE padre_id = :user_id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN); // Devuelve solo los valores de la columna alumno_id
    }

    public function readAll() {
        $query = 'SELECT id, usuario, rol, estado FROM ' . $this->table . ' ORDER BY usuario ASC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>