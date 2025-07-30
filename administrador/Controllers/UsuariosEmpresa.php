<?php
use Spipu\Html2Pdf\Html2Pdf;
require 'vendor/autoload.php';
require_once("Models/TiendaModel.php");
require_once("Models/ClientePedidoModel.php");

class UsuariosEmpresa extends Controllers
{
	public function __construct()
	{
		parent::__construct();
		sessionStart();
		getPermisos();
	}

	public function UsuariosEmpresa()
	{
		//control de Acceso por Roles
		checkPermission('r', 'dashboard');
		$data = getPageData("Usuarios Empresa", "usuariosempresa");
		$data['empresa_rol'] = $this->model->consultarRolEmpresa();
		$Cli_Id = retornarDataSesion("Cli_Id");
		$data['cli_id'] = $Cli_Id ;
		$data['cliente'] = (new ClientePedidoModel())->consultarClienteTienda();
		$data['tiendas'] = (new TiendaModel())->getClienteTiendas($Cli_Id);
		$this->views->getView($this, "usuariosempresa", $data);
	}

	public function getUsuariosEmpresa()
	{
		$arrData = $this->model->consultarDatos();
		foreach ($arrData as &$objData) {
			$objData['Estado'] = $objData['Estado'] == 1
				? '<span class="badge badge-success">Activo</span>'
				: '<span class="badge badge-danger">Inactivo</span>';
			$objData['options'] = $this->getArrayOptions($objData['Ids'], $objData['RolId']);
		}
		echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
		exit();
	}

	private function getArrayOptions($id,$RolId)
	{
		$options = '<div class="text-center">';
		if ($_SESSION['permisosMod']['r']) {
			$options .= '<button class="btn btn-info btn-sm btnViewUsu" onClick="fntViewUsu(\'' . $id . '\')" title="Ver Datos"><i class="fa fa-eye"></i></button> ';
		}
		if ($_SESSION['permisosMod']['u']) {
			$options .= '<button class="btn btn-primary  btn-sm btnEditUsu" onClick="fntEditUsu(\'' . $id . '\')" title="Editar Datos"><i class="fa fa-pencil"></i></button> ';
			$options .= '<button class="btn btn-primary  btn-sm " onClick="fntEditClave(\'' . $id . '\')" title="Cambiar Clave"><i class="fa fa-key"></i></button> ';
			$options .= '<button class="btn btn-primary  btn-sm " onClick="fntVerTienda(\'' . $id . '\', \'' . $RolId . '\')" title="Tiendas"><i class="fa fa-shopping-bag"></i></button> ';
		}
		if ($_SESSION['permisosMod']['d']) {
			$options .= " <button class='btn btn-danger btn-sm btnDelUsu' onClick='fntDelUsu($id)' title='Eliminar'><i class='fa fa-trash'></i></button> ";
		}
		return $options . '</div>';
	}

	public function getRolesUsu()
	{
		$model = new UsuariosModel();
		$htmlOptions = "";
		$arrData = $model->consultarRoles();
		if (count($arrData) > 0) {
			$htmlOptions = '<option value="0">SELECCIONAR</option>';
			for ($i = 0; $i < count($arrData); $i++) {
				$htmlOptions .= '<option value="' . $arrData[$i]['Ids'] . '">' . $arrData[$i]['Nombre'] . '</option>';
			}
		}
		echo $htmlOptions;
		die();
	}
	public function buscarAutoUsuario()
	{
		try {

			$inputData = validarMetodoPost();
			// Sanitizar y obtener los valores con seguridad
			$parametro = isset($inputData['parametro']) ? filter_var($inputData['parametro'], FILTER_SANITIZE_STRING) : "";
			$limit = isset($inputData['limit']) ? filter_var($inputData['limit'], FILTER_VALIDATE_INT) : 10;


			$request = (new PersonaModel())->consultarDatosCedulaNombres($parametro);

			// Responder con los datos obtenidos o mensaje de error
			$arrResponse = $request
				? ['status' => true, 'data' => $request, 'msg' => 'Datos retornados correctamente.']
				: ['status' => false, 'msg' => 'No existen datos.'];
		} catch (Exception $e) {
			$arrResponse = ['status' => false, 'msg' => $e->getMessage()];
			logFileSystem("Error en consutla Catalogo: " . $e->getMessage(), "ERROR");
		}

		// Responder con JSON
		header('Content-Type: application/json; charset=UTF-8');
		echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		exit();
	}





	public function guardarUsuarioEmpresa()
	{
		if ($_POST) {
			$data = recibirData($_POST['data'] ?? null);

			if (empty($data['dataObj']) || empty($data['accion'])) {
				$arrResponse = ['status' => false, 'msg' => 'No se recibieron todos los datos necesarios.'];
				responseJson($arrResponse);
			}

			$datos = $data['dataObj'];
			$accion = $data['accion'];
			$option = 0;
			$request = [];
			try {
				if ($accion === "Create") {
					checkPermission('w', 'usuariosempresa');
					$request = $this->model->insertData($datos);
					$option = 1;
				} elseif ($accion === "Edit") {
					checkPermission('u', 'usuariosempresa');
					$request = $this->model->updateData($datos);
					$option = 2;
				} elseif ($accion === "CreateEdit") {
					checkPermission('w',  'usuariosempresa');
					$request = $this->model->insertDataEmpUsuRol($datos);
					$option = 1;
				} else {
					$arrResponse = ['status' => false, 'msg' => 'Acción no válida.'];
					responseJson($arrResponse);
				}
				if (!empty($request["status"])) {
					$msg = ($option === 1) ? 'Datos guardados correctamente.' : 'Datos actualizados correctamente.';
					$arrResponse = ['status' => true, 'numero' => $request["numero"] ?? 0, 'msg' => $msg];
				} else {
					$arrResponse = ['status' => false, 'msg' => 'No fue posible almacenar los datos. '. $request["message"]];
				}
			} catch (Exception $e) {
				logFileSystem("Error en guardarUsuarioEmpresa: " . $e->getMessage(), "ERROR");
				$arrResponse = ['status' => false, 'msg' => 'Ocurrió un error inesperado al guardar los datos.'];
			}

			responseJson($arrResponse);
		}

		exit();
	}



	public function getUsuarioEmpresa()
	{
		try {
			if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
				responseJson(['status' => false, 'msg' => 'Método no permitido.']);
			}

			$data = recibirData($_POST['data']);

			if (empty($data['ids']) || !is_numeric($data['ids'])) {
				responseJson(['status' => false, 'msg' => 'ID inválido o faltante.']);
			}

			$ids = intval(strClean($data['ids']));

			$resultado = (new UsuariosModel())->consultarDatosId($ids);
			if (empty($resultado)) {
				$arrResponse = [
					'status' => false,
					'msg' => 'No se pudo encontrar el usuario. Verifica si el ID es correcto.'
				];
			} else {
				$arrResponse = [
					'status' => true,
					'data' => $resultado
				];
			}

			responseJson($arrResponse);
		} catch (Exception $e) {
			logFileSystem("Error en getUsuarioEmpresa: " . $e->getMessage(), "ERROR");
			responseJson(['status' => false, 'msg' => 'Ocurrió un error al encontrar el usuario.']);
		}
		exit();
	}



	/**
	 * Controlador para eliminar (desactivar lógicamente) un usuario.
	 */
	public function eliminarUsuario()
	{
		try {
			if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
				responseJson(['status' => false, 'msg' => 'Método no permitido.']);
			}

			$data = recibirData($_POST['data']);

			if (empty($data['ids']) || !is_numeric($data['ids'])) {
				responseJson(['status' => false, 'msg' => 'ID inválido o faltante.']);
			}

			$ids = intval(strClean($data['ids']));

			$resultado = (new UsuariosModel())->deleteUsuario($ids);

			if (!$resultado) {
				$arrResponse = [
					'status' => false,
					'msg' => 'No se pudo eliminar el usuario. Verifica si el ID es correcto.'
				];
			} else {
				$arrResponse = [
					'status' => true,
					'msg' => 'Usuario eliminado correctamente.',
					'data' => $resultado
				];
			}

			responseJson($arrResponse);
		} catch (Exception $e) {
			logFileSystem("Error en eliminarUsuario: " . $e->getMessage(), "ERROR");
			responseJson(['status' => false, 'msg' => 'Ocurrió un error al eliminar el usuario.']);
		}
		exit();
	}

	public function cambiarClave()
	{
		try {
			if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
				responseJson(['status' => false, 'msg' => 'Método no permitido.']);
			}

			$data = recibirData($_POST['data']);

			if (empty($data['ids']) || !is_numeric($data['ids']) || empty($data['clave'])) {
				responseJson(['status' => false, 'msg' => 'ID inválido o clave faltante.']);
			}

			$ids = intval(strClean($data['ids']));
			$clave = $data['clave'];

			$resultado = (new UsuariosModel())->cambiarClave($ids, $clave);

			if (!$resultado) {
				$arrResponse = [
					'status' => false,
					'msg' => 'No se pudo Cambiar la clave. Verifica si el ID es correcto.'
				];
			} else {
				$arrResponse = [
					'status' => true,
					'msg' => 'Registro actualizado correctamente.'
				];
			}

			responseJson($arrResponse);
		} catch (Exception $e) {
			logFileSystem("Error en cambiarClave: " . $e->getMessage(), "ERROR");
			responseJson(['status' => false, 'msg' => 'Ocurrió un error al cambiarClave el usuario.']);
		}
		exit();
	}


	public function getTiendasEmpresa()
	{
		try {
			if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
				responseJson(['status' => false, 'msg' => 'Método no permitido.']);
			}

			$data = recibirData($_POST['data']);

			if (empty($data['ids']) || !is_numeric($data['ids'])) {
				responseJson(['status' => false, 'msg' => 'ID inválido o faltante.']);
			}

			$ids = intval(strClean($data['ids']));

			$resultado = (new UsuariosModel())->consultarDatosId($ids);
			if (empty($resultado)) {
				$arrResponse = [
					'status' => false,
					'msg' => 'No se pudo encontrar el usuario. Verifica si el ID es correcto.'
				];
			} else {
				$arrResponse = [
					'status' => true,
					'data' => $resultado
				];
			}

			responseJson($arrResponse);
		} catch (Exception $e) {
			logFileSystem("Error en getUsuarioEmpresa: " . $e->getMessage(), "ERROR");
			responseJson(['status' => false, 'msg' => 'Ocurrió un error al encontrar el usuario.']);
		}
		exit();
	}



	public function guardarUsuarioTiendas()
	{
		if ($_POST) {
			$data = recibirData($_POST['data'] ?? null);

			if (empty($data['ids']) || empty($data['cliId']) || empty($data['rolId']) || empty($data['tiendas'])) {
				$arrResponse = ['status' => false, 'msg' => 'No se recibieron todos los datos necesarios.'];
				responseJson($arrResponse);
			}
			

			$ids = $data['ids'];
			$rolId = $data['rolId'];
			$cliId = $data['cliId'];
			$tiendas = $data['tiendas'];
			$accion = 'Create';
			$option = 0;
			$request = [];
			//putMessageLogFile("Guardar Usuario Tiendas: ids=" . json_encode($ids) . ", tiendas=" . json_encode($tiendas) . ", accion=" . $accion);
			try {
				if ($accion === "Create") {
					checkPermission('w', 'usuariosempresa');
					// Enviar el id de usuario y las tiendas seleccionadas al modelo
					$request = (new TiendaModel())->asignarTiendasUsuario($ids,$rolId,$cliId, $tiendas);
					$option = 1;
				} elseif ($accion === "Edit") {
					//checkPermission('u', 'usuariosempresa');
					//$request = $this->model->updateData($datos);
					//$option = 2;
				} else {
					$arrResponse = ['status' => false, 'msg' => 'Acción no válida.'];
					responseJson($arrResponse);
				}

				if (!empty($request["status"])) {
					$msg = ($option === 1) ? 'Datos guardados correctamente.' : 'Datos actualizados correctamente.';
					$arrResponse = ['status' => true, 'numero' => $request["numero"] ?? 0, 'msg' => $msg];
				} else {
					$arrResponse = ['status' => false, 'msg' => 'No fue posible almacenar los datos.'];
				}
			} catch (Exception $e) {
				logFileSystem("Error en guardarUsuarioTiendas: " . $e->getMessage(), "ERROR");
				$arrResponse = ['status' => false, 'msg' => 'Ocurrió un error inesperado al guardar los datos.'];
			}

			responseJson($arrResponse);
		}

		exit();
	}



}
?>