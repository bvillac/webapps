<?php
class UsuariosEmpresaModel extends Mysql
{
	private $db_name;
	private $rolName;
	private $EmpIds;
	public function __construct()
	{
		parent::__construct();
		$this->db_name = $this->getDbNameMysql();
		$this->rolName=retornarDataSesion("RolNombre");
		$this->EmpIds=retornarDataSesion("Emp_Id");
	}

	
	public function consultarDatos() {
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

	
	
	
}
