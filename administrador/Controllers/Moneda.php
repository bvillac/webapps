<?php 
	class Moneda extends Controllers{
		
		public function __construct(){
			parent::__construct();
        	sessionStart();
        	getPermisos();
		}

		public function moneda(){
			if(empty($_SESSION['permisosMod']['r'])){
				header("Location:".base_url().'/dashboard');
			}
			$data['page_tag'] = "Moneda";
			$data['page_name'] = "Moneda";
			$data['page_title'] = "Moneda <small> ".TITULO_EMPRESA ."</small>";
			$data['fileJS'] = "funcionesMoneda.js";
			$this->views->getView($this,"moneda",$data);
		}

		public function getMonedas(){
			if($_SESSION['permisosMod']['r']){
			$model=new MonedaModel();
			$arrData = $model->consultarDatos();
			for ($i=0; $i < count($arrData); $i++) {
				$btnOpciones="";
				if($arrData[$i]['Estado'] == 1){
					$arrData[$i]['Estado'] = '<span class="badge badge-success">Activo</span>';
				}else{
					$arrData[$i]['Estado'] = '<span class="badge badge-danger">Inactivo</span>';
				}

				if($_SESSION['permisosMod']['r']){
					$btnOpciones .='<button class="btn btn-info btn-sm btnViewMoneda" onClick="fntViewMoneda(\''.$arrData[$i]['Ids'].'\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
				}
				if($_SESSION['permisosMod']['u']){
					$btnOpciones .='<button class="btn btn-primary  btn-sm btnEditMoneda" onClick="fntEditMoneda(\''.$arrData[$i]['Ids'].'\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
				}
				if($_SESSION['permisosMod']['d']){
					$btnOpciones .='<button class="btn btn-danger btn-sm btnDelMoneda" onClick="fntDeleteMoneda('.$arrData[$i]['Ids'].')" title="Eliminar Datos"><i class="fa fa-trash"></i></button>';
				}
				$arrData[$i]['options'] = '<div class="text-center">'.$btnOpciones.'</div>';
				
			}
			echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
		}
			die();
		}
	

		public function getMoneda(int $ids){
			if($_SESSION['permisosMod']['r']){
			$ids = intval(strClean($ids));
			$model=new MonedaModel();
			if($ids > 0){
				$arrData = $model->consultarDatosId($ids);
				//dep($arrData);
				if(empty($arrData)){
					$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
				}else{
					$arrResponse = array('status' => true, 'data' => $arrData);
				}
				
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}}
			die();
		}

	public function setMoneda(){
		//dep($_POST);
		if ($_POST) {			
			$model = new MonedaModel();
			if ( empty($_POST['txt_mon_simbolo']) || empty($_POST['txt_mon_nombre']) || empty($_POST['txt_mon_unidad_cambio'])
				|| empty($_POST['cmb_estado'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			} else {
				$Ids = intval($_POST['txth_ids']);
				$mon_simbolo = strClean($_POST['txt_mon_simbolo']);
				$mon_nombre = strClean($_POST['txt_mon_nombre']);
				$mon_unidad_cambio = floatval($_POST['txt_mon_unidad_cambio']);
				$estado = intval($_POST['cmb_estado']);
				if ($Ids == 0) {
					$option = 1;
					if($_SESSION['permisosMod']['w']){
					$result = $model->insertData($Ids, $mon_simbolo, $mon_nombre, $mon_unidad_cambio, $estado);
				}} else {
					$option = 2;
					if($_SESSION['permisosMod']['u']){
					$result = $model->updateData($Ids, $mon_simbolo, $mon_nombre, $mon_unidad_cambio, $estado);
				}}

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


	
		public function delMoneda(){
			if($_POST){
				if($_SESSION['permisosMod']['d']){
				$model = new MonedaModel();
				$ids = intval($_POST['ids']);
				$request = $model->deleteRegistro($ids);
				if($request){
					$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Registro');
				}else{
					$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Registro.');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}}
			die();
		}

	}
 ?>