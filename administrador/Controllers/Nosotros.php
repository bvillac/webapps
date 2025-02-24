<?php 
	class Nosotros extends Controllers{
		public function __construct(){
			parent::__construct();
			session_start();
			//getPermisos(MDPAGINAS);
		}

		public function nosotros(){

                $data['page_tag'] = "Contacto";
                $data['page_name'] = "Contacto";
                $data['page_title'] = "Contacto <small> ".$_SESSION['empresaData']['NombreComercial'] ."</small>";
				$this->views->getView($this,"nosotros",$data);  
			/*$pageContent = getPageRout('nosotros');
			if(empty($pageContent)){
				header("Location: ".base_url());
			}else{
				//$data['page'] = $pageContent;
                $data['page_tag'] = "Contacto";
                $data['page_name'] = "Contacto";
                $data['page_title'] = "Usuarios <small> ".$_SESSION['empresaData']['NombreComercial'] ."</small>";
                //$data['fileJS'] = "funcionesUsuarios.js";
				$this->views->getView($this,"nosotros",$data);  
			}*/

		}

	}
 ?>
