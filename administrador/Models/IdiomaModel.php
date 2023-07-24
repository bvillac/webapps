<?php
class IdiomaModel extends MysqlAcademico
{
    private $db_name;
    public function __construct()
    {
        parent::__construct();
        $this->db_name = $this->getDbNameMysql();
    }

    public function consultarIdioma(){
        $sql = "SELECT idi_id Ids, idi_nombre Nombre ";
        $sql .= " FROM ". $this->db_name .".idioma WHERE idi_estado_logico!=0  ORDER BY idi_nombre ASC";
        $request = $this->select_all($sql);
        return $request;
    }
   



}
