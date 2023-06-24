<?php 

	class Home extends Controllers{
		public function __construct()
		{
			parent::__construct();
		}

		public function home(){
			//$data['page_id'] = 1;
			$data['page_tag'] = TITULO_EMPRESA;
			$data['page_title'] = TITULO_EMPRESA;
			$data['page_name'] = "home";
			//$data['page_content'] = "Informacion de la Pagina Princial";
			$this->views->getView($this,"home",$data);
		}

	}
 ?>