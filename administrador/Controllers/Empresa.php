<?php
require_once("Models/EmpresaModel.php");
require_once("Models/RolesModel.php");
require_once("Models/ModuloModel.php");
require_once("Models/UsuariosEmpresaModel.php");
class Empresa extends Controllers
{

    public function __construct()
    {
        parent::__construct();
        sessionStart();
        getPermisos();
    }

    public function empresa()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url() . '/dashboard');
        }
        $data['moneda'] = $this->model->consultarMoneda();
        $data['page_tag'] = 'Empresa';
        $data['page_name'] = 'Empresa';
        $data['page_title'] = 'Empresa <small> ' . $_SESSION['empresaData']['NombreComercial'] . '</small>';
        $data['page_back'] = 'empresa';
        $this->views->getView($this, 'empresa', $data);
    }

    public function getEmpresas()
    {
        if ($_SESSION['permisosMod']['r']) {
            //$model = new EmpresaModel();
            $arrData = $this->model->consultarDatos();
            for (
                $i = 0;
                $i < count($arrData);
                $i++
            ) {
                $btnOpciones = '';
                if ($arrData[$i]['Estado'] == 1) {
                    $arrData[$i]['Estado'] = '<span class="badge badge-success">Activo</span>';
                } else {
                    $arrData[$i]['Estado'] = '<span class="badge badge-danger">Inactivo</span>';
                }

                if ($_SESSION['permisosMod']['r']) {
                    $btnOpciones .= '<button class="btn btn-info btn-sm btnViewEmpresa" onClick="fntViewEmpresa(\'' . $arrData[$i]['Ids'] . '\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
                }
                if ($_SESSION['permisosMod']['u']) {
                    $btnOpciones .= '<button class="btn btn-primary  btn-sm btnEditEmpresa" onClick="fntEditEmpresa(\'' . $arrData[$i]['Ids'] . '\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
                }
                if ($_SESSION['permisosMod']['d']) {
                    $btnOpciones .= '<button class="btn btn-danger btn-sm btnDelEmpresa" onClick="fntDeleteEmpresa(' . $arrData[$i]['Ids'] . ')" title="Eliminar Datos"><i class="fa fa-trash"></i></button>';
                }
                if ($_SESSION['permisosMod']['r']) {
                    $btnOpciones .= '<a href="' . base_url() . '/Empresa/getEmpresaModulos/' . base64_encode($arrData[$i]['Ids']) . '"  class="btn btn-primary btn-sm" title="Modulo Empresa Datos"> <i class="fa fa-th"></i> </a> ';
                }
                $arrData[$i]['options'] = '<div class="text-center">' . $btnOpciones . '</div>';
            }
            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getMoneda()
    {
        $model = new EmpresaModel();
        $htmlOptions = '';
        $arrData = $model->consultarMoneda();
        if (count($arrData) > 0) {
            $htmlOptions = '<option value="0">SELECCIONAR</option>';
            for (
                $i = 0;
                $i < count($arrData);
                $i++
            ) {
                $htmlOptions .= '<option value="' . $arrData[$i]['Ids'] . '">' . $arrData[$i]['Nombre'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }

    public function getEmpresa(int $ids)
    {
        if ($_SESSION['permisosMod']['r']) {
            $ids = intval(strClean($ids));
            //$model = new EmpresaModel();
            if ($ids > 0) {
                $arrData = $this->model->consultarDatosId($ids);
                //dep( $arrData );
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

    public function setEmpresa()
    {
        //dep( $_POST );
        if ($_POST) {
            $model = new EmpresaModel();
            if (
                empty($_POST['txt_emp_ruc']) || empty($_POST['txt_emp_razon_social']) || empty($_POST['txt_emp_nombre_comercial']) || empty($_POST['txt_emp_direccion']) ||
                empty($_POST['txt_emp_correo']) || empty($_POST['txt_emp_ruta_logo'])
            ) {
                $arrResponse = array('status' => false, 'msg' => 'Datos incorrectos.');
            } else {
                $Ids = intval($_POST['txth_ids']);
                $ruc = strClean($_POST['txt_emp_ruc']);
                $razon = strClean($_POST['txt_emp_razon_social']);
                $nombre = strClean($_POST['txt_emp_nombre_comercial']);
                $direccion = strClean($_POST['txt_emp_direccion']);
                $correo = strClean($_POST['txt_emp_correo']);
                $logo = strClean($_POST['txt_emp_ruta_logo']);
                $moneda = intval($_POST['cmb_moneda']);
                $estado = intval($_POST['cmb_estado']);
                if ($Ids == 0) {
                    $option = 1;
                    if ($_SESSION['permisosMod']['w']) {
                        $result = $model->insertData($Ids, $ruc, $razon, $nombre, $direccion, $correo, $logo, $moneda, $estado);
                    }
                } else {
                    $option = 2;
                    if ($_SESSION['permisosMod']['u']) {
                        $result = $model->updateData($Ids, $ruc, $razon, $nombre, $direccion, $correo, $logo, $moneda, $estado);
                    }
                }

                if ($result > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
                    }
                } else if ($result == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! Registro ya existe, ingrese otro.');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function delEmpresa()
    {
        if ($_POST) {
            if ($_SESSION['permisosMod']['d']) {
                $model = new EmpresaModel();
                $ids = intval($_POST['ids']);
                $request = $model->deleteRegistro($ids);
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

    public function getModulosPorEmpresa()
    {
        if ($_POST) {

            $decodedData = base64_decode($_POST['datos']);
            $data = json_decode($decodedData, true);
            $ids = intval(strClean($data['Ids']));
            if ($ids > 0) {
                $modelEmpresa = new EmpresaModel();
                $Emp_Id = $modelEmpresa->getIdEmpresaUsuario($ids);
                $arrData['Modulo'] = (new ModuloModel())->getEmpresaModulo($Emp_Id);
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

    public function getEmpresaModulos($ids)
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url() . '/dashboard');
            die();
        }
        $ids = base64_decode($ids);
        if (is_numeric($ids)) {
            $modelEmpresa = new EmpresaModel();
            $modelRol = new RolesModel();
            $data['Eusu_id'] = $_SESSION['Eusu_id'];
            $data['Empresas'] = $modelEmpresa->consultarEmpresaUsuario($_SESSION['Usu_id']);
            $modelModel = new ModuloModel();
            $data['EmpModulo'] = $modelModel->getEmpresaModulo($_SESSION['Emp_Id']);
            $data['Modulos'] = $modelModel->getModuloAll();
            $data['Roles'] = $modelRol->getRolAll();
            $data['page_tag'] = 'Empresa Modulo';
            $data['page_name'] = 'Empresa Modulo';
            $data['page_title'] = 'Empresa Modulo <small> ' . $_SESSION['empresaData']['NombreComercial'] . '</small>';
            $data['page_back'] = 'empresamodulo';
            $this->views->getView($this, 'empresamodulo', $data);
        } else {
            putMessageLogFile("EmpresaModulo Error");
            require_once("Controllers/Error.php");
        }

        die();
    }



    public function actualizarEmpresaModulo()
    {
        if ($_POST) {
            $arrResponse = ["status" => false, "msg" => "Datos incorrectos."];

            // Decodificar datos
            $decodedData = base64_decode($_POST['datos'] ?? '');
            $data = json_decode($decodedData, true);

            // Validar datos obligatorios
            $eusu_id = $data['eusu_id'] ?? null;
            $ids = $data['ids'] ?? null;
            $accion = $data['accion'] ?? null;

            if (!$eusu_id || !$ids || !$accion) {
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                die();
            }

            // Obtener ID de empresa desde modelo
            $modelEmpresa = new EmpresaModel();
            $Emp_Id = $modelEmpresa->getIdEmpresaUsuario($eusu_id);

            // Ejecutar acción según tipo
            $request = null;
            if ($accion === "Create" && $_SESSION['permisosMod']['w']) {
                $request = $this->model->insertDataEmpModulo($ids, $Emp_Id);
            }

            // Generar respuesta
            if (!empty($request) && $request["status"]) {
                $arrResponse = [
                    'status' => true,
                    'msg' => 'Datos guardados correctamente.',
                    'numero' => $request["numero"] ?? null
                ];
            } else {
                $arrResponse['msg'] = 'No es posible almacenar los datos.';
            }

            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }

        die();
    }


    public function getRolesPorEmpresa()
    {
        if ($_POST) {

            $decodedData = base64_decode($_POST['datos']);
            $data = json_decode($decodedData, true);
            $ids = intval(strClean($data['Ids']));
            if ($ids > 0) {
                $modelEmpresa = new EmpresaModel();
                $Emp_Id = $modelEmpresa->getIdEmpresaUsuario($ids);
                $modelRol = new RolesModel();
                $arrData['Modulo'] = $modelRol->getEmpresaRol($Emp_Id);

                //dep( $arrData );
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



    public function actualizarEmpresaRoles()
    {
        if ($_POST) {
            $arrResponse = ["status" => false, "msg" => "Datos incorrectos."];

            // Decodificar datos
            $decodedData = base64_decode($_POST['datos'] ?? '');
            $data = json_decode($decodedData, true);

            // Validar datos obligatorios
            $eusu_id = $data['eusu_id'] ?? null;
            $ids = $data['ids'] ?? null;
            $accion = $data['accion'] ?? null;

            if (!$eusu_id || !$ids || !$accion) {
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                die();
            }

            // Obtener ID de empresa desde modelo
            $Emp_Id = (new EmpresaModel())->getIdEmpresaUsuario($eusu_id);

            // Ejecutar acción según tipo
            $request = null;
            if ($accion === "Create" && $_SESSION['permisosMod']['w']) {
                $request = $this->model->insertDataEmpRol($ids, $Emp_Id);
            }

            // Generar respuesta
            if (!empty($request) && $request["status"]) {
                $arrResponse = [
                    'status' => true,
                    'msg' => 'Datos guardados correctamente.',
                    'numero' => $request["numero"] ?? null
                ];
            } else {
                $arrResponse['msg'] = 'No es posible almacenar los datos.';
            }

            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }

        die();
    }




    public function getModuloRolesPorEmpresa()
    {
        if ($_POST) {
            $decodedData = base64_decode($_POST['datos']);
            $data = json_decode($decodedData, true);
            $ids = intval(strClean($data['Ids']));
            if ($ids > 0) {
                $Emp_Id = (new EmpresaModel())->getIdEmpresaUsuario($ids);
                $arrData['Modulo'] = (new RolesModel())->getEmpresaRol($Emp_Id);
                $arrData['EmpresaModulo'] = (new ModuloModel())->getEmpresaModulo($Emp_Id);
                //dep( $arrData );
                if (empty($arrData)) {
                    $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                } else {
                    $arrResponse = array('status' => true, 'data' => $arrData);
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        exit();
    }



    public function ingresarUsuarioEmpresa()
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
                    //if ($_SESSION['permisosMod']['w']) {
                    $request = $this->model->insertDataUsuarioEmpresa($datos);
                    //}
                } else {
                    //$option = 2;
                    //if ($_SESSION['permisosMod']['u']) {
                    //    $request = $this->model->updateData($datos);
                    //}
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
        exit();
    }

    public function getEmpresaModuloRol()
    {
        if ($_POST) {
            $decodedData = base64_decode($_POST['datos']);
            $data = json_decode($decodedData, true);
            $erol_id = intval(strClean($data['Ids']));
            if ($erol_id > 0) {
                //$Emp_Id = (new EmpresaModel())->getIdEmpresaUsuario($ids);
                $arrData['EmpModRol'] = (new ModuloModel())->getEmpresaModuloRol($erol_id);
                //dep( $arrData );
                if (empty($arrData)) {
                    $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                } else {
                    $arrResponse = array('status' => true, 'data' => $arrData);
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        exit();
    }


    public function actualizarEmpresaRolesSelect()
    {
        if ($_POST) {
            $arrResponse = ["status" => false, "msg" => "Datos incorrectos."];

            // Decodificar datos
            $decodedData = base64_decode($_POST['datos'] ?? '');
            $data = json_decode($decodedData, true);

            // Validar datos obligatorios
            $erol_id = $data['erol_id'] ?? 0;
            $ids = $data['ids'] ?? null;
            $accion = $data['accion'] ?? null;

            if (!$erol_id || !$ids || !$accion) {
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                exit();
            }

            // Obtener ID de empresa desde modelo
            //$Emp_Id = (new EmpresaModel())->getIdEmpresaUsuario($eusu_id);

            // Ejecutar acción según tipo
            $request = null;
            if ($accion === "Create" && $_SESSION['permisosMod']['w']) {
                $request = $this->model->insertDataEmpRolSelect($ids, $erol_id);
            }

            // Generar respuesta
            if (!empty($request) && $request["status"]) {
                $arrResponse = [
                    'status' => true,
                    'msg' => 'Datos guardados correctamente.',
                    'numero' => $request["numero"] ?? null
                ];
            } else {
                $arrResponse['msg'] = 'No es posible almacenar los datos.';
            }

            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }

        exit();
    }

    public function setModuloIndex()
{
    try {
        // Verificar que la solicitud sea POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            responseJson(['status' => false, 'msg' => 'Método no permitido.']);
        }

        $data = recibirData($_POST['data'] ?? null);
      
        if (empty($data) || !isset($data['erol_id'], $data['emod_id'])) {
            responseJson(['status' => false, 'msg' => 'Faltan datos requeridos.']);
        }

        $ErolId = intval(strClean($data['erol_id']));
        $EmodId = intval(strClean($data['emod_id']));

        if ($ErolId <= 0 || $EmodId <= 0) {
            responseJson(['status' => false, 'msg' => 'Los identificadores deben ser numéricos válidos.']);
        }

        // Lógica de negocio: asignar módulo index
        $arrResponse = $this->model->setModuloIndex($EmodId, $ErolId);

        responseJson($arrResponse);
    } catch (Exception $e) {
        logFileSystem("Error en setModuloIndex: " . $e->getMessage(), "ERROR");
        responseJson(['status' => false, 'msg' => 'Ocurrió un error al intentar asignar el módulo index.']);
    }
}





}