<?php 
	require_once("Models/EmpresaModel.php");
	require_once("Models/ItemsModel.php");
	require_once("Libraries/Core/Conexion.php");
	class ItemsModel extends Mysql{
        
		public function __construct(){
			parent::__construct();
		}	


        public function consultarDatos(){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT a.item_id Codigo,a.item_nombre Nombre,b.lin_nombre Linea, ";
			$sql .= "		IFNULL((SELECT SUM(c.stock) FROM ". $db_name .".item_bodega c WHERE c.estado_logico=1 AND a.item_id=c.item_id GROUP BY c.item_id),0) Stock, ";
			$sql .= "		a.item_precio_costo P_Costo,a.estado_logico Estado ";
			$sql .= "	FROM ". $db_name .".item a ";
			$sql .= "		INNER JOIN ". $db_name .".linea_item b ON a.lin_id=b.lin_id ";
			$sql .= "	WHERE a.estado_logico!=0 ";
			$request = $this->select_all($sql);
			putMessageLogFile($request);
			return $request;
		}

		public function consultarLineas(){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT lin_id Ids, lin_nombre Nombre ";
			$sql .= " FROM ". $db_name .".linea_item WHERE estado_logico=1 ORDER BY lin_nombre ASC";
			$request = $this->select_all($sql);
			return $request;
		}
		public function consultarTipos(){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT tip_id Ids, tip_nombre Nombre ";
			$sql .= " FROM ". $db_name .".tipo_item WHERE estado_logico=1 ORDER BY tip_nombre ASC ";
			$request = $this->select_all($sql);
			return $request;
		}

		public function consultarMarcas(){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT mar_id Ids, mar_nombre Nombre ";
			$sql .= " FROM ". $db_name .".marca_item WHERE estado_logico=1 ORDER BY mar_nombre ASC";
			$request = $this->select_all($sql);
			return $request;
		}

		public function consultarUnidadMedida(){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT umed_id Ids, umed_nombre Nombre ";
			$sql .= " FROM ". $db_name .".unidad_medida WHERE estado_logico=1 ORDER BY umed_nombre ASC";
			$request = $this->select_all($sql);
			return $request;
		}

		public function consultarBodegas(){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT bod_id Ids, bod_nombre Nombre ";
			$sql .= " FROM ". $db_name .".bodega WHERE estado_logico=1 ORDER BY bod_nombre ASC ";
			$request = $this->select_all($sql);
			return $request;
		}


		public function insertaImagen(string $idproducto, string $imagen){
			$db_name=$this->getDbNameMysql();
			$sql  = "INSERT INTO " . $db_name . ".item_imagen (item_id,img_nombre,estado_logico) VALUES(?,?,?)";
	        $arrData = array($idproducto,$imagen,1);
	        $request = $this->insert($sql,$arrData);
	        return $request;
		}

		public function eliminarImage(string $idproducto, string $imagen){
			$db_name=$this->getDbNameMysql();
			$sql  = "DELETE FROM " . $db_name . ".item_imagen
						WHERE item_id = '{$idproducto}' 
						AND img_nombre = '{$imagen}'";
			//dep($sql);
	        $request_delete = $this->delete($sql);
	        return $request_delete;
		}


		public function insertData(string $Codigo,string $Nombre,string $Descripcion,string $linea,string $tipo,string $marca,string $Medida,string $Percha,
								   string $Ubicacion,string $Iva,string $Plista,string $Ppromedio,string $Porcosto,string $Pcosto,string $Por1,string $Precio1,
								   string $Por2,string $Precio2,string $Por3,string $Precio3,string $Por4,string $Precio4,string $Max,string $Min,string $Bodega,string $Estado){
			
			$db_name=$this->getDbNameMysql();
			$return = "";
			$idsUsuario= $_SESSION['idsUsuario'];
			//Verifica que el correo no existe
			$sql = "SELECT * FROM ". $db_name .".item  WHERE item_nombre = '{$Nombre}'";
			$request = $this->select_all($sql);
			if(empty($request)){//Si no hay resultado Inserta los datos
				$con=$this->getConexion();
				$con->beginTransaction();
				try{	
					//Inserta Items				
					$arrData = array($Codigo,$Nombre,$Descripcion,$linea,$tipo,$marca,$Medida,$Percha,
									  $Ubicacion,$Iva,$Plista,$Ppromedio,$Porcosto,$Pcosto,$Por1,$Precio1,
					                  $Por2,$Precio2,$Por3,$Precio3,$Por4,$Precio4,$Max,$Min,$Estado,$idsUsuario);
					$Ids=$this->insertarItem($con,$db_name,$arrData);
					//Inserta Bodega por Defecto
					$Bodega=1;//Bodega por Defecto cada vez que se ingresa los datos
					$arrDataBodega = array($Bodega,$Codigo,0,$Estado,$idsUsuario);
					$Ids=$this->insertarBodegaItem($con,$db_name,$arrDataBodega);
					$return = $Ids;
					$con->commit();
					return true;
				}catch(Exception $e) {
					$con->rollBack();
					//echo "Fallo: " . $e->getMessage();
					//throw $e;
					return false;
				}   
			}else{
				return false;
				$return = "exist";
			}
	        return $return;
		}

		private function insertarBodegaItem($con,$db_name,$arrData){		
			$SqlQuery  = "INSERT INTO ". $db_name .".item_bodega ";
			$SqlQuery .= "(bod_id,item_id,stock,estado_logico,usuario_creacion) ";
			$SqlQuery .= " VALUES(?,?,?,?,?) ";			
			$insert = $con->prepare($SqlQuery);
        	$resInsert = $insert->execute($arrData);
        	if($resInsert){
	        	//$lastInsert = $con->lastInsertId();
				$lastInsert = 1;
	        }else{
	        	$lastInsert = 0;
	        }
	        return $lastInsert; 
		}

		private function insertarItem($con,$db_name,$arrData){		
			$SqlQuery  = "INSERT INTO ". $db_name .".item  ";
			$SqlQuery .= "(item_id,item_nombre,item_descripcion,lin_id,tip_id,mar_id,umed_id,item_nombre_percha,item_ubiacion_percha,item_graba_iva,
						   item_precio_lista,item_precio_promedio,item_por_costo,item_precio_costo,item_por_precio1,item_precio1,item_por_precio2,item_precio2,
						   item_por_precio3,item_precio3,item_por_venta,item_precio_venta,item_existencia_maxima,item_existencia_minima,estado_logico,usuario_creacion) ";
			$SqlQuery .= " VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";			
			$insert = $con->prepare($SqlQuery);
        	$resInsert = $insert->execute($arrData);
        	if($resInsert){
	        	$lastInsert = $con->lastInsertId();
				//$lastInsert = 1;
	        }else{
	        	$lastInsert = 0;
	        }
	        return $lastInsert; 
		}


		public function updateData(string $Codigo,string $Nombre,string $Descripcion,string $linea,string $tipo,string $marca,string $Medida,string $Percha,
									string $Ubicacion,string $Iva,string $Plista,string $Ppromedio,string $Porcosto,string $Pcosto,string $Por1,string $Precio1,
		                            string $Por2,string $Precio2,string $Por3,string $Precio3,string $Por4,string $Precio4,string $Max,string $Min,string $Bodega,string $Estado){

			$db_name=$this->getDbNameMysql();
			$idsUsuario= $_SESSION['idsUsuario'];
		
			//$con=$this->getConexion();
			//$con->beginTransaction();
			//try{		
				//Actualizar Items
				$SqlQuery  = "UPDATE " . $db_name . ".item  ";//item_precio_lista=?,item_precio_promedio=?,
				$SqlQuery .= "SET item_nombre=?,item_descripcion=?,lin_id=?,tip_id=?,mar_id=?,umed_id=?,item_nombre_percha=?,item_ubiacion_percha=?,item_graba_iva=?, ";
				$SqlQuery .= "item_por_costo=?,item_precio_costo=?,item_por_precio1=?,item_precio1=?,item_por_precio2=?,item_precio2 =?, ";
				$SqlQuery .= "item_por_precio3=?,item_precio3=?,item_por_venta=?,item_precio_venta=?,item_existencia_maxima=?,item_existencia_minima=?, ";
				$SqlQuery .= " estado_logico = ?,usuario_modificacion=?,fecha_modificacion = CURRENT_TIMESTAMP() ";
				$SqlQuery .= " WHERE item_id = '{$Codigo}' ";	
				//$update = $con->prepare($SqlQuery);
				$arrData = array($Nombre,$Descripcion,$linea,$tipo,$marca,$Medida,$Percha,
									  $Ubicacion,$Iva,$Porcosto,$Pcosto,$Por1,$Precio1,
					                  $Por2,$Precio2,$Por3,$Precio3,$Por4,$Precio4,$Max,$Min,$Estado,$idsUsuario);
				$request = $this->update($SqlQuery, $arrData);
                return $request;
				//$update->execute($arrData);
				//$con->commit();
				//return true;
							
			//}catch(Exception $e) {
			//	$con->rollBack();
				//echo "Fallo: " . $e->getMessage();
				//throw $e;
			//	return false;
			//}   
		}

		public function consultarReporteItemPDF(string $idItem){
			$busqueda = "";
			/*if($idpersona != NULL){
				$busqueda = " AND p.personaid =".$idpersona;
			}*/
			$empresa=1;
			$ObjEmp=new EmpresaModel;
			$request = array();
			$requestEmpresa=$ObjEmp->consultarEmpresaId($empresa);
			$requestCab=$this->consultarCabecerDoc($idItem);			
			if(!empty($requestCab)){
				$requestDet=$this->consultarDetalleDoc();			
				$request = array('cabReporte' => $requestCab,
								 'detReporte' => $requestDet,
								 'empData' => $requestEmpresa);
			}
			return $request;
		}
		public function consultarCabecerDoc(string $IdsEmpresa){
			$db_name=$this->getDbNameMysql();
			$IdsEmpresa="1";
			
			$sql = "SELECT a.usu_id Ids,b.per_id ,a.usu_correo Correo,a.usu_alias Alias,a.usu_clave Clave,b.per_cedula Cedula,b.per_nombre Nombre,b.per_apellido Apellido, ";
			$sql .= "	d.rol_nombre Rol,a.estado_logico Estado  ";
			$sql .= "	FROM ". $db_name .".usuario a ";
			$sql .= "		INNER JOIN ". $db_name .".persona b ON a.per_id=b.per_id AND b.estado_logico!=0  ";
			$sql .= "			INNER JOIN (". $db_name .".empresa_usuario c ";
			$sql .= "				INNER JOIN ". $db_name .".rol d ON c.rol_id=d.rol_id ) ";
			$sql .= "		ON a.usu_id=c.usu_id AND c.estado_logico!=0 ";
			$sql .= "	WHERE a.estado_logico!=0 AND c.emp_id='{$IdsEmpresa}' ";
			$request = $this->select($sql);
			return $request;
		}
		public function consultarDetalleDoc(){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT a.item_id Codigo,a.item_nombre Nombre,b.lin_nombre Linea, ";
			$sql .= "		IFNULL((SELECT SUM(c.stock) FROM ". $db_name .".item_bodega c WHERE c.estado_logico=1 AND a.item_id=c.item_id GROUP BY c.item_id),0) Stock, ";
			$sql .= "		a.item_precio_costo P_Costo,a.estado_logico Estado ";
			$sql .= "	FROM ". $db_name .".item a ";
			$sql .= "		INNER JOIN ". $db_name .".linea_item b ON a.lin_id=b.lin_id ";
			$sql .= "	WHERE a.estado_logico!=0 ";
			$request = $this->select_all($sql);
			return $request;
		}

		public function consultarDatosItems(){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT a.item_id Codigo,a.item_nombre Nombre,b.lin_nombre Linea, ";
			$sql .= "		IFNULL((SELECT SUM(c.stock) FROM ". $db_name .".item_bodega c WHERE c.estado_logico=1 AND a.item_id=c.item_id GROUP BY c.item_id),0) Stock, ";
			$sql .= "		a.item_precio_costo P_Costo,a.estado_logico Estado ";
			$sql .= "	FROM ". $db_name .".item a ";
			$sql .= "		INNER JOIN ". $db_name .".linea_item b ON a.lin_id=b.lin_id ";
			$sql .= "	WHERE a.estado_logico!=0 ";
			$request = $this->select_all($sql);
			return $request;
		}


		public function consultarDatosId(string $Ids){
			$db_name=$this->getDbNameMysql();
		

			$sql = "SELECT   a.*,
						IFNULL((SELECT SUM(c.stock) FROM ". $db_name .".item_bodega c WHERE c.estado_logico=1 AND c.item_id=a.item_id GROUP BY c.item_id),0) Stock
					FROM ". $db_name .".item a WHERE a.estado_logico=1 AND a.item_id='{$Ids}'  ";
			$request = $this->select($sql);
			return $request;
		}

		public function selectImages(string $Ids){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT item_id,img_nombre
						FROM ". $db_name .".item_imagen
					WHERE item_id='{$Ids}' ";
			$request = $this->select_all($sql);
			return $request;
		}

		public function deleteItem(string $Ids){
			$db_name=$this->getDbNameMysql();
			$idsUsuario= $_SESSION['idsUsuario'];
			$sql = "UPDATE ". $db_name .".item SET estado_logico = ?,usuario_modificacion=?,fecha_modificacion = CURRENT_TIMESTAMP() WHERE item_id='{$Ids}' ";
			$arrData = array(0,$idsUsuario);
			$request = $this->update($sql,$arrData);
			return $request;
		}

		public function consultarItemProducto(string $Codigo){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT  a.item_id Codigo,a.item_nombre Nombre,a.item_precio_lista Precio,a.item_graba_iva Iva,
			        IFNULL((SELECT SUM(c.stock) FROM db_utisum.item_bodega c WHERE c.estado_logico=1 AND c.item_id=a.item_id GROUP BY c.item_id),0) Stock
				FROM ". $db_name .".item a WHERE a.estado_logico=1 AND a.item_id LIKE '%{$Codigo}%'; ";
			$request = $this->select($sql);
			return $request;
		}


		public function movimientoItemsGrid($codBodega,$codItem,$fecDesde,$fecHasta){
			$db_name=$this->getDbNameMysql();	
			$sql = "SELECT b.bod_id BODEGA,b.fecha_ingreso FECHA,b.ing_tipo TIPO,b.ing_numero NUMERO,a.idet_cantidad PEDIDO,c.pro_nombre REF,1 AS ORD,a.estado_logico ESTADO ";
			$sql .= "		FROM db_utisum.ingreso_detalle a ";
			$sql .= "			INNER JOIN (". $db_name .".ingresos b ";
			$sql .= "				  INNER JOIN  ". $db_name .".proveedor c ON c.pro_codigo=b.pro_codigo) ";
			$sql .= "			ON a.ing_id=b.ing_id ";
			$sql .= "		WHERE a.estado_logico=1 AND b.bod_id={$codBodega} AND a.item_id='{$codItem}'  AND b.fecha_ingreso  BETWEEN '{$fecDesde}' AND '{$fecHasta}' ";
			$sql .= "	UNION ALL ";
			$sql .= "	SELECT b.bod_id ,b.fecha_egreso,b.egr_tipo,b.egr_numero,a.edet_cantidad,c.cli_nombre,2,a.estado_logico ";
			$sql .= "		FROM db_utisum.egreso_detalle a ";
			$sql .= "			INNER JOIN (". $db_name .".egresos b ";
			$sql .= "				  INNER JOIN  ". $db_name .".cliente c ON c.cli_codigo=b.cli_codigo) ";
			$sql .= "			ON a.egr_id=b.egr_id ";
			$sql .= "		WHERE a.estado_logico=1 AND b.bod_id={$codBodega} AND a.item_id='{$codItem}'  AND b.fecha_egreso  BETWEEN '{$fecDesde}' AND '{$fecHasta}' ";
			$sql .= "			ORDER BY FECHA ";
			//putMessageLogFile($sql);
			$result = $this->select_all($sql);

			$movimiento=[];
			$Saldo=0;
			$rowData[0]['FECHA']='2021-01-01';
			$rowData[0]['INGRESO']="INICIAL";
			$rowData[0]['EGRESO']="";
			$rowData[0]['SALDO']= $Saldo;
			$rowData[0]['CANTIDAD']="";
			$rowData[0]['ESTADO']="";
			$rowData[0]['REFERENCIA']="";
			$c=1;
			$totIng=$Saldo;
			$totEgr=0;

			for ($i = 0; $i < sizeof($result); $i++) {//Construir el Array
				$rowData[$c]['FECHA']=$result[$i]['FECHA'];
				$rowData[$c]['INGRESO']='';
				$rowData[$c]['EGRESO']='';
				if($result[$i]['ORD']==1){//'ingreso por compras y devoluciones en ventas
					$rowData[$c]['INGRESO']=$result[$i]['TIPO'].$result[$i]['NUMERO'];
					if($result[$i]['ESTADO']<>'A'){//No suma los ANULADOS
						$Saldo=$Saldo+$result[$i]['PEDIDO'];
						$totIng+=$result[$i]['PEDIDO'];
					}
					
				}elseif($result[$i]['ORD']==2){//egreso por ventas y devoluciones en compras
					$rowData[$c]['EGRESO']=$result[$i]['TIPO'].$result[$i]['NUMERO'];
					if($result[$i]['ESTADO']<>'A'){
						$Saldo=$Saldo-$result[$i]['PEDIDO'];
						$totEgr+=$result[$i]['PEDIDO'];
					}
				}
				$rowData[$c]['CANTIDAD']=$result[$i]['PEDIDO'];
				$rowData[$c]['SALDO']=$Saldo;
				$rowData[$c]['ESTADO']=$result[$i]['ESTADO'];
				$rowData[$c]['REFERENCIA']=$result[$i]['REF'];
				$movimiento=$rowData;
				$c++;
			}
			$retornar['TOT_ING']=$totIng;
			$retornar['TOT_EGR']=$totEgr;
			$retornar['MOVIMIENTO']=$movimiento;
			return 	$retornar;

		}






	}
 ?>