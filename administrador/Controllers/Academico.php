<?php
use Spipu\Html2Pdf\Html2Pdf;
require 'vendor/autoload.php';
require_once("Models/ValoracionModel.php");
class Academico extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        sessionStart();
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
        $data['page_back'] = "academico";
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
                    $btnOpciones .= ' <a title="Evaluar Beneficiario" href="' . base_url() . '/Academico/evaluar/' . $arrData[$i]['BenId'] . '"  class="btn btn-primary btn-sm"> <i class="fa fa-list-alt"></i> </a> ';
                    //$btnOpciones .= '<button class="btn btn-primary  btn-sm btnEditLinea" onClick="editarSalon(\'' . $arrData[$i]['Ids'] . '\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
                }
                if($_SESSION['permisosMod']['r']){
					$btnOpciones .=' <a title="Generar PDF" href="'.base_url().'/Academico/generarControlAcademicoPDF/'.$arrData[$i]['BenId'].'" target="_blanck" class="btn btn-primary btn-sm"> <i class="fa fa-file-pdf-o"></i> </a> ';
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
                    $data['page_back'] = "academico";
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

    public function ingresarEvaluacion()
    {
        if ($_POST) {
            //dep($_POST);
            if (empty($_POST['data']) || empty($_POST['accion'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
            } else {
                $request = "";
                $datos = isset($_POST['data']) ? json_decode($_POST['data'], true) : array();
                $accion = isset($_POST['accion']) ? $_POST['accion'] : "";
                if ($accion == "Evaluar") {
                    if ($_SESSION['permisosMod']['u']) {
                        $request = $this->model->updateDataEvaluacion($datos);
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

    public function generarControlAcademicoPDF($idBen){
		if($_SESSION['permisosMod']['r']){
			if(is_numeric($idBen)){			
                $data = $this->model->consultarDatosId($idBen);
				if(empty($data)){
					echo "Datos no encontrados";
				}else{
                    $data['control'] = $this->model->consultarBenefId($idBen);
                    //putMessageLogFile($data);
                    
					$numeroContrato = $data['Contrato'];
					ob_end_clean();
					$html =getFile("Academico/controlPDF",$data);
					$html2pdf = new Html2Pdf('p','A4','es','true','UTF-8');
					$html2pdf->writeHTML($html);
					$html2pdf->output('CONTROL_'.$numeroContrato.'.pdf');
				}
			}else{
				echo "Dato no válido";
			}
		}else{
			header('Location: '.base_url().'/login');
			die();
		}
	}

    
   

}
