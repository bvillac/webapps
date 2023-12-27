<?php
require_once("Models/EstablecimientoModel.php");
require_once("Models/PuntoModel.php");
class Secuencias extends Controllers
{
	//private $pagView="Secuencias";

	public function __construct()
	{
		parent::__construct();
        sessionStart();
        getPermisos();
	}
	public function secuencias()
	{
		if (empty($_SESSION['permisosMod']['r'])) {
			header("Location:" . base_url() . '/dashboard');
		}
		$Establecimiento = new EstablecimientoModel();
		$EstData = $Establecimiento->consultarEstablecimiento();
		$data['Establecimiento'] = $EstData;
		$Punto= new PuntoModel();
		$data['Punto'] = $Punto->consultarPuntoEmision($EstData[0]['Ids']);
		$data['idsEmpresa']=$_SESSION['idEmpresa'];		
		$data['page_tag'] = "Secuencias";
		$data['page_name'] = "Secuencias";
		$data['page_title'] = "Secuencias <small> " . TITULO_EMPRESA . "</small>";
		$this->views->getView($this, "secuencias", $data);
	}

	public function getSecuencias()
	{
		if ($_SESSION['permisosMod']['r']) {
			$model = new SecuenciasModel();
			$arrData = $model->consultarDatos();
			for ($i = 0; $i < count($arrData); $i++) {
				$btnOpciones = "";
				if ($arrData[$i]['Estado'] == 1) {
					$arrData[$i]['Estado'] = '<span class="badge badge-success">Activo</span>';
				} else {
					$arrData[$i]['Estado'] = '<span class="badge badge-danger">Inactivo</span>';
				}

				if ($_SESSION['permisosMod']['r']) {
					$btnOpciones .= '<button class="btn btn-info btn-sm btnViewSec" onClick="fntViewSec(\'' . $arrData[$i]['Ids'] . '\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
				}
				if ($_SESSION['permisosMod']['u']) {
					$btnOpciones .= '<button class="btn btn-primary  btn-sm btnEditSec" onClick="fntEditSec(\'' . $arrData[$i]['Ids'] . '\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
				}
				if ($_SESSION['permisosMod']['d']) {
					$btnOpciones .= '<button class="btn btn-danger btn-sm btnDelSec" onClick="fntDeleteSec(' . $arrData[$i]['Ids'] . ')" title="Eliminar Datos"><i class="fa fa-trash"></i></button>';
				}
				$arrData[$i]['options'] = '<div class="text-center">' . $btnOpciones . '</div>';
			}
			echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	

	public function getPunto(int $ids)
	{
		$model = new SecuenciasModel();
		$htmlOptions = "";
		$arrData = $model->consultarPunto($ids);
		if (count($arrData) > 0) {
			//$htmlOptions = '<option value="0">SELECCIONAR</option>';
			for ($i = 0; $i < count($arrData); $i++) {
				$htmlOptions .= '<option value="' . $arrData[$i]['Ids'] . '">' . $arrData[$i]['Nombre'] . '</option>';
			}
		}
		echo $htmlOptions;
		die();
	}

	public function editarSecuencia(int $ids)
	{
		if ($_SESSION['permisosMod']['r']) {
			$ids = intval(strClean($ids));
			$model = new SecuenciasModel();
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

	public function getSecuencia(int $ids)
	{
		if ($_SESSION['permisosMod']['r']) {
			$ids = intval(strClean($ids));
			$model = new SecuenciasModel();
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

	public function setSecuencia()
	{
		//dep($_POST);
		if ($_POST) {
			$model = new SecuenciasModel();
			if (
				empty($_POST['cmb_punto']) || empty($_POST['txt_sec_tipo']) || empty($_POST['txt_sec_numero'])
				|| empty($_POST['txt_sec_nombre'])  || empty($_POST['cmb_estado'])
			) {
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			} else {
				$Ids = intval($_POST['txth_ids']);
				$punto = intval($_POST['cmb_punto']);
				$tipo = strClean($_POST['txt_sec_tipo']);
				$numero = strClean($_POST['txt_sec_numero']);
				$nombre = strClean($_POST['txt_sec_nombre']);
				$estado = intval($_POST['cmb_estado']);
				if ($Ids == 0) {
					$option = 1;
					if ($_SESSION['permisosMod']['w']) {
						$result = $model->insertData($Ids, $punto, $tipo, $numero, $nombre, $estado);
					}
				} else {
					$option = 2;
					if ($_SESSION['permisosMod']['u']) {
						$result = $model->updateData($Ids, $punto, $tipo, $numero, $nombre, $estado);
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



	public function delSecuencia()
	{
		if ($_POST) {
			if ($_SESSION['permisosMod']['d']) {
				$model = new SecuenciasModel();
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
