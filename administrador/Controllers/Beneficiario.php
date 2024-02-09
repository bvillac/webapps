<?php
use Spipu\Html2Pdf\Html2Pdf;
require 'vendor/autoload.php';
require_once("Models/CentroAtencionModel.php");
require_once("Models/PaqueteModel.php");
require_once("Models/ModalidadModel.php");
require_once("Models/IdiomaModel.php");
class Beneficiario extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        sessionStart();
        getPermisos();
    }

    public function beneficiario()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        
        $data['page_tag'] = "Beneficiarío";
        $data['page_name'] = "Beneficiarío";
        $data['page_title'] = "Beneficiarío <small> " . TITULO_EMPRESA . "</small>";
        $this->views->getView($this, "beneficiario", $data);
    }

    public function consultarBeneficiario()
    {
        if ($_SESSION['permisosMod']['r']) {
            //Inicio Paginado
            $pagina = 1;
            $countRows=$this->model->countDatos();
            $totalRows = $countRows['total_registro'];
            $desde = ($pagina-1) * LIMIT_NUMERO;
            $TotPaginas = ceil($totalRows / LIMIT_NUMERO);
            $parametro=array("desde"=>$desde,"Tpagina"=>$TotPaginas);
            //Fin Paginado
            $arrData = $this->model->consultarDatos($parametro);
            //$arrData['pagina'] = $pagina;
			//$arrData['total_paginas'] = $TotPaginas;
            //putMessageLogFile($arrData);
            for ($i = 0; $i < count($arrData); $i++) {
                $btnOpciones = "";
                if ($arrData[$i]['Estado'] == 1) {
                    $arrData[$i]['Estado'] = '<span class="badge badge-success">Activo</span>';
                } else {
                    $arrData[$i]['Estado'] = '<span class="badge badge-danger">Inactivo</span>'; //target="_blanck"  
                }
                $arrData[$i]['TipoBenefiario'] = ($arrData[$i]['TipoBenefiario'] == 1) ? "TITULAR" : "BENEFICIARIO";

                if ($_SESSION['permisosMod']['r']) {
                    //$btnOpciones .= '<button class="btn btn-info btn-sm btnViewLinea" onClick="fntViewLinea(\'' . $arrData[$i]['Ids'] . '\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
                }
                if ($_SESSION['permisosMod']['u']) {
                    $btnOpciones .= ' <a title="Editar Datos" href="' . base_url() . '/Beneficiario/editar/' . $arrData[$i]['Ids'] . '"  class="btn btn-primary btn-sm"> <i class="fa fa-pencil"></i> </a> ';
                    //$btnOpciones .='<button class="btn btn-primary  btn-sm btnEditLinea" onClick="editarBeneficiario(\''.$arrData[$i]['Ids'].'\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
                }
                if ($_SESSION['permisosMod']['d']) {
                    $btnOpciones .= '<button class="btn btn-danger btn-sm btnDelLinea" onClick="fntDeleteBeneficiario(' . $arrData[$i]['Ids'] . ')" title="Eliminar Datos"><i class="fa fa-trash"></i></button>';
                }
                $arrData[$i]['options'] = '<div class="text-center">' . $btnOpciones . '</div>';
            }
            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function ingresarBeneficiario()
    {
        if ($_POST) {
            //dep($_POST);
            if (empty($_POST['beneficiario']) || empty($_POST['accion'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
            } else {
                $request = "";
                $datos = isset($_POST['beneficiario']) ? json_decode($_POST['beneficiario'], true) : array();
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


    public function editar($ids)
    {
        if ($_SESSION['permisosMod']['r']) {
            if (is_numeric($ids)) {
                $data = $this->model->consultarBeneficiario($ids);
                if (empty($data)) {
                    echo "Datos no encontrados";
                } else {
                    $modelCentro = new CentroAtencionModel();
                    $modelPaquete = new PaqueteModel();
                    $modelModalidad = new ModalidadModel();
                    $modelIdioma = new IdiomaModel();
                    $data['Ids'] = $ids;
                    $data['centroAtencion'] = $modelCentro->consultarCentroEmpresa();
                    $data['paqueteEstudios'] = $modelPaquete->consultarPaquete();
                    $data['modalidadEstudios'] = $modelModalidad->consultarModalidad();
                    $data['idioma'] = $modelIdioma->consultarIdioma();
                    $data['page_tag'] = "Editar Beneficiarío";
                    $data['page_name'] = "Editar Beneficiarío";
                    $data['page_title'] = "Editar Beneficiarío <small> " . TITULO_EMPRESA . "</small>";
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

    public function eliminarBeneficiario()
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

    public function beneficiarioContratoNombres()
	{
		if ($_POST) {
			//dep($_POST);
			$Buscar = isset($_POST['buscar']) ? $_POST['buscar'] : "";
			$request = $this->model->beneficiarioContratoNombres($Buscar);
			if ($request) {
				$arrResponse = array('status' => true, 'data' => $request, 'msg' => 'Datos Retornados correctamente.');
			} else {
				$arrResponse = array('status' => false, 'msg' => 'No Existen Datos');
			}	
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

    public function generarReporteBeneficiarioPDF(){
		if($_SESSION['permisosMod']['r']){
                $parametro = array('estado' => '1');		
                $data['Result'] = $this->model->consultarDatos($parametro);
				if(empty($data)){
					echo "Datos no encontrados";
				}else{
					ob_end_clean();
                    $data['Titulo']="Lista Beneficiarios Activos";
					$html =getFile("Beneficiario/beneficiarioPDF",$data);
					$html2pdf = new Html2Pdf('p','A4','es','true','UTF-8');
					$html2pdf->writeHTML($html);
                    $FechaActual= date('m-d-Y H:i:s a', time()); 
                    //$html2pdf->pdf->SetDisplayMode('fullpage');
                    $html2pdf->output('ReporteBeneficiarios_'.$FechaActual.'.pdf','D');
				}
		}else{
			header('Location: '.base_url().'/login');
			die();
		}
	}



}
