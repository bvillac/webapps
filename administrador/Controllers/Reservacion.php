<?php
require_once("Models/CentroAtencionModel.php");
require_once("Models/SalonModel.php");
require_once("Models/InstructorModel.php");
require_once("Models/ActividadModel.php");
require_once("Models/ValoracionModel.php");
require_once("Models/NivelModel.php");
class Reservacion extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        sessionStart();
        getPermisos();
    }


    public function reservacion()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        $data['page_tag'] = "Reservación";
        $data['page_name'] = "Reservación";
        $data['page_title'] = "Reservación <small> " . TITULO_EMPRESA . "</small>";
        $this->views->getView($this, "reservacion", $data);
    }



    public function consultarPlanificacion()
    {
        if ($_SESSION['permisosMod']['r']) {
            $arrData = $this->model->consultarDatos();
            //putMessageLogFile($arrData);
            for ($i = 0; $i < count($arrData); $i++) {
                $btnOpciones = "";
                if ($arrData[$i]['Estado'] == 1) {
                    $arrData[$i]['Estado'] = '<span class="badge badge-success">Activo</span>';
                } else {
                    $arrData[$i]['Estado'] = '<span class="badge badge-danger">Inactivo</span>'; //target="_blanck"  
                }
               
                if ($_SESSION['permisosMod']['u']) {
                    $btnOpciones .= ' <a title="Agendar" href="' . base_url() . '/Reservacion/agendar/' . $arrData[$i]['Ids'] . '"  class="btn btn-primary btn-sm"> <i class="fa fa-solid fa-calendar"></i> </a> ';
                }
                /*if ($_SESSION['permisosMod']['u']) {
                    $btnOpciones .= '<button class="btn btn-info btn-sm btnViewLinea" onClick="fntClonarPlanificacion(\'' . $arrData[$i]['Ids'] . '\')" title="Clonar Planificación"><i class="fa fa-clone"></i></button> ';
                    $btnOpciones .= '<button class="btn btn-info btn-sm btnViewLinea" onClick="fntAutorizarPlanificacion(\'' . $arrData[$i]['Ids'] . '\')" title="Autorizar Planificación"><i class="fa fa-sharp fa-solid fa-thumbs-up"></i></button> ';
                }
                if ($_SESSION['permisosMod']['d']) {
                    $btnOpciones .= '<button class="btn btn-danger btn-sm btnDelLinea" onClick="fntDeletePlanificacion(' . $arrData[$i]['Ids'] . ')" title="Eliminar Datos"><i class="fa fa-trash"></i></button>';
                }*/
                $arrData[$i]['options'] = '<div class="text-center">' . $btnOpciones . '</div>';
            }
            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        }
        die();
    }


    public function agendar($ids)
    {
        if ($_SESSION['permisosMod']['r']) {
            if (is_numeric($ids)) {
                $data = $this->model->consultarDatosId($ids);
                //putMessageLogFile($data);
                if (empty($data)) {
                    echo "Datos no encontrados";
                } else {
                    $data['fechaDia']=$data['pla_fecha_incio'];
                    $data['accion']="INI";
                    //$data['reservacion'] = $this->model->consultarReservaciones($data);
                    $data['reservacion'] = $this->model->consultarReservacionFecha($data['cat_id'],$data['pla_id'],$data['pla_fecha_incio']);
                    $data['numero_reser']=$this->contarResrevados($data['reservacion']);
                    $modelCentro = new CentroAtencionModel();
                    $data['centroAtencion'] = $modelCentro->consultarCentroEmpresa();
                    $modelInstructor = new InstructorModel();
                    $data['dataInstructor'] = $modelInstructor->consultarCentroInstructores($data['cat_id']);
                    $modelSalon = new SalonModel();
                    $data['dataSalon'] = $modelSalon->consultarSalones($data['cat_id']);
                    $modelActividad = new ActividadModel();
                    $data['dataActividad'] = $modelActividad->consultarActividad();
                    $modelNivel = new NivelModel();
                    $data['dataNivel'] = $modelNivel->consultarNivel();
                    $data['page_tag'] = "Agendar";
                    $data['page_name'] = "Agendar";
                    $data['page_title'] = "Agendar <small> " . TITULO_EMPRESA . "</small>";
                    $this->views->getView($this, "agendar", $data);
                }
            } else {
                echo "Dato no válido";
            }
        } else {
            header('Location: ' . base_url() . '/login');
            die();
        }
        die();
    }

    public function moverAgenda()
    {
        //putMessageLogFile($_GET);
        if ($_GET) {
            //dep($_GET);
            if (empty($_GET['cat_id']) || empty($_GET['pla_id']) || empty($_GET['accion']) || empty($_GET['fechaDia']) ) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
            } else {
                $request = "";
                //$datos = isset($_POST['reservar']) ? json_decode($_POST['reservar'], true) : array();
                $cat_id = isset($_GET['cat_id']) ? $_GET['cat_id'] : "";
                $pla_id = isset($_GET['pla_id']) ? $_GET['pla_id'] : "";
                $accion = isset($_GET['accion']) ? $_GET['accion'] : "";
                $fechaDia = isset($_GET['fechaDia']) ? $_GET['fechaDia'] : null;
                $data = $this->model->consultarDatosId($pla_id);
                $result=$this->estaEnRango($accion,$fechaDia,$data['pla_fecha_incio'],$data['pla_fecha_fin']);
               
                //if($result["estado"]=="FUE"){
                //    $fechaDia=$result["fecha"];
                    //swal("Atención!", "Fechas fuera de Rango", "error");
                //}
                $fechaDia=$result["fecha"];
                $data['fechaDia']=$fechaDia;
                $data['accion']=$accion;
                $data['reservacion'] = $this->model->consultarReservacionFecha($cat_id, $pla_id, $fechaDia);
                $data['numero_reser'] = $this->contarResrevados($data['reservacion']);

                $modelCentro = new CentroAtencionModel();
                $data['centroAtencion'] = $modelCentro->consultarCentroEmpresa();
                $modelInstructor = new InstructorModel();
                $data['dataInstructor'] = $modelInstructor->consultarCentroInstructores($cat_id);
                $modelSalon = new SalonModel();
                $data['dataSalon'] = $modelSalon->consultarSalones($cat_id);
                $modelActividad = new ActividadModel();
                $data['dataActividad'] = $modelActividad->consultarActividad();
                $modelNivel = new NivelModel();
                $data['dataNivel'] = $modelNivel->consultarNivel();
                $data['page_tag'] = "Agendar";
                $data['page_name'] = "Agendar";
                $data['page_title'] = "Agendar <small> " . TITULO_EMPRESA . "</small>";
                $this->views->getView($this, "agendar", $data);

                
            }

        }
        die();
    }

    private function contarResrevados($data)
    {
        $numRes = [];
        $c = 0;
        if (sizeof($data) > 0) {
            $aux = $data[0]['Ids'];
            $rowData[$c]['Ids'] = $data[0]['Ids'];
            $rowData[$c]['count'] = 0;
            for ($i = 0; $i < sizeof($data); $i++) {
                if ($data[$i]['Ids'] <> $aux) {
                    $c++;
                    $rowData[$c]['Ids'] = $data[$i]['Ids'];
                    $rowData[$c]['count']++;
                } else {
                    $rowData[$c]['count']++;
                }
                $aux = $data[$i]['Ids'];
            }
            $numRes = $rowData;
        }
        
        return $numRes;
    }

    public function reservarBeneficiario()
    {
        if ($_POST) {
            //dep($_POST);
            if (empty($_POST['reservar']) || empty($_POST['accion'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
            } else {
                $request = "";
                //$datos = isset($_POST['reservar']) ? json_decode($_POST['reservar'], true) : array();
                $datos = isset($_POST['reservar']) ? $_POST['reservar'] : array();
                $accion = isset($_POST['accion']) ? $_POST['accion'] : "";
                if ($accion == "Create") {
                    $option = 1;
                    if ($_SESSION['permisosMod']['w']) {
                        $request = $this->model->insertData($datos);
                    }
                } else {
                    $option = 2;
                    if ($_SESSION['permisosMod']['u']) {
                        $request = $this->model->updateData($datos);
                    }
                }
                if ($request["status"]) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'numero' => $request["numero"], 'msg' => 'Datos guardados correctamente.');
                    } else {
                        $arrResponse = array('status' => true, 'numero' => $request["numero"], 'msg' => 'Datos Actualizados correctamente.');
                    }
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function countBeneficiario()
    {
        if ($_POST) {
            //dep($_POST);
            if (empty($_POST['cat_id']) || empty($_POST['pla_id']) || empty($_POST['fechaDia']) ) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
            } else {
                $request = "";
                //$datos = isset($_POST['reservar']) ? json_decode($_POST['reservar'], true) : array();
                $cat_id = isset($_POST['cat_id']) ? $_POST['cat_id'] : "";
                $pla_id = isset($_POST['pla_id']) ? $_POST['pla_id'] : "";
                $fechaDia = isset($_POST['fechaDia']) ? $_POST['fechaDia'] : "";

                $arrData['reservacion'] = $this->model->consultarReservacionFecha($cat_id,$pla_id,$fechaDia);
                $arrData['numero_reser']= $this->contarResrevados($arrData['reservacion']);

                if (empty($arrData)) {
                    $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                } else {
                    $arrResponse = array('status' => true, 'data' => $arrData);
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
            
        }
        die();
    }




    private function contarFechaDia($accionMove,$fecha){
        if ($accionMove == "Next") {
            $fecha->modify('+1 day');
        } else if ($accionMove == "Back") {
            $fecha->modify('-1 day');
        }
        return $fecha->format('Y-m-d');
    }

    function estaEnRango($Evento,$fecha, $fechaInicio, $fechaFin) {   
        $fechaInicio = new DateTime($fechaInicio);
        $fechaFin = new DateTime($fechaFin);
        $fecha = new DateTime($fecha);
        $result=$this->contarFechaDia($Evento,$fecha);
        if($fecha > $fechaInicio && $fecha < $fechaFin){
            //Dentro del Rengo  
            $obtResult['estado'] ="OK";
            $obtResult['fecha'] =$fecha->format('Y-m-d');
        }  else if ($fecha == $fechaInicio) {
            $obtResult['estado'] ="INI";
            $obtResult['fecha'] =$fechaInicio->format('Y-m-d');
        } else if ($fecha == $fechaFin) {
            $obtResult['estado'] ="FIN";
            $obtResult['fecha'] =$fechaFin->format('Y-m-d');
        } else if ($fecha < $fechaInicio) {
            //Fuera de Rango
            $obtResult['estado'] ="FUE";
            $obtResult['fecha'] =$fechaInicio->format('Y-m-d');
        } else if ($fecha > $fechaFin) {
            $obtResult['estado'] ="FUE";
            $obtResult['fecha'] =$fechaFin->format('Y-m-d');
        }else{
            $obtResult['estado'] ="INI";
            $obtResult['fecha'] =$fechaInicio->format('Y-m-d');
            //obtResult.fecha=0;
        }
        return $obtResult;
    
    }

    public function anularReservacion()
    {
        if ($_POST) {
            if ($_SESSION['permisosMod']['d']) {
                $ids = intval($_POST['ids']);
                $request = $this->model->anularReservacion($ids);
                if ($request) {
                    $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Registro');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Registro.');
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }






}
