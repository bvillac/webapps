<?php 
	class EmpresaModel extends Mysql{
		private $db_name;

		public function __construct(){
			parent::__construct();
			$this->db_name=$this->getDbNameMysql();
		}

		public function consultarDatos(){
			$sql = "SELECT a.emp_id Ids,CONCAT(b.mon_simbolo,'-',b.mon_nombre) Moneda,a.emp_ruc Ruc, ";
			$sql .= "   a.emp_razon_social Razon,a.emp_nombre_comercial	Nombre,a.emp_direccion Direccion,a.emp_correo Correo,a.emo_ruta_logo Logo,a.estado_logico Estado ";
			$sql .= "   FROM ". $this->db_name .".empresa a  ";
			$sql .= "      INNER JOIN ". $this->db_name .".moneda b  ";
			$sql .= "      ON a.mon_id=b.mon_id AND b.estado_logico!=0  ";
			$sql .= "WHERE a.estado_logico!=0  ";	
			$request = $this->select_all($sql);

			return $request;
		}
		public function consultarMoneda(){
			$sql = "SELECT mon_id Ids, CONCAT(mon_simbolo,'-',mon_nombre) Nombre ";
			$sql .= " FROM ". $this->db_name .".moneda WHERE estado_logico!=0 ORDER BY mon_nombre ASC";
			$request = $this->select_all($sql);
			return $request;
		}

		
		public function consultarDatosId(int $Ids){
			$sql = "SELECT a.emp_id Ids,b.mon_id,b.mon_nombre Moneda,a.emp_ruc Ruc,a.mon_id IdMoneda, ";
			$sql .= "   a.emp_razon_social Razon,a.emp_nombre_comercial	Nombre,a.emp_direccion Direccion,a.emp_correo Correo,a.emo_ruta_logo Logo,a.estado_logico Estado,date(a.fecha_creacion) FechaIng";
			$sql .= "   FROM ". $this->db_name .".empresa a  ";
			$sql .= "      INNER JOIN ". $this->db_name .".moneda b  ";
			$sql .= "      ON a.mon_id=b.mon_id AND b.estado_logico!=0  ";
			$sql .= "WHERE a.estado_logico!=0 AND a.emp_id={$Ids} ";
			$request = $this->select($sql);
			return $request;
		}


		public function insertData(int $Ids, string $ruc, string $razon, string $nombre, string $direccion, string $correo, string $logo, int $moneda, int $estado){
			//$return = "";
			
				//$query_insert  = "INSERT INTO ". $this->db_name .".empresa (emp_ruc,emp_razon_social,emp_nombre_comercial,emp_direccion,emp_correo,emp_ruta_logo,mon_id,estado_logico) VALUES(?,?,?,?,?,?,?,?)  ";
				$db_name=$this->getDbNameMysql();
				$return = "";
				$sql = "SELECT * FROM ". $db_name .".empresa WHERE emp_razon_social = '{$razon}'   ";
				$request = $this->select_all($sql);
				if(empty($request)){
					$con=$this->getConexion();
					$con->beginTransaction();
					try{
					  
						 $arrData = array( $ruc, $razon, $nombre, $direccion, $correo, $logo, $moneda, $estado);
						$request_insert =$this->insertarEmpresa($con,$db_name,$arrData);
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

		private function insertarEmpresa($con,$db_name,$arrData){

			$SqlQuery  = "INSERT INTO ". $db_name .".empresa (emp_ruc,emp_razon_social,emp_nombre_comercial,emp_direccion,emp_correo,emp_ruta_logo,mon_id,estado_logico) VALUES(?,?,?,?,?,?,?,?)  ";
			$insert = $con->prepare($SqlQuery);
        	$resInsert = $insert->execute($arrData);
        	if($resInsert){
	        	$lastInsert = $con->lastInsertId();
	        }else{
	        	$lastInsert = 0;
	        }
	        return $lastInsert; 
		}	




		public function updateData(int $Ids, string $ruc, string $razon, string $nombre, string $direccion, string $correo, string $logo, int $moneda, int $estado){
			$sql = "UPDATE " . $this->db_name . ".empresa 
							SET emp_ruc = ?,emp_razon_social= ?,emp_nombre_comercial = ?,emp_direccion = ?,emp_correo = ?,emo_ruta_logo = ?,mon_id = ?, estado_logico = ?,fecha_modificacion = CURRENT_TIMESTAMP() WHERE emp_id = {$Ids} ";
			$arrData = array($ruc,$razon,$nombre,$direccion,$correo,$logo,$moneda,$estado);
			$request = $this->update($sql, $arrData);
			return $request;
		}


		public function deleteRegistro(int $Ids){
			$sql = "UPDATE " . $this->db_name . ".empresa SET estado_logico = ?,usuario_modificacion=1,fecha_modificacion = CURRENT_TIMESTAMP() WHERE emp_id = {$Ids} ";
			$arrData = array(0);
			$request = $this->update($sql, $arrData);
			return $request;
		}

		public function consultarEmpresaId(){
			$IdsEmp=$_SESSION['idEmpresa'];
			$sql = "SELECT a.emp_id Ids,a.emp_ruc Ruc,a.mon_id IdMoneda, ";
			$sql .= "   a.emp_razon_social Razon,a.emp_nombre_comercial	Nombre,a.emp_direccion Direccion, ";
			$sql .= "	a.emp_correo Correo,a.emp_ruta_logo Logo";
			$sql .= "   FROM ". $this->db_name .".empresa a  ";
			$sql .= "WHERE a.estado_logico=1 AND a.emp_id={$IdsEmp} ";
			$request = $this->select($sql);
			return $request;
		}


		//Nuevo 23-04-2023
		public function consultarEmpresaPermiso(int $Ids){
			$sql = "SELECT distinct(a.emp_id) Ids,b.emp_razon_social ";
			$sql = "	FROM ". $this->db_name .".permiso a ";
			$sql = "		INNER JOIN ". $this->db_name .".empresa b ";
			$sql = "			ON a.emp_id=b.emp_id ";
			$sql = "	WHERE a.estado_logico!=0 AND a.usu_id={$Ids} ";
			$request = $this->select($sql);
			return $request;
		}

		public function consultarEmpresaEstPunto(int $Ids){
			$sql = "SELECT a.emp_id EmpIds,a.emp_ruc Ruc,a.emp_nombre_comercial NombreComercial,b.est_id EstableId,
						c.pemi_id PuntoEmisId,a.emp_ruta_logo Logo,a.emp_correo Correo,a.emp_direccion Direccion 
						FROM ". $this->db_name .".empresa a 
							inner join (". $this->db_name .".establecimiento b 
									inner join ". $this->db_name .".punto_emision c 
										on b.est_id=c.est_id)
								on a.emp_id=b.emp_id
						where a.estado_logico!=0 and a.emp_id={$Ids} ";
			$request = $this->select($sql);
			return $request;
		}

	}
 ?>