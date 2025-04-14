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
        $cliIds = retornarDataSesion("Cli_Id");
        $data['tienda'] = (new TiendaModel())->consultarTiendaCliente($cliIds);
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

    

    private function enviarMail()
    {
        /*$this->model->sendMailPedidosTemp($arroout["data"]);
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
        $dataMail->enviarNotificacion($htmlMail, $CabPed, $Asunto, $Titulo);*/
    }


    public function ingresarPedidoTemp()
    {
        if ($_POST) {
            $data = recibirData($_POST['data']);
            if (empty($data['productos']) || empty($data['accion']) || empty($data['tienda_id'])) {
                $arrResponse = array('status' => false, 'msg' => 'Error no se recibieron todos los datos necesarios');
            } else {
                $datos = $data['productos'];
                $idTienda = $data['tienda_id'];
                $total = $data['total'];
                $accion = $data['accion'];
                //$request = "";

                if ($accion == "Create") {
                    $option = 1;
                    if ($_SESSION['permisosMod']['w']) {
                        $request = $this->model->insertData($datos, $idTienda, $total);
                    }
                } else {
                    //$option = 2;
                    //if ($_SESSION['permisosMod']['u']) {
                    //    $request = $this->model->updateData($datos);
                    //}
                }
                $request["status"]=true;
                if ($request["status"]) {
                    $idPedido = 1;//$request["numero"];

                    if ($option == 1) {
                        // Ejecutar script externo en segundo plano
                        /*$pedido = [
                            ['codigo' => 'P001', 'nombre' => 'Lápiz', 'cantidad' => 10, 'precio' => 0.5, 'total' => 5.0],
                            ['codigo' => 'P002', 'nombre' => 'Cuaderno', 'cantidad' => 5, 'precio' => 2.0, 'total' => 10.0],
                        ];
                        $dataExec = [
                            'destinatario' => 'byron_villacresesf@hotmail.com',
                            'asunto' => 'Confirmación de Pedido',
                            'pedido' => $pedido,  // array con los productos
                            'bcc' => 'admin@correo.com',
                            'cli_id' =>1
                        ];
                        //$rutaScript = __DIR__ . "/EnviarMail.php";
                        $rutaScript = "EnviarMail.php";
                        putMessageLogFile($rutaScript);
                        $comando = "php " . escapeshellarg($rutaScript) . " " . escapeshellarg(json_encode($dataExec)) . " > /dev/null 2>&1 &";
                        exec($comando); // Sin esperar respuesta*/

                        //Recupera infor de CabTemp  para enviar info al supervisor de tienda
                        //$CabPed=$this->model->sendMailPedidosTemp($request["numero"]);
                        $CabPed=$this->model->sendMailPedidosTemp(17);
                        $cliId = retornarDataSesion('Cli_Id');
                        //$objUser=$this->model->recuperarUserCorreoTiendaSUP($idTienda,16,$cliId);//Recupera Usuairos Superviswor
                        $CabPed[0]["correouser"]='byron_villacresesf@hotmail.com';//$objUser["usu_correo"];
                        $CabPed[0]["nombreuser"]='Byron Villacreses';//$objUser["usu_nombre"];
                        //putMessageLogFile($CabPed);
                        putMessageLogFile("ant1");
                        
                        //$htmlMail=getFile("Template/Email/email_bienvenida", $CabPed[0]);

                        
                        putMessageLogFile($htmlMail);

                        // ob_start();
                        // $data = $CabPed[0]; // pasa variables necesarias
                        // include 'Views/Template/Email/email_bienvenida.php';
                        // $htmlMail = ob_get_clean();
                        $htmlMail='hola';
           
                        putMessageLogFile("ant2");
                        $mailer  = new MailSystem();
                        putMessageLogFile("paso");
                        $resultado = $mailer ->enviarNotificacion(
                            'byron_villacresesf@hotmail.com',
                            'Confirmación de Pedido',
                            $htmlMail,
                            '',                              // Aquí va el PDF generado
                            'byronvillacreses@gmail.com' ,         // BCC
                            true
                        );
                        putMessageLogFile($resultado);

                        $arrResponse = array('status' => true, 'numero' => $idPedido, 'msg' => 'Datos guardados correctamente.');
                    } else {
                        $arrResponse = array('status' => true, 'numero' => $idPedido, 'msg' => 'Datos actualizados correctamente.');
                    }
                } else {
                    $arrResponse = array("status" => false, "msg" => $request["message"]);
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        exit();
    }





}
