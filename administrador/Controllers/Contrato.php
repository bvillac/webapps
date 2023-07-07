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
		$data['secuencia'] = $modelSecuencia->newSecuence("CON",$_SESSION['empresaData']['PuntoEmisId']);
		$data['centroAtencion'] = $modelCentro->consultarCentroEmpresa();
		$data['paqueteEstudios'] = $modelPaquete->consultarPaquete();
		$data['modalidadEstudios'] = $modelModalidad->consultarModalidad();
		$data['idioma'] = $modelIdioma->consultarIdioma();
		$data['Ruc']=$_SESSION['empresaData']['Ruc'];
		$data['page_tag'] = "Nuevo Contrato";
		$data['page_name'] = "Nuevo Contrato";
		$data['page_title'] = "Nuevo Contrato <small> " . TITULO_EMPRESA . "</small>";
		$this->views->getView($this, "nuevo", $data);
	}

	

}
