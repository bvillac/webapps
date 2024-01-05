<?php
class AcademicoModel extends MysqlAcademico
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
        $sql = "SELECT a.ben_id BenId,CONCAT(c.per_nombre,' ',c.per_apellido) Nombres,d.con_numero Contrato,date(d.con_fecha_inicio) FechaIngreso, ";
                $sql .= " if(b.ben_tipo=1, 'TITULAR', 'BENEFICIARIO') Tipo,a.cac_estado_logico Estado ";
                $sql .= "    FROM " . $this->db_name . ".control_academico a ";
                $sql .= "     INNER JOIN (" . $this->db_name . ".beneficiario b ";
                $sql .= "         INNER JOIN " . $this->db_nameAdmin . ".persona c on b.per_id=c.per_id ";
                $sql .= "         INNER JOIN " . $this->db_name . ".contrato d on b.con_id=d.con_id) ";
                $sql .= "      ON a.ben_id=b.ben_id ";
                $sql .= "   WHERE a.cac_estado_logico!=0 ";
                $sql .= "      GROUP BY a.ben_id ";
                $sql .= "     ORDER BY Nombres ASC ";
        $request = $this->select_all($sql);
        return $request;
    }

    
    public function consultarDatosId(int $Ids)
    {
        $sql = "SELECT a.ben_id Ids,b.per_cedula DNI,CONCAT(b.per_nombre,' ',b.per_apellido) Nombres, ";
        $sql .= "    b.per_fecha_nacimiento FechaNac,b.per_telefono Telefono,b.per_direccion Direccion, ";
        $sql .= "    c.con_numero Contrato,date(c.con_fecha_inicio) FechaIngreso,if(b.per_genero='M', 'MASCULINO', 'FEMENINO') Genero, ";
        $sql .= "    if(a.ben_tipo=1, 'TITULAR', 'BENEFICIARIO') Tipo ";
        $sql .= "  FROM " . $this->db_name . ".beneficiario a ";
        $sql .= "    INNER JOIN " . $this->db_nameAdmin . ".persona b on b.per_id=a.per_id ";
        $sql .= "    INNER JOIN " . $this->db_name . ".contrato c on a.con_id=c.con_id ";
        $sql .= "  WHERE a.ben_estado_logico!=0 and a.ben_id={$Ids} ";
        $request = $this->select($sql);
        return $request;
    }


    public function consultarBenefId(int $Ids)
    {
        $sql = "select a.cac_id Ids,a.ben_id BenId,b.niv_nombre Nivel,a.cac_unidad Unidad,c.act_nombre Actividad,a.cac_hora Hora, ";
        $sql .= "   CONCAT(p.per_nombre,' ',p.per_apellido) Instructor,date(a.cac_fecha_creacion) FechaAsistencia,date(a.cac_fecha_evaluacion) FechaEvaluacion, ";
        $sql .= "   d.val_nombre Valoracion,a.cac_valoracion Valor ";
        $sql .= " from db_academico.control_academico a ";
        $sql .= "    inner join " . $this->db_name . ".nivel b on a.niv_id=b.niv_id ";
        $sql .= "    inner join " . $this->db_name . ".actividad c on a.act_id=c.act_id ";
        $sql .= "    inner join (" . $this->db_name . ".instructor i  ";
        $sql .= "         inner join " . $this->db_nameAdmin . ".persona p on p.per_id=i.per_id) on i.ins_id=a.ins_id ";
        $sql .= "    left join " . $this->db_name . ".valoracion d on d.val_id=a.val_id ";
        $sql .= " where a.cac_estado_logico!=0 and a.ben_id={$Ids} ";
        $request = $this->select_all($sql);
        return $request;
    }


    public function updateDataEvaluacion($dataObj)
    {
        try {
            $Ids = $dataObj['ids'];
            $arrData = array(
                $dataObj['val_id'],
                $dataObj['val_por'],
                $dataObj['comentario'],
                retornaUser(), 1
            );
            $sql = "UPDATE " . $this->db_name . ".control_academico 
						SET val_id = ?, cac_valoracion = ?,cac_observacion = ?,cac_usuario_modificacion = ?,
                            cac_estado_logico = ?,cac_fecha_modificacion = CURRENT_TIMESTAMP(),cac_fecha_evaluacion = CURRENT_TIMESTAMP() 
                            WHERE cac_id={$Ids}  ";
            $request = $this->update($sql, $arrData);
            $arroout["status"]=($request)?true:false;
            $arroout["numero"] = 0;
            return $arroout;
        } catch (Exception $e) {
            //throw $e;
            $arroout["status"] = false;
            $arroout["message"] = "Fallo: " . $e->getMessage();
            return $arroout;
        }
    }

}
