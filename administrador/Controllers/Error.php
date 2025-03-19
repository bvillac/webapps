<?php

class Errors extends Controllers
{
    public function __construct()
    {
        parent::__construct();

        if (empty($_SESSION['loginEstado'])) {
            header('Location: ' . base_url() . '/login');
            exit();
        }
        getPermisos();
    }

	/* COMO USAR 
	require_once("Errors.php");
	$errors = new Errors();
	$errors->notFound(403); // Muestra el error 403 (Acceso prohibido)*/
    public function notFound($code = 404)
    {
        // Definimos los mensajes de estado HTTP
        $errorMessages = [
            200 => "Respuesta exitosa", // OK
            201 => "Recurso creado con éxito", // Created
            400 => "Datos incorrectos o faltantes", // Bad Request
            401 => "Falta autenticación", // Unauthorized
            403 => "Sin permisos para acceder", // Forbidden
            404 => "Página No Encontrada", // Not Found
            500 => "Error en el servidor" // Internal Server Error
        ];

        // Obtener el mensaje según el código, con un mensaje predeterminado
        $data = [
            'code' => $code,
            'message' => $errorMessages[$code] ?? "Error desconocido"
        ];

        // Establecer el código de respuesta HTTP
        http_response_code($code);

        // Cargar la vista de error con los datos
        $this->views->getView($this, "error", $data);
    }
}

// Ejecutar la clase automáticamente
//(new Errors())->notFound();
?>
