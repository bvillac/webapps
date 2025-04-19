<?php
require_once("Models/EmpresaModel.php");
require_once("Models/ClienteModel.php");
require_once("Models/LoginModel.php");
require_once("Models/TiendaModel.php");
class LoginTienda extends Controllers
{
	private $dataEmpresa;
	public function __construct()
	{
		parent::__construct();
		session_start();
	
		// Verificar si el usuario está logueado
		if (!isset($_SESSION['loginEstado'])) {
			// No está logueado, redirigir al login
			header('Location: ' . base_url() . '/loginPedido');
			exit;
		}

		$this->datosSession(3);//Acceso Computic
	
		// Ya tiene empresa seleccionada
		if (isset($_SESSION['Cli_id'])) {
			header('Location: ' . base_url() . '/dashboard');
			exit;
		}
	
		// Aquí continúa si hay múltiples empresas y aún no se ha seleccionado una
	}

	private function datosSession(int $Eusu_id){
		$modelLoguin=new LoginModel();
		$modelEmpresa=new EmpresaModel();
		$Emp_Id=3;//$modelEmpresa->getIdEmpresaUsuario($Eusu_id);
		if ($Emp_Id !=0){
			//GUARDA SESSION DATOS DE EMPRESA
			$_SESSION['Emp_Id'] = $Emp_Id; 
			$_SESSION['Eusu_id'] = $Eusu_id;
			$_SESSION['Utie_id'] = 8;//Se debe llenar con la sescion al iniciar
			//$_SESSION['Cli_id'] = 0;//Se debe llenar con la sescion al iniciar
			$_SESSION['empresaData'] = $modelEmpresa->consultarEmpresaEstPunto($Emp_Id);
			$_SESSION['usuarioData']=$modelLoguin->sessionLogin($_SESSION['Usu_id']);//Datos de usuario
			//DATOS ROL DE USUARIO
			$resulRol = $modelLoguin->consultarUsuarioEmpresaRol($Eusu_id);
			if(count($resulRol)>0){
				$_SESSION['usuarioData']['Eurol_id'] = $resulRol[0]['eurol_id'];
				$_SESSION['usuarioData']['Erol_id'] = $resulRol[0]['erol_id'];
				$_SESSION['usuarioData']['Rol_id'] = $resulRol[0]['rol_id'];
				$_SESSION['usuarioData']['Rol_nombre'] = strtolower(str_replace(' ', '', $resulRol[0]['rol_nombre']));
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
	


	public function logintienda()
	{
		//$usuId=retornarDataSesion('Usu_id');
		//$cliId=retornarDataSesion('Cli_Id');
		$data = getPageData("Login Tienda", "loginPedido");
		$data['Cliente'] = (new ClienteModel())->consultarEmpresaCliente();
		//$data['Tienda'] = (new TiendaModel())->consultarTiendaUsuario($cliId,$usuId );
		$this->views->getView($this, "logintienda", $data);
	}


	public function loginUsuarioTienda()
	{
		//dep($_POST);
		if ($_POST) {
			//{ Cliente: ncliente, Tienda: nTienda };
		   $data=recibirData($_POST['data']);
			if (empty($data['Cliente']) || empty($data['Tienda']) ) {
				$arrResponse = array('status' => false, 'msg' => 'Error no se recibieron todos los datos necesarios');
			} else {
				$idsCliente = intval(strClean($data['Cliente']));
				$idsUsuTienda = intval(strClean($data['Tienda']));
				$_SESSION['Cli_id'] = $idsCliente;//Se debe llenar con la sescion al iniciar
				$_SESSION['Utie_id'] = $idsUsuTienda;
				//Obtener datos empresa 
				//$this->datosSession($data['Empresa']);
				//Variables de Session		
				//$idrol = ($idrol != "") ? $idrol : 4; //Si no tiene asignado Rol se envia un rol=4 Usuario
				$arrResponse = array('status' => true, 'msg' => 'ok');
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	

	public function bucarTiendas()
	{
		if ($_POST) {			
			$decodedData = base64_decode($_POST['datos']);
			$data = json_decode($decodedData, true);
			$cliId = intval(strClean($data['Ids']));
			if ($cliId > 0) {
				$usuId=retornarDataSesion('Usu_id');
				$arrData['Tienda'] = (new TiendaModel())->consultarTiendaUsuario($cliId,$usuId );
				if (empty($arrData)) {
					$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
				} else {
					$arrResponse = array('status' => true, 'data' => $arrData);
				}
				echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			}
		}
		exit();
	}


}
