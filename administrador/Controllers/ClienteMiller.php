<?php
use Spipu\Html2Pdf\Html2Pdf;
require 'vendor/autoload.php';
require_once("Models/PagoModel.php");
require_once("Models/UsuariosModel.php");
class ClienteMiller extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        sessionStart();
        getPermisos();
    }


    public function clientemiller()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        $data['page_tag'] = "Cliente";
        $data['page_name'] = "Cliente";
        $data['page_title'] = "Cliente <small> " . TITULO_EMPRESA . "</small>";
        $data['page_back'] = "clientemiller";
        $this->views->getView($this, "clientemiller", $data);
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
                    $btnOpciones .= ' <a title="Editar Datos" href="' . base_url() . '/ClienteMiller/editar/' . $arrData[$i]['Ids'] . '"  class="btn btn-primary btn-sm"> <i class="fa fa-pencil"></i> </a> ';
                }
                if ($_SESSION['permisosMod']['d']) {
                    $btnOpciones .= '<button class="btn btn-danger btn-sm btnDelInstructor" onClick="fntDeleteCliente(' . $arrData[$i]['Ids'] . ')" title="Eliminar Datos"><i class="fa fa-trash"></i></button>';
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
        $data['ocupacion'] = $this->model->consultarProfesion();
        $data['usuario_rol'] = $usuarioRol->consultarRoles();
        $data['forma_pago'] = $formaPago->consultarPago();
        $data['page_tag'] = "Cliente";
        $data['page_name'] = "Cliente";
        $data['page_title'] = "Cliente <small> " . TITULO_EMPRESA . "</small>";
        $data['page_back'] = "clienteMiller";
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
                    $usuarioRol = new UsuariosModel();
                    $data['ocupacion'] = $this->model->consultarProfesion();
                    $data['forma_pago'] = $formaPago->consultarPago();
                    $data['page_tag'] = "Editar Cliente";
                    $data['page_name'] = "Editar Cliente";
                    $data['page_title'] = "Editar Cliente <small> " . TITULO_EMPRESA . "</small>";
                    $data['page_back'] = "clienteMiller";
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



}
