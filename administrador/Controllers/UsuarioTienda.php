<?php
require_once("Models/TiendaModel.php");
require_once("Models/UsuariosModel.php");
require_once("Models/ClientePedidoModel.php");

class UsuarioTienda extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        sessionStart();
        getPermisos();

    }


    public function usuariotienda()
    {
        checkPermission('r', 'dashboard');
        $data = getPageData("Usuario Tienda", "usuariotienda");
        $data['roles'] = (new UsuariosModel())->consultarRolEmpresa();
        $data['cliente'] = (new ClientePedidoModel())->consultarClienteTienda();
        $data['tienda'] =[];
        $this->views->getView($this, "usuariotienda", $data);
    }

    

    public function consultarUsuarioTienda()
    {
        checkPermission('r', 'dashboard');
        //$data['cliente'] = (new ClientePedidoModel())->consultarClienteTienda();
        //$arrData = $this->model->consultarDatos();
        $arrData = (new TiendaModel())->consultarUsuarioTienda(array());
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
        // if ($_SESSION['permisosMod']['r']) {
        //     $options .= '<button class="btn btn-info btn-sm btnViewLinea" onClick="fntViewTienda(\'' . $id . '\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
        // }
        if ($_SESSION['permisosMod']['u']) {
            $options .= '<button class="btn btn-primary  btn-sm btnEditLinea" onClick="editarTienda(\'' . $id . '\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
        }
        if ($_SESSION['permisosMod']['d']) {
            $options .= " <button class='btn btn-danger btn-sm btnDelLinea' onClick='fntDeleteTienda($id)' title='Eliminar'><i class='fa fa-trash'></i></button> ";
        }
        //$options .= " <a title='Catálogo' href='" . base_url() . "/tienda/catalogo/$id' class='btn btn-primary btn-sm'><i class='fa fa-list'></i></a> ";
        return $options . '</div>';
    }

   
    public function ingresarUsuarioTienda()
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
                        $request = (new TiendaModel())->insertUserTienda($datos );
                    }
                } else {
                    $option = 2;
                    if ($_SESSION['permisosMod']['u']) {
                        $request = $this->model->updateData($datos);
                    }
                }
                if ($request["status"]) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'numero' => 0, 'msg' => 'Datos guardados correctamente.');
                    } else {
                        $arrResponse = array('status' => true, 'numero' => 0, 'msg' => 'Datos Actualizados correctamente.');
                    }
                } else {
                    $arrResponse = array("status" => false, "msg" => $request["message"]);
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        exit();
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
        exit();
    }

 



}
