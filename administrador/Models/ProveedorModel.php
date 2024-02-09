<?php 
	require_once("Models/EmpresaModel.php");
	require_once("Models/ProveedorModel.php");
	require_once("Libraries/Core/Conexion.php");
	class ProveedorModel extends Mysql{


		public function __construct(){
			parent::__construct();
		}

		public function consultarDatos(){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT a.pro_codigo Ids,b.fpag_codigo Pago,a.pro_tipo_dni Tipo, ";
			$sql .= "   a.pro_cedula_ruc Cedula,a.pro_nombre Nombre,a.pro_direccion Direccion,a.pro_telefono Telefono,a.pro_correo Correo,a.estado_logico Estado ";
			$sql .= "   FROM ". $db_name .".proveedor a  ";
			$sql .= "      INNER JOIN ". $db_name .".forma_pago b  ";
			$sql .= "      ON a.fpag_id=b.fpag_id AND b.estado_logico!=0  ";
			$sql .= "WHERE a.estado_logico!=0  ";
			
			$request = $this->select_all($sql);
			return $request;
		}
		public function consultarPago(){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT fpag_id Ids, fpag_nombre Nombre ";
			$sql .= " FROM ". $db_name .".forma_pago WHERE estado_logico!=0 ORDER BY fpag_nombre ASC; ";
			$request = $this->select_all($sql);
			return $request;
		}

		
		public function consultarDatosId(string $Ids){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT a.pro_codigo Ids,b.fpag_codigo Pago,a.fpag_id,a.pro_tipo_dni Tipo, ";
			$sql .= "   a.pro_cedula_ruc Cedula,a.pro_nombre Nombre,a.pro_direccion Direccion,a.pro_telefono Telefono,a.pro_correo Correo,a.estado_logico Estado,date(a.fecha_creacion) FechaIng";
			$sql .= "   FROM ". $db_name .".proveedor a  ";
			$sql .= "      INNER JOIN ". $db_name .".forma_pago b  ";
			$sql .= "      ON a.fpag_id=b.fpag_id AND b.estado_logico!=0  ";
			$sql .= "WHERE a.estado_logico!=0 AND a.pro_codigo={$Ids} ";
			$request = $this->select($sql);
			return $request;
		}


		public function insertData(string $Ids, string $tipo, string $cedula, string $nombre, string $direccion, string $telefono, string $correo, int $pago, int $estado){
			$db_name=$this->getDbNameMysql();
			$idsUsuario= $_SESSION['idsUsuario'];		
			//$query_insert  = "INSERT INTO " . $db_name . " .proveedor (pro_codigo,pro_tipo_dni,pro_cedula_ruc,pro_nombre,pro_direccion,pro_telefono,pro_correo,fpag_id,estado_logico,usuario_creacion) VALUES(?,?,?,?,?,?,?,?,?,?) WHERE pro_codigo = '{$Ids}' AND pro_cedula_ruc= {$cedula} AND pro_nombre= {$nombre} ";
	        //$arrData = array($Ids,$tipo,$cedula,$nombre,$direccion,$telefono,$correo,$pago,$estado,$idsUsuario);
	        $return = "";
			$sql = "SELECT * FROM ". $db_name .".proveedor  WHERE pro_nombre = '{$nombre}'   ";
			$request = $this->select_all($sql);
			if(empty($request)){
				$con=$this->getConexion();
				$con->beginTransaction();
				try{
			      
	             	$arrData = array( $Ids,$tipo,$cedula,$nombre,$direccion,$telefono,$correo,$pago,$estado,$idsUsuario);
					$request_insert =$this->insertarProveedor($con,$db_name,$arrData);
	            	$return = $request_insert;//Retorna el Ultimo IDS(0) No inserta y si es >0 si inserto
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
		private function insertarProveedor($con,$db_name,$arrData){

			$SqlQuery  = "INSERT INTO ". $db_name ." .proveedor (pro_codigo,pro_tipo_dni,pro_cedula_ruc,pro_nombre,pro_direccion,pro_telefono,pro_correo,fpag_id,estado_logico,usuario_creacion) VALUES(?,?,?,?,?,?,?,?,?,?) ";
			$insert = $con->prepare($SqlQuery);
        	$resInsert = $insert->execute($arrData);
        	if($resInsert){
	        	$lastInsert = $con->lastInsertId();
	        }else{
	        	$lastInsert = 0;
	        }
	        return $lastInsert; 
		}	


		public function updateData(string $Ids, string $tipo, string $cedula, string $nombre, string $direccion, string $telefono, string $correo, int $pago, int $estado){
			$db_name=$this->getDbNameMysql();
			$idsUsuario= $_SESSION['idsUsuario'];
			$sql = "UPDATE " . $db_name . ".proveedor 
							SET pro_tipo_dni = ?,pro_cedula_ruc = ?,pro_nombre = ?,pro_direccion = ?,pro_telefono = ?,pro_correo = ?,
							fpag_id = ?, estado_logico = ?,usuario_modificacion=?,fecha_modificacion = CURRENT_TIMESTAMP() WHERE pro_codigo ='{$Ids}' ";
			$arrData = array($tipo, $cedula, $nombre, $direccion, $telefono, $correo, $pago, $estado,$idsUsuario);
			$request = $this->update($sql, $arrData);
			return $request;
		}
		public function consultarDatosProveedores(){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT a.pro_codigo Ids,b.fpag_codigo Pago,a.pro_tipo_dni Tipo, ";
			$sql .= "   a.pro_cedula_ruc Cedula,a.pro_nombre Nombre,a.pro_direccion Direccion,a.pro_telefono Telefono,a.pro_correo Correo,a.estado_logico Estado ";
			$sql .= "   FROM ". $db_name .".proveedor a  ";
			$sql .= "      INNER JOIN ". $db_name .".forma_pago b  ";
			$sql .= "      ON a.fpag_id=b.fpag_id AND b.estado_logico!=0  ";
			$sql .= "WHERE a.estado_logico!=0  ";
			
			$request = $this->select_all($sql);
			putMessageLogFile($request);
			return $request;
		}

		public function deleteRegistro(string $Ids){
			$db_name=$this->getDbNameMysql();
			$idsUsuario= $_SESSION['idsUsuario'];
			$sql = "UPDATE " . $db_name . ".proveedor SET estado_logico = ?,usuario_modificacion={$idsUsuario},fecha_modificacion = CURRENT_TIMESTAMP() WHERE pro_codigo = '{$Ids}' ";
			$arrData = array(0);
			$request = $this->update($sql, $arrData);
			return $request;
		}

		public function consultarReporteProveedorPDF(string $idProveedor){
			$busqueda = "";
			/*if($idpersona != NULL){
				$busqueda = " AND p.personaid =".$idpersona;
			}*/
			$empresa=1;
			$ObjEmp=new EmpresaModel;
			$request = array();
			$requestEmpresa=$ObjEmp->consultarEmpresaId($empresa);
			$requestCab=$this->consultarCabecerDoc($idProveedor);			
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
			$sql = "SELECT a.pro_codigo Ids,b.fpag_codigo Pago,a.pro_tipo_dni Tipo, ";
			$sql .= "   a.pro_cedula_ruc Cedula,a.pro_nombre Nombre,a.pro_direccion Direccion,a.pro_telefono Telefono,a.pro_correo Correo,a.estado_logico Estado ";
			$sql .= "   FROM ". $db_name .".proveedor a  ";
			$sql .= "      INNER JOIN ". $db_name .".forma_pago b  ";
			$sql .= "      ON a.fpag_id=b.fpag_id AND b.estado_logico!=0  ";
			$sql .= "WHERE a.estado_logico!=0  ";
			
			$request = $this->select_all($sql);
			return $request;
		}


	}
 ?>