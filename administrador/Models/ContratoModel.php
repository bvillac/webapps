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
        $sql = "SELECT a.con_id Ids,a.con_numero Numero,date(a.con_fecha_inicio) FechaIni,b.cli_razon_social RazonSocial,a.con_valor Total,a.con_valor_cuota_inicial CuoInicial, ";
		$sql .= "(a.con_valor-a.con_valor_cuota_inicial) Saldo,a.con_numero_pagos Npagos,a.con_valor_cuota_mensual Vmensual,a.con_estado_logico Estado ";
		$sql .= "FROM " . $this->db_name . ".contrato a ";
		$sql .= "	INNER JOIN 	" . $this->db_nameAdmin . ".cliente b ";
		$sql .= "		ON a.cli_id=b.cli_id and b.estado_logico!=0 ";
        //$sql .= "   WHERE a.con_estado_logico!=0 ORDER BY a.con_numero ASC ";
        $sql .= "   ORDER BY a.con_numero ASC ";
        $request = $this->select_all($sql);
        return $request;
    }

 

    public function insertData($Cabecera, $Detalle,$Referencia)
    {
        $con = $this->getConexion();
        $con->beginTransaction();
        try {
            $PuntoEmision=$_SESSION['empresaData']['PuntoEmisId'];
            $objSecuencia=new SecuenciasModel;
			//$numGenerado=$objSecuencia->newSecuence("CON",$PuntoEmision,true,$con);
            $numGenerado=$Cabecera['numeroContrato'];
            if((int)$numGenerado>0){//Si Es mayor a 0 continua guardando
                $contId=$this->insertarContrato($con,$Cabecera,$numGenerado); 
                //INSERTAR COBRANZAS
                $numCobroGenerado=$objSecuencia->newSecuence("COB",$PuntoEmision,true,$con);
                //Si existe Cuota Inicial se ingresa
                if($Cabecera['cuotaInicial']!=0){
                    //$this->insertarInicialCobranza($con,$Cabecera,$contId,$numGenerado,$numCobroGenerado);
                    $arrData = array(
                        $contId,
                        "COB",
                        $numCobroGenerado,
                        "INI",
                        $Cabecera['fecha_inicio'],
                        $Cabecera['fecha_inicio'],
                        0,
                        $Cabecera['cuotaInicial'],
                        $Cabecera['fecha_inicio'],
                        $Cabecera['cuotaInicial'],
                        "C",
                        "CON",
                        $numGenerado,
                        retornaUser(), 1
                    ); 
                    $this->insertarCobranza($con,$arrData);
                }

                //Si existe varias cuotas ingresa
                $numCuota = ($Cabecera['numeroCuota']!="")?(int)$Cabecera['numeroCuota']:0;
                if($numCuota>0){   
                    $plazo=30; 
                    $fechaFin = new DateTime($Cabecera['fecha_inicio']);                                 
                    for ($i = 0; $i < $numCuota; $i++) {
                        $fechaFin->modify("+{$plazo} day");                         
                        $arrData = array(
                            $contId,
                            "COB",
                            $numCobroGenerado,
                            ($i + 1) . "/" . $numCuota,
                            $Cabecera['fecha_inicio'],
                            $fechaFin->format('Y-m-d'),
                            $plazo,
                            $Cabecera['valorMensual'],
                            NULL,
                            0,
                            "P",
                            "CON",
                            $numGenerado,
                            retornaUser(), 1
                        );
                        $this->insertarCobranza($con,$arrData);
                        //$plazo+=30;
                    } 
                }
                
                for ($i = 0; $i < sizeof($Detalle); $i++) {
                    $arrBeneficiario = array(
                        $contId,
                        $Detalle[$i]['PerIdBenef'],
                        $Detalle[$i]['TBenfId'],
                        $Detalle[$i]['EdadBeneficirio'],
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

                for ($i = 0; $i < sizeof($Referencia); $i++) {
                   
                    $arrReferencia = array(
                        $contId,
                        $Referencia[$i]['refNombre'],
                        $Referencia[$i]['refDireccion'],
                        $Referencia[$i]['refTelefono'],
                        $Referencia[$i]['refCiudad'],
                        retornaUser(),1
                    ); 
                    $this->insertarReferencia($con, $arrReferencia);
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
            //putMessageLogFile($e);
            //throw $e;
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
            $Cabecera['tipoPago'],
            $Cabecera['idsFPago'],
            $Cabecera['valor'],
            $Cabecera['cuotaInicial'],
            $Cabecera['numeroCuota'],
            $Cabecera['valorMensual'],
            $Cabecera['observacion'],
            retornaUser(),1
        );     
        $SqlQuery  = "INSERT INTO " . $this->db_name . ".contrato ";
        $SqlQuery .= "(`emp_id`,`cli_id`,`con_numero`,`con_fecha_inicio`,`con_fecha_fin`,`con_num_recibo_inscripcion`,`con_num_deposito`,
                        `con_tipo_pago`,`con_forma_pago`,`con_valor`,`con_valor_cuota_inicial`,`con_numero_pagos`,`con_valor_cuota_mensual`,
                        `con_observacion`,`con_usuario_creacion`,`con_estado_logico`) ";
        $SqlQuery .= " VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
        return $this->insertConTrasn($con, $SqlQuery, $arrData);        
    }

    private function insertarCobranza($con, $arrData)
    { 
        $SqlQuery  = "INSERT INTO " . $this->db_name . ".cobranza ";
        $SqlQuery .= "(`con_id`,`sec_tipo`,`sec_numero`,`numero_cobro`,`fecha_sustento_debito`,`fecha_vencimiento_debito`,`dia_plazo`,
                            `valor_debito`,`fecha_pago_debito`,`valor_cancelado`,`estado_cancelado`,`original_transaccion`,`original_documento`,
                            `usuario_ingreso`,`estado_logico`) ";
        $SqlQuery .= " VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
        $this->insertConTrasn($con, $SqlQuery, $arrData);
    }


    private function insertarBeneficiario($con, $arrDetalle){ 
        $SqlQuery  = "INSERT INTO " . $this->db_name . ".beneficiario ";
        $SqlQuery .= "(`con_id`,`per_id`,`ben_tipo`,`ben_edad`,`ben_usuario_creacion`,`ben_estado_logico`) ";
        $SqlQuery .= " VALUES (?,?,?,?,?,?) ";
        return $this->insertConTrasn($con, $SqlQuery, $arrDetalle);
    }

    private function insertarAprendisaje($con, $arrDetalle){ 
        $SqlQuery  = "INSERT INTO " . $this->db_name . ".aprendisaje ";
        $SqlQuery .= "(`ben_id`,`paq_id`,`idi_id`,`mas_id`,`cat_id`,`apr_numero_meses`,`apr_numero_horas`,`apr_observaciones`,
                       `apr_examen_internacional`,`apr_usuario_creacion`,`apr_estado_logico`) ";
        $SqlQuery .= " VALUES (?,?,?,?,?,?,?,?,?,?,?) ";
        return $this->insertConTrasn($con, $SqlQuery, $arrDetalle);
    }

    private function insertarReferencia($con, $arrReferencia):void{//No retorna valor 
        $SqlQuery  = "INSERT INTO " . $this->db_name . ".referencia ";
        $SqlQuery .= "(`con_id`,`ref_nombre`,`ref_direccion`,`ref_telefono`,`ref_ciudad`,`ref_usuario_creacion`,`ref_estado_logico`) ";
        $SqlQuery .= " VALUES (?,?,?,?,?,?,?) ";
        $this->insertConTrasn($con, $SqlQuery, $arrReferencia);
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

    public function consultarContratoPDF(int $Ids){
        $request = array();
        $requestContrato=$this->consultarContratoId($Ids);		
        if(!empty($requestContrato)){
            $requestBenef=$this->consultarBeneficiarioContrato($requestContrato['Ids']);			
            $request = array('cabData' => $requestContrato,
                             'detData' => $requestBenef,
                             'empData' => "");
        }
        return $request;
    }

    public function consultarContratoId(int $codigo){
        $sql = "SELECT a.con_id Ids,a.con_numero Numero,date(a.con_fecha_inicio) FechaIni,b.cli_razon_social RazonSocial,b.cli_cedula_ruc CedulaRucCli, ";
        $sql .= " concat(p.per_nombre,' ',p.per_apellido) NombresCliente,b.cli_cargo Cargo,b.cli_ingreso_mensual IngMensual, ";
        $sql .= " b.cli_antiguedad Antiguedad,b.cli_referencia_bancaria RefBanco,a.con_num_recibo_inscripcion NumRecibo, ";
        $sql .= " a.con_num_deposito NumDeposito,b.cli_direccion DirTrabajo,b.cli_telefono_oficina TelOficina,b.cli_telefono TelDomicilio, ";
		$sql .= " p.per_direccion DirDomicilio,p.per_telefono TelCelular, ";
		$sql .= " (SELECT ocu_nombre FROM " . $this->db_nameAdmin . ".ocupacion where ocu_id=b.ocu_id) Ocupacion, ";
        $sql .= " (SELECT fpag_nombre FROM " . $this->db_nameAdmin . ".forma_pago where fpag_id=b.fpag_id) FormaPago, ";
        $sql .= " a.con_valor Total,a.con_valor_cuota_inicial CuoInicial, ";
		$sql .= " (a.con_valor-a.con_valor_cuota_inicial) Saldo,a.con_numero_pagos Npagos,a.con_valor_cuota_mensual Vmensual,a.con_estado_logico Estado ";
		$sql .= "FROM " . $this->db_name . ".contrato a ";
		$sql .= "	INNER JOIN 	(" . $this->db_nameAdmin . ".cliente b ";
        $sql .= "               INNER JOIN " . $this->db_nameAdmin . ".persona p ";
        $sql .= "                    ON b.per_id=p.per_id)  ";
		$sql .= "		ON a.cli_id=b.cli_id and b.estado_logico!=0 ";
        $sql .= "   WHERE a.con_estado_logico=1 AND a.con_id  = {$codigo}  ";
        $request = $this->select($sql);
        return $request;
    }

    public function consultarBeneficiarioContrato(int $codigo){
        $sql = "SELECT a.ben_tipo,b.per_cedula Dni,CONCAT(b.per_nombre,' ',b.per_apellido) Nombres,b.per_telefono TelCelular, ";
        $sql .= "  c.apr_numero_meses NMeses,c.apr_numero_horas NHoras,c.apr_examen_internacional Examen, ";
        //$sql .= "  FLOOR(DATEDIFF(CURDATE(),b.per_fecha_nacimiento) / 365.25) Edad , ";
        $sql .= "  a.ben_edad Edad , ";
        $sql .= "  (SELECT paq_nombre FROM " . $this->db_name . ".paquete where paq_id=c.paq_id) Paquete, ";
        $sql .= "  (SELECT idi_nombre FROM " . $this->db_name . ".idioma where idi_id=c.idi_id) Idioma, ";
        $sql .= "  (SELECT mas_nombre FROM " . $this->db_name . ".modalidad_asistencia where mas_id=c.mas_id) Modalidad, ";
        $sql .= "  (SELECT cat_nombre FROM " . $this->db_name . ".centro_atencion where cat_id=c.cat_id) CentroAtencion ";
        $sql .= "  FROM " . $this->db_name . ".beneficiario a ";
		$sql .= "    INNER JOIN " . $this->db_nameAdmin . ".persona b ";
		$sql .= "	    ON a.per_id=b.per_id ";
        $sql .= "    INNER JOIN " . $this->db_name . ".aprendisaje c ";
		$sql .= "	    ON c.ben_id=a.ben_id ";
        $sql .= "  WHERE a.ben_estado_logico!=0 AND a.con_id={$codigo} ";
        $request = $this->select_all($sql);
        return $request;
    }


    public function desativarContrato(int $Ids)
    {
        $usuario = retornaUser();
        $sql = "UPDATE " . $this->db_name . ".contrato SET con_estado_logico = ?,con_usuario_modificacion='{$usuario}',
                        con_fecha_modificacion = CURRENT_TIMESTAMP() WHERE con_id = {$Ids} ";
        $arrData = array(0);
        $request = $this->update($sql, $arrData);
        return $request;
    }


}
