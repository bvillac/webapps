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
					a.estado_logico AS Estado
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

			return $resultado;
		} catch (Exception $e) {
			logFileSystem("Error en consultarDatos(): " . $e->getMessage(), "ERROR");
			return [];
		}
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
		$con = $this->getConexion(); // Obtiene la conexión a la base de datos
		$arroout = ["status" => false, "message" => "No se realizó ninguna operación."];
		$usuarioModel = new UsuariosModel();
		$idsUsuCre = retornaUser();
		$idsEmpresa = retornarDataSesion('Emp_Id');
		try {
			$con->beginTransaction(); // Inicia una transacción
			// Verificar si el producto ya existe la persona
			$sqlCheck = "SELECT * FROM {$this->db_name}.persona WHERE per_cedula = :per_cedula AND per_nombre = :per_nombre AND per_apellido = :per_apellido  ";
			$stmtCheck = $con->prepare($sqlCheck);
			$stmtCheck->execute([
				':per_cedula' => $dataObj['dni'],
				':per_nombre' => $dataObj['nombre'],
				':per_apellido' => $dataObj['apellido']
			]);
			//{"usuIds":"","dni":"121231231231","fecha_nacimiento":"2025-05-10","nombre":"JOSE","apellido":"PEDRO",
			//	"telefono":"0999999999","direccion":"COLON","alias":"JPE","genero":"M","email":"JOSE@miller.com","estado":"1",
			//	"rol":"12","password":"1234523232323"}

			$result = $stmtCheck->fetch(PDO::FETCH_ASSOC);
			if ($result) {
				// Actualizar si ya existe
				/*$sqlUpdate = "UPDATE {$this->db_name}.empresa_usuario 
																									SET estado_logico = 1, 
																										fecha_modificacion = CURRENT_TIMESTAMP 
																									WHERE eusu_id = :eusu_id";
																						$stmtUpdate = $con->prepare($sqlUpdate);
																						$stmtUpdate->execute([
																							':eusu_id' => $result['eusu_id']
																						]);*/
			} else {
				// Insertar si no existe
				$Erol_id = $dataObj['rol'];
				$arrDataPer = array(
					$dataObj['dni'],
					$dataObj['nombre'],
					$dataObj['apellido'],
					$dataObj['fecha_nacimiento'],
					$dataObj['telefono'],
					$dataObj['direccion'],
					$dataObj['genero'],
					$idsUsuCre
				);
				$PerIds = $usuarioModel->insertarPersona($con, $arrDataPer);
				$Clave = empty($dataObj['password']) ? hash("SHA256", passGenerator()) : hash("SHA256", $dataObj['password']);
				$arrDataUsu = array(
					$PerIds,
					$dataObj['email'],
					$Clave,
					$dataObj['alias'],
					$idsUsuCre
				);
				$UsuIds = $usuarioModel->insertarUsuario($con, $arrDataUsu);
				$arrDataEmp = array($idsEmpresa, $UsuIds, 1, $idsUsuCre);
				$Eusu_id = $this->insertarEmpresaUsuario($con, $arrDataEmp);
				$arrDataUsuRol = array($Eusu_id, $Erol_id, 1, $idsUsuCre);
				$Eusu_id = $this->insertarEmpresaUsuarioRol($con, $arrDataUsuRol);
				$modulos = $this->retornarModuloRolEmpresa($idsEmpresa, $Erol_id);
				$this->insertarPermisoEmpresaUsuario($con, $modulos, $Eusu_id, $Erol_id, $idsUsuCre);

			}

			//$this->actualizaEmpresaUsuario($con, $dataObj['usuIds'], $dataObj['valores']);
			$con->commit(); // Confirma la transacción
			return ["status" => true, "numero" => 0, "message" => "Registros guardados correctamente."];

		} catch (Exception $e) {
			$con->rollBack(); // Revierte la transacción en caso de error
			logFileSystem("Error en insertData: " . $e->getMessage(), "ERROR");
			return ["status" => false, "message" => "Error en la operación: " . $e->getMessage()];
		}
	}


	public function insertarEmpresaUsuario($con, $arrData)
	{
		$sqlInsert = "INSERT INTO {$this->db_name}.empresa_usuario 
								(emp_id, usu_id,estado_logico,usuario_creacion, fecha_creacion) 
								VALUES (?,?,?,?, CURRENT_TIMESTAMP)";
		return $this->insertConTrasn($con, $sqlInsert, $arrData);

	}

	public function insertarEmpresaUsuarioRol($con, $arrData)
	{
		$sqlInsert = "INSERT INTO {$this->db_name}.empresa_usuario_rol 
								(eusu_id, erol_id,estado_logico,usuario_creacion, fecha_creacion) 
								VALUES (?,?,?,?, CURRENT_TIMESTAMP)";
		return $this->insertConTrasn($con, $sqlInsert, $arrData);

	}

	public function retornarModuloRolEmpresa(int $Emp_Id, int $Erol_id)
	{
		try {
			$sql = " SELECT b.emod_id,b.mod_id 
						FROM {$this->db_name}.empresa_modulo_rol a 
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

		return $this->updateConTrasn($con, $sql, $arrData);
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

		return $this->updateConTrasn($con, $sql, $arrData);
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


}
