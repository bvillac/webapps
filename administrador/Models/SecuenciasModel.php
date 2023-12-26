<?php 
	class SecuenciasModel extends Mysql{
		private $db_name;

		public function __construct(){
			parent::__construct();
			$this->db_name=$this->getDbNameMysql();
		}

		public function consultarDatos(){
			$sql = "SELECT a.sec_id Ids,CONCAT(c.est_numero,'-',b.pemi_numero) Punto,a.sec_tipo Tipo,a.sec_numero Numero, ";
			$sql .= "  CONCAT(c.est_numero,'-',b.pemi_numero,'-',a.sec_numero) Secuencia,a.sec_nombre Documento,a.estado_logico Estado ";
			$sql .= "   FROM ". $this->db_name .".secuencias a  ";
			$sql .= "      INNER JOIN (". $this->db_name .".punto_emision b  ";
            $sql .= "         INNER JOIN ". $this->db_name .".establecimiento c  ";
			$sql .= "            ON b.est_id=c.est_id AND c.estado_logico!=0)  ";
			$sql .= "      ON a.pemi_id=b.pemi_id AND b.estado_logico!=0  ";
			$sql .= "WHERE a.estado_logico!=0  ";

			$request = $this->select_all($sql);
			return $request;
		}

		public function consultarEstablecimiento(){
			$sql = "SELECT est_id Ids, CONCAT(est_numero,'-',est_nombre) Nombre ";
			$sql .= " FROM ". $this->db_name .".establecimiento WHERE estado_logico!=0 ";
			$request = $this->select_all($sql);
			return $request;
		}

		public function consultarPunto(int $idsEst){
			$sql = "SELECT pemi_id Ids, CONCAT(pemi_numero,'-',pemi_nombre) Nombre ";
			$sql .= " FROM ". $this->db_name .".punto_emision WHERE estado_logico!=0 AND est_id={$idsEst} ";
			$request = $this->select_all($sql);
			return $request;
		}

		public function consultarDatosId(int $Ids){
			$sql = "SELECT a.sec_id Ids,c.est_id,c.est_numero Establecimiento,b.pemi_id,b.pemi_numero Punto,a.sec_tipo Tipo,a.sec_numero Numero, ";
			$sql .= "   CONCAT(c.est_numero,'-',b.pemi_numero,'-',a.sec_numero) Secuencia,a.sec_nombre Documento,a.estado_logico Estado,date(a.fecha_creacion) FechaIng";
			$sql .= "   FROM ". $this->db_name .".secuencias a  ";
			$sql .= "      INNER JOIN (". $this->db_name .".punto_emision b  ";
            $sql .= "         INNER JOIN ". $this->db_name .".establecimiento c  ";
			$sql .= "            ON b.est_id=c.est_id AND c.estado_logico!=0)  ";
			$sql .= "      ON a.pemi_id=b.pemi_id AND b.estado_logico!=0  ";
			$sql .= "WHERE a.estado_logico!=0 AND a.sec_id={$Ids} ";
			$request = $this->select($sql);
			return $request;
		}

		public function insertData(int $Ids, int $punto, string $tipo, string $numero, string $nombre, string $estado){
			$return = "";
			$sql = "SELECT * FROM ". $this->db_name .".secuencias WHERE sec_tipo = '{$tipo}' AND pemi_id= {$punto} ";
			$request = $this->select_all($sql);
			if(empty($request)){//Si el Request es vacio inserta los datos
				$query_insert  = "INSERT INTO ". $this->db_name .".secuencias (pemi_id,sec_tipo,sec_numero,sec_nombre,estado_logico) VALUES(?,?,?,?,?)";
	        	$arrData = array($punto,$tipo,$numero,$nombre,$estado);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;//Retorna el Ultimo IDS
			}else{
				$return = "exist";//Restonra Mensaje si ya Existe en la tabla
			}
			return $return;
		}



		public function updateData(int $Ids, int $punto, string $tipo, string $numero, string $nombre, string $estado){
			$sql = "UPDATE " . $this->db_name . ".secuencias 
							SET pemi_id = ?,sec_numero = ?,sec_nombre = ?, estado_logico = ?,fecha_modificacion = CURRENT_TIMESTAMP() WHERE sec_id = {$Ids} ";
			$arrData = array($punto, $numero, $nombre, $estado);
			$request = $this->update($sql, $arrData);
			return $request;
		}

		public function deleteRegistro(int $Ids){
			$sql = "UPDATE " . $this->db_name . ".secuencias SET estado_logico = ?,usuario_modificacion=1,fecha_modificacion = CURRENT_TIMESTAMP() WHERE sec_id = {$Ids} ";
			$arrData = array(0);
			$request = $this->update($sql, $arrData);
			return $request;
		}

		public function newSecuence(string $TipoSec, int $puntoEmision,$executeSecuence = false,$con = false) {
			$numero = 0;
			$strPad = 10;
			if(!$con){//Si la conexion no existe crea una nueva
				//putMessageLogFile("crea una conexion SecuenciasModel");
				$con=$this->getConexion();
			}
			
			//$con->beginTransaction();
			try{
				$sql = "
					SELECT 
						IFNULL(CAST(sec_numero AS UNSIGNED), 0) secuencia 
					FROM 
						" . $this->db_name . ".secuencias 
					WHERE 
						sec_tipo = '{$TipoSec}' AND
						estado_logico = 1 AND pemi_id = {$puntoEmision}";
				if($executeSecuence)   $sql .= " FOR UPDATE ";
				$result = $con->prepare($sql);
				$result->execute();
        		$rawData = $result->fetch(PDO::FETCH_ASSOC);//Devuelve solo 1 Registro
				if($rawData !== false){//Si la Consulta esta OK
					$numero = str_pad( intval($rawData['secuencia']) + 1, $strPad, "0", STR_PAD_LEFT);
					if(!$executeSecuence)   return $numero; //Si es True Devuelve solo el numero
					$sql = "
						UPDATE " . $this->db_name . ".secuencias 
							SET sec_numero = '{$numero}' 
						WHERE 
							sec_tipo = '{$TipoSec}' AND
							estado_logico = 1 AND pemi_id = {$puntoEmision} ";
					$result = $con->prepare($sql);
					$result->execute();
					return $numero;
				}
			}catch(\Exception $e){
				return 0;
			}


		}  




	}
 ?>