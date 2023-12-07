<?php
require_once("Models/ValoracionModel.php");
class Academico extends Controllers
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


    public function academico()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        //$modelCentro = new CentroAtencionModel();
        //$data['centroAtencion'] = $modelCentro->consultarCentroEmpresa();
        $data['page_tag'] = "Control Académico";
        $data['page_name'] = "Control Académico";
        $data['page_title'] = "Control Académico <small> " . TITULO_EMPRESA . "</small>";
        $this->views->getView($this, "academico", $data);
    }

    public function consultarControl()
    {
        if ($_SESSION['permisosMod']['r']) {
            $arrData = $this->model->consultarDatos();
            for ($i = 0; $i < count($arrData); $i++) {
                $btnOpciones = "";
                if ($arrData[$i]['Estado'] == 1) {
                    $arrData[$i]['Estado'] = '<span class="badge badge-success">Activo</span>';
                } else {
                    $arrData[$i]['Estado'] = '<span class="badge badge-danger">Inactivo</span>'; //target="_blanck"  
                }


                if ($_SESSION['permisosMod']['r']) {
                    //$btnOpciones .= '<button class="btn btn-info btn-sm btnViewLinea" onClick="fntViewSalon(\'' . $arrData[$i]['BenId'] . '\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
                }
                if ($_SESSION['permisosMod']['u']) {
                    $btnOpciones .= ' <a title="Evaluar Beneficiario" href="' . base_url() . '/Academico/evaluar/' . $arrData[$i]['BenId'] . '"  class="btn btn-primary btn-sm"> <i class="fa fa-pencil"></i> </a> ';
                    //$btnOpciones .= '<button class="btn btn-primary  btn-sm btnEditLinea" onClick="editarSalon(\'' . $arrData[$i]['Ids'] . '\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
                }
                if ($_SESSION['permisosMod']['d']) {
                    //$btnOpciones .= '<button class="btn btn-danger btn-sm btnDelLinea" onClick="fntDeleteSalon(' . $arrData[$i]['BenId'] . ')" title="Eliminar Datos"><i class="fa fa-trash"></i></button>';
                }
                $arrData[$i]['options'] = '<div class="text-center">' . $btnOpciones . '</div>';
            }
            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        }
        die();
    }


    public function evaluar($ids)
    {
        if ($_SESSION['permisosMod']['r']) {
            if (is_numeric($ids)) {
                $data = $this->model->consultarDatosId($ids);
                if (empty($data)) {
                    echo "Datos no encontrados";
                } else {
                    $data['control'] = $this->model->consultarBenefId($ids);
                    $valoracion = new ValoracionModel();
                    $data['valoracion'] = $valoracion->consultarValoracion();
                    $data['porcentaje'] = range(0, 100);
                    $data['page_tag'] = "Control Académico";
                    $data['page_name'] = "Control Académico";
                    $data['page_title'] = "Control Académico <small> " . TITULO_EMPRESA . "</small>";
                    $this->views->getView($this, "evaluar", $data);
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

    public function ingresarSalon()
    {
        if ($_POST) {
            //dep($_POST);
            if (empty($_POST['salon']) || empty($_POST['accion'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
            } else {
                $request = "";
                $datos = isset($_POST['salon']) ? json_decode($_POST['salon'], true) : array();
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

   

}
