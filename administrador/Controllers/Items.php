<?php 
require_once("Models/ItemsModel.php");
use Spipu\Html2Pdf\Html2Pdf;
require 'vendor/autoload.php';
	class Items extends Controllers{

		public function __construct(){
			parent::__construct();
			sessionStart();
			getPermisos();
		}

		public function Items(){
			if(empty($_SESSION['permisosMod']['r'])){
				header("Location:".base_url().'/dashboard');
			}
			$data['page_tag'] = "Productos(Items)";
			$data['page_title'] = "Productos(Items) <small> ".TITULO_EMPRESA ."</small>";
			$data['page_name'] = "Productos(Items)";
			$data['fileJS'] = "funcionesItems.js";
			$this->views->getView($this,"items",$data);
		}

		public function getItems(){			
			if($_SESSION['permisosMod']['r']){
				$model=new ItemsModel;
			    $arrData = $model->consultarDatos();				
				for ($i=0; $i < count($arrData); $i++) {
					$btnOpciones="";
					if($arrData[$i]['Estado'] == 1){
						$arrData[$i]['Estado'] = '<span class="badge badge-success">Activo</span>';
					}else{
						$arrData[$i]['Estado'] = '<span class="badge badge-danger">Inactivo</span>';
					}

					if($_SESSION['permisosMod']['r']){
						$btnOpciones .='<button class="btn btn-info btn-sm btnView" onClick="fntView(\''.$arrData[$i]['Codigo'].'\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
					}
					if($_SESSION['permisosMod']['u']){
						$btnOpciones .='<button class="btn btn-primary  btn-sm btnEdit" onClick="fntEdit(this,\''.$arrData[$i]['Codigo'].'\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
					}
					if($_SESSION['permisosMod']['d']){
						$btnOpciones .='<button class="btn btn-danger btn-sm btnDel" onClick="fntDelete(\''.$arrData[$i]['Codigo'].'\')" title="Eliminar Datos"><i class="fa fa-trash"></i></button>';
					}
					
					$arrData[$i]['options'] = '<div class="text-center">'.$btnOpciones.'</div>';
					
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function getLinea(){			
			$model=new ItemsModel;
			$htmlOptions = "";
			$arrData = $model->consultarLineas();
			if(count($arrData) > 0 ){
				//$htmlOptions = '<option value="0">SELECCIONAR</option>';
				for ($i=0; $i < count($arrData); $i++) { 
						$htmlOptions .= '<option value="'.$arrData[$i]['Ids'].'">'.$arrData[$i]['Nombre'].'</option>';
				}
			}
			echo $htmlOptions;
			//sleep(10);
			die();		
		}

		public function getTipo(){			
			$model=new ItemsModel;
			$htmlOptions = "";
			$arrData = $model->consultarTipos();
			if(count($arrData) > 0 ){
				//$htmlOptions = '<option value="0">SELECCIONAR</option>';
				for ($i=0; $i < count($arrData); $i++) { 
						$htmlOptions .= '<option value="'.$arrData[$i]['Ids'].'">'.$arrData[$i]['Nombre'].'</option>';
				}
			}
			echo $htmlOptions;
			//sleep(10);
			die();		
		}

		public function getMarca(){			
			$model=new ItemsModel;
			$htmlOptions = "";
			$arrData = $model->consultarMarcas();
			if(count($arrData) > 0 ){
				//$htmlOptions = '<option value="0">SELECCIONAR</option>';
				for ($i=0; $i < count($arrData); $i++) { 
						$htmlOptions .= '<option value="'.$arrData[$i]['Ids'].'">'.$arrData[$i]['Nombre'].'</option>';
				}
			}
			echo $htmlOptions;
			//sleep(10);
			die();		
		}

		public function getUnidadMedida(){			
			$model=new ItemsModel;
			$htmlOptions = "";
			$arrData = $model->consultarUnidadMedida();
			if(count($arrData) > 0 ){
				//$htmlOptions = '<option value="0">SELECCIONAR</option>';
				for ($i=0; $i < count($arrData); $i++) { 
						$htmlOptions .= '<option value="'.$arrData[$i]['Ids'].'">'.$arrData[$i]['Nombre'].'</option>';
				}
			}
			echo $htmlOptions;
			//sleep(10);
			die();		
		}

		public function getBodegas(){			
			$model=new ItemsModel;
			$htmlOptions = "";
			$arrData = $model->consultarBodegas();
			if(count($arrData) > 0 ){
				for ($i=0; $i < count($arrData); $i++) { 
						$htmlOptions .= '<option value="'.$arrData[$i]['Ids'].'">'.$arrData[$i]['Nombre'].'</option>';
				}
			}
			echo $htmlOptions;
			die();		
		}

		public function ingresarItem(){
			if($_POST){
				//dep($_POST);
				if(empty($_POST['txtNombre']) || empty($_POST['cmb_tipo']) || empty($_POST['cmb_linea']) || empty($_POST['cmb_marca']) 
							|| empty($_POST['cmb_estado']) ){
					$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
				}else{					
					$idProducto = strClean($_POST['idProducto']);//Recibe Datos si es una modificacion
					$Codigo = strClean($_POST['txtCodigo']);
					$Nombre = strClean($_POST['txtNombre']);
					$Descripcion = strClean($_POST['txtDescripcion']);					
					$linea = intval($_POST['cmb_linea']);
					$tipo = intval($_POST['cmb_tipo']);
					$marca = intval($_POST['cmb_marca']);
					$Medida = intval($_POST['cmb_medida']);
					$Percha = strClean($_POST['txtPercha']);
					$Ubicacion = strClean($_POST['txtUbicacion']);
					$Iva = intval($_POST['cmb_iva']);
					$Estado = intval($_POST['cmb_estado']);
					$Bodega = intval($_POST['cmb_bodega']);

					//Precios 
					$Plista = strClean($_POST['txtLista']);
					$Ppromedio = strClean($_POST['txtPromedio']);
					$Porcosto= strClean($_POST['txtPorCostos']);
					$Pcosto= strClean($_POST['txtCostos']);
					$Por1 = strClean($_POST['txtPor1']);
					$Precio1 = strClean($_POST['txtPrecio1']);
					$Por2 = strClean($_POST['txtPor2']);
					$Precio2 = strClean($_POST['txtPrecio2']);
					$Por3 = strClean($_POST['txtPor3']);
					$Precio3 = strClean($_POST['txtPrecio3']);
					$Por4 = strClean($_POST['txtPor4']);
					$Precio4 = strClean($_POST['txtPrecio4']);
					//Existencias
					$Max = strClean($_POST['txtMax']);
					$Min = strClean($_POST['txtMin']);				

					$request = "";
					$ruta = strtolower(clear_cadena($Nombre));
					$ruta = str_replace(" ","-",$ruta);
					$model=new ItemsModel;
					if($idProducto==""){
						$option = 1;
						if($_SESSION['permisosMod']['w']){
							$request = $model->insertData($Codigo,$Nombre,$Descripcion,$linea,$tipo,$marca,$Medida,$Percha,
															$Ubicacion,$Iva,$Plista,$Ppromedio,$Porcosto,$Pcosto,$Por1,$Precio1,
															$Por2,$Precio2,$Por3,$Precio3,$Por4,$Precio4,$Max,$Min,$Bodega,$Estado);
						}
					}else{
						$option = 2;
						if($_SESSION['permisosMod']['u']){
							$request = $model->updateData($idProducto,$Nombre,$Descripcion,$linea,$tipo,$marca,$Medida,$Percha,
															$Ubicacion,$Iva,$Plista,$Ppromedio,$Porcosto,$Pcosto,$Por1,$Precio1,
															$Por2,$Precio2,$Por3,$Precio3,$Por4,$Precio4,$Max,$Min,$Bodega,$Estado);
						}
					}
					if($request > 0 ){
						if($option == 1){
							$arrResponse = array('status' => true, 'txtCodigo' => $Codigo, 'msg' => 'Datos guardados correctamente.');
						}else{
							$arrResponse = array('status' => true, 'idProducto' => $idProducto, 'msg' => 'Datos Actualizados correctamente.');
						}
					}else if($request == 'exist'){
						$arrResponse = array('status' => false, 'msg' => '¡Atención! ya existe un Item con el Código Ingresado.');		
					}else{
						$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
					}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function getProducto($idproducto){
			if($_SESSION['permisosMod']['r']){
				$model=new ItemsModel;
				if($idproducto !=''){
					$arrData = $model->consultarDatosId($idproducto);
					if(empty($arrData)){
						$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
					}else{
						$arrImg = $model->selectImages($idproducto);
						if(count($arrImg) > 0){
							for ($i=0; $i < count($arrImg); $i++) { 
								$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img_nombre'];
							}
						}
						$arrData['images'] = $arrImg;
						$arrResponse = array('status' => true, 'data' => $arrData);
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}

		public function setImage(){
			if($_POST){
				if(empty($_POST['idproducto'])){
					$arrResponse = array('status' => false, 'msg' => 'Error de dato.');
				}else{
					$model=new ItemsModel;
					$idProducto = $_POST['idproducto'];
					$foto      = $_FILES['foto'];
					$imgNombre = 'pro_'.md5(date('d-m-Y H:i:s')).'.jpg';			
					$request = $model->insertaImagen($idProducto,$imgNombre);
					if($request){
						$uploadImage = uploadImage($foto,$imgNombre);
						$arrResponse = array('status' => true, 'imgname' => $imgNombre, 'msg' => 'Archivo cargado.');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error de carga.');
					}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function eliminarArchivo(){
			if($_POST){
				if(empty($_POST['idproducto']) || empty($_POST['file'])){
					$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
				}else{
					//Eliminar de la DB
					$idProducto = $_POST['idproducto'];
					$imgNombre  = strClean($_POST['file']);
					$model=new ItemsModel;
					$request_image = $model->eliminarImage($idProducto,$imgNombre);
					if($request_image){
						$deleteFile =  deleteFile($imgNombre);
						$arrResponse = array('status' => true, 'msg' => 'Archivo eliminado');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al eliminar');
					}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function delItem(){
			if($_POST){
				if($_SESSION['permisosMod']['d']){
					$model=new ItemsModel;
					$ids = $_POST['idProducto'];
					$requestDelete = $model->deleteItem($ids);
					if($requestDelete){
						$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Registro');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Registro.');
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}

		public function generarReporteItemPDF($idItem){
			if($_SESSION['permisosMod']['r']){
				if(is_string($idItem)){
					$idpersona = "";
					//if($_SESSION['permisosMod']['r'] and $_SESSION['userData']['idrol'] == RCLIENTES){
					//	$idpersona = $_SESSION['userData']['idpersona'];
					//}
					$model=new ItemsModel;
					$data = $model->consultarReporteItemPDF($idItem);
					//putMessageLogFile($data);
					if(empty($data)){
						echo "Datos no encontrados";
					}else{
						$idItem = $data['cabReporte']['item_id'];
						ob_end_clean();
						$html =getFile("Template/Modals/ReporteItemPDF",$data);
						$html2pdf = new Html2Pdf('p','A4','es','true','UTF-8');
						$html2pdf->writeHTML($html);
						$FechaActual= date('m-d-Y H:i:s a', time()); 
						$html2pdf->output('ReporteItems_'.$FechaActual.'.pdf');
					}
				}else{
					echo "Dato no válido";
				}
			}else{
				header('Location: '.base_url().'/login');
				die();
			}
		}
	
		public function getItemsReporte(){			
			if($_SESSION['permisosMod']['r']){
				$model=new ItemsModel;
				$arrData = $model->consultarDatosItems();				
				for ($i=0; $i < count($arrData); $i++) {
					$btnOpciones="";				
					if($_SESSION['permisosMod']['r']){
						$btnOpciones .=' <a title="Generar PDF" href="'.base_url().'/Items/generarReporteItemPDF/'.$arrData[$i]['Codigo'].'" target="_blanck" class="btn btn-primary btn-sm"> <i class="fa fa-file-pdf-o"></i> </a> ';
					}
					
					$arrData[$i]['options'] = '<div class="text-center">'.$btnOpciones.'</div>';
					
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
			die();
		}
		


		public function getItemsbuscar(){			
				$model=new ItemsModel;
			    $arrData = $model->consultarDatos();				
				for ($i=0; $i < count($arrData); $i++) {
					$btnOpciones="";			
					if($_SESSION['permisosMod']['r']){
						$btnOpciones .='<button class="btn btn-info btn-sm btnView" onClick="buscarItemProducto(\''.$arrData[$i]['Codigo'].'\')" title="Agregar"><i class="fa fa-plus"></i></button>';
					}					
					$arrData[$i]['options'] = '<div class="text-center">'.$btnOpciones.'</div>';
				}
				//dep($arrData);
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);

			die();
		}
	}

 ?>