<?php
// Controllers para peticiones AJAX que retornan JSON
class ApiController {

    public function getGrupoDetails() {
        header('Content-Type: application/json');
        
        $grupo_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($grupo_id === 0) {
            echo json_encode(['error' => 'ID de grupo no válido.']);
            return;
        }

        // Incluir archivos necesarios
        include_once 'config/database.php';
        include_once 'models/Grupo.php'; // Reusaremos el modelo Grupo
        include_once 'models/Alumno.php'; // Para contar alumnos

        // Instanciar DB
        $database = new Database();
        $db = $database->connect();

        // Obtener detalles del docente y cursos
        // (Esto requiere agregar nuevos métodos a los modelos)
        $grupo = new Grupo($db);
        $docente_y_cursos = $this->getDocenteAndCursosForGrupo($db, $grupo_id);
        
        // Obtener conteo de alumnos
        $alumno = new Alumno($db);
        $num_alumnos = $this->getStudentCountForGrupo($db, $grupo_id);
        $vacantes = 30 - $num_alumnos;

        $response = [
            'docente' => $docente_y_cursos['docente'],
            'cursos' => $docente_y_cursos['cursos'],
            'vacantes' => $vacantes > 0 ? $vacantes : 0
        ];

        echo json_encode($response);
    }

    private function getDocenteAndCursosForGrupo($db, $grupo_id) {
        // Docente principal del grupo
        $query_docente = 'SELECT d.nombres, d.apellido_paterno FROM docentes d JOIN grupos g ON d.id = g.docente_id WHERE g.id = ?';
        $stmt_docente = $db->prepare($query_docente);
        $stmt_docente->execute([$grupo_id]);
        $docente_row = $stmt_docente->fetch(PDO::FETCH_ASSOC);
        $docente_nombre = $docente_row ? $docente_row['nombres'] . ' ' . $docente_row['apellido_paterno'] : 'No asignado';

        // Cursos asociados al grupo (vía horarios o docentes_areas)
        // Usaremos docentes_areas ya que es más directo que horarios
        $query_cursos = 'SELECT ar.nombre FROM areas ar JOIN docentes_areas da ON ar.id = da.area_id WHERE da.grupo_id = ?';
        $stmt_cursos = $db->prepare($query_cursos);
        $stmt_cursos->execute([$grupo_id]);
        $cursos_rows = $stmt_cursos->fetchAll(PDO::FETCH_ASSOC);
        $cursos = array_column($cursos_rows, 'nombre');

        return ['docente' => $docente_nombre, 'cursos' => $cursos];
    }

    private function getStudentCountForGrupo($db, $grupo_id) {
        $query = 'SELECT COUNT(id) as count FROM alumnos WHERE grupo_id = ?';
        $stmt = $db->prepare($query);
        $stmt->execute([$grupo_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['count'] : 0;
    }
}
?>