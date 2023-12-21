<?php 
	class Empresa extends Controllers{
	
		public function __construct(){
			parent::__construct();
        	sessionStart();
        	getPermisos();
		}

		public function empresa(){
			if(empty($_SESSION['permisosMod']['r'])){
				header("Location:".base_url().'/dashboard');
			}
			$data['page_tag'] = "Empresa";
			$data['page_name'] = "Empresa";
			$data['page_title'] = "Empresa <small> ".TITULO_EMPRESA ."</small>";
			$data['fileJS'] = "funcionesEmpresa.js";
			$this->views->getView($this,"empresa",$data);
		}

		public function getEmpresas(){
			if($_SESSION['permisosMod']['r']){
				$model=new EmpresaModel();
				$arrData = $model->consultarDatos();
				for ($i=0; $i < count($arrData); $i++) {
					$btnOpciones="";
					if($arrData[$i]['Estado'] == 1){
						$arrData[$i]['Estado'] = '<span class="badge badge-success">Activo</span>';
					}else{
						$arrData[$i]['Estado'] = '<span class="badge badge-danger">Inactivo</span>';
					}

					if($_SESSION['permisosMod']['r']){
						$btnOpciones .='<button class="btn btn-info btn-sm btnViewEmpresa" onClick="fntViewEmpresa(\''.$arrData[$i]['Ids'].'\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
					}
					if($_SESSION['permisosMod']['u']){
						$btnOpciones .='<button class="btn btn-primary  btn-sm btnEditEmpresa" onClick="fntEditEmpresa(\''.$arrData[$i]['Ids'].'\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
					}
					if($_SESSION['permisosMod']['d']){
						$btnOpciones .='<button class="btn btn-danger btn-sm btnDelEmpresa" onClick="fntDeleteEmpresa('.$arrData[$i]['Ids'].')" title="Eliminar Datos"><i class="fa fa-trash"></i></button>';
					}
					$arrData[$i]['options'] = '<div class="text-center">'.$btnOpciones.'</div>';
					
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function getMoneda(){
			$model=new EmpresaModel();
			$htmlOptions = "";
			$arrData = $model->consultarMoneda();
			if(count($arrData) > 0 ){
				$htmlOptions = '<option value="0">SELECCIONAR</option>';
				for ($i=0; $i < count($arrData); $i++) { 
						$htmlOptions .= '<option value="'.$arrData[$i]['Ids'].'">'.$arrData[$i]['Nombre'].'</option>';
				}
			}
			echo $htmlOptions;
			die();		
		}


		public function getEmpresa(int $ids){
			if($_SESSION['permisosMod']['r']){
				$ids = intval(strClean($ids));
				$model=new EmpresaModel();
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

	public function setEmpresa(){
		//dep($_POST);
		if ($_POST) {			
			$model = new EmpresaModel();
			if ( empty($_POST['txt_emp_ruc']) || empty($_POST['txt_emp_razon_social']) || empty($_POST['txt_emp_nombre_comercial']) ||empty($_POST['txt_emp_direccion']) ||
						empty($_POST['txt_emp_correo']) ||empty($_POST['txt_emp_ruta_logo']) ) {
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			} else {
				$Ids = intval($_POST['txth_ids']);
				$ruc = strClean($_POST['txt_emp_ruc']);
				$razon = strClean($_POST['txt_emp_razon_social']);
				$nombre = strClean($_POST['txt_emp_nombre_comercial']);
				$direccion = strClean($_POST['txt_emp_direccion']);
				$correo = strClean($_POST['txt_emp_correo']);
				$logo = strClean($_POST['txt_emp_ruta_logo']);
				$moneda = intval($_POST['cmb_moneda']);
				$estado = intval($_POST['cmb_estado']);
				if ($Ids == 0) {
					$option = 1;
					if($_SESSION['permisosMod']['w']){
					$result = $model->insertData($Ids, $ruc, $razon, $nombre, $direccion, $correo, $logo, $moneda, $estado);
				} }else {
					$option = 2;
					if($_SESSION['permisosMod']['u']){
					$result = $model->updateData($Ids, $ruc, $razon, $nombre, $direccion, $correo, $logo, $moneda, $estado);
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


	
		public function delEmpresa(){
			if($_POST){
				if($_SESSION['permisosMod']['d']){
				$model = new EmpresaModel();
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