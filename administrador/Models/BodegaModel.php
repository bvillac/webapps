<?php 
	require_once("Libraries/Core/Conexion.php");
	class BodegaModel extends Mysql{
		private $db_name;

		public function __construct(){
			parent::__construct();
			$this->db_name=$this->getDbNameMysql();
		}

		public function consultarDatos(){
			$sql = "SELECT a.bod_id Ids,a.bod_nombre Nombre, ";
			$sql .= "   a.bod_direccion Direccion,a.bod_telefono Telefono,a.estado_logico Estado ";
			$sql .= "   FROM bodega a  ";
			$sql .= "WHERE a.estado_logico!=0  ";
			$request = $this->select_all($sql);
			return $request;
		}

		
		public function consultarDatosId(int $Ids){
			$sql = "SELECT a.bod_id Ids,a.bod_nombre Nombre, ";
			$sql .= "   a.bod_direccion Direccion,a.bod_telefono Telefono,a.estado_logico Estado ";
			$sql .= "   FROM ". $this->db_name .".bodega a  ";
			$sql .= "WHERE a.estado_logico!=0 AND a.bod_id={$Ids} ";
			$request = $this->select($sql);
			return $request;
		}

		public function insertData(int $Ids, string $bod_nombre, string $bod_direccion, string $bod_telefono, int $estado){
			
		//	$query_insert  = "INSERT INTO " . $this->db_name . ".bodega (bod_nombre, bod_direccion, bod_telefono, estado_logico) VALUES(?,?,?,?) AND bod_id= {$Ids} AND bod_nombre= {$bod_nombre}";
        $db_name=$this->getDbNameMysql();
			$return = "";
			$sql = "SELECT * FROM ". $db_name .".bodega WHERE bod_nombre= '{$bod_nombre}' ";
			$request = $this->select_all($sql);
			if(empty($request)){
				$con=$this->getConexion();
				$con->beginTransaction();
				try{
			      
	             	$arrData = array($bod_nombre, $bod_direccion, $bod_telefono, $estado);
					$request_insert =$this->insertarBodega($con,$db_name,$arrData);
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
		private function insertarBodega($con,$db_name,$arrData){

			$SqlQuery  = "INSERT INTO ". $db_name .".bodega (bod_nombre, bod_direccion, bod_telefono, estado_logico) VALUES(?,?,?,?)";
			$insert = $con->prepare($SqlQuery);
        	$resInsert = $insert->execute($arrData);
        	if($resInsert){
	        	$lastInsert = $con->lastInsertId();
	        }else{
	        	$lastInsert = 0;
	        }
	        return $lastInsert; 
		}	

	public function updateData(int $Ids, string $bod_nombre, string $bod_direccion, string $bod_telefono, int $estado){
		$sql = "UPDATE " . $this->db_name . ".bodega 
						SET bod_nombre = ?, bod_direccion = ?, bod_telefono = ?, estado_logico = ?,fecha_modificacion = CURRENT_TIMESTAMP() WHERE bod_id = {$Ids} ";
		$arrData = array( $bod_nombre, $bod_direccion, $bod_telefono, $estado);
		$request = $this->update($sql, $arrData);
		return $request;
	}

		public function deleteRegistro(int $Ids){
			$sql = "UPDATE " . $this->db_name . ".bodega SET estado_logico = ?,usuario_modificacion=1,fecha_modificacion = CURRENT_TIMESTAMP() WHERE bod_id = {$Ids} ";
			$arrData = array(0);
			$request = $this->update($sql, $arrData);
			return $request;
		}


	}
 ?>