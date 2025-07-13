<?php

class MysqlAcademico extends ConAcademico
{
	private $con;
	private $strQuery;
	private $arrValues;
	private $dbName;
	private $dbNameAdmin = DB_NAME;

	public function __construct()
	{
		$conAcademico = new ConAcademico();
		$this->dbName = $conAcademico->getDbName();
		$this->con = $conAcademico->conect();
	}

	public function getConexion()
	{
		return $this->con;
	}

	public function getDbNameMysql()
	{
		return $this->dbName;
	}

	public function getDbNameMysqlAdmin()
	{
		return $this->dbNameAdmin;
	}

	// Insertar un registro
	public function insert(string $query, array $arrValues)
	{
		$this->strQuery = $query;
		$this->arrValues = $arrValues;
		$insert = $this->con->prepare($this->strQuery);
		$resInsert = $insert->execute($this->arrValues);
		return $resInsert ? $this->con->lastInsertId() : 0;
	}

	// Buscar un registro
	public function select(string $query, array $arrValues = [])
	{
		$this->strQuery = $query;
		$stmt = $this->con->prepare($this->strQuery);
		$stmt->execute($arrValues);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	// Devolver todos los registros
	public function select_all(string $query, array $arrValues = [])
	{
		$this->strQuery = $query;
		$stmt = $this->con->prepare($this->strQuery);
		$stmt->execute($arrValues);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	// Actualizar registros
	public function update(string $query, array $arrValues)
	{
		$this->strQuery = $query;
		$this->arrValues = $arrValues;
		$update = $this->con->prepare($this->strQuery);
		return $update->execute($this->arrValues);
	}

	// Eliminar un registro
	public function delete(string $query, array $arrValues = [])
	{
		$this->strQuery = $query;
		$stmt = $this->con->prepare($this->strQuery);
		return $stmt->execute($arrValues);
	}

	// Insertar datos con una conexión externa (transacción)
	public function insertConTrans($con, string $query, array $arrValues)
	{
		$stmt = $con->prepare($query);
		$resInsert = $stmt->execute($arrValues);
		return $resInsert ? $con->lastInsertId() : 0;
	}

	// Actualizar registros con una conexión externa (transacción)
	public function updateConTrans($con, string $query, array $arrValues)
	{
		$stmt = $con->prepare($query);
		return $stmt->execute($arrValues);
	}
}
