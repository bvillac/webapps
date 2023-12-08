<?php
class Cuota extends Controllers
{
    public function __construct()
    {
        sessionStart();
        parent::__construct();
        if (empty($_SESSION['loginEstado'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
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
                    $btnOpciones .= ' <a title="Editar Datos" href="' . base_url() . '/Cuota/detallepago/' . $arrData[$i]['ContIds'] . '"  class="btn btn-primary btn-sm"> <i class="fa fa-pencil"></i> </a> ';
                    //$btnOpciones .= '<button class="btn btn-primary  btn-sm btnEditLinea" onClick="editarSalon(\'' . $arrData[$i]['ContIds'] . '\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
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


    




}
