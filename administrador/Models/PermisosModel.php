<?php 

	class PermisosModel extends Mysql{
		public $intIdpermiso;
		public $intRolid;
		public $intModuloid;
		public $r;
		public $w;
		public $u;
		public $d;

		public function __construct(){
			parent::__construct();
		}

		public function selectModulos(){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT * FROM ". $db_name .".modulo WHERE estado_logico != 0";
			$request = $this->select_all($sql);
			return $request;
		}	
		public function selectPermisosRol(int $ids){
			$db_name=$this->getDbNameMysql();
			$sql = "SELECT * FROM ". $db_name .".permisos WHERE rol_id = {$ids}";
			$request = $this->select_all($sql);
			return $request;
		}

		public function deletePermisos(int $ids){
			$db_name=$this->getDbNameMysql();
			$sql = "DELETE FROM ". $db_name .".permisos WHERE rol_id = {$ids}";
			$request = $this->delete($sql);
			return $request;
		}

		public function insertPermisos(int $rol_id, int $mod_id, int $r, int $w, int $u, int $d){
			$db_name=$this->getDbNameMysql();
			$usuario=1;
			$query_insert  = "INSERT INTO  ". $db_name .".permisos(mod_id,rol_id,r,w,u,d,estado_logico,usuario_creacion) VALUES(?,?,?,?,?,?,?,?)";
        	$arrData = array($mod_id,$rol_id, $r, $w, $u, $d,1,$usuario);
        	$request_insert = $this->insert($query_insert,$arrData);		
	        return $request_insert;
		}

		
	}
 ?>