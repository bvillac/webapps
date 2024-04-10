<?php 
	require_once("Models/ItemsModel.php");
	require_once("Models/ProveedorModel.php");
	require_once("Models/SecuenciasModel.php");

	use Spipu\Html2Pdf\Html2Pdf;
	require 'vendor/autoload.php';

	class Orden extends Controllers{
		//use ItemsModel;

		public function __construct(){
			parent::__construct();
			sessionStart();
			getPermisos();
		}

		public function Orden(){
			//if(empty($_SESSION['permisosMod']['r'])){
				//header("Location:".base_url().'/dashboard');
			//}		

			$data['page_tag'] = "Orden de Pedido";
			$data['page_title'] = "Orden de Pedido <small> ".TITULO_EMPRESA ."</small>";
			$data['page_name'] = "Orden de Pedido";
			$data['fileJS'] = "funcionesOrden.js";			
			$this->views->getView($this,"orden",$data);
		}

		public function ordenCompra(){
			//if(empty($_SESSION['permisosMod']['r'])){
				//header("Location:".base_url().'/dashboard');
			//}
			$objSec=new SecuenciasModel;
			$data['page_tag'] = "Orden de Compra";
			$data['page_title'] = "Orden de Compra <small> ".TITULO_EMPRESA ."</small>";
			$data['page_name'] = "Orden de Compra";
			$data['fileJS'] = "funcionesOrden.js";
			$data['Secuencia'] = $objSec->newSecuence("OC",1,FALSE);
			$this->views->getView($this,"ordencompra",$data);
		}

		public function getOrdenes(){			
			if($_SESSION['permisosMod']['r']){
				$model=new OrdenModel;
			    $arrData = $model->consultarDatos();				
				for ($i=0; $i < count($arrData); $i++) {
					$btnOpciones="";
					if($arrData[$i]['Autorizado'] == 1){
						$arrData[$i]['Autorizado'] = '<span class="badge badge-success">SI</span>';
					}else{
						$arrData[$i]['Autorizado'] = '<span class="badge badge-danger">NO</span>';
					}

					if($arrData[$i]['Estado'] == 1){
						$arrData[$i]['Estado'] = '<span class="badge badge-success">Activo</span>';
					}else{
						$arrData[$i]['Estado'] = '<span class="badge badge-danger">Inactivo</span>';
					}

					if($_SESSION['permisosMod']['r']){
						//if($arrData[$i]['Autorizado'] != 1){
							$btnOpciones .='<button class="btn btn-primary  btn-sm btnEdit" onClick="fntAutoriza('.$arrData[$i]['Ids'].')" title="Autorizar"><i class="fa fa-check"></i></button>';
						//}
						
					}

					if($_SESSION['permisosMod']['r']){
						$btnOpciones .=' <a title="Generar PDF" href="'.base_url().'/Orden/generarOrdenCompraPDF/'.$arrData[$i]['Ids'].'" target="_blanck" class="btn btn-primary btn-sm"> <i class="fa fa-file-pdf-o"></i> </a> ';
						//$btnOpciones .='<button class="btn btn-info btn-sm btnView" onClick="fntView(\''.$arrData[$i]['Ids'].'\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
					}
					
					if($_SESSION['permisosMod']['d']){
						//if($arrData[$i]['Autorizado'] != 1){
							$btnOpciones .='<button class="btn btn-danger btn-sm btnDel" onClick="fntAnular('.$arrData[$i]['Ids'].')" title="Anular Datos"><i class="fa fa-ban"></i></button>';
						//}
						
					}
					$arrData[$i]['options'] = '<div class="text-center">'.$btnOpciones.'</div>';
					
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function getProveedor(){
			if($_POST){
				$model=new OrdenModel;
				$Codigo = isset($_POST['codigo']) ? $_POST['codigo'] : "";
				$request = $model->consultarProveedor($Codigo);
				if($request){//True si Retorna Datos
					$arrResponse = array('status' => true, 'data' => $request, 'msg' => 'Datos Retornados correctamente.');						
				}else{
					$arrResponse = array("status" => false, "msg" => 'No Existen Datos');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();

		}

		public function getItemProducto(){
			if($_POST){
				$model=new ItemsModel;
				$Codigo = isset($_POST['codigo']) ? $_POST['codigo'] : "";
				$request = $model->consultarItemProducto($Codigo);
				if($request){
					$arrResponse = array('status' => true, 'data' => $request, 'msg' => 'Datos Retornados correctamente.');						
				}else{
					$arrResponse = array("status" => false, "msg" => 'No Existen Datos');
				}
				//putMessageLogFile($arrResponse);	
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();

		}


		public function ingresarOrden(){
			if($_POST){
				//dep($_POST);
				if(empty($_POST['cabecera']) || empty($_POST['dts_detalle']) || empty($_POST['accion'])  ){
					$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
				}else{					
					$request = "";
					$Cabecera = isset($_POST['cabecera']) ? $_POST['cabecera'] : array();
					$Detalle = isset($_POST['dts_detalle']) ? $_POST['dts_detalle'] : array();
					$accion = isset($_POST['accion']) ? $_POST['accion'] : "";
					
					$model=new OrdenModel;
					if($accion=="Create"){
						$option = 1;
						if($_SESSION['permisosMod']['w']){
							$request = $model->insertData($Cabecera,$Detalle);
						}
					}else{
						$option = 2;
						if($_SESSION['permisosMod']['u']){
							
						}
					}
					if($request["status"]){
						if($option == 1){
							$arrResponse = array('status' => true, 'numero' => $request["numero"], 'msg' => 'Datos guardados correctamente.');
						}else{
							$arrResponse = array('status' => true, 'numero' => $request["numero"], 'msg' => 'Datos Actualizados correctamente.');
						}		
					}else{
						$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
					}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function anularOrden(){
			if($_POST){
				if($_SESSION['permisosMod']['d']){
					$model=new OrdenModel;
					$ids = $_POST['ids'];
					$requestDelete = $model->anularOrden($ids);
					if($requestDelete){
						$arrResponse = array('status' => true, 'msg' => 'Se ha Anulado el Registro');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al Anular el Registro.');
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}

		public function autorizarOrden(){
			if($_POST){
				if($_SESSION['permisosMod']['d']){
					$model=new OrdenModel;
					$ids = $_POST['ids'];
					$request = $model->autorizarOrden($ids);
					if($request){
						$arrResponse = array('status' => true, 'msg' => 'Se ha Autorizado el Registro');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al Autorizar el Registro.');
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}


		public function generarOrdenCompraPDF($idpedido){
			if($_SESSION['permisosMod']['r']){
				if(is_numeric($idpedido)){
					$idpersona = "";
					//if($_SESSION['permisosMod']['r'] and $_SESSION['userData']['idrol'] == RCLIENTES){
					//	$idpersona = $_SESSION['userData']['idpersona'];
					//}
					$model=new OrdenModel;
					$data = $model->consultarOrdenPDF($idpedido,$idpersona);
					if(empty($data)){
						echo "Datos no encontrados";
					}else{
						$idpedido = $data['cabData']['com_id'];
						ob_end_clean();
						$html =getFile("Template/Modals/ordenCompraPDF",$data);
						$html2pdf = new Html2Pdf('p','A4','es','true','UTF-8');
						$html2pdf->writeHTML($html);
						$html2pdf->output('OC_'.$idpedido.'.pdf');
					}
				}else{
					echo "Dato no válido";
				}
			}else{
				header('Location: '.base_url().'/login');
				die();
			}
		}



	

	}

 ?>