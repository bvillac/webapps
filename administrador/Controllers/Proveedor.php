<?php 
	require_once("Models/ProveedorModel.php");
	use Spipu\Html2Pdf\Html2Pdf;
	require 'vendor/autoload.php';
	class Proveedor extends Controllers{
		
		public function __construct(){
			parent::__construct();
        	sessionStart();
        	getPermisos();
		}

		public function proveedor(){
			if(empty($_SESSION['permisosMod']['r'])){
				header("Location:".base_url().'/dashboard');
			}
			$data['page_tag'] = "Proveedor";
			$data['page_name'] = "Proveedor";
			$data['page_title'] = "Proveedor <small> ".TITULO_EMPRESA ."</small>";
			$data['fileJS'] = "funcionesProveedor.js";
			$this->views->getView($this,"proveedor",$data);
		}

		public function getProveedores(){
			if($_SESSION['permisosMod']['r']){
			$model=new ProveedorModel();
			$arrData = $model->consultarDatos();
			for ($i=0; $i < count($arrData); $i++) {
				$btnOpciones="";
				if($arrData[$i]['Estado'] == 1){
					$arrData[$i]['Estado'] = '<span class="badge badge-success">Activo</span>';
				}else{
					$arrData[$i]['Estado'] = '<span class="badge badge-danger">Inactivo</span>';
				}

				if($_SESSION['permisosMod']['r']){
					$btnOpciones .='<button class="btn btn-info btn-sm btnViewProveedor" onClick="fntViewProveedor(\''.$arrData[$i]['Ids'].'\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
				}
				if($_SESSION['permisosMod']['u']){
					$btnOpciones .='<button class="btn btn-primary  btn-sm btnEditProveedor" onClick="fntEditProveedor(\''.$arrData[$i]['Ids'].'\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
				}
				if($_SESSION['permisosMod']['d']){
					$btnOpciones .='<button class="btn btn-danger btn-sm btnDelProveedor" onClick="fntDeleteProveedor('.$arrData[$i]['Ids'].')" title="Eliminar Datos"><i class="fa fa-trash"></i></button>';
				}
				
				$arrData[$i]['options'] = '<div class="text-center">'.$btnOpciones.'</div>';
				
			}
			echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
		}
			die();
		}

		public function getPago(){
			$model=new ProveedorModel();
			$htmlOptions = "";
			$arrData = $model->consultarPago();
			if(count($arrData) > 0 ){
				$htmlOptions = '<option value="0">SELECCIONAR</option>';
				for ($i=0; $i < count($arrData); $i++) { 
						$htmlOptions .= '<option value="'.$arrData[$i]['Ids'].'">'.$arrData[$i]['Nombre'].'</option>';
				}
			}
			echo $htmlOptions;
			die();		
		}


		public function getProveedor($ids){
			if($_SESSION['permisosMod']['r']){
				//$ids = intval(strClean($ids));
				$ids = strClean($ids);
				$model=new ProveedorModel();
				if($ids!=""){
					$arrData = $model->consultarDatosId($ids);
					if(empty($arrData)){
						$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
					}else{
						$arrResponse = array('status' => true, 'data' => $arrData);
					}					
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}

	public function setProveedor(){
		if ($_POST) {	
			$model = new ProveedorModel();
			if (empty($_POST['txt_codigo']) || empty($_POST['txt_pro_tipo_dni']) || empty($_POST['txt_pro_cedula_ruc']) || empty($_POST['txt_pro_nombre']) || empty($_POST['txt_pro_direccion']) || 
			    empty($_POST['txt_pro_telefono']) || empty($_POST['txt_pro_correo']) || empty($_POST['cmb_pago']) || empty($_POST['cmb_estado'])) {
				     $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			} else {
				$Ids = strClean($_POST['txth_ids']);
				$codigo = strClean($_POST['txt_codigo']);
				$tipo = strClean($_POST['txt_pro_tipo_dni']);
				$cedula = strClean($_POST['txt_pro_cedula_ruc']);
				$nombre = strClean($_POST['txt_pro_nombre']);
				$direccion = strClean($_POST['txt_pro_direccion']);
				$telefono = strClean($_POST['txt_pro_telefono']);
				$correo = strClean($_POST['txt_pro_correo']);
				$pago = intval($_POST['cmb_pago']);
				$estado = intval($_POST['cmb_estado']);
				if ($Ids == "") {
					$option = 1;
					if($_SESSION['permisosMod']['w']){
					$result = $model->insertData($codigo, $tipo, $cedula, $nombre, $direccion, $telefono, $correo, $pago, $estado);
				}}else {
					$option = 2;
					if($_SESSION['permisosMod']['u']){
					$result = $model->updateData($codigo, $tipo, $cedula, $nombre, $direccion, $telefono, $correo, $pago, $estado);
				}}

				if ($result > 0) {
					if ($option == 1) {
						$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
					} else {							
						$arrResponse = array('status' => true, 'msg' => 'Datos actualizados correctamente.');
					}				
				}else if($result == 'exist'){
					$arrResponse = array('status' => false, 'msg' => '¡Atención! ya existe el Código Ingresado.');		
				}else{
					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function generarReporteProveedorPDF($idProveedor){
		if($_SESSION['permisosMod']['r']){
			if(is_string($idProveedor)){
				$idpersona = "";
				//if($_SESSION['permisosMod']['r'] and $_SESSION['userData']['idrol'] == RCLIENTES){
				//	$idpersona = $_SESSION['userData']['idpersona'];
				//}
				$model=new ProveedorModel;
				$data = $model->consultarReporteProveedorPDF($idProveedor);
				putMessageLogFile($data);
				if(empty($data)){
					echo "Datos no encontrados";
				}else{
					$idCliente = $data['cabReporte']['pro_codigo'];
					ob_end_clean();
					$html =getFile("Template/Modals/ReporteProveedorPDF",$data);
					$html2pdf = new Html2Pdf('p','A4','es','true','UTF-8');
					$html2pdf->writeHTML($html);
					$FechaActual= date('m-d-Y H:i:s a', time()); 
					$html2pdf->output('ReporteProveedores_'.$FechaActual.'.pdf');
				}
			}else{
				echo "Dato no válido";
			}
		}else{
			header('Location: '.base_url().'/login');
			die();
		}
	}

	public function getProveedoresReporte(){			
		if($_SESSION['permisosMod']['r']){
			$model=new ProveedorModel;
			$arrData = $model->consultarDatosProveedores();				
			for ($i=0; $i < count($arrData); $i++) {
				$btnOpciones="";				
				if($_SESSION['permisosMod']['r']){
					$btnOpciones .=' <a title="Generar PDF" href="'.base_url().'//generarReporteProveedorPDF/'.$arrData[$i]['Ids'].'" target="_blanck" class="btn btn-primary btn-sm"> <i class="fa fa-file-pdf-o"></i> </a> ';
				}
				
				$arrData[$i]['options'] = '<div class="text-center">'.$btnOpciones.'</div>';
				
			}
			echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
		}
		die();
	}
	
		public function delProveedor(){
			if($_POST){
				if($_SESSION['permisosMod']['d']){
					$model = new ProveedorModel();
					//$ids = intval($_POST['ids']);
					$ids = $_POST['ids'];
					$request = $model->deleteRegistro($ids);
					if($request){
						$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Registro');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Registro.');
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}


		public function getProveedorbuscar(){			
			$model=new ProveedorModel;
			$arrData = $model->consultarDatos();				
			for ($i=0; $i < count($arrData); $i++) {
				$btnOpciones="";			
				if($_SESSION['permisosMod']['r']){
					$btnOpciones .='<button class="btn btn-info btn-sm btnView" onClick="buscarProveedor(\''.$arrData[$i]['Ids'].'\')" title="Agregar"><i class="fa fa-plus"></i></button>';
				}					
				$arrData[$i]['options'] = '<div class="text-center">'.$btnOpciones.'</div>';
			}
			echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			die();
		}

	}
 ?>