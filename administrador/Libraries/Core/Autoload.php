<?php 
	//Registra los controladores en la carpeta librarries cuando se crea el contructor Controllers
	//Se ejecuta cuando se instancia cualquier clase
	spl_autoload_register(function($class){
		if(file_exists("Libraries/".'Core/'.$class.".php")){//Si existe el archivo lo requiere
			require_once("Libraries/".'Core/'.$class.".php");
		}
	});
 ?>