<?php
class PadreController {
    public function index() {
        // Incluir archivos necesarios
        include_once 'config/database.php';
        include_once 'models/User.php';
        include_once 'models/Alumno.php';
        include_once 'models/Asistencia.php';
        include_once 'models/Nota.php';
        include_once 'models/Horario.php'; // Modelo a crear

        $database = new Database();
        $db = $database->connect();

        $user_model = new User($db);
        $alumno_model = new Alumno($db);
        $asistencia_model = new Asistencia($db);
        $nota_model = new Nota($db);
        $horario_model = new Horario($db); // Instanciar el modelo de Horario

        $user_id = $_SESSION['user_id'] ?? null;
        $hijos_data = [];

        if ($user_id) {
            $alumnos_ids = $user_model->findAlumnosByPadre($user_id);
            
            foreach ($alumnos_ids as $alumno_id) {
                $alumno_info = $alumno_model->readOneSimple($alumno_id);
                if ($alumno_info) {
                    $alumno_info['asistencias'] = $asistencia_model->readByAlumnoId($alumno_id);
                    $alumno_info['notas'] = $nota_model->readByAlumnoId($alumno_id);
                    $alumno_info['horario'] = $horario_model->readByGrupoId($alumno_info['grupo_id']);
                    $hijos_data[] = $alumno_info;
                }
            }
        }

        include_once 'views/padre/index.php';
    }
}
?>