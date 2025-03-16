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
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        $data['page_tag'] = "Cliente";
        $data['page_name'] = "Cliente";
        $data['page_title'] = "Cliente <small> " . $_SESSION['empresaData']['NombreComercial'] . "</small>";
        $data['page_back'] = "clientepedido";
        $this->views->getView($this, "clientepedido", $data);
    }

    public function getClientes()
    {
        //putMessageLogFile($_SESSION['permisosMod']['r']);
        if ($_SESSION['permisosMod']['r']) {
            $parametro = array();
            //$parametro = array('estado' => $requestCab);
            $arrData = $this->model->consultarDatos($parametro);
            for ($i = 0; $i < count($arrData); $i++) {
                $btnOpciones = "";
                if ($arrData[$i]['Estado'] == 1) {
                    $arrData[$i]['Estado'] = '<span class="badge badge-success">Activo</span>';
                } else {
                    $arrData[$i]['Estado'] = '<span class="badge badge-danger">Inactivo</span>'; //target="_blanck"
                }

                if ($_SESSION['permisosMod']['r']) {
                    //$btnOpciones .= '<button class="btn btn-info btn-sm btnViewInstructor" onClick="fntViewInstructor(\'' . $arrData[$i]['Ids'] . '\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
                }
                if ($_SESSION['permisosMod']['u']) {
                    //$btnOpciones .= '<button class="btn btn-primary  btn-sm btnEditInstructor" onClick="fntEditInstructor(\'' . $arrData[$i]['Ids'] . '\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
                    $btnOpciones .= ' <a title="Editar Datos" href="' . base_url() . '/clientePedido/editar/' . $arrData[$i]['Ids'] . '"  class="btn btn-primary btn-sm"> <i class="fa fa-pencil"></i> </a> ';
                }
                if ($_SESSION['permisosMod']['d']) {
                    $btnOpciones .= '<button class="btn btn-danger btn-sm btnDelInstructor" onClick="fntDeleteCliente(' . $arrData[$i]['Ids'] . ')" title="Eliminar Datos"><i class="fa fa-trash"></i></button>';
                }
                if ($_SESSION['permisosMod']['r']) {
                    $btnOpciones .= ' <a title="Catálogo de Productos" href="' . base_url() . '/clientePedido/catalogo/' . $arrData[$i]['Ids'] . '"  class="btn btn-primary btn-sm"> <i class="fa fa-list"></i> </a> ';
                }
                //$btnOpciones .= '<button class="btn btn-info btn-sm btnViewInstructor" onClick="fntViewInstructor(\'' . $arrData[$i]['Ids'] . '\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
                //$btnOpciones .= '<button class="btn btn-primary  btn-sm btnEditInstructor" onClick="fntEditInstructor(\'' . $arrData[$i]['Ids'] . '\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
                //$btnOpciones .= '<button class="btn btn-danger btn-sm btnDelInstructor" onClick="fntDeleteInstructor(' . $arrData[$i]['Ids'] . ')" title="Eliminar Datos"><i class="fa fa-trash"></i></button>';
                $arrData[$i]['options'] = '<div class="text-center">' . $btnOpciones . '</div>';
            }
            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

  


    public function nuevo()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        $formaPago = new PagoModel();
        $usuarioRol = new UsuariosModel();
        $data['usuario_rol'] = $usuarioRol->consultarRoles();
        $data['forma_pago'] = $formaPago->consultarPago();
        $data['page_tag'] = "Cliente";
        $data['page_name'] = "Cliente";
        $data['page_title'] = "Cliente <small> " . $_SESSION['empresaData']['NombreComercial'] . "</small>";
        $data['page_back'] = "clientepedido";
        $this->views->getView($this, "nuevo", $data);
    }

    public function editar($ids)
    {
        if ($_SESSION['permisosMod']['r']) {
            if (is_numeric($ids)) {
                $data = $this->model->consultarDatosId($ids);
                if (empty($data)) {
                    echo "Datos no encontrados";
                } else {
                    $formaPago = new PagoModel();
                    $data['forma_pago'] = $formaPago->consultarPago();
                    $data['page_tag'] = "Editar Cliente";
                    $data['page_name'] = "Editar Cliente";
                    $data['page_title'] = "Editar Cliente <small> " . $_SESSION['empresaData']['NombreComercial'] . "</small>";
                    $data['page_back'] = "clientepedido";
                    $this->views->getView($this, "editar", $data);
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

    public function ingresarCliente()
    {
        if ($_POST) {
            //dep($_POST);
            if (empty($_POST['dataObj']) || empty($_POST['accion'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
            } else {
                $request = "";
                $datos = isset($_POST['dataObj']) ? json_decode($_POST['dataObj'], true) : array();
                $accion = isset($_POST['accion']) ? $_POST['accion'] : "";
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
                        $arrResponse = array('status' => true, 'numero' => 0, 'msg' => 'Datos Actualizados correctamente.');
                    }
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos: ' . $request["message"]);
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function delCliente()
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


    public function generarReporteClientesPDF(){
		if($_SESSION['permisosMod']['r']){
                $parametro = array('estado' => '1');		
                $data['Result'] = $this->model->consultarDatos($parametro);
				if(empty($data)){
					echo "Datos no encontrados";
				}else{
					//$numeroContrato = $data['Contrato'];
					ob_end_clean();
                    $data['Titulo']="Lista Clientes Activos";
					$html =getFile("ClienteMiller/clientePDF",$data);
					$html2pdf = new Html2Pdf('p','A4','es','true','UTF-8');
					$html2pdf->writeHTML($html);

                    $FechaActual= date('m-d-Y H:i:s a', time()); 
                    //$html2pdf->pdf->SetDisplayMode('fullpage');
                    $html2pdf->output('ReporteClientes_'.$FechaActual.'.pdf','D');
				}
		}else{
			header('Location: '.base_url().'/login');
			die();
		}
	}

    public function catalogo($ids)
    {
        if ($_SESSION['permisosMod']['r']) {
            if (is_numeric($ids)) {
                $data = $this->model->consultarDatosId($ids);
                if (empty($data)) {
                    echo "Datos no encontrados";
                } else {
                    //$modelCliente = new ClientePedidoModel();
                    //$data['cliente'] = $modelCliente->consultarClienteTienda();
                    //$data['tienda'] = $this->model->consultarTiendaCliente($data['Cli_Ids']);
                    $data['nombreCliente'] = $data['Nombre'];

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
            echo json_encode(["status" => false, "msg" => $e->getMessage()]);
        }
    }





}
