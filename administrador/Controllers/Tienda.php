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
        checkPermission('r', 'dashboard');
        $data = getPageData("Tienda", "Tienda");
        $data['cliente'] = (new ClientePedidoModel())->consultarClienteTienda();
        $this->views->getView($this, "tienda", $data);
    }

    

    public function consultarTienda()
    {
        checkPermission('r', 'dashboard');
        $arrData = $this->model->consultarDatos([]);
        foreach ($arrData as &$objData) {
            $objData['Estado'] = $objData['Estado'] == 1
                ? '<span class="badge badge-success">Activo</span>'
                : '<span class="badge badge-danger">Inactivo</span>';
            $objData['options'] = $this->getArrayOptions($objData['Ids']);
        }
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        exit();
    }

    private function getArrayOptions($id)
    {
        $options = '<div class="text-center">';
        if ($_SESSION['permisosMod']['r']) {
            $options .= '<button class="btn btn-info btn-sm btnViewLinea" onClick="fntViewTienda(\'' . $id . '\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
        }
        if ($_SESSION['permisosMod']['u']) {
            $options .= '<button class="btn btn-primary  btn-sm btnEditLinea" onClick="editarTienda(\'' . $id . '\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
        }
        if ($_SESSION['permisosMod']['d']) {
            $options .= " <button class='btn btn-danger btn-sm btnDelLinea' onClick='fntDeleteTienda($id)' title='Eliminar'><i class='fa fa-trash'></i></button> ";
        }
        $options .= " <a title='Catálogo' href='" . base_url() . "/tienda/catalogo/$id' class='btn btn-primary btn-sm'><i class='fa fa-list'></i></a> ";
        return $options . '</div>';
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

    public function catalogo($ids) {
        try {
            checkPermission('r', 'dashboard');
            // Validar que `ids` sea un número válido
            if (!is_numeric($ids) || $ids <= 0) {
                logFileSystem("Error Id es invalido: " ,"WARNING");
                exit;
            }
            // Datos para la vista
            $data = getPageData("Catálogo de Productos", "tienda");
    
            // Consultar datos del cliente
            $data = $this->model->consultarDatosId($ids);
            putMessageLogFile($data);
    
            // Consultar productos del cliente
            $data['TiendaId']=$ids;
            $data['tiendas'] = $this->model->consultarTiendaCliente($data['Cli_Ids']);
            $data['ClienteProducto'] = (new ArticuloModel())->consultarProductosCliente($data['Cli_Ids']);
            $data['nombreCliente'] = htmlspecialchars($data['RazonSocial'], ENT_QUOTES, 'UTF-8');
          
            // Cargar vista
            $this->views->getView($this, "catalogo", $data);
    
        } catch (Exception $e) {
            // Registrar error en log
            logFileSystem("Error en consutla Catalogo: " . $e->getMessage(),"ERROR");
            exit;
        }
    }


        




}
