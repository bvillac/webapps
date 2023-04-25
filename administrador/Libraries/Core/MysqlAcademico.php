<?php 
	
	class MysqlAcademico extends ConAcademico
	{
		private $conexion;
		private $strquery;
		private $arrValues;
		private $db_name;

		function __construct(){
			$this->con = new Conexion();
			$this->db_name = $this->con->getDbName();
			$this->con = $this->con->conect();			
		}

		public function getConexion(){
			return $this->con;
		}

		public function getDbNameMysql(){
			return $this->db_name;
		}

		//Insertar un registro
		public function insert(string $query, array $arrValues){
			$this->strquery = $query;
			$this->arrVAlues = $arrValues;
        	$insert = $this->con->prepare($this->strquery);
        	$resInsert = $insert->execute($this->arrVAlues);
        	if($resInsert){
	        	$lastInsert = $this->con->lastInsertId();
	        }else{
	        	$lastInsert = 0;
	        }
	        return $lastInsert; 
		}
		//Busca un registro
		public function select(string $query)
		{
			$this->strquery = $query;
        	$result = $this->con->prepare($this->strquery);
			$result->execute();
        	$data = $result->fetch(PDO::FETCH_ASSOC);
        	return $data;
		}
		//Devuelve todos los registros
		public function select_all(string $query){
			$this->strquery = $query;
        	$result = $this->con->prepare($this->strquery);
			$result->execute();
        	$data = $result->fetchall(PDO::FETCH_ASSOC);
        	return $data;
		}
		//Actualiza registros
		public function update(string $query, array $arrValues)
		{
			$this->strquery = $query;
			$this->arrVAlues = $arrValues;
			$update = $this->con->prepare($this->strquery);
			$resExecute = $update->execute($this->arrVAlues);
	        return $resExecute;
		}
		//Eliminar un registros
		public function delete(string $query)
		{
			$this->strquery = $query;
        	$result = $this->con->prepare($this->strquery);
			$del = $result->execute();
        	return $del;
		}

		
	}


 ?>

