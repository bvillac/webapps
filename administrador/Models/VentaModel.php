<?php 

	require_once("Models/EmpresaModel.php");

	class VentaModel extends Mysql{
        
		public function __construct(){
			parent::__construct();
		}	

        public function consultarDatosVentas(){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT a.ped_id Ids,CONCAT(e.est_numero,'-',d.pemi_numero,'-',a.ped_numero) Numero,a.ped_fecha Fecha,b.cli_nombre Nombre,b.cli_cedula_ruc Cedula,";
      		$sql .= "	'PayPal' FormaPago,a.ped_valor_neto ValorNeto,a.estado_logico Estado,IFNULL(a.estado_entrega,0) Despacho ";
    		$sql .= "		FROM ". $db_name .".pedidos a ";
			$sql .= "			INNER JOIN ". $db_name .".cliente b ON a.cli_codigo=b.cli_codigo ";
			$sql .= "			INNER JOIN ". $db_name .".forma_pago c ON a.fpag_id=c.fpag_id ";
			$sql .= "      INNER JOIN ". $db_name .".punto_emision d  ";
            $sql .= "         INNER JOIN ". $db_name .".establecimiento e  ";
			$sql .= "	WHERE a.estado_logico=1 AND a.sec_tipo='FE' ; ";
			$request = $this->select_all($sql);
			return $request;
		}

		public function consultarFacturaPDF(int $idpedido, $idpersona = NULL,$ped_fecha,$ped_numero){
			$busqueda = "";
			/*if($idpersona != NULL){
				$busqueda = " AND p.personaid =".$idpersona;
			}*/
			$empresa=1;
			$idpersona="";
			$ObjEmp=new EmpresaModel;
			$request = array();
			$requestEmpresa=$ObjEmp->consultarEmpresaId($empresa);
			$requestCab=$this->consultarCabecerDoc($idpedido,$ped_fecha,$ped_numero);			
			if(!empty($requestCab)){
				$requestDet=$this->consultarDetalleDoc($idpedido);			
				$request = array('cabData' => $requestCab,
								 'detData' => $requestDet,
								 'empData' => $requestEmpresa);
			}
			return $request;
		}

		public function consultarCabecerDoc(int $codigo){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT a.*,b.cli_nombre Nombre,b.cli_direccion Direccion,b.cli_cedula_ruc Cedula,c.fpag_nombre FormaPago ";
			$sql .= " 	FROM ". $db_name .".pedidos a ";
			$sql .= "		INNER JOIN ". $db_name .".cliente b ON a.cli_codigo=b.cli_codigo ";
			$sql .= "		INNER JOIN  ". $db_name .".forma_pago c ON a.fpag_id=c.fpag_id ";
			$sql .= "WHERE a.estado_logico=1 AND ped_id = {$codigo}  ";
			$request = $this->select($sql);
			return $request;
		}

		public function consultarDetalleDoc(int $codigo){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT a.*,b.item_nombre Nombre ";
  			$sql .= "  FROM ". $db_name .".pedido_detalle a ";
    		$sql .= "   INNER JOIN ". $db_name .".item b ON b.item_id=a.pdet_item_id ";
			$sql .= "  WHERE a.estado_logico=1 AND a.ped_id={$codigo} ";
			$request = $this->select_all($sql);
			return $request;
		}

		public function consultarDatosVentasDespacho(){
			$db_name=$this->getDbNameMysql();//c.fpag_nombre 
			$sql = "SELECT a.ped_id Ids,CONCAT(e.est_numero,'-',d.pemi_numero,'-',a.ped_numero) Numero,a.ped_fecha Fecha,b.cli_nombre Nombre,b.cli_cedula_ruc Cedula, ";
      		$sql .= "	'PayPal' FormaPago,a.ped_valor_neto ValorNeto,a.estado_logico Estado,IFNULL(a.estado_entrega,0) Despacho ";
    		$sql .= "		FROM ". $db_name .".pedidos a ";
			$sql .= "			INNER JOIN ". $db_name .".cliente b ON a.cli_codigo=b.cli_codigo ";
			$sql .= "			INNER JOIN ". $db_name .".forma_pago c ON a.fpag_id=c.fpag_id ";
    		$sql .= "      INNER JOIN ". $db_name .".punto_emision d  ";
            $sql .= "         INNER JOIN ". $db_name .".establecimiento e  ";
			$sql .= "	WHERE a.estado_logico=1 AND a.sec_tipo='FE' AND  a.estado_entrega IS NULL; ";
			$request = $this->select_all($sql);
			return $request;
		}

		public function despacharDocumento(string $Ids){
			$db_name=$this->getDbNameMysql();
			$idsUsuario= $_SESSION['idsUsuario'];
			$sql = "UPDATE ". $db_name .".pedidos SET estado_entrega = ?,usuario_modificacion=?,fecha_modificacion = CURRENT_TIMESTAMP() WHERE ped_id={$Ids} AND estado_logico=1 ";
			$arrData = array(1,$idsUsuario);
			$request = $this->update($sql,$arrData);
			return $request;
		}

		


	}
 ?>