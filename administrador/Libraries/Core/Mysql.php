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
	public function select(string $query)
	{
		try {
			$this->strquery = $query;
			$result = $this->con->prepare($this->strquery);
			$result->execute();
			$data = $result->fetch(PDO::FETCH_ASSOC);
			return $data;
		} catch (\Throwable $e) {
			echo "Mensaje de Error: " . $e->getMessage();
			putMessageLogFile("ERROR: " . $e->getMessage() . $e);
		}
	}
	//Devuelve todos los registros
	public function select_all(string $query)
	{
		try {
			$this->strquery = $query;
			$result = $this->con->prepare($this->strquery);
			$result->execute();
			$data = $result->fetchall(PDO::FETCH_ASSOC);
			return $data;
		} catch (\Throwable $e) {
			echo "Mensaje de Error: " . $e->getMessage();
			putMessageLogFile("ERROR: " . $e->getMessage() . $e);
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
}
