<?php
require_once("Models/UsuarioModel.php");
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
				$arrDataUsu = array(
					$PerIds,
					$dataObj['email'],
					$dataObj['password'],
					$dataObj['alias'],
					$idsUsuCre
				);
				$UsuIds = $usuarioModel->insertarUsuario($con, $arrDataUsu);
				$arrDataEmp = array($idsEmpresa, $UsuIds, 1, $idsUsuCre);
				$UsuIds = $this->insertarEmpresaUsuario($con, $arrDataEmp);
				$modulos=$this->retornarModuloRolEmpresa($idsEmpresa,$dataObj['rol']);

				$sql = "INSERT INTO {$this->db_name}.det_pedido (
                    cped_id, art_id, tie_id, dped_can_ped, dped_p_venta, dped_i_m_iva, 
                    dped_val_des, dped_por_des, dped_t_venta, dped_observa, 
                    dped_fec_cre, dped_est_log, cli_id
                ) VALUES (
                    :cped_id, :art_id, :tie_id, :dped_can_ped, :dped_p_venta, :dped_i_m_iva, 
                    :dped_val_des, :dped_por_des, :dped_t_venta, :dped_observa, 
                    CURRENT_TIMESTAMP(), 2, :cli_id
                )";
            $stmt = $con->prepare($sql);
				foreach ($modulos as $modulo) {
					$stmt->execute([
						':cped_id' => $idCab,
						':art_id' => $detalle['art_id'],
						':tie_id' => $detalle['tie_id'],
						':dped_can_ped' => $detalle['tdped_can_ped'],
						':dped_p_venta' => $detalle['tdped_p_venta'],
						':dped_i_m_iva' => 0,
						':dped_val_des' => 0,
						':dped_por_des' => 0,
						':dped_t_venta' => $detalle['tdped_t_venta'],
						':dped_observa' => $detalle['tdped_observa'] ?? '',
						':cli_id' => $cliId
					]);
				}

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






}
