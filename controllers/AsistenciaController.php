<?php
class AsistenciaController {

    public function index() {
        // Cargar los modelos necesarios y la conexión a la DB
        include_once 'config/database.php';
        include_once 'models/Grupo.php';
        include_once 'models/Asistencia.php';

        $database = new Database();
        $db = $database->connect();

        // Si es admin, va a una vista especial de solo lectura con filtros
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'ADMIN') {
            $grupo_model = new Grupo($db);
            $grupos = $grupo_model->read()->fetchAll(PDO::FETCH_ASSOC);

            $asistencia_model = new Asistencia($db);
            $asistencias = [];

            $filters = [
                'grupo_id' => $_GET['grupo_id'] ?? null,
                'fecha' => $_GET['fecha'] ?? null,
                'search_term' => $_GET['search_term'] ?? null,
            ];

            // Solo buscar si se proporciona al menos un filtro
            if (!empty($filters['grupo_id']) || !empty($filters['fecha']) || !empty($filters['search_term'])) {
                 $asistencias = $asistencia_model->getAdminAttendanceReport($filters);
            }
           
            include_once 'views/asistencia/admin_view.php';
            return;
        }

        // Comportamiento original para otros roles
        $grupo = new Grupo($db);
        $stmt = $grupo->read();
        $grupos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Cargar la vista para seleccionar el grupo y la fecha
        include_once 'views/asistencia/index.php';
    }

        public function listAlumnos() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $grupo_id = $_POST['grupo_id'];
                $fecha = $_POST['fecha'];
    
                include_once 'config/database.php';
                include_once 'models/Asistencia.php';
                include_once 'models/Grupo.php';
    
                $database = new Database();
                $db = $database->connect();
    
                $asistencia_model = new Asistencia($db);
                $alumnos = $asistencia_model->getAlumnosByGrupo($grupo_id);
                $asistencias_existentes = $asistencia_model->getExistingAsistencia($grupo_id, $fecha);
    
                // También obtenemos el nombre del grupo para mostrarlo en la vista
                $grupo_model = new Grupo($db);
                // Necesitamos un método readOne en el modelo Grupo
                // Por ahora, lo dejamos pendiente y podemos agregarlo luego.
                
                include_once 'views/asistencia/list.php';
            } else {
                header('Location: index.php?controller=asistencia&action=index');
            }
        }
    
                public function report() {
            if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'DOCENTE' && isset($_SESSION['docente_id'])) {
                include_once 'config/database.php';
                include_once 'models/Asistencia.php';
                
                $database = new Database();
                $db = $database->connect();
                $asistencia_model = new Asistencia($db);

                $docente_id = $_SESSION['docente_id'];
                $report_data = $asistencia_model->getFullAttendanceReport($docente_id);

                include_once 'views/asistencia/report.php';
            } else {
                // Redirigir si no es un docente
                header('Location: index.php');
            }
        }

        public function save() {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'ADMIN') {
                            header('Location: index.php?controller=asistencia&action=index&error=permission_denied');
                            exit;
                        }
                        include_once 'config/database.php';
                        include_once 'models/Asistencia.php';
        
                        $database = new Database();
                        $db = $database->connect();
                        $asistencia_model = new Asistencia($db);
        
                        $asistencias = $_POST['asistencia'];
                        $fecha = $_POST['fecha'];
                        $grupo_id = $_POST['grupo_id'];
        
                        if ($asistencia_model->saveAsistencia($asistencias, $fecha, $grupo_id)) {
                            header('Location: index.php?controller=asistencia&action=index&success=true');        
                        } else {
                            header('Location: index.php?controller=asistencia&action=index&error=true');
                        }
        
                    } else {
                        header('Location: index.php?controller=asistencia&action=index');
                    }
                        }
                
                    public function exportPDF() {
                        if (!isset($_GET['alumno_id'])) {
                            die('Error: No se especificó el ID del alumno.');
                        }
                        $alumno_id = $_GET['alumno_id'];
                
                        // Incluir modelos y la librería FPDF
                        include_once 'config/database.php';
                        include_once 'models/Asistencia.php';
                        include_once 'models/Alumno.php';
                        require_once 'libs/fpdf/fpdf.php';
                
                        // Conectar a la BD y obtener datos
                        $database = new Database();
                        $db = $database->connect();
                        $asistencia_model = new Asistencia($db);
                        $alumno_model = new Alumno($db);
                
                        $alumno_data = $alumno_model->readOneSimple($alumno_id);
                        $asistencias = $asistencia_model->readByAlumnoId($alumno_id);
                
                        if (!$alumno_data) {
                            die('Error: Alumno no encontrado.');
                        }
                
                        // Crear PDF
                        $pdf = new FPDF();
                        $pdf->AddPage();
                        $pdf->SetFont('Arial', 'B', 16);
                
                        // Título
                        $nombre_completo = $alumno_data['apellido_paterno'] . ' ' . $alumno_data['apellido_materno'] . ', ' . $alumno_data['nombres'];
                        // Usamos iconv para manejar correctamente los caracteres especiales como tildes
                        $pdf->Cell(190, 10, iconv('UTF-8', 'ISO-8859-1', 'Reporte de Asistencia de:'), 0, 1, 'C');
                        $pdf->SetFont('Arial', '', 14);
                        $pdf->Cell(190, 10, iconv('UTF-8', 'ISO-8859-1', $nombre_completo), 0, 1, 'C');
                        $pdf->Ln(10);
                
                        // Cabecera de la tabla
                        $pdf->SetFont('Arial', 'B', 12);
                        $pdf->SetFillColor(200, 220, 255);
                        $pdf->Cell(95, 10, 'Fecha', 1, 0, 'C', true);
                        $pdf->Cell(95, 10, 'Estado', 1, 1, 'C', true);
                
                        // Contenido de la tabla
                        $pdf->SetFont('Arial', '', 12);
                        if (!empty($asistencias)) {
                            foreach ($asistencias as $asistencia) {
                                $pdf->Cell(95, 10, date("d/m/Y", strtotime($asistencia['fecha'])), 1, 0, 'C');
                                $pdf->Cell(95, 10, $asistencia['estado'], 1, 1, 'C');
                            }
                        } else {
                            $pdf->Cell(190, 10, 'No hay registros de asistencia.', 1, 1, 'C');
                        }
                
                        // Salida del PDF
                        $pdf->Output('D', 'Reporte_Asistencia_' . $alumno_data['apellido_paterno'] . '.pdf');
                    }
                }?>