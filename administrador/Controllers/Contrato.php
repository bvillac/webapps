<?php
class Contrato extends Controllers
{
    public function __construct()
	{
		parent::__construct();
		session_start();
		session_regenerate_id(true);
		if (empty($_SESSION['loginEstado'])) {
			header('Location: ' . base_url() . '/login');
			die();
		}
		//getPermisos(4);
	}


    public function contrato()
	{
		/*if(empty($_SESSION['permisosMod']['r'])){
				header("Location:".base_url().'/dashboard');
			}*/
		$data['page_tag'] = "Contrato";
		$data['page_name'] = "Contrato";
		$data['page_title'] = "Contrato <small> " . TITULO_EMPRESA . "</small>";
		$data['fileJS'] = "funcionesContrato.js";
		$this->views->getView($this, "contrato", $data);
	}

    public function nuevo()
	{
		/*if(empty($_SESSION['permisosMod']['r'])){
				header("Location:".base_url().'/dashboard');
			}*/
		$data['page_tag'] = "Nuevo Contrato";
		$data['page_name'] = "Nuevo Contrato";
		$data['page_title'] = "Nuevo Contrato <small> " . TITULO_EMPRESA . "</small>";
		$data['fileJS'] = "funcionesContrato.js";
		$this->views->getView($this, "nuevo", $data);
	}
}
?>