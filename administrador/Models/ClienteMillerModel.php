<?php
class ClienteMillerModel extends Mysql
{
    private $db_name;

    public function __construct()
    {
        parent::__construct();
        $this->db_name = $this->getDbNameMysql();
    }

    public function consultarDatos($parametro)
    {
        //putMessageLogFile(empty($parametro ));
        $sql = "SELECT a.cli_id Ids,a.per_id PerIds,b.fpag_nombre Pago,a.cli_tipo_dni Tipo, ";
        $sql .= "   a.cli_cedula_ruc Cedula,a.cli_razon_social Nombre,a.cli_direccion Direccion,a.cli_correo Correo,a.cli_telefono Telefono, a.cli_distribuidor Distribuidor,a.cli_tipo_precio Precio,a.cli_ruta_certificado_ruc Certificado,a.estado_logico Estado ";
        $sql .= "   FROM " . $this->db_name . ".cliente a  ";
        $sql .= "      INNER JOIN " . $this->db_name . ".forma_pago b ON a.fpag_id=b.fpag_id  ";
        $sql .= " WHERE a.estado_logico!=0  ";
        //if(empty($parametro )){
        //    $sql .= ($parametro['estado']==1)? " AND a.estado_logico=1 ":"";
        //}
        $request = $this->select_all($sql);
        return $request;
    }

    public function consultarDatosId(int $Ids)
    {
        $sql = "SELECT a.cli_id Ids,a.cli_codigo Codigo,a.per_id PerIds,b.fpag_nombre Pago,a.cli_tipo_dni Tipo,a.fpag_id,a.ocu_id OcupId, ";
        $sql .= "   a.cli_cedula_ruc Cedula,a.cli_razon_social Nombre,a.cli_direccion Direccion,a.cli_correo Correo, ";
        $sql .= "   a.cli_telefono Telefono, a.cli_distribuidor Distribuidor,a.cli_tipo_precio Precio, ";
        $sql .= "   a.cli_telefono_oficina TelefOficina,a.cli_referencia_bancaria RefBanco,a.cli_cargo Cargo,a.cli_antiguedad Antiguedad,a.cli_ingreso_mensual IngMensual, ";
        $sql .= "   a.cli_ruta_certificado_ruc Certificado,a.estado_logico Estado ";
        $sql .= "   FROM " . $this->db_name . ".cliente a  ";
        $sql .= "      INNER JOIN " . $this->db_name . ".forma_pago b ON a.fpag_id=b.fpag_id  ";
        $sql .= "WHERE a.estado_logico!=0 AND a.cli_id={$Ids} ";
        $request = $this->select($sql);
        return $request;
    }

    public function insertData($dataObj)
    {
        $idsUsuario = retornaUser();
        $strPerID = $dataObj['per_id'];
        $empId = $_SESSION['idEmpresa'];
        $con = $this->getConexion();
        $sql = "SELECT * FROM " . $this->db_name . ".cliente where per_id={$strPerID}";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $con->beginTransaction();
            try {
                //Inserta Cabecera add_ceros();		
                $arrData = array(
                    $empId,
                    $dataObj['per_id'],
                    $dataObj['pago'],
                    $dataObj['codigo'],
                    $dataObj['cli_tipo_dni'],
                    $dataObj['cli_cedula_ruc'],
                    $dataObj['cli_razon_social'],
                    $dataObj['cli_direccion'],
                    $dataObj['cli_correo'],
                    $dataObj['cli_telefono'],
                    $dataObj['cli_telefono_oficina'],
                    $dataObj['cli_referencia_bancaria'],
                    $dataObj['cli_cargo'],
                    $dataObj['cli_antiguedad'],
                    $dataObj['ocupacion'],
                    $dataObj['cli_ingreso_mensual'],
                    1, $idsUsuario
                );
                //putMessageLogFile($arrData);
                $SqlQuery  = "INSERT INTO " . $this->db_name . ".cliente 
				    (`emp_id`,
                    `per_id`,
                    `fpag_id`,
                    `cli_codigo`,
                    `cli_tipo_dni`,
                    `cli_cedula_ruc`,
                    `cli_razon_social`,
                    `cli_direccion`,
                    `cli_correo`,
                    `cli_telefono`,
                    `cli_telefono_oficina`,
                    `cli_referencia_bancaria`,
                    `cli_cargo`,
                    `cli_antiguedad`,
                    `ocu_id`,
                    `cli_ingreso_mensual`,
                    `estado_logico`,
                    `usuario_creacion`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
                $Ids = $this->insertConTrasn($con, $SqlQuery, $arrData);
                $con->commit();
                $arroout["status"] = true;
                $arroout["numero"] = $Ids;
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
            $arroout["message"] = "Ya exite Persona asignada al Cliente.";
            return $arroout;
        }
    }

    public function updateData($dataObj)
    {
        $Ids = $dataObj['ids'];
        $arroout["status"] = false;
        $arrData = array(
            $dataObj['pago'],
            $dataObj['cli_tipo_dni'],
            $dataObj['cli_cedula_ruc'],
            $dataObj['cli_razon_social'],
            $dataObj['cli_direccion'],
            $dataObj['cli_correo'],
            $dataObj['cli_telefono'],
            $dataObj['cli_telefono_oficina'],
            $dataObj['cli_referencia_bancaria'],
            $dataObj['cli_cargo'],
            $dataObj['cli_antiguedad'],
            $dataObj['ocupacion'],
            $dataObj['cli_ingreso_mensual'],
            $dataObj['estado'],
            retornaUser()
        );

        $sql = "UPDATE " . $this->db_name . ".cliente
						SET					
						`fpag_id` = ?,
						`cli_tipo_dni` = ?,
						`cli_cedula_ruc` = ?,
						`cli_razon_social` = ?,
                        `cli_direccion` = ?,
                        `cli_correo` = ?,
                        `cli_telefono` = ?,
                        `cli_telefono_oficina`= ?,
                        `cli_referencia_bancaria`= ?,
                        `cli_cargo`= ?,
                        `cli_antiguedad`= ?,
                        `ocu_id`= ?,
                        `cli_ingreso_mensual`= ?,
                        `estado_logico` = ?,
						`usuario_modificacion` = ?,
						`fecha_modificacion` = CURRENT_TIMESTAMP()
						WHERE `cli_id` = {$Ids}";

        $request = $this->update($sql, $arrData);
        if ($request) {
            $arroout["status"] = true;
        }
        return $arroout;
    }


    public function deleteRegistro(int $Ids)
    {
        $usuario = retornaUser();
        $sql = "UPDATE " . $this->db_name . ".cliente SET estado_logico = ?,usuario_modificacion='{$usuario}',fecha_modificacion = CURRENT_TIMESTAMP() WHERE cli_id = {$Ids} ";
        $arrData = array(0);
        $request = $this->update($sql, $arrData);
        return $request;
    }

    public function consultarProfesion()
    {
        $sql = "SELECT ocu_id Ids, ocu_nombre Nombre ";
        $sql .= " FROM " . $this->db_name . ".ocupacion WHERE estado_logico=1 ORDER BY ocu_nombre ASC";
        $request = $this->select_all($sql);
        return $request;
    }

    public function consultarDatosCedulaNombres(string $parametro){

        $sql = "SELECT a.cli_id Ids,a.per_id,a.fpag_id FpagIds,c.fpag_nombre FpagoNombre,d.ocu_nombre OcupaNombre,a.cli_tipo_dni TipoDni,a.cli_cedula_ruc CedulaRuc,
                    a.cli_razon_social RazonSocial,a.cli_direccion DireccionCliente,a.cli_correo CorreoCliente,a.cli_telefono TelefCliente,
                    a.cli_telefono_oficina TelfOficina,a.cli_cargo Cargo,a.cli_antiguedad Antiguedad,a.cli_ingreso_mensual IngMensual,
                    a.cli_referencia_bancaria RefBanco,CONCAT(b.per_nombre,' ',b.per_apellido) NombreTitular,b.per_telefono TelfCelular,
                    b.per_direccion DireccionDomicilio
                FROM " . $this->db_name . ".cliente a
                    INNER JOIN " . $this->db_name . ".persona b
                        ON a.per_id=b.per_id AND b.estado_logico!=0
                    INNER JOIN " . $this->db_name . ".forma_pago c
                        ON c.fpag_id=a.fpag_id
                    INNER JOIN " . $this->db_name . ".ocupacion d
                        ON d.ocu_id=a.ocu_id
                WHERE a.estado_logico!=0 ";

		if($parametro!=''){
			if (is_numeric($parametro)) {
				//$sql .= " AND (a.per_id LIKE '%{$parametro}%' OR a.per_cedula LIKE '%{$parametro}%'); ";
				$sql .= " AND a.cli_cedula_ruc LIKE '%{$parametro}%' ";
			}else{
				$sql .= " AND (b.per_nombre LIKE '%{$parametro}%' OR b.per_apellido LIKE '%{$parametro}%' OR a.cli_razon_social LIKE '%{$parametro}%') ";
			}
		}
        //$sql .= LIMIT;
		$request = $this->select_all($sql);
		return $request;
	}


}
