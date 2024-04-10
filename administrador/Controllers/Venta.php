<?php 
	require_once("Models/ItemsModel.php");
	require_once("Models/ProveedorModel.php");
	require_once("Models/SecuenciasModel.php");
	use Spipu\Html2Pdf\Html2Pdf;
	require 'vendor/autoload.php';

	class Venta extends Controllers{


		public function __construct(){
			parent::__construct();
			sessionStart();
			getPermisos();
		}

		public function Venta(){
			//if(empty($_SESSION['permisosMod']['r'])){
				//header("Location:".base_url().'/dashboard');
			//}		
			dep("lleg");
			$data['page_tag'] = "Venta";
			$data['page_title'] = "Venta <small> ".TITULO_EMPRESA ."</small>";
			$data['page_name'] = "Venta";
			$data['fileJS'] = "funcionesVenta.js";			
			$this->views->getView($this,"venta",$data);
		}



		//ok
		public function getVentas(){			
			if($_SESSION['permisosMod']['r']){
				$model=new VentaModel;
			    $arrData = $model->consultarDatosVentas();				
				for ($i=0; $i < count($arrData); $i++) {
					$btnOpciones="";
					

					if($arrData[$i]['Estado'] == 1){
						$arrData[$i]['Estado'] = '<span class="badge badge-success">Pagada</span>';
					}else{
						$arrData[$i]['Estado'] = '<span class="badge badge-danger">Anulado</span>';
					}		
					if($arrData[$i]['Despacho'] == 1){
						$arrData[$i]['Despacho'] = '<span class="badge badge-success">Entregado</span>';
					}else{
						$arrData[$i]['Despacho'] = '<span class="badge badge-danger">No Entregado</span>';
					}		
					if($_SESSION['permisosMod']['r']){
						$btnOpciones .=' <a title="Generar PDF" href="'.base_url().'/Venta/generarFacturaPDF/'.$arrData[$i]['Ids'].'" target="_blanck" class="btn btn-primary btn-sm"> <i class="fa fa-file-pdf-o"></i> </a> ';
					}
					
					$arrData[$i]['options'] = '<div class="text-center">'.$btnOpciones.'</div>';
					
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function generarFacturaPDF($idpedido){
			if($_SESSION['permisosMod']['r']){
				if(is_numeric($idpedido)){
					$idpersona = "";
					$ped_fecha="";
					$ped_numero="";
					//if($_SESSION['permisosMod']['r'] and $_SESSION['userData']['idrol'] == RCLIENTES){
					//	$idpersona = $_SESSION['userData']['idpersona'];
					//}
					$model=new VentaModel;
					$data = $model->consultarFacturaPDF($idpedido,$idpersona,$ped_fecha,$ped_numero);
					putMessageLogFile($data);
					if(empty($data)){
						echo "Datos no encontrados";
					}else{
						$idpedido = $data['cabData']['com_id'];
						$ped_fecha = $data['cabData']['ped_fecha'];
					    $ped_numero= $data['cabData']['ped_numero'];					
						ob_end_clean();
						$html =getFile("Template/Modals/facturaPDF",$data);
						$html2pdf = new Html2Pdf('p','A4','es','true','UTF-8');
						$html2pdf->writeHTML($html);
						 $html2pdf->output('FE_'.$ped_numero.'_'.$ped_fecha.'.pdf');
					}
				}else{
					echo "Dato no válido";
				}
			}else{
				header('Location: '.base_url().'/login');
				die();
			}
		}


		public function Despacho(){
			//if(empty($_SESSION['permisosMod']['r'])){
				//header("Location:".base_url().'/dashboard');
			//}		
			dep("lleg");
			$data['page_tag'] = "Despachos";
			$data['page_title'] = "Despachos <small> ".TITULO_EMPRESA ."</small>";
			$data['page_name'] = "Despachos";
			$data['fileJS'] = "funcionesVenta.js";			
			$this->views->getView($this,"despacho",$data);
		}

		public function getVentasDespacho(){			
			if($_SESSION['permisosMod']['r']){
				$model=new VentaModel;
			    $arrData = $model->consultarDatosVentasDespacho();				
				for ($i=0; $i < count($arrData); $i++) {
					$btnOpciones="";				

					if($arrData[$i]['Estado'] == 1){
						$arrData[$i]['Estado'] = '<span class="badge badge-success">Pagada</span>';
					}else{
						$arrData[$i]['Estado'] = '<span class="badge badge-danger">Anulado</span>';
					}	
					if($arrData[$i]['Despacho'] == 1){
						$arrData[$i]['Despacho'] = '<span class="badge badge-success">Entregado</span>';
					}else{
						$arrData[$i]['Despacho'] = '<span class="badge badge-danger">No Entregado</span>';
					}	
					if($_SESSION['permisosMod']['r']){
							$btnOpciones .='<button class="btn btn-primary  btn-sm btnEdit" onClick="fntDespachar('.$arrData[$i]['Ids'].')" title="Despachar"><i class="fa fa-check"></i></button>';
					}		
					if($_SESSION['permisosMod']['r']){
						$btnOpciones .=' <a title="Generar PDF" href="'.base_url().'/Venta/generarFacturaPDF/'.$arrData[$i]['Ids'].'" target="_blanck" class="btn btn-primary btn-sm"> <i class="fa fa-file-pdf-o"></i> </a> ';
					}
					
					$arrData[$i]['options'] = '<div class="text-center">'.$btnOpciones.'</div>';
					
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function despacharDocumento(){
			if($_POST){
				if($_SESSION['permisosMod']['d']){
					$model=new VentaModel;
					$ids = $_POST['ids'];
					$request = $model->despacharDocumento($ids);
					if($request){
						$arrResponse = array('status' => true, 'msg' => 'Se ha Despachado el Documento');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al Despachado el Documento.');
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}


		

	

	}

 ?>