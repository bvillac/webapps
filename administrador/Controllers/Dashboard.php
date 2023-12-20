<?php 

	class Dashboard extends Controllers{
		public function __construct(){
			parent::__construct();
			sessionStart();//Para que se muestre el Dasboard
			getPermisos();
		}

		public function dashboard(){
			//control de Acceso por Roles
			
			$data['page_id'] = 2;
			$data['page_tag'] = "Dashboard";
			$data['page_title'] = "Dashboard - " .TITULO_EMPRESA;
			$data['page_name'] = "dashboard";
			

			
			$data['usuarios'] = $this->model->cantUsuarios();
			$data['clientes'] = $this->model->cantClientes();
			$data['beneficiario'] = $this->model->cantBeneficiarios();
			$data['proveedores'] = array();//$this->model->cantProveedores();
			$data['productos'] = array();//$this->model->cantProductos();
			$data['pedidos'] = array();//$this->model->cantPedidos();
			$data['lastContrato'] = $this->model->lastContrato();
			$data['lastCompras'] = array();//$this->model->lastCompras();
			$data['itemUtilidad'] = array();//$this->model->UtilidadItems();
			$data['itemMarca'] = array();//$this->model->UtilidadItemsMarca();
			$data['itemMinima'] = array();//$this->model->ExistenciaMinima();
			$data['productosTen'] = 0;//$this->model->productosTen();

			$this->views->getView($this,"dashboard",$data);
		}


		public function ventasMes(){
			if($_POST){
				$grafica = "ventasMes";
				$nFecha = str_replace(" ","",$_POST['fecha']);
				$arrFecha = explode('-',$nFecha);
				$mes = $arrFecha[0];
				$anio = $arrFecha[1];
				$pagos = $this->model->selectVentasMes($anio,$mes);
				$script = getFile("Template/Modals/graficas",$pagos);
				echo $script;
				die();
			}
		}

		public function compraMes(){
			if($_POST){
				$grafica = "ComprasMes";
				$nFecha = str_replace(" ","",$_POST['fecha']);
				$arrFecha = explode('-',$nFecha);
				$mes = $arrFecha[0];
				$anio = $arrFecha[1];
				$pagos = $this->model->selectComprasMes($anio,$mes);
				$script = getFile("Template/Modals/graficascompra",$pagos);
				echo $script;
				die();
			}
		}
			

	}
 ?>