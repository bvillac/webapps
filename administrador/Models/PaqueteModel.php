<?php
class PaqueteModel extends MysqlAcademico
{
    private $db_name;
    public function __construct()
    {
        parent::__construct();
        $this->db_name = $this->getDbNameMysql();
    }

    public function consultarPaquete(){
        $sql = "SELECT paq_id Ids, paq_nombre Nombre,concat(paq_id,'-',paq_meses) IdsMes ";
        $sql .= " FROM ". $this->db_name .".paquete WHERE paq_estado_logico!=0  ORDER BY paq_nombre ASC";
        $request = $this->select_all($sql);
        return $request;
    }
   



}
