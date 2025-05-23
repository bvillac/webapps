<?php
require_once("Models/UsuariosModel.php");
require_once("Models/PersonaModel.php");
require_once("Models/EmpresaModel.php");
require_once("Models/AcademicoModel.php");
require_once("Models/ValoracionModel.php");
use Spipu\Html2Pdf\Html2Pdf;
require 'vendor/autoload.php';

class Usuarios extends Controllers
{
	public function __construct()
	{
		parent::__construct();
		sessionStart();
		getPermisos();
	}

	public function Usuarios()
	{
		//control de Acceso por Roles
		checkPermission('r', 'dashboard');
		$data = getPageData("Usuarios", "Usuario");
		$data['usuario_rol'] = $this->model->consultarRoles();
		$data['empresas'] = (new EmpresaModel())->consultarEmpresas();
		$this->views->getView($this, "usuarios", $data);
	}

	public function getUsuarios()
	{
		$arrData = (new UsuariosModel())->consultarDatos();
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
			$options .= '<button class="btn btn-info btn-sm btnViewUsu" onClick="fntViewUsu(\'' . $id . '\')" title="Ver Datos"><i class="fa fa-eye"></i></button>';
		}
		if ($_SESSION['permisosMod']['u']) {
			$options .= '<button class="btn btn-primary  btn-sm btnEditUsu" onClick="fntEditUsu(\'' . $id . '\')" title="Editar Datos"><i class="fa fa-pencil"></i></button>';
		}
		if ($_SESSION['permisosMod']['d']) {
			$options .= " <button class='btn btn-danger btn-sm btnDelUsu' onClick='fntDelUsu($id)' title='Eliminar'><i class='fa fa-trash'></i></button> ";
		}

		$options .= " <a title='Evaluar Beneficiario' href='" . base_url() . "/Usuarios/rol/$id' class='btn btn-primary btn-sm'><i class='fa fa-list-alt'></i></a> ";
		$options .= '<button class="btn btn-primary  btn-sm btnEmpresa" onClick="fntAsigEmpresa(\'' . $id . '\')" title="Asignar Empresa"><i class="fa fa-pencil"></i></button>';
		return $options . '</div>';
	}

	public function getRolesUsu()
	{
		$model = new UsuariosModel();
		$htmlOptions = "";
		$arrData = $model->consultarRoles();
		if (count($arrData) > 0) {
			$htmlOptions = '<option value="0">SELECCIONAR</option>';
			for ($i = 0; $i < count($arrData); $i++) {
				$htmlOptions .= '<option value="' . $arrData[$i]['Ids'] . '">' . $arrData[$i]['Nombre'] . '</option>';
			}
		}
		echo $htmlOptions;
		die();
	}

	public function setUsuario()
	{

		if ($_POST) {//Recibe los datos Post
			$model = new UsuariosModel();
			if (
				empty($_POST['txt_dni']) || empty($_POST['txt_nombre']) || empty($_POST['txt_apellido']) || empty($_POST['dtp_fecha_nacimiento']) ||
				empty($_POST['txt_direccion']) || empty($_POST['txt_alias']) || empty($_POST['cmb_genero']) ||
				empty($_POST['txt_telefono']) || empty($_POST['txt_correo']) || empty($_POST['cmb_estado'])
			) {//empty($_POST['cmb_rol']) ||
				$arrResponse = array("status" => false, "msg" => 'Datos Ingresados incorrectos.');
			} else {
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
				if ($usu_id == 0) {
					$option = 1;
					$Clave = empty($_POST['txt_Password']) ? hash("SHA256", passGenerator()) : hash("SHA256", $_POST['txt_Password']);
					$result = $model->insertData(
						$Dni,
						$FecNaci,
						$Nombre,
						$Apellido,
						$Telefono,
						$Correo,
						$Clave,
						$Genero,
						$Direccion,
						$Alias,
						$rol_id,
						$estado
					);
				} else {
					$option = 2;
					$Clave = empty($_POST['txt_Password']) ? "" : hash("SHA256", $_POST['txt_Password']);
					$result = $model->updateData(
						$usu_id,
						$Dni,
						$FecNaci,
						$Nombre,
						$Apellido,
						$Telefono,
						$Correo,
						$Clave,
						$Genero,
						$Direccion,
						$Alias,
						$rol_id,
						$estado,
						$per_id,
						$eusu_id
					);

				}
				if ($result["status"]) {
					if ($option == 1) {
						$arrResponse = array('status' => true, 'dato' => $result["numero"], 'msg' => 'Datos guardados correctamente.');
					} else {
						$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
					}
				} else if ($result["exist"] == 'exist') {
					$arrResponse = array('status' => false, 'msg' => '¡Atención! el email o la identificación ya existe, ingrese otro.');
				} else {
					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}



	public function getUsuario(int $ids)
	{
		$ids = intval(strClean($ids));
		$model = new UsuariosModel();
		if ($ids > 0) {
			$arrData = $model->consultarDatosId($ids);
			//dep($arrData);
			if (empty($arrData)) {

				$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
			} else {
				$arrData['RolID'] = 4;//$_SESSION['usuarioData']['RolID'];//Usuario por Defecto
				$arrResponse = array('status' => true, 'data' => $arrData);
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function generarReporteUsuarioPDF($idUsuario)
	{
		if ($_SESSION['permisosMod']['r']) {
			if (is_string($idUsuario)) {
				$idpersona = "";
				//if($_SESSION['permisosMod']['r'] and $_SESSION['userData']['idrol'] == RCLIENTES){
				//	$idpersona = $_SESSION['userData']['idpersona'];
				//}
				$model = new UsuariosModel;
				$data = $model->consultarReporteUsuarioPDF($idUsuario, $idpersona);
				if (empty($data)) {
					echo "Datos no encontrados";
				} else {
					$idUsuario = $data['cabReporte']['usu_id'];
					ob_end_clean();
					$html = getFile("Template/Modals/ReporteUsuarioPDF", $data);
					$html2pdf = new Html2Pdf('p', 'A4', 'es', 'true', 'UTF-8');
					$html2pdf->writeHTML($html);
					$Object = new DateTime();
					$FechaActual = date('m-d-Y H:i:s a', time());
					$html2pdf->output('ReporteUsuarios_' . $FechaActual . '.pdf');
				}
			} else {
				echo "Dato no válido";
			}
		} else {
			header('Location: ' . base_url() . '/login');
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

	public function getUsuariosReporte()
	{
		if ($_SESSION['permisosMod']['r']) {
			$model = new UsuariosModel;
			$arrData = $model->consultarDatosUsuarios();
			for ($i = 0; $i < count($arrData); $i++) {
				$btnOpciones = "";
				if ($_SESSION['permisosMod']['r']) {
					$btnOpciones .= ' <a title="Generar PDF" href="' . base_url() . '//generarReporteUsuarioPDF/' . $arrData[$i]['Ids'] . '" target="_blanck" class="btn btn-primary btn-sm"> <i class="fa fa-file-pdf-o"></i> </a> ';
				}

				$arrData[$i]['options'] = '<div class="text-center">' . $btnOpciones . '</div>';

			}
			echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function delUsuario()
	{
		if ($_POST) {
			$Ids = intval($_POST['Ids']);
			$model = new UsuariosModel();
			$request = $model->deleteUsuario($Ids);
			if ($request) {
				$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Registro');
			} else {
				$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Registro.');
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function perfil()
	{
		checkPermission('r', 'dashboard');
		$data = getPageData("Perfil", "perfil");
		$this->views->getView($this, "perfil", $data);
	}

	public function setPerfil()
	{
		if ($_POST) {
			if (empty($_POST['txt_nombre']) || empty($_POST['txt_apellido']) || empty($_POST['txt_Telefono']) || empty($_POST['txt_direccion']) || empty($_POST['txt_alias'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			} else {
				$model = new UsuariosModel;
				$idUsuario = $_SESSION['Usu_id'];
				$strNombre = strClean($_POST['txt_nombre']);
				$strApellido = strClean($_POST['txt_apellido']);
				$intTelefono = intval(strClean($_POST['txt_Telefono']));
				$strDireccion = strClean($_POST['txt_direccion']);
				$strAlias = strClean($_POST['txt_alias']);
				$strPassword = "";
				if (!empty($_POST['txt_Password'])) {
					$strPassword = hash("SHA256", $_POST['txt_Password']);
				}
				$request = $model->updateDataPerfil($idUsuario, $strNombre, $strApellido, $intTelefono, $strDireccion, $strAlias, $strPassword);
				if ($request) {
					sessionUsuario($_SESSION['Usu_id']);
					$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
				} else {
					$arrResponse = array("status" => false, "msg" => 'No es posible actualizar los datos.');
				}
			}
			sleep(3);//Hace una espera para retorna datos
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
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
					$data['control'] = (new AcademicoModel())->consultarBenefId($ids);
					$data['valoracion'] = (new ValoracionModel())->consultarValoracion();
					$data['porcentaje'] = range(0, 100);
					$data['page_tag'] = "Control Académico";
					$data['page_name'] = "Control Académico";
					$data['page_title'] = "Control Académico <small> " . $_SESSION['empresaData']['NombreComercial'] . "</small>";
					$data['page_back'] = "academico";
					$this->views->getView($this, "rol", $data);
				}
			} else {
				echo "Dato no válido";
			}
		} else {
			header('Location: ' . base_url() . '/login');
			exit();
		}
		exit();
	}


	public function consultarUserID(){
		//dep($_POST);
		if($_POST){
			$data=recibirData($_POST['data']);
			if(empty($data['ids']) ){
				$arrResponse = array('status' => false, 'msg' => 'Error de datos' );
			}else{
				$ids = intval(strClean($data['ids']));
				$arrData = (new UsuariosModel())->consultarDatosId($ids);
				
				
				if(empty($arrData)){
					$arrResponse = array('status' => false, 'msg' => 'El usuario no se encontro.' ); 
				}else{	
					$arrData['empresas'] = (new EmpresaModel())->consultarEmpresaUsuarioAsingado($ids);
					$arrResponse = array('status' => true, 'data' => $arrData);

				}
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		exit();
	}


	public function buscarAutoUsuario()
    {
        try {
			
            $inputData=validarMetodoPost();           
            // Sanitizar y obtener los valores con seguridad
            $parametro = isset($inputData['parametro']) ? filter_var($inputData['parametro'], FILTER_SANITIZE_STRING) : "";
            $limit = isset($inputData['limit']) ? filter_var($inputData['limit'], FILTER_VALIDATE_INT) : 10;

            // Validar parámetros obligatorios
            //if (!$cli_id || !$tie_id) {
            //    throw new Exception("Parámetros insuficientes", 400);
            //}

			$request = (new PersonaModel())->consultarDatosCedulaNombres($parametro);

            // Responder con los datos obtenidos o mensaje de error
            $arrResponse = $request
                ? ['status' => true, 'data' => $request, 'msg' => 'Datos retornados correctamente.']
                : ['status' => false, 'msg' => 'No existen datos.'];
        } catch (Exception $e) {
            $arrResponse = ['status' => false, 'msg' => $e->getMessage()];
			logFileSystem("Error en consutla Catalogo: " . $e->getMessage(),"ERROR");
        }

        // Responder con JSON
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit();
    }




}
?>