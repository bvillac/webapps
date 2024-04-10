<?php 
require_once("Models/ClienteModel.php");
use Spipu\Html2Pdf\Html2Pdf;
require 'vendor/autoload.php';
	class Cliente extends Controllers{
		
		public function __construct(){
			parent::__construct();
        	sessionStart();
        	getPermisos();
		}

		public function cliente(){
			if(empty($_SESSION['permisosMod']['r'])){
				header("Location:".base_url().'/dashboard');
			}
			$data['page_tag'] = "Cliente";
			$data['page_name'] = "Cliente";
			$data['page_title'] = "Cliente <small> ".TITULO_EMPRESA ."</small>";
			$data['fileJS'] = "funcionesCliente.js";
			$this->views->getView($this,"cliente",$data);
		}

		public function getClientes(){
			if($_SESSION['permisosMod']['r']){
				$model=new ClienteModel();
				$arrData = $model->consultarDatos();		
				for ($i=0; $i < count($arrData); $i++) {
					$btnOpciones="";
					if($arrData[$i]['Estado'] == 1){
						$arrData[$i]['Estado'] = '<span class="badge badge-success">Activo</span>';
					}else{
						$arrData[$i]['Estado'] = '<span class="badge badge-danger">Inactivo</span>';
					}

					if($arrData[$i]['Distribuidor'] == 1){
						$arrData[$i]['Distribuidor'] = '<span>DISTRIBUIDOR</span>';
					}else{
						$arrData[$i]['Distribuidor'] = '<span>FINAL</span>';
					}

					if($_SESSION['permisosMod']['r']){
						$btnOpciones .='<button class="btn btn-info btn-sm btnViewCliente" onClick="fntViewCliente(\''.$arrData[$i]['Ids'].'\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
					}
					if($_SESSION['permisosMod']['u']){
						$btnOpciones .='<button class="btn btn-primary  btn-sm btnEditCliente" onClick="fntEditCliente(\''.$arrData[$i]['Ids'].'\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
					}
					if($_SESSION['permisosMod']['d']){
						$btnOpciones .='<button class="btn btn-danger btn-sm btnDelCliente" onClick="fntDeleteCliente(\''.$arrData[$i]['Ids'].'\')" title="Eliminar Datos"><i class="fa fa-trash"></i></button>';
					}
					$arrData[$i]['options'] = '<div class="text-center">'.$btnOpciones.'</div>';
					
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
				die();
		}

		public function getPago(){
			$model=new ClienteModel();
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


		public function getCliente($ids){		
			if($_SESSION['permisosMod']['r']){				
				//$ids = intval(strClean($ids));
				$ids = strClean($ids);
				$model=new ClienteModel();				
				if($ids!=""){
					$arrData = $model->consultarDatosId($ids);				    
					if(empty($arrData)){
						$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
					}else{
						$arrResponse = array('status' => true, 'data' => $arrData);
					}					
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
				die();
			}
		}

	public function setCliente(){
		if ($_POST) {	
			$model = new ClienteModel();
			if (empty($_POST['txt_codigo']) || empty($_POST['txt_cli_tipo_dni']) || empty($_POST['txt_cli_cedula_ruc']) || empty($_POST['txt_cli_nombre']) || empty($_POST['txt_cli_direccion']) || 
			    empty($_POST['txt_cli_correo']) || empty($_POST['txt_cli_telefono'])  ) {
				     $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			} else {
				$Ids = strClean($_POST['txth_ids']);
				$codigo = strClean($_POST['txt_codigo']);
				$tipo = strClean($_POST['txt_cli_tipo_dni']);
				$cedula = strClean($_POST['txt_cli_cedula_ruc']);
				$nombre = strClean($_POST['txt_cli_nombre']);
				$direccion = strClean($_POST['txt_cli_direccion']);
				$correo = strClean($_POST['txt_cli_correo']);
				$telefono = strClean($_POST['txt_cli_telefono']);
				$cli_distribuidor = intval($_POST['cmb_distribuidor']);
				$cli_tipo_precio = intval($_POST['cmb_tipo_precio']);
				$url =  "";//strClean($_POST['txt_ruta_certificado']);
				$pago = intval($_POST['cmb_pago']);
				$estado = intval($_POST['cmb_estado']);
				if ($Ids == "") {
					$option = 1;
					if($_SESSION['permisosMod']['w']){
					$result = $model->insertData($codigo, $tipo, $cedula, $nombre, $direccion,$correo, $telefono, $cli_distribuidor, $cli_tipo_precio, $url, $pago, $estado);
				}}else {
					$option = 2;
					if($_SESSION['permisosMod']['u']){
						$result = $model->updateData($codigo, $tipo, $cedula, $nombre, $direccion,$correo, $telefono, $cli_distribuidor, $cli_tipo_precio, $url, $pago, $estado);
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


	public function generarReporteClientePDF($idCliente){
		if($_SESSION['permisosMod']['r']){
			if(is_string($idCliente)){
				$idpersona = "";
				//if($_SESSION['permisosMod']['r'] and $_SESSION['userData']['idrol'] == RCLIENTES){
				//	$idpersona = $_SESSION['userData']['idpersona'];
				//}
				$model=new ClienteModel;
				$data = $model->consultarReporteClientePDF($idCliente);
				//putMessageLogFile($data);
				if(empty($data)){
					echo "Datos no encontrados";
				}else{
					$idCliente = $data['cabReporte']['cli_codigo'];
					ob_end_clean();
					$html =getFile("Template/Modals/ReporteClientePDF",$data);
					$html2pdf = new Html2Pdf('p','A4','es','true','UTF-8');
					$html2pdf->writeHTML($html);
					$FechaActual= date('m-d-Y H:i:s a', time()); 
					$html2pdf->output('ReporteClientes_'.$FechaActual.'.pdf');
				}
			}else{
				echo "Dato no válido";
			}
		}else{
			header('Location: '.base_url().'/login');
			die();
		}
	}

	public function getClientesReporte(){			
		if($_SESSION['permisosMod']['r']){
			$model=new ClienteModel;
			$arrData = $model->consultarDatosClientes();				
			for ($i=0; $i < count($arrData); $i++) {
				$btnOpciones="";				
				if($_SESSION['permisosMod']['r']){
					$btnOpciones .=' <a title="Generar PDF" href="'.base_url().'//generarReporteClientePDF/'.$arrData[$i]['Ids'].'" target="_blanck" class="btn btn-primary btn-sm"> <i class="fa fa-file-pdf-o"></i> </a> ';
				}
				
				$arrData[$i]['options'] = '<div class="text-center">'.$btnOpciones.'</div>';
				
			}
			echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

		public function delCliente(){
			if($_POST){
				if($_SESSION['permisosMod']['d']){
				$model = new ClienteModel();
				//$ids = intval($_POST['ids']);
				$ids = $_POST['ids'];
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