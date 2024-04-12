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
        $sql = "SELECT a.tpla_id Ids,b.cat_nombre Centro, a.tpla_fecha_incio FechaIni,a.tpla_fecha_fin FechaFin,a.tpla_fechas_rango Rango,a.tpla_estado_logico Estado ";
        $sql .= "    FROM " . $this->db_name . ".planificacion_temp a ";
        $sql .= "        inner join " . $this->db_name . ".centro_atencion b  ";
        $sql .= "            on a.cat_id=b.cat_id ";
        $sql .= "    where a.tpla_estado_logico!=0; ";
        $request = $this->select_all($sql);
        return $request;
    }

    public function consultarDatosAut()
    {
        $sql = "SELECT a.pla_id Ids,b.cat_nombre Centro, a.pla_fecha_incio FechaIni,a.pla_fecha_fin FechaFin,a.pla_fechas_rango Rango,a.pla_estado_logico Estado ";
        $sql .= "    FROM " . $this->db_name . ".planificacion a ";
        $sql .= "        inner join " . $this->db_name . ".centro_atencion b  ";
        $sql .= "            on a.cat_id=b.cat_id ";
        $sql .= "    where a.pla_estado_logico!=0; ";
        $request = $this->select_all($sql);
        return $request;
    }

    public function consultarDatosId(int $Ids)
    {
        $sql = "SELECT * FROM " . $this->db_name . ".planificacion_temp where tpla_id={$Ids} and tpla_estado_logico!=0;";
        $request = $this->select($sql);
        return $request;
    }

    public function consultarDatosIdAut(int $Ids)
    {
        $sql = "SELECT * FROM " . $this->db_name . ".planificacion where pla_id={$Ids} and pla_estado_logico!=0;";
        $request = $this->select($sql);
        return $request;
    }


    public function insertData($Cabecera, $Detalle)
    {
        $diaLunes="";
        $diaMartes="";
        $diaMiercoles="";
        $diaJueves="";
        $diaViernes="";
        $diaSabado="";
        $diaDomingo="";
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
                    retornaUser(),
                    1
                );
                $SqlQuery = "INSERT INTO " . $this->db_name . ".planificacion_temp 
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
                //throw $e;
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





    public function updateData($Cabecera, $Detalle)
    {
        try {
            $Ids = $Cabecera['ids'];
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
                retornaUser()
            );

            $sql = "UPDATE " . $this->db_name . ".planificacion_temp 
                        SET tpla_fecha_incio=?,tpla_fecha_fin=?,
						tpla_lunes = ?, tpla_martes = ?,tpla_miercoles = ?,tpla_jueves = ?,tpla_viernes = ?,tpla_sabado = ?,tpla_domingo = ?,
                        tpla_fechas_rango = ?,tpla_usuario_modificacion = ?,tpla_fecha_modificacion = CURRENT_TIMESTAMP() WHERE tpla_id={$Ids}  ";
            $request = $this->update($sql, $arrData);
            $arroout["status"] = ($request) ? true : false;
            $arroout["numero"] = 0;
            return $arroout;
        } catch (Exception $e) {
            //throw $e;
            $arroout["status"] = false;
            $arroout["message"] = "Fallo: " . $e->getMessage();
            return $arroout;
        }
    }

    public function deleteRegistro(int $Ids)
    {
        $usuario = retornaUser();
        $sql = "UPDATE " . $this->db_name . ".planificacion_temp SET tpla_estado_logico = ?,tpla_usuario_modificacion='{$usuario}',
                    tpla_fecha_modificacion = CURRENT_TIMESTAMP() WHERE tpla_id = {$Ids} ";
        $arrData = array(0);
        $request = $this->update($sql, $arrData);
        return $request;
    }

    public function clonarRegistro($dataObj)
    {
        $Ids=$dataObj['ids'];
        $centroId=$dataObj['CentroAtencionID'];
        $fecha_desde=$dataObj['fecIni'];
        $fecha_hasta=$dataObj['fecFin'];
        $usuario = retornaUser();
        $sql = "INSERT INTO " . $this->db_name . ".planificacion_temp 
                        (`cat_id`,`tpla_fecha_incio`,`tpla_fecha_fin`,`tpla_lunes`,`tpla_martes`,`tpla_miercoles`,`tpla_jueves`,
                        `tpla_viernes`,`tpla_sabado`,`tpla_domingo`,`tpla_fechas_rango`,`tpla_estado`,`tpla_usuario_creacion`,`tpla_estado_logico`)
                        SELECT '{$centroId}','{$fecha_desde}','{$fecha_hasta}',`tpla_lunes`,`tpla_martes`,`tpla_miercoles`,`tpla_jueves`,
                         `tpla_viernes`,`tpla_sabado`,`tpla_domingo`,'',`tpla_estado`,'{$usuario}',1
                                FROM " . $this->db_name . ".planificacion_temp WHERE tpla_id = {$Ids} ";

        $arrData = array();
        $request = $this->update($sql, $arrData);
        return $request;
    }


    public function autorizarRegistro(int $Ids)
    {
        $usuario = retornaUser();
        $sql = "INSERT INTO " . $this->db_name . ".planificacion 
                        (`cat_id`,`pla_fecha_incio`,`pla_fecha_fin`,`pla_lunes`,`pla_martes`,`pla_miercoles`,`pla_jueves`,
                        `pla_viernes`,`pla_sabado`,`pla_domingo`,`pla_fechas_rango`,`pla_estado`,`pla_usuario_creacion`,`pla_estado_logico`)
                        SELECT `cat_id`,`tpla_fecha_incio`,`tpla_fecha_fin`,`tpla_lunes`,`tpla_martes`,`tpla_miercoles`,`tpla_jueves`,
                         `tpla_viernes`,`tpla_sabado`,`tpla_domingo`,`tpla_fechas_rango`,`tpla_estado`,'{$usuario}',1
                                FROM " . $this->db_name . ".planificacion_temp WHERE tpla_id = {$Ids} ";

        $arrData = array();
        $request = $this->update($sql, $arrData);
        return $request;
    }

}