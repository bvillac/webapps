<?php 
class ClienteMillerModel extends Mysql{
    private $db_name;
    public function __construct()
	{
		parent::__construct();
		$this->db_name = $this->getDbNameMysql();
	
	}//$_SESSION['idEmpresa']

    public function consultarDatos(){
        $sql = "SELECT a.cli_codigo Ids,b.fpag_nombre Pago,a.cli_tipo_dni Tipo, ";
        $sql .= "   a.cli_cedula_ruc Cedula,a.cli_razon_social Nombre,a.cli_direccion Direccion,a.cli_correo Correo,a.cli_telefono Telefono, a.cli_distribuidor Distribuidor,a.cli_tipo_precio Precio,a.cli_ruta_certificado_ruc Certificado,a.estado_logico Estado ";
        $sql .= "   FROM " . $this->db_name . ".cliente a  ";
        $sql .= "      INNER JOIN " . $this->db_name . ".forma_pago b ON a.fpag_id=b.fpag_id  ";
        $sql .= "WHERE a.estado_logico!=0  ";
        $request = $this->select_all($sql);
        return $request;
    }



}

?>