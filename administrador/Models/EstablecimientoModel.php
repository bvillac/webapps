<?php 
require_once("Libraries/Core/Conexion.php");
	class EstablecimientoModel extends Mysql{
		private $db_name;

		public function __construct(){
			parent::__construct();
			$this->db_name=$this->getDbNameMysql();
		}

		public function consultarDatos(){
			$sql = "SELECT a.est_id Ids,b.emp_nombre_comercial Empresa,a.est_numero Numero, ";
			$sql .= "   a.est_nombre Nombre,a.estado_logico Estado ";
			$sql .= "   FROM ". $this->db_name .".establecimiento a  ";
			$sql .= "      INNER JOIN ". $this->db_name .".empresa b  ";
			$sql .= "      ON a.emp_id=b.emp_id AND b.estado_logico!=0  ";
			$sql .= "WHERE a.estado_logico!=0  ";

			$request = $this->select_all($sql);
			return $request;
		}
		public function consultarEmpresa(){
			$sql = "SELECT emp_id Ids,emp_nombre_comercial Nombre ";
			$sql .= " FROM ". $this->db_name .".empresa WHERE estado_logico!=0";
			$request = $this->select_all($sql);
			return $request;
		}

		
		public function consultarDatosId(int $Ids){
			$sql = "SELECT a.est_id Ids,b.emp_id,b.emp_nombre_comercial Empresa,a.est_numero Numero, ";
			$sql .= "   a.est_nombre Nombre,a.estado_logico Estado,date(a.fecha_creacion) FechaIng";
			$sql .= "   FROM ". $this->db_name .".establecimiento a  ";
			$sql .= "      INNER JOIN ". $this->db_name .".empresa b  ";
			$sql .= "      ON a.emp_id=b.emp_id AND b.estado_logico!=0  ";
			$sql .= "WHERE a.estado_logico!=0 AND a.est_id={$Ids} ";
			$request = $this->select($sql);
			return $request;
		}


		public function insertData(int $Ids, int $empresa, string $numero, string $nombre, int $estado){
			//$query_insert  = "INSERT INTO ". $this->db_name .".establecimiento (emp_id,est_numero,est_nombre,estado_logico) VALUES(?,?,?,?)";
	        $db_name=$this->getDbNameMysql();
			$return = "";
			$sql = "SELECT * FROM ". $db_name .".establecimiento WHERE est_nombre = '{$nombre}'   ";
			$request = $this->select_all($sql);
			if(empty($request)){
				$con=$this->getConexion();
				$con->beginTransaction();
				try{
			      
	             	$arrData = array($empresa, $numero, $nombre,$estado);
					$request_insert =$this->insertarEstablecimiento($con,$db_name,$arrData);
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
		private function insertarEstablecimiento($con,$db_name,$arrData){

			$SqlQuery  = "INSERT INTO ". $db_name .".establecimiento (emp_id,est_numero,est_nombre,estado_logico) VALUES(?,?,?,?)";
			$insert = $con->prepare($SqlQuery);
        	$resInsert = $insert->execute($arrData);
        	if($resInsert){
	        	$lastInsert = $con->lastInsertId();
	        }else{
	        	$lastInsert = 0;
	        }
	        return $lastInsert; 
		}	


		public function updateData(int $Ids, int $empresa, string $numero, string $nombre, int $estado){
			$sql = "UPDATE " . $this->db_name . ".establecimiento 
							SET emp_id = ?,est_numero = ?,est_nombre = ?, estado_logico = ?,fecha_modificacion = CURRENT_TIMESTAMP() WHERE est_id = {$Ids} ";
			$arrData = array($empresa, $numero, $nombre,$estado);
			$request = $this->update($sql, $arrData);
			return $request;
		}


		public function deleteRegistro(int $Ids){
			$sql = "UPDATE " . $this->db_name . ".establecimiento SET estado_logico = ?,usuario_modificacion=1,fecha_modificacion = CURRENT_TIMESTAMP() WHERE est_id = {$Ids} ";
			$arrData = array(0);
			$request = $this->update($sql, $arrData);
			return $request;
		}

		public function consultarEstablecimiento(){
			$idsEmpresa=$_SESSION['idEmpresa'];
			$sql = "SELECT est_id Ids,CONCAT(est_numero,' ',est_nombre) Nombre ";
			$sql .= " FROM ". $this->db_name .".establecimiento WHERE estado_logico!=0 and emp_id='{$idsEmpresa}' ORDER BY est_nombre ASC";
			$request = $this->select_all($sql);
			return $request;
		}


	}
 ?>