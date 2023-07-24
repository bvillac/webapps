<?php 
//require_once("Models/SecuenciasModel.php");
class ContratoModel extends MysqlAcademico
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
        $sql = "SELECT a.cli_id Ids,a.per_id PerIds,b.fpag_nombre Pago,a.cli_tipo_dni Tipo, ";
        $sql .= "   a.cli_cedula_ruc Cedula,a.cli_razon_social Nombre,a.cli_direccion Direccion,a.cli_correo Correo,a.cli_telefono Telefono, a.cli_distribuidor Distribuidor,a.cli_tipo_precio Precio,a.cli_ruta_certificado_ruc Certificado,a.estado_logico Estado ";
        $sql .= "   FROM " . $this->db_name . ".cliente a  ";
        $sql .= "      INNER JOIN " . $this->db_name . ".forma_pago b ON a.fpag_id=b.fpag_id  ";
        $sql .= "WHERE a.estado_logico!=0  ";
        $request = $this->select_all($sql);
        return $request;
    }

    public function consultarDatosId(int $Ids)
    {
        $sql = "SELECT a.cli_id Ids,a.cli_codigo Codigo,a.per_id PerIds,b.fpag_nombre Pago,a.cli_tipo_dni Tipo,a.fpag_id, ";
        $sql .= "   a.cli_cedula_ruc Cedula,a.cli_razon_social Nombre,a.cli_direccion Direccion,a.cli_correo Correo, ";
        $sql .= "   a.cli_telefono Telefono, a.cli_distribuidor Distribuidor,a.cli_tipo_precio Precio, ";
        $sql .= "   a.cli_ruta_certificado_ruc Certificado,a.estado_logico Estado ";
        $sql .= "   FROM " . $this->db_name . ".cliente a  ";
        $sql .= "      INNER JOIN " . $this->db_name . ".forma_pago b ON a.fpag_id=b.fpag_id  ";
        $sql .= "WHERE a.estado_logico!=0 AND a.cli_id={$Ids} ";
        $request = $this->select($sql);
        return $request;
    }

    public function insertData($Cabecera, $Detalle)
    {
        //$strPerID = $dataObj['per_id'];
        
        $con = $this->getConexion();
        $con->beginTransaction();

        try {
            $PuntoEmision=$_SESSION['empresaData']['PuntoEmisId'];
            $objSecuencia=new SecuenciasModel;
			$numGenerado=$objSecuencia->newSecuence("CON",$PuntoEmision,true,$con);
            if((int)$numGenerado>0){//Si Es mayor a 0 continua guardando
                $contId=$this->insertarContrato($con,$Cabecera,$numGenerado);                
                for ($i = 0; $i < sizeof($Detalle); $i++) {
                    $arrBeneficiario = array(
                        $contId,
                        $Detalle[$i]['PerIdBenef'],
                        $Detalle[$i]['TBenfId'],
                        retornaUser(),1
                    ); 
					$benId=$this->insertarBeneficiario($con, $arrBeneficiario);
                    $arrAprendisaje = array(
                        $benId,
                        $Detalle[$i]['PaqueteEstudiosID'],
                        $Detalle[$i]['IdiomaID'],
                        $Detalle[$i]['ModalidadEstudiosID'],
                        $Detalle[$i]['CentroAtencionID'],
                        $Detalle[$i]['NMeses'],
                        $Detalle[$i]['NHoras'],
                        $Detalle[$i]['Observaciones'],
                        $Detalle[$i]['ExaInternacional'],
                        retornaUser(),1
                    );
                    $aprId=$this->insertarAprendisaje($con, $arrAprendisaje);
				}
                
                $con->commit();
                $arroout["status"] = true;
                $arroout["numero"] = $numGenerado;
            
            }else{
                $con->rollBack();
                $arroout["status"] = false;
                $arroout["message"] = "La secuencía no se genero!.";
            }
            return $arroout;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
            $arroout["status"] = false;
            $arroout["message"] = "Fallo: " . $e->getMessage();
            return $arroout;
        }
    }

    private function insertarContrato($con, $Cabecera,$numGenerado){   
        $empId = $_SESSION['idEmpresa'];
        $arrData = array(
            $empId,
            $Cabecera['cliIds'],
            $numGenerado,
            $Cabecera['fecha_inicio'],
            null,
            $Cabecera['numero_recibo'],
            $Cabecera['numero_deposito'],
            $Cabecera['idsFPago'],
            $Cabecera['valor'],
            $Cabecera['cuotaInicial'],
            $Cabecera['numeroCuota'],
            $Cabecera['valorMensual'],
            retornaUser(),1
        );     
        $SqlQuery  = "INSERT INTO " . $this->db_name . ".contrato ";
        $SqlQuery .= "(`emp_id`,`cli_id`,`con_numero`,`con_fecha_inicio`,`con_fecha_fin`,`con_num_recibo_inscripcion`,`con_num_deposito`,
                        `con_tipo_pago`,`con_valor`,`con_valor_cuota_inicial`,`con_numero_pagos`,`con_valor_cuota_mensual`,`con_usuario_creacion`,
                        `con_estado_logico`) ";
        $SqlQuery .= " VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
        return $this->insertConTrasn($con, $SqlQuery, $arrData);        
    }

    private function insertarBeneficiario($con, $arrDetalle){ 
        $SqlQuery  = "INSERT INTO " . $this->db_name . ".beneficiario ";
        $SqlQuery .= "(`con_id`,`per_id`,`ben_tipo`,`ben_usuario_creacion`,`ben_estado_logico`) ";
        $SqlQuery .= " VALUES (?,?,?,?,?) ";
        return $this->insertConTrasn($con, $SqlQuery, $arrDetalle);
    }

    private function insertarAprendisaje($con, $arrDetalle){ 
        $SqlQuery  = "INSERT INTO " . $this->db_name . ".aprendisaje ";
        $SqlQuery .= "(`ben_id`,`paq_id`,`idi_id`,`mas_id`,`cat_id`,`apr_numero_meses`,`apr_numero_horas`,`apr_observaciones`,
                       `apr_examen_internacional`,`apr_usuario_creacion`,`apr_estado_logico`) ";
        $SqlQuery .= " VALUES (?,?,?,?,?,?,?,?,?,?,?) ";
        return $this->insertConTrasn($con, $SqlQuery, $arrDetalle);
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
}
