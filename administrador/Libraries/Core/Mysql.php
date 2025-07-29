<?php

class Mysql extends Conexion
{
	/** @var PDO */
	private $con;
	/** @var string */
	private $strquery;
	/** @var array */
	private $arrValues;
	/** @var string */
	private $db_name;
	/** @var string */
	private $db_nameAcad = DB_NAME_ACAD;

	public function __construct()
	{
		$conexion = new Conexion();
		$this->db_name = $conexion->getDbName();
		$this->con = $conexion->conect();
	}

	public function getConexion(): PDO
	{
		return $this->con;
	}

	public function getDbNameMysql(): string
	{
		return $this->db_name ?? '';
	}

	/**
	 * Insertar un registro
	 */
	public function insert(string $query, array $arrValues): int
	{
		try {
			$this->strquery = $query;
			$this->arrValues = $arrValues;
			$insert = $this->con->prepare($this->strquery);
			$resInsert = $insert->execute($this->arrValues);
			return $resInsert ? (int)$this->con->lastInsertId() : 0;
		} catch (\Throwable $e) {
			putMessageLogFile("ERROR: " . $e->getMessage());
			return 0;
		}
	}

	/**
	 * Busca un registro
	 */
	public function select(string $query, array $params = []): ?array
	{
		try {
			$this->strquery = $query;
			$result = $this->con->prepare($this->strquery);
			$this->bindParams($result, $params);
			$result->execute();
			$data = $result->fetch(PDO::FETCH_ASSOC);
			return $data !== false ? $data : null;
		} catch (\Throwable $e) {
			putMessageLogFile("ERROR: " . $e->getMessage());
			return null;
		}
	}

	/**
	 * Devuelve todos los registros
	 */
	//public function select_all(string $query, array $params = []): array|false
	public function select_all(string $query, array $params = [])
	{
		try {
			$this->strquery = $query;
			$result = $this->con->prepare($this->strquery);
			$this->bindParams($result, $params);
			$result->execute();
			return $result->fetchAll(PDO::FETCH_ASSOC);
		} catch (\Throwable $e) {
			putMessageLogFile("ERROR: " . $e->getMessage());
			return false;
		}
	}

	/**
	 * Actualiza registros
	 */
	public function update(string $query, array $arrValues): bool
	{
		try {
			$this->strquery = $query;
			$this->arrValues = $arrValues;
			$update = $this->con->prepare($this->strquery);
			return $update->execute($this->arrValues);
		} catch (\Throwable $e) {
			putMessageLogFile("ERROR: " . $e->getMessage());
			return false;
		}
	}

	/**
	 * Eliminar un registro
	 */
	public function delete(string $query, array $params = []): bool
	{
		try {
			$this->strquery = $query;
			$result = $this->con->prepare($this->strquery);
			$this->bindParams($result, $params);
			return $result->execute();
		} catch (\Throwable $e) {
			putMessageLogFile("ERROR: " . $e->getMessage());
			return false;
		}
	}

	/**
	 * Insertar datos con la conexión pasada por parámetro (transacción)
	 */
	public function insertConTrans($con, string $query, array $arrValues): int
	{
		try {
			$stmt = $con->prepare($query);
			$resInsert = $stmt->execute($arrValues);
			return $resInsert ? (int)$con->lastInsertId() : 0;
		} catch (\Throwable $e) {
			putMessageLogFile("ERROR: " . $e->getMessage());
			return 0;
		}
	}

	/**
	 * Actualiza registros con transacción
	 */
	public function updateConTrans($con, string $query, array $arrValues): bool
	{
		try {
			$stmt = $con->prepare($query);
			return $stmt->execute($arrValues);
		} catch (\Throwable $e) {
			putMessageLogFile("ERROR: " . $e->getMessage());
			return false;
		}
	}

	/**
	 * Enlaza parámetros a un statement PDO
	 */
	private function bindParams($stmt, array $params): void
	{
		foreach ($params as $key => $value) {
			$paramType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
			// Si el key es numérico, usar posición, si es string, usar nombre
			if (is_int($key)) {
				$stmt->bindValue($key + 1, $value, $paramType); // PDO es 1-based para posiciones
			} else {
				$stmt->bindValue($key, $value, $paramType);
			}
		}
	}
}
