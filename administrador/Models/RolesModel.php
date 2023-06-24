<?php 

	class RolesModel extends Mysql{

		private $db_name;

		public function __construct(){
			parent::__construct();
			$this->db_name=$this->getDbNameMysql();
		}

		public function selectRoles(){
			//EXTRAE ROLES
			$sql = "SELECT * FROM ". $this->db_name .".rol WHERE estado_logico != 0";
			$request = $this->select_all($sql);
			return $request;
		}

		public function selectRol(int $rol_id){
			//BUSCAR ROLE
			$sql = "SELECT * FROM ". $this->db_name .".rol WHERE rol_id = {$rol_id}";
			$request = $this->select($sql);
			return $request;
		}

		
		
		public function insertData(int $rol_id, string $rol_nombre, string $estado){

			$return = "";
			$sql = "SELECT * FROM ". $this->db_name .".rol WHERE rol_nombre= '{$rol_nombre}' ";
			$request = $this->select_all($sql);
			if(empty($request))//Si el Request es vacio inserta los datos
			{
				$query_insert  = "INSERT INTO ". $this->db_name .".rol (rol_nombre,estado_logico) VALUES(?,?)";
	        	$arrData = array($rol_nombre,$estado);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;//Retorna el Ultimo IDS
			}else{
				$return = "exist";//Restonra Mensaje si ya Existe en la tabla
			}
			return $return;
		}	

		public function updateData(int $rol_id, string $rol_nombre,int $estado){
			
			    $sql = "UPDATE ". $this->db_name .".rol SET rol_nombre = ?, estado_logico = ?,fecha_modificacion = CURRENT_TIMESTAMP() WHERE rol_id = {$rol_id} ";
				$arrData = array($rol_nombre,$estado);
				$request = $this->update($sql,$arrData);
			    return $request;			
		}

		public function deleteRol(int $rol_id){
				$sql = "UPDATE ". $this->db_name .".rol SET estado_logico = ? WHERE rol_id = {$rol_id} ";
				$arrData = array(0);
				$request = $this->update($sql,$arrData);
				if($request)
				{
					$request = 'ok';	
				}else{
					$request = 'error';
				}
			return $request;
		}

		


	}
 ?>