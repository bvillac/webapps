<?php 

	class Home extends Controllers{
		public function __construct()
		{
			parent::__construct();
		}

		public function home(){
			//$data['page_id'] = 1;
			$data['page_tag'] = $_SESSION['empresaData']['NombreComercial'];
			$data['page_title'] = $_SESSION['empresaData']['NombreComercial'];
			$data['page_name'] = "home";
			//$data['page_content'] = "Informacion de la Pagina Princial";
			$this->views->getView($this,"home",$data);
		}

	}
 ?>