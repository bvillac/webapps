<?php 
	class Login extends Controllers{
		public function __construct(){
			parent::__construct();
			session_start();//iniciamos el uso de variables de session	
			if(isset($_SESSION['loginEstado'])){//Veifica si existe la seesion
				header('Location: '.base_url().'/dashboard');//Lo direciona al  dashboard
				die();
			}
			
		}

		public function login(){
			$data['page_tag'] = "Login";
			$data['page_name'] = "Login";
			$data['page_title'] = "Login <small> ".$_SESSION['empresaData']['NombreComercial'] ."</small>";
			//dep($data);
			$this->views->getView($this,"login",$data);
		}

		public function loginUsuario(){
			//dep($_POST);
			if($_POST){
				$data=recibirData($_POST['data']);
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
							//Variables de Session
							$_SESSION['Usu_id'] = $arrData['usu_id'];
							$_SESSION['Per_id'] = $arrData['per_id'];
							//Para que la Session no se cierre en algunos navegadores.
							$_SESSION['timeout'] = true;
							$_SESSION['inicio'] = time();//Devuelve la hora en numero entero
							$_SESSION['loginEstado'] = true;//estado de la Session Login
							$arrResponse = array('status' => true, 'msg' => 'ok');
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


		public function cambiarClave(){
			if($_POST){
				error_reporting(0);

				if(empty($_POST['txt_Email_Reset'])){
					$arrResponse = array('status' => false, 'msg' => 'Error de datos' );
				}else{
					$model=new LoginModel();
					$token = token();
					$strEmail  =  strtolower(strClean($_POST['txt_Email_Reset']));
					$arrData = $model->getUsuarioCorreo($strEmail);

					if(empty($arrData)){
						$arrResponse = array('status' => false, 'msg' => 'Usuario no existente.' ); 
					}else{

						$idsUsuario = $arrData['usu_id'];
						$nombreUsuario = $arrData['per_nombre'].' '.$arrData['per_apellido'];

						$url_recuperar = base_url().'/login/confirmaUsuario/'.$strEmail.'/'.$token;
						$requestUpdate = $model->setTokenUsuario($idsUsuario,$token);

						$dataUsuario = array('nombreUsuario' => $nombreUsuario,
											 'email' => $strEmail,
											 'asunto' => 'Recuperar cuenta - '.REMITENTE,
											 'url_recovery' => $url_recuperar);
						if($requestUpdate){
							$objMail= new mailSystem();
							//$sendEmail = enviarEmail($dataUsuario,'cambioClave');//Hosting
							$sendEmail = $objMail->enviarMail($dataUsuario,'cambioClave');//mailing
							if($sendEmail){
								$arrResponse = array('status' => true, 
												 'msg' => 'Se ha enviado un email a tu cuenta de correo para cambiar tu clave.');
							}else{
								$arrResponse = array('status' => false, 
												 'msg' => 'No es posible realizar el proceso, intenta más tarde.' );
							}
						}else{
							$arrResponse = array('status' => false, 
												 'msg' => 'No es posible realizar el proceso, intenta más tarde.' );
						}
					}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function confirmaUsuario(string $params){

			if(empty($params)){
				header('Location: '.base_url());
			}else{
				$arrParams = explode(',',$params);
				$strEmail = strClean($arrParams[0]);
				$strToken = strClean($arrParams[1]);
				$model=new LoginModel();
				$arrResponse = $model->getUsuario($strEmail,$strToken);
				if(empty($arrResponse)){
					header("Location: ".base_url());//Retorna la Vista Principal
				}else{
					//Formulario de confirmacion
					$data['page_tag'] = "Cambiar Clave";
					$data['page_name'] = "Cambiar Clave";
					$data['page_title'] = "Cambiar Clave";
					$data['email'] = $strEmail;
					$data['token'] = $strToken;
					$data['UsuIds'] = $arrResponse['UsuIds'];
					$data['fileJS'] = "funcionesLogin.js";
					$this->views->getView($this,"cambiarclave",$data);
				}
			}
			die();
		}

		public function setPassword(){

			if(empty($_POST['idUsuario']) || empty($_POST['txtEmail']) || empty($_POST['txtToken']) || empty($_POST['txtPassword']) || empty($_POST['txtPasswordConfirm'])){

					$arrResponse = array('status' => false, 
										 'msg' => 'Error de datos' );
				}else{
					$intIdpersona = intval($_POST['idUsuario']);
					$strPassword = $_POST['txtPassword'];
					$strPasswordConfirm = $_POST['txtPasswordConfirm'];
					$strEmail = strClean($_POST['txtEmail']);
					$strToken = strClean($_POST['txtToken']);

					if($strPassword != $strPasswordConfirm){
						$arrResponse = array('status' => false, 
											 'msg' => 'Las contraseñas no son iguales.' );
					}else{
						$arrResponseUser = $this->model->getUsuario($strEmail,$strToken);
						if(empty($arrResponseUser)){
							$arrResponse = array('status' => false, 
											 'msg' => 'Erro de datos.' );
						}else{
							$strPassword = hash("SHA256",$strPassword);
							$requestPass = $this->model->insertPassword($intIdpersona,$strPassword);

							if($requestPass){
								$arrResponse = array('status' => true, 
													 'msg' => 'Contraseña actualizada con éxito.');
							}else{
								$arrResponse = array('status' => false, 
													 'msg' => 'No es posible realizar el proceso, intente más tarde.');
							}
						}
					}
				}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			die();
		}

	}
 ?>