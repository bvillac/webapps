<?php
use Spipu\Html2Pdf\Html2Pdf;
require 'vendor/autoload.php';
require_once("Models/PagoModel.php");
require_once("Models/UsuariosModel.php");
require_once("Models/ArticuloModel.php");

class ClientePedido extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        sessionStart();
        getPermisos();
    }

    public function clientepedido()
    {
        checkPermission('r', 'dashboard');
        $data = getPageData("Cliente", "clientepedido");
        $this->views->getView($this, "clientepedido", $data);
    }

    public function getClientes()
    {
        if ($_SESSION['permisosMod']['r']) {
            $arrData = $this->model->consultarDatos([]);
            foreach ($arrData as &$cliente) {
                $cliente['Estado'] = $cliente['Estado'] == 1 
                    ? '<span class="badge badge-success">Activo</span>' 
                    : '<span class="badge badge-danger">Inactivo</span>';
                $cliente['options'] = $this->getClientOptions($cliente['Ids']);
            }
            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    private function getClientOptions($id)
    {
        $options = '<div class="text-center">';
        if ($_SESSION['permisosMod']['u']) {
            $options .= " <a title='Editar' href='" . base_url() . "/clientePedido/editar/$id' class='btn btn-primary btn-sm'><i class='fa fa-pencil'></i></a> ";
        }
        if ($_SESSION['permisosMod']['d']) {
            $options .= " <button class='btn btn-danger btn-sm' onClick='fntDeleteCliente($id)' title='Eliminar'><i class='fa fa-trash'></i></button> ";
        }
        $options .= " <a title='Catálogo' href='" . base_url() . "/clientePedido/catalogo/$id' class='btn btn-primary btn-sm'><i class='fa fa-list'></i></a> ";
        return $options . '</div>';
    }

    public function nuevo()
    {
        checkPermission('r', 'dashboard');
        $data = getPageData("Cliente", "clientepedido");
        $data['usuario_rol'] = (new UsuariosModel())->consultarRoles();
        $data['forma_pago'] = (new PagoModel())->consultarPago();
        $this->views->getView($this, "nuevo", $data);
    }

    public function editar($id)
    {
        if (!is_numeric($id)) die("Dato no válido");
        checkPermission('r', 'login');
        $data = $this->model->consultarDatosId($id);
        if (empty($data)) die("Datos no encontrados");
        $data['forma_pago'] = (new PagoModel())->consultarPago();
        $data = array_merge($data, getPageData("Editar Cliente", "clientepedido"));
        $this->views->getView($this, "editar", $data);
    }

    public function ingresarCliente()
    {
        $this->processFormRequest(function ($datos, $accion) {
            $modelMethod = $accion == "Create" ? 'insertData' : 'updateData';
            return $this->model->$modelMethod($datos);
        });
    }

    public function delCliente()
    {
        if ($_SESSION['permisosMod']['d']) {
            $id = intval($_POST['ids'] ?? 0);
            echo json_encode(["status" => $this->model->deleteRegistro($id), "msg" => "Registro eliminado"]);
        }
        die();
    }

    public function generarReporteClientesPDF()
    {
        checkPermission('r', 'login');
        $data['Result'] = $this->model->consultarDatos(['estado' => '1']);
        if (empty($data)) die("Datos no encontrados");
        $data['Titulo'] = "Lista Clientes Activos";
        ob_end_clean();
        $html = getFile("ClienteMiller/clientePDF", $data);
        $pdf = new Html2Pdf('p', 'A4', 'es', true, 'UTF-8');
        $pdf->writeHTML($html);
        $pdf->output("ReporteClientes_" . date('m-d-Y_His') . ".pdf", 'D');
    }

    

    

    private function processFormRequest($callback)
    {
        if ($_POST) {
            $datos = json_decode($_POST['dataObj'] ?? '{}', true);
            $accion = $_POST['accion'] ?? "";
            if (empty($datos) || empty($accion)) {
                die(json_encode(["status" => false, "msg" => "Datos incorrectos"]));
            }
            $request = $callback($datos, $accion);
            echo json_encode([
                "status" => $request["status"],
                "numero" => $request["status"] ? ($accion == "Create" ? $request["numero"] : 0) : "",
                "msg" => $request["status"] ? "Datos guardados correctamente" : "Error al guardar datos"
            ]);
        }
        die();
    }

    public function buscarAutoCliente()
	{
		if ($_POST) {
			//dep($_POST);
			$Buscar = isset($_POST['buscar']) ? $_POST['buscar'] : "";
			$request = $this->model->consultarDatosCedulaNombres($Buscar);
			if ($request) {
				$arrResponse = array('status' => true, 'data' => $request, 'msg' => 'Datos Retornados correctamente.');
			} else {
				$arrResponse = array('status' => false, 'msg' => 'No Existen Datos');
			}
			//putMessageLogFile($arrResponse);	
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

    public function eliminarItemCliente()
    {
        try {
            // Validar permisos de Eliminacion
            if (!isset($_SESSION['permisosMod']['d'])) {
                header('Location: ' . base_url() . '/login');
                exit;
            }
            $inputData=validarMetodoPost();  
            // Sanitizar y obtener los valores con seguridad
            //$parametro = isset($inputData['ids']) ? filter_var($inputData['parametro'], FILTER_SANITIZE_STRING) : "";
            $cli_id = isset($inputData['idsCliente']) ? filter_var($inputData['idsCliente'], FILTER_VALIDATE_INT) : 0;
            $ids = isset($inputData['ids']) ? filter_var($inputData['ids'], FILTER_VALIDATE_INT) : 0;
    
            // Validar que `ids` sea un número válido
            if (!is_numeric($ids) || $ids <= 0) {
                logFileSystem("Error ids es invalido: " ,"WARNING");
                exit;
            }

            $modelArticulo = new ArticuloModel();
            $request = $modelArticulo->eliminarItemCliente($ids,$cli_id);
            if ($request) {
                $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Registro');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Registro.');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);

    
        } catch (Exception $e) {
            // Registrar error en log
            logFileSystem("Error en consutla Catalogo: " . $e->getMessage(),"ERROR");
            exit;
        }
    }

    public function catalogo($ids) {
        try {
            // Validar permisos de lectura
            if (!isset($_SESSION['permisosMod']['r'])) {
                header('Location: ' . base_url() . '/login');
                exit;
            }
    
            // Validar que `ids` sea un número válido
            if (!is_numeric($ids) || $ids <= 0) {
                //throw new Exception("ID inválido.");
                logFileSystem("Error Id es invalido: " ,"WARNING");
                exit;
            }
    
            // Consultar datos del cliente
            $data = $this->model->consultarDatosId($ids);
    
            // Consultar productos del cliente
            $modelArticulo = new ArticuloModel();
            $data['ClienteProducto'] = $modelArticulo->consultarProductosCliente($data['Ids']);
            $data['nombreCliente'] = htmlspecialchars($data['Nombre'], ENT_QUOTES, 'UTF-8');
            // Datos para la vista
            $data['page_tag'] = "Catálogo de Productos";
            $data['page_name'] = "Catálogo de Productos";
            $data['page_title'] = "Catálogo de Productos <small> " . htmlspecialchars($_SESSION['empresaData']['NombreComercial'], ENT_QUOTES, 'UTF-8') . "</small>";
            $data['page_back'] = "tienda";
    
            // Cargar vista
            $this->views->getView($this, "catalogo", $data);
    
        } catch (Exception $e) {
            // Registrar error en log
            logFileSystem("Error en consutla Catalogo: " . $e->getMessage(),"ERROR");
            exit;
        }
    }


    public function buscarAutoProducto()
    {
        try {
            $inputData=validarMetodoPost();           

            // Sanitizar y obtener los valores con seguridad
            $parametro = isset($inputData['parametro']) ? filter_var($inputData['parametro'], FILTER_SANITIZE_STRING) : "";
            //$cli_id = isset($inputData['cli_id']) ? filter_var($inputData['cli_id'], FILTER_VALIDATE_INT) : null;
            //$tie_id = isset($inputData['tie_id']) ? filter_var($inputData['tie_id'], FILTER_VALIDATE_INT) : null;
            $limit = isset($inputData['limit']) ? filter_var($inputData['limit'], FILTER_VALIDATE_INT) : 10;

            // Validar parámetros obligatorios
            //if (!$cli_id || !$tie_id) {
            //    throw new Exception("Parámetros insuficientes", 400);
            //}

            // Instancia del modelo y consulta
            $modelArticulo = new ArticuloModel();
            $request = $modelArticulo->retornarBusArticulo($parametro,  LIMIT_SQL);

            // Responder con los datos obtenidos o mensaje de error
            $arrResponse = $request
                ? ['status' => true, 'data' => $request, 'msg' => 'Datos retornados correctamente.']
                : ['status' => false, 'msg' => 'No existen datos.'];
        } catch (Exception $e) {
            // Manejo de errores
            $arrResponse = ['status' => false, 'msg' => $e->getMessage()];
            putMessageLogFile($arrResponse);
            http_response_code($e->getCode() ?: 500);
        }

        // Responder con JSON
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function guardarListaProductos() {
        try {
            $json = file_get_contents("php://input");
            $data = json_decode($json, true);

            if (empty($data['productos']) || !is_array($data['productos']) || 
                empty($data['accion']) || !isset($data['idsCliente']) || 
                !filter_var($data['idsCliente'], FILTER_VALIDATE_INT)) {
                        $arrResponse = ["status" => false, "msg" => "Datos inválidos."];
            } else {
                // Procesar la lógica cuando los datos son válidos
                $cli_id = isset($data['idsCliente']) ? filter_var($data['idsCliente'], FILTER_VALIDATE_INT) : 0;
                $productos = isset($data['productos']) ? $data['productos'] : array();
                $accion = isset($data['accion']) ? $data['accion'] : "";
                if ($accion == "Create") {
                    $option = 1;
                    if ($_SESSION['permisosMod']['w']) {
                        //$request = $this->model->insertData($datos);
                        $modelArticulo = new ArticuloModel();
                        $request=$request=$modelArticulo->guardarProductosCliente($productos,$cli_id );
                    }
                } else {
                    $option = 2;
                    if ($_SESSION['permisosMod']['u']) {
                        //$request = $this->model->updateData($datos);
                    }
                }
                if ($request["status"]) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'numero' => $request["numero"], 'msg' => 'Datos guardados correctamente.');
                    } else {
                        $arrResponse = array('status' => true, 'numero' => 0, 'msg' => 'Datos Actualizados correctamente.');
                    }
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos: ' . $request["message"]);
                }
            }

            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            logFileSystem("Error en guardarListaProductos: " . $e->getMessage(), "ERROR");
            echo json_encode(["status" => false, "msg" => $e->getMessage()]);
        }
    }

}