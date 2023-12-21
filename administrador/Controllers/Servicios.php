<?php
class Servicios extends Controllers{

	public function __construct()
	{
		parent::__construct();
        sessionStart();
        getPermisos();
	}

	public function nosotros(){

		$data['page_tag'] = "Contacto";
		$data['page_name'] = "Contacto";
		$data['page_title'] = "Contacto <small> " . TITULO_EMPRESA . "</small>";
		$this->views->getView($this, "nosotros", $data);
	}

	public function soporte(){

		$data['page_tag'] = "Soporte";
		$data['page_name'] = "Soporte";
		$data['page_title'] = "Soporte <small> " . TITULO_EMPRESA . "</small>";
		$this->views->getView($this, "soporte", $data);
	}

	public function consultoria(){
		$data['page_tag'] = "Consultoria";
		$data['page_name'] = "Consultoria";
		$data['page_title'] = "Consultoria <small> " . TITULO_EMPRESA . "</small>";
		$this->views->getView($this, "consultoria", $data);
	}

	public function sistemasea(){
		$data['page_tag'] = "Sistema SEA";
		$data['page_name'] = "Sistema SEA";
		$data['page_title'] = "SEA <small> " . TITULO_EMPRESA . "</small>";
		$this->views->getView($this, "sistemasea", $data);
	}
}
