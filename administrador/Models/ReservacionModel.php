<?php

use Matrix\Functions;

class ReservacionModel extends MysqlAcademico
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
        $sql = "SELECT * FROM " . $this->db_name . ".planificacion where pla_id={$Ids} and pla_estado_logico!=0;";
        $request = $this->select($sql);
        return $request;
    }


    public function insertData($dataObj)
    {
        $con = $this->getConexion();
        //$nombreSalon = $dataObj['nombre'];
        //$sql = "SELECT * FROM " . $this->db_name . ".reservacion where res_id='{$dataObj['nombre']}' and cat_id='{$dataObj['CentroAtencionID']}'";

        //$request = $this->select($sql);
        //if (empty($request)) {
        if (true) {
            $con->beginTransaction();
            try {

                $arrData = array(
                    $dataObj['pla_id'],
                    $dataObj['act_id'],
                    $dataObj['niv_id'],
                    $dataObj['ben_id'],
                    $dataObj['ins_id'],
                    $dataObj['fechaReserv'],
                    $dataObj['uni_id'],
                    $dataObj['diaLetra'],
                    $dataObj['hora'],
                    retornaUser(), 1
                );
                putMessageLogFile($arrData); 
                //["1","1","1","1","3","2023-11-13","1","LU","9","byron_villacresesf",1]           
                $SqlQuery  = "INSERT INTO " . $this->db_name . ".reservacion 
				    (`pla_id`,
                    `act_id`,
                    `niv_id`,
                    `ben_id`,
                    `ins_id`,
                    `res_fecha_reservacion`,
                    `res_unidad`,
                    `res_dia`,
                    `res_hora`,
                    `res_usuario_creacion`,
                    `res_estado_logico`) VALUES(?,?,?,?,?,?,?,?,?,?,?) ";
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
            $arroout["message"] = "Ya exite la Reservación";
            return $arroout;
        }
    }






}