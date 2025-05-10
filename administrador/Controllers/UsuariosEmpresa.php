<?php
use Spipu\Html2Pdf\Html2Pdf;
require 'vendor/autoload.php';

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
		$this->views->getView($this, "usuariosempresa", $data);
	}

	public function getUsuariosEmpresa()
	{
		//$arrData = (new UsuariosModel())->consultarDatos();
		$arrData = $this->model->consultarDatos();
		foreach ($arrData as &$objData) {
			$objData['Estado'] = $objData['Estado'] == 1
				? '<span class="badge badge-success">Activo</span>'
				: '<span class="badge badge-danger">Inactivo</span>';
			$objData['options'] = $this->getArrayOptions($objData['Ids']);
		}
		echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
		exit();
	}

	private function getArrayOptions($id)
	{
		$options = '<div class="text-center">';
		if ($_SESSION['permisosMod']['r']) {
			$options .= '<button class="btn btn-info btn-sm btnViewUsu" onClick="fntViewUsu(\'' . $id . '\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
		}
		if ($_SESSION['permisosMod']['u']) {
			$options .= '<button class="btn btn-primary  btn-sm btnEditUsu" onClick="fntEditUsu(\'' . $id . '\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
		}
		if ($_SESSION['permisosMod']['d']) {
			$options .= " <button class='btn btn-danger btn-sm btnDelUsu' onClick='fntDelUsu($id)' title='Eliminar'><i class='fa fa-trash'></i></button> ";
		}

		//$options .= " <a title='Evaluar Beneficiario' href='" . base_url() . "/Usuarios/rol/$id' class='btn btn-primary btn-sm'><i class='fa fa-list-alt'></i></a> ";
		//$options .= '<button class="btn btn-primary  btn-sm btnEmpresa" onClick="fntAsigEmpresa(\'' . $id . '\')" title="Asignar Empresa"><i class="fa fa-pencil"></i></button>';
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
					$request = $this->model->insertDataUsuarioEmpresa($datos);
					$option = 1;
				} elseif ($accion === "Edit") {
					checkPermission('u', 'usuariosempresa');
					$request = $this->model->updateData($datos);
					$option = 2;
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
				logFileSystem("Error en guardarUsuarioEmpresa: " . $e->getMessage(), "ERROR");
				$arrResponse = ['status' => false, 'msg' => 'Ocurrió un error inesperado al guardar los datos.'];
			}

			responseJson($arrResponse);
		}

		exit();
	}





}
?>