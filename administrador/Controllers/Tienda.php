<?php
require_once("Models/ClientePedidoModel.php");
require_once("Models/ArticuloModel.php");
class Tienda extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        sessionStart();
        getPermisos();
    }


    public function tienda()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        $modelCliente = new ClientePedidoModel();
        $data['cliente'] = $modelCliente->consultarClienteTienda();
        $data['page_tag'] = "Tienda";
        $data['page_name'] = "Tienda";
        $data['page_title'] = "Tienda <small> " . $_SESSION['empresaData']['NombreComercial'] . "</small>";
        $data['page_back'] = "Tienda";
        $this->views->getView($this, "tienda", $data);
    }

    public function consultarTienda()
    {
        if ($_SESSION['permisosMod']['r']) {
            $arrData = $this->model->consultarDatos();
            for ($i = 0; $i < count($arrData); $i++) {
                $btnOpciones = "";
                if ($arrData[$i]['Estado'] == 1) {
                    $arrData[$i]['Estado'] = '<span class="badge badge-success">Activo</span>';
                } else {
                    $arrData[$i]['Estado'] = '<span class="badge badge-danger">Inactivo</span>'; //target="_blanck"  
                }

                if ($_SESSION['permisosMod']['r']) {
                    $btnOpciones .= '<button class="btn btn-info btn-sm btnViewLinea" onClick="fntViewTienda(\'' . $arrData[$i]['Ids'] . '\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
                }
                if ($_SESSION['permisosMod']['u']) {
                    $btnOpciones .= '<button class="btn btn-primary  btn-sm btnEditLinea" onClick="editarTienda(\'' . $arrData[$i]['Ids'] . '\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
                }
                if ($_SESSION['permisosMod']['d']) {
                    $btnOpciones .= '<button class="btn btn-danger btn-sm btnDelLinea" onClick="fntDeleteTienda(' . $arrData[$i]['Ids'] . ')" title="Eliminar Datos"><i class="fa fa-trash"></i></button>';
                }
                if ($_SESSION['permisosMod']['r']) {
                    $btnOpciones .= ' <a title="Catálogo de Productos" href="' . base_url() . '/tienda/catalogo/' . $arrData[$i]['Ids'] . '"  class="btn btn-primary btn-sm"> <i class="fa fa-list"></i> </a> ';
                    $btnOpciones .= ' <a title="Catálogo de Productos" href="' . base_url() . '/tienda/precio/' . $arrData[$i]['Ids'] . '"  class="btn btn-primary btn-sm"> <i class="fa fa-coins"></i> </a> ';
                }

                $arrData[$i]['options'] = '<div class="text-center">' . $btnOpciones . '</div>';
            }
            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function ingresarTienda()
    {
        if ($_POST) {
            //dep($_POST);
            $data = recibirData($_POST['data']);
            if (empty($data['dataObj']) || empty($data['accion'])) {
                $arrResponse = array('status' => false, 'msg' => 'Error no se recibieron todos los datos necesarios');
            } else {
                $request = "";
                $datos = isset($data['dataObj']) ? $data['dataObj'] : array();
                $accion = isset($data['accion']) ? $data['accion'] : "";
                if ($accion == "Create") {
                    $option = 1;
                    if ($_SESSION['permisosMod']['w']) {
                        $request = $this->model->insertData($datos);
                    }
                } else {
                    $option = 2;
                    if ($_SESSION['permisosMod']['u']) {
                        $request = $this->model->updateData($datos);
                    }
                }
                if ($request["status"]) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'numero' => $request["numero"], 'msg' => 'Datos guardados correctamente.');
                    } else {
                        $arrResponse = array('status' => true, 'numero' => $request["numero"], 'msg' => 'Datos Actualizados correctamente.');
                    }
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }


    public function consultarTiendaId(int $ids)
    {
        if ($_SESSION['permisosMod']['r']) {
            $ids = intval(strClean($ids));
            if ($ids > 0) {
                $arrData = $this->model->consultarDatosId($ids);
                //dep($arrData);
                if (empty($arrData)) {
                    $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                } else {
                    $arrResponse = array('status' => true, 'data' => $arrData);
                }

                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }


    public function eliminarTienda()
    {
        if ($_POST) {

            if ($_SESSION['permisosMod']['d']) {
                $ids = intval($_POST['ids']);
                $request = $this->model->deleteRegistro($ids);
                if ($request) {
                    $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Registro');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Registro.');
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }

    public function bucarTiendaCentro()
    {
        if ($_POST) {
            if ($_SESSION['permisosMod']['r']) {
                $modelTienda = new TiendaModel();
                $ids = intval(strClean($_POST['Ids']));
                if ($ids > 0) {
                    $arrData = $modelTienda->consultarTiendaes($ids);
                    //dep($arrData);
                    if (empty($arrData)) {
                        $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                    } else {
                        $arrResponse = array('status' => true, 'data' => $arrData);
                    }
                    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                }
            }
        }
        die();
    }


    public function catalogo($ids)
    {
        if ($_SESSION['permisosMod']['r']) {
            if (is_numeric($ids)) {


                $data = $this->model->consultarDatosId($ids);
                if (empty($data)) {
                    echo "Datos no encontrados";
                } else {
                    putMessageLogFile($data);
                    //$modelCliente = new ClientePedidoModel();
                    //$data['cliente'] = $modelCliente->consultarClienteTienda();
                    $data['tienda'] = $this->model->consultarTiendaCliente($data['Cli_Ids']);
                    $data['nombreCliente'] = $data['RazonSocial'];

                    $data['page_tag'] = "Catálogo de Productos";
                    $data['page_name'] = "Catálogo de Productos";
                    $data['page_title'] = "Catálogo de Productos <small> " . $_SESSION['empresaData']['NombreComercial'] . "</small>";
                    $data['page_back'] = "tienda";
                    $this->views->getView($this, "catalogo", $data);
                }
            } else {
                echo "Dato no válido";
            }
        } else {
            header('Location: ' . base_url() . '/login');
            die();
        }
        die();
    }

    public function precio($ids)
    {
        if ($_SESSION['permisosMod']['r']) {
            if (is_numeric($ids)) {


                $data = $this->model->consultarDatosId($ids);
                if (empty($data)) {
                    echo "Datos no encontrados";
                } else {
                    //$modelCliente = new ClientePedidoModel();
                    //$data['cliente'] = $modelCliente->consultarClienteTienda();
                    $data['tienda'] = $this->model->consultarTiendaCliente($data['Cli_Ids']);
                    $data['nombreCliente'] = $data['RazonSocial'];

                    $data['page_tag'] = "Tienda Precio de Productos";
                    $data['page_name'] = "Tienda Precio de Productos";
                    $data['page_title'] = "Tienda Precio de Productos <small> " . $_SESSION['empresaData']['NombreComercial'] . "</small>";
                    $data['page_back'] = "tienda";
                    $this->views->getView($this, "precio", $data);
                }
            } else {
                echo "Dato no válido";
            }
        } else {
            header('Location: ' . base_url() . '/login');
            die();
        }
        die();
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
            $request = $modelArticulo->retornarBusArticulo($parametro,  $limit);

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
            putMessageLogFile($data);
    
            if (!isset($data['productos']) || !is_array($data['productos'])) {
                throw new Exception("Datos inválidos");
            }
    
            $modelArticulo = new ArticuloModel();
            $modelArticulo->guardarProducto($data);
            //foreach ($data['productos'] as $producto) {
            //    $modelArticulo->guardarProducto($producto);
            //}
    
            echo json_encode(["status" => true, "msg" => "Productos guardados correctamente"]);
        } catch (Exception $e) {
            echo json_encode(["status" => false, "msg" => $e->getMessage()]);
        }
    }
    




}
