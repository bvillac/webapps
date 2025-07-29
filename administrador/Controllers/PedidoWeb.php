<?php
use Spipu\Html2Pdf\Html2Pdf;
require 'vendor/autoload.php';
require_once("Models/TiendaModel.php");
require_once("Models/ClientePedidoModel.php");
require_once("Models/EmpresaModel.php");
class PedidoWeb extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        sessionStart();
        getPermisos();


    }


    public function pedidoweb()
    {
        checkPermission('r', 'dashboard');
        $data = getPageData("Pedido Web", "pedidoWeb");
        //$data['cliente'] = (new ClientePedidoModel())->consultarClienteTienda();
        $this->views->getView($this, "pedidoweb", $data);
    }



    public function consultarPedidos()
    {
        checkPermission('r', 'dashboard');
        $arrData = $this->model->consultarDatos();
        $RolNombre = retornarDataSesion('RolNombre');
        foreach ($arrData as &$objData) {
            $estadoTexto = estadoPedidos($objData['Estado']);
            $EstadoDoc = $objData['Estado'];
            $claseBadge = ($objData['Estado'] != 4) ? 'badge-success' : 'badge-danger';

            $objData['Estado'] = "<span class='badge {$claseBadge}'>{$estadoTexto}</span>";
            $objData['options'] = $this->getArrayOptions($objData['Ids'], $RolNombre, $EstadoDoc);
        }
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        exit();
    }

    private function getArrayOptions($id, $RolNombre, $EstadoDoc)
    {

        $options = '<div class="text-center">';
        if ($_SESSION['permisosMod']['r']) {
            //$options .= '<button class="btn btn-info btn-sm btnViewLinea" onClick="fntViewTienda(\'' . $id . '\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
        }
        if ($_SESSION['permisosMod']['u'] && $EstadoDoc == 1) {
            //$options .= '<button class="btn btn-primary  btn-sm btnEditLinea" onClick="editarTienda(\'' . $id . '\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
            $options .= " <a title='Catálogo' href='" . base_url() . "/pedidoWeb/editar/$id' class='btn btn-primary btn-sm'><i class='fa fa-pencil'></i></a> ";
        }
        if ($_SESSION['permisosMod']['r'] && $EstadoDoc == 1 && $RolNombre == 'supervisortienda') {
            $options .= " <button class='btn btn-success btn-sm btnDelLinea' onClick='fntAutorizarPedido($id)' title='Autorizar'><i class='fa fa-check-circle-o'></i></button> ";
        }
        if ($_SESSION['permisosMod']['d'] && $EstadoDoc == 1) {
            $options .= " <button class='btn btn-danger btn-sm btnDelLinea' onClick='fntAnularPedido($id)' title='Anular'><i class='fa fa-trash'></i></button> ";
        }
        if ($_SESSION['permisosMod']['r']) {
            $options .= ' <a title="Generar PDF" href="' . base_url() . '/pedidoWeb/generarPedidoPDF/' . $id . '" target="_blanck" class="btn btn-primary btn-sm"> <i class="fa fa-file-pdf-o"></i> </a> ';
        }
        //$options .= " <a title='Catálogo' href='" . base_url() . "/tienda/catalogo/$id' class='btn btn-primary btn-sm'><i class='fa fa-list'></i></a> ";
        return $options . '</div>';
    }

    public function nuevo()
    {
        checkPermission('r', 'dashboard');

        $data = getPageData("Nuevo Pedido Web", "pedidoWeb");
        $cliIds = retornarDataSesion("Cli_Id");
        $Utieid = retornarDataSesion("Utie_id");
        $data['tienda'] = (new TiendaModel())->consultarTiendaCliente($Utieid, $cliIds);
        $data['Cliente'] = (new ClientePedidoModel())->consultarDatosId($cliIds);
        $data['nombreCliente'] = htmlspecialchars($data['Cliente']['Nombre'], ENT_QUOTES, 'UTF-8');
        $this->views->getView($this, "nuevo", $data);


    }

    public function retornarDatosTienda()
    {
        //dep($_POST);
        if ($_POST) {
            $data = recibirData($_POST['data']);
            if (empty($data['ids'])) {
                $arrResponse = array('status' => false, 'msg' => 'Error de datos');
            } else {
                $ids = intval(strClean($data['ids']));
                $arrData = (new TiendaModel())->consultarDatosId($ids);
                $cliIds = retornarDataSesion("Cli_Id");
                $arrData['Items'] = $this->model->listarItemsTiendas($ids, $cliIds);
                $arrData['SaldoTienda'] = $this->model->recuperarSaldoTienda($ids, $cliIds);
                if (empty($arrData)) {
                    $arrResponse = array('status' => false, 'msg' => 'La tienda no Existe.');
                } else {
                    $arrResponse = array('status' => true, 'data' => $arrData);
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        exit();
    }






    public function ingresarPedidoTemp()
    {
        if ($_POST) {
            $data = recibirData($_POST['data']);
            //if (empty($data['productos']) || empty($data['accion']) || empty($data['tienda_id'])) {
            if (empty($data['productos']) || empty($data['accion'])) {
                $arrResponse = array('status' => false, 'msg' => 'Error no se recibieron todos los datos necesarios');
            } else {
                $datos = $data['productos'];
                $idTienda = $data['tienda_id'];
                $total = $data['total'];
                $accion = $data['accion'];
                $cabIds = isset($data['cabIds']) ? filter_var($data['cabIds'], FILTER_VALIDATE_INT) : 0;
                //$request = "";

                if ($accion == "Create") {
                    $option = 1;
                    if ($_SESSION['permisosMod']['w']) {
                        $request = $this->model->insertData($datos, $idTienda, $total);
                    }
                } else {
                    $option = 2;
                    if ($_SESSION['permisosMod']['u']) {
                        $request = $this->model->updateData($datos, $idTienda, $total, $cabIds);
                    }
                }
                //$request["status"]=true;
                if ($request["status"]) {
                    $idPedido = $request["numero"];

                    //Recupera infor de CabTemp  para enviar info al supervisor de tienda
                    $CabPed = $this->model->sendMailPedidosTemp($idPedido);
                    //putMessageLogFile($CabPed);
                    $cliId = retornarDataSesion('Cli_Id');
                    //$objUser=$this->model->recuperarUserCorreoTiendaSUP($idTienda,16,$cliId);//Recupera Usuairos Superviswor
                    //$CabPed[0]["correouser"] = 'byron_villacresesf@hotmail.com';//$objUser["usu_correo"];
                    //$CabPed[0]["nombreuser"] = 'Byron Villacreses';//$objUser["usu_nombre"];
                    $cliIds = retornarDataSesion("Cli_Id");
                    $Cliente = (new ClientePedidoModel())->consultarDatosId($cliIds);
                    $Server = (new EmpresaModel())->consultarEmpresaServerMail(retornarDataSesion('Emp_Id'));
                    putMessageLogFile($Server);
                    putMessageLogFile($Server["usuario"]);
                    
                    $nombreCliente = $Cliente["Nombre"];
                    $TotalPedido = formatMoney($CabPed[0]["valorneto"], 2);


                    $CabPed[0]["web_empresa"] = retornarDataSesion('SitioWeb');
                    $CabPed[0]["empresa"] = $nombreCliente;//TITULO_EMPRESA;
                    $CabPed[0]["base_url"] = BASE_URL;

                    $htmlMail = getFile("Template/Email/email_notificaPedido", $CabPed[0]);
                    $arrParams = [
                        'destinatario' => 'byron_villacresesf@hotmail.com',
                        'asunto' => "({$nombreCliente}) {$TotalPedido} Confirmación de pedido",
                        'html' => $htmlMail,
                        'pdf' => '',//$pdfPath,
                        'bcc' => retornarDataSesion('Emp_mail'),//'byronvillacreses@gmail.com',
                        'borrarPDF' => true
                    ];

                    $mailer = new MailSystem(
                        host:  $Server["smtp_servidor"],//'smtp.gmail.com',
                        port: $Server["smtp_puerto"],//587,
                        username: $Server["usuario"],//retornarDataSesion('Emp_mail'),
                        password: $Server["clave"]//'wftd aqkb uonh fusa' // 
                    );
                    $resultado = $mailer->enviarNotificacion($arrParams);

                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'numero' => add_ceros($idPedido, 9), 'msg' => 'Datos guardados correctamente.');
                    } else {
                        $arrResponse = array('status' => true, 'numero' => add_ceros($idPedido, 9), 'msg' => 'Datos actualizados correctamente.');
                    }
                } else {
                    $arrResponse = array("status" => false, "msg" => $request["message"]);
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        exit();
    }


    public function editar($id)
    {
        if (!is_numeric($id))
            die("Dato no válido");
        checkPermission('r', 'pedidoWeb');
        $cliIds = retornarDataSesion("Cli_Id");
        $data['CabPed'] = $this->model->cabeceraPedidoTemp($id);
        $data['DetPed'] = $this->model->detallePedidoTemp($id);
        $data['Cliente'] = (new ClientePedidoModel())->consultarDatosId($cliIds);
        $tie_id = $data['CabPed'][0]['tieid'];
        $data['Tienda'] = (new TiendaModel())->consultarDatosId($tie_id);
        $data['Items'] = $this->model->listarItemsTiendas($tie_id, $cliIds);
        $data['Items'] = $this->actualizarItems($data['Items'], $data['DetPed']);
        $data['SaldoTienda'] = $this->model->recuperarSaldoTienda($tie_id, $cliIds);
        $data = array_merge($data, getPageData("Editar Pedido", "pedidoWeb"));
        $this->views->getView($this, "editar", $data);
    }

    public function actualizarItems(array $items, array $detalles): array
    {
        foreach ($detalles as $detalle) {
            foreach ($items as &$item) {
                if ($item['art_id'] == $detalle['artid']) {
                    $item['cantidad'] = $detalle['cantidad'];
                    $item['precio'] = $detalle['precio'];
                    $item['total'] = $detalle['totvta'];
                    $item['observacion'] = $detalle['observacion'];
                    break; // si ya lo encontró, pasa al siguiente detalle
                }
            }
        }
        return $items;
    }

    public function anularPedidoTemp()
    {
        if ($_POST) {
            try {
                checkPermission('d', 'pedidoWeb');
                $data = recibirData($_POST['data']);
                $ids = isset($data['ids']) ? filter_var($data['ids'], FILTER_VALIDATE_INT) : 0;
                //$ids = intval(strClean($ids));
                $request = $this->model->anularPedidoTemp($ids);
                if ($request) {
                    $arrResponse = array('status' => true, 'msg' => 'Se ha Anulado el Registro');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'Error al Anuladar el Registro.');
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                // Registrar error en log
                logFileSystem("Error en consutla anularPedidoTemp: " . $e->getMessage(), "ERROR");
                exit;
            }

        }
        exit();
    }

    public function generarPedidoPDF($id)
    {
        if (!is_numeric($id))
            exit("Dato no válido");
        checkPermission('r', 'pedidoWeb');
        $cliIds = retornarDataSesion("Cli_Id");
        $data['cabData'] = $this->model->cabeceraPedidoTemp($id);
        $data['detData'] = $this->model->detallePedidoTemp($id);
        $data['Cliente'] = (new ClientePedidoModel())->consultarDatosId($cliIds);
        $numeroSecuencia = $data['cabData'][0]['numero'];
        $tie_id = $data['cabData'][0]['tieid'];
        $data['Tienda'] = (new TiendaModel())->consultarDatosId($tie_id);
        ob_end_clean();
        //$html =getFile("Template/Modals/ordenCompraPDF",$data);
        $html = getFile("PedidoWeb/pedidoPDF", $data);
        $html2pdf = new Html2Pdf('p', 'A4', 'es', 'true', 'UTF-8');
        $html2pdf->writeHTML($html);
        $html2pdf->output('PEDIDO_' . $numeroSecuencia . '.pdf');
    }


    public function autorizarPedidoTemp()
    {
        if ($_POST) {
            try {
                checkPermission('r', 'pedidoWeb');
                $data = recibirData($_POST['data']);
                $ids = isset($data['ids']) ? filter_var($data['ids'], FILTER_VALIDATE_INT) : 0;
                $request = $this->model->autorizarPedidoTemp($ids);
                if ($request["status"]) {
                    $numero = $request["numero"];
                    $this->enviarCorreo($numero, "Autorización de pedido");

                    $arrResponse = array('status' => true, 'msg' => 'Registro Autorizado correctamente');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'Error al Autorizar el Registro.');
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                // Registrar error en log
                logFileSystem("Error en consutla autorizarPedidoTemp: " . $e->getMessage(), "ERROR");
                exit;
            }

        }
        exit();
    }

    private function enviarCorreo(int $idPedido, string $asunto)
    {
        //Recupera infor de CabTemp  para enviar info al supervisor de tienda
        $CabPed = $this->model->sendMailPedidosTemp($idPedido);
        $cliId = retornarDataSesion('Cli_Id');
        //$objUser=$this->model->recuperarUserCorreoTiendaSUP($idTienda,16,$cliId);//Recupera Usuairos Superviswor
        $CabPed[0]["correouser"] = 'byron_villacresesf@hotmail.com';//$objUser["usu_correo"];
        $CabPed[0]["nombreuser"] = 'Byron Villacreses';//$objUser["usu_nombre"];
        $cliIds = retornarDataSesion("Cli_Id");
        $Cliente = (new ClientePedidoModel())->consultarDatosId($cliIds);
        $nombreCliente = $Cliente["Nombre"];
        $TotalPedido = formatMoney($CabPed[0]["valorneto"], 2);


        $CabPed[0]["web_empresa"] = WEB_EMPRESA;
        $CabPed[0]["empresa"] = $nombreCliente;//TITULO_EMPRESA;
        $CabPed[0]["base_url"] = BASE_URL;

        $htmlMail = getFile("Template/Email/email_notificaPedido", $CabPed[0]);

        $arrParams = [
            'destinatario' => 'byron_villacresesf@hotmail.com',
            'asunto' => "({$nombreCliente}) {$TotalPedido} {$asunto}",
            'html' => $htmlMail,
            'pdf' => '',//$pdfPath,
            'bcc' => 'byronvillacreses@gmail.com',
            'borrarPDF' => true
        ];
        $mailer = new MailSystem();
        $resultado = $mailer->enviarNotificacion($arrParams);

    }








}
