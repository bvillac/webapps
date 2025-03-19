<?php
class Controllers
{
    protected $views;
    protected $model;

    public function __construct()
    {
        // Inicialización de la vista
        $this->views = new Views();

        // Intentar cargar el modelo correspondiente
        $this->loadModel();
    }

    /**
     * Carga el modelo correspondiente a este controlador.
     */
    public function loadModel()
    {
        // Generar el nombre del modelo en base al nombre de la clase del controlador
        $modelName = get_class($this) . "Model";

        // Establecer la ruta del archivo del modelo
        $modelPath = "Models/" . $modelName . ".php";

        // Verificar si el archivo del modelo existe
        if (file_exists($modelPath)) {
            require_once($modelPath);

            // Instanciar el modelo
            $this->model = new $modelName();
        } else {
            // Manejo de error si el modelo no se encuentra
            // Puedes lanzar una excepción, registrar el error, o manejarlo de otra manera
			putMessageLogFile("Modelo '$modelName' no encontrado en '$modelPath'.");
			//logFileSystem("Modelo '$modelName' no encontrado en '$modelPath'.","ERROR");
			//throw new Exception("Modelo no encontrado: " . $modelName);
        }
    }
}