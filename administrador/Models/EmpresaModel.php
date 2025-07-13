<?php
class EmpresaModel extends Mysql
{
	private $db_name;

	public function __construct()
	{
		parent::__construct();
		$this->db_name = $this->getDbNameMysql();
	}

	public function consultarDatos()
	{
		$sql = "SELECT a.emp_id Ids,CONCAT(b.mon_simbolo,'-',b.mon_nombre) Moneda,a.emp_ruc Ruc, ";
		$sql .= "   a.emp_razon_social Razon,a.emp_nombre_comercial	Nombre,a.emp_direccion Direccion,a.emp_correo Correo,a.emp_ruta_logo Logo,a.estado_logico Estado ";
		$sql .= "   FROM " . $this->db_name . ".empresa a  ";
		$sql .= "      INNER JOIN " . $this->db_name . ".moneda b  ";
		$sql .= "      ON a.mon_id=b.mon_id AND b.estado_logico!=0  ";
		$sql .= "WHERE a.estado_logico!=0  ";
		$request = $this->select_all($sql);

		return $request;
	}
	public function consultarMoneda()
	{
		$sql = "SELECT mon_id Ids, CONCAT(mon_simbolo,'-',mon_nombre) Nombre ";
		$sql .= " FROM " . $this->db_name . ".moneda WHERE estado_logico!=0 ORDER BY mon_nombre ASC";
		$request = $this->select_all($sql);
		return $request;
	}


	public function consultarDatosId(int $Ids)
	{
		$sql = "SELECT a.emp_id Ids,b.mon_id,b.mon_nombre Moneda,a.emp_ruc Ruc,a.mon_id IdMoneda, ";
		$sql .= "   a.emp_razon_social Razon,a.emp_nombre_comercial	Nombre,a.emp_direccion Direccion,a.emp_correo Correo,a.emp_ruta_logo Logo,a.estado_logico Estado,date(a.fecha_creacion) FechaIng";
		$sql .= "   FROM " . $this->db_name . ".empresa a  ";
		$sql .= "      INNER JOIN " . $this->db_name . ".moneda b  ";
		$sql .= "      ON a.mon_id=b.mon_id AND b.estado_logico!=0  ";
		$sql .= "WHERE a.estado_logico!=0 AND a.emp_id={$Ids} ";
		$request = $this->select($sql);
		return $request;
	}


	public function insertData(int $Ids, string $ruc, string $razon, string $nombre, string $direccion, string $correo, string $logo, int $moneda, int $estado)
	{
		//$return = "";

		//$query_insert  = "INSERT INTO ". $this->db_name .".empresa (emp_ruc,emp_razon_social,emp_nombre_comercial,emp_direccion,emp_correo,emp_ruta_logo,mon_id,estado_logico) VALUES(?,?,?,?,?,?,?,?)  ";
		$db_name = $this->getDbNameMysql();
		$return = "";
		$sql = "SELECT * FROM " . $db_name . ".empresa WHERE emp_razon_social = '{$razon}'   ";
		$request = $this->select_all($sql);
		if (empty($request)) {
			$con = $this->getConexion();
			$con->beginTransaction();
			try {

				$arrData = array($ruc, $razon, $nombre, $direccion, $correo, $logo, $moneda, $estado);
				$request_insert = $this->insertarEmpresa($con, $db_name, $arrData);
				$return = $request_insert;//Retorna el Ultimo IDS(0) No inserta y si es >0 si inserto
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

	private function insertarEmpresa($con, $db_name, $arrData)
	{

		$SqlQuery = "INSERT INTO " . $db_name . ".empresa (emp_ruc,emp_razon_social,emp_nombre_comercial,emp_direccion,emp_correo,emp_ruta_logo,mon_id,estado_logico) VALUES(?,?,?,?,?,?,?,?)  ";
		$insert = $con->prepare($SqlQuery);
		$resInsert = $insert->execute($arrData);
		if ($resInsert) {
			$lastInsert = $con->lastInsertId();
		} else {
			$lastInsert = 0;
		}
		return $lastInsert;
	}




	public function updateData(int $Ids, string $ruc, string $razon, string $nombre, string $direccion, string $correo, string $logo, int $moneda, int $estado)
	{
		$sql = "UPDATE " . $this->db_name . ".empresa 
							SET emp_ruc = ?,emp_razon_social= ?,emp_nombre_comercial = ?,emp_direccion = ?,emp_correo = ?,emp_ruta_logo = ?,mon_id = ?, estado_logico = ?,fecha_modificacion = CURRENT_TIMESTAMP() WHERE emp_id = {$Ids} ";
		$arrData = array($ruc, $razon, $nombre, $direccion, $correo, $logo, $moneda, $estado);
		$request = $this->update($sql, $arrData);
		return $request;
	}


	public function deleteRegistro(int $Ids)
	{
		$sql = "UPDATE " . $this->db_name . ".empresa SET estado_logico = ?,usuario_modificacion=1,fecha_modificacion = CURRENT_TIMESTAMP() WHERE emp_id = {$Ids} ";
		$arrData = array(0);
		$request = $this->update($sql, $arrData);
		return $request;
	}

	public function consultarEmpresaId()
	{
		$IdsEmp = $_SESSION['Emp_Id'];
		$sql = "SELECT a.emp_id Ids,a.emp_ruc Ruc,a.mon_id IdMoneda, ";
		$sql .= "   a.emp_razon_social Razon,a.emp_nombre_comercial	Nombre,a.emp_direccion Direccion, ";
		$sql .= "	a.emp_correo Correo,a.emp_ruta_logo Logo";
		$sql .= "   FROM " . $this->db_name . ".empresa a  ";
		$sql .= "WHERE a.estado_logico=1 AND a.emp_id={$IdsEmp} ";
		$request = $this->select($sql);
		return $request;
	}


	//Nuevo 23-04-2023
	public function consultarEmpresaPermiso(int $Ids)
	{
		$sql = "SELECT distinct(a.emp_id) Ids,b.emp_razon_social ";
		$sql = "	FROM " . $this->db_name . ".permiso a ";
		$sql = "		INNER JOIN " . $this->db_name . ".empresa b ";
		$sql = "			ON a.emp_id=b.emp_id ";
		$sql = "	WHERE a.estado_logico!=0 AND a.usu_id={$Ids} ";
		$request = $this->select($sql);
		return $request;
	}

	public function consultarEmpresaEstPunto(int $Ids)
	{
		$sql = "SELECT a.emp_id EmpIds,a.emp_ruc Ruc,a.emp_nombre_comercial NombreComercial,b.est_id EstableId,
						c.pemi_id PuntoEmisId,a.emp_ruta_logo Logo,a.emp_correo Correo,a.emp_direccion Direccion 
						FROM " . $this->db_name . ".empresa a 
							inner join (" . $this->db_name . ".establecimiento b 
									inner join " . $this->db_name . ".punto_emision c 
										on b.est_id=c.est_id)
								on a.emp_id=b.emp_id
						where a.estado_logico!=0 and a.emp_id={$Ids} ";
		$request = $this->select($sql);
		return $request;
	}
	/*######################################
				   NUEVAS FUNCIONES  21-04-2024 
				   ######################################*/

	public function consultarEmpresaUsuario(int $Usu_id)
	{
		$sql = "SELECT a.eusu_id Ids,b.emp_nombre_comercial NombreComercial ";
		$sql .= "	FROM {$this->db_name}.empresa_usuario a ";
		$sql .= "		INNER JOIN {$this->db_name}.empresa b ";
		$sql .= "			ON a.emp_id=b.emp_id ";
		$sql .= "	WHERE a.estado_logico=1 AND a.usu_id=:usu_id ";
		$request = $this->select_all($sql, [":usu_id" => $Usu_id]);
		return $request;
	}

	public function getIdEmpresaUsuario(int $Eusu_id)
	{
		$sql = "SELECT emp_id FROM {$this->db_name}.empresa_usuario WHERE eusu_id= :eusu_id ";
		$request = $this->select($sql, [":eusu_id" => $Eusu_id]);
		//Si existen datos retonar el valor caso contario retorna 0
		return (!empty($request)) ? $request['emp_id'] : 0;
		//return 0;
	}

	/*public function insertDataEmpModulo(string $data, string $Emp_id)
		  {
			  try {
				  $con = $this->getConexion();
				  $con->beginTransaction();
				  $arrData = array(0);
				  $sql = "UPDATE " . $this->db_name . ".empresa_modulo SET estado_logico=? WHERE emp_id={$Emp_id}";
				  $request = $this->updateConTrans($con, $sql, $arrData);
				  if ($request) {//Si todo es correcto retorna True
					  $arrayIds = explode(",", $data);
					  $usuario = retornaUser();
					  //01,02,0203,0204,0205,0206,0207,03,0301
					  $arrData = array(1);
					  //Actualiza todos los Ids
					  $sql = "UPDATE " . $this->db_name . ".empresa_modulo SET estado_logico=? 
						  WHERE emp_id={$Emp_id} AND mod_id IN({$data})";
					  $request = $this->updateConTrans($con, $sql, $arrData);
					  if ($request) {
						  foreach ($arrayIds as $Mod_id) {
							  $sql = "SELECT * FROM " . $this->db_name . ".empresa_modulo WHERE emp_id={$Emp_id} AND mod_id='{$Mod_id}'";
							  $requestSel = $this->select($sql);//usuario_modificacion
							  if (empty($requestSel)) {
								  //Inserta un nuevo modulo
								  $arrData = array($Emp_id, $Mod_id, 1, $usuario);
								  $SqlQuery = "INSERT INTO " . $this->db_name . ".empresa_modulo
											  (`emp_id`,`mod_id`,`estado_logico`,`usuario_creacion`) VALUES (?,?,?,?) ";
								  $request_insert = $this->insertConTrans($con, $SqlQuery, $arrData);
								  if ($request_insert == 0) {//si es igual 0 no inserto nada
									  $con->rollBack();
									  $arroout["status"] = false;
									  $arroout["message"] = "Error al insertar Empresa Modulo!.";
								  }
								  //$return = $request_insert;//Retorna el Ultimo IDS(0) No inserta y si es >0 si inserto

							  }
						  }
					  } else {
						  $con->rollBack();
						  $arroout["status"] = false;
						  $arroout["message"] = "Error al Actualizar Empresa Modulo!.";
					  }
					  $con->commit();
					  $arroout["status"] = true;
				  } else {
					  $con->rollBack();
					  $arroout["status"] = false;
					  $arroout["message"] = "Error al Eliminar los modulos!.";
				  }
				  return $arroout;
			  } catch (Exception $e) {
				  $con->rollBack();
				  //throw $e;
				  $arroout["status"] = false;
				  $arroout["message"] = "Fallo: " . $e->getMessage();
				  return $arroout;
			  }
		  }*/

	public function insertDataEmpModulo(string $data, string $Emp_id): array
	{
		$arroout = ["status" => false, "message" => "Operación fallida."];

		try {
			$con = $this->getConexion();
			$con->beginTransaction();

			$usuario = retornaUser();
			$arrayIds = array_filter(explode(",", $data)); // Limpia valores vacíos

			// Desactiva todos los módulos actuales
			$sql = "UPDATE {$this->db_name}.empresa_modulo SET estado_logico = 0 WHERE emp_id = ?";
			if (!$this->updateConTrans($con, $sql, [$Emp_id])) {
				throw new Exception("Error al desactivar los módulos actuales.");
			}

			if (!empty($arrayIds)) {
				// Activa los módulos seleccionados
				$placeholders = implode(',', array_fill(0, count($arrayIds), '?'));
				$sql = "UPDATE {$this->db_name}.empresa_modulo SET estado_logico = 1 
                    WHERE emp_id = ? AND mod_id IN ($placeholders)";
				$params = array_merge([$Emp_id], $arrayIds);
				if (!$this->updateConTrans($con, $sql, $params)) {
					throw new Exception("Error al activar los módulos seleccionados.");
				}

				// Inserta módulos nuevos que no existan aún
				foreach ($arrayIds as $mod_id) {
					$sqlCheck = "SELECT 1 FROM {$this->db_name}.empresa_modulo 
                             WHERE emp_id = :emp_id AND mod_id = :mod_id ";
					$exists = $this->select($sqlCheck, [":emp_id" => $Emp_id, ":mod_id" => $mod_id]);

					if (empty($exists)) {
						$sqlInsert = "INSERT INTO {$this->db_name}.empresa_modulo
                                  (emp_id, mod_id, estado_logico, usuario_creacion) 
                                  VALUES (?, ?, ?, ?)";
						$insertSuccess = $this->insertConTrans($con, $sqlInsert, [$Emp_id, $mod_id, 1, $usuario]);
						if ($insertSuccess === 0) {
							throw new Exception("Error al insertar el módulo $mod_id.");
						}
					}
				}
			}

			$con->commit();
			$arroout["status"] = true;
			$arroout["message"] = "Módulos actualizados correctamente.";
		} catch (Exception $e) {
			$con->rollBack();
			$arroout["message"] = "Fallo: " . $e->getMessage();
		}

		return $arroout;
	}



	public function consultarEmpresas()
	{
		try {
			$sql = "SELECT emp_id as Ids,emp_nombre_comercial as Nombre
  						FROM {$this->db_name}.empresa where estado_logico!=0;";
			$resultado = $this->select_all($sql);
			if ($resultado === false) {
				//logFileSystem("Consulta fallida para tie_id: $tie_id", "WARNING");
				return []; // Retornar un array vacío en lugar de false para evitar errores en la vista
			}
			return $resultado;
		} catch (Exception $e) {
			logFileSystem("Error en consultarEmpresas: " . $e->getMessage(), "ERROR");
			return []; // En caso de error, retornar un array vacío
		}

	}




	public function insertDataUsuarioEmpresa(array $dataObj)
	{
		$con = $this->getConexion(); // Obtiene la conexión a la base de datos
		$arroout = ["status" => false, "message" => "No se realizó ninguna operación."];
		try {
			$con->beginTransaction(); // Inicia una transacción
			$idsSeleccionado = explode(',', $dataObj['valores']);
			foreach ($idsSeleccionado as $idsEmp) {
				// Verificar si el producto ya existe para el cliente
				$sqlCheck = "SELECT eusu_id FROM {$this->db_name}.empresa_usuario WHERE emp_id = :emp_id AND usu_id = :usu_id";
				$stmtCheck = $con->prepare($sqlCheck);
				$stmtCheck->execute([
					':emp_id' => $idsEmp,
					':usu_id' => $dataObj['usuIds']
				]);
				$result = $stmtCheck->fetch(PDO::FETCH_ASSOC);
				if ($result) {
					// Actualizar si ya existe
					$sqlUpdate = "UPDATE {$this->db_name}.empresa_usuario 
								SET estado_logico = 1, 
									fecha_modificacion = CURRENT_TIMESTAMP 
								WHERE eusu_id = :eusu_id";
					$stmtUpdate = $con->prepare($sqlUpdate);
					$stmtUpdate->execute([
						':eusu_id' => $result['eusu_id']
					]);
				} else {
					// Insertar si no existe
					$sqlInsert = "INSERT INTO {$this->db_name}.empresa_usuario 
								(emp_id, usu_id,  estado_logico, fecha_creacion) 
								VALUES (:emp_id, :usu_id,  1, CURRENT_TIMESTAMP)";
					$stmtInsert = $con->prepare($sqlInsert);
					$stmtInsert->execute([
						':usu_id' => $dataObj['usuIds'],
						':emp_id' => $idsEmp
					]);
				}
			}
			$this->actualizaEmpresaUsuario($con, $dataObj['usuIds'], $dataObj['valores']);
			$con->commit(); // Confirma la transacción
			return ["status" => true, "numero" => 0, "message" => "Registros guardados correctamente."];

		} catch (Exception $e) {
			$con->rollBack(); // Revierte la transacción en caso de error
			logFileSystem("Error en guardarProductosCliente: " . $e->getMessage(), "ERROR");
			return ["status" => false, "message" => "Error en la operación: " . $e->getMessage()];
		}
	}

	private function actualizaEmpresaUsuario($con, $UsuId, $valores)
	{

		$sqlUpdate = "UPDATE {$this->db_name}.empresa_usuario SET estado_logico=0 WHERE usu_id=:usu_id AND emp_id NOT IN($valores)";
		$stmtUpdate = $con->prepare($sqlUpdate);
		$stmtUpdate->execute([
			':usu_id' => $UsuId
		]);
	}


	public function consultarEmpresaUsuarioAsingado(int $ids)
	{
		try {
			$sql = "SELECT emp_id FROM {$this->db_name}.empresa_usuario where usu_id=:ids;";
			$resultado = $this->select_all($sql, [":ids" => $ids]);
			if ($resultado === false) {
				logFileSystem("Consulta fallida para usu_id: $ids", "WARNING");
				return []; // Retornar un array vacío en lugar de false para evitar errores en la vista
			}
			return array_map(fn($item) => strval($item["emp_id"]), $resultado);
		} catch (Exception $e) {
			logFileSystem("Error en consultarEmpresaUsuarioAsingado: " . $e->getMessage(), "ERROR");
			return []; // En caso de error, retornar un array vacío
		}
	}

	public function getIdEmpresaUsuarioXEmpresa(int $Emp_id, int $Usu_id)
	{
		$sql = "SELECT eusu_id FROM {$this->db_name}.empresa_usuario WHERE emp_id=:emp_id and usu_id= :usu_id ";
		$request = $this->select($sql, [":emp_id" => $Emp_id, ":usu_id" => $Usu_id]);
		//empty($request) => una cadena vacía, valor nulo,valor entero 0,array vacío y variable no definida
		//Si existen datos retonar el valor caso contario retorna 0
		return (!empty($request)) ? $request['eusu_id'] : 0;
	}

	public function getIdEmpresaRol(int $Emp_id, int $Rol_id)
	{
		$sql = "SELECT erol_id FROM {$this->db_name}.empresa_rol WHERE emp_id=:emp_id and rol_id= :rol_id ";
		$request = $this->select($sql, [":emp_id" => $Emp_id, ":rol_id" => $Rol_id]);
		//empty($request) => una cadena vacía, valor nulo,valor entero 0,array vacío y variable no definida
		//Si existen datos retonar el valor caso contario retorna 0
		return (!empty($request)) ? $request['erol_id'] : 0;
	}

	public function getIdEmpresaUsuarioRol(int $Eusu_id, int $Erol_id)
	{
		$sql = "SELECT eurol_id FROM {$this->db_name}.empresa_usuario_rol WHERE eusu_id=:eusu_id and erol_id= :erol_id ";
		$request = $this->select($sql, [":eusu_id" => $Eusu_id, ":erol_id" => $Erol_id]);
		//empty($request) => una cadena vacía, valor nulo,valor entero 0,array vacío y variable no definida
		//Si existen datos retonar el valor caso contario retorna 0
		return (!empty($request)) ? $request['eurol_id'] : 0;
	}


	public function insertDataEmpRol(string $data, string $Emp_id): array
	{
		$arroout = ["status" => false, "message" => "Operación fallida."];

		try {
			$con = $this->getConexion();
			$con->beginTransaction();

			$usuario = retornaUser();
			$arrayIds = array_filter(explode(",", $data)); // Limpia valores vacíos

			// Desactiva todos los módulos actuales
			$sql = "UPDATE {$this->db_name}.empresa_rol  SET estado_logico = 0 WHERE emp_id = ?";
			if (!$this->updateConTrans($con, $sql, [$Emp_id])) {
				throw new Exception("Error al desactivar los módulos actuales.");
			}

			if (!empty($arrayIds)) {
				// Activa los módulos seleccionados
				$placeholders = implode(',', array_fill(0, count($arrayIds), '?'));
				$sql = "UPDATE {$this->db_name}.empresa_rol  SET estado_logico = 1 
                    WHERE emp_id = ? AND rol_id IN ($placeholders)";
				$params = array_merge([$Emp_id], $arrayIds);
				if (!$this->updateConTrans($con, $sql, $params)) {
					throw new Exception("Error al activar los Roles seleccionados.");
				}

				// Inserta módulos nuevos que no existan aún
				foreach ($arrayIds as $rol_id) {
					$sqlCheck = "SELECT 1 FROM {$this->db_name}.empresa_rol  
                             WHERE emp_id = :emp_id AND rol_id = :rol_id ";
					$exists = $this->select($sqlCheck, [":emp_id" => $Emp_id, ":rol_id" => $rol_id]);

					if (empty($exists)) {
						$sqlInsert = "INSERT INTO {$this->db_name}.empresa_rol 
                                  (emp_id, rol_id, estado_logico, usuario_creacion) 
                                  VALUES (?, ?, ?, ?)";
						$insertSuccess = $this->insertConTrans($con, $sqlInsert, [$Emp_id, $rol_id, 1, $usuario]);
						if ($insertSuccess === 0) {
							throw new Exception("Error al insertar el Roles $rol_id.");
						}
					}
				}
			}

			$con->commit();
			$arroout["status"] = true;
			$arroout["message"] = "Módulos actualizados correctamente.";
		} catch (Exception $e) {
			$con->rollBack();
			$arroout["message"] = "Fallo: " . $e->getMessage();
		}

		return $arroout;
	}


	public function insertDataEmpRolSelect(string $data, string $erol_id): array
	{
		$arroout = ["status" => false, "message" => "Operación fallida."];
		try {
			$con = $this->getConexion();
			$con->beginTransaction();

			$usuario = retornaUser();
			$arrayIds = array_filter(explode(",", $data)); // Limpia valores vacíos

			// Desactiva todos los módulos actuales
			$sql = "UPDATE {$this->db_name}.empresa_modulo_rol  SET estado_logico = 0 WHERE erol_id = :erol_id ";
			if (!$this->updateConTrans($con, $sql, [":erol_id" => $erol_id])) {
				throw new Exception("Error al desactivar los módulos y Roles actuales.");
			}

			if (!empty($arrayIds)) {
				// Activa los módulos seleccionados
				$placeholders = implode(',', array_fill(0, count($arrayIds), '?'));
				$sql = "UPDATE {$this->db_name}.empresa_modulo_rol  SET estado_logico = 1 
                    WHERE erol_id = ? AND emod_id IN ($placeholders)";
				$params = array_merge([$erol_id], $arrayIds);
				if (!$this->updateConTrans($con, $sql, $params)) {
					throw new Exception("Error al activar los Roles y Modulos seleccionados.");
				}

				// Inserta módulos nuevos que no existan aún
				foreach ($arrayIds as $emod_id) {
					$sqlCheck = "SELECT 1 FROM {$this->db_name}.empresa_modulo_rol  
                             WHERE erol_id = :erol_id AND emod_id = :emod_id ";
					$exists = $this->select($sqlCheck, [":erol_id" => $erol_id, ":emod_id" => $emod_id]);

					if (empty($exists)) {
						$sqlInsert = "INSERT INTO {$this->db_name}.empresa_modulo_rol 
                                  (emod_id, erol_id, estado_logico, usuario_creacion) 
                                  VALUES (?, ?, ?, ?)";
						$insertSuccess = $this->insertConTrans($con, $sqlInsert, [$emod_id, $erol_id, 1, $usuario]);
						if ($insertSuccess === 0) {
							throw new Exception("Error al insertar el Roles $emod_id.");
						}
					}
				}
			}

			$con->commit();
			$arroout["status"] = true;
			$arroout["message"] = "Módulos actualizados correctamente.";
		} catch (Exception $e) {
			putMessageLogFile($e);
			$con->rollBack();
			$arroout["message"] = "Fallo: " . $e->getMessage();
		}

		return $arroout;
	}


	public function setModuloIndex(int $Emod_id, int $Erol_id)
	{
		if ($Emod_id <= 0 || $Erol_id <= 0) {
			return ["status" => false, "message" => "IDs inválidos"];
		}

		try {
			$con = $this->getConexion();
			$con->beginTransaction();
			$usuarioMod = retornaUser(); // Usuario que realiza la modificación

			// Verificar si existe el módulo-rol
			$sqlCheck = "SELECT emrol_id FROM {$this->db_name}.empresa_modulo_rol 
                     WHERE estado_logico != 0 AND emod_id = :emod_id AND erol_id = :erol_id";
			$paramsCheck = [":emod_id" => $Emod_id, ":erol_id" => $Erol_id];
			$result = $this->select($sqlCheck, $paramsCheck);

			if (empty($result)) {
				return ["status" => false, "message" => "No existe el módulo para asignar como índice"];
			}

			// Desactivar el index de todos los módulos para ese rol
			$this->desactivarIndexModulo($con, $Erol_id);

			// Activar el index para el módulo seleccionado
			$Emrol_id = $result['emrol_id'];
			$sqlUpdate = "UPDATE {$this->db_name}.empresa_modulo_rol 
                      SET emrol_index = 1 
                      WHERE emrol_id = :emrol_id";
			$arrData = [":emrol_id" => $Emrol_id];
			$this->updateConTrans($con, $sqlUpdate, $arrData);

			$con->commit();

			return ["status" => true, "message" => "Módulo index establecido correctamente."];
		} catch (Exception $e) {
			$con->rollBack();
			logFileSystem("Error en setModuloIndex: " . $e->getMessage(), "ERROR");
			return ["status" => false, "message" => "Error al establecer el módulo index."];
		}
	}

	private function desactivarIndexModulo($con, int $Erol_id)
	{
		$sqlUpdate = "UPDATE {$this->db_name}.empresa_modulo_rol 
                  SET emrol_index = 0 
                  WHERE erol_id = :erol_id";
		$arrData = [":erol_id" => $Erol_id];
		return $this->updateConTrans($con, $sqlUpdate, $arrData);
	}




}
?>