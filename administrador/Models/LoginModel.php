<?php

class LoginModel extends Mysql
{
	private $db_name;
	private $intIdUsuario;
	private $strUsuario;
	private $strPassword;
	private $strToken;

	public function __construct()
	{
		parent::__construct();
		$this->db_name = $this->getDbNameMysql();
	}

	public function loginData(string $usuario, string $clave): ?array
	{
		try {
			$sql = "SELECT usu_id, per_id, usu_alias, estado_logico AS Estado
				FROM {$this->db_name}.usuario
				WHERE usu_correo = :usu_correo AND usu_clave = :usu_clave AND estado_logico != 0";

			$resultado = $this->select($sql, [ ":usu_correo" => $usuario,":usu_clave" => $clave]);
			if ($resultado === false) {
				logFileSystem("Consulta fallida en loginData", "WARNING");
				return [];
			}
			return $resultado;
		} catch (Exception $e) {
			logFileSystem("Error en loginData: " . $e->getMessage(), "ERROR");
			return [];
		}
	}


	public function sessionLogin(int $IdsUser)
	{
		$sql = "SELECT a.usu_id UsuId,a.usu_correo,a.usu_alias Alias,b.per_cedula Dni,CONCAT(b.per_nombre,' ',b.per_apellido) Nombres,";
		$sql .= "	b.per_fecha_nacimiento FechaNac,b.per_nombre,b.per_apellido,b.per_genero Genero,a.estado_logico Estado,";
		$sql .= "	date(a.fecha_creacion) FechaIng,b.per_telefono Telefono,b.per_direccion Direccion";
		$sql .= "		FROM {$this->db_name}.usuario a";
		$sql .= "			INNER JOIN {$this->db_name}.persona b";
		$sql .= "				ON a.per_id=b.per_id";
		$sql .= "	WHERE a.estado_logico=1 AND a.usu_id = :usu_id";
		//$request = $this->select($sql);
		$request = $this->select($sql, [":usu_id" => $IdsUser]);
		return $request;
	}

	public function getUsuarioCorreo(string $Correo)
	{
		$db_name = $this->getDbNameMysql();
		$sql = "SELECT a.usu_id,a.per_id,a.usu_correo,a.usu_alias,b.per_cedula,b.per_nombre,b.per_apellido,b.per_fecha_nacimiento FechaNac,  ";
		$sql .= "    a.estado_logico Estado,a.fecha_creacion FechaIng,b.per_telefono Telefono,b.per_direccion Direccion  ";
		$sql .= "	FROM " . $db_name . ".usuario a  ";
		$sql .= "		INNER JOIN " . $db_name . ".persona b ON a.per_id=b.per_id AND b.estado_logico!=0  ";
		$sql .= "WHERE a.estado_logico=1 AND a.usu_correo='{$Correo}'  ";
		$request = $this->select($sql);
		return $request;
	}

	public function setTokenUsuario(int $idsUsuario, string $token)
	{
		$db_name = $this->getDbNameMysql();
		$sql = "UPDATE " . $db_name . ".usuario SET usu_token = ? WHERE usu_id = {$idsUsuario} ";
		$arrData = array($token);
		$request = $this->update($sql, $arrData);
		return $request;
	}

	public function getUsuario(string $Correo, string $token)
	{
		$db_name = $this->getDbNameMysql();
		$sql = "SELECT usu_id UsuIds ";
		$sql .= "  FROM " . $db_name . ".usuario ";
		$sql .= " Where usu_correo='{$Correo}' AND usu_token='{$token}' AND estado_logico=1 ";
		$request = $this->select($sql);
		return $request;
	}

	public function insertPassword(int $idPersona, string $password)
	{
		$this->intIdUsuario = $idPersona;
		$this->strPassword = $password;
		$sql = "UPDATE persona SET password = ?, token = ? WHERE idpersona = $this->intIdUsuario ";
		$arrData = array($this->strPassword, "");
		$request = $this->update($sql, $arrData);
		return $request;
	}

	//Nueva Funciones 2023-04
	public function consultarUsuarioEmpresaRol(int $Eurol_id)
	{
		$sql = "SELECT a.eurol_id,a.erol_id,c.rol_id,c.rol_nombre ";
		$sql .= "	FROM {$this->db_name}.empresa_usuario_rol a ";
		$sql .= "		INNER JOIN ({$this->db_name}.empresa_rol b ";
		$sql .= "				INNER JOIN {$this->db_name}.rol c ";
		$sql .= "					ON c.rol_id=b.rol_id) ";
		$sql .= "			ON a.erol_id=b.erol_id ";
		$sql .= "	WHERE a.estado_logico!=0 AND a.eurol_id= :eurol_id ";
		$request = $this->select_all($sql, [":eurol_id" => $Eurol_id]);
		return $request;
	}

	
	public function permisosModulo(int $Eusu_id, int $Erol_id)
	{
		// Consulta SQL optimizada con parámetros preparados
		$sql = "SELECT 
                a.perm_id, a.emod_id, c.mod_id, 
                SUBSTRING(c.mod_id, 1, LENGTH(c.mod_id) - 2) AS idPadre,  
                c.mod_nombre, c.mod_url, c.mod_icono, 
                a.r, a.w, a.u, a.d   
            FROM ({$this->db_name}.permiso a 
				 INNER JOIN ({$this->db_name}.empresa_modulo b 
					INNER JOIN {$this->db_name}.modulo c ON b.mod_id = c.mod_id )
                 ON b.emod_id=a.emod_id)
            WHERE a.estado_logico != 0  AND a.erol_id = :Erol_id ORDER BY  c.mod_id";
		// Ejecutar la consulta con parámetros preparados
		$request = $this->select_all($sql, [":Erol_id" => $Erol_id]);

		// Construir el menú en base a los resultados
		return $this->construirMenu($request, "");
	}

	/**
	 * Construye el menú de forma recursiva
	 *
	 * @param array $datosMenu Datos obtenidos de la base de datos
	 * @param string $id_padre ID del padre actual (para recursividad)
	 * @return array Menú estructurado con submenús anidados
	 */
	private function construirMenu(array $datosMenu, string $id_padre): array
	{
		return array_values(array_map(function ($item) use ($datosMenu) {
			return [
				'id' => $item['mod_id'],
				'titulo' => $item['mod_nombre'],
				'enlace' => $item['mod_url'],
				'icono' => $item['mod_icono'],
				'r' => $item['r'],
				'w' => $item['w'],
				'u' => $item['u'],
				'd' => $item['d'],
				'hijos' => $this->construirMenu($datosMenu, $item['mod_id'])
			];
		}, array_filter($datosMenu, fn($item) => $item['idPadre'] == $id_padre)));
	}






}
