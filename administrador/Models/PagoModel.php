<?php 
require_once("Libraries/Core/Conexion.php");
	class PagoModel extends Mysql{
		private $db_name;

		public function __construct(){
			parent::__construct();
			$this->db_name=$this->getDbNameMysql();
		}

		public function consultarDatos(){
			$sql = "SELECT a.fpag_id Ids,a.fpag_nombre Nombre, ";
			$sql .= "   a.fpag_codigo Codigo,a.estado_logico Estado ";
			$sql .= "   FROM forma_pago a  ";
			$sql .= "WHERE a.estado_logico!=0  ";
			$request = $this->select_all($sql);
			return $request;
		}
		public function consultarPago(){
			$sql = "SELECT fpag_id Ids, fpag_nombre Nombre ";
			$sql .= " FROM ". $this->db_name .".forma_pago WHERE estado_logico!=0 ORDER BY fpag_nombre ASC ";
			$request = $this->select_all($sql);
			return $request;
		}

		
		public function consultarDatosId(int $Ids){
			$sql = "SELECT a.fpag_id Ids,a.fpag_nombre Nombre, ";
			$sql .= "   a.fpag_codigo Codigo,a.estado_logico Estado,date(a.fecha_creacion) FechaIng";
			$sql .= "   FROM ". $this->db_name .".forma_pago a  ";
			$sql .= "WHERE a.estado_logico!=0 AND a.fpag_id={$Ids} ";
			$request = $this->select($sql);
			return $request;
		}

		public function insertData(int $Ids, string $fpag_nombre, string $fpag_codigo, int $estado){
			//	$query_insert  = "INSERT INTO " . $this->db_name . ".forma_pago (fpag_nombre, fpag_codigo,estado_logico) VALUES(?,?,?) WHERE fpag_id = {$Ids} AND fpag_nombre = {$fpag_nombre}";
	        //$arrData = array($fpag_nombre,$fpag_codigo, $estado);
			$db_name=$this->getDbNameMysql();
			$return = "";
			$sql = "SELECT * FROM ". $db_name .".forma_pago WHERE fpag_nombre = '{$fpag_nombre}'   ";
			$request = $this->select_all($sql);
			if(empty($request)){
				$con=$this->getConexion();
				$con->beginTransaction();
				try{
			      
	             	$arrData = array( $fpag_nombre,$fpag_codigo, $estado);
					$request_insert =$this->insertarPago($con,$db_name,$arrData);
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

		
		private function insertarPago($con,$db_name,$arrData){

			$SqlQuery  = "INSERT INTO ". $db_name .".forma_pago (fpag_nombre, fpag_codigo,estado_logico) VALUES(?,?,?)";
			$insert = $con->prepare($SqlQuery);
        	$resInsert = $insert->execute($arrData);
        	if($resInsert){
	        	$lastInsert = $con->lastInsertId();
	        }else{
	        	$lastInsert = 0;
	        }
	        return $lastInsert; 
		}	

	public function updateData(int $Ids, string $fpag_nombre, string $fpag_codigo, int $estado){
		$sql = "UPDATE " . $this->db_name . ".forma_pago
						SET fpag_nombre = ?, fpag_codigo = ?, estado_logico = ?,fecha_modificacion = CURRENT_TIMESTAMP() WHERE fpag_id = '{$Ids}' ";
		$arrData = array( $fpag_nombre, $fpag_codigo, $estado);
		$request = $this->update($sql, $arrData);
		return $request;
	}

		public function deleteRegistro(int $Ids){
			$sql = "UPDATE " . $this->db_name . ".forma_pago SET estado_logico = ?,usuario_modificacion=1,fecha_modificacion = CURRENT_TIMESTAMP() WHERE fpag_id = {$Ids} ";
			$arrData = array(0);
			$request = $this->update($sql, $arrData);
			return $request;
		}


	}
 ?>