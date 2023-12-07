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

    
    public function consultarDatosId(int $Ids)
    {
        $sql = "SELECT a.sal_id Ids,a.cat_id,b.cat_nombre NombreCentro,a.sal_nombre NombreSalon,a.sal_color Color, ";
        $sql .= "	a.sal_cupo_minimo CupoMinimo,a.sal_cupo_maximo CupoMaximo,a.sal_estado_logico Estado,date(sal_fecha_creacion) FechaIngreso ";
        $sql .= "	FROM " . $this->db_name . ".salon a ";
        $sql .= "		inner join " . $this->db_name . ".centro_atencion b ";
        $sql .= "			on a.cat_id=b.cat_id ";
        $sql .= " where a.sal_estado_logico!=0 AND a.sal_id={$Ids}";

        SELECT a.numero_cobro Numero,DATE(a.fecha_vencimiento_debito) FechaVencimiento,a.valor_debito ValorDebito,DATE(a.fecha_pago_debito) FechaPago,
		a.valor_cancelado ValorCancelado,if(a.fecha_pago_debito>=a.fecha_vencimiento_debito,'VENCIDO','') EstadoVencimiento,
        if(a.estado_cancelado='C','CANCELADO','PENDIENTE') EstadoCancelado
		FROM db_academico.cobranza a
        WHERE a.estado_logico!=0 and a.con_id=11;

        $request = $this->select($sql);
        return $request;
    }

    
}
