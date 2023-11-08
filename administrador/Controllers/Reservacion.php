<?php
require_once("Models/CentroAtencionModel.php");
require_once("Models/SalonModel.php");
require_once("Models/InstructorModel.php");
class Reservacion extends Controllers
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
                    $btnOpciones .= ' <a title="Reservaciones" href="' . base_url() . '/Reservacion/editar/' . $arrData[$i]['Ids'] . '"  class="btn btn-primary btn-sm"> <i class="fa fa-solid fa-calendar"></i> </a> ';
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



}
