<?php 
	
	class Views
	{
		function getView($controller,$view,$data="")
		{
			$controller = get_class($controller);
			if($controller == "Home"){//Si la condicion no se cumple el controlador no es Home
				$view = "Views/".$view.".php";//envia directamente al archivo sin concatenar el controlador
			}else{
				$view = "Views/".$controller."/".$view.".php";
			}
			require_once ($view);
		}
	}

 ?>