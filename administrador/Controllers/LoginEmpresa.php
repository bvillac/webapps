<?php 
	require_once("Models/EmpresaModel.php");
	class LoginEmpresa extends Controllers{
		private $dataEmpresa;
		public function __construct(){
			//putMessageLogFile("llego al constructor LoginEmpresa");
			parent::__construct();
			session_start();//iniciamos el uso de variables de session	
			if(isset($_SESSION['loginEstado'])){//Veifica si existe la seesion
				$countEmp=$this->countEmpresa();
				if($countEmp==2){//Solo cuando es una empresa pasa directo
					header('Location: '.base_url().'/dashboard');//Lo direciona al  dashboard
					die();
				}
				
			}else{
				putMessageLogFile("No esta logueado");
				header('Location: ' . base_url() . '/login');//Retorna al login
				die();
			}
			
		}

		private function countEmpresa(){
			$modelEmpresa = new EmpresaModel();
			$this->dataEmpresa = $modelEmpresa->consultarEmpresaUsuario();
			return sizeof($this->dataEmpresa);//Retorna el Numero de Empresas
		}

		public function loginempresa(){
			$data['empresa'] = $this->dataEmpresa;
			$data['page_tag'] = "Login";
			$data['page_name'] = "Login";
			$data['page_title'] = "Login <small> ".TITULO_EMPRESA ."</small>";
			//dep($data);
			$this->views->getView($this,"loginempresa",$data);
		}

		
		public function loginUsuario(){
			//dep($_POST);
			if($_POST){
				$decodedData = base64_decode($_POST['datos']);
				//putMessageLogFile($decodedData);
				//$datosDescifrados = openssl_decrypt(base64_decode($datosEncriptados), 'aes-256-ecb', ClavePrivate, OPENSSL_RAW_DATA);
				// Verificar si hubo algún error al descifrar los datos
				/*if ($datosDescifrados === false) {
					// Capturar el mensaje de error
					$error = openssl_error_string();
					putMessageLogFile("Error al descifrar los datos: $error") ;
					// Puedes registrar este error en un archivo de registro, enviar un correo electrónico al administrador, etc.
					die();// Detener la ejecución del script
				}*/
			
				// Decodificar los datos JSON
				$data = json_decode($decodedData, true);
				if(empty($data['txt_Email']) || empty($data['txt_clave'])){
					$arrResponse = array('status' => false, 'msg' => 'Error de datos' );
				}else{
					$model=new LoginModel();
					$strUsuario  =  strtolower(strClean($data['txt_Email']));//minusculas
					$strClave = hash("SHA256",$data['txt_clave']);//Se encripta para comparar en la base
					$request = $model->loginData($strUsuario, $strClave);			
					if(empty($request)){
						$arrResponse = array('status' => false, 'msg' => 'El usuario o la contraseña es incorrecto.' ); 
					}else{
						$arrData = $request;
						//putMessageLogFile($arrData);
						if($arrData['Estado'] == 1){							
							//Obtener datos empresa 
							$arrEmpresa=datosEmpresaEstablePunto(ID_EMPRESA);
							$_SESSION['empresaData']=$arrEmpresa;
							//Variables de Session
							$_SESSION['idsUsuario'] = $arrData['usu_id'];
							$_SESSION['idEmpresa'] = $arrEmpresa['EmpIds'];//Cambiar por el retornado y seleccionado
							$_SESSION['idsPersona'] = $arrData['per_id'];
							$_SESSION['loginEstado'] = true;//estado de la Session Login
							//Para que la Session no se cierre en algunos navegadores.
							$_SESSION['timeout'] = true;
							$_SESSION['inicio'] = time();//Devuelve la hora en numero entero
							
							$arrData = $model->sessionLogin($_SESSION['idsUsuario']);								
							sessionUsuario($_SESSION['idsUsuario']);//Actualiza la Session del usuario.
							$idrol = $_SESSION['usuarioData']['RolID'];//se obtiene el rol de la seccion
							$usuId = $_SESSION['idsUsuario'];
							$empId = $_SESSION['idEmpresa'];			
							$idrol=($idrol!="")?$idrol:4;//Si no tiene asignado Rol se envia un rol=4 Usuario
							$_SESSION['menuData'] = $model->permisosModulo($usuId,$empId,$idrol);						
							$arrResponse = array('status' => true, 'msg' => 'ok');
							//putMessageLogFile($arrResponse);
							//echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
							//die();
						}else{
							$arrResponse = array('status' => false, 'msg' => 'Usuario inactivo.');
						}
						//putMessageLogFile($arrResponse);
					}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}


	
	
	}
 ?>