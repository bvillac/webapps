<?php 
	class DashboardModel extends MysqlAcademico
	{

		private $db_name;
		private $db_nameAdmin;

		public function __construct()
		{
			parent::__construct();
			$this->db_name = $this->getDbNameMysql();
			$this->db_nameAdmin = $this->getDbNameMysqlAdmin();
		}

		public function cantUsuarios(){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT COUNT(*) as total FROM ". $this->db_nameAdmin .".usuario WHERE estado_logico != 0";
			$request = $this->select($sql);
			$total = $request['total']; 
			return $total;
		}
		public function cantClientes(){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT COUNT(*) as total FROM ". $this->db_nameAdmin .".cliente WHERE estado_logico != 0 ";
			$request = $this->select($sql);
			$total = $request['total']; 
			return $total;
		}
		public function cantProveedores(){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT COUNT(*) as total FROM ". $this->db_nameAdmin .".proveedor WHERE estado_logico != 0 ";
			$request = $this->select($sql);
			$total = $request['total']; 
			return $total;
		}

		public function cantBeneficiarios(){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT COUNT(*) as total FROM ". $this->db_name .".beneficiario WHERE ben_estado_logico != 0 ";
			$request = $this->select($sql);
			$total = $request['total']; 
			return $total;
		}

		public function cantProductos(){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT COUNT(*) as total FROM ". $this->db_name .".item WHERE estado_logico != 0 ";
			$request = $this->select($sql);
			$total = $request['total']; 
			return $total;
		}
		public function cantPedidos(){
			//$rolid = $_SESSION['idsUsuario'];
			$idsUsuario= $_SESSION['idsUsuario'];
			$db_name=$this->getDbNameMysql();

			$sql = "SELECT COUNT(*) as total FROM ". $this->db_name .".pedidos WHERE estado_logico != 0 ";
			$request = $this->select($sql);
			$total = $request['total']; 
			return $total;
		}

		/* public function lastContrato(){	
			$sql = "SELECT a.ped_id Ids,a.ped_numero Numero,b.cli_nombre Nombre,a.ped_valor_neto Monto,if(a.estado_logico,'ACTIVO','ANULADO') Estado
						FROM ". $this->db_name .".pedidos a
							INNER JOIN ". $this->db_name .".cliente b ON a.cli_codigo=b.cli_codigo
						WHERE a.estado_logico!=0 ORDER BY a.ped_id DESC LIMIT 10;";
			$request = $this->select_all($sql);
			return $request;
		} */

		public function lastContrato()
		{
			$sql = "SELECT a.con_id Ids,a.con_numero Numero,date(a.con_fecha_inicio) FechaIni,b.cli_razon_social RazonSocial,a.con_valor Total,a.con_valor_cuota_inicial CuoInicial, ";
			$sql .= "(a.con_valor-a.con_valor_cuota_inicial) Saldo,a.con_numero_pagos Npagos,a.con_valor_cuota_mensual Vmensual,a.con_estado_logico Estado ";
			$sql .= "FROM " . $this->db_name . ".contrato a ";
			$sql .= "	INNER JOIN 	" . $this->db_nameAdmin . ".cliente b ";
			$sql .= "		ON a.cli_id=b.cli_id and b.estado_logico!=0 ";
			$sql .= "   WHERE a.con_estado_logico!=0 ORDER BY a.con_numero ASC LIMIT 10 ";
			$request = $this->select_all($sql);
			return $request;
		}

		public function selectVentasMes(int $anio, int $mes){
			$db_name=$this->getDbNameMysql();
			//$rolid = $_SESSION['userData']['idrol'];
			//$idUser = $_SESSION['userData']['idpersona'];
		

			$totalVentasMes = 0;
			$arrVentaDias = array();
			$dias = cal_days_in_month(CAL_GREGORIAN,$mes, $anio);
			$n_dia = 1;
			for ($i=0; $i < $dias ; $i++) { 
				$date = date_create($anio."-".$mes."-".$n_dia);
				$fechaVenta = date_format($date,"Y-m-d");
				//Extrae la Venta dia a dia
				$sql = "SELECT DAY(ped_fecha) AS dia, COUNT(ped_id) AS cantidad, SUM(ped_valor_neto) AS total
							FROM ". $db_name .".pedidos
			  			WHERE DATE(ped_fecha) = '{$fechaVenta}' AND estado_logico=1 GROUP BY DAY(ped_fecha);";

				$ventaDia = $this->select($sql);
				$ventaDia['dia'] = $n_dia;
				$ventaDia['total'] = $ventaDia['total'] == "" ? 0 : $ventaDia['total'];
				$totalVentasMes += $ventaDia['total'];
				array_push($arrVentaDias, $ventaDia);
				$n_dia++;
			}
			$meses = Meses();
			$arrData = array('anio' => $anio, 'mes' => $meses[intval($mes-1)], 'total' => $totalVentasMes,'ventas' => $arrVentaDias );
			return $arrData;
		}

		public function lastCompras(){	
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT a.com_id Ids,a.com_numero Numero,b.pro_nombre Nombre,a.com_valor_neto Monto,if(a.estado_logico,'ACTIVO','ANULADO') Estado
						FROM ". $db_name .".compras a
							INNER JOIN ". $db_name .".proveedor b ON a.pro_codigo=b.pro_codigo
						WHERE a.estado_logico=1 AND a.sec_tipo='CO' ORDER BY a.com_id DESC LIMIT 10;";
						//putMessageLogFile($sql);
			$request = $this->select_all($sql);
			return $request;
		}	

		public function selectComprasMes(int $anio, int $mes){
			$db_name=$this->getDbNameMysql();
			//$rolid = $_SESSION['userData']['idrol'];
			//$idUser = $_SESSION['userData']['idpersona'];
		

			$totalVentasMes = 0;
			$arrVentaDias = array();
			$dias = cal_days_in_month(CAL_GREGORIAN,$mes, $anio);
			$n_dia = 1;
			for ($i=0; $i < $dias ; $i++) { 
				$date = date_create($anio."-".$mes."-".$n_dia);
				$fechaVenta = date_format($date,"Y-m-d");
				//Extrae la Venta dia a dia
				$sql = "SELECT DAY(com_fecha) AS dia, COUNT(com_id) AS cantidad, SUM(com_valor_neto) AS total
							FROM ". $db_name .".compras
			  			WHERE DATE(com_fecha) = '{$fechaVenta}' AND sec_tipo='CO' AND estado_logico=1 GROUP BY DAY(com_fecha);";

				$ventaDia = $this->select($sql);
				$ventaDia['dia'] = $n_dia;
				$ventaDia['total'] = $ventaDia['total'] == "" ? 0 : $ventaDia['total'];
				$totalVentasMes += $ventaDia['total'];
				array_push($arrVentaDias, $ventaDia);
				$n_dia++;
			}
			$meses = Meses();
			$arrData = array('anio' => $anio, 'mes' => $meses[intval($mes-1)], 'total' => $totalVentasMes,'ventas' => $arrVentaDias );
			return $arrData;
		}


		public function UtilidadItems(){	
			$db_name=$this->getDbNameMysql();

			$sql = "SELECT a.pdet_item_id Codigo,b.item_nombre Nombre,SUM(a.pdet_cantidad*a.pdet_precio)-SUM(a.pdet_cantidad*b.item_precio_costo) Utilidad,
						(((SUM(a.pdet_cantidad*a.pdet_precio)-SUM(a.pdet_cantidad*b.item_precio_costo))/SUM(a.pdet_cantidad*a.pdet_precio))*100) POR
					FROM ". $db_name .".pedido_detalle a
						INNER JOIN ". $db_name .".item b  ON a.pdet_item_id =b.item_id
					WHERE a.estado_logico=1 GROUP BY a.pdet_item_id  ORDER BY POR DESC LIMIT 10 ; ";
					//WHERE a.estado_logico=1 GROUP BY a.pdet_item_id  ORDER BY a.pdet_item_id LIMIT 10 ; ";
			$request = $this->select_all($sql);

			return $request;
		}	

		
		public function UtilidadItemsMarca(){	
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT c.mar_nombre Marca,SUM(a.pdet_cantidad*a.pdet_precio)-SUM(a.pdet_cantidad*b.item_precio_costo) Utilidad,							
							(((SUM(a.pdet_cantidad*a.pdet_precio)-SUM(a.pdet_cantidad*b.item_precio_costo))/SUM(a.pdet_cantidad*a.pdet_precio))*100) POR
							FROM ". $db_name .".pedido_detalle a
								INNER JOIN (". $db_name .".item b
										INNER JOIN ". $db_name .".marca_item c  ON c.mar_id =b.mar_id)
									ON a.pdet_item_id =b.item_id
						WHERE a.estado_logico=1 GROUP BY b.mar_id  ORDER BY POR DESC LIMIT 10 ; ";
			$request = $this->select_all($sql);
			
			return $request;
		}	

		public function ExistenciaMinima(){	
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT  a.item_id Codigo,a.item_nombre Nombre,a.item_existencia_minima Minima,
								IFNULL((SELECT SUM(c.stock) FROM ". $db_name .".item_bodega c WHERE c.estado_logico=1 AND c.item_id=a.item_id GROUP BY c.item_id),0) Stock
							FROM ". $db_name .".item a WHERE a.estado_logico=1
					AND IFNULL((SELECT SUM(c.stock) FROM ". $db_name .".item_bodega c WHERE c.estado_logico=1 AND c.item_id=a.item_id GROUP BY c.item_id),0)<=10  LIMIT 10 ;";
			$request = $this->select_all($sql);
			
			return $request;
		}	



	}
 ?>