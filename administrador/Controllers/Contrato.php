<?php
require_once("Models/SecuenciasModel.php");
require_once("Models/CentroAtencionModel.php");
require_once("Models/PaqueteModel.php");
require_once("Models/ModalidadModel.php");
require_once("Models/IdiomaModel.php");
class Contrato extends Controllers
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
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			} else {
				$request = "";
				$Cabecera = isset($_POST['cabecera']) ? $_POST['cabecera'] : array();
				$Detalle = isset($_POST['dts_detalle']) ? $_POST['dts_detalle'] : array();
				$accion = isset($_POST['accion']) ? $_POST['accion'] : "";

				if ($accion == "Create") {
					$option = 1;
					if ($_SESSION['permisosMod']['w']) {
						$request = $this->model->insertData($Cabecera, $Detalle);
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
}
