<?php
class ConAcademico{
	private $conect;
	private $db_name;

	public function __construct(){
		$connectionString = "mysql:host=".DB_HOST.";dbname=".DB_NAME_ACAD.";charset=".DB_CHARSET;
		try{
			$this->conect = new PDO($connectionString, DB_USER, DB_PASSWORD);
			$this->conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->db_name=DB_NAME_ACAD;
		    //echo "conexión exitosa";
		}catch(PDOException $e){
			$this->conect = 'Error de conexión';
		    echo "ERROR: " . $e->getMessage();
		}
	}

	public function conect(){
		return $this->conect;
	}
	public function getDbName(){
		return $this->db_name;
	}
}

?>