<?php
class NotaController {

    public function index() {
        // Cargar los modelos necesarios y la conexión a la DB
        include_once 'config/database.php';
        include_once 'models/Grupo.php';
        include_once 'models/Area.php';
        include_once 'models/Nota.php';

        $database = new Database();
        $db = $database->connect();
        
        // Si es admin, va a una vista especial de solo lectura con filtros
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'ADMIN') {
            $grupo_model = new Grupo($db);
            $grupos = $grupo_model->read()->fetchAll(PDO::FETCH_ASSOC);

            $area_model = new Area($db);
            $areas = $area_model->read()->fetchAll(PDO::FETCH_ASSOC);

            $nota_model = new Nota($db);
            $notas = [];

            $filters = [
                'grupo_id' => $_GET['grupo_id'] ?? null,
                'area_id' => $_GET['area_id'] ?? null,
                'bimestre' => $_GET['bimestre'] ?? null,
                'search_term' => $_GET['search_term'] ?? null,
            ];

            // Solo buscar si se proporciona al menos un filtro
            if (!empty($filters['grupo_id']) || !empty($filters['area_id']) || !empty($filters['bimestre']) || !empty($filters['search_term'])) {
                 $notas = $nota_model->getAdminNotesReport($filters);
            }
           
            include_once 'views/notas/admin_view.php';
            return;
        }

        // Comportamiento original para otros roles
        $grupo = new Grupo($db);
        $stmt_grupos = $grupo->read();
        $grupos = $stmt_grupos->fetchAll(PDO::FETCH_ASSOC);
        
        $area = new Area($db);
        $stmt_areas = $area->read();
        $areas = $stmt_areas->fetchAll(PDO::FETCH_ASSOC);

        // Cargar la vista para seleccionar
        include_once 'views/notas/index.php';
    }

    public function listAlumnos() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $grupo_id = $_POST['grupo_id'];
            $area_id = $_POST['area_id'];
            $bimestre = $_POST['bimestre'];

            include_once 'config/database.php';
            include_once 'models/Nota.php';

            $database = new Database();
            $db = $database->connect();

            $nota_model = new Nota($db);
            $alumnos = $nota_model->getAlumnosByGrupo($grupo_id);
            $notas_existentes = $nota_model->getExistingNotas($grupo_id, $area_id, $bimestre);

            // Podríamos pasar también los nombres del grupo, area, etc., para mejor UI
            
            include_once 'views/notas/list.php';
        } else {
            header('Location: index.php?controller=nota&action=index');
        }
    }

    public function report() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'DOCENTE' || !isset($_SESSION['docente_id'])) {
            header('Location: index.php');
            exit;
        }

        include_once 'config/database.php';
        include_once 'models/Nota.php';
        include_once 'models/Area.php';

        $database = new Database();
        $db = $database->connect();
        $nota_model = new Nota($db);
        $area_model = new Area($db);

        $docente_id = $_SESSION['docente_id'];

        // Obtener los cursos que enseña el docente para el filtro
        $cursos_impartidos = $area_model->getCursosByDocente($docente_id);

        // Definir filtros a partir de la solicitud (puede ser GET o POST)
        $filters = [
            'area_id' => $_REQUEST['area_id'] ?? null,
            'dni' => $_REQUEST['dni'] ?? null
        ];

        // Obtener el reporte de notas con los filtros aplicados
        $report_data = $nota_model->getGradeReport($docente_id, $filters);

        include_once 'views/notas/report.php';
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'ADMIN') {
                header('Location: index.php?controller=nota&action=index&error=permission_denied');
                exit;
            }
            include_once 'config/database.php';
            include_once 'models/Nota.php';
            include_once 'models/Docente.php';

            $database = new Database();
            $db = $database->connect();
            $nota_model = new Nota($db);
            $docente_model = new Docente($db);

            $notas = $_POST['notas'];
            $area_id = $_POST['area_id'];
            $bimestre = $_POST['bimestre'];
            $grupo_id = $_POST['grupo_id']; // Necesario para el admin
            $docente_id = null;

            // Determinar el ID del docente basado en el rol
            if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'DOCENTE') {
                $docente_id = isset($_SESSION['docente_id']) ? $_SESSION['docente_id'] : null;        
            } elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'ADMIN') {
                // Para el admin, buscamos qué docente enseña esa área en ese grupo
                $docente_id = $docente_model->findIdByGrupoAndArea($grupo_id, $area_id);
            }

            // Solo proceder si tenemos un ID de docente válido
            if ($docente_id && $nota_model->saveNotas($notas, $area_id, $bimestre, $docente_id)) {    
                header('Location: index.php?controller=nota&action=index&success=true');
            } else {
                // El error puede ser por ID de docente nulo o por fallo al guardar
                header('Location: index.php?controller=nota&action=index&error=true');
            }

        } else {
            header('Location: index.php?controller=nota&action=index');
        }
    }
}
?>