<?php 

	class Roles extends Controllers{
		public function __construct(){
			parent::__construct();
			sessionStart();
			getPermisos();			
		}

		public function Roles(){
			$data['page_id'] = 3;
			$data['page_tag'] = "Roles Usuario";
			$data['page_name'] = "rol_usuario";
			$data['page_title'] = "Roles Usuario <small> ".TITULO_EMPRESA ."</small>";
			$this->views->getView($this,"roles",$data);
		}

		public function getRoles()
		{
			$arrData = $this->model->selectRoles();

			for ($i=0; $i < count($arrData); $i++) {

				if($arrData[$i]['estado_logico'] == 1)
				{
					$arrData[$i]['estado_logico'] = '<span class="badge badge-success">Activo</span>';
				}else{
					$arrData[$i]['estado_logico'] = '<span class="badge badge-danger">Inactivo</span>';
				}

				$arrData[$i]['options'] = '<div class="text-center">
				<button class="btn btn-secondary btn-sm btnPermisosRol" rl="'.$arrData[$i]['rol_id'].'" title="Permisos"><i class="fa fa-key"></i></button>
				<button class="btn btn-primary btn-sm btnEditRol" rl="'.$arrData[$i]['rol_id'].'" title="Editar"><i class="fa fa-pencil"></i></button>
				<button class="btn btn-danger btn-sm btnDelRol" rl="'.$arrData[$i]['rol_id'].'" title="Eliminar"><i class="fa fa-trash"></i></button>
				</div>';
			}
			echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			die();
		}

		public function getRol(int $ids)
		{
			$rol_id = intval(strClean($ids));
			if($rol_id > 0)
			{
				$arrData = $this->model->selectRol($rol_id);
				if(empty($arrData)){
					$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
				}else{
					$arrResponse = array('status' => true, 'data' => $arrData);
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

	public function setRol(){
			//dep($_POST);
			//$model=new RolesModel();
			if ($_POST) {			
				$model = new RolesModel();
				if ( empty($_POST['txt_rol_nombre']) || empty($_POST['cmb_estado'])) {
					$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
				} else {
						$rol_id = intval($_POST['txth_rol_id']);
						$rol_nombre = strClean($_POST['txt_rol_nombre']);
						$estado = intval($_POST['cmb_estado']);
			if($rol_id == 0){
				
				//Crear Nuevo Registro
				$request_rol = $model->insertData($rol_id,$rol_nombre,$estado);
				//$request_rol = $model->insertData($rol_nombre,$estado);
				$option = 1;
			}else{
				//Actualizar Registro
				$request_rol = $model->updateData($rol_id,$rol_nombre, $estado);
				$option = 2;
			}

			if ($request_rol > 0) {
				if ($option == 1) {
					$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
				} else {
					$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
				}
			} else if ($request_rol == 'exist') {
				$arrResponse = array('status' => false, 'msg' => '¡Atención! Registro ya existe, ingrese otro.');
			} else {
				$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
			}
		}
		echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
	}
	die();
}


		public function delRol(){
			if($_POST){
				$rol_id = intval($_POST['ids']);
				$requestDelete = $this->model->deleteRol($rol_id);
				if($requestDelete == 'ok'){
					$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Registro');
				}else if($requestDelete == 'exist'){
					$arrResponse = array('status' => false, 'msg' => 'No es posible eliminar un Rol asociado a usuarios.');
				}else{
					$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Registro.');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		

	}
