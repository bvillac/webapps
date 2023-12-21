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

	public function loginData(string $usuario, string $clave)
	{
		$db_name = $this->getDbNameMysql();
		$sql = "SELECT usu_id,per_id,usu_alias,estado_logico Estado ";
		$sql .= "  FROM " . $db_name . ".usuario ";
		$sql .= " Where usu_correo='{$usuario}' AND usu_clave='{$clave}' AND estado_logico!=0 ";
		$request = $this->select($sql);
		return $request;
	}

	public function sessionLogin(int $IdsUser)
	{
		$sql = "SELECT a.usu_id UsuId,a.usu_correo,a.usu_alias Alias,b.per_cedula Dni,CONCAT(b.per_nombre,' ',b.per_apellido) Nombres,";
		$sql .= "	b.per_fecha_nacimiento FechaNac,b.per_nombre,b.per_apellido,b.per_genero Genero,a.estado_logico Estado,";
		$sql .= "	date(a.fecha_creacion) FechaIng,b.per_telefono Telefono,b.per_direccion Direccion";
		$sql .= "		FROM " . $this->db_name . ".usuario a";
		$sql .= "			INNER JOIN " . $this->db_name . ".persona b";
		$sql .= "				ON a.per_id=b.per_id";
		$sql .= "	WHERE a.estado_logico=1 AND a.usu_id={$IdsUser}";
		$request = $this->select($sql);
		$_SESSION['usuarioData'] = $request;
		//Obtener el Rol de la Persona
		$resulRol = $this->selectRolesPermiso($_SESSION['idsUsuario'], $_SESSION['idEmpresa']); //IMPLMENTAR LO DE EMPRESAS
		$_SESSION['usuarioData']['RolID'] = $resulRol['Ids'];
		$_SESSION['usuarioData']['Rol'] = $resulRol['rol_nombre'];

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
	public function selectRolesPermiso(int $usu_id, int $emp_id)
	{
		$db_name = $this->getDbNameMysql();
		$sql = "SELECT distinct(a.rol_id) Ids,b.rol_nombre ";
		$sql .= "	FROM " . $db_name . ".permiso a ";
		$sql .= "		INNER JOIN " . $db_name . ".rol b ";
		$sql .= "			ON a.rol_id=b.rol_id ";
		$sql .= "	WHERE a.estado_logico!=0 AND a.usu_id={$usu_id} AND a.emp_id={$emp_id} ";
		//$request = $this->select_all($sql);//Devuelve mas de 1
		$request = $this->select($sql); //Devuelve solo 1
		return $request;
	}

	public function permisosModulo(int $usu_id, int $emp_id,int $rolId)
	{
		$sql = "SELECT a.mod_id,SUBSTRING(a.mod_id, 1, LENGTH(a.mod_id) - 2) idPadre,b.mod_nombre,b.mod_url,b.mod_icono,a.r,a.w,a.u,a.d ";
		$sql .= "	FROM " . $this->db_name . ".permiso a ";
		$sql .= "		INNER JOIN " . $this->db_name . ".modulo b ";
		$sql .= "			ON a.mod_id=b.mod_id ";
		$sql .= "	WHERE a.estado_logico!=0 AND a.usu_id={$usu_id} AND a.emp_id={$emp_id} AND a.rol_id={$rolId} ";
		$sql .= "		ORDER BY a.mod_id ASC ";
		$request = $this->select_all($sql);
		$menuArray = $this->construirMenu($request,"");
		//putMessageLogFile($menuArray);
		return $menuArray;
	}

	
	// Función recursiva para construir el array de menú
	private function construirMenu($datosMenu,$id_padre) {
		// Array de menú vacío
		$menu = array();
		// Recorrer los datos del menú
		foreach ($datosMenu as $item) {
			if ($item['idPadre'] == $id_padre) {
				// Construir el elemento del menú
				$menuItem = array(
					'id' => $item['mod_id'],
					'titulo' => $item['mod_nombre'],
					'enlace' => $item['mod_url'],
					'icono' => $item['mod_icono'],
					'r' => $item['r'],
					'w' => $item['w'],
					'u' => $item['u'],
					'd' => $item['d'],
					'hijos' => $this->construirMenu($datosMenu,$item['mod_id']) // Llamada recursiva para obtener los hijos
				);
				// Agregar el elemento al array de menú
				$menu[] = $menuItem;
			}
		}
		// Devolver el array de menú
		return $menu;
	}


	


}
