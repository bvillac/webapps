<?php
class TipoItem extends Controllers
{

	public function __construct()
	{
		parent::__construct();
        sessionStart();
        getPermisos();
	}

	public function tipoitem()
	{
		if (empty($_SESSION['permisosMod']['r'])) {
			header("Location:" . base_url() . '/dashboard');
		}
		$data['page_tag'] = "TipoItem";
		$data['page_name'] = "TipoItem";
		$data['page_title'] = "TipoItem <small> " . TITULO_EMPRESA . "</small>";
		$data['fileJS'] = "funcionesTipoItem.js";
		$this->views->getView($this, "tipoitem", $data);
	}

	public function getTipoItems()
	{
		if ($_SESSION['permisosMod']['r']) {
			$model = new TipoItemModel();
			$arrData = $model->consultarDatos();
			for ($i = 0; $i < count($arrData); $i++) {
				$btnOpciones = "";
				if ($arrData[$i]['Estado'] == 1) {
					$arrData[$i]['Estado'] = '<span class="badge badge-success">Activo</span>';
				} else {
					$arrData[$i]['Estado'] = '<span class="badge badge-danger">Inactivo</span>';
				}

				if ($_SESSION['permisosMod']['r']) {
					$btnOpciones .= '<button class="btn btn-info btn-sm btnViewTipoItem" onClick="fntViewTipoItem(\'' . $arrData[$i]['Ids'] . '\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
				}
				if ($_SESSION['permisosMod']['u']) {
					$btnOpciones .= '<button class="btn btn-primary  btn-sm btnEditTipoItem" onClick="fntEditTipoItem(\'' . $arrData[$i]['Ids'] . '\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
				}
				if ($_SESSION['permisosMod']['d']) {
					$btnOpciones .= '<button class="btn btn-danger btn-sm btnDelTipoItem" onClick="fntDeleteTipoItem(' . $arrData[$i]['Ids'] . ')" title="Eliminar Datos"><i class="fa fa-trash"></i></button>';
				}
				$arrData[$i]['options'] = '<div class="text-center">' . $btnOpciones . '</div>';
			}
			echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
		}
		die();
	}


	public function getTipoItem(int $ids)
	{
		if ($_SESSION['permisosMod']['r']) {
			$ids = intval(strClean($ids));
			$model = new TipoItemModel();
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
		}
		die();
	}

	public function setTipoItem()
	{
		//dep($_POST);
		if ($_POST) {
			$model = new TipoItemModel();
			if (empty($_POST['txt_tip_nombre']) || empty($_POST['cmb_estado'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			} else {
				$Ids = intval($_POST['txth_ids']);
				$tip_nombre = strClean($_POST['txt_tip_nombre']);
				$estado = intval($_POST['cmb_estado']);
				if ($Ids == 0) {
					$option = 1;
					if ($_SESSION['permisosMod']['w']) {
						$result = $model->insertData($Ids, $tip_nombre, $estado);
					}
				} else {
					$option = 2;
					if ($_SESSION['permisosMod']['u']) {
						$result = $model->updateData($Ids, $tip_nombre, $estado);
					}
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



	public function delTipoItem()
	{
		if ($_POST) {
			if ($_SESSION['permisosMod']['d']) {
				$model = new TipoItemModel();
				$ids = intval($_POST['ids']);
				$request = $model->deleteRegistro($ids);
				if ($request) {
					$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Registro');
				} else {
					$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Registro.');
				}
				echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			}
		}
		die();
	}
}
