<?php 
require_once("Libraries/Core/Conexion.php");
	class UnidadMedidaModel extends Mysql{
		private $db_name;

		public function __construct(){
			parent::__construct();
			$this->db_name=$this->getDbNameMysql();
		}

		public function consultarDatos(){
			$sql = "SELECT a.umed_id Ids, a.umed_nombre Nombre, a.umed_nomenclatura Nomenclatura, ";
			$sql .= " a.umed_factor_conversion FactorConversion, a.estado_logico Estado ";
			$sql .= "FROM unidad_medida a  ";
			$sql .= "WHERE a.estado_logico!=0  ";
			$request = $this->select_all($sql);
			return $request;
		}

		
		public function consultarDatosId(int $Ids){
			$sql = "SELECT a.umed_id Ids, a.umed_nombre Nombre, a.umed_nomenclatura Nomenclatura, ";
			$sql .= "   a.umed_factor_conversion FactorConversion, a.estado_logico Estado,date(a.fecha_creacion) FechaIng ";
			$sql .= "   FROM ". $this->db_name .".unidad_medida a  ";
			$sql .= "WHERE a.estado_logico!=0 AND a.umed_id={$Ids} ";
			$request = $this->select($sql);
			return $request;
		}

		public function insertData(int $Ids, string $umed_nombre, string $umed_nomenclatura, $umed_factor_conversion, int $estado){
		//			$query_insert  = "INSERT INTO " . $this->db_name . ".unidad_medida (umed_nombre, umed_nomenclatura, umed_factor_conversion, estado_logico) VALUES(?,?,?,?)";
		$db_name=$this->getDbNameMysql();
		$return = "";
		$sql = "SELECT * FROM ". $db_name .".unidad_medida WHERE umed_nombre = '{$umed_nombre}'   ";
		$request = $this->select_all($sql);
		if(empty($request)){
			$con=$this->getConexion();
			$con->beginTransaction();
			try{
			  
				 $arrData = array($umed_nombre, $umed_nomenclatura, $umed_factor_conversion, $estado);
				$request_insert =$this->insertarUnidadMedida($con,$db_name,$arrData);
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
		private function insertarUnidadMedida($con,$db_name,$arrData){

			$SqlQuery  = "INSERT INTO ". $db_name .".unidad_medida (umed_nombre, umed_nomenclatura, umed_factor_conversion, estado_logico) VALUES(?,?,?,?)";
			$insert = $con->prepare($SqlQuery);
        	$resInsert = $insert->execute($arrData);
        	if($resInsert){
	        	$lastInsert = $con->lastInsertId();
	        }else{
	        	$lastInsert = 0;
	        }
	        return $lastInsert; 
		}	

	public function updateData(int $Ids, string $umed_nombre, string $umed_nomenclatura, $umed_factor_conversion, int $estado){
		$sql = "UPDATE " . $this->db_name . ".unidad_medida 
						SET umed_nombre = ?, umed_nomenclatura = ?, umed_factor_conversion = ?, estado_logico = ?,fecha_modificacion = CURRENT_TIMESTAMP() WHERE umed_id = {$Ids} ";
		$arrData = array($umed_nombre, $umed_nomenclatura, $umed_factor_conversion, $estado);
		$request = $this->update($sql, $arrData);
		return $request;
	}

		public function deleteRegistro(int $Ids){
			$sql = "UPDATE " . $this->db_name . ".unidad_medida SET estado_logico = ?,usuario_modificacion=1, fecha_modificacion = CURRENT_TIMESTAMP() WHERE umed_id = {$Ids} ";
			$arrData = array(0);
			$request = $this->update($sql, $arrData);
			return $request;
		}


	}
 ?>