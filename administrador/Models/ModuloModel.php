<?php
class ModuloModel extends Mysql
{
	private $db_name;

	public function __construct()
	{
		parent::__construct();
		$this->db_name = $this->getDbNameMysql();
	}

	public function consultarDatos()
	{
		//$sql = "SELECT @secuencia := @secuencia + 1 AS orden,a.mod_id Ids,a.mod_nombre Nombre, a.mod_url Url, ";
		$sql = "SELECT a.mod_id Ids,a.mod_nombre Nombre, a.mod_url Url, ";
		$sql .= " a.estado_logico Estado ";
		$sql .= "   FROM " . $this->db_name . ".modulo a  ";
		//$sql .= ", (SELECT @secuencia := 0) AS temp ";
		$sql .= "WHERE a.estado_logico!=0  ";
		$sql .= "ORDER BY  a.mod_id ";
		$request = $this->select_all($sql);
		return $request;
	}


	public function consultarDatosId(int $Ids)
	{
		$sql = "SELECT a.mod_id Ids,a.mod_nombre Nombre, a.mod_url Url, ";
		$sql .= "  a.estado_logico Estado,date(a.fecha_creacion) FechaIng ";
		$sql .= "   FROM " . $this->db_name . ".modulo a  ";
		$sql .= "WHERE a.estado_logico!=0 AND a.mod_id={$Ids} ";
		$request = $this->select($sql);
		return $request;
	}

	public function insertData(string $Ids, string $mod_nombre, string $mod_url, int $estado)
	{
		//$return = "";
		//$query_insert  = "INSERT INTO " . $this->db_name . ".modulo (mod_nombre, mod_url, estado_logico) VALUES(?,?,?) WHERE mod_id = {$Ids} AND mod_nombre = {$mod_nombre} ";
		$db_name = $this->getDbNameMysql();
		$return = "";
		//$sql = "SELECT * FROM ". $db_name .".modulo WHERE mod_nombre = '{$mod_nombre}'   ";
		$sql = "SELECT * FROM " . $db_name . ".modulo WHERE mod_id = '{$Ids}'   ";
		$request = $this->select_all($sql);
		if (empty($request)) {
			$con = $this->getConexion();
			$con->beginTransaction();
			try {
				$arrData = array($Ids, $mod_nombre, $mod_url, $estado);
				$request_insert = $this->insertarModulo($con, $db_name, $arrData);
				$return = $request_insert; //Retorna el Ultimo IDS(0) No inserta y si es >0 si inserto
				$con->commit();
				return true;
			} catch (Exception $e) {
				$con->rollBack();
				//echo "Fallo: " . $e->getMessage();
				//throw $e;
				return false;
			}
		} else {
			return false;
			$return = "exist";
		}
		return $return;
	}
	private function insertarModulo($con, $db_name, $arrData)
	{
		$usuario = retornaUser();
		$SqlQuery = "INSERT INTO " . $db_name . ".modulo (mod_id,mod_nombre, mod_url, estado_logico,usuario_creacion) VALUES(?,?,?,?,'{$usuario}') ";
		$insert = $con->prepare($SqlQuery);
		$resInsert = $insert->execute($arrData);
		if ($resInsert) {
			$lastInsert = $con->lastInsertId();
		} else {
			$lastInsert = 0;
		}
		return $lastInsert;
	}

	public function updateData(string $Ids, string $mod_nombre, string $mod_url, int $estado)
	{
		$usuario = retornaUser();
		$sql = "UPDATE " . $this->db_name . ".modulo 
						SET mod_nombre = ?, mod_url = ?, estado_logico = ?, usuario_modificacion='{$usuario}',fecha_modificacion = CURRENT_TIMESTAMP() WHERE mod_id = '{$Ids}' ";
		$arrData = array($mod_nombre, $mod_url, $estado);
		$request = $this->update($sql, $arrData);
		return $request;
	}

	public function deleteRegistro(int $Ids)
	{
		$usuario = retornaUser();
		$sql = "UPDATE " . $this->db_name . ".modulo SET estado_logico = ?,usuario_modificacion='{$usuario}',fecha_modificacion = CURRENT_TIMESTAMP() WHERE mod_id = '{$Ids}' ";
		$arrData = array(0);
		$request = $this->update($sql, $arrData);
		return $request;
	}

	public function getEmpresaModulo2(int $Emp_id)
	{

		$request = $this->select_all($sql);
		return $request;
	}

	public function getEmpresaModulo(int $Emp_id)
	{
		try {
			$sql = "SELECT a.emod_id Ids,CONCAT(RPAD(a.mod_id, 20-LENGTH(a.mod_id), ' '),b.mod_nombre) Nombre,a.mod_id ";
			$sql .= "	FROM {$this->db_name}.empresa_modulo a ";
			$sql .= "			INNER JOIN {$this->db_name}.modulo b ";
			$sql .= "		ON a.mod_id=b.mod_id ";
			$sql .= "	WHERE a.estado_logico!=0 AND a.emp_id= :emp_id order by Nombre asc";

			$arrParams = [":emp_id" => $Emp_id];
			$resultado = $this->select_all($sql, $arrParams);
			if ($resultado === false) {
				logFileSystem("Consulta fallida getEmpresaModulo", "WARNING");
				return []; // Retornar un array vacío en lugar de false para evitar errores en la vista
			}
			return $resultado;
		} catch (Exception $e) {
			logFileSystem("Error en getEmpresaModulo: " . $e->getMessage(), "ERROR");
			return []; // En caso de error, retornar un array vacío
		}
	}

	public function getModuloAll()
	{
		$sql = "SELECT a.mod_id Ids,CONCAT(RPAD(a.mod_id, 20-LENGTH(a.mod_id), ' '),a.mod_nombre) Nombre,a.mod_id,SUBSTRING(a.mod_id, 1, LENGTH(a.mod_id) - 2) idPadre ";
		$sql .= "      FROM " . $this->db_name . ".modulo a ";
		$sql .= "   WHERE a.estado_logico!=0 ";
		$request = $this->select_all($sql);
		return $request;
	}

	public function getModuloRolEmpresa(int $Emod_id, int $Erol_id)
	{
		$sql = "SELECT a.emod_id,CONCAT(RPAD(c.mod_id, 20-LENGTH(c.mod_id), ' '),c.mod_nombre) Nombre  ";
		$sql .= "	FROM (" . $this->db_name . ".permiso a ";
		$sql .= "		INNER JOIN (db_administrador.empresa_modulo b ";
		$sql .= "				INNER JOIN " . $this->db_name . ".modulo c  ";
		$sql .= "					ON  c.mod_id=b.mod_id)  ";
		$sql .= "			ON a.emod_id=b.emod_id) ";
		$sql .= "	WHERE a.estado_logico!=0 AND a.erol_id={$Erol_id} AND b.emod_id={$Emod_id} ";
		$request = $this->select_all($sql);
		return $request;
	}

	public function getEmpresaModuloRol(int $Erol_id)
	{
		try {
			$sql = "SELECT  a.emod_id as Ids,CONCAT(RPAD(b.mod_id, 20-LENGTH(b.mod_id), ' '),c.mod_nombre) Nombre
						FROM {$this->db_name}.permiso a
							inner join ({$this->db_name}.empresa_modulo b 
								inner join {$this->db_name}.modulo c on c.mod_id=b.mod_id)
							on b.emod_id=a.emod_id
						WHERE a.estado_logico!=0 and a.erol_id=:erol_id order by Nombre asc";
			$resultado = $this->select_all($sql, [":erol_id" => $Erol_id]);
			if ($resultado === false) {
				logFileSystem("Consulta fallida getEmpresaModuloRol", "WARNING");
				return []; // Retornar un array vacío en lugar de false para evitar errores en la vista
			}
			return $resultado;
		} catch (Exception $e) {
			logFileSystem("Error en getEmpresaModuloRol: " . $e->getMessage(), "ERROR");
			return []; // En caso de error, retornar un array vacío
		}
	}


}
