<?php
require_once("Models/UsuariosModel.php");
class UsuariosEmpresaModel extends Mysql
{
	private $db_name;
	private $rolName;
	private $EmpIds;
	public function __construct()
	{
		parent::__construct();
		$this->db_name = $this->getDbNameMysql();
		$this->rolName = retornarDataSesion("RolNombre");
		$this->EmpIds = retornarDataSesion("Emp_Id");
	}


	public function consultarDatos()
	{
		try {
			$rolName = retornarDataSesion("RolNombre");
			$empresaId = retornarDataSesion("Emp_Id");
			$sql = "
				SELECT 
					a.usu_id AS Ids,
					a.per_id,
					a.usu_correo,
					a.usu_alias,
					p.per_cedula,
					CONCAT(p.per_nombre, ' ', p.per_apellido) AS Nombres,
					a.estado_logico AS Estado,
					(select  GROUP_CONCAT(b1.tie_nombre SEPARATOR ', ') from  db_pedidos.usuario_tienda a1 
						inner join db_pedidos.tienda b1 on a1.tie_id=b1.tie_id where a1.usu_id=a.usu_id) as Tiendas,
					(select  GROUP_CONCAT(r.rol_nombre SEPARATOR ', ') from  {$this->db_name}.empresa_usuario_rol m 
						inner join ({$this->db_name}.empresa_rol n inner join {$this->db_name}.rol r on r.rol_id=n.rol_id) on n.erol_id=m.erol_id
						where m.eusu_id=x.eusu_id) as RolEmpresa,
					(select GROUP_CONCAT(cu1.cli_razon_social SEPARATOR ', ') from {$this->db_name}.empresa_usuario eu1
						inner join {$this->db_name}.cliente cu1 
						where eu1.cli_id=cu1.cli_id and eu1.emp_id=x.emp_id) as CliEmpresa,'' AS RolId
				FROM {$this->db_name}.empresa_usuario x
				INNER JOIN {$this->db_name}.usuario a ON a.usu_id = x.usu_id
				INNER JOIN {$this->db_name}.persona p ON a.per_id = p.per_id
				WHERE x.emp_id = :emp_id
			";

			// Solo mostrar usuarios activos si el rol no es admin
			if ($rolName !== "admin") {
				$sql .= " AND x.estado_logico != 0 ";
			}


			$resultado = $this->select_all($sql, [":emp_id" => $empresaId]);

			if ($resultado === false) {
				logFileSystem("Consulta fallida en consultarDatos()", "WARNING");
				return [];
			}
			foreach ($resultado as &$row) {
				$row['RolId'] = $this->obtenerRolesConIds($row['RolEmpresa']);
			}
			unset($row);
			return $resultado;
		} catch (Exception $e) {
			logFileSystem("Error en consultarDatos(): " . $e->getMessage(), "ERROR");
			return [];
		}
	}


	/**
	 * Convierte una cadena de nombres de roles en un array de arrays con nombre e id de cada rol.
	 * @param string $rolesString Cadena de nombres de roles separados por coma
	 * @return array Array de arrays ['id' => ..., 'nombre' => ...]
	 */

	private function obtenerRolesConIds($rolesString)
	{
		if (!is_string($rolesString) || trim($rolesString) === '') {
			return 0;
		}
		$roles = array_filter(array_map('trim', explode(',', (string) $rolesString)), function ($r) {
			return $r !== ''; });
		if (empty($roles)) {
			return 0;
		}
		$placeholders = implode(',', array_fill(0, count($roles), '?'));

		if (empty($placeholders) || count($roles) < 1) {
			return 0;
		}

		$sql = "SELECT rol_id AS id FROM {$this->db_name}.rol WHERE rol_nombre IN ($placeholders) AND estado_logico != 0 LIMIT 1";
		$result = $this->select($sql, array_values($roles));
		if ($result === false || !isset($result['id'])) {
			logFileSystem("Fallo al obtener ID de rol en obtenerRolesConIds", "WARNING");
			return 0;
		}
		return $result['id'];
	}


	public function consultarRolEmpresa()
	{
		try {
			//$rolName = retornarDataSesion("RolNombre");
			$empresaId = retornarDataSesion("Emp_Id");

			$sql = "
			SELECT 
				a.erol_id AS Ids,
				b.rol_nombre AS Nombre 
			FROM {$this->db_name}.empresa_rol a
			INNER JOIN {$this->db_name}.rol b ON a.rol_id = b.rol_id
			WHERE a.emp_id = :emp_id AND a.estado_logico != 0
		";

			// Si el rol no es admin, podrías aplicar más filtros si lo deseas
			// En este caso, no se requiere más condición ya que ya se filtra por estado_logico

			$resultado = $this->select_all($sql, [":emp_id" => $empresaId]);

			if ($resultado === false) {
				logFileSystem("Consulta fallida en consultarRolEmpresa", "WARNING");
				return [];
			}

			return $resultado;
		} catch (Exception $e) {
			logFileSystem("Error en consultarRolEmpresa: " . $e->getMessage(), "ERROR");
			return [];
		}
	}


	public function insertData(array $dataObj)
	{
		$con = $this->getConexion();
		$usuarioModel = new UsuariosModel();
		$idsUsuCre = retornaUser();
		$idsEmpresa = retornarDataSesion('Emp_Id');
		$IdCliente = $dataObj['cliente'] ?? 0; // Cliente opcional, si no se envía, será 0

		try {
			$con->beginTransaction();

			// Validación: verificar si la persona ya existe
			$sqlCheck = " SELECT 1 
						FROM {$this->db_name}.persona 
						WHERE per_cedula = :cedula 
						AND per_nombre = :nombre 
						AND per_apellido = :apellido ";
			$stmtCheck = $con->prepare($sqlCheck);
			$stmtCheck->execute([
				':cedula' => $dataObj['dni'],
				':nombre' => $dataObj['nombre'],
				':apellido' => $dataObj['apellido']
			]);

			if ($stmtCheck->fetch()) {
				$con->rollBack();
				return ["status" => false, "message" => "El usuario ya existe con esa cédula, nombre y apellido."];
			}

			// Insertar en persona
			$arrDataPer = [
				$dataObj['dni'],
				$dataObj['nombre'],
				$dataObj['apellido'],
				$dataObj['fecha_nacimiento'],
				$dataObj['telefono'],
				$dataObj['direccion'],
				$dataObj['genero'],
				$idsUsuCre
			];
			$perId = $usuarioModel->insertarPersona($con, $arrDataPer);

			// Insertar en usuario
			$claveHash = generaClave($dataObj['password']);
			$arrDataUsu = [
				$perId,
				$dataObj['email'],
				$claveHash,
				$dataObj['alias'],
				$idsUsuCre
			];
			$usuId = $usuarioModel->insertarUsuario($con, $arrDataUsu);

			// Relación con empresa
			$arrDataEmp = [$idsEmpresa, $usuId, $IdCliente, 1, $idsUsuCre];
			$eusuId = $this->insertarEmpresaUsuario($con, $arrDataEmp);

			// Rol
			$rolId = $dataObj['rol'];
			$arrDataUsuRol = [$eusuId, $rolId, 1, $idsUsuCre];
			$this->insertarEmpresaUsuarioRol($con, $arrDataUsuRol);

			// Permisos
			//$modulos = $this->retornarModuloRolEmpresa($idsEmpresa, $rolId);
			//$this->insertarPermisoEmpresaUsuario($con, $modulos, $eusuId, $rolId, $idsUsuCre);

			$con->commit();

			return [
				"status" => true,
				"numero" => $usuId,
				"message" => "Usuario registrado correctamente."
			];
		} catch (Exception $e) {
			$con->rollBack();
			logFileSystem("Error en insertData: " . $e->getMessage(), "ERROR");

			return [
				"status" => false,
				"message" => "Error al guardar el usuario: " . $e->getMessage()
			];
		}
	}


	public function insertarEmpresaUsuario($con, $arrData)
	{
		$sqlInsert = "INSERT INTO {$this->db_name}.empresa_usuario 
								(emp_id, usu_id,cli_id,estado_logico,usuario_creacion, fecha_creacion) 
								VALUES (?,?,?,?,?, CURRENT_TIMESTAMP)";
		return $this->insertConTrans($con, $sqlInsert, $arrData);

	}

	public function insertarEmpresaUsuarioRol($con, $arrData)
	{
		$sqlInsert = "INSERT INTO {$this->db_name}.empresa_usuario_rol 
								(eusu_id, erol_id,estado_logico,usuario_creacion, fecha_creacion) 
								VALUES (?,?,?,?, CURRENT_TIMESTAMP)";
		return $this->insertConTrans($con, $sqlInsert, $arrData);

	}

	public function retornarModuloRolEmpresa(int $Emp_Id, int $Erol_id)
	{
		try {
			$sql = " SELECT b.emod_id,b.mod_id 
						FROM {$this->db_name}.permiso a 
							inner join {$this->db_name}.empresa_modulo b 
							on a.emod_id=b.emod_id
						where a.estado_logico!=0 and a.erol_id=:erol_id and b.emp_id=:emp_id";

			$resultado = $this->select_all($sql, [":emp_id" => $Emp_Id, ":erol_id" => $Erol_id]);
			if ($resultado === false) {
				logFileSystem("Consulta fallida en retornarModuloRolEmpresa", "WARNING");
				return [];
			}
			return $resultado;
		} catch (Exception $e) {
			logFileSystem("Error en retornarModuloRolEmpresa: " . $e->getMessage(), "ERROR");
			return [];
		}
	}


	public function insertarPermisoEmpresaUsuario($con, $modulos, int $Eusu_id, int $Erol_id, $UsuCre)
	{
		$privilegio = $this->retornarPrivilegioRol($Erol_id);
		$sql = "INSERT INTO {$this->db_name}.permiso
			(`eusu_id`,`emod_id`,`erol_id`,`mod_id`,`r`,`w`,`u`,`d`,`estado_logico`,`usuario_creacion`)
			VALUES 
			(:eusu_id,:emod_id,:erol_id,:mod_id,:r,:w,:u,:d,:estado_logico,:usuario_creacion);";
		$stmt = $con->prepare($sql);
		foreach ($modulos as $modulo) {
			$stmt->execute([
				':eusu_id' => $Eusu_id,
				':emod_id' => $modulo['emod_id'],
				':erol_id' => $Erol_id,
				':mod_id' => $modulo['mod_id'],
				':r' => $privilegio['r'],
				':w' => $privilegio['w'],
				':u' => $privilegio['u'],
				':d' => $privilegio['d'],
				':estado_logico' => 1,
				':usuario_creacion' => $UsuCre,
			]);
		}

	}

	public function retornarPrivilegioRol(int $Erol_id)
	{
		try {
			$sql = " SELECT b.* FROM 
						{$this->db_name}.empresa_rol a
							inner join {$this->db_name}.rol b
							on a.rol_id=b.rol_id
						where a.estado_logico!=0 and  a.erol_id=:erol_id;";

			$resultado = $this->select($sql, [":erol_id" => $Erol_id]);
			if ($resultado === false) {
				logFileSystem("Consulta fallida en retornarPrivilegioRol", "WARNING");
				return [];
			}
			return $resultado;
		} catch (Exception $e) {
			logFileSystem("Error en retornarPrivilegioRol: " . $e->getMessage(), "ERROR");
			return [];
		}
	}


	public function updateData(array $dataObj)
	{
		$con = $this->getConexion(); // Obtiene la conexión a la base de datos
		$usuarioModel = new UsuariosModel();
		$idsUsuUpd = retornaUser(); // Usuario que actualiza
		$idsUsu = $dataObj['usuIds'];

		try {
			$con->beginTransaction(); // Inicia la transacción

			// Verifica que el usuario exista
			$objUsuario = $this->consultaUsuario($idsUsu);
			if (!$objUsuario || empty($objUsuario['per_id'])) {
				throw new Exception("Usuario no encontrado.");
			}

			$perId = $objUsuario['per_id'];

			// Actualizar datos del usuario
			$arrDataUsu = [
				$dataObj['alias'],
				$dataObj['email'],
				$dataObj['estado'],
				$idsUsuUpd
			];
			$this->actualizarUsuario($con, $idsUsu, $arrDataUsu);

			// Actualizar datos de la persona
			$arrDataPer = [
				$dataObj['nombre'],
				$dataObj['apellido'],
				$dataObj['telefono'],
				$dataObj['direccion'],
				$dataObj['genero'],
				$idsUsuUpd
			];
			$this->actualizarPersona($con, $perId, $arrDataPer);

			$con->commit(); // Confirma los cambios

			return [
				"status" => true,
				"numero" => 0,
				"message" => "Registros actualizados correctamente."
			];

		} catch (Exception $e) {
			$con->rollBack(); // Revierte los cambios en caso de error
			logFileSystem("Error en updateData: " . $e->getMessage(), "ERROR");
			return [
				"status" => false,
				"message" => "Error en la operación: " . $e->getMessage()
			];
		}
	}

	public function actualizarUsuario($con, $UsuId, $arrData)
	{
		if (empty($UsuId)) {
			return false; // ID inválido
		}

		$sql = "UPDATE {$this->db_name}.usuario 
			   SET usu_alias = ?, 
				   usu_correo = ?, 
				   estado_logico = ?, 
				   usuario_modificacion = ?, 
				   fecha_modificacion = CURRENT_TIMESTAMP()
			 WHERE usu_id = ?";

		// Agregamos el ID como último parámetro
		$arrData[] = $UsuId;

		return $this->updateConTrans($con, $sql, $arrData);
	}

	public function actualizarPersona($con, $PerId, $arrData)
	{
		if (empty($PerId)) {
			return false; // ID inválido
		}

		$sql = "UPDATE {$this->db_name}.persona 
			   SET per_nombre = ?, 
				   per_apellido = ?, 
				   per_telefono = ?, 
				   per_direccion = ?, 
				   per_genero = ?, 
				   usuario_modificacion = ?, 
				   fecha_modificacion = CURRENT_TIMESTAMP()
			 WHERE per_id = ?";

		// Añadir el ID como último parámetro
		$arrData[] = $PerId;

		return $this->updateConTrans($con, $sql, $arrData);
	}

	private function consultaUsuario(int $ids)
	{
		try {
			$sql = "select * from {$this->db_name}.usuario 
						where usu_id=:usu_id";
			$arrParams = [":usu_id" => $ids];
			$resultado = $this->select($sql, $arrParams);
			if ($resultado === false) {
				logFileSystem("Consulta fallida consultaUsuario", "WARNING");
				return []; // Retornar un array vacío en lugar de false para evitar errores en la vista
			}
			return $resultado;
		} catch (Exception $e) {
			logFileSystem("Error en consultaUsuario: " . $e->getMessage(), "ERROR");
			return []; // En caso de error, retornar un array vacío
		}
	}


	public function insertDataEmpUsuRol(array $dataObj)
	{
		$con = $this->getConexion();
		$usuarioModel = new UsuariosModel();
		$idsUsuCre = retornaUser();
		$idsEmpresa = retornarDataSesion('Emp_Id');
		$IdCliente = $dataObj['cliente'] ?? 0; // Cliente opcional, si no se envía, será 0

		try {
			$con->beginTransaction();
			$perId = $dataObj['perId'];
			$usuId = $dataObj['usuId'];

			// Relación con empresa
			$arrDataEmp = [$idsEmpresa, $usuId, $IdCliente, 1, $idsUsuCre];
			$eusuId = $this->insertarEmpresaUsuario($con, $arrDataEmp);

			// Rol
			$rolId = $dataObj['rol'];
			$arrDataUsuRol = [$eusuId, $rolId, 1, $idsUsuCre];
			$this->insertarEmpresaUsuarioRol($con, $arrDataUsuRol);

			// Permisos
			//$modulos = $this->retornarModuloRolEmpresa($idsEmpresa, $rolId);
			//$this->insertarPermisoEmpresaUsuario($con, $modulos, $eusuId, $rolId, $idsUsuCre);

			$con->commit();

			return [
				"status" => true,
				"numero" => $usuId,
				"message" => "Usuario registrado correctamente."
			];
		} catch (Exception $e) {
			$con->rollBack();
			logFileSystem("Error en insertData: " . $e->getMessage(), "ERROR");

			return [
				"status" => false,
				"message" => "Error al guardar el usuario: " . $e->getMessage()
			];
		}
	}





}
