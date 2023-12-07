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
        $sql = "SELECT b.ben_id BenId,CONCAT(c.per_nombre,' ',c.per_apellido) Nombres,d.con_numero Contrato,date(d.con_fecha_inicio) FechaIngreso, ";
                $sql .= " if(b.ben_tipo=1, 'TITULAR', 'BENEFICIARIO') Tipo,a.cac_estado_logico Estado ";
                $sql .= "    FROM " . $this->db_name . ".control_academico a ";
                $sql .= "     INNER JOIN (" . $this->db_name . ".beneficiario b ";
                $sql .= "         INNER JOIN " . $this->db_nameAdmin . ".persona c on b.per_id=c.per_id ";
                $sql .= "         INNER JOIN " . $this->db_name . ".contrato d on b.con_id=d.con_id) ";
                $sql .= "      ON a.ben_id=b.ben_id ";
                $sql .= "   WHERE a.cac_estado_logico!=0 ";
                $sql .= "      GROUP BY a.ben_id ";
                $sql .= "     ORDER BY Nombres ASC ";
            //putMessageLogFile($sql);
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

    public function insertData($dataObj)
    {
        $con = $this->getConexion();
        $nombreSalon = $dataObj['nombre'];
        $sql = "SELECT * FROM " . $this->db_name . ".salon where sal_nombre='{$dataObj['nombre']}' and cat_id='{$dataObj['CentroAtencionID']}'";

        $request = $this->select($sql);
        if (empty($request)) {
            $con->beginTransaction();
            try {
                $arrData = array(
                    $dataObj['CentroAtencionID'],
                    $dataObj['nombre'],
                    $dataObj['cupominimo'],
                    $dataObj['cupomaximo'],
                    $dataObj['color'],
                    retornaUser(), 1
                );
                //putMessageLogFile($arrData);
                $SqlQuery  = "INSERT INTO " . $this->db_name . ".salon 
				    (`cat_id`,
                    `sal_nombre`,
                    `sal_cupo_minimo`,
                    `sal_cupo_maximo`,
                    `sal_color`,
                    `sal_usuario_creacion`,                   
                    `sal_estado_logico`) VALUES(?,?,?,?,?,?,?) ";
                $Ids = $this->insertConTrasn($con, $SqlQuery, $arrData);
                $con->commit();
                $arroout["status"] = true;
                $arroout["numero"] = 0;
                return $arroout;
            } catch (Exception $e) {
                $con->rollBack();
                //echo "Fallo: " . $e->getMessage();
                throw $e;
                $arroout["message"] = $e->getMessage();
                $arroout["status"] = false;
                return $arroout;
            }
        } else {
            $arroout["status"] = false;
            $arroout["message"] = "Ya exite el Aula con este Nombre.";
            return $arroout;
        }
    }




    public function updateData($dataObj)
    {
        try {

            $Ids = $dataObj['ids'];
            $arrData = array(
                $dataObj['CentroAtencionID'],
                $dataObj['nombre'],
                $dataObj['cupominimo'],
                $dataObj['cupomaximo'],
                $dataObj['color'],
                retornaUser(), 1
            );
            $sql = "UPDATE " . $this->db_name . ".salon 
						SET cat_id = ?, sal_nombre = ?,sal_cupo_minimo = ?,sal_cupo_maximo = ?,sal_color = ?,sal_usuario_modificacion = ?,
                            sal_estado_logico = ?,sal_fecha_modificacion = CURRENT_TIMESTAMP() WHERE sal_id={$Ids}  ";
            $request = $this->update($sql, $arrData);
            $arroout["status"]=($request)?true:false;
            $arroout["numero"] = 0;
            return $arroout;
        } catch (Exception $e) {
            throw $e;
            $arroout["status"] = false;
            $arroout["message"] = "Fallo: " . $e->getMessage();
            return $arroout;
        }
    }

    public function deleteRegistro(int $Ids)
    {
        $usuario = retornaUser();
        $sql = "UPDATE " . $this->db_name . ".salon SET sal_estado_logico = ?,sal_usuario_modificacion='{$usuario}',
                        sal_fecha_modificacion = CURRENT_TIMESTAMP() WHERE sal_id = {$Ids} ";
        $arrData = array(0);
        $request = $this->update($sql, $arrData);
        return $request;
    }

    public function consultarSalones(int $idsCentro){
        $sql = "SELECT sal_id Ids, sal_nombre Nombre,sal_color Color,sal_cupo_maximo CupoMax ";
        $sql .= " FROM ". $this->db_name .".salon WHERE sal_estado_logico!=0 and cat_id='{$idsCentro}' ORDER BY sal_nombre ASC";
        $request = $this->select_all($sql);
        return $request;
    }

}
