<?php 
require_once("Libraries/Core/Conexion.php");
	class MonedaModel extends Mysql{
		private $db_name;

		public function __construct(){
			parent::__construct();
			$this->db_name=$this->getDbNameMysql();
		}

		public function consultarDatos(){
			$sql = "SELECT a.mon_id Ids, a.mon_simbolo Simbolo, a.mon_nombre Nombre, ";
			$sql .= " a.mon_unidad_cambio UnidadCambio, a.estado_logico Estado ";
			$sql .= "FROM moneda a  ";
			$sql .= "WHERE a.estado_logico!=0  ";
			$request = $this->select_all($sql);
			return $request;
		}

		
		public function consultarDatosId(int $Ids){
			$sql = "SELECT a.mon_id Ids,a.mon_simbolo Simbolo, a.mon_nombre Nombre, ";
			$sql .= "   a.mon_unidad_cambio UnidadCambio, a.estado_logico Estado,date(a.fecha_creacion) FechaIng ";
			$sql .= "   FROM ". $this->db_name .".moneda a  ";
			$sql .= "WHERE a.estado_logico!=0 AND a.mon_id={$Ids} ";
			$request = $this->select($sql);
			return $request;
		}

		public function insertData(int $Ids, string $mon_simbolo, string $mon_nombre, $mon_unidad_cambio, int $estado){
			//$return = "";
			//$query_insert  = "INSERT INTO " . $this->db_name . ".moneda (mon_simbolo, mon_nombre, mon_unidad_cambio, estado_logico) VALUES(?,?,?,?) WHERE mon_id = {$Ids} AND mon_nombre = {$mon_nombre}";
			$db_name=$this->getDbNameMysql();
			$return = "";
			$sql = "SELECT * FROM ". $db_name .".moneda WHERE mon_nombre = '{$mon_nombre}'   ";
			$request = $this->select_all($sql);
			if(empty($request)){
				$con=$this->getConexion();
				$con->beginTransaction();
				try{
				  
					 $arrData = array( $mon_simbolo, $mon_nombre, $mon_unidad_cambio, $estado);
					$request_insert =$this->insertarMoneda($con,$db_name,$arrData);
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
		private function insertarMoneda($con,$db_name,$arrData){

			$SqlQuery  = "INSERT INTO ". $db_name .".moneda (mon_simbolo, mon_nombre, mon_unidad_cambio, estado_logico) VALUES(?,?,?,?) ";
			$insert = $con->prepare($SqlQuery);
        	$resInsert = $insert->execute($arrData);
        	if($resInsert){
	        	$lastInsert = $con->lastInsertId();
	        }else{
	        	$lastInsert = 0;
	        }
	        return $lastInsert; 
		}	

	public function updateData(int $Ids, string $mon_simbolo, string $mon_nombre, $mon_unidad_cambio, int $estado){
		$sql = "UPDATE " . $this->db_name . ".moneda 
						SET mon_simbolo = ?, mon_nombre = ?, mon_unidad_cambio = ?, estado_logico = ?,fecha_modificacion = CURRENT_TIMESTAMP() WHERE mon_id = {$Ids}  ";
		$arrData = array($mon_simbolo, $mon_nombre, $mon_unidad_cambio, $estado);
		$request = $this->update($sql, $arrData);
		return $request;
	}

		public function deleteRegistro(int $Ids){
			$sql = "UPDATE " . $this->db_name . ".moneda SET estado_logico = ?,usuario_modificacion=1, fecha_modificacion = CURRENT_TIMESTAMP() WHERE mon_id = {$Ids} ";
			$arrData = array(0);
			$request = $this->update($sql, $arrData);
			return $request;
		}


	}
 ?>