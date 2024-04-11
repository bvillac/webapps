<?php
//namespace Models;
require_once("Models/EmpresaModel.php");
//require_once("Libraries/Core/Conexion.php");
class UsuariosModel extends Mysql
{
	private $db_name;
	private $db_nameAdmin;
	public function __construct()
	{
		parent::__construct();
		$this->db_name = $this->getDbNameMysql();
		//$this->db_nameAdmin = $this->getDbNameMysqlAdmin();
	}


	public function insertData(
		string $Dni,
		string $FecNaci,
		string $Nombre,
		string $Apellido,
		string $Telefono,
		string $Correo,
		string $Clave,
		string $Genero,
		string $Direccion,
		string $Alias,
		int $rol_id,
		int $estado
	) {
		$db_name = $this->getDbNameMysql();
		$idsEmpresa = $_SESSION['idEmpresa'];
		$idsUsuCre = retornaUser();
		//Verifica que el correo no existe
		$sql = "SELECT * FROM " . $this->db_name . ".persona WHERE per_cedula = '{$Dni}' AND per_nombre = '{$Nombre}' AND per_apellido = '{$Apellido}'   ";
		$request = $this->select_all($sql);
		if (empty($request)) { //Si no hay resultado Inserta los datos
			$con = $this->getConexion();
			$con->beginTransaction();
			try {
				$arrDataPer = array($Dni, $Nombre, $Apellido, $FecNaci, $Telefono, $Direccion, $Genero, $idsUsuCre);
				$PerIds = $this->insertarPersona($con, $db_name, $arrDataPer);
				//putMessageLogFile($PerIds);			
				$arrDataUsu = array($PerIds, $Correo, $Clave, $Alias, $idsUsuCre);
				$UsuIds = $this->insertarUsuario($con, $db_name, $arrDataUsu);
				$arrDataEmp = array($idsEmpresa, $UsuIds, $rol_id, $idsUsuCre);
				//$UsuIds=$this->insertarEmpresaUsuario($con,$db_name,$arrDataEmp);
				$con->commit();
				$arroout["status"] = true;
				$arroout["numero"] = $PerIds;
				return $arroout;
			} catch (Exception $e) {
				$con->rollBack();
				//echo "Fallo: " . $e->getMessage();
				//throw $e;
				$arroout["message"] = $e->getMessage();
				$arroout["status"] = false;
				return $arroout;
			}
		} else {
			$arroout["status"] = false;
			$arroout["exist"] = "exist";
			return $arroout;
		}
	}

	private function insertarPersona($con, $db_name, $arrData)
	{
		$SqlQuery  = "INSERT INTO " . $db_name . ".persona ";
		$SqlQuery .= "(per_cedula,per_nombre,per_apellido,per_fecha_nacimiento,per_telefono,per_direccion,per_genero,usuario_creacion,estado_logico) ";
		$SqlQuery .= " VALUES(?,?,?,?,?,?,?,?,1) ";
		$insert = $con->prepare($SqlQuery);
		$resInsert = $insert->execute($arrData);
		if ($resInsert) {
			$lastInsert = $con->lastInsertId();
		} else {
			$lastInsert = 0;
		}
		return $lastInsert;
	}



	private function insertarUsuario($con, $db_name, $arrData)
	{
		$SqlQuery  = "INSERT INTO " . $db_name . ".usuario ";
		$SqlQuery .= "(per_id,usu_correo,usu_clave,usu_alias,usuario_creacion,estado_logico) ";
		$SqlQuery .= " VALUES(?,?,?,?,?,1) ";
		$insert = $con->prepare($SqlQuery);
		$resInsert = $insert->execute($arrData);
		if ($resInsert) {
			$lastInsert = $con->lastInsertId();
		} else {
			$lastInsert = 0;
		}
		return $lastInsert;
	}


	public function consultarReporteUsuarioPDF(string $idUsuario, $idpersona = NULL)
	{
		$busqueda = "";
		/*if($idpersona != NULL){
				$busqueda = " AND p.personaid =".$idpersona;
			}*/
		//$empresa = 1;
		$IdsEmpresa = $_SESSION['idEmpresa'];
		$ObjEmp = new EmpresaModel;
		$request = array();
		$requestEmpresa = $ObjEmp->consultarEmpresaId($IdsEmpresa);
		$requestCab = $this->consultarCabecerDoc($idUsuario);
		if (!empty($requestCab)) {
			$requestDet = $this->consultarDetalleDoc($idUsuario);
			$request = array(
				'cabReporte' => $requestCab,
				'detReporte' => $requestDet,
				'empData' => $requestEmpresa
			);
		}
		return $request;
	}


	public function consultarCabecerDoc(string $IdsEmpresa)
	{
		//$db_name=$this->getDbNameMysql();
		$IdsEmpresa = $_SESSION['idEmpresa'];
		$sql = "SELECT a.usu_id Ids,b.per_id ,a.usu_correo Correo,a.usu_alias Alias,a.usu_clave Clave,b.per_cedula Cedula,b.per_nombre Nombre,b.per_apellido Apellido, ";
		$sql .= "	a.estado_logico Estado  ";
		$sql .= "	FROM " . $this->db_name . ".usuario a ";
		$sql .= "		INNER JOIN " . $this->db_name . ".persona b ON a.per_id=b.per_id AND b.estado_logico!=0  ";
		$sql .= "	WHERE a.estado_logico!=0  ";

		//putMessageLogFile($sql);
		$request = $this->select($sql);
		return $request;
	}
	public function consultarDetalleDoc(string $IdsEmpresa)
	{
		//$db_name=$this->getDbNameMysql();
		//$IdsEmpresa="1";
		$IdsEmpresa = $_SESSION['idEmpresa'];
		$sql = "SELECT a.usu_id Ids,a.per_id,a.usu_correo,a.usu_alias,a.usu_clave,b.per_cedula,b.per_nombre,b.per_apellido, ";
		$sql .= "	a.estado_logico Estado  ";
		$sql .= "	FROM " . $this->db_name . ".usuario a ";
		$sql .= "		INNER JOIN " . $this->db_name . ".persona b ON a.per_id=b.per_id AND b.estado_logico!=0  ";
		$sql .= "	WHERE a.estado_logico!=0  ";
		//putMessageLogFile($sql);
		$request = $this->select_all($sql);
		return $request;
	}

	public function consultarDatosUsuarios()
	{
		//$db_name=$this->getDbNameMysql();
		$IdsEmpresa = $_SESSION['idEmpresa'];
		$sql = "SELECT a.usu_id Ids,a.per_id,a.usu_correo,a.usu_alias,a.usu_clave,b.per_cedula,b.per_nombre,b.per_apellido, ";
		$sql .= "	a.estado_logico Estado  ";
		$sql .= "	FROM " . $this->db_name . ".usuario a ";
		$sql .= "		INNER JOIN " . $this->db_name . ".persona b ON a.per_id=b.per_id AND b.estado_logico!=0  ";
		$sql .= "	WHERE a.estado_logico!=0  ";
		$request = $this->select_all($sql);
		return $request;
	}

	public function consultarDatos()
	{
		$db_name = $this->getDbNameMysql();
		$idsEmpresa = $_SESSION['idEmpresa'];
		$rolId=$_SESSION['usuarioData']['RolID'];

		$sql = "SELECT a.usu_id Ids,a.per_id,a.usu_correo,a.usu_alias,a.usu_clave,p.per_cedula,p.per_nombre,p.per_apellido,a.estado_logico Estado  	";
		$sql .= "	FROM " . $db_name . ".usuario a ";
		$sql .= "		INNER JOIN " . $db_name . ".persona p ";
		$sql .= "			ON a.per_id=p.per_id AND p.estado_logico!=0 	";
		if($rolId!=1){//Diferente de rol administrador
			$sql .= "	WHERE a.estado_logico!=0 ";
		}
		//putMessageLogFile($sql);
		$request = $this->select_all($sql);
		return $request;
	}

	public function consultarRoles()
	{
		$db_name = $this->getDbNameMysql();
		$sql = "SELECT rol_id Ids, rol_nombre Nombre ";
		$sql .= " FROM " . $db_name . ".rol WHERE estado_logico!=0 ORDER BY rol_nombre ASC";
		//putMessageLogFile($sql);
		$request = $this->select_all($sql);
		return $request;
	}

	public function consultarDatosId(int $Ids)
	{
		$db_name = $this->getDbNameMysql();
		//$idsEmpresa = $_SESSION['idEmpresa'];
		$sql = "SELECT distinct(a.usu_id) Ids,a.per_id,a.usu_correo,a.usu_alias Alias,p.per_cedula Dni,p.per_nombre Nombre,p.per_apellido Apellido,p.per_fecha_nacimiento FechaNac, ";
		//$sql .= "	r.rol_nombre Rol,c.rol_id RolID,p.per_genero Genero,a.estado_logico Estado,date(a.fecha_creacion) FechaIng,p.per_telefono Telefono,p.per_direccion Direccion ";
		$sql .= "	p.per_genero Genero,a.estado_logico Estado,date(a.fecha_creacion) FechaIng,p.per_telefono Telefono,p.per_direccion Direccion ";
		$sql .= " FROM " . $db_name . ".usuario a ";
		$sql .= "	INNER JOIN " . $db_name . ".persona p ";
		//$sql .= "		ON a.per_id=p.per_id AND p.estado_logico!=0 ";
		$sql .= "		ON a.per_id=p.per_id ";
		//$sql .= "	INNER JOIN (" . $db_name . ".permiso c ";
		//$sql .= "			INNER JOIN " . $db_name . ".rol r ON c.rol_id=r.rol_id) ";
		//$sql .= "		ON a.usu_id=c.usu_id AND c.estado_logico!=0 ";
		//$sql .= " WHERE a.estado_logico!=0 AND c.emp_id='{$idsEmpresa}' AND a.usu_id={$Ids} ";
		$sql .= " WHERE  a.usu_id={$Ids} ";
		$request = $this->select($sql);
		return $request;
	}


	public function updateData(
		int $UsuId,
		string $Dni,
		string $FecNaci,
		string $Nombre,
		string $Apellido,
		string $Telefono,
		string $Correo,
		string $Clave,
		string $Genero,
		string $Direccion,
		string $Alias,
		int $rol_id,
		int $estado,
		int $per_id,
		int $eusu_id
	) {

		$db_name = $this->getDbNameMysql();
		//$idsUsuMod=1;
		$idsUsuMod = $_SESSION['idsUsuario'];
		$idsEmpresa = 1;


		$con = $this->getConexion();
		$con->beginTransaction();
		try {
			$strClave = ($Clave != "") ? ",usu_clave='{$Clave}'" : "";
			//Actualizar Usuario
			$SqlQuery  = "UPDATE " . $db_name . ".usuario  ";
			$SqlQuery .= "SET usu_alias = ?,usu_correo = ?, estado_logico = ?,usuario_modificacion=?,fecha_modificacion = CURRENT_TIMESTAMP(){$strClave}";
			$SqlQuery .= " WHERE usu_id = '{$UsuId}' ";
			$update = $con->prepare($SqlQuery);
			//$arrDataUsu = array($Alias, $Correo, $Clave, $estado, $idsUsuMod);
			$arrDataUsu = array($Alias, $Correo, $estado, $idsUsuMod);
			$update->execute($arrDataUsu);

			//Actualizar Personas
			$SqlQuery  = "UPDATE " . $db_name . ".persona  ";
			$SqlQuery .= "SET per_cedula = ?,per_nombre = ?,per_apellido = ?,per_fecha_nacimiento = ?,per_telefono = ?, ";
			$SqlQuery .= " per_direccion = ?,per_genero=?,estado_logico = ?,usuario_modificacion=?,fecha_modificacion = CURRENT_TIMESTAMP() ";
			$SqlQuery .= " WHERE per_id = '{$per_id}' ";
			$update = $con->prepare($SqlQuery);
			$arrDataPer = array($Dni, $Nombre, $Apellido, $FecNaci, $Telefono, $Direccion, $Genero, $estado, $idsUsuMod);
			$update->execute($arrDataPer);

			//Actualizar Empresa Usuario Rol
			/*$SqlQuery  = "UPDATE " . $db_name . ".empresa_usuario  ";
			$SqlQuery .= "SET rol_id = ?,estado_logico = ?,usuario_modificacion=?,fecha_modificacion = CURRENT_TIMESTAMP() ";
			$SqlQuery .= " WHERE emp_id = '{$idsEmpresa}' AND usu_id = '{$UsuId}' ";
			$update = $con->prepare($SqlQuery);
			$arrDataEmp = array($rol_id, $estado, $idsUsuMod);
			$update->execute($arrDataEmp);*/

			$con->commit();
			$arroout["status"] = true;
			return $arroout;
		} catch (Exception $e) {
			$con->rollBack();
			//echo "Fallo: " . $e->getMessage();
			//throw $e;
			$arroout["message"] = $e->getMessage();
			$arroout["status"] = false;
			return $arroout;
		}
	}

	public function updateDataPerfil(int $UsuId, string $Nombre, string $Apellido, int $Telefono, string $Direccion, string $Alias, string $Clave)
	{
		$db_name = $this->getDbNameMysql();
		$per_id = $_SESSION['idsPersona'];
		$con = $this->getConexion();
		$con->beginTransaction();
		try {
			$strClave = ($Clave != "") ? ",usu_clave={$Clave}" : "";
			//Actualizar Usuario
			$SqlQuery  = "UPDATE " . $db_name . ".usuario  ";
			$SqlQuery .= "SET usu_alias = ?,usuario_modificacion=?,fecha_modificacion = CURRENT_TIMESTAMP(){$strClave} ";
			$SqlQuery .= " WHERE usu_id = {$UsuId} ";
			$update = $con->prepare($SqlQuery);
			$arrDataUsu = array($Alias, $UsuId);
			$update->execute($arrDataUsu);

			//Actualizar Persona
			$SqlQuery  = "UPDATE " . $db_name . ".persona  ";
			$SqlQuery .= "SET per_nombre = ?,per_apellido = ?,per_telefono = ?, ";
			$SqlQuery .= " per_direccion = ?,usuario_modificacion=?,fecha_modificacion = CURRENT_TIMESTAMP() ";
			$SqlQuery .= " WHERE per_id = {$per_id} ";
			$update = $con->prepare($SqlQuery);
			$arrDataPer = array($Nombre, $Apellido, $Telefono, $Direccion, $UsuId);
			$update->execute($arrDataPer);
			$con->commit();
			return true;
		} catch (Exception $e) {
			$con->rollBack();
			//echo "Fallo: " . $e->getMessage();
			//throw $e;
			return false;
		}
	}


	public function deleteUsuario(int $Ids)
	{
		$db_name = $this->getDbNameMysql();
		//$idsUsuMod=1;
		$idsUsuMod = $_SESSION['idsUsuario'];
		$sql = "UPDATE " . $db_name . ".usuario SET estado_logico = ?,usuario_modificacion=?,fecha_modificacion = CURRENT_TIMESTAMP() WHERE usu_id = {$Ids} ";
		$arrData = array(0, $idsUsuMod);
		$request = $this->update($sql, $arrData);
		return $request;
	}
}
