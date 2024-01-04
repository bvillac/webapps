<?php

class Errors extends Controllers
{
	public function __construct()
	{
		parent::__construct();
		//sessionStart();	
		if (empty($_SESSION['loginEstado'])) {
			header('Location: ' . base_url() . '/login');
			die();
		}
		getPermisos();
	}

	public function notFound()
	{
		//$this->views->getView($this, "error");
		$this->views->getView($this, "error", null);
	}
}

$notFound = new Errors();
$notFound->notFound();
?>