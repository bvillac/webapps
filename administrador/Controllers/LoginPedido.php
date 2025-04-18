<?php
class LoginPedido extends Controllers
{

	public function __construct()
	{
		parent::__construct();
		if (session_status() !== PHP_SESSION_ACTIVE) {
			session_start();
		}

		// Si ya está logueado, redirigir al dashboard
		if (!empty($_SESSION['loginEstado'])) {

			header('Location: ' . base_url() . '/dashboard');
			exit;
		}
	}

	public function loginpedido()
	{
		$data = getPageData("Login Pedido", "loginPedido");
		$this->views->getView($this, "loginpedido", $data);
	}

	public function loginUsuario()
	{
		if ($_POST) {
			$data = recibirData($_POST['data']);
			$strUsuario = strtolower(strClean($data['txt_Email'] ?? ''));
			$strClave = $data['txt_clave'] ?? '';

			if (empty($strUsuario) || empty($strClave)) {
				$arrResponse = ['status' => false, 'msg' => 'Datos incompletos.'];
			} else {
				$model = new LoginModel();
				$claveHash = hash("SHA256", $strClave);//Se encripta para comparar en la base

				$user = $model->loginData($strUsuario, $claveHash);

				if (!$user) {
					$arrResponse = ['status' => false, 'msg' => 'Usuario o contraseña incorrectos.'];
				} elseif ($user['Estado'] != 1) {
					$arrResponse = ['status' => false, 'msg' => 'Usuario inactivo.'];
				} else {
					// Crear sesión
					$_SESSION['Usu_id'] = $user['usu_id'];
					$_SESSION['Per_id'] = $user['per_id'];
					$_SESSION['timeout'] = true;
					$_SESSION['inicio'] = time();//Devuelve la hora en numero entero
					$_SESSION['loginEstado'] = true;//estado de la Session Login

					$arrResponse = ['status' => true, 'msg' => 'ok'];
				}
			}
			putMessageLogFile("ok");

			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}

		exit(); // Finaliza para evitar contenido extra
	}


}
?>