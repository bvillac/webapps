<?php
class Permisos extends Controllers
{
	public function __construct()
	{
		parent::__construct();
        sessionStart();
        getPermisos();
	}

	public function getPermisosRol(int $idrol)
	{
		$model = new PermisosModel();
		$rolid = intval($idrol);
		if ($rolid > 0) {
			//$arrModulos = $this->model->selectModulos();
			$arrModulos = $model->selectModulos();
			$arrPermisosRol = $model->selectPermisosRol($rolid);
			$arrPermisos = array('r' => 0, 'w' => 0, 'u' => 0, 'd' => 0);
			$arrPermisoRol = array('idrol' => $rolid);

			if (empty($arrPermisosRol)) {
				for ($i = 0; $i < count($arrModulos); $i++) {
					$arrModulos[$i]['permisos'] = $arrPermisos; //Agregamos una columna se Permiso con otro Array
				}
			} else {
				for ($i = 0; $i < count($arrModulos); $i++) {
					$arrPermisos = array(
						'r' => $arrPermisosRol[$i]['r'],
						'w' => $arrPermisosRol[$i]['w'],
						'u' => $arrPermisosRol[$i]['u'],
						'd' => $arrPermisosRol[$i]['d']
					);
					if ($arrModulos[$i]['idmodulo'] == $arrPermisosRol[$i]['moduloid']) {
						$arrModulos[$i]['permisos'] = $arrPermisos;
					}
				}
			}
			$arrPermisoRol['modulos'] = $arrModulos;
			$html = getModal("modalPermisos", $arrPermisoRol);
			//dep($arrPermisoRol);
		}
		die();
	}

	public function setPermisos()
	{
		//dep($_POST);
		if ($_POST) {
			$rol_id = intval($_POST['rol_id']);
			$modulos = $_POST['modulos']; //Recibe Moudloes			
			$this->model->deletePermisos($rol_id);
			foreach ($modulos as $modulo) {
				//putMessageLogFile($modulo);
				$mod_id = $modulo['mod_id'];
				$r = empty($modulo['r']) ? 0 : 1;
				$w = empty($modulo['w']) ? 0 : 1;
				$u = empty($modulo['u']) ? 0 : 1;
				$d = empty($modulo['d']) ? 0 : 1;
				$request = $this->model->insertPermisos($rol_id, $mod_id, $r, $w, $u, $d);
			}
			if ($request > 0) {
				$arrResponse = array('status' => true, 'msg' => 'Permisos asignados correctamente.');
			} else {
				$arrResponse = array("status" => false, "msg" => 'No es posible asignar los permisos.');
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}
}
