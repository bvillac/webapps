<?php
class Persona extends Controllers
{
	public function __construct()
	{
		parent::__construct();
        sessionStart();
        getPermisos();
	}

	public function persona()
	{
		if (empty($_SESSION['permisosMod']['r'])) {
			header("Location:" . base_url() . '/dashboard');
		}
		$data['page_tag'] = "Persona";
		$data['page_name'] = "Persona";
		$data['page_title'] = "Persona <small> " . TITULO_EMPRESA . "</small>";
		$data['fileJS'] = "funcionesPersona.js";
		$this->views->getView($this, "persona", $data);
	}

	public function getPersonas()
	{
		$model = new PersonaModel();
		$arrData = $model->consultarDatos();
		for ($i = 0; $i < count($arrData); $i++) {
			if ($arrData[$i]['Estado'] == 1) {
				$arrData[$i]['Estado'] = '<span class="badge badge-success">Activo</span>';
			} else {
				$arrData[$i]['Estado'] = '<span class="badge badge-danger">Inactivo</span>';
			}

			$arrData[$i]['options'] = '<div class="text-center">
				<button class="btn btn-info btn-sm btnViewPersona" onClick="fntViewPersona(' . $arrData[$i]['Ids'] . ')" title="Ver Datos"><i class="fa fa-eye"></i></button>
				<button class="btn btn-primary  btn-sm btnEditPersona" onClick="fntEditPersona(' . $arrData[$i]['Ids'] . ')" title="Editar Datos"><i class="fa fa-pencil"></i></button>
				<button class="btn btn-danger btn-sm btnDelPersona" onClick="fntDelPersona(' . $arrData[$i]['Ids'] . ')" title="Eliminar Registro"><i class="fa fa-trash"></i></button>
				</div>';
		}
		echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
		die();
	}


	public function getPersona(int $ids)
	{
		$ids = intval(strClean($ids));
		$model = new PersonaModel();
		if ($ids > 0) {
			$arrData = $model->consultarDatosId($ids);
			//dep($arrData);
			if (empty($arrData)) {
				$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
			} else {
				$arrResponse = array('status' => true, 'data' => $arrData);
			}

			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function setPersona()
	{
		//dep($_POST);
		if ($_POST) {
			$model = new PersonaModel();
			if (
				empty($_POST['txt_per_cedula']) || empty($_POST['txt_per_nombre']) || empty($_POST['txt_per_apellido'])
				|| empty($_POST['txt_per_fecha_nacimiento']) || empty($_POST['txt_per_telefono']) || empty($_POST['txt_per_direccion']) || empty($_POST['txt_per_genero']) || empty($_POST['cmb_estado'])
			) {
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			} else {
				$Ids = intval($_POST['txth_ids']);
				$per_cedula = strClean($_POST['txt_per_cedula']);
				$per_nombre = strClean($_POST['txt_per_nombre']);
				$per_apellido = strClean($_POST['txt_per_apellido']);
				$per_fecha_nacimiento = strftime($_POST['txt_per_fecha_nacimiento']);
				$per_telefono = strClean($_POST['txt_per_telefono']);
				$per_direccion = strClean($_POST['txt_per_direccion']);
				$per_genero = strClean($_POST['txt_per_genero']);
				$estado = intval($_POST['cmb_estado']);
				if ($Ids == 0) {
					$option = 1;
					$result = $model->insertData($Ids, $per_cedula, $per_nombre, $per_apellido, $per_fecha_nacimiento, $per_telefono, $per_direccion, $per_genero, $estado);
				} else {
					$option = 2;
					$result = $model->updateData($Ids, $per_cedula, $per_nombre, $per_apellido, $per_fecha_nacimiento, $per_telefono, $per_direccion, $per_genero, $estado);
				}

				if ($result > 0) {
					if ($option == 1) {
						$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
					} else {
						$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
					}
				} else if ($result == 'exist') {
					$arrResponse = array('status' => false, 'msg' => '¡Atención! Registro ya existe, ingrese otro.');
				} else {
					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}



	public function delPersona()
	{
		if ($_POST) {
			$model = new PersonaModel();
			$ids = intval($_POST['ids']);
			$request = $model->deleteRegistro($ids);
			if ($request) {
				$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Registro');
			} else {
				$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Registro.');
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}


	public function getPersonabuscar()
	{
		$arrData = $arrData = $this->model->consultarDatos();
		for ($i = 0; $i < count($arrData); $i++) {
			$btnOpciones = "";
			$btnOpciones .= '<button class="btn btn-info btn-sm btnView" onClick="buscarPersonaDni(\'' . $arrData[$i]['Ids'] . '\')" title="Agregar"><i class="fa fa-plus"></i></button>';
			$arrData[$i]['options'] = '<div class="text-center">' . $btnOpciones . '</div>';
		}
		//dep($arrData);
		echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
		die();
	}

	public function getPersonaIdDni()
	{
		if ($_POST) {
			$Codigo = isset($_POST['codigo']) ? $_POST['codigo'] : "";
			$request = $this->model->consultarDatosIdCedula($Codigo);
			if ($request) {
				$arrResponse = array('status' => true, 'data' => $request, 'msg' => 'Datos Retornados correctamente.');
			} else {
				$arrResponse = array("status" => false, "msg" => 'No Existen Datos');
			}
			//putMessageLogFile($arrResponse);	
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function buscarAutoPersona()
	{
		if ($_POST) {
			//dep($_POST);
			$Buscar = isset($_POST['buscar']) ? $_POST['buscar'] : "";
			$request = $this->model->consultarDatosCedulaNombres($Buscar);
			if ($request) {
				$arrResponse = array('status' => true, 'data' => $request, 'msg' => 'Datos Retornados correctamente.');
			} else {
				$arrResponse = array('status' => false, 'msg' => 'No Existen Datos');
			}
			//putMessageLogFile($arrResponse);	
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function consultarPersonaId()
	{
		if ($_POST) {
			$Codigo = isset($_POST['codigo']) ? $_POST['codigo'] : "0";
			$Codigo = intval(strClean($Codigo));
			$arrData = $this->model->consultarDatosId($Codigo);
			if (empty($arrData)) {
				$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
			} else {
				$arrResponse = array('status' => true, 'data' => $arrData);
			}
			
			//putMessageLogFile($arrResponse);	
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function ingresarPersonaDatos()
	{
		if ($_POST) {
			//dep($_POST);
			if (empty($_POST['persona']) || empty($_POST['accion'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			} else {
				$request = "";
				$datos = isset($_POST['persona']) ? json_decode($_POST['persona'], true) : array();
				$accion = isset($_POST['accion']) ? $_POST['accion'] : "";
				if ($accion == "Create") {
					$option = 1;
					//if($_SESSION['permisosMod']['w']){
					$request = $this->model->insertDataPersona($datos);
					//}
				} else {
					$option = 2;
					//if($_SESSION['permisosMod']['u']){
					$request = $this->model->updateData($datos);
					//}
				}
				if ($request["status"]) {
					if ($option == 1) {
						$arrResponse = array('status' => true, 'numero' => $request["numero"], 'msg' => 'Datos guardados correctamente.');
					} else {
						$arrResponse = array('status' => true, 'numero' => $request["numero"], 'msg' => 'Datos Actualizados correctamente.');
					}
				} else {
					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos: ' . $request["message"]);
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}



}
