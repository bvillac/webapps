<?php
require_once("Models/CentroAtencionModel.php");
require_once("Models/SalonModel.php");
require_once("Models/InstructorModel.php");
require_once("Models/ActividadModel.php");
require_once("Models/ValoracionModel.php");
require_once("Models/NivelModel.php");
class Asistencia extends Controllers
{
    public function __construct()
    {
        sessionStart();
        parent::__construct();
        if (empty($_SESSION['loginEstado'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
        getPermisos();
    }


    public function asistencia()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        $data['horarios'] = range(8, 20);
        $modelCentro = new CentroAtencionModel();
        $data['centroAtencion'] = $modelCentro->consultarCentroEmpresa();
        $data['page_tag'] = "Asistencia";
        $data['page_name'] = "Asistencia";
        $data['page_title'] = "Asistencia <small> " . TITULO_EMPRESA . "</small>";
        $this->views->getView($this, "asistencia", $data);
    }


    

    public function asistenciaFechaHora(){	
        if ($_POST) {
            if (empty($_POST['catId']) || empty($_POST['plaId']) || empty($_POST['fechaDia'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
            } else {
                if($_SESSION['permisosMod']['r']){
                    $catId = isset($_POST['catId']) ? $_POST['catId'] : 0;
                    $plaId = isset($_POST['plaId']) ? $_POST['plaId'] : 0;
                    $insId = isset($_POST['insId']) ? $_POST['insId'] : 0;
                    $fechaDia = isset($_POST['fechaDia']) ? $_POST['fechaDia'] : '';
                    $hora = isset($_POST['hora']) ? $_POST['hora'] : 0;        
                    $arrData = $this->model->consultarAsistenciaFechaHora($catId,$plaId,$insId,$fechaDia,$hora);
                    if (empty($arrData)) {
                        $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                    } else {
                        $arrResponse = array('status' => true, 'data' => $arrData);
                    }
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }		
        die();
    }


    public function marcarAsistencia()
    {
        if ($_POST) {
            if ($_SESSION['permisosMod']['d']) {
                $ids = intval($_POST['Ids']);
                $request = $this->model->marcarAsistencia($ids);
                
                if ($request["status"]) {
                    $arrResponse = array('status' => true, 'msg' => 'Asistencía Registrada Correctamente');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'Error al Registrar la Asistencía.');
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }

  









}
