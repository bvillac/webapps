<?php
class CentroAtencionModel extends MysqlAcademico
{
    private $db_name;
	//private $db_nameAdmin;
    public function __construct()
    {
        parent::__construct();
        $this->db_name = $this->getDbNameMysql();
        //$this->db_nameAdmin = $this->getDbNameMysqlAdmin();
    }

    public function consultarCentroEmpresa(){
        $idsEmpresa=$_SESSION['idEmpresa'];
        $sql = "SELECT cat_id Ids, cat_nombre Nombre ";
        $sql .= " FROM ". $this->db_name .".centro_atencion WHERE cat_estado_logico!=0 and emp_id='{$idsEmpresa}' ORDER BY cat_nombre ASC";
        $request = $this->select_all($sql);
        return $request;
    }
   



}
