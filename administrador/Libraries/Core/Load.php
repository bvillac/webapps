<?php 
	$controller = ucwords($controller);
	$controllerFile = "Controllers/".$controller.".php";
	//putMessageLogFile($controller);//puede ver la ruta a la que accede
	if(file_exists($controllerFile)){
		require_once($controllerFile);//Requiere la ruta del archvio
		$controller = new $controller();//Crea la instancia del controlador
		if(method_exists($controller, $method))//Verifica si existe el metodo
		{
			$controller->{$method}($params);
		}else{
			require_once("Controllers/Error.php");
		}
	}else{
		require_once("Controllers/Error.php");
	}

 ?>