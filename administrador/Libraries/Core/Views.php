<?php

class Views
{
	function getView($controller, $view, $data = "")
	{
		$controller = get_class($controller);
		//putMessageLogFile($controller);
		if ($controller == "Home") { //Si la condicion no se cumple el controlador no es Home
			$view = "Views/" . $view . ".php"; //envia directamente al archivo sin concatenar el controlador
		} else {
			$view = "Views/" . $controller . "/" . $view . ".php";
			//$this->getJS("Views/" . $controller . "/js/");
		}
		require_once($view);
	}

	
}
