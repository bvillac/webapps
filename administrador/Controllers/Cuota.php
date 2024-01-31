<?php
use Spipu\Html2Pdf\Html2Pdf;

require 'vendor/autoload.php';
class Cuota extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        sessionStart();
        getPermisos();
    }


    public function cuota()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        $data['page_tag'] = "Cuota Pago";
        $data['page_name'] = "Cuota Pago";
        $data['page_title'] = "Cuota Pago <small> " . TITULO_EMPRESA . "</small>";
        $data['page_back'] = "cuota";
        $this->views->getView($this, "cuota", $data);
    }

    public function consultarCuota()
    {
        if ($_SESSION['permisosMod']['r']) {
            $arrData = $this->model->consultarDatos();
            for ($i = 0; $i < count($arrData); $i++) {
                $btnOpciones = "";
                if ($_SESSION['permisosMod']['u']) {
                    $btnOpciones .= ' <a title="Ver Detalle Pagos" href="' . base_url() . '/Cuota/detallepago/' . $arrData[$i]['ContIds'] . '"  class="btn btn-primary btn-sm"> <i class="fa fa-list-alt"></i> </a> ';
                    //$btnOpciones .= '<button class="btn btn-primary  btn-sm btnEditLinea" onClick="editarSalon(\'' . $arrData[$i]['ContIds'] . '\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
                }
                if ($_SESSION['permisosMod']['r']) {
                    $btnOpciones .= ' <a title="Generar PDF" href="' . base_url() . '/Cuota/generarDetallePagoPDF/' . $arrData[$i]['ContIds'] . '" target="_blanck" class="btn btn-primary btn-sm"> <i class="fa fa-file-pdf-o"></i> </a> ';
                }
                $arrData[$i]['options'] = '<div class="text-center">' . $btnOpciones . '</div>';
            }
            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function detallePago($ids)
    {
        if ($_SESSION['permisosMod']['r']) {
            if (is_numeric($ids)) {
                $data = $this->model->consultarPagoContratoId($ids);
                //putMessageLogFile($data);
                if (empty($data)) {
                    echo "Datos no encontrados";
                } else {
                    $data['page_tag'] = "Detalle Pagos";
                    $data['page_name'] = "Detalle Pagos";
                    $data['page_title'] = "Detalle Pagos <small> " . TITULO_EMPRESA . "</small>";
                    $data['page_back'] = "cuota";
                    $this->views->getView($this, "detallepago", $data);
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


    public function realizarPago()
    {
        if ($_POST) {
            if ($_SESSION['permisosMod']['d']) {
                $ids = intval($_POST['Ids']);
                $request = $this->model->realizarPago($ids);
                //$request["status"]=true;
                if ($request["status"]) {
                    $arrResponse = array('status' => true, 'msg' => 'Pago Registrado Correctamente');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'Error al Registrar el Pago.');
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }


    public function generarDetallePagoPDF($idContrato)
    {
        if ($_SESSION['permisosMod']['r']) {
            if (is_numeric($idContrato)) {
                $data = $this->model->consultarPagoContratoId($idContrato);
                //putMessageLogFile($data);
                if (empty($data)) {
                    echo "Datos no encontrados";
                } else {
                    $numeroSecuencia = $data['contrato']['Contrato'];
                    ob_end_clean();
                    $html = getFile("Cuota/pagosDetallePDF", $data);
                    $html2pdf = new Html2Pdf('p', 'A4', 'es', 'true', 'UTF-8');
                    $html2pdf->writeHTML($html);
                    $html2pdf->output('CONTRATO_' . $numeroSecuencia . '.pdf');
                }
            } else {
                echo "Dato no válido";
            }
        } else {
            header('Location: ' . base_url() . '/login');
            die();
        }
    }

    public function consultarPagos()
    {
        if ($_POST) {
            $idsContrato = intval($_POST['IdsCont']);
            //putMessageLogFile($data);
            if (is_numeric($idsContrato)) {
                $data = $this->model->consultarPagoContratoId($idsContrato);
                //putMessageLogFile($data);
                if (empty($data)) {
                    //echo "Datos no encontrados";
                    $arrResponse = array('status' => false, 'msg' => 'Error contrato no existe');
                } else {
                    $arrResponse = array('status' => true,'data' => $data, 'msg' => 'Registro Encontrado');
                }
            } else {
                //echo "Dato no válido";
                $arrResponse = array('status' => false, 'msg' => 'Dato no válido');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }








}
