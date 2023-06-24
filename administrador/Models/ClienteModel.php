<?php 
require_once("Models/EmpresaModel.php");
require_once("Models/ClienteModel.php");
require_once("Libraries/Core/Conexion.php");
	class ClienteModel extends Mysql{


		public function __construct(){
			parent::__construct();
		}

		public function consultarDatos(){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT a.cli_codigo Ids,b.fpag_nombre Pago,a.cli_tipo_dni Tipo, ";
			$sql .= "   a.cli_cedula_ruc Cedula,a.cli_razon_social Nombre,a.cli_direccion Direccion,a.cli_correo Correo,a.cli_telefono Telefono, a.cli_distribuidor Distribuidor,a.cli_tipo_precio Precio,a.cli_ruta_certificado_ruc Certificado,a.estado_logico Estado ";
			$sql .= "   FROM ". $db_name .".cliente a  ";
			$sql .= "      INNER JOIN ". $db_name .".forma_pago b ON a.fpag_id=b.fpag_id  ";
			$sql .= "WHERE a.estado_logico!=0  ";

			$request = $this->select_all($sql);
			return $request;
		}
		public function consultarPago(){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT fpag_id Ids, fpag_nombre Nombre ";
			$sql .= " FROM ". $db_name .".forma_pago WHERE estado_logico!=0 ORDER BY fpag_nombre ASC ";
			$request = $this->select_all($sql);
			return $request;
		}

		
		public function consultarDatosId(string $Ids){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT a.cli_codigo Ids,b.fpag_nombre Pago,a.fpag_id,a.cli_tipo_dni Tipo, ";
			$sql .= "   a.cli_cedula_ruc Cedula,a.cli_nombre Nombre,a.cli_direccion Direccion,a.cli_correo Correo,a.cli_telefono Telefono, ";
			$sql .= "   a.cli_distribuidor Distribuidor,a.cli_tipo_precio Precio,a.cli_ruta_certificado_ruc Certificado,a.estado_logico Estado,date(a.fecha_creacion) FechaIng";
			$sql .= "   FROM ". $db_name .".cliente a  ";
			$sql .= "      INNER JOIN ". $db_name .".forma_pago b  ";
			$sql .= "      ON a.fpag_id=b.fpag_id AND b.estado_logico!=0  ";
			$sql .= "WHERE a.estado_logico!=0 AND a.cli_codigo='{$Ids}' ";
			$request = $this->select($sql);
			return $request;
			
		}


		public function insertData(string $Ids, string $tipo, string $cedula, string $nombre, string $direccion,  string $correo,string $telefono, int $distribuidor, int $precio, string $url,  int $pago, int $estado){
			$db_name=$this->getDbNameMysql();
			$idsUsuario= $_SESSION['idsUsuario'];
			$return = 0;
			$sql = "SELECT * FROM " . $db_name . ".cliente WHERE  cli_correo = '{$correo}' AND cli_nombre ='{$nombre}'  ";
			$request = $this->select_all($sql);
			if(empty($request)){//Si el Request es vacio inserta los datos
							$query_insert  = "INSERT INTO " . $db_name . " .cliente 
									(cli_codigo,cli_tipo_dni,cli_cedula_ruc,cli_nombre,cli_direccion, cli_correo, cli_telefono,cli_distribuidor,cli_tipo_precio,cli_ruta_certificado_ruc,fpag_id,estado_logico,usuario_creacion) 
									VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?) ";
	        	$arrData = array($Ids,$tipo,$cedula,$nombre,$direccion,$correo,$telefono,$distribuidor,$precio,$url,$pago,$estado,$idsUsuario);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = 1;
			}else{
				$return = "exist";//Restonra Mensaje si ya Existe en la tabla
			}
			return $return;
		}



		public function updateData(string $Ids, string $tipo, string $cedula, string $nombre, string $direccion, string $correo,string $telefono, int $distribuidor, int $precio, string $url,  int $pago, int $estado){
			$db_name=$this->getDbNameMysql();
			$idsUsuario= $_SESSION['idsUsuario'];
			$sql = "UPDATE " . $db_name . ".cliente 
							SET cli_tipo_dni = ?,cli_cedula_ruc = ?,cli_nombre = ?,cli_direccion = ?,cli_correo = ?, cli_telefono = ?, cli_distribuidor = ?, 
							cli_tipo_precio = ?, cli_ruta_certificado_ruc = ?, fpag_id = ?, estado_logico = ?,usuario_modificacion=?,fecha_modificacion = CURRENT_TIMESTAMP()
							 WHERE cli_codigo = {$Ids} ";
			$arrData = array($tipo,$cedula,$nombre,$direccion,$correo,$telefono,$distribuidor,$precio,$url,$pago,$estado,$idsUsuario);
			$request = $this->update($sql, $arrData);
			return $request;
		}
        


		public function deleteRegistro(string $Ids){
			$db_name=$this->getDbNameMysql();
			$idsUsuario= $_SESSION['idsUsuario'];
			$sql = "UPDATE " . $db_name . ".cliente SET estado_logico = ?,usuario_modificacion={$idsUsuario},fecha_modificacion = CURRENT_TIMESTAMP() WHERE cli_codigo = '{$Ids}' ";
			$arrData = array(0);
			$request = $this->update($sql, $arrData);
			return $request;
		}
		public function consultarReporteClientePDF(string $idCliente){
			$busqueda = "";
			/*if($idpersona != NULL){
				$busqueda = " AND p.personaid =".$idpersona;
			}*/
			$empresa=1;
			$ObjEmp=new EmpresaModel;
			$request = array();
			$requestEmpresa=$ObjEmp->consultarEmpresaId($empresa);
			$requestCab=$this->consultarCabecerDoc($idCliente);			
			if(!empty($requestCab)){
				$requestDet=$this->consultarDetalleDoc();			
				$request = array('cabReporte' => $requestCab,
								 'detReporte' => $requestDet,
								 'empData' => $requestEmpresa);
			}
			return $request;
		}
		public function consultarCabecerDoc(string $IdsEmpresa){
			$db_name=$this->getDbNameMysql();
			$IdsEmpresa="1";
			
			$sql = "SELECT a.usu_id Ids,b.per_id ,a.usu_correo Correo,a.usu_alias Alias,a.usu_clave Clave,b.per_cedula Cedula,b.per_nombre Nombre,b.per_apellido Apellido, ";
			$sql .= "	d.rol_nombre Rol,a.estado_logico Estado  ";
			$sql .= "	FROM ". $db_name .".usuario a ";
			$sql .= "		INNER JOIN ". $db_name .".persona b ON a.per_id=b.per_id AND b.estado_logico!=0  ";
			$sql .= "			INNER JOIN (". $db_name .".empresa_usuario c ";
			$sql .= "				INNER JOIN ". $db_name .".rol d ON c.rol_id=d.rol_id ) ";
			$sql .= "		ON a.usu_id=c.usu_id AND c.estado_logico!=0 ";
			$sql .= "	WHERE a.estado_logico!=0 AND c.emp_id='{$IdsEmpresa}' ";
			$request = $this->select($sql);
			return $request;
		}
		public function consultarDetalleDoc(){
			
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT a.cli_codigo Ids,b.fpag_codigo Pago,a.cli_tipo_dni Tipo, ";
			$sql .= "   a.cli_cedula_ruc Cedula,a.cli_nombre Nombre,a.cli_direccion Direccion,a.cli_correo Correo,a.cli_telefono Telefono,a.cli_distribuidor Distribuidor,a.cli_tipo_precio Precio,a.cli_ruta_certificado_ruc Certificado,a.estado_logico Estado ";
			$sql .= "   FROM ". $db_name .".cliente a  ";
			$sql .= "      INNER JOIN ". $db_name .".forma_pago b ON a.fpag_id=b.fpag_id  ";
			$sql .= "WHERE a.estado_logico!=0 ";
			$request = $this->select_all($sql);
			return $request;
		}

		public function consultarDatosClientes(){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT a.cli_codigo Ids,b.fpag_codigo Pago,a.cli_tipo_dni Tipo, ";
			$sql .= "   a.cli_cedula_ruc Cedula,a.cli_nombre Nombre,a.cli_direccion Direccion,a.cli_correo Correo,a.cli_telefono Telefono, a.cli_distribuidor Distribuidor,a.cli_tipo_precio Precio,a.cli_ruta_certificado_ruc Certificado,a.estado_logico Estado ";
			$sql .= "   FROM ". $db_name .".cliente a  ";
			$sql .= "      INNER JOIN ". $db_name .".forma_pago b ON a.fpag_id=b.fpag_id  ";
			$sql .= "WHERE a.estado_logico!=0  ";
			$request = $this->select_all($sql);
			return $request;
		}


	}
 ?>