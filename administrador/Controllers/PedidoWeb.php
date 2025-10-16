<?php

use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;
use Spipu\Html2Pdf\Html2Pdf;

require 'vendor/autoload.php';
require_once("Models/TiendaModel.php");
require_once("Models/ClientePedidoModel.php");
require_once("Models/EmpresaModel.php");
class PedidoWeb extends Controllers
{
    private int $utie_id;
    private bool $valFechas;
    public function __construct()
    {
        parent::__construct();
        sessionStart();
        getPermisos();
        $this->utie_id = retornarDataSesion("Utie_id");
        $this->valFechas = (new TiendaModel())->validarFechasTienda($this->utie_id);
    }


    public function pedidoweb()
    {
        checkPermission('r', 'dashboard');
        $data = getPageData("Pedido Web", "pedidoWeb");
        //putMessageLogFile("Validación de fechas para tienda: {$this->utie_id} - Resultado: " . json_encode($this->valFechas));

        $data['ValFechas'] = $this->valFechas;
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
        if ($_SESSION['permisosMod']['u'] && $EstadoDoc == 1 && $this->valFechas) {
            $options .= " <a title='Editar' href='" . base_url() . "/pedidoWeb/editar/$id' class='btn btn-primary btn-sm'><i class='fa fa-pencil'></i></a> ";
        }
        if ($_SESSION['permisosMod']['r'] && $EstadoDoc == 1 && $this->valFechas && ($RolNombre == 'supervisortienda' || $RolNombre == 'clientetienda')) {
            $options .= " <button class='btn btn-success btn-sm btnDelLinea' onClick='fntAutorizarPedido($id)' title='Autorizar'><i class='fa fa-check-circle-o'></i></button> ";
        }
        if ($_SESSION['permisosMod']['d'] && $EstadoDoc == 1 && $this->valFechas) {
            $options .= " <button class='btn btn-danger btn-sm btnDelLinea' onClick='fntAnularPedido($id)' title='Anular'><i class='fa fa-trash'></i></button> ";
        }
        if ($_SESSION['permisosMod']['r']) {
            $options .= ' <a title="Generar PDF" href="' . base_url() . '/pedidoWeb/generarPedidoPDF/' . $id . '" target="_blanck" class="btn btn-primary btn-sm"> <i class="fa fa-file-pdf-o"></i> </a> ';
        }
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
                $arrData['SaldoTienda'] = $this->model->recuperarConsumoTienda($ids, $cliIds);
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



    private function consumoTiendaPedido($tiendaId,$totalPedido = 0)
    {
        $result = [
            'Estado' => true,
            'Saldo'  => 0
        ];
        $clienteId = retornarDataSesion("Cli_Id");
        // Recuperar datos de la tienda
        $tienda = (new TiendaModel())->consultarDatosId($tiendaId);
        $cupo   = floatval($tienda['Cupo'] ?? 0);
        // Recuperar consumo actual
        $consumoActual = floatval($this->model->recuperarConsumoTienda($tiendaId, $clienteId));
        // Validar disponibilidad de cupo
        $nuevoSaldo = $consumoActual + $totalPedido;
        if ($nuevoSaldo > $cupo) {
            $saldoDisponible = $cupo - $consumoActual;
            $result['Estado'] = false;
            $result['Saldo']  = $saldoDisponible;
            $result['Mensaje'] = "Registro NO guardado: El total del pedido excede el cupo disponible. Saldo disponible: " . formatMoney($saldoDisponible, 2);
            return $result;
        }
        $result['Saldo'] = $cupo - $nuevoSaldo;
        $result['Mensaje'] = "Registro guardado. Saldo restante: " . formatMoney($result['Saldo'], 2);
        return $result;
    }



    public function ingresarPedidoTemp()
    {
        if ($_POST) {
            $data = recibirData($_POST['data']);
            if (empty($data['productos']) || empty($data['accion'])) {
                $arrResponse = array('status' => false, 'msg' => 'Error no se recibieron todos los datos necesarios');
            } else {
                //putMessageLogFile("Datos recibidos en ingresarPedidoTemp: " . json_encode($data));
                $totalPedido = isset($data['total']) ? floatval(str_replace([',', ' '], ['.', ''], $data['total'])) : 0.0;
                $validacionSaldo = $this->consumoTiendaPedido($data['tienda_id'], $totalPedido);
                //putMessageLogFile("Validación de saldo para tienda {$data['tienda_id']} con total pedido {$totalPedido}: " . json_encode($validacionSaldo));
                if (!$validacionSaldo['Estado']) {
                    $arrResponse = array('status' => false, 'msg' => $validacionSaldo['Mensaje'] ?? 'El total del pedido excede el cupo disponible.', 'saldo' => $validacionSaldo['Saldo'] ?? 0);
                    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                    exit();
                }   

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
                    $idSolicitud = $request["numero"];
                    //$restultado=$this->enviarCorreoNotificacion( $idSolicitud, '', "Autorización de pedido");

                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'numero' => add_ceros($idSolicitud, 9), 'msg' => 'Datos guardados correctamente.');
                    } else {
                        $arrResponse = array('status' => true, 'numero' => add_ceros($idSolicitud, 9), 'msg' => 'Datos actualizados correctamente.');
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
        $data['SaldoTienda'] = $this->model->recuperarConsumoTienda($tie_id, $cliIds);
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
        $Server = (new EmpresaModel())->consultarEmpresaServerMail(retornarDataSesion('Emp_Id'));
        $data['correo_admin'] = $Server["correo_admin"];
        ob_end_clean();
        //$html =getFile("Template/Modals/ordenCompraPDF",$data);
        $html = getFile("PedidoWeb/pedidoPDF", $data);
        $html2pdf = new Html2Pdf('p', 'A4', 'es', 'true', 'UTF-8');
        $html2pdf->writeHTML($html);
        $html2pdf->output('Solicitud_' . $numeroSecuencia . '.pdf');
    }




    private function validarSaldoTienda($ids)
    {
        $result = [
            'Estado' => true,
            'Saldo'  => 0
        ];

        // Obtener cabecera del pedido temporal
        $cabData = $this->model->cabeceraPedidoTemp($ids);
        if (empty($cabData) || !isset($cabData[0]['tieid'])) {
            return ['Estado' => false, 'Saldo' => 0, 'Mensaje' => 'Pedido no encontrado.'];
        }

        $clienteId = retornarDataSesion("Cli_Id");
        $tiendaId  = $cabData[0]['tieid'];

        // Recuperar datos de la tienda
        $tienda = (new TiendaModel())->consultarDatosId($tiendaId);
        $cupo   = floatval($tienda['Cupo'] ?? 0);

        // Recuperar consumo actual
        $consumoActual = floatval($this->model->recuperarConsumoTienda($tiendaId, $clienteId));

        // Calcular total del pedido
        $totalPedido = isset($cabData[0]['total'])
            ? floatval(str_replace([',', ' '], ['.', ''], $cabData[0]['total']))
            : 0.0;

        // Validar disponibilidad de cupo
        $nuevoSaldo = $consumoActual + $totalPedido;

        if ($nuevoSaldo > $cupo) {
            $saldoDisponible = $cupo - $consumoActual;
            $result['Estado'] = false;
            $result['Saldo']  = $saldoDisponible;
            $result['Mensaje'] = "Pedido NO autorizado: El total del pedido excede el cupo disponible. Saldo disponible: " . formatMoney($saldoDisponible, 2);
            return $result;
        }

        $result['Saldo'] = $cupo - $nuevoSaldo;
        $result['Mensaje'] = "Pedido autorizado. Saldo restante: " . formatMoney($result['Saldo'], 2);

        return $result;
    }





    public function autorizarPedidoTemp()
    {
        if ($_POST) {
            try {
                checkPermission('r', 'pedidoWeb');
                $data = recibirData($_POST['data']);
                $ids = isset($data['ids']) ? filter_var($data['ids'], FILTER_VALIDATE_INT) : 0;

                // Validar saldo de tienda usando el método reutilizable
                $validacion = $this->validarSaldoTienda($ids);
                if (!$validacion['Estado']) {
                    $msg = $validacion['Mensaje'] ?? 'El pedido excede el cupo disponible.';
                    echo json_encode([
                        'status' => false,
                        'msg' => $msg,
                        'saldo' => $validacion['Saldo'] ?? 0
                    ], JSON_UNESCAPED_UNICODE);
                    exit();
                }

                $request = $this->model->autorizarPedidoTemp($ids);
                if ($request["status"]) {
                    $numeroPedido = $request["numero"];
                    $restultado = $this->enviarCorreoNotificacion($ids, $numeroPedido, "Autorización de pedido");
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

    private function enviarCorreoNotificacion(int $idSolicitud, $idPedido, string $asunto)
    {
        //Recupera infor de CabTemp  para enviar info al supervisor de tienda
        $CabPed = $this->model->sendMailPedidosTemp($idSolicitud);
        $cliIds = retornarDataSesion("Cli_Id");
        $Cliente = (new ClientePedidoModel())->consultarDatosId($cliIds);
        $Server = (new EmpresaModel())->consultarEmpresaServerMail(retornarDataSesion('Emp_Id'));

        $nombreCliente = $Cliente["Nombre"];
        $TotalPedido = formatMoney($CabPed[0]["valorneto"], 2);

        $CabPed[0]["web_empresa"] = $Server["dominio_empresa"];
        $CabPed[0]["empresa"] = $nombreCliente; //TITULO_EMPRESA;
        $CabPed[0]["numero_pedido"] = $idPedido;
        $CabPed[0]["base_url"] = BASE_URL;

        $htmlMail = getFile("Template/Email/email_notificaPedido", $CabPed[0]);

        $arrParams = [
            'destinatario' => $CabPed[0]["correopersona"], //'byron_villacresesf@hotmail.com',
            'asunto' => "({$nombreCliente}) {$TotalPedido} Confirmación de pedido",
            'nombreEmpresa' => $Server["nombre_mostrar"],
            'no_responder' => $Server["mail"],
            'html' => $htmlMail,
            'pdf' => '', //$pdfPath,
            'cc' => $Server["correo_admin"], //copia
            //'bcc' => $Server["correo_admin"],//copia oculta
            'borrarPDF' => true
        ];

        $mailer = new MailSystem(
            $Server["smtp_servidor"],
            $Server["smtp_puerto"],
            $Server["usuario"],
            base64_decode($Server["clave"])
        );
        $resultado = $mailer->enviarNotificacion($arrParams);
        if (!$resultado["status"]) {
            logFileSystem("Error al enviarNotificacion Pedido: {$idPedido}-{$nombreCliente}", "ERROR");
        }
        return $resultado; // Retorna el resultado del envío de correo
    }
}
