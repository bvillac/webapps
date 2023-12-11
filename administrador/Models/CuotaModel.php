<?php
class CuotaModel extends MysqlAcademico
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
        $empId = $_SESSION['idEmpresa'];
        $sql = "SELECT b.con_id ContIds,c.cli_cedula_ruc DNI,c.cli_razon_social RazonSolcial,date(b.con_fecha_inicio) FechaContrato,CONCAT(a.original_transaccion,a.original_documento) Contrato,b.con_numero_pagos NumeroPagos, ";
        $sql .= "   b.con_valor_cuota_mensual ValorMensual,b.con_valor ValorDebito,SUM(a.valor_cancelado) ValorAbonos, (b.con_valor-SUM(a.valor_cancelado)) Saldo, ";
        $sql .= "   max(date(a.fecha_pago_debito)) FechaUltPago,if((b.con_valor-SUM(a.valor_cancelado))<b.con_valor,'PENDIENTE','CANCELADO') EstadoCancelado ";
        $sql .= "  FROM " . $this->db_name . ".cobranza a ";
        $sql .= "       INNER JOIN (" . $this->db_name . ".contrato b ";
        $sql .= "           INNER JOIN " . $this->db_nameAdmin . ".cliente c ON b.cli_id=c.cli_id) ";
        $sql .= "       ON a.con_id=b.con_id ";
        $sql .= "   WHERE a.estado_logico!=0 AND b.emp_id={$empId} ";
        $sql .= "   GROUP BY a.original_documento ORDER BY a.original_documento ";
        $request = $this->select_all($sql);
        return $request;
    }

    
    public function consultarPagoContratoId(int $Ids)
    {
        $sql = "SELECT a.con_id Ids,a.con_numero Contrato,DATE(a.con_fecha_inicio) FechaInicio,a.con_valor Valor,a.con_valor_cuota_mensual ValorMensual, ";
        $sql .= "   c.cli_cedula_ruc DNI,c.cli_razon_social RazonSocial,a.con_numero_pagos Npagos ";
        $sql .= "   FROM " . $this->db_name . ".contrato a  ";
        $sql .= "       INNER JOIN " . $this->db_nameAdmin . ".cliente c ON a.cli_id=c.cli_id ";
        $sql .= "   WHERE a.con_estado_logico=1 and a.con_id={$Ids} ";
        $resultContrato = $this->select($sql);
        if (!empty($resultContrato)) { 
            $sql = "SELECT a.cob_id Ids,a.numero_cobro Numero,DATE(a.fecha_vencimiento_debito) FechaVencimiento,a.valor_debito ValorDebito,DATE(a.fecha_pago_debito) FechaPago, ";
            $sql .= "   a.valor_cancelado ValorCancelado,if(a.fecha_pago_debito>=a.fecha_vencimiento_debito,'VENCIDO','') EstadoVencimiento, ";
            $sql .= "   a.estado_cancelado Estado,if(a.estado_cancelado='C','CANCELADO','PENDIENTE') EstadoCancelado ";
            $sql .= "FROM " . $this->db_name . ".cobranza a ";
            $sql .= "   WHERE a.estado_logico!=0 and a.con_id={$Ids} ";
            $resultCobros = $this->select_all($sql);

            $movimiento=[];
			$Saldo=$resultContrato['Valor'];
            $rowData[0]['IDS']="";
            $rowData[0]['NUMERO']="";
            $rowData[0]['FECHA_VENCE']=$resultContrato['FechaInicio'];
            $rowData[0]['FECHA_PAGO']="";            			
			$rowData[0]['DEBITO']="INICIAL";
			$rowData[0]['CREDITO']="";
			$rowData[0]['SALDO']= SMONEY . ' ' . formatMoney($Saldo, 2);
            $rowData[0]['CANCELADO']="";
			$rowData[0]['VENCIDO']="";
			$rowData[0]['REFERENCIA']="";
            $rowData[0]['ESTADO']="N";
			$c=1;	

            for ($i = 0; $i < sizeof($resultCobros); $i++) {
                $rowData[$c]['IDS']=$resultCobros[$i]['Ids'];
                $rowData[$c]['NUMERO']=$resultCobros[$i]['Numero'];
                $rowData[$c]['FECHA_VENCE']=$resultCobros[$i]['FechaVencimiento'];
                $rowData[$c]['FECHA_PAGO']=$resultCobros[$i]['FechaPago'];                			
                $rowData[$c]['DEBITO']=$resultCobros[$i]['ValorDebito'];	
                $rowData[$c]['CREDITO']=($resultCobros[$i]['Estado']=='C')? SMONEY . ' ' . formatMoney($resultCobros[$i]['ValorCancelado'], 2):"";
                $Saldo=$Saldo-$resultCobros[$i]['ValorCancelado'];	
                $rowData[$c]['SALDO']= ($resultCobros[$i]['Estado']=='C')? SMONEY . ' ' . formatMoney($Saldo, 2):""; 
                $rowData[$c]['CANCELADO']=$resultCobros[$i]['EstadoCancelado'];	
                $rowData[$c]['VENCIDO']=$resultCobros[$i]['EstadoVencimiento'];	
                $rowData[$c]['REFERENCIA']="";
                $rowData[$c]['ESTADO']=$resultCobros[$i]['Estado'];
                $movimiento=$rowData;
                $c++;
            }
            $arroout['movimiento']=$movimiento;
            $arroout["contrato"] = $resultContrato;
            $arroout["status"] = true;
        }else {
            $arroout["status"] = false;
            $arroout["message"] = "Error en la consulta de Contrato.";
        }
        return $arroout;
    }


    public function realizarPago(int $Ids)
    {
        $usuario = retornaUser();
        $con = $this->getConexion();
        $sql = "SELECT valor_debito Valor,estado_cancelado Estado FROM " . $this->db_name . ".cobranza where estado_logico=1 and cob_id={$Ids} and estado_cancelado='P' ";
        $requestCob = $this->select($sql);
        if (!empty($requestCob)) { //Ingresa solo si existen valores
            $con->beginTransaction();
            try {				
                $sql = "UPDATE " . $this->db_name . ".cobranza SET valor_cancelado = ?,estado_cancelado = ?,fecha_pago_debito = CURRENT_TIMESTAMP(),
                            usuario_modificacion='{$usuario}',fecha_modificacion = CURRENT_TIMESTAMP() WHERE cob_id = {$Ids} ";
                $arrData = array($requestCob['Valor'],"C");
                $request = $this->updateConTrasn($con,$sql, $arrData);
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
            $arroout["message"] = "Error al Registrar Pago.";
            return $arroout;
        }
    }


    
}
