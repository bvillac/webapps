<?php 
//require_once("Libraries/Core/Conexion.php");
	class ModuloModel extends Mysql{
		private $db_name;

		public function __construct(){
			parent::__construct();
			$this->db_name=$this->getDbNameMysql();
		}

		public function consultarDatos(){
			//$sql = "SELECT @secuencia := @secuencia + 1 AS orden,a.mod_id Ids,a.mod_nombre Nombre, a.mod_url Url, ";
			$sql = "SELECT a.mod_id Ids,a.mod_nombre Nombre, a.mod_url Url, ";
			$sql .= " a.estado_logico Estado ";
			$sql .= "   FROM ". $this->db_name .".modulo a  ";
			//$sql .= ", (SELECT @secuencia := 0) AS temp ";
			$sql .= "WHERE a.estado_logico!=0  ";
			$sql .= "ORDER BY  a.mod_id ";
			$request = $this->select_all($sql);
			//putMessageLogFile($request);
			return $request;
		}

		
		public function consultarDatosId(int $Ids){
			$sql = "SELECT a.mod_id Ids,a.mod_nombre Nombre, a.mod_url Url, ";
			$sql .= "  a.estado_logico Estado,date(a.fecha_creacion) FechaIng ";
			$sql .= "   FROM ". $this->db_name .".modulo a  ";
			$sql .= "WHERE a.estado_logico!=0 AND a.mod_id={$Ids} ";
			$request = $this->select($sql);
			return $request;
		}

		public function insertData(int $Ids, string $mod_nombre, string $mod_url, int $estado){
			//$return = "";
			//$query_insert  = "INSERT INTO " . $this->db_name . ".modulo (mod_nombre, mod_url, estado_logico) VALUES(?,?,?) WHERE mod_id = {$Ids} AND mod_nombre = {$mod_nombre} ";
			$db_name=$this->getDbNameMysql();
			$return = "";
			$sql = "SELECT * FROM ". $db_name .".modulo WHERE mod_nombre = '{$mod_nombre}'   ";
			$request = $this->select_all($sql);
			if(empty($request)){
				$con=$this->getConexion();
				$con->beginTransaction();
				try{
				  
					 $arrData = array( $mod_nombre, $mod_url, $estado);
					$request_insert =$this->insertarModulo($con,$db_name,$arrData);
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
		private function insertarModulo($con,$db_name,$arrData){

			$SqlQuery  = "INSERT INTO ". $db_name .".modulo (mod_nombre, mod_url, estado_logico) VALUES(?,?,?) ";
			$insert = $con->prepare($SqlQuery);
        	$resInsert = $insert->execute($arrData);
        	if($resInsert){
	        	$lastInsert = $con->lastInsertId();
	        }else{
	        	$lastInsert = 0;
	        }
	        return $lastInsert; 
		}	

	public function updateData(int $Ids, string $mod_nombre, string $mod_url, int $estado){
		$sql = "UPDATE " . $this->db_name . ".modulo 
						SET mod_nombre = ?, mod_url = ?, estado_logico = ?, fecha_modificacion = CURRENT_TIMESTAMP() WHERE mod_id = {$Ids} ";
		$arrData = array( $mod_nombre,  $mod_url, $estado);
		$request = $this->update($sql, $arrData);
		return $request;
	}

		public function deleteRegistro(int $Ids){
			$usuario = retornaUser();
			$sql = "UPDATE " . $this->db_name . ".modulo SET estado_logico = ?,usuario_modificacion='{$usuario}',fecha_modificacion = CURRENT_TIMESTAMP() WHERE mod_id = {$Ids} ";
			$arrData = array(0);
			$request = $this->update($sql, $arrData);
			return $request;
		}


	}
 ?>