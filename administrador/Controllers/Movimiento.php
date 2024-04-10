<?php 
	require_once("Models/ItemsModel.php");
	class Movimiento extends Controllers{

		public function __construct(){
			parent::__construct();
        	sessionStart();
        	getPermisos();
		}

		public function Movimiento(){
			if(empty($_SESSION['permisosMod']['r'])){
				header("Location:".base_url().'/dashboard');
			}
			$data['page_tag'] = "Movimiento";
			$data['page_title'] = "Movimiento <small> ".TITULO_EMPRESA ."</small>";
			$data['page_name'] = "Movimiento";
			$data['fileJS'] = "funcionesMovimiento.js";
			$this->views->getView($this,"movimiento",$data);
		}

		public function getMovimiento(){			
			if($_SESSION['permisosMod']['r']){
				$model=new ItemsModel;
				$codBodega = isset($_POST['bodega']) ? $_POST['bodega'] : 1;
				$codItem = isset($_POST['codigo']) ? $_POST['codigo'] : "";
				$fecDesde = isset($_POST['fecDes']) ? $_POST['fecDes'] : '2021-01-01';
				$fecHasta = isset($_POST['fecHas']) ? $_POST['fecHas'] : '2021-12-01';				
			    $arrData = $model->movimientoItemsGrid($codBodega,$codItem,$fecDesde,$fecHasta);
				$arrDataMov=$arrData['MOVIMIENTO'];			
				for ($i=0; $i < count($arrDataMov); $i++) {
					if($arrDataMov[$i]['ESTADO']!=0){
						if($arrDataMov[$i]['ESTADO'] == 1){
							$arrDataMov[$i]['ESTADO'] = '<span class="badge badge-success">LIQUIDADO</span>';
						}else{						
							$arrDataMov[$i]['ESTADO'] = '<span class="badge badge-danger">ANULADO</span>';
						}
					}
				}
				$arrData['MOVIMIENTO']=$arrDataMov;
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
			die();
		}



	}

 ?>