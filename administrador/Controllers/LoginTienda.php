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
		// Ya tiene empresa seleccionada
		if (isset($_SESSION['Cli_id'])) {
			header('Location: ' . base_url() . '/dashboard');
			exit;
		}

	}

	
	


	public function logintienda()
	{
		$data = getPageData("Login Tienda", "loginPedido");
		$data['Cliente'] = (new ClienteModel())->consultarEmpresaCliente(3);//Para Computic
		$this->views->getView($this, "logintienda", $data);
	}


	public function loginUsuarioTienda()
	{
		//dep($_POST);
		if ($_POST) {
		   $data=recibirData($_POST['data']);
			if (empty($data['Cliente']) || empty($data['Tienda']) ) {
				$arrResponse = array('status' => false, 'msg' => 'Error no se recibieron todos los datos necesarios');
			} else {
				$idsCliente = intval(strClean($data['Cliente']));
				$idsUsuTienda = intval(value: strClean($data['Tienda']));
				$this->datosSession(3,$idsUsuTienda,$idsCliente);//Acceso Computic								
				$arrResponse = array('status' => true, 'msg' => 'ok');
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		exit();
	}

	private function datosSession(int $Emp_Id,int $Utie_id,int $Cli_id){
		putMessageLogFile("Datos de Session Empresa: Emp_Id:{$Emp_Id}, Utie_id:{$Utie_id}, Cli_id:{$Cli_id}");
		$modelLoguin=new LoginModel();
		$modelEmpresa=new EmpresaModel();
		$usuId=retornarDataSesion('Usu_id');
		$Eusu_id=$modelEmpresa->getIdEmpresaUsuarioXEmpresa($Emp_Id,$usuId);
		if ($Eusu_id !=0){
			//GUARDA SESSION DATOS DE EMPRESA
			$_SESSION['Emp_Id'] = $Emp_Id; 
			$_SESSION['Eusu_id'] = $Eusu_id;
			$_SESSION['Utie_id'] = $Utie_id;//Se debe llenar con la sescion al iniciar
			$_SESSION['Cli_id'] = $Cli_id;//Se debe llenar con la sescion al iniciar
			$_SESSION['empresaData'] = $modelEmpresa->consultarEmpresaEstPunto($Emp_Id);
			$_SESSION['usuarioData']=$modelLoguin->sessionLogin($usuId);//Datos de usuario
			$tienda = (new TiendaModel())->consultarUtieId($Utie_id);
			$Rol_id=$tienda[0]['rol_id'];
			$Erol_id=$modelEmpresa->getIdEmpresaRol($Emp_Id,$Rol_id);
			$Eurol_id=$modelEmpresa->getIdEmpresaUsuarioRol($Eusu_id,$Erol_id);
			//DATOS ROL DE USUARIO
			$resulRol = $modelLoguin->consultarUsuarioEmpresaRol($Eurol_id);//esta mal
			if(count($resulRol)>0){
				$_SESSION['usuarioData']['Eurol_id'] = $resulRol[0]['eurol_id'];
				$_SESSION['usuarioData']['Erol_id'] = $resulRol[0]['erol_id'];
				$_SESSION['usuarioData']['Rol_id'] = $Rol_id;//$tienda[0]['rol_id'];
				$_SESSION['usuarioData']['Rol_nombre'] = strtolower(str_replace(' ', '', $tienda[0]['rol_nombre']));
				//$_SESSION['usuarioData']['Rol_id'] = $resulRol[0]['rol_id'];
				//$_SESSION['usuarioData']['Rol_nombre'] = strtolower(str_replace(' ', '', $resulRol[0]['rol_nombre']));
				//DATOS PERMISO MODULO
				$_SESSION['menuData'] = $modelLoguin->permisosModulo($Eusu_id,$resulRol[0]['erol_id']);

			}else{
				putMessageLogFile("EmpresaUsuarioRol no Existe roles a empresa LoginTienda");
				require_once("Controllers/Error.php");
			} 

		}else{
			putMessageLogFile("EmpresaUsuario con Id no existe= ".$Eusu_id);
			require_once("Controllers/Error.php");
		}
		
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
