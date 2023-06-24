<?php 
	require_once("Models/SecuenciasModel.php");
	require_once("Models/EmpresaModel.php");
	class OrdenModel extends Mysql{
        
		public function __construct(){
			parent::__construct();
		}	


        public function consultarDatos(){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT a.com_id Ids,a.com_numero_orden Norden,a.com_numero Ncompra,a.com_fecha Fecha, ";
			$sql .= "    b.pro_nombre Nombre,a.com_valor_neto Vneto,a.com_estado_autoriza Autorizado,a.estado_logico Estado ";
    		$sql .= "    FROM ". $db_name .".compras a ";
      		$sql .= "      INNER JOIN ". $db_name .".proveedor b ON a.pro_codigo=b.pro_codigo ";
	  		$sql .= "  where a.estado_logico!=0  AND a.sec_tipo='OC'; ";
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
			$con = $this->getConexion();
			$con->beginTransaction();
			try {
				//Inserta Cabecera add_ceros();

				$objSecuencia=new SecuenciasModel;
				$numGenerado=$objSecuencia->newSecuence("OC",1,true);
				$SecId=$Cabecera['SecId'];
				$Tipo=$Cabecera['Tipo'];
				$Codproveedor=$Cabecera['Codproveedor'];
				$ValorBruto=$Cabecera['ValorBruto'];
				$ValorBase0=$Cabecera['ValorBase0'];
				$ValorBase12=$Cabecera['ValorBase12'];
				$ValorIva=$Cabecera['ValorIva'];
				$ValorNeto=$Cabecera['ValorNeto'];
				$arrData = array(
					$SecId, $Tipo, 1, NULL, $numGenerado,
					$Codproveedor, date("Y-m-d"),$ValorBruto, $ValorBase0,
					$ValorBase12, $ValorIva, $ValorNeto, 1, $idsUsuario
				);
				$Ids = $this->insertarCabecera($con, $db_name, $arrData);
				for ($i = 0; $i < sizeof($Detalle); $i++) {
					$arrDetalle = array(
						$Ids, $bodega, $Detalle[$i]['CodigoItem'], $Detalle[$i]['CantidadItem'],
						$Detalle[$i]['PrecioItem'], $Detalle[$i]['cargaIva'], 1, $idsUsuario
					);
					$this->insertarDetalle($con, $db_name, $arrDetalle);
				}
				$con->commit();
				$arroout["status"] = true;
				$arroout["numero"] = $numGenerado;
				return $arroout;
			} catch (Exception $e) {
				$con->rollBack();
				//echo "Fallo: " . $e->getMessage();
				//throw $e;
				$arroout["status"] = false;
				return $arroout;
			}
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

		private function insertarDetalle($con, $db_name, $arrData){
			$SqlQuery  = "INSERT INTO " . $db_name . ".compra_detalle ";
			$SqlQuery .= "(com_id,bod_id,item_id,com_cantidad,com_precio,com_graba_iva,estado_logico,usuario_creacion) ";
			$SqlQuery .= " VALUES (?,?,?,?,?,?,?,?) ";
			$insert = $con->prepare($SqlQuery);
			$insert->execute($arrData);
		}


		public function anularOrden(string $Ids){
			$db_name=$this->getDbNameMysql();
			$idsUsuario= $_SESSION['idsUsuario'];
			$sql = "UPDATE ". $db_name .".compras SET estado_logico = ?,usuario_modificacion=?,fecha_modificacion = CURRENT_TIMESTAMP() WHERE com_id={$Ids} ";
			$arrData = array(2,$idsUsuario);
			$request = $this->update($sql,$arrData);
			$sql = "UPDATE ". $db_name .".compra_detalle SET estado_logico = ?,usuario_modificacion=?,fecha_modificacion = CURRENT_TIMESTAMP() WHERE com_id={$Ids} ";
			$arrData = array(2,$idsUsuario);
			$request = $this->update($sql,$arrData);
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


		public function consultarOrdenPDF(int $idpedido, $idpersona = NULL){
			$busqueda = "";
			/*if($idpersona != NULL){
				$busqueda = " AND p.personaid =".$idpersona;
			}*/
			$empresa=1;
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