<?php
class GlobalVariablesManager {
    private static $instance;
    private $globals = array();

    private function __construct() {
        // Aquí puedes inicializar tus variables globales si es necesario
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new GlobalVariablesManager();
        }
        return self::$instance;
    }

    public function setGlobal($name, $value) {
        $this->globals[$name] = $value;
    }

    public function getGlobal($name) {
        return isset($this->globals[$name]) ? $this->globals[$name] : null;
    }
}

// Uso de la clase GlobalVariablesManager
//$manager = GlobalVariablesManager::getInstance();

// Establecer una variable global
//$manager->setGlobal('variable_global', 10);

// Acceder a la variable global desde cualquier lugar
//echo $manager->getGlobal('variable_global'); // Imprimirá "10"
?>
