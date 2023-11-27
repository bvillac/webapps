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
        //$sql = "SELECT * FROM " . $this->db_name . ".planificacion where pla_id={$Ids} and pla_estado_logico!=0;";
        $sql = "SELECT a.*,b.cat_nombre Centro ";
        $sql .= "    FROM " . $this->db_name . ".planificacion a ";
        $sql .= "        inner join " . $this->db_name . ".centro_atencion b  ";
        $sql .= "            on a.cat_id=b.cat_id ";
        $sql .= "    where a.pla_estado_logico!=0 and pla_id={$Ids} ";
        $request = $this->select($sql);
        return $request;
    }

    public function consultarReservaciones($dataObj)
    {
        $cat_id=$dataObj['cat_id'];
        $fec_ini=$dataObj['pla_fecha_incio'];
        $fec_fin=$dataObj['pla_fecha_fin'];
        $sql = "SELECT * FROM " . $this->db_name . ".reservacion  ";
        $sql .= "  where cat_id={$cat_id} and date(res_fecha_reservacion) between '{$fec_ini}' and '{$fec_fin}' and res_estado_logico!=0 ";
        $sql .= "     order by res_fecha_reservacion,res_dia,res_hora,sal_id " ;
        $request = $this->select_all($sql);
        return $request;
    }

    public function consultarReservacionFecha($catId,$plaId,$fecha)
    {
        $sql = "SELECT a.res_id,concat(a.res_dia,'_',a.res_hora,'_',a.ins_id,'_',a.sal_id) Ids,a.cat_id,a.pla_id,a.act_id,a.niv_id,a.ben_id,a.ins_id,a.sal_id," ;
        $sql .= "   a.res_fecha_reservacion FechaRes,a.res_unidad,a.res_dia,a.res_hora,a.res_asistencia, CONCAT(c.per_nombre,' ',c.per_apellido) Nombres " ;
        $sql .= "       FROM " . $this->db_name . ".reservacion a   " ;
        $sql .= "           inner join (" . $this->db_name . ".beneficiario b   " ;
        $sql .= "               inner join " . $this->db_nameAdmin . ".persona c  " ;
        $sql .= "                   on b.per_id=c.per_id)   " ;
        $sql .= "           on a.ben_id=b.ben_id   " ;
        $sql .= "   where a.cat_id={$catId} and a.pla_id={$plaId} and date(a.res_fecha_reservacion) = '{$fecha}' " ; 
        $sql .= "       and a.res_estado_logico!=0  order by a.res_hora,a.ins_id " ;
        $request = $this->select_all($sql);
        return $request;
    }


    public function insertData($dataObj)
    {
        $con = $this->getConexion();
        //$res_id = $dataObj['res_id'];
        $pla_id=$dataObj['pla_id'];
        $ben_id=$dataObj['ben_id'];
        $act_id=$dataObj['act_id'];
        $fechaReserv=$dataObj['fechaReserv'];
        $sql = "SELECT * FROM " . $this->db_name . ".reservacion 
                    where pla_id={$pla_id} and ben_id={$ben_id} and act_id={$act_id} and date(res_fecha_reservacion)='{$fechaReserv}' ";
            //putMessageLogFile($sql); 
        $request = $this->select($sql);
        if (empty($request)) {
        
            $con->beginTransaction();
            try {

                $arrData = array(
                    $dataObj['cat_id'],
                    $dataObj['pla_id'],
                    $dataObj['act_id'],
                    $dataObj['niv_id'],
                    $dataObj['ben_id'],
                    $dataObj['ins_id'],
                    $dataObj['sal_id'],
                    $dataObj['fechaReserv'],
                    $dataObj['uni_id'],
                    $dataObj['diaLetra'],
                    $dataObj['hora'],                    
                    retornaUser(), 1
                );
                //putMessageLogFile($arrData); 
                //["1","1","1","1","3","2023-11-13","1","LU","9","byron_villacresesf",1]           
                $SqlQuery  = "INSERT INTO " . $this->db_name . ".reservacion 
				    (`cat_id`,
                    `pla_id`,
                    `act_id`,
                    `niv_id`,
                    `ben_id`,
                    `ins_id`,
                    `sal_id`,
                    `res_fecha_reservacion`,
                    `res_unidad`,
                    `res_dia`,
                    `res_hora`,
                    `res_usuario_creacion`,
                    `res_estado_logico`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?) ";
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
            $arroout["message"] = "Ya exite la Reservación";
            return $arroout;
        }
    }

    public function anularReservacion(int $Ids)
    {
        $usuario = retornaUser();
        $sql = "UPDATE " . $this->db_name . ".reservacion SET res_estado_logico = ?,res_usuario_modificacion='{$usuario}',
                        res_fecha_modificacion = CURRENT_TIMESTAMP() WHERE res_id = {$Ids} ";
        $arrData = array(0);
        $request = $this->update($sql, $arrData);
        return $request;
    }


    public function consultarAsistenciaFecha($catId,$plaId,$insId,$fecha)
    {
        $sql = "SELECT a.res_id,a.cat_id,a.pla_id,a.act_id,d.act_nombre,a.niv_id,f.niv_nombre,a.ben_id,a.ins_id,a.sal_id,e.sal_nombre," ;
        $sql .= "   a.res_fecha_reservacion FechaRes,a.res_unidad,a.res_dia,a.res_hora,a.res_asistencia, CONCAT(c.per_nombre,' ',c.per_apellido) Nombres, " ;
        $sql .= "   (SELECT CONCAT(z.per_nombre,' ',z.per_apellido)  FROM db_academico.instructor x inner join db_administrador.persona z on x.per_id=z.per_id where x.ins_id=a.ins_id) Instructor " ;
        $sql .= "       FROM " . $this->db_name . ".reservacion a   " ;
        $sql .= "           inner join (" . $this->db_name . ".beneficiario b   " ;
        $sql .= "               inner join " . $this->db_nameAdmin . ".persona c  " ;
        $sql .= "                   on b.per_id=c.per_id)   " ;
        $sql .= "           on a.ben_id=b.ben_id   " ;
        $sql .= "           inner join " . $this->db_name . ".actividad d on d.act_id=a.act_id   " ;
        $sql .= "           inner join " . $this->db_name . ".salon e on e.sal_id=a.sal_id   " ;
        $sql .= "           inner join " . $this->db_name . ".nivel f on f.niv_id=a.niv_id   " ;
        $sql .= "   where a.cat_id={$catId} and a.pla_id={$plaId} and a.ins_id={$insId} and date(a.res_fecha_reservacion) = '{$fecha}' " ; 
        $sql .= "       and a.res_estado_logico!=0  order by a.res_hora " ;
        $request = $this->select_all($sql);
        return $request;
    }






}