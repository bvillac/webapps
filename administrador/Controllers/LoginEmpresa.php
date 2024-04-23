<?php
require_once("Models/EmpresaModel.php");
require_once("Models/CentroAtencionModel.php");
require_once("Models/EstablecimientoModel.php");
require_once("Models/PuntoModel.php");
require_once("Models/LoginModel.php");
class LoginEmpresa extends Controllers
{
	private $dataEmpresa;
	public function __construct()
	{
		parent::__construct();
		session_start(); //iniciamos el uso de variables de session	
		if (isset($_SESSION['loginEstado'])) { //Veifica si existe la seesion
			//Si esta Logueado
			$countEmp = $this->countEmpresa();
			if ($countEmp == 1) { //Solo cuando es una empresa pasa directo
				$this->datosSession($this->dataEmpresa[0]['Ids']);
				header('Location: ' . base_url() . '/dashboard'); //Lo direciona al  dashboard
				die();
			}else{
				//Verifica si ya tiene logueado la empresa
				if (isset($_SESSION['idEmpresa'])) {
					header('Location: ' . base_url() . '/dashboard'); //Lo direciona al  dashboard
					die();
				}
				
			}
		
		} else {
			//putMessageLogFile("No esta logueado");
			header('Location: ' . base_url() . '/login'); //Retorna al login
			die();
		}
	}

	private function countEmpresa()
	{
		$modelEmpresa = new EmpresaModel();
		$this->dataEmpresa = $modelEmpresa->consultarEmpresaUsuario();
		return sizeof($this->dataEmpresa); //Retorna el Numero de Empresas
	}

	public function loginempresa()
	{
		$data['empresa'] = $this->dataEmpresa;
		$data['page_tag'] = "Login Empresa";
		$data['page_name'] = "Login Empresa";
		$data['page_title'] = "Login <small> " . TITULO_EMPRESA . "</small>";
		$data['page_back'] = "loginempresa";
		//dep($data);
		$this->views->getView($this, "loginempresa", $data);
	}


	public function loginUsuarioEmpresa()
	{
		//dep($_POST);
		if ($_POST) {
			$decodedData = base64_decode($_POST['datos']);
			// Decodificar los datos JSON
			$data = json_decode($decodedData, true);
			if (empty($data['Empresa']) || empty($data['Establecimiento']) || empty($data['Punto'])) {
				$arrResponse = array('status' => false, 'msg' => 'Error de datos');
			} else {
				//Obtener datos empresa 
				$this->datosSession($data['Empresa']);
				//Variables de Session		

				/*$arrData = $model->sessionLogin($_SESSION['idsUsuario']);
				sessionUsuario($_SESSION['idsUsuario']); //Actualiza la Session del usuario.
				$idrol = $_SESSION['usuarioData']['RolID']; //se obtiene el rol de la seccion
				$usuId = $_SESSION['idsUsuario'];
				$empId = $_SESSION['idEmpresa'];
				$idrol = ($idrol != "") ? $idrol : 4; //Si no tiene asignado Rol se envia un rol=4 Usuario
				$_SESSION['menuData'] = $model->permisosModulo($usuId, $empId, $idrol);*/

				$arrResponse = array('status' => true, 'msg' => 'ok');
				//putMessageLogFile($arrResponse);
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	private function datosSession(int $EmpId){
		$modelLoguin=new LoginModel();
		//GUARDA SESSION DATOS DE EMPRESA
		$arrEmpresa = datosEmpresaEstablePunto($EmpId);
		$_SESSION['idEmpresa'] = $EmpId; 
		$_SESSION['empresaData'] = $arrEmpresa;
		$_SESSION['usuarioData']=$modelLoguin->sessionLogin($_SESSION['idsUsuario']);//Datos de usuario

		$resulRol = $modelLoguin->selectRolesPermiso($_SESSION['idsUsuario'], $_SESSION['idEmpresa']); //IMPLMENTAR LO DE EMPRESAS
		$_SESSION['usuarioData']['RolID'] = $resulRol['Ids'];
		$_SESSION['usuarioData']['Rol'] = $resulRol['rol_nombre'];
		$_SESSION['menuData'] = $modelLoguin->permisosModulo($_SESSION['idsUsuario'], $_SESSION['idEmpresa'],$resulRol['Ids']);
		putMessageLogFile($_SESSION);
	}

	public function bucarCentro()
	{
		if ($_POST) {
						
			$decodedData = base64_decode($_POST['datos']);
			$data = json_decode($decodedData, true);
			$ids = intval(strClean($data['Ids']));
			if ($ids > 0) {
				$modelCentro = new CentroAtencionModel();
				$modelEstablecimiento = new EstablecimientoModel();
				$modelEmpresa = new EmpresaModel();
				$idEmpresa=$modelEmpresa->getIdEmpresaUsuario($ids);
				$arrData['Centro'] = $modelCentro->consultarCentroEmpresaIds($ids);
				$arrData['Establecimiento'] = $modelEstablecimiento->consultarEstablecimientoEmpresa($ids);
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

	public function bucarPunto()
	{
		if ($_POST) {
			$modelPunto = new PuntoModel();	
			$decodedData = base64_decode($_POST['datos']);
			$data = json_decode($decodedData, true);
			$ids = intval(strClean($data['Ids']));
			if ($ids > 0) {
				$arrData=$modelPunto->consultarEstablecimientoPunto($ids);
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



}
