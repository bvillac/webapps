<?php

class Mysql extends Conexion
{
	private $conexion;
	private $strquery;
	private $arrValues;
	private $db_name;
	private $db_nameAcad = DB_NAME_ACAD;
	

	function __construct()
	{
		$this->con = new Conexion();
		$this->db_name = $this->con->getDbName();
		$this->con = $this->con->conect();
	}

	public function getConexion()
	{
		return $this->con;
	}

	public function getDbNameMysql()
	{
		return $this->db_name;
	}

	//Insertar un registro
	public function insert(string $query, array $arrValues)
	{
		try {
			$this->strquery = $query;
			$this->arrVAlues = $arrValues;
			$insert = $this->con->prepare($this->strquery);
			$resInsert = $insert->execute($this->arrVAlues);
			if ($resInsert) {
				$lastInsert = $this->con->lastInsertId();
			} else {
				$lastInsert = 0;
			}
			return $lastInsert;
		} catch (\Throwable $e) {
			echo "Mensaje de Error: " . $e->getMessage();
			putMessageLogFile("ERROR: " . $e->getMessage() . $e);
		}
	}
	//Busca un registro
	public function select(string $query,array $params = [])
	{
		try {
			$this->strquery = $query;
			$result = $this->con->prepare($this->strquery);
			if (!empty($params)) {
				foreach ($params as $key => $value) {
					$result->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
				}
			}
			$result->execute();
			$data = $result->fetch(PDO::FETCH_ASSOC);
			return $data;
		} catch (\Throwable $e) {
			echo "Mensaje de Error: " . $e->getMessage();
			putMessageLogFile("ERROR: " . $e->getMessage() . $e);
		}
	}
	//Devuelve todos los registros
	public function select_all(string $query, array $params = [])
	{
		try {
			$this->strquery = $query;
			$result = $this->con->prepare($this->strquery);
			// Si hay parámetros, los enlazamos de forma segura
			if (!empty($params)) {
				foreach ($params as $key => $value) {
					$result->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
				}
			}
			$result->execute();
			return $result->fetchAll(PDO::FETCH_ASSOC);
		} catch (\Throwable $e) {
			putMessageLogFile("ERROR: " . $e->getMessage());
			return false; // Retornar false en caso de error
		}
	}



	//Actualiza registros
	public function update(string $query, array $arrValues)
	{
		try {
			$this->strquery = $query;
			$this->arrVAlues = $arrValues;
			$update = $this->con->prepare($this->strquery);
			$resExecute = $update->execute($this->arrVAlues);
			return $resExecute;
		} catch (\Throwable $e) {
			echo "Mensaje de Error: " . $e->getMessage();
			putMessageLogFile("ERROR: " . $e->getMessage() . $e);
		}
	}
	//Eliminar un registros
	public function delete(string $query)
	{
		try {
			$this->strquery = $query;
			$result = $this->con->prepare($this->strquery);
			$del = $result->execute();
			return $del;
		} catch (\Throwable $e) {
			echo "Mensaje de Error: " . $e->getMessage();
			putMessageLogFile("ERROR: " . $e->getMessage() . $e);
		}
	}

	//Insertar Datos con la Conexion datos
	public function insertConTrasn($con,string $query, array $arrValues){
		$this->strquery = $query;
		$this->arrValues = $arrValues;
		$insert = $con->prepare($this->strquery);
		$resInsert = $insert->execute($this->arrValues);
		if($resInsert){
			$lastInsert = $con->lastInsertId();
		}else{
			$lastInsert = 0;
		}
		return $lastInsert; 
	}

	//Actualiza registros Con Transaccion
	public function updateConTrasn($con, string $query, array $arrValues)
	{
		$this->strquery = $query;
		$this->arrValues = $arrValues;
		$update = $con->prepare($this->strquery);
		$resExecute = $update->execute($this->arrValues);
		return $resExecute;
	}
}
