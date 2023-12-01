<?php
class SalonModel extends MysqlAcademico
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
        $sql = "SELECT a.sal_id Ids,a.cat_id,b.cat_nombre NombreCentro,a.sal_nombre NombreSalon,a.sal_color Color, ";
        $sql .= "	a.sal_cupo_minimo CupoMinimo,a.sal_cupo_maximo CupoMaximo,a.sal_estado_logico Estado ";
        $sql .= "	FROM " . $this->db_name . ".salon a ";
        $sql .= "		inner join " . $this->db_name . ".centro_atencion b ";
        $sql .= "			on a.cat_id=b.cat_id ";
        $sql .= " where a.sal_estado_logico!=0 ";

        $request = $this->select_all($sql);
        return $request;
    }

    
    public function consultarDatosId(int $Ids)
    {
        $sql = "SELECT a.sal_id Ids,a.cat_id,b.cat_nombre NombreCentro,a.sal_nombre NombreSalon,a.sal_color Color, ";
        $sql .= "	a.sal_cupo_minimo CupoMinimo,a.sal_cupo_maximo CupoMaximo,a.sal_estado_logico Estado,date(sal_fecha_creacion) FechaIngreso ";
        $sql .= "	FROM " . $this->db_name . ".salon a ";
        $sql .= "		inner join " . $this->db_name . ".centro_atencion b ";
        $sql .= "			on a.cat_id=b.cat_id ";
        $sql .= " where a.sal_estado_logico!=0 AND a.sal_id={$Ids}";
        $request = $this->select($sql);
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
