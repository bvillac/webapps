<?php
require_once("Models/CentroAtencionModel.php");
require_once("Models/SalonModel.php");
require_once("Models/InstructorModel.php");
require_once("Models/ActividadModel.php");
require_once("Models/ValoracionModel.php");
require_once("Models/NivelModel.php");
use Spipu\Html2Pdf\Html2Pdf;
require 'vendor/autoload.php';
class Asistencia extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        sessionStart();
        getPermisos();
    }


    public function asistencia()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        $data['horarios'] = range(8, 20);
        $modelCentro = new CentroAtencionModel();
        $data['centroAtencion'] = $modelCentro->consultarCentroEmpresa();
        $data['page_tag'] = "Asistencia";
        $data['page_name'] = "Asistencia";
        $data['page_title'] = "Asistencia <small> " . TITULO_EMPRESA . "</small>";
        $data['page_back'] = "asistencia";
        $this->views->getView($this, "asistencia", $data);
    }


    

    public function asistenciaFechaHora(){	
        if ($_POST) {
            if (empty($_POST['catId']) || empty($_POST['fechaDia'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
            } else {
                if($_SESSION['permisosMod']['r']){
                    $catId = isset($_POST['catId']) ? $_POST['catId'] : 0;
                    $plaId = 0;//isset($_POST['plaId']) ? $_POST['plaId'] : 0;
                    $insId = isset($_POST['insId']) ? $_POST['insId'] : 0;
                    $fechaDia = isset($_POST['fechaDia']) ? $_POST['fechaDia'] : '';
                    $hora = isset($_POST['hora']) ? $_POST['hora'] : 0;        
                    $arrData = $this->model->consultarAsistenciaFechaHora($catId,$plaId,$insId,$fechaDia,$hora);
                    if (empty($arrData)) {
                        $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                    } else {
                        $arrResponse = array('status' => true, 'data' => $arrData);
                    }
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }		
        die();
    }


    public function marcarAsistencia()
    {
        if ($_POST) {
            if ($_SESSION['permisosMod']['d']) {
                $ids = intval($_POST['Ids']);
                $request = $this->model->marcarAsistencia($ids);
                //$request["status"]=true;
                if ($request["status"]) {
                    $arrResponse = array('status' => true, 'msg' => 'Asistencía Registrada Correctamente');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'Error al Registrar la Asistencía.');
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }

  

    public function generarAsistenciaPDF(){
        
		if($_SESSION['permisosMod']['r']){
            if ($_GET) {
                //dep($_GET);
                if (empty($_GET['centro']) || empty($_GET['fechaDia']) ) {
                    $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
                } else {
                    $request = "";
                    //$datos = isset($_POST['reservar']) ? json_decode($_POST['reservar'], true) : array();
                    $centro = isset($_GET['centro']) ? $_GET['centro'] : "";
                    $InsId = isset($_GET['InsId']) ? $_GET['InsId'] : "";
                    $hora = isset($_GET['hora']) ? $_GET['hora'] : "";
                    $fechaDia = isset($_GET['fechaDia']) ? $_GET['fechaDia'] : "";
                    $plaId=0;
                    $dataSet = $this->model->consultarAsistenciaFechaHora($centro,$plaId,$InsId,$fechaDia,$hora);
                    $c = 0;
                    $rowData=[];
                    while ($c < sizeof($dataSet)) {
                        $thoras = $dataSet[$c]['Reservado'];
                        $h = 0;
                        $x = -1;
                        $z=0; 
                        $aux="";
                        $horas=[];
                        while ($h < sizeof($thoras)) {
                            if($aux!=$thoras[$h]['ResHora']){
                                $x++;
                                $rowData[$x]['Hora']=$thoras[$h]['ResHora'];
                                $rowData[$x]['Salon']=$thoras[$h]['SalNombre'];
                                $horas=[];
                                $aux=$thoras[$h]['ResHora'];
                                $z=0; 
                                $horas=$this->retonarHoras($thoras,$h,$horas,$z);  
                            }else{
                                $z++;
                                $horas+=$this->retonarHoras($thoras,$h,$horas,$z);
                            }
                            $h++;
                            $rowData[$x]['Horas']=$horas;
                        }
                        $dataSet[$c]['Reservado']=$rowData;
                        $c++;
                    }
                    $data['result']=$dataSet;
                    $data['fechaAsistencia']=$fechaDia;
					ob_end_clean();
                    $data['Titulo']="Asistencía de Usuarios";
					$html =getFile("Asistencia/Reporte/controlPDF",$data);
					$html2pdf = new Html2Pdf('p','A4','es','true','UTF-8');
					$html2pdf->writeHTML($html);
                    $FechaActual= date('m-d-Y H:i:s a', time()); 
                    //$html2pdf->pdf->SetDisplayMode('fullpage');
                    $html2pdf->output('CONTROL_'.$FechaActual.'.pdf','D');
                }
    
            }
            //die();
		}else{
			header('Location: '.base_url().'/login');
			die();
		}
	}

    private function retonarHoras($result,$i,$horas,$h){
        $horas[$h]['ResId']=$result[$i]['ResId'];
        $horas[$h]['ResHora']=$result[$i]['ResHora'];
        $horas[$h]['ActNombre']=$result[$i]['ActNombre'];
        $horas[$h]['NivNombre']=$result[$i]['NivNombre'];
        $horas[$h]['ResUnidad']=$result[$i]['ResUnidad'];
        $horas[$h]['BenId']=$result[$i]['BenId'];
        $horas[$h]['BenNombre']=$result[$i]['BenNombre'];
        $horas[$h]['SalId']=$result[$i]['SalId'];
        $horas[$h]['SalNombre']=$result[$i]['SalNombre'];
        $horas[$h]['Estado']=$result[$i]['Estado'];
        return $horas;
    }







}
