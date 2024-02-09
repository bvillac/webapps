<?php 
	require_once("Models/SecuenciasModel.php");
	require_once("Models/ItemsModel.php");
	require_once("Models/EmpresaModel.php");

	class CompraModel extends Mysql{
        
		public function __construct(){
			parent::__construct();
		}	


        public function consultarDatosCompras(){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT a.com_id Ids,a.com_numero_orden Norden,a.com_numero Ncompra,a.com_fecha Fecha, ";
			$sql .= "    b.pro_nombre Nombre,a.com_valor_neto Vneto,a.com_estado_autoriza Autorizado,a.estado_logico Estado ";
    		$sql .= "    FROM ". $db_name .".compras a ";
      		$sql .= "      INNER JOIN ". $db_name .".proveedor b ON a.pro_codigo=b.pro_codigo ";
	  		$sql .= "  where a.estado_logico!=0 AND a.sec_tipo='CO' ";//AND a.sec_tipo='CO';
			$request = $this->select_all($sql);
			return $request;
		}

		public function consultarDatosOrdenes(){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT a.com_id Ids,a.com_numero_orden Norden,a.com_numero Ncompra,a.com_fecha Fecha, ";
			$sql .= "    b.pro_nombre Nombre,a.com_valor_neto Vneto,a.com_estado_autoriza Autorizado,a.estado_logico Estado ";
    		$sql .= "    FROM ". $db_name .".compras a ";
      		$sql .= "      INNER JOIN ". $db_name .".proveedor b ON a.pro_codigo=b.pro_codigo ";
	  		$sql .= "  where a.estado_logico!=0 AND a.com_estado_autoriza=1  ";//AND a.sec_tipo='CO';
			$request = $this->select_all($sql);
			return $request;
		}

		public function consultarProveedor(string $Codigo){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT * ";
			$sql .= " FROM ". $db_name .".proveedor WHERE pro_codigo like '%{$Codigo}%' AND estado_logico=1 ";
			$request = $this->select_all($sql);
			return $request;
		}

		public function insertData($Cabecera, $Detalle){
			$db_name = $this->getDbNameMysql();
			$idsUsuario = $_SESSION['idsUsuario'];
			$bodega = 1;
			$objItem=new ItemsModel;
			$con = $this->getConexion();
			$con->beginTransaction();
			try {
				//Inserta Cabecera add_ceros();
				$objSecuencia=new SecuenciasModel;
				$numGenerado=$objSecuencia->newSecuence("CO",1,true);
				$SecId=3;//$Cabecera['SecId'];
				$Tipo="CO";
				$Codproveedor=$Cabecera['Codproveedor'];
				$Orden=$Cabecera['Orden'];
				$ValorBruto=$Cabecera['ValorBruto'];
				$ValorBase0=$Cabecera['ValorBase0'];
				$ValorBase12=$Cabecera['ValorBase12'];
				$ValorIva=$Cabecera['ValorIva'];
				$ValorNeto=$Cabecera['ValorNeto'];
				$arrData = array(
					$SecId, $Tipo, 1,$numGenerado,$Orden,
					$Codproveedor, date("Y-m-d"),$ValorBruto, $ValorBase0,
					$ValorBase12, $ValorIva, $ValorNeto, 1, $idsUsuario
				);
				$Ids = $this->insertarCabecera($con, $db_name, $arrData);
				/******************* */
				//Ingreso Bodega
				$arrDataIng = array(
					$bodega, $Tipo,$numGenerado,date("Y-m-d"),
					$Codproveedor,NULL,NULL,NULL,$ValorBruto,1, $idsUsuario
				);				
				$IdsIngreso = $this->insertarCabeceraIngreso($con, $db_name, $arrDataIng);
				/******************* */
				for ($i = 0; $i < sizeof($Detalle); $i++) {
					//Calculo de Precios	
					$codigoItem =	$Detalle[$i]['CodigoItem'];			
					$producto = $objItem->consultarDatosId($codigoItem);
					$exiAnt=$producto['Stock'];
					$proAnt=$producto['item_precio_promedio'];
					$cantCompra=$Detalle[$i]['CantidadItem'];
					$preCompra=$Detalle[$i]['PrecioItem'];
					$exiTotal=$exiAnt+$cantCompra;
					$Ppromedio=(($exiAnt*$proAnt)+($cantCompra*$preCompra))/$exiTotal;
					$pCosto= ($Ppromedio/((100-$producto['item_por_costo'])/100));
					$pPrecio1= ($pCosto/((100-$producto['item_por_precio1'])/100));
					$pPrecio2= ($pCosto/((100-$producto['item_por_precio2'])/100));
					$pPrecio3= ($pCosto/((100-$producto['item_por_precio3'])/100));
					$pPrecio4= ($pCosto/((100-$producto['item_por_venta'])/100));
					$arrItems = array(
						$Detalle[$i]['PrecioItem'], $Ppromedio, $pCosto, $pPrecio1,$pPrecio2,$pPrecio3,$pPrecio4,1, $idsUsuario
					);					
					$this->actualizarItems($con, $db_name, $arrItems,$codigoItem);//Actualiza Items
					$this->actualizarBodega($con, $db_name,$codigoItem,$bodega,$exiTotal);//Actualiza Bodegas
					$this->actualizarCabOrden($con, $db_name,$Orden,$numGenerado);//Actualiza Orden

					/*********************+ */
					$arrDetalle = array(
						$Ids, $bodega, $Detalle[$i]['CodigoItem'], $Detalle[$i]['CantidadItem'],
						$Detalle[$i]['PrecioItem'], $Detalle[$i]['cargaIva'], 1, $idsUsuario
					);
					$this->insertarDetalle($con, $db_name, $arrDetalle);
					//DETALLE INGRESO
					$totCosto=$Detalle[$i]['CantidadItem']*$Detalle[$i]['PrecioItem'];
					$arrDetalleIng = array(
						$IdsIngreso, $Detalle[$i]['CodigoItem'], $Detalle[$i]['CantidadItem'],
						$Detalle[$i]['PrecioItem'],$totCosto, 1, $idsUsuario
					);
					$this->insertarDetalleIngreso($con, $db_name, $arrDetalleIng);
				}
				$con->commit();
				$arroout["status"] = true;
				$arroout["numero"] = $numGenerado;
				return $arroout;
			} catch (Exception $e) {
				$con->rollBack();
				echo "Fallo: " . $e->getMessage();
				//throw $e;
				$arroout["status"] = false;
				return $arroout;
			}
		}

		public function actualizarCabecera($con, $db_name, $arrData){
			$db_name=$this->getDbNameMysql();
			$idsUsuario= $_SESSION['idsUsuario'];
			$sql = "UPDATE ". $db_name .".compras SET 
						sec_tipo= ?,com_numero= ?,pro_codigo= ?,com_fecha= ?,com_valor_bruto= ?,
						com_biva0= ?,com_biva12= ?,com_valor_iva= ?,com_valor_neto= ?,
						estado_logico = ?,usuario_modificacion=?,fecha_modificacion = CURRENT_TIMESTAMP() 
						WHERE item_id='{$arrData}' ";
			$arrData = array(0,$idsUsuario);
			$request = $this->update($sql,$arrData);
			return $request;
		}

		private function insertarCabecera($con, $db_name, $arrData){
			$SqlQuery  = "INSERT INTO " . $db_name . ".compras ";
			$SqlQuery .= "(sec_id,sec_tipo,pemi_id,com_numero,com_numero_orden,pro_codigo,com_fecha,com_valor_bruto,
								com_biva0,com_biva12,com_valor_iva,com_valor_neto,estado_logico,usuario_creacion) ";
			$SqlQuery .= " VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
			$insert = $con->prepare($SqlQuery);
			$resInsert = $insert->execute($arrData);
			if ($resInsert) {
				$lastInsert = $con->lastInsertId();
			} else {
				$lastInsert = 0;
			}
			return $lastInsert;
		}

		private function insertarCabeceraIngreso($con, $db_name, $arrData){
			$SqlQuery  = "INSERT INTO " . $db_name . ".ingresos ";
			$SqlQuery .= "(bod_id,ing_tipo,ing_numero,fecha_ingreso,pro_codigo,trans_bodega,
			               trans_tipo,trans_numero,ing_total,estado_logico,usuario_creacion) ";
			$SqlQuery .= " VALUES (?,?,?,?,?,?,?,?,?,?,?) ";
			$insert = $con->prepare($SqlQuery);
			$resInsert = $insert->execute($arrData);
			if ($resInsert) {
				$lastInsert = $con->lastInsertId();
			} else {
				$lastInsert = 0;
			}
			return $lastInsert;
		}

		private function insertarDetalle($con, $db_name, $arrData){
			$SqlQuery  = "INSERT INTO " . $db_name . ".compra_detalle ";
			$SqlQuery .= "(com_id,bod_id,item_id,com_cantidad,com_precio,com_graba_iva,estado_logico,usuario_creacion) ";
			$SqlQuery .= " VALUES (?,?,?,?,?,?,?,?) ";
			$insert = $con->prepare($SqlQuery);
			$insert->execute($arrData);
		}

		private function insertarDetalleIngreso($con, $db_name, $arrData){
			$SqlQuery  = "INSERT INTO " . $db_name . ".ingreso_detalle ";
			$SqlQuery .= "(ing_id,item_id,idet_cantidad,idet_precio_costos,idet_total_costo,estado_logico,usuario_creacion) ";
			$SqlQuery .= " VALUES (?,?,?,?,?,?,?) ";
			$insert = $con->prepare($SqlQuery);
			$insert->execute($arrData);
		}


		public function anularCompra(string $Ids){
			$db_name=$this->getDbNameMysql();
			$idsUsuario= $_SESSION['idsUsuario'];
			$sql = "UPDATE ". $db_name .".compras SET estado_logico = ?,usuario_modificacion=?,fecha_modificacion = CURRENT_TIMESTAMP() WHERE com_id={$Ids} ";
			$arrData = array(2,$idsUsuario);
			$request = $this->update($sql,$arrData);
			$sql = "UPDATE ". $db_name .".compra_detalle SET estado_logico = ?,usuario_modificacion=?,fecha_modificacion = CURRENT_TIMESTAMP() WHERE com_id={$Ids} ";
			$arrData = array(2,$idsUsuario);
			$request = $this->update($sql,$arrData);
			//Retroceder los Stock
			return $request;
		}

		public function autorizarOrden(string $Ids){
			$db_name=$this->getDbNameMysql();
			$idsUsuario= $_SESSION['idsUsuario'];
			$sql = "UPDATE ". $db_name .".compras SET com_estado_autoriza = ?,com_usuario_autoriza=?,com_fecha_autoriza = CURRENT_TIMESTAMP() WHERE com_id={$Ids} AND estado_logico=1 ";
			$arrData = array(1,$idsUsuario);
			$request = $this->update($sql,$arrData);
			return $request;
		}

		public function consultarCabecerDoc(int $codigo){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT a.*,b.pro_cedula_ruc Cedula,b.pro_nombre Nombre,b.pro_direccion Direccion ";
			$sql .= " FROM ". $db_name .".compras a ";
			$sql .= "INNER JOIN ". $db_name .".proveedor b ON a.pro_codigo=b.pro_codigo ";
			$sql .= "WHERE a.estado_logico=1 AND com_id = {$codigo}  ";
			$request = $this->select($sql);
			return $request;
		}

		public function consultarDetalleDoc(int $codigo){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT a.*,b.item_nombre Nombre ";
  			$sql .= "  FROM ". $db_name .".compra_detalle a ";
    		$sql .= "   INNER JOIN ". $db_name .".item b ON b.item_id=a.item_id ";
			$sql .= "  WHERE a.estado_logico=1 AND a.com_id={$codigo} ";
			$request = $this->select_all($sql);
			return $request;
		}

		public function actualizarItems($con, $db_name, $arrData,$codigo){
			$db_name=$this->getDbNameMysql();
			$sql = "UPDATE ". $db_name .".item SET 
						item_precio_lista=?,item_precio_promedio=?,
						item_precio_costo= ?,item_precio1= ?,item_precio2= ?,item_precio3= ?,item_precio_venta= ?,
						estado_logico = ?,usuario_modificacion=?,fecha_modificacion = CURRENT_TIMESTAMP() 
					WHERE item_id='{$codigo}' ";
			//$this->update($sql,$arrData);
			$result = $con->prepare($sql);
			$result->execute($arrData);
		}

		public function actualizarBodega($con, $db_name,$codigo,$bodega,$stock){
			$db_name=$this->getDbNameMysql();
			$idsUsuario = $_SESSION['idsUsuario'];
			$arrData = array(
				$stock,1, $idsUsuario
			);
			$sql = "UPDATE ". $db_name .".item_bodega SET 
						stock=?,estado_logico = ?,usuario_modificacion=?,fecha_modificacion = CURRENT_TIMESTAMP() 
						WHERE item_id='{$codigo}' AND bod_id={$bodega} ";
			//$this->update($sql,$arrData);
			$result = $con->prepare($sql);
			$result->execute($arrData);
		}

		public function actualizarCabOrden($con, $db_name,$NumOrden,$NumCompra){
			$db_name=$this->getDbNameMysql();
			$arrData = array($NumCompra);
			$sql = "UPDATE ". $db_name .".compras SET com_numero= ?
						   WHERE sec_tipo='OC' AND com_numero_orden='{$NumOrden}' ";
			//putMessageLogFile($sql);
			$result = $con->prepare($sql);
			$result->execute($arrData);
		}

		public function consultarCompraPDF(int $idpedido, $idpersona = NULL,$com_fecha){
			$busqueda = "";
			/*if($idpersona != NULL){
				$busqueda = " AND p.personaid =".$idpersona;
			}*/
			$empresa=1;
			$com_fecha="";
			$ObjEmp=new EmpresaModel;
			$request = array();
			$requestEmpresa=$ObjEmp->consultarEmpresaId($empresa);
			$requestCab=$this->consultarCabecerDoc($idpedido);			
			if(!empty($requestCab)){
				$requestDet=$this->consultarDetalleDoc($idpedido);			
				$request = array('cabData' => $requestCab,
								 'detData' => $requestDet,
								 'empData' => $requestEmpresa);
			}
			return $request;
		}



		






	}
 ?>