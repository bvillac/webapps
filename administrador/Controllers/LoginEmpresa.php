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
				if (isset($_SESSION['Emp_Id'])) {
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
		$this->dataEmpresa = $modelEmpresa->consultarEmpresaUsuario($_SESSION['Usu_id']);
		return sizeof($this->dataEmpresa); //Retorna el Numero de Empresas
	}

	public function loginempresa()
	{
		$data['empresa'] = $this->dataEmpresa;
		$data['page_tag'] = "Login Empresa";
		$data['page_name'] = "Login Empresa";
		$data['page_title'] = "Login Empresa";
		$data['page_back'] = "loginempresa";
		//dep($data);
		$this->views->getView($this, "loginempresa", $data);
	}


	public function loginUsuarioEmpresa()
	{
		//dep($_POST);
		if ($_POST) {
		   $data=recibirData($_POST['data']);
			if (empty($data['Empresa']) || empty($data['Establecimiento']) || empty($data['Punto'])) {
				$arrResponse = array('status' => false, 'msg' => 'Error no se recibieron todos los datos necesarios');
			} else {
				//Obtener datos empresa 
				$this->datosSession($data['Empresa']);
				//Variables de Session		
				//$idrol = ($idrol != "") ? $idrol : 4; //Si no tiene asignado Rol se envia un rol=4 Usuario
				$arrResponse = array('status' => true, 'msg' => 'ok');
				//putMessageLogFile($arrResponse);
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	private function datosSession(int $Eusu_id){
		$modelLoguin=new LoginModel();
		$modelEmpresa=new EmpresaModel();
		$Emp_Id=$modelEmpresa->getIdEmpresaUsuario($Eusu_id);
		if ($Emp_Id !=0){
			//GUARDA SESSION DATOS DE EMPRESA
			$_SESSION['Emp_Id'] = $Emp_Id; 
			$_SESSION['Eusu_id'] = $Eusu_id;
			$_SESSION['empresaData'] = $modelEmpresa->consultarEmpresaEstPunto($Emp_Id);
			$_SESSION['usuarioData']=$modelLoguin->sessionLogin($_SESSION['Usu_id']);//Datos de usuario
			//DATOS ROL DE USUARIO
			$resulRol = $modelLoguin->consultarUsuarioEmpresaRol($Eusu_id);
			if(count($resulRol)>0){
				$_SESSION['usuarioData']['Eurol_id'] = $resulRol[0]['eurol_id'];
				$_SESSION['usuarioData']['Erol_id'] = $resulRol[0]['erol_id'];
				$_SESSION['usuarioData']['Rol_id'] = $resulRol[0]['rol_id'];
				$_SESSION['usuarioData']['Rol_nombre'] = $resulRol[0]['rol_nombre'];
				//DATOS PERMISO MODULO
				$_SESSION['menuData'] = $modelLoguin->permisosModulo($Eusu_id,$resulRol[0]['erol_id']);

			}else{
				putMessageLogFile("EmpresaUsuarioRol no Existe roles a empresa ");
				require_once("Controllers/Error.php");
			} 
			
			//putMessageLogFile($_SESSION);

		}else{
			putMessageLogFile("EmpresaUsuario con Id no existe= ".$Eusu_id);
			require_once("Controllers/Error.php");
		}
		
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
