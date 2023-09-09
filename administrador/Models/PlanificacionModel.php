<?php

use Matrix\Functions;

class PlanificacionModel extends MysqlAcademico
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
        $sql = "SELECT a.sal_id Ids,a.cat_id,b.cat_nombre NombreCentro,a.sal_nombre NombreSalon, ";
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
        $sql = "SELECT a.sal_id Ids,a.cat_id,b.cat_nombre NombreCentro,a.sal_nombre NombreSalon, ";
        $sql .= "	a.sal_cupo_minimo CupoMinimo,a.sal_cupo_maximo CupoMaximo,a.sal_estado_logico Estado,date(sal_fecha_creacion) FechaIngreso ";
        $sql .= "	FROM " . $this->db_name . ".salon a ";
        $sql .= "		inner join " . $this->db_name . ".centro_atencion b ";
        $sql .= "			on a.cat_id=b.cat_id ";
        $sql .= " where a.sal_estado_logico!=0 AND a.sal_id={$Ids}";
        $request = $this->select($sql);
        return $request;
    }

    public function insertData($Cabecera, $Detalle)
    {

        $con = $this->getConexion();
        $sql = "SELECT * FROM " . $this->db_name . ".planificacion_temp 
                  where tpla_estado_logico=1 and cat_id='{$Cabecera['centro']}' and tpla_fecha_incio='{$Cabecera['fechaInicio']}' ";

        $request = $this->select($sql);
        if (empty($request)) {
            $con->beginTransaction();
            try {
                $rangoDia = "";
                for ($i = 0; $i < sizeof($Detalle); $i++) {
                    switch ($Detalle[$i]['dia']) {
                        case "LU":
                            $diaLunes = $Detalle[$i]['horario'];
                            $rangoDia .= "LU:" . date("Y-m-d", strtotime($Detalle[$i]['fecha'])) . ";";
                            break;
                        case "MA":
                            $diaMartes = $Detalle[$i]['horario'];
                            $rangoDia .= "MA:" . date("Y-m-d", strtotime($Detalle[$i]['fecha'])) . ";";
                            break;
                        case "MI":
                            $diaMiercoles = $Detalle[$i]['horario'];
                            $rangoDia .= "MI:" . date("Y-m-d", strtotime($Detalle[$i]['fecha'])) . ";";
                            break;
                        case "JU":
                            $diaJueves = $Detalle[$i]['horario'];
                            $rangoDia .= "JU:" . date("Y-m-d", strtotime($Detalle[$i]['fecha'])) . ";";
                            break;
                        case "VI":
                            $diaViernes = $Detalle[$i]['horario'];
                            $rangoDia .= "VI:" . date("Y-m-d", strtotime($Detalle[$i]['fecha'])) . ";";
                            break;
                        case "SA":
                            $diaSabado = $Detalle[$i]['horario'];
                            $rangoDia .= "SA:" . date("Y-m-d", strtotime($Detalle[$i]['fecha'])) . ";";
                            break;
                        case "DO":
                            $diaDomingo = $Detalle[$i]['horario'];
                            $rangoDia .= "DO:" . date("Y-m-d", strtotime($Detalle[$i]['fecha'])) . ";";
                            break;
                    }
                }

                $arrData = array(
                    $Cabecera['centro'],
                    $Cabecera['fechaInicio'],
                    $Cabecera['fechaFin'],
                    empty(!$diaLunes) ? $diaLunes : "",
                    empty(!$diaMartes) ? $diaMartes : "",
                    empty(!$diaMiercoles) ? $diaMiercoles : "",
                    empty(!$diaJueves) ? $diaJueves : "",
                    empty(!$diaViernes) ? $diaViernes : "",
                    empty(!$diaSabado) ? $diaSabado : "",
                    empty(!$diaDomingo) ? $diaDomingo : "",
                    $rangoDia,
                    'T',
                    retornaUser(), 1
                );
                putMessageLogFile($arrData);
                $SqlQuery  = "INSERT INTO " . $this->db_name . ".planificacion_temp 
				    (`cat_id`,
                    `tpla_fecha_incio`,
                    `tpla_fecha_fin`,
                    `tpla_lunes`,
                    `tpla_martes`,
                    `tpla_miercoles`,
                    `tpla_jueves`,
                    `tpla_viernes`,
                    `tpla_sabado`,
                    `tpla_domingo`,
                    `tpla_fechas_rango`,
                    `tpla_estado`,                    
                    `tpla_usuario_creacion`,                   
                    `tpla_estado_logico`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
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
            $arroout["message"] = "Ya exite el Planificación con esta fecha.";
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
                retornaUser(), 1
            );
            $sql = "UPDATE " . $this->db_name . ".salon 
						SET cat_id = ?, sal_nombre = ?,sal_cupo_minimo = ?,sal_cupo_maximo = ?,sal_usuario_modificacion = ?,
                            sal_estado_logico = ?,sal_fecha_modificacion = CURRENT_TIMESTAMP() WHERE sal_id={$Ids}  ";
            $request = $this->update($sql, $arrData);
            $arroout["status"] = ($request) ? true : false;
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
}
