<?php
class BeneficiarioModel extends MysqlAcademico
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
        $sql = "SELECT a.ben_id Ids,b.con_numero NumeroContrato,a.ben_tipo TipoBenefiario,CONCAT(c.per_nombre,' ',c.per_apellido) Nombres,c.per_telefono Telefono, ";
        $sql .= "   c.per_direccion Direccion,a.ben_estado_logico Estado  ";
        $sql .= "   FROM " . $this->db_name . ".beneficiario a  ";
        $sql .= "       inner join " . $this->db_name . ".contrato b  ";
        $sql .= "           on a.con_id=b.con_id and b.con_estado_logico=1  ";
        $sql .= "       inner join " . $this->db_nameAdmin . ".persona c  ";
        $sql .= "           on a.per_id=c.per_id ";
        $sql .= "   where a.ben_estado_logico!=0 ";

        $request = $this->select_all($sql);
        return $request;
    }

    public function consultarBeneficiario(int $Ids)
    {
        $sql = "SELECT a.ben_tipo,b.per_cedula Dni,CONCAT(b.per_nombre,' ',b.per_apellido) Nombres,b.per_telefono TelCelular, ";
        $sql .= "  c.apr_numero_meses NMeses,c.apr_numero_horas NHoras,c.apr_examen_internacional Examen, ";
        $sql .= "  FLOOR(DATEDIFF(CURDATE(),b.per_fecha_nacimiento) / 365.25) Edad ,  ";
        $sql .= "  c.idi_id IdiomaId,c.paq_id PaqueteId,c.mas_id ModalidadId,c.cat_id CentroId,    ";
        $sql .= "  (SELECT paq_nombre FROM " . $this->db_name . ".paquete where paq_id=c.paq_id) Paquete, ";
        $sql .= "  (SELECT idi_nombre FROM " . $this->db_name . ".idioma where idi_id=c.idi_id) Idioma, ";
        $sql .= "  (SELECT mas_nombre FROM " . $this->db_name . ".modalidad_asistencia where mas_id=c.mas_id) Modalidad, ";
        $sql .= "  (SELECT cat_nombre FROM " . $this->db_name . ".centro_atencion where cat_id=c.cat_id) CentroAtencion ";
        $sql .= "  FROM " . $this->db_name . ".beneficiario a ";
        $sql .= "    INNER JOIN " . $this->db_nameAdmin . ".persona b ";
        $sql .= "	    ON a.per_id=b.per_id ";
        $sql .= "    INNER JOIN " . $this->db_name . ".aprendisaje c ";
        $sql .= "	    ON c.ben_id=a.ben_id ";
        $sql .= "  WHERE a.ben_estado_logico!=0 AND a.ben_id={$Ids} ";
        $request = $this->select($sql);
        return $request;
    }

    public function updateData($dataObj)
	{
		$Ids = $dataObj['ids'];
		$arroout["status"] = false;
		$arrData = array(
			$dataObj['horas_asignadas'],
			$dataObj['horas_extras'],
			0,
			$dataObj['semana_horas'],
			retornaUser()
		);
		$sql = "UPDATE " . $this->db_name . ".instructor
						SET					
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
		return $arroout;
	}

    public function deleteRegistro(int $Ids)
    {
        $usuario = retornaUser();
        $sql = "UPDATE " . $this->db_name . ".beneficiario SET ben_estado_logico = ?,ben_usuario_modificacion='{$usuario}',
                        ben_fecha_modificacion = CURRENT_TIMESTAMP() WHERE ben_id = {$Ids} ";
        $arrData = array(0);
        $request = $this->update($sql, $arrData);
        return $request;
    }
}
