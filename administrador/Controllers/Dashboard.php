<?php 

	class Dashboard extends Controllers{
		public function __construct(){
			sessionStart();//Para que se muestre el Dasboard
			parent::__construct();
			//session_start();
			//session_regenerate_id(true);
			if(empty($_SESSION['loginEstado'])){
				header('Location: '.base_url().'/login');
				die();
			}
			getPermisos();
			//getPermisos(1);//Control de Permisos Segun el Ids de la base de Datos
			//getGenerarMenu();
		}

		public function dashboard(){
			//control de Acceso por Roles
			
			$data['page_id'] = 2;
			$data['page_tag'] = "Dashboard";
			$data['page_title'] = "Dashboard - " .TITULO_EMPRESA;
			$data['page_name'] = "dashboard";
			//$data['fileJS'] = "funcionesAdmin.js";
			//$data['fileJS'] = "funciones_dashboard.js";

			
			$data['usuarios'] = $this->model->cantUsuarios();
			$data['clientes'] = $this->model->cantClientes();
			$data['beneficiario'] = $this->model->cantBeneficiarios();
			$data['proveedores'] = array();//$this->model->cantProveedores();
			$data['productos'] = array();//$this->model->cantProductos();
			$data['pedidos'] = array();//$this->model->cantPedidos();
			$data['lastOrders'] = array();//$this->model->lastOrders();
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