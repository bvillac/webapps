<?php
require_once("Models/CentroAtencionModel.php");
require_once("Models/SalonModel.php");
class Instructor extends Controllers
{


	public function __construct()
	{
		parent::__construct();
		sessionStart();
		getPermisos();
	}

	public function instructor()
	{
		if (empty($_SESSION['permisosMod']['r'])) {
			header("Location:" . base_url() . '/dashboard');
		}
		$data['page_tag'] = "Instructor";
		$data['page_name'] = "Instructor";
		$data['page_title'] = "Instructor <small> " . TITULO_EMPRESA . "</small>";
		$this->views->getView($this, "instructor", $data);
	}

	public function nuevo()
	{
		if (empty($_SESSION['permisosMod']['r'])) {
			header("Location:" . base_url() . '/dashboard');
		}
		$modelCentro = new CentroAtencionModel();
		$data['centroAtencion'] = $modelCentro->consultarCentroEmpresa();
		$data['page_tag'] = "Nuevo Instructor";
		$data['page_name'] = "Nuevo Instructor";
		$data['page_title'] = "Nuevo Instructor <small> " . TITULO_EMPRESA . "</small>";
		$this->views->getView($this, "nuevo", $data);
	}

	public function getInstructor()
	{
		//if ($_SESSION['permisosMod']['r']) {
		$arrData = $this->model->consultarDatos();
		for ($i = 0; $i < count($arrData); $i++) {
			$btnOpciones = "";
			if ($arrData[$i]['Estado'] == 1) {
				$arrData[$i]['Estado'] = '<span class="badge badge-success">Activo</span>';
			} else {
				$arrData[$i]['Estado'] = '<span class="badge badge-danger">Inactivo</span>'; //target="_blanck"
			}

			if ($_SESSION['permisosMod']['r']) {
				$btnOpciones .= '<button class="btn btn-info btn-sm btnViewInstructor" onClick="fntViewInstructor(\'' . $arrData[$i]['Ids'] . '\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
			}
			if ($_SESSION['permisosMod']['u']) {
				$btnOpciones .= ' <a title="Editar Datos" href="' . base_url() . '/Instructor/editar/' . $arrData[$i]['Ids'] . '"  class="btn btn-primary btn-sm"> <i class="fa fa-pencil"></i> </a> ';
			}
			if ($_SESSION['permisosMod']['d']) {
				$btnOpciones .= '<button class="btn btn-danger btn-sm btnDelInstructor" onClick="fntDeleteInstructor(' . $arrData[$i]['Ids'] . ')" title="Eliminar Datos"><i class="fa fa-trash"></i></button>';
			}
			$arrData[$i]['options'] = '<div class="text-center">' . $btnOpciones . '</div>';
		}
		echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
		//}
		die();
	}


	public function getInstructorIds(int $ids)
	{
		//if ($_SESSION['permisosMod']['r']) {
		$ids = intval(strClean($ids));
		if ($ids > 0) {
			$arrData = $this->model->consultarDatosId($ids);
			//dep($arrData);
			if (empty($arrData)) {
				$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
			} else {
				$arrResponse = array('status' => true, 'data' => $arrData);
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		//}
		die();
	}





	public function delInstructor()
	{
		if ($_POST) {
			//if ($_SESSION['permisosMod']['d']) {
			$ids = intval($_POST['ids']);
			$request = $this->model->deleteRegistro($ids);
			if ($request) {
				$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Registro');
			} else {
				$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Registro.');
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			//}
		}
		die();
	}

	public function ingresarInstructor()
	{
		if ($_POST) {
			//dep($_POST);
			if (empty($_POST['instructor']) || empty($_POST['accion'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			} else {
				$request = "";
				$datos = isset($_POST['instructor']) ? json_decode($_POST['instructor'], true) : array();
				$accion = isset($_POST['accion']) ? $_POST['accion'] : "";
				if ($accion == "Create") {
					$option = 1;
					//if($_SESSION['permisosMod']['w']){
					$request = $this->model->insertData($datos);
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
					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function editar($ids)
	{
		//if($_SESSION['permisosMod']['r']){
		if (is_numeric($ids)) {
			$data = $this->model->consultarDatosId($ids);
			if (empty($data)) {
				echo "Datos no encontrados";
			} else {
				$modelCentro = new CentroAtencionModel();
				$data['centroAtencion'] = $modelCentro->consultarCentroEmpresa();
				$modelSalon = new SalonModel();
				$data['dataSalon'] = $modelSalon->consultarSalones($data['CentroId']);
				$data['page_tag'] = "Editar Instructor";
				$data['page_name'] = "Editar Instructor";
				$data['page_title'] = "Editar Instructor <small> " . TITULO_EMPRESA . "</small>";
				//$data['fileJS'] = "funcionesInstructor.js";
				$this->views->getView($this, "editar", $data);
			}
		} else {
			echo "Dato no válido";
		}
		//}else{
		//header('Location: '.base_url().'/login');
		//die();
		//}
		die();
	}
}
