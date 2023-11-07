<?php
require_once("Models/CentroAtencionModel.php");
require_once("Models/SalonModel.php");
require_once("Models/InstructorModel.php");
class Planificacion extends Controllers
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


    public function planificacion()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        $data['page_tag'] = "Planificación";
        $data['page_name'] = "Planificación";
        $data['page_title'] = "Planificación <small> " . TITULO_EMPRESA . "</small>";
        $this->views->getView($this, "planificacion", $data);
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


                if ($_SESSION['permisosMod']['r']) {
                    $btnOpciones .= '<button class="btn btn-info btn-sm btnViewLinea" onClick="fntViewSalon(\'' . $arrData[$i]['Ids'] . '\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
                }
                if ($_SESSION['permisosMod']['u']) {
                    $btnOpciones .= ' <a title="Editar Datos" href="' . base_url() . '/Planificacion/editar/' . $arrData[$i]['Ids'] . '"  class="btn btn-primary btn-sm"> <i class="fa fa-pencil"></i> </a> ';
                }
                if ($_SESSION['permisosMod']['d']) {
                    $btnOpciones .= '<button class="btn btn-danger btn-sm btnDelLinea" onClick="fntDeleteSalon(' . $arrData[$i]['Ids'] . ')" title="Eliminar Datos"><i class="fa fa-trash"></i></button>';
                }
                $arrData[$i]['options'] = '<div class="text-center">' . $btnOpciones . '</div>';
            }
            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function nuevo()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        $modelCentro = new CentroAtencionModel();
        $data['centroAtencion'] = $modelCentro->consultarCentroEmpresa();
        //$modelInstructor = new InstructorModel();
        //$data['instructor'] = $modelInstructor->consultarInstructores(); //Valor por defecto
        $data['page_tag'] = "Nueva Planificación";
        $data['page_name'] = "Nueva Planificación";
        $data['plugin'] = "calendar";
        $data['page_title'] = "Nueva Planificación <small> " . TITULO_EMPRESA . "</small>";
        $this->views->getView($this, "nuevo", $data);
    }

    public function editar($ids)
    {
        if ($_SESSION['permisosMod']['r']) {
            if (is_numeric($ids)) {
                $data = $this->model->consultarDatosId($ids);
                if (empty($data)) {
                    echo "Datos no encontrados";
                } else {
                    $modelCentro = new CentroAtencionModel();
                    $data['centroAtencion'] = $modelCentro->consultarCentroEmpresa();
                    $modelInstructor = new InstructorModel();
                    $data['dataInstructor'] = $modelInstructor->consultarCentroInstructores($data['cat_id']);
                    $modelSalon = new SalonModel();
                    $data['dataSalon'] = $modelSalon->consultarSalones($data['cat_id']);
                    $data['page_tag'] = "Editar Planificación";
                    $data['page_name'] = "Editar Planificación";
                    $data['page_title'] = "Editar Planificación <small> " . TITULO_EMPRESA . "</small>";
                    $this->views->getView($this, "editar", $data);
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

    public function ingresarPlanificacion()
    {
        if ($_POST) {
            //dep($_POST);
            if (empty($_POST['cabecera']) || empty($_POST['detalle']) || empty($_POST['accion'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
            } else {
                $request = "";
                //$datos = isset($_POST['cabecera']) ? json_decode($_POST['cabecera'], true) : array();
                $Cabecera = isset($_POST['cabecera']) ? $_POST['cabecera'] : array();
                $Detalle = isset($_POST['detalle']) ? $_POST['detalle'] : array();
                $accion = isset($_POST['accion']) ? $_POST['accion'] : "";
                if ($accion == "Create") {
                    $option = 1;
                    //if ($_SESSION['permisosMod']['w']) {
                    $request = $this->model->insertData($Cabecera, $Detalle);
                    //}
                } else {
                    $option = 2;
                    if ($_SESSION['permisosMod']['u']) {
                        $request = $this->model->updateData($Cabecera, $Detalle);
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


    public function consultarSalonId(int $ids)
    {
        if ($_SESSION['permisosMod']['r']) {
            $ids = intval(strClean($ids));
            if ($ids > 0) {
                $arrData = $this->model->consultarDatosId($ids);
                //dep($arrData);
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


    public function eliminarSalon()
    {
        if ($_POST) {

            if ($_SESSION['permisosMod']['d']) {
                $ids = intval($_POST['ids']);
                $request = $this->model->deleteRegistro($ids);
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

    public function bucarSalonCentro()
    {
        if ($_POST) {
            if ($_SESSION['permisosMod']['r']) {
                $modelSalon = new SalonModel();
                $ids = intval(strClean($_POST['Ids']));
                if ($ids > 0) {
                    $arrData = $modelSalon->consultarSalones($ids);
                    //dep($arrData);
                    if (empty($arrData)) {
                        $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                    } else {
                        $arrResponse = array('status' => true, 'data' => $arrData);
                    }
                    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                }
            }
        }
        die();
    }

    public function bucarInstructor()
    {
        if ($_POST) {
            if ($_SESSION['permisosMod']['r']) {
                $ids = intval(strClean($_POST['Ids']));
                if ($ids > 0) {
                    $modelInstructor = new InstructorModel();
                    $arrData = $modelInstructor->consultarDatosId($ids);
                    //dep($arrData);
                    if (empty($arrData)) {
                        $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                    } else {
                        $arrResponse = array('status' => true, 'data' => $arrData);
                    }
                    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                }
            }
        }
        die();
    }

    public function bucarInstructorCentro()
    {
        if ($_POST) {
            if ($_SESSION['permisosMod']['r']) {

                $ids = intval(strClean($_POST['Ids']));
                if ($ids > 0) {
                    $modelInstructor = new InstructorModel();
                    $arrData = $modelInstructor->consultarCentroInstructores($ids);
                    //dep($arrData);
                    if (empty($arrData)) {
                        $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                    } else {
                        $arrResponse = array('status' => true, 'data' => $arrData);
                    }
                    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                }
            }
        }
        die();
    }
}
