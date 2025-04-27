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
        if ($_SESSION['permisosMod']['r']) {
            //$options .= '<button class="btn btn-info btn-sm btnViewLinea" onClick="fntViewTienda(\'' . $id . '\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
        }
        if ($_SESSION['permisosMod']['u'] && $EstadoDoc==1) {
            //$options .= '<button class="btn btn-primary  btn-sm btnEditLinea" onClick="editarTienda(\'' . $id . '\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
            $options .= " <a title='Catálogo' href='" . base_url() . "/pedidoWeb/editar/$id' class='btn btn-primary btn-sm'><i class='fa fa-pencil'></i></a> ";
        }
        if ($_SESSION['permisosMod']['r']  ) {
            $options .= " <button class='btn btn-success btn-sm btnDelLinea' onClick='fntFacturarPedido($id)' title='Facturado'><i class='fa fa-usd'></i></button> ";
        }
        if ($_SESSION['permisosMod']['d'] && $EstadoDoc==1) {
            $options .= " <button class='btn btn-danger btn-sm btnDelLinea' onClick='fntAnularPedido($id)' title='Anular'><i class='fa fa-trash'></i></button> ";
        }
        if ($_SESSION['permisosMod']['r']) {
            $options .= ' <a title="Generar PDF" href="' . base_url() . '/pedidoWeb/generarPedidoPDF/' . $id . '" target="_blanck" class="btn btn-primary btn-sm"> <i class="fa fa-file-pdf-o"></i> </a> ';
        }
        //$options .= " <a title='Catálogo' href='" . base_url() . "/tienda/catalogo/$id' class='btn btn-primary btn-sm'><i class='fa fa-list'></i></a> ";
        return $options . '</div>';
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
