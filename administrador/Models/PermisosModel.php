<?php 

	class PermisosModel extends Mysql{
		private $db_name;
		public $intIdpermiso;
		public $intRolid;
		public $intModuloid;
		public $r;
		public $w;
		public $u;
		public $d;

		public function __construct(){
			parent::__construct();
			$this->db_name = $this->getDbNameMysql();
		}

		public function selectModulos(){
			$sql = "SELECT * FROM " . $this->db_name . ".modulo WHERE estado_logico != 0";
			$request = $this->select_all($sql);
			return $request;
		}	
		public function selectPermisosRol(int $ids){
			$sql = "SELECT * FROM " . $this->db_name . ".permiso WHERE rol_id = {$ids}";
			$request = $this->select_all($sql);
			return $request;
		}

		public function deletePermisos(int $ids){
			$usuId = $_SESSION['idsUsuario'];
			$empId = $_SESSION['idEmpresa'];
			$sql = "DELETE FROM " . $this->db_name . ".permiso WHERE rol_id = {$ids} AND usu_id={$usuId} AND emp_id={$empId}";
			$request = $this->delete($sql);
			return $request;
		}

		public function insertPermisos(int $rol_id, int $mod_id, int $r, int $w, int $u, int $d){
			$usuId = $_SESSION['idsUsuario'];
			$empId = $_SESSION['idEmpresa'];
			$usuario = retornaUser();
			$query_insert  = "INSERT INTO  " . $this->db_name . ".permiso 
								(usu_id,mod_id,emp_id,rol_id,r,w,u,d,estado_logico,usuario_creacion)
								 VALUES(?,?,?,?,?,?,?,?,?,?)";
        	$arrData = array($usuId,$mod_id,$empId,$rol_id, $r, $w, $u, $d,1,$usuario);
        	$request_insert = $this->insert($query_insert,$arrData);		
	        return $request_insert;
		}

		
	}
 ?>