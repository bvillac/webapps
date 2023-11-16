<?php
class InstructorModel extends MysqlAcademico
{
	private $db_name;
	private $db_nameAdmin;

	public function __construct()
	{
		parent::__construct();
		$this->db_name = $this->getDbNameMysql();
		$this->db_nameAdmin = $this->getDbNameMysqlAdmin();
	}

	public function consultarDatos()
	{
		$sql = "SELECT  A.ins_id Ids,B.per_cedula Cedula, concat(B.per_nombre,' ',B.per_apellido) Nombres, A.ins_estado_logico Estado,date(A.ins_fecha_creacion) Fecha ";
		$sql .= "	FROM " . $this->db_name . ".instructor A";
		$sql .= "		INNER JOIN " . $this->db_nameAdmin . ".persona B";
		$sql .= "			ON A.per_id=B.per_id AND B.estado_logico!=0";
		$sql .= "	WHERE A.ins_estado_logico!=0 ";
		$request = $this->select_all($sql);
		return $request;
	}


	public function consultarDatosId(int $Ids)
	{
		$sql = "SELECT  A.ins_id Ids,A.per_id PerIds,A.cat_id CentroId,B.per_cedula Cedula, concat(B.per_nombre,' ',B.per_apellido) Nombres, A.ins_estado_logico Estado,date(A.ins_fecha_creacion) FechaIng, ";
		$sql .= "	A.ins_semana_horas Horas,A.ins_horas_asignadas HoraNormal,A.ins_horas_extras HoraExtra,ins_salones Salones";
		$sql .= "	FROM " . $this->db_name . ".instructor A";
		$sql .= "		INNER JOIN " . $this->db_nameAdmin . ".persona B";
		$sql .= "			ON A.per_id=B.per_id AND B.estado_logico!=0";
		$sql .= "	WHERE A.ins_estado_logico!=0 AND A.ins_id={$Ids} ";
		$request = $this->select($sql);
		return $request;
	}

	public function insertData($dataObj)
	{
		$idsUsuario = retornaUser();
		$strPerID = $dataObj['per_id'];
		$con = $this->getConexion();
		$sql = "SELECT * FROM " . $this->db_name . ".instructor where per_id={$strPerID}";
		$request = $this->select_all($sql);
		if (empty($request)) {
			$con->beginTransaction();
			try {
				//Inserta Cabecera add_ceros();				
				$arrData = array(
					$dataObj['per_id'],
					$dataObj['cat_id'],
					$dataObj['salones'],
					$dataObj['horas_asignadas'],
					$dataObj['horas_extras'],
					0,
					$dataObj['semana_horas'],
					$idsUsuario, 1
				);
				$SqlQuery  = "INSERT INTO " . $this->db_name . ".instructor 
				(`per_id`,`cat_id`,
				`ins_salones`,
				`ins_horas_asignadas`,
				`ins_horas_extras`,
				`ins_semana_dias`,
				`ins_semana_horas`,
				`ins_usuario_creacion`,
			`ins_estado_logico`) VALUES(?,?,?,?,?,?,?,?,?) ";
				$Ids = $this->insertConTrasn($con, $SqlQuery, $arrData);
				$con->commit();
				$arroout["status"] = true;
				$arroout["numero"] = $Ids;
				return $arroout;
			} catch (Exception $e) {
				$con->rollBack();
				//echo "Fallo: " . $e->getMessage();
				//throw $e;
				$arroout["status"] = false;
				return $arroout;
			}
		} else {
			$arroout["status"] = false;
			return $arroout;
		}
	}




	public function updateData($dataObj)
	{
		$Ids = $dataObj['ids'];
		$arroout["status"] = false;
		$arrData = array(
			$dataObj['cat_id'],
			$dataObj['salones'],
			$dataObj['horas_asignadas'],
			$dataObj['horas_extras'],
			0,
			$dataObj['semana_horas'],
			retornaUser()
		);
		$sql = "UPDATE " . $this->db_name . ".instructor
						SET	
						`cat_id`= ?,
						`ins_salones`= ?,				
						`ins_horas_asignadas` = ?,
						`ins_horas_extras` = ?,
						`ins_semana_dias` = ?,
						`ins_semana_horas` = ?,
						`ins_usuario_modificacion` = ?,
						`ins_fecha_modificacion` = CURRENT_TIMESTAMP()
						WHERE `ins_id` = {$Ids}";

		$request = $this->update($sql, $arrData);
		if ($request) {
			$arroout["status"] = true;
		}
		$arroout["numero"] = 0;
		return $arroout;
	}

	public function deleteRegistro(int $Ids)
	{
		$usuario=retornaUser();
		$sql = "UPDATE " . $this->db_name . ".instructor SET ins_estado_logico = ?,ins_usuario_modificacion='{$usuario}',ins_fecha_modificacion = CURRENT_TIMESTAMP() WHERE ins_id = {$Ids} ";
		$arrData = array(0);
		$request = $this->update($sql, $arrData);
		return $request;
	}

	public function consultarCentroInstructores(int $catIds){
		$sql = "SELECT  A.ins_id Ids,B.per_cedula Cedula, concat(B.per_nombre,' ',B.per_apellido) Nombre, ins_semana_horas Horario,ins_salones Salones ";
		$sql .= "	FROM " . $this->db_name . ".instructor A";
		$sql .= "		INNER JOIN " . $this->db_nameAdmin . ".persona B";
		$sql .= "			ON A.per_id=B.per_id AND B.estado_logico!=0";
		$sql .= "	WHERE A.ins_estado_logico!=0 AND A.cat_id={$catIds} ORDER BY Nombre DESC ";
		$request = $this->select_all($sql);
		return $request;
        
    }
}
