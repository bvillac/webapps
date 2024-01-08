<?php
use Spipu\Html2Pdf\Html2Pdf;
require 'vendor/autoload.php';
require_once("Models/SecuenciasModel.php");
require_once("Models/CentroAtencionModel.php");
require_once("Models/PaqueteModel.php");
require_once("Models/ModalidadModel.php");
require_once("Models/IdiomaModel.php");
class Contrato extends Controllers
{
	public function __construct()
	{
		parent::__construct();
		sessionStart();
		getPermisos();
	}


	public function contrato()
	{
		if (empty($_SESSION['permisosMod']['r'])) {
			header("Location:" . base_url() . '/dashboard');
		}
		$data['page_tag'] = "Contrato";
		$data['page_name'] = "Contrato";
		$data['page_title'] = "Contrato <small> " . TITULO_EMPRESA . "</small>";
		$this->views->getView($this, "contrato", $data);
	}

	public function consultarContrato()
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
                    //$btnOpciones .= '<button class="btn btn-info btn-sm btnViewInstructor" onClick="fntViewInstructor(\'' . $arrData[$i]['Ids'] . '\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
                }
				if($_SESSION['permisosMod']['r']){
					$btnOpciones .=' <a title="Generar PDF" href="'.base_url().'/Contrato/generarContratoPDF/'.$arrData[$i]['Ids'].'" target="_blanck" class="btn btn-primary btn-sm"> <i class="fa fa-file-pdf-o"></i> </a> ';
				}
				
                if ($_SESSION['permisosMod']['u']) {
                    //$btnOpciones .= ' <a title="Editar Datos" href="' . base_url() . '/ClienteMiller/editar/' . $arrData[$i]['Ids'] . '"  class="btn btn-primary btn-sm"> <i class="fa fa-pencil"></i> </a> ';
                }
                if ($_SESSION['permisosMod']['d']) {
                    $btnOpciones .= '<button class="btn btn-danger btn-sm btnDelInstructor" onClick="fntDesactivarContrato(' . $arrData[$i]['Ids'] . ')" title="Desactivar Contrato"><i class="fa fa-times-circle"></i></button>';
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
		$modelSecuencia = new SecuenciasModel();
		$modelCentro = new CentroAtencionModel();
		$modelPaquete = new PaqueteModel();
		$modelModalidad = new ModalidadModel();
		$modelIdioma = new IdiomaModel();
		//putMessageLogFile($_SESSION['empresaData']);
		$data['fechaActual'] = date('Y-m-d');
		$data['secuencia'] = $modelSecuencia->newSecuence("CON", $_SESSION['empresaData']['PuntoEmisId']);
		$data['centroAtencion'] = $modelCentro->consultarCentroEmpresa();
		$data['paqueteEstudios'] = $modelPaquete->consultarPaquete();
		$data['modalidadEstudios'] = $modelModalidad->consultarModalidad();
		$data['idioma'] = $modelIdioma->consultarIdioma();
		$data['Ruc'] = $_SESSION['empresaData']['Ruc'];
		$data['page_tag'] = "Nuevo Contrato";
		$data['page_name'] = "Nuevo Contrato";
		$data['page_title'] = "Nuevo Contrato <small> " . TITULO_EMPRESA . "</small>";
		$this->views->getView($this, "nuevo", $data);
	}


	public function ingresarContrato()
	{
		if ($_POST) {
			//dep($_POST);
			if (empty($_POST['cabecera']) || empty($_POST['dts_detalle']) || empty($_POST['accion'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos de Cliente y Beneficiarios son incorrectos.');
			} else {
				$request = "";
				$Cabecera = isset($_POST['cabecera']) ? $_POST['cabecera'] : array();
				$Detalle = isset($_POST['dts_detalle']) ? $_POST['dts_detalle'] : array();
				$Referencia = isset($_POST['dts_referencia']) ? $_POST['dts_referencia'] : array();
				$accion = isset($_POST['accion']) ? $_POST['accion'] : "";

				if ($accion == "Create") {
					$option = 1;
					if ($_SESSION['permisosMod']['w']) {
						$request = $this->model->insertData($Cabecera, $Detalle,$Referencia);
					}
				} else {
					$option = 2;
					if ($_SESSION['permisosMod']['u']) {
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

	public function generarContratoPDF($idContrato){
		if($_SESSION['permisosMod']['r']){
			if(is_numeric($idContrato)){			
				$data = $this->model->consultarContratoPDF($idContrato);
				if(empty($data)){
					echo "Datos no encontrados";
				}else{
					$numeroSecuencia = $data['cabData']['Numero'];
					ob_end_clean();
					//$html =getFile("Template/Modals/ordenCompraPDF",$data);
					$html =getFile("Contrato/contratoPDF",$data);
					$html2pdf = new Html2Pdf('p','A4','es','true','UTF-8');
					$html2pdf->writeHTML($html);
					$html2pdf->output('CONTRATO_'.$numeroSecuencia.'.pdf');
				}
			}else{
				echo "Dato no válido";
			}
		}else{
			header('Location: '.base_url().'/login');
			die();
		}
	}

	public function desativarContrato()
    {
        if ($_POST) {

            if ($_SESSION['permisosMod']['d']) {
                $ids = intval($_POST['ids']);
                $request = $this->model->desativarContrato($ids);
                if ($request) {
                    $arrResponse = array('status' => true, 'msg' => 'Se ha Desactivado el Contrato');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'Error al Desactivar el Contrato.');
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }


}
