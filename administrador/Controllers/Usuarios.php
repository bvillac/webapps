<?php 
	require_once("Models/UsuariosModel.php");
	require_once("Models/PersonaModel.php");
	require_once("Models/EmpresaModel.php");
	use Spipu\Html2Pdf\Html2Pdf;
	require 'vendor/autoload.php';
	
	class Usuarios extends Controllers{
		public function __construct(){
			parent::__construct();
        	sessionStart();
        	getPermisos();
			/*parent::__construct();
			session_start();
			//session_regenerate_id(true);//Seguridad en Session hace que IDsesion Php anteriores se eliminen y que no se puedan usar
			if(empty($_SESSION['loginEstado'])){
				header('Location: '.base_url().'/login');
				die();
			}
			getPermisos(3);//Control de Permisos Segun el Ids de la base de Datos*/
		}

		public function Usuarios(){
			//control de Acceso por Roles
			
			if(empty($_SESSION['permisosMod']['r'])){//si no existe lo redirecciona
				header("Location:".base_url().'/dashboard');//Redirecciona al dashboard
			}
			$data['usuario_rol'] = $this->model->consultarRoles();
			$data['page_tag'] = "Usuarios";
			$data['page_name'] = "Usuarios";
			$data['page_title'] = "Usuarios <small> ".TITULO_EMPRESA ."</small>";
			$this->views->getView($this,"usuarios",$data);
			
		}

		public function getRolesUsu(){	
			$model=new UsuariosModel();
			$htmlOptions = "";
			$arrData = $model->consultarRoles();
			if(count($arrData) > 0 ){
				$htmlOptions = '<option value="0">SELECCIONAR</option>';
				for ($i=0; $i < count($arrData); $i++) { 
						$htmlOptions .= '<option value="'.$arrData[$i]['Ids'].'">'.$arrData[$i]['Nombre'].'</option>';
				}
			}
			echo $htmlOptions;
			die();		
		}

		public function setUsuario(){
			
			if($_POST){//Recibe los datos Post
				$model=new UsuariosModel();
				if(empty($_POST['txt_dni']) || empty($_POST['txt_nombre']) || empty($_POST['txt_apellido']) ||  empty($_POST['dtp_fecha_nacimiento']) || 
					empty($_POST['txt_direccion']) || empty($_POST['txt_alias']) || empty($_POST['cmb_genero']) ||	
					empty($_POST['txt_telefono']) || empty($_POST['txt_correo']) ||  empty($_POST['cmb_estado']) ){//empty($_POST['cmb_rol']) ||
					$arrResponse = array("status" => false, "msg" => 'Datos Ingresados incorrectos.');
				}else{ 
					$usu_id = intval($_POST['txth_ids']);
					$per_id = intval($_POST['txth_perids']);
					$eusu_id = intval($_POST['txth_eusuids']);

					$Dni = strClean($_POST['txt_dni']);
					$FecNaci = strClean($_POST['dtp_fecha_nacimiento']);
					$Nombre = ucwords(strClean($_POST['txt_nombre']));
					$Apellido = ucwords(strClean($_POST['txt_apellido']));
					$Telefono = intval(strClean($_POST['txt_telefono']));
					$Correo = strtolower(strClean($_POST['txt_correo']));
					$Direccion = strClean($_POST['txt_direccion']);
					$Alias = strtolower(strClean($_POST['txt_alias']));
					$Genero = strtoupper(strClean($_POST['cmb_genero']));
					$rol_id = intval(strClean($_POST['cmb_rol']));
					$estado = intval(strClean($_POST['cmb_estado']));
					if($usu_id == 0){
						$option = 1;
						$Clave =  empty($_POST['txt_Password']) ? hash("SHA256",passGenerator()) : hash("SHA256",$_POST['txt_Password']);
						$result = $model->insertData($Dni,$FecNaci,$Nombre,	$Apellido,$Telefono, 
													$Correo,$Clave,$Genero,$Direccion,$Alias,$rol_id,$estado );						
					}else{
						$option = 2;
						$Clave =  empty($_POST['txt_Password']) ? "" : hash("SHA256",$_POST['txt_Password']);
						$result = $model->updateData($usu_id,$Dni, $FecNaci, $Nombre,$Apellido, $Telefono, 
													$Correo,$Clave,	$Genero, $Direccion, $Alias, $rol_id,$estado,$per_id,$eusu_id );	

					}
					if($result["status"]){
						if($option == 1){
							$arrResponse = array('status' => true, 'dato' => $result["numero"],'msg' => 'Datos guardados correctamente.');
						}else{
							$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
						}
					}else if($result["exist"] == 'exist'){
						$arrResponse = array('status' => false, 'msg' => '¡Atención! el email o la identificación ya existe, ingrese otro.');		
					}else{
						$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
					}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function getUsuarios(){
			$model=new UsuariosModel;
			$arrData = $model->consultarDatos();
			for ($i=0; $i < count($arrData); $i++) {
				$btnOpciones="";
				if($arrData[$i]['Estado'] == 1)
				{
					$arrData[$i]['Estado'] = '<span class="badge badge-success">Activo</span>';
				}else{
					$arrData[$i]['Estado'] = '<span class="badge badge-danger">Inactivo</span>';
				}

				
				if($_SESSION['permisosMod']['r']){
					$btnOpciones .='<button class="btn btn-info btn-sm btnViewUsu" onClick="fntViewUsu('.$arrData[$i]['Ids'].')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
				}
				if($_SESSION['permisosMod']['u']){
					$btnOpciones .='<button class="btn btn-primary  btn-sm btnEditUsu" onClick="fntEditUsu('.$arrData[$i]['Ids'].')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
				}
				if($_SESSION['permisosMod']['d']){
					$btnOpciones .='<button class="btn btn-danger btn-sm btnDelUsu" onClick="fntDelUsu('.$arrData[$i]['Ids'].')" title="Eliminar Datos"><i class="fa fa-trash"></i></button>';
				}
				if ($_SESSION['permisosMod']['u']) {
                    $btnOpciones .= ' <a title="Evaluar Beneficiario" href="' . base_url() . '/Usuario/rol/' . $arrData[$i]['Ids'] . '"  class="btn btn-primary btn-sm"> <i class="fa fa-list-alt"></i> </a> ';
                }
				//$btnOpciones .='<button class="btn btn-info btn-sm btnViewUsu" onClick="fntViewUsu('.$arrData[$i]['Ids'].')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
				//$btnOpciones .='<button class="btn btn-primary  btn-sm btnEditUsu" onClick="fntEditUsu('.$arrData[$i]['Ids'].')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
				//$btnOpciones .='<button class="btn btn-danger btn-sm btnDelUsu" onClick="fntDelUsu('.$arrData[$i]['Ids'].')" title="Eliminar Datos"><i class="fa fa-trash"></i></button>';
				$arrData[$i]['options'] = '<div class="text-center">'.$btnOpciones.'</div>';
			}
      
			
			echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			die();
		}

		public function getUsuario(int $ids){
			$ids = intval(strClean($ids));
			$model=new UsuariosModel();
			if($ids > 0){
				$arrData = $model->consultarDatosId($ids);			
				//dep($arrData);
				if(empty($arrData)){
					
					$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
				}else{
					$arrData['RolID']=4;//$_SESSION['usuarioData']['RolID'];//Usuario por Defecto
					$arrResponse = array('status' => true, 'data' => $arrData);
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function generarReporteUsuarioPDF($idUsuario){
			if($_SESSION['permisosMod']['r']){
				if(is_string($idUsuario)){
					$idpersona = "";
					//if($_SESSION['permisosMod']['r'] and $_SESSION['userData']['idrol'] == RCLIENTES){
					//	$idpersona = $_SESSION['userData']['idpersona'];
					//}
					$model=new UsuariosModel;
					$data = $model->consultarReporteUsuarioPDF($idUsuario, $idpersona);
					//putMessageLogFile($data);
					if(empty($data)){
						echo "Datos no encontrados";
					}else{
						$idUsuario = $data['cabReporte']['usu_id'];
						ob_end_clean();
						$html =getFile("Template/Modals/ReporteUsuarioPDF",$data);
						$html2pdf = new Html2Pdf('p','A4','es','true','UTF-8');
						$html2pdf->writeHTML($html);
						$Object = new DateTime(); 
					    $FechaActual= date('m-d-Y H:i:s a', time()); 
						$html2pdf->output('ReporteUsuarios_'.$FechaActual.'.pdf');
					}
				}else{
					echo "Dato no válido";
				}
			}else{
				header('Location: '.base_url().'/login');
				die();
			}
		}


	public function reporteUsuariosPDF()
	{
		if ($_SESSION['permisosMod']['r']) {

			//if($_SESSION['permisosMod']['r'] and $_SESSION['userData']['idrol'] == RCLIENTES){
			//	$idpersona = $_SESSION['userData']['idpersona'];
			//}
			$data['Result'] = $this->model->consultarDatosUsuarios();
			//putMessageLogFile($data);
			if (empty($data)) {
				echo "Datos no encontrados";
			} else {
				//$idUsuario = $data['cabReporte']['usu_id'];
				ob_end_clean();
				$ObjEmp = new EmpresaModel;
				$data['EmpData'] = $ObjEmp->consultarEmpresaId();
				$data['Titulo'] = "Lista Usuarios Activos";
				$html = getFile("Usuarios/Reporte/usuarioPDF", $data);
				$html2pdf = new Html2Pdf('p', 'A4', 'es', 'true', 'UTF-8');
				$html2pdf->writeHTML($html);
				$FechaActual = date('m-d-Y H:i:s a', time());
				$html2pdf->output('ReporteUsuarios_' . $FechaActual . '.pdf', 'D');
			}
		} else {
			header('Location: ' . base_url() . '/login');
			die();
		}
	}

    	public function getUsuariosReporte(){			
			if($_SESSION['permisosMod']['r']){
				$model=new UsuariosModel;
			    $arrData = $model->consultarDatosUsuarios();				
				for ($i=0; $i < count($arrData); $i++) {
					$btnOpciones="";				
					if($_SESSION['permisosMod']['r']){
						$btnOpciones .=' <a title="Generar PDF" href="'.base_url().'//generarReporteUsuarioPDF/'.$arrData[$i]['Ids'].'" target="_blanck" class="btn btn-primary btn-sm"> <i class="fa fa-file-pdf-o"></i> </a> ';
					}
					
					$arrData[$i]['options'] = '<div class="text-center">'.$btnOpciones.'</div>';
					
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
			die();
		}
		
		public function delUsuario(){
			if($_POST){
				$Ids = intval($_POST['Ids']);
				$model=new UsuariosModel();
				$request = $model->deleteUsuario($Ids);
				if($request){
					$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Registro');
				}else{
					$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Registro.');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function perfil(){
			$data['page_tag'] = "Perfil";			
			$data['page_title'] = "Perfil  <small> ".TITULO_EMPRESA ."</small>";
			$data['page_name'] = "Perfil Usuario";
			$data['fileJS'] = "funcionesUsuarios.js";
			$this->views->getView($this,"perfil",$data);
		}

		public function setPerfil(){
			if($_POST){
				if( empty($_POST['txt_nombre']) || empty($_POST['txt_apellido']) || empty($_POST['txt_Telefono']) || empty($_POST['txt_direccion']) || empty($_POST['txt_alias']) )
				{
					$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
				}else{
					$model=new UsuariosModel;
					$idUsuario = $_SESSION['idsUsuario'];
					$strNombre = strClean($_POST['txt_nombre']);
					$strApellido = strClean($_POST['txt_apellido']);
					$intTelefono = intval(strClean($_POST['txt_Telefono']));
					$strDireccion = strClean($_POST['txt_direccion']);
					$strAlias = strClean($_POST['txt_alias']);
					$strPassword = "";
					if(!empty($_POST['txt_Password'])){
						$strPassword = hash("SHA256",$_POST['txt_Password']);
					}
					$request = $model->updateDataPerfil($idUsuario,$strNombre,$strApellido,$intTelefono,$strDireccion,$strAlias,$strPassword);
					if($request){
						sessionUsuario($_SESSION['idsUsuario']);
						$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
					}else{
						$arrResponse = array("status" => false, "msg" => 'No es posible actualizar los datos.');
					}
				}
				sleep(3);//Hace una espera para retorna datos
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}


	public function rol($ids)
    {
        if ($_SESSION['permisosMod']['r']) {
            if (is_numeric($ids)) {
                $data = $this->model->consultarDatosId($ids);
                if (empty($data)) {
                    echo "Datos no encontrados";
                } else {
                    $data['control'] = $this->model->consultarBenefId($ids);
                    $valoracion = new ValoracionModel();
                    $data['valoracion'] = $valoracion->consultarValoracion();
                    $data['porcentaje'] = range(0, 100);
                    $data['page_tag'] = "Control Académico";
                    $data['page_name'] = "Control Académico";
                    $data['page_title'] = "Control Académico <small> " . TITULO_EMPRESA . "</small>";
                    $data['page_back'] = "academico";
                    $this->views->getView($this, "rol", $data);
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
 ?>