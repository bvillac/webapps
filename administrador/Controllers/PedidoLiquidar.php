<?php
use Spipu\Html2Pdf\Html2Pdf;
require 'vendor/autoload.php';
require_once("Models/TiendaModel.php");
require_once("Models/ClientePedidoModel.php");
class PedidoLiquidar extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        sessionStart();
        getPermisos();


    }


    public function pedidoliquidar()
    {
        checkPermission('r', 'dashboard');
        $data = getPageData("Pedido Liquidar", "pedidoliquidar");
        $this->views->getView($this, "pedidoliquidar", $data);
    }



    public function consultarPedidos()
    {
        checkPermission('r', 'dashboard');
        $arrData = $this->model->consultarDatos();
        $RolNombre=retornarDataSesion('RolNombre');
        foreach ($arrData as &$objData) {
            $estadoTexto = estadoPedidos($objData['Estado']);
            $EstadoDoc=$objData['Estado'];
            $claseBadge = ($objData['Estado'] != 4) ? 'badge-success' : 'badge-danger';

            $objData['Estado'] = "<span class='badge {$claseBadge}'>{$estadoTexto}</span>";
            $objData['options'] = $this->getArrayOptions($objData['Ids'],$RolNombre,$EstadoDoc);
        }
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        exit();
    }

    private function getArrayOptions($id,$RolNombre,$EstadoDoc)
    {
        $options = '<div class="text-center">';
        if ($_SESSION['permisosMod']['r'] && $EstadoDoc==2 ) {
            $options .= " <button class='btn btn-success btn-sm btnDelLinea' onClick='fntFacturarPedido($id)' title='Facturado'><i class='fa fa-usd'></i></button> ";
        }
     
        if ($_SESSION['permisosMod']['r']) {
            $options .= ' <a title="Generar PDF" href="' . base_url() . '/pedidoLiquidar/generarPedidoPDF/' . $id . '" target="_blanck" class="btn btn-primary btn-sm"> <i class="fa fa-file-pdf-o"></i> </a> ';
        }
        return $options . '</div>';
    }

    
    public function generarPedidoPDF($id)
    {
        if (!is_numeric($id))
            exit("Dato no válido");
        checkPermission('r', 'pedidoWeb');
        $cliIds = retornarDataSesion("Cli_Id");
        $data['cabData'] = $this->model->cabeceraPedido($id);
        $data['detData'] = $this->model->detallePedido($id);
        $data['Cliente'] = (new ClientePedidoModel())->consultarDatosId($cliIds);
        $numeroSecuencia = $data['cabData'][0]['numero'];
        $tie_id = $data['cabData'][0]['tieid'];
        $data['Tienda'] = (new TiendaModel())->consultarDatosId($tie_id);
        ob_end_clean();
        //$html =getFile("Template/Modals/ordenCompraPDF",$data);
        $html = getFile("PedidoLiquidar/pedidoPDF", $data);
        $html2pdf = new Html2Pdf('p', 'A4', 'es', 'true', 'UTF-8');
        $html2pdf->writeHTML($html);
        $html2pdf->output('PEDIDO_' . $numeroSecuencia . '.pdf');
    }


    public function facturarPedido()
    {
        if ($_POST) {
            try {
                checkPermission('r', 'pedidoWeb');
                $data = recibirData($_POST['data']);
                $ids = isset($data['ids']) ? filter_var($data['ids'], FILTER_VALIDATE_INT) : 0;
                $request = $this->model->facturarPedido($ids);
                if ($request["status"]) {
                    $arrResponse = array('status' => true, 'msg' => 'Registro Facturado correctamente');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'Error al Facturar el Registro.');
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                // Registrar error en log
                logFileSystem("Error en consutla facturarPedido: " . $e->getMessage(), "ERROR");
                exit;
            }

        }
        exit();
    }







}
