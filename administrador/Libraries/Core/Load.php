<?php 
require_once("Controllers/Error.php");
try {
    $controller = ucwords($controller);
    $controllerFile = "Controllers/" . $controller . ".php";
	//putMessageLogFile($controller);//puede ver la ruta a la que accede
	//putMessageLogFile($controllerFile);
    if (file_exists($controllerFile)) {
        require_once($controllerFile);
        if (class_exists($controller)) {
            $instance = new $controller();
            if (method_exists($instance, $method)) {
                $instance->{$method}($params);
                exit();
            }
        }
    } 

    // Si el archivo, clase o método no existen, carga el controlador de error
	//putMessageLogFile("Archivo, clase o método no existen: ".$controllerFile);
    //require_once("Controllers/Error.php");
	(new Errors())->notFound(404);

} catch (Exception $e) {
	putMessageLogFile("Error en la carga del controlador: " . $e->getMessage());
	//logFileSystem("Error en la carga del controlador: " . $e->getMessage(),"ERROR");
    //require_once("Controllers/Error.php");
	(new Errors())->notFound(500);
}
?>
