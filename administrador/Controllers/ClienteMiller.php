<?php
class ClienteMiller extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        session_regenerate_id(true);
        if (empty($_SESSION['loginEstado'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
        //getPermisos(4);
    }


    public function clientemiller()
    {
        /*if(empty($_SESSION['permisosMod']['r'])){
				header("Location:".base_url().'/dashboard');
			}*/
        $data['page_tag'] = "Cliente";
        $data['page_name'] = "Cliente";
        $data['page_title'] = "Cliente <small> " . TITULO_EMPRESA . "</small>";
        $data['fileJS'] = "funcionesClienteMiller.js";
        $this->views->getView($this, "index", $data);
    }

    public function getClientes()
    {
        //if ($_SESSION['permisosMod']['r']) {
        $arrData = $this->model->consultarDatos();
        for ($i = 0; $i < count($arrData); $i++) {
            $btnOpciones = "";
            if ($arrData[$i]['Estado'] == 1) {
                $arrData[$i]['Estado'] = '<span class="badge badge-success">Activo</span>';
            } else {
                $arrData[$i]['Estado'] = '<span class="badge badge-danger">Inactivo</span>'; //target="_blanck"
            }

            /*if ($_SESSION['permisosMod']['r']) {
					$btnOpciones .= '<button class="btn btn-info btn-sm btnViewInstructor" onClick="fntViewInstructor(\'' . $arrData[$i]['Ids'] . '\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
				}
				if ($_SESSION['permisosMod']['u']) {
					$btnOpciones .= '<button class="btn btn-primary  btn-sm btnEditInstructor" onClick="fntEditInstructor(\'' . $arrData[$i]['Ids'] . '\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
				}
				if ($_SESSION['permisosMod']['d']) {
					$btnOpciones .= '<button class="btn btn-danger btn-sm btnDelInstructor" onClick="fntDeleteInstructor(' . $arrData[$i]['Ids'] . ')" title="Eliminar Datos"><i class="fa fa-trash"></i></button>';
				}*/
            $btnOpciones .= '<button class="btn btn-info btn-sm btnViewInstructor" onClick="fntViewInstructor(\'' . $arrData[$i]['Ids'] . '\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
            $btnOpciones .= '<button class="btn btn-primary  btn-sm btnEditInstructor" onClick="fntEditInstructor(\'' . $arrData[$i]['Ids'] . '\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
            $btnOpciones .= '<button class="btn btn-danger btn-sm btnDelInstructor" onClick="fntDeleteInstructor(' . $arrData[$i]['Ids'] . ')" title="Eliminar Datos"><i class="fa fa-trash"></i></button>';
            $arrData[$i]['options'] = '<div class="text-center">' . $btnOpciones . '</div>';
        }
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        //}
        die();
    }
}
