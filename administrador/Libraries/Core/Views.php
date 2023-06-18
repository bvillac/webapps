<?php

class Views
{
	function getView($controller, $view, $data = "")
	{
		$controller = get_class($controller);
		if ($controller == "Home") { //Si la condicion no se cumple el controlador no es Home
			$view = "Views/" . $view . ".php"; //envia directamente al archivo sin concatenar el controlador
		} else {
			$view = "Views/" . $controller . "/" . $view . ".php";
			//$this->getJS("Views/" . $controller . "/js/");
		}
		require_once($view);
	}

	function getJS($directorio){
		//$directorio = "js/";
		$archivos = scandir($directorio);

		/*foreach ($archivos as $archivo) {
			if (pathinfo($archivo, PATHINFO_EXTENSION) == 'js') {
				$prefijo = session_id() . "_";
				$destino = tempnam("Assets/temp", $prefijo);
				copy($directorio . $archivo, $destino);
				echo "<script src='" . $destino . "'></script>";
			}
		}*/

		foreach ($archivos as $archivo) {
			if (pathinfo($archivo, PATHINFO_EXTENSION) == 'js') {
			  $archivo_origen = $directorio . $archivo;
			  $archivo_destino = "Assets/temp/js/" . md5($archivo) . "/".$archivo.".js";
		  
			  // Verificar si el archivo ya existe en la carpeta temporal
			  if (file_exists($archivo_destino)) {
				// Verificar si el archivo ha sido modificado desde la última vez que se importó
				if (filemtime($archivo_origen) > filemtime($archivo_destino)) {
				  // Si el archivo ha sido modificado, copiarlo a la carpeta temporal
				  copy($archivo_origen, $archivo_destino);
				}
			  } else {
				// Si el archivo no existe en la carpeta temporal, copiarlo
				copy($archivo_origen, $archivo_destino);
			  }
		  
			  // Imprimir la etiqueta <script> para importar el archivo JavaScript
			  echo "<script src='" . $archivo_destino . "'></script>";
			}
		  }

	}
}
