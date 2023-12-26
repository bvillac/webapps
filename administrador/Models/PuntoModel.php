<?php 
require_once("Libraries/Core/Conexion.php");
	class PuntoModel extends Mysql{
		private $db_name;

		public function __construct(){
			parent::__construct();
			$this->db_name=$this->getDbNameMysql();
		}

		public function consultarDatos(){
			$sql = "SELECT a.pemi_id Ids,CONCAT(b.est_numero,'',b.est_nombre) Establecimiento,a.pemi_numero Numero, ";
			$sql .= "   a.pemi_nombre Nombre,a.estado_logico Estado ";
			$sql .= "   FROM ". $this->db_name .".punto_emision a  ";
			$sql .= "      INNER JOIN ". $this->db_name .".establecimiento b  ";
			$sql .= "      ON a.est_id=b.est_id AND b.estado_logico!=0  ";
			$sql .= "WHERE a.estado_logico!=0  ";

			$request = $this->select_all($sql);
			return $request;
		}

		public function consultarEstablecimiento(){
			$sql = "SELECT est_id Ids, CONCAT(est_numero,'',est_nombre) Nombre ";
			$sql .= " FROM ". $this->db_name .".establecimiento WHERE estado_logico!=0 ";
			$request = $this->select_all($sql);
			return $request;
		}

		
		public function consultarDatosId(int $Ids){
			$sql = "SELECT a.pemi_id Ids,b.est_id,b.est_nombre Establecimiento,a.pemi_numero Numero, ";
			$sql .= "   a.pemi_nombre Nombre,a.estado_logico Estado,date(a.fecha_creacion) FechaIng";
			$sql .= "   FROM ". $this->db_name .".punto_emision a  ";
			$sql .= "      INNER JOIN ". $this->db_name .".establecimiento b  ";
			$sql .= "      ON a.est_id=b.est_id AND b.estado_logico!=0  ";
			$sql .= "WHERE a.estado_logico!=0 AND a.pemi_id={$Ids} ";
			$request = $this->select($sql);
			return $request;
		}


		public function insertData(int $Ids, int $establecimiento, string $numero, string $nombre, int $estado){
				
			//	$query_insert  = "INSERT INTO ". $this->db_name .".punto_emision (est_id,pemi_numero,pemi_nombre,estado_logico) VALUES(?,?,?,?)  WHERE pemi_numero = '{$numero}' AND est_id= {$establecimiento} ";
			$db_name=$this->getDbNameMysql();
			$return = "";
			$sql = "SELECT * FROM ". $db_name .".punto_emision  WHERE pemi_nombre = '{$nombre}'   ";
			$request = $this->select_all($sql);
			if(empty($request)){
				$con=$this->getConexion();
				$con->beginTransaction();
				try{
			      
	             	$arrData = array( $establecimiento, $numero, $nombre,  $estado);
					$request_insert =$this->insertarPunto($con,$db_name,$arrData);
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


		private function insertarPunto($con,$db_name,$arrData){

			$SqlQuery  = "INSERT INTO ". $db_name .".punto_emision (est_id,pemi_numero,pemi_nombre,estado_logico) VALUES(?,?,?,?)  ";
			$insert = $con->prepare($SqlQuery);
        	$resInsert = $insert->execute($arrData);
        	if($resInsert){
	        	$lastInsert = $con->lastInsertId();
	        }else{
	        	$lastInsert = 0;
	        }
	        return $lastInsert; 
		}	

		public function updateData(int $Ids, int $establecimiento, string $numero, string $nombre, int $estado){
			$sql = "UPDATE " . $this->db_name . ".punto_emision 
							SET est_id = ?,pemi_numero = ?,pemi_nombre = ?, estado_logico = ?,fecha_modificacion = CURRENT_TIMESTAMP() WHERE pemi_id = {$Ids} ";
			$arrData = array($establecimiento, $numero, $nombre,$estado);
			$request = $this->update($sql, $arrData);
			return $request;
		}


		public function deleteRegistro(int $Ids){
			$sql = "UPDATE " . $this->db_name . ".punto_emision SET estado_logico = ?,usuario_modificacion=1,fecha_modificacion = CURRENT_TIMESTAMP() WHERE pemi_id = {$Ids} ";
			$arrData = array(0);
			$request = $this->update($sql, $arrData);
			return $request;
		}

		public function consultarPuntoEmision(int $Ids){
			$sql = "SELECT pemi_id Ids, CONCAT(pemi_numero,' ',pemi_nombre) Nombre ";
			$sql .= " FROM ". $this->db_name .".punto_emision WHERE estado_logico!=0 and pemi_id='{$Ids}' ORDER BY pemi_nombre ASC";
			$request = $this->select_all($sql);
			return $request;
		}


	}
 ?>