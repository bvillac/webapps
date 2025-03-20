<?php
// Registra los controladores en la carpeta Libraries/Core cuando se instancia una clase
spl_autoload_register(function ($class) {
    // Definir la ruta base para la carga automática de clases
    $filePath = "Libraries/Core/{$class}.php";

    // Verificar si el archivo existe antes de requerirlo
    if (file_exists($filePath)) {
        require_once $filePath;
    } else {
        // Manejo de error: registrar en log o lanzar excepción
		putMessageLogFile("Autoload.php Clase '{$class}' no encontrada en '{$filePath}'.");
		//logFileSystem("Clase '{$class}' no encontrada en '{$filePath}'.","ERROR");
        //throw new Exception("Error: No se pudo cargar la clase {$class}.");
    }
});
?>
