<?php
class ModalidadModel extends MysqlAcademico
{
    private $db_name;
    public function __construct()
    {
        parent::__construct();
        $this->db_name = $this->getDbNameMysql();
    }

    public function consultarModalidad(){
        $sql = "SELECT mas_id Ids, mas_nombre Nombre ";
        $sql .= " FROM ". $this->db_name .".modalidad_asistencia WHERE mas_estado_logico!=0  ORDER BY mas_nombre ASC";
        $request = $this->select_all($sql);
        return $request;
    }
   



}
