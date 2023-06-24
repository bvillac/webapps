<?php 
require_once("Libraries/Core/Conexion.php");
	class LineaModel extends Mysql{
		private $db_name;

		public function __construct(){
			parent::__construct();
			$this->db_name=$this->getDbNameMysql();
		}

		public function consultarDatos(){
			$sql = "SELECT a.lin_id Ids,a.lin_nombre Nombre, ";
			$sql .= " a.estado_logico Estado ";
			$sql .= "   FROM linea_item a  ";
			$sql .= "WHERE a.estado_logico!=0  ";
			$request = $this->select_all($sql);
			return $request;
		}

		
		public function consultarDatosId(int $Ids){
			$sql = "SELECT a.lin_id Ids,a.lin_nombre Nombre, ";
			$sql .= "  a.estado_logico Estado,date(a.fecha_creacion) FechaIng ";
			$sql .= "   FROM ". $this->db_name .".linea_item a  ";
			$sql .= "WHERE a.estado_logico!=0 AND a.lin_id={$Ids} ";
			$request = $this->select($sql);
			return $request;
		}

		public function insertData(int $Ids, string $lin_nombre, int $estado){
			$db_name=$this->getDbNameMysql();
			$return = "";
			$sql = "SELECT * FROM ". $db_name .".linea_item WHERE lin_nombre = '{$lin_nombre}'   ";
			$request = $this->select_all($sql);
			if(empty($request)){
				$con=$this->getConexion();
				$con->beginTransaction();
				try{
			      
	             	$arrData = array($lin_nombre, $estado);
					$request_insert =$this->insertarLinea($con,$db_name,$arrData);
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

		private function insertarLinea($con,$db_name,$arrData){

			$SqlQuery  = "INSERT INTO ". $db_name .".linea_item (lin_nombre, estado_logico) VALUES(?,?) ";
			$insert = $con->prepare($SqlQuery);
        	$resInsert = $insert->execute($arrData);
        	if($resInsert){
	        	$lastInsert = $con->lastInsertId();
	        }else{
	        	$lastInsert = 0;
	        }
	        return $lastInsert; 
		}	

	public function updateData(int $Ids, string $lin_nombre, int $estado){
		$sql = "UPDATE " . $this->db_name . ".linea_item 
						SET lin_nombre = ?, estado_logico = ?,fecha_modificacion = CURRENT_TIMESTAMP() WHERE lin_id={$Ids}  ";
		$arrData = array( $lin_nombre, $estado);
		$request = $this->update($sql, $arrData);
		return $request;
	}

		public function deleteRegistro(int $Ids){
			$sql = "UPDATE " . $this->db_name . ".linea_item SET estado_logico = ?,usuario_modificacion=1,fecha_modificacion = CURRENT_TIMESTAMP() WHERE lin_id = {$Ids} ";
			$arrData = array(0);
			$request = $this->update($sql, $arrData);
			return $request;
		}


	}
 ?>