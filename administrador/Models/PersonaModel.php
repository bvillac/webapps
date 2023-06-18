<?php
class PersonaModel extends Mysql
{
	private $db_name;

	public function __construct()
	{
		parent::__construct();
		$this->db_name = $this->getDbNameMysql();
	}

	public function consultarDatos()
	{
		$sql = "SELECT a.per_id Ids,a.per_cedula Cedula,a.per_nombre Nombre, ";
		$sql .= "   a.per_apellido Apellido,a.per_fecha_nacimiento FechaNacimiento,a.per_telefono Telefono,a.per_direccion Direccion,a.per_genero Genero,a.estado_logico Estado ";
		$sql .= "   FROM persona a  ";
		$sql .= "WHERE a.estado_logico!=0  ";
		$request = $this->select_all($sql);
		return $request;
	}


	public function consultarDatosId(int $Ids)
	{
		$sql = "SELECT a.per_id Ids,a.per_cedula Cedula,a.per_nombre Nombre, ";
		$sql .= "   a.per_apellido Apellido,a.per_fecha_nacimiento FechaNacimiento, a.per_telefono Telefono, a.per_direccion Direccion,  a.per_genero Genero, a.estado_logico Estado,date(a.fecha_creacion) FechaIng ";
		$sql .= "   FROM " . $this->db_name . ".persona a  ";
		$sql .= "WHERE a.estado_logico!=0 AND a.per_id={$Ids} ";
		$request = $this->select($sql);
		return $request;
	}

	public function consultarDatosIdCedula(string $parametro){
		$sql = "SELECT a.per_id Ids,a.per_cedula Cedula,a.per_nombre Nombre, ";
		$sql .= "   a.per_apellido Apellido,a.per_fecha_nacimiento FechaNacimiento, a.per_telefono Telefono, a.per_direccion Direccion,  a.per_genero Genero, a.estado_logico Estado,date(a.fecha_creacion) FechaIng ";
		$sql .= "   FROM " . $this->db_name . ".persona a  ";
		$sql .= " WHERE a.estado_logico!=0  ";
		if($parametro!=''){
			if (is_numeric($parametro)) {
				//$sql .= " AND (a.per_id LIKE '%{$parametro}%' OR a.per_cedula LIKE '%{$parametro}%'); ";
				$sql .= " AND a.per_id = {$parametro} ";
			}else{
				$sql .= " AND (a.per_nombre LIKE '%{$parametro}%' OR a.per_apellido LIKE '%{$parametro}%') ";
			}
		}
		
		//$sql .= " AND (a.per_cedula LIKE '%{$parametro}%' OR a.per_nombre LIKE '%{$parametro}%' OR a.per_apellido LIKE '%{$parametro}%'); ";
		$request = $this->select($sql);
		return $request;
	}

	public function insertData(int $Ids, string $per_cedula, string $per_nombre, string $per_apellido, $per_fecha_nacimiento, string $per_telefono, string $per_direccion, string $per_genero, int $estado)
	{
		$return = "";
		$query_insert  = "INSERT INTO " . $this->db_name . ".persona (per_cedula, per_nombre, per_apellido, per_fecha_nacimiento,per_telefono,per_direccion,per_genero, estado_logico) VALUES(?,?,?,?,?,?,?,?) WHERE per_id = {$Ids} AND per_nombre = {$per_nombre} AND per_apellido = {$per_apellido}";
		$arrData = array($per_cedula, $per_nombre, $per_apellido, $per_fecha_nacimiento, $per_telefono, $per_direccion, $per_genero, $estado);
		$request_insert = $this->insert($query_insert, $arrData);
		$return = $request_insert; //Retorna el Ultimo IDS(0) No inserta y si es >0 si inserto

		return $return;
	}

	public function updateData(int $Ids, string $per_cedula, string $per_nombre, string $per_apellido, $per_fecha_nacimiento, string $per_telefono, string $per_direccion, string $per_genero, int $estado)
	{
		$sql = "UPDATE " . $this->db_name . ".persona 
						SET per_cedula = ?, per_nombre = ?, per_apellido = ?, per_fecha_nacimiento = ?,  per_telefono = ?, per_direccion = ?, per_genero = ?, estado_logico = ?,fecha_modificacion = CURRENT_TIMESTAMP() WHERE per_id = {$Ids} ";
		$arrData = array($per_cedula, $per_nombre, $per_apellido, $per_fecha_nacimiento, $per_telefono, $per_direccion, $per_genero, $estado);
		$request = $this->update($sql, $arrData);
		return $request;
	}

	public function deleteRegistro(int $Ids)
	{
		$sql = "UPDATE " . $this->db_name . ".persona SET estado_logico = ?,usuario_modificacion=1,fecha_modificacion = CURRENT_TIMESTAMP() WHERE per_id = {$Ids} ";
		$arrData = array(0);
		$request = $this->update($sql, $arrData);
		return $request;
	}
}
