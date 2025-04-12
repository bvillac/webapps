<?php
require_once("Models/TiendaModel.php");
require_once("Models/ClientePedidoModel.php");
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
        foreach ($arrData as &$objData) {
            $objData['Estado'] = $objData['Estado'] == 1
                ? '<span class="badge badge-success">Activo</span>'
                : '<span class="badge badge-danger">Inactivo</span>';
            $objData['options'] = $this->getArrayOptions($objData['Ids']);
        }
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        exit();
    }

    private function getArrayOptions($id)
    {
        $options = '<div class="text-center">';
        if ($_SESSION['permisosMod']['r']) {
            $options .= '<button class="btn btn-info btn-sm btnViewLinea" onClick="fntViewTienda(\'' . $id . '\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
        }
        if ($_SESSION['permisosMod']['u']) {
            $options .= '<button class="btn btn-primary  btn-sm btnEditLinea" onClick="editarTienda(\'' . $id . '\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
        }
        if ($_SESSION['permisosMod']['d']) {
            $options .= " <button class='btn btn-danger btn-sm btnDelLinea' onClick='fntDeleteTienda($id)' title='Eliminar'><i class='fa fa-trash'></i></button> ";
        }
        $options .= " <a title='Catálogo' href='" . base_url() . "/tienda/catalogo/$id' class='btn btn-primary btn-sm'><i class='fa fa-list'></i></a> ";
        return $options . '</div>';
    }

    public function nuevo()
    {
        checkPermission('r', 'dashboard');
        $data = getPageData("Nuevo Pedido Web", "pedidoWeb");
        $cliIds=retornarDataSesion("Cli_Id");
        $data['tienda'] = (new TiendaModel())->consultarTiendaCliente($cliIds);
        $data['Cliente'] = (new ClientePedidoModel())->consultarDatosId($cliIds);
        $data['nombreCliente'] = htmlspecialchars($data['Cliente']['Nombre'], ENT_QUOTES, 'UTF-8');
        $this->views->getView($this, "nuevo", $data);

            
    }

    public function retornarDatosTienda(){
		//dep($_POST);
		if($_POST){
			$data=recibirData($_POST['data']);         
			if(empty($data['ids']) ){
				$arrResponse = array('status' => false, 'msg' => 'Error de datos' );
			}else{
				$ids = intval(strClean($data['ids']));
                $arrData = (new TiendaModel())->consultarDatosId($ids);
                $cliIds=retornarDataSesion("Cli_Id");
                $arrData['Items']=$this->model->listarItemsTiendas($ids,$cliIds);
                $arrData['SaldoTienda']=$this->model->recuperarSaldoTienda($ids,$cliIds);
                putMessageLogFile($arrData['SaldoTienda']);
				if(empty($arrData)){
					$arrResponse = array('status' => false, 'msg' => 'La tienda no Existe.' ); 
				}else{	
					$arrResponse = array('status' => true, 'data' => $arrData);
				}
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		exit();
	}

    public function ingresarPedidoTemp()
    {
        if ($_POST) {
            //dep($_POST);
            //var dataPost = { accion: accion,tienda_id: tiendaSeleccionada, productos: productosModificados };
            $data = recibirData($_POST['data']);
            if (empty($data['productos']) || empty($data['accion']) || empty($data['tienda_id'])) {
                $arrResponse = array('status' => false, 'msg' => 'Error no se recibieron todos los datos necesarios');
            } else {
                $request = "";
                $datos = isset($data['productos']) ? $data['productos'] : array();
                $idTienda = isset($data['tienda_id']) ? $data['tienda_id'] : 0;
                $total = isset($data['total']) ? $data['total'] : 0;
                $accion = isset($data['accion']) ? $data['accion'] : "";

                if ($accion == "Create") {
                    $option = 1;
                    if ($_SESSION['permisosMod']['w']) {
                        $request = $this->model->insertData($datos,$idTienda,$total);
                    }
                } else {
                    //$option = 2;
                    //if ($_SESSION['permisosMod']['u']) {
                    //    $request = $this->model->updateData($datos);
                    //}
                }
                if ($request["status"]) {
                    if ($option == 1) {
                        //Enviar correo
                        $arrResponse = array('status' => true, 'numero' => $request["numero"], 'msg' => 'Datos guardados correctamente.');
                    } else {
                        $arrResponse = array('status' => true, 'numero' => $request["numero"], 'msg' => 'Datos Actualizados correctamente.');
                    }
                } else {
                    $arrResponse = array("status" => false, "msg" => $request["message"]);
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    private function enviarMail()
    {
        $this->model->sendMailPedidosTemp($arroout["data"]);
        $objUser = $ModUsu->recuperarUserCorreoTiendaSUP($tieId, 8, $cli_Id);//Recupera Usuairos Superviswor
        //VSValidador::putMessageLogFile($objUser);
        $CabPed[0]["CorreoUser"] = $objUser["USU_CORREO"];
        $CabPed[0]["NombreUser"] = $objUser["USU_NOMBRE"];
        //VSValidador::putMessageLogFile($CabPed);

        $nomEmpresa = "NOBMRE";
        $valorNeto = $CabPed[0]["ValorNeto"];
        $Asunto = "$valorNeto ($nomEmpresa) Pedido en línea realizado con éxito!";
        $Titulo = "";
        $htmlMail = $this->renderPartial(
            'mensaje',
            array(
                'CabPed' => $CabPed,
                'TituloData' => "PEDIDO EN LÍNEA REALIZADO CON ÉXITO!!",
                'Estado' => "R",
            ),
            true
        );
        //$dataMail->enviarRevisado($htmlMail,$CabPed);
        $dataMail->enviarNotificacion($htmlMail, $CabPed, $Asunto, $Titulo);
    }



    
}
