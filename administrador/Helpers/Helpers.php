<?php

//Retorla la url del proyecto

use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

function base_url()
{
    return BASE_URL_ADMIN;
}

function base_apps()
{
    return $_SERVER['DOCUMENT_ROOT'] . "/" . nameApps;
}

function media()
{
    return BASE_URL_ADMIN . "/Assets";
}

function vendor()
{
    return BASE_URL_ADMIN . "/vendor";
}

function cdnTableLink()
{
    //return cdnTable;
    return  media()."/js/cdn/i18n/Spanish.json";

}

//Archivo de Idiomas
function filelang($lang = "es", $file = "general")
{
    $langRuta =  "messages/{$lang}/{$file}.php";
    //$langRuta =  __DIR__  ."/../messages/{$lang}/{$file}.php";
    //echo $langRuta;
    require_once($langRuta);
}

//Muestra información formateada
function dep($data)
{
    $format  = print_r('<pre>');
    $format .= print_r($data);
    $format .= print_r('</pre>');
    return $format;
}

//Muestra información formateada texto
function putMessageLogFile($message)
{
    $rutaLog = __DIR__ . '/../Log/Errorlog.log'; 
    if (is_array($message))
        $message = json_encode($message);
    $message = date("Y-m-d H:i:s") . " " . $message . "\n";
    if (!is_dir(dirname($rutaLog))) {
        mkdir(dirname($rutaLog), 0777, true);
        chmod(dirname($rutaLog), 0777);
        touch($rutaLog);
    }
    //se escribe en el fichero
    file_put_contents($rutaLog, $message, FILE_APPEND | LOCK_EX);
}

function logFileSystem($message, $level = "INFO") {
    //Registra tipo de mensaje (INFO, WARNING, ERROR).
    //mkdir -p /opt/webapps/
    //chmod -R 777 /opt/webapps/

    $logDir = "/opt/webapps/log/"; // Ruta absoluta
    $logFile = $logDir . "errors.log";

    // Validar que el directorio existe, si no, crearlo con permisos seguros
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    // Formatear el mensaje si es un array u objeto
    if (is_array($message) || is_object($message)) {
        $message = json_encode($message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    // Estructura del mensaje de log
    $logEntry = sprintf("[%s] [%s] %s\n", date("Y-m-d H:i:s"), strtoupper($level), $message);

    // Abrir el archivo con bloqueo para evitar corrupción
    $fileHandle = fopen($logFile, "a");
    if ($fileHandle) {
        if (flock($fileHandle, LOCK_EX)) { // Bloqueo exclusivo
            fwrite($fileHandle, $logEntry);
            fflush($fileHandle); // Asegurar que se escriba inmediatamente
            flock($fileHandle, LOCK_UN); // Liberar bloqueo
        }
        fclose($fileHandle);
    }
}

//Agregar Archivos JS
function incluirJs()
{
    $uriData = explode("/", $_SERVER["REQUEST_URI"]);
    $controller = ucwords($uriData[3]); //Obtiene el Controlador
    $directorio = "Views/" . $controller . "/js/";
    if (!is_dir($directorio)) {
        return;
    } //Sale de la funcion si el direcctorio no existe.
    $archivos = scandir($directorio);
    foreach ($archivos as $archivo) {
        if (pathinfo($archivo, PATHINFO_EXTENSION) == 'js') {
            $archivo_origen = $directorio . $archivo;
            $rutaDestino = "Assets/temp/js/" . substr(md5($archivo), 0, 6);
            $archivo_destino = $rutaDestino . "/" . $archivo;
            if (!is_dir($rutaDestino)) { //Verficar ruta de Destino
                //Crea el Directorio y retorna un TRUE y se lo niega para que rpesente el mensaje de no creado si fallo algo
                //Si la ruta existe no no imprime nada
                if (!mkdir($rutaDestino, 0777, true)) {
                    putMessageLogFile("Directorio Js file no creado en la vista :" . $rutaDestino);
                }
            }
            // Verificar si el archivo ya existe en la carpeta temporal
            if (file_exists($archivo_destino)) {
                // Verificar si el archivo ha sido modificado desde la última vez que se importó
                if (filemtime($archivo_origen) > filemtime($archivo_destino)) {
                    // Si el archivo ha sido modificado, copiarlo a la carpeta temporal
                    copy($archivo_origen, $archivo_destino);
                }
            } else {
                // Si el archivo no existe en la carpeta temporal, copiarlo
                copy($archivo_origen, $archivo_destino);
            }

            // Imprimir la etiqueta <script> para importar el archivo JavaScript
            echo "<script src='" . base_url() . "/" . $archivo_destino . "'></script>";
        }
    }
}


//Elimina exceso de espacios entre palabras
//Para Injecciones Sql
function strClean($strCadena)
{
    $string = preg_replace(['/\s+/', '/^\s|\s$/'], [' ', ''], $strCadena); //elimina exceso de espacio entre palabras
    $string = trim($string); //Elimina espacios en blanco al inicio y al final
    $string = stripslashes($string); // Elimina las \ invertidas
    $string = str_ireplace("<script>", "", $string);
    $string = str_ireplace("</script>", "", $string);
    $string = str_ireplace("<script src>", "", $string);
    $string = str_ireplace("<script type=>", "", $string);
    $string = str_ireplace("SELECT * FROM", "", $string);
    $string = str_ireplace("DELETE FROM", "", $string);
    $string = str_ireplace("INSERT INTO", "", $string);
    $string = str_ireplace("SELECT COUNT(*) FROM", "", $string);
    $string = str_ireplace("DROP TABLE", "", $string);
    $string = str_ireplace("OR '1'='1", "", $string);
    $string = str_ireplace('OR "1"="1"', "", $string);
    $string = str_ireplace('OR ´1´=´1´', "", $string);
    $string = str_ireplace("is NULL; --", "", $string);
    $string = str_ireplace("is NULL; --", "", $string);
    $string = str_ireplace("LIKE '", "", $string);
    $string = str_ireplace('LIKE "', "", $string);
    $string = str_ireplace("LIKE ´", "", $string);
    $string = str_ireplace("OR 'a'='a", "", $string);
    $string = str_ireplace('OR "a"="a', "", $string);
    $string = str_ireplace("OR ´a´=´a", "", $string);
    $string = str_ireplace("OR ´a´=´a", "", $string);
    $string = str_ireplace("--", "", $string);
    $string = str_ireplace("^", "", $string);
    $string = str_ireplace("[", "", $string);
    $string = str_ireplace("]", "", $string);
    $string = str_ireplace("==", "", $string);
    return $string;
}
//Genera una contraseña de 10 caracteres 
function passGenerator($length = 10)
{
    $pass = "";
    $longitudPass = $length;
    $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    $longitudCadena = strlen($cadena);
    for ($i = 1; $i <= $longitudPass; $i++) {
        $pos = rand(0, $longitudCadena - 1);
        $pass .= substr($cadena, $pos, 1);
    }
    return $pass;
}
//Genera un token para recuperar correos
function token()
{
    $r1 = bin2hex(random_bytes(10));
    $r2 = bin2hex(random_bytes(10));
    $r3 = bin2hex(random_bytes(10));
    $r4 = bin2hex(random_bytes(10));
    $token = $r1 . '-' . $r2 . '-' . $r3 . '-' . $r4;
    return $token;
}
//Formato para valores monetarios puntos y comas como decimales
function formatMoney($cantidad, $numDecimal)
{
    $cantidad = number_format($cantidad, $numDecimal, SPD, SPM);
    return $cantidad;
}

//Muestra Header Admin
function adminHeader($data = "")
{
    $viewRuta = "Views/Template/admin_header.php";
    require_once($viewRuta);
}
//Muestra adminMenu
function adminMenu($data = "")
{
    $viewRuta = "Views/Template/admin_menu.php";
    require_once($viewRuta);
}
//Muestra adminFooter
function adminFooter($data = "")
{
    $viewRuta = "Views/Template/admin_footer.php";
    require_once($viewRuta);
}

//Muestra Header Tienda
function tiendaHeader($data = "")
{
    $viewRuta = "Views/Template/tienda_header.php";
    require_once($viewRuta);
}
//Muestra adminMenu
function tiendaMenu($data = "")
{
    $viewRuta = "Views/Template/tienda_menu.php";
    require_once($viewRuta);
}
//Muestra adminFooter
function tiendaFooter($data = "")
{
    $viewRuta = "Views/Template/tienda_footer.php";
    require_once($viewRuta);
}

//Muestra tiendaSlider
function tiendaSlider($data = "")
{
    $viewRuta = "Views/Template/tienda_Slider.php";
    require_once($viewRuta);
}

//Muestra tiendaBanner
function tiendaBanner($data = "")
{
    $viewRuta = "Views/Template/tienda_Banner.php";
    require_once($viewRuta);
}

//Muestra tiendaProducto
function tiendaProducto($data = "")
{
    $viewRuta = "Views/Template/tienda_Producto.php";
    require_once($viewRuta);
}


function getModal(string $nameModal, $data)
{
    $view_modal = "Views/Template/Modals/{$nameModal}.php";
    //$view_modal = "{$url}{$nameModal}.php";
    require_once $view_modal;
}

function getFile2(string $url, $data)
{
    ob_start();
    //extract($data); // Esto convierte ['nombreuser' => 'Byron'] en $nombreuser = 'Byron'
    $data = $data;
    require("Views/{$url}.php");
    return ob_get_clean();
}

function getFile(string $url, $data)
{
    ob_start();
    require_once("Views/{$url}.php");
    $file = ob_get_clean();
    return $file;
}


//Envio de correos
function enviarEmail($data, $template)
{
    $asunto = $data['asunto'];
    $paraDestino = $data['email'];
    $empresa = REMITENTE;
    $remitente = NO_RESPONDER;
    $emailCopia = !empty($data['emailCopia']) ? $data['emailCopia'] : "";
    //ENVIO DE CORREO
    $de = "MIME-Version: 1.0\r\n";
    $de .= "Content-type: text/html; charset=UTF-8\r\n";
    $de .= "From: {$empresa} <{$remitente}>\r\n";
    $de .= "Bcc: $emailCopia\r\n";
    ob_start();
    require_once("Views/Template/Email/" . $template . ".php");
    $mensaje = ob_get_clean();
    $send = mail($paraDestino, $asunto, $mensaje, $de);
    return $send;
}

function datosEmpresaEstablePunto(int $IdEmpresa)
{
    require_once("Models/EmpresaModel.php");
    $objData = new EmpresaModel();
    $request = $objData->consultarEmpresaEstPunto($IdEmpresa);
    return $request;
}


function sessionUsuario(int $idsUsuario)
{
    //putMessageLogFile("paso sessionUsuario helpers");
    /*require_once("Models/LoginModel.php");
    $objLogin = new LoginModel();
    $request = $objLogin->sessionLogin($idsUsuario);
    return $request;*/
}

/*function sessionStart(){
    // Iniciar la sesión
    session_start();
    //session_regenerate_id(true);//Cambiar la version
    session_regenerate_id();//Regenerar el ID de la sesión
    // Ahora puedes acceder al nuevo ID de sesión
    //$newSessionId = session_id();
    //putMessageLogFile("tiempo ".$_SESSION['timeout']);
    $inactive=TIMESESSION;//usuario va a permanercer logueado en segundos 60segundos 60=>30s 360>3minustos 120x1minut
    if(isset($_SESSION['timeout'])){//Ingresa solo si existe alguna sesion
        /*$session_in = time()-$_SESSION['inicio'];
        if($session_in>$inactive){//paso el tiempo en que usuario permanece logueado
            header('Location: '.base_url().'/Logout');//solo ingrsa cuando la session a caducado
        }
        //Revisa si la Session esta Activa Caso contrario la envia a login
        if(empty($_SESSION['loginEstado'])){
            header('Location: '.base_url().'/login');
            die();
        }
    }else{
        header('Location: '.base_url().'/Logout');//solo ingrsa cuando la session a caducado
    }
}*/

function sessionStart()
{
    // 1. Parámetros de la cookie de sesión (ajusta según tus necesidades)
    session_set_cookie_params([
        'lifetime' => 0,            // Hasta cerrar el navegador
        'path'     => '/',
        'domain'   => $_SERVER['HTTP_HOST'],
        'secure'   => isset($_SERVER['HTTPS']), // Solo HTTPS si aplica
        'httponly' => true,
        'samesite' => 'Lax'         // O 'Strict' si tu app lo permite
    ]);

    // 2. Iniciar sesión si no está iniciada
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // 3. Regenerar ID de sesión cada X segundos (ej: 5 minutos)
    $regenerateInterval = 300; // segundos
    if (!isset($_SESSION['created'])) {
        $_SESSION['created'] = time();
    } elseif (time() - $_SESSION['created'] > $regenerateInterval) {
        session_regenerate_id(true);
        $_SESSION['created'] = time();
    }

    // 4. Control de inactividad (timeout)
    $inactiveLimit = TIMESESSION; // define en segundos (p.ej. 1800 = 30 min)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactiveLimit) {
        // Sesión inactiva: destruir y forzar logout
        session_unset();
        session_destroy();
        header('Location: ' . base_url() . '/Logout');
        exit;
    }
    $_SESSION['last_activity'] = time();

    // 5. Verificar estado de login
    if (empty($_SESSION['loginEstado'])) {
        // No está logueado: forzar login
        header('Location: ' . base_url() . '/login');
        exit;
    }
}


function uploadImage(array $data, string $name)
{
    //Nota Otorgar los Permisos a la Caperta Uploads en el servidor
    $url_temp = $data['tmp_name'];
    //$destino    = __DIR__.'/../Assets/images/uploads/'.$name; 
    $destino    = 'Assets/images/uploads/' . $name;
    $move = move_uploaded_file($url_temp, $destino);
    return $move;
}

function deleteFile(string $name)
{
    unlink('Assets/images/uploads/' . $name);
}

function clear_cadena(string $cadena)
{
    //Reemplazamos la A y a
    $cadena = str_replace(
        array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
        array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
        $cadena
    );

    //Reemplazamos la E y e
    $cadena = str_replace(
        array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
        array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
        $cadena
    );

    //Reemplazamos la I y i
    $cadena = str_replace(
        array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
        array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
        $cadena
    );

    //Reemplazamos la O y o
    $cadena = str_replace(
        array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
        array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
        $cadena
    );

    //Reemplazamos la U y u
    $cadena = str_replace(
        array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
        array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
        $cadena
    );

    //Reemplazamos la N, n, C y c
    $cadena = str_replace(
        array('Ñ', 'ñ', 'Ç', 'ç', ',', '.', ';', ':'),
        array('N', 'n', 'C', 'c', '', '', '', ''),
        $cadena
    );
    return $cadena;
}

function calculoCostos(float $costo, float $margen, float $numDecimal)
{
    //Aplica para los decuentos sin tener perdidas
    $precio = ($costo / ((100 - $margen) / 100));
    return  number_format($precio, $numDecimal, SPD, SPM);
}

function add_ceros($numero, $ceros)
{
    /* Ejemplos para usar.
          $numero="123";
          echo add_ceros($numero,8) */
    $insertar_ceros = "";
    $order_diez = explode(".", $numero);
    $dif_diez = $ceros - strlen($order_diez[0]);
    for ($m = 0; $m < $dif_diez; $m++) {
        $insertar_ceros .= 0;
    }
    return $insertar_ceros .= $numero;
}

function Meses()
{
    $meses = array(
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre"
    );
    return $meses;
}

function getCatFooter()
{
    require_once("Models/CategoriasModel.php");
    //$objCategoria = new CategoriasModel();
    //$request = $objCategoria->getCategoriasFooter();
    return $request;
}

function getInfoPage(int $idpagina)
{
    require_once("Libraries/Core/Mysql.php");
    $con = new Mysql();
    $sql = "SELECT * FROM post WHERE idpost = $idpagina";
    $request = $con->select($sql);
    return $request;
}

function getPageRout(string $ruta)
{
    require_once("Libraries/Core/Mysql.php");
    $con = new Mysql();
    $sql = "SELECT * FROM post WHERE ruta = '$ruta' AND status != 0 ";
    $request = $con->select($sql);
    if (!empty($request)) {
        $request['portada'] = $request['portada'] != "" ? media() . "/images/uploads/" . $request['portada'] : "";
    }
    return $request;
}

function viewPage(int $idpagina)
{
    require_once("Libraries/Core/Mysql.php");
    $con = new Mysql();
    $sql = "SELECT * FROM post WHERE idpost = $idpagina ";
    $request = $con->select($sql);
    if (($request['status'] == 2 and isset($_SESSION['permisosMod']) and $_SESSION['permisosMod']['u'] == true) or $request['status'] == 1) {
        return true;
    } else {
        return false;
    }
}

function getPermisos()
{
    $uriData = explode("/", trim($_SERVER["REQUEST_URI"], "/")); // Elimina '/' inicial y final
    $controller = strtolower($uriData[2] ?? ''); // Obtiene el controlador de la URL
    if (empty($controller)) {
        $_SESSION['permisosMod'] = 0;
        $_SESSION['permisos'] = 0;
        return;
    }

    $menuApp = $_SESSION['menuData'] ?? []; // Evita error si no está definido
    $claveEncontrada = buscarEnArray($menuApp, $controller);
 
    $_SESSION['permisosMod'] = $claveEncontrada ?: 0;
    $_SESSION['permisos'] = 0; // Puedes definirlo según lógica adicional
}

function buscarEnArray(array $menu, string $cadena)
{
    foreach ($menu as $item) {
        if (isset($item['enlace']) && strtolower($item['enlace']) === $cadena) {
            return $item;
        }

        // Si hay hijos, buscar recursivamente
        if (!empty($item['hijos'])) {
            $resultado = buscarEnArray($item['hijos'], $cadena);
            if ($resultado) {
                return $resultado;
            }
        }
    }
    return false;
}


function getGenerarMenu()
{
    $menuApp = $_SESSION['menuData'];
    $menu = '<ul class="app-menu">';

    foreach ($menuApp as $item) {
        $menu .= generarMenuItem($item, 0);
    }

    // Agregar opción de salida
    $menu .= '<li><a class="app-menu__item" href="' . base_url() . '/salida">
                <i class="app-menu__icon fa fa-sign-out"></i>
                <span class="app-menu__label">Salir</span></a></li>';

    $menu .= '</ul>';
    echo $menu;
}

/**
 * Genera un elemento del menú de forma recursiva con niveles
 * @param array $item Elemento del menú
 * @param int $nivel Nivel de profundidad en el menú
 */
function generarMenuItem($item, $nivel)
{
    $espaciado = str_repeat('&nbsp;&nbsp;&nbsp;', $nivel);
    $menuItem = '';

    if (!empty($item['hijos'])) { // Si tiene submenús
        $menuItem .= '<li class="treeview">';
        $menuItem .= '<a class="app-menu__item toggle-menu" href="#" data-toggle="treeview">
                        <i class="app-menu__icon ' . (!empty($item['icono']) ? $item['icono'] : 'fa fa-folder') . '"></i>
                        <span class="app-menu__label">' . $espaciado . $item['titulo'] . '</span>
                        <i class="treeview-indicator fa fa-angle-right"></i>
                      </a>';
        $menuItem .= '<ul class="treeview-menu">';

        foreach ($item['hijos'] as $subItem) {
            $menuItem .= generarMenuItem($subItem, $nivel + 1);
        }

        $menuItem .= '</ul></li>';
    } else { // Si es un enlace normal
        $menuItem .= '<li><a class="app-menu__item menu-link" href="' . base_url() . '/' . $item['enlace'] . '">
                        <i class="app-menu__icon ' . (!empty($item['icono']) ? $item['icono'] : 'fa fa-circle-o') . '"></i>
                        <span class="app-menu__label">' . $espaciado . $item['titulo'] . '</span>
                      </a></li>';
    }

    return $menuItem;
}

function retornaUser()
{
    $dataSession = $_SESSION['usuarioData'];
    return strstr($dataSession['usu_correo'], '@', true);
}

function recibirData($datos)
{
    // Decodificar Base64
    $encryptedData = base64_decode($datos);
    // Descifrar los datos
    $decryptedData = openssl_decrypt($encryptedData,'AES-128-CBC',key,0,iv);
   // Convertir JSON a un arreglo PHP
   return json_decode($decryptedData, true);
}

function validarMetodoPost(){
    // Verifica si la solicitud es POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método no permitido", 405);
    }

    // Obtener los datos del cuerpo de la solicitud JSON
    $json = file_get_contents("php://input");
    $inputData = json_decode($json, true); // Convertir JSON a array asociativo

    // Validar que los datos existen
    if (!is_array($inputData)) {
        throw new Exception("Datos inválidos o no enviados", 400);
    }
    return $inputData;
}

function checkPermission($type, $redirect)
{
    if (empty($_SESSION['permisosMod'][$type])) {
        //putMessageLogFile("Permiso No autorizado");
        header("Location: " . base_url() . "/$redirect");
        exit();
    }
}

function responseJson($status, $msg, $extra = [])
{
    echo json_encode(array_merge(["status" => $status, "msg" => $msg], $extra), JSON_UNESCAPED_UNICODE);
    exit();
}
function getPageData($title, $back)
{
    $data['page_tag']=$title;
    $data['page_name']=$title;
    $data['page_title']="$title <small> " . htmlspecialchars($_SESSION['empresaData']['NombreComercial'], ENT_QUOTES, 'UTF-8') . "</small>";
    $data['page_back']=$back;
    return $data;
}

function retornarDataSesion(string $param = "rolNombre")
{
    $errorMessages = [
        "rolNombre" => isset($_SESSION['usuarioData']['Rol_nombre']) ? strtolower(str_replace(' ', '', $_SESSION['usuarioData']['Rol_nombre'])) : null,//Retorna Nombre de RoL minusucla
        "Emp_Id" => isset($_SESSION['Emp_Id']) ?$_SESSION['Emp_Id']:0, // Created     
        "Utie_id" => isset($_SESSION['Utie_id']) ?$_SESSION['Utie_id']:0, // Created    
        "Cli_Id" => isset($_SESSION['Cli_id']) ?$_SESSION['Cli_id']:0, // Created            
        "" => null // Internal Server Error
    ];
    return $errorMessages[$param] ?? "Error Session desconocido";
}
