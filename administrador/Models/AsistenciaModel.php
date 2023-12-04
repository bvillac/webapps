<?php

use Matrix\Functions;

class AsistenciaModel extends MysqlAcademico
{
    private $db_name;
    private $db_nameAdmin;
    public function __construct()
    {
        parent::__construct();
        $this->db_name = $this->getDbNameMysql();
        $this->db_nameAdmin = $this->getDbNameMysqlAdmin();
    }

    public function consultarAsistenciaFechaHora($catId,$plaId,$insId,$fecha,$hora)
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
        $sql .= "   where a.res_estado_logico!=0  and a.cat_id={$catId} and a.pla_id={$plaId} and date(a.res_fecha_reservacion) = '{$fecha}' " ; 
        $sql .=($insId!="0")?" and a.ins_id={$insId} ":"";
        $sql .=($hora!="0")?" and a.res_hora={$hora} ":"";
        $sql .= "   order by a.ins_id,CONVERT(a.res_hora , SIGNED) " ;
        $result = $this->select_all($sql);
        //putMessageLogFile($result);
        $c=-1;
        $aux="";
        $h=0;
        $horas=[];
        for ($i = 0; $i < sizeof($result); $i++) {
            if($aux!=$result[$i]['ins_id']){
                $c++;
                $aux=$result[$i]['ins_id'];
                $rowData[$c]['CatId']=$result[$i]['cat_id'];
                $rowData[$c]['PlaId']=$result[$i]['pla_id'];
                $rowData[$c]['Fecha']=$result[$i]['FechaRes'];
                $rowData[$c]['Dia']=$result[$i]['res_dia'];
                $rowData[$c]['InsId']=$result[$i]['ins_id'];
                $rowData[$c]['InsNombre']=$result[$i]['Instructor'];
                $horas=[];
                $h=0;
                $horas=$this->retonarHoras($result,$i,$horas,$h);
            }else{
                $h++;
                $horas+=$this->retonarHoras($result,$i,$horas,$h);
            }
            $rowData[$c]['Reservado']=$horas;
        }
        //putMessageLogFile($rowData);
        return $rowData;
    }

    public function retonarHoras($result,$i,$horas,$h){
        $horas[$h]['ResId']=$result[$i]['res_id'];
        $horas[$h]['ResHora']=$result[$i]['res_hora'];
        $horas[$h]['ActNombre']=$result[$i]['act_nombre'];
        $horas[$h]['NivNombre']=$result[$i]['niv_nombre'];
        $horas[$h]['ResUnidad']=$result[$i]['res_unidad'];
        $horas[$h]['BenId']=$result[$i]['ben_id'];
        $horas[$h]['BenNombre']=$result[$i]['Nombres'];
        $horas[$h]['SalId']=$result[$i]['sal_id'];
        $horas[$h]['SalNombre']=$result[$i]['sal_nombre'];
        $horas[$h]['Estado']=$result[$i]['res_asistencia'];
        return $horas;
    }






}