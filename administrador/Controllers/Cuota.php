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
        $this->views->getView($this, "cuota", $data);
    }

    public function consultarCuota()
    {
        if ($_SESSION['permisosMod']['r']) {
            $arrData = $this->model->consultarDatos();
            for ($i = 0; $i < count($arrData); $i++) {
                $btnOpciones = "";
                if ($_SESSION['permisosMod']['u']) {
                    //$btnOpciones .= ' <a title="Editar Datos" href="' . base_url() . '/Beneficiario/editar/' . $arrData[$i]['Ids'] . '"  class="btn btn-primary btn-sm"> <i class="fa fa-pencil"></i> </a> ';
                    $btnOpciones .= '<button class="btn btn-primary  btn-sm btnEditLinea" onClick="editarSalon(\'' . $arrData[$i]['ContIds'] . '\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
                }                
                $arrData[$i]['options'] = '<div class="text-center">' . $btnOpciones . '</div>';
            }
            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    




}
