<?php 

	//Retorla la url del proyecto

use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

	function base_url()
	{
		return BASE_URL_ADMIN;
	}
    function media()
    {
        return BASE_URL_ADMIN."/Assets";
    }

    function vendor()
    {
        return BASE_URL_ADMIN."/vendor";
    }

    function cdnTableLink()
    {
        return cdnTable;
    }

    //Archivo de Idiomas
	function filelang($lang="es",$file="general"){
        $langRuta="messages/{$lang}/{$file}.php";
        //echo $langRuta;
        require_once ($langRuta);
    }
    
	//Muestra información formateada
	function dep($data){
        $format  = print_r('<pre>');
        $format .= print_r($data);
        $format .= print_r('</pre>');
        return $format;
    }
    
    //Muestra información formateada texto
    function putMessageLogFile($message) {
        $rutaLog= __DIR__ . '/../Log/Errorlog.log';//$this->logfile;//PHP 7.0
        //echo $rutaLog;
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


    //Elimina exceso de espacios entre palabras
    //Para Injecciones Sql
    function strClean($strCadena){
        $string = preg_replace(['/\s+/','/^\s|\s$/'],[' ',''], $strCadena);//elimina exceso de espacio entre palabras
        $string = trim($string); //Elimina espacios en blanco al inicio y al final
        $string = stripslashes($string); // Elimina las \ invertidas
        $string = str_ireplace("<script>","",$string);
        $string = str_ireplace("</script>","",$string);
        $string = str_ireplace("<script src>","",$string);
        $string = str_ireplace("<script type=>","",$string);
        $string = str_ireplace("SELECT * FROM","",$string);
        $string = str_ireplace("DELETE FROM","",$string);
        $string = str_ireplace("INSERT INTO","",$string);
        $string = str_ireplace("SELECT COUNT(*) FROM","",$string);
        $string = str_ireplace("DROP TABLE","",$string);
        $string = str_ireplace("OR '1'='1","",$string);
        $string = str_ireplace('OR "1"="1"',"",$string);
        $string = str_ireplace('OR ´1´=´1´',"",$string);
        $string = str_ireplace("is NULL; --","",$string);
        $string = str_ireplace("is NULL; --","",$string);
        $string = str_ireplace("LIKE '","",$string);
        $string = str_ireplace('LIKE "',"",$string);
        $string = str_ireplace("LIKE ´","",$string);
        $string = str_ireplace("OR 'a'='a","",$string);
        $string = str_ireplace('OR "a"="a',"",$string);
        $string = str_ireplace("OR ´a´=´a","",$string);
        $string = str_ireplace("OR ´a´=´a","",$string);
        $string = str_ireplace("--","",$string);
        $string = str_ireplace("^","",$string);
        $string = str_ireplace("[","",$string);
        $string = str_ireplace("]","",$string);
        $string = str_ireplace("==","",$string);
        return $string;
    }
    //Genera una contraseña de 10 caracteres 
	function passGenerator($length = 10){
        $pass = "";
        $longitudPass=$length;
        $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $longitudCadena=strlen($cadena);
        for($i=1; $i<=$longitudPass; $i++){
            $pos = rand(0,$longitudCadena-1);
            $pass .= substr($cadena,$pos,1);
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
        $token = $r1.'-'.$r2.'-'.$r3.'-'.$r4;
        return $token;
    }
    //Formato para valores monetarios puntos y comas como decimales
    function formatMoney($cantidad,$numDecimal){
        $cantidad = number_format($cantidad,$numDecimal,SPD,SPM);
        return $cantidad;
    }

    //Muestra Header Admin
	function adminHeader($data=""){
        $viewRuta="Views/Template/admin_header.php";
        require_once ( $viewRuta);
    }
    //Muestra adminMenu
	function adminMenu($data=""){
        $viewRuta="Views/Template/admin_menu.php";
        require_once ( $viewRuta);
    }
    //Muestra adminFooter
	function adminFooter($data=""){
        $viewRuta="Views/Template/admin_footer.php";
        require_once ( $viewRuta);
    }

    //Muestra Header Tienda
	function tiendaHeader($data=""){
        $viewRuta="Views/Template/tienda_header.php";
        require_once ( $viewRuta);
    }
    //Muestra adminMenu
	function tiendaMenu($data=""){
        $viewRuta="Views/Template/tienda_menu.php";
        require_once ( $viewRuta);
    }
    //Muestra adminFooter
	function tiendaFooter($data=""){
        $viewRuta="Views/Template/tienda_footer.php";
        require_once ( $viewRuta);
    }

    //Muestra tiendaSlider
	function tiendaSlider($data=""){
        $viewRuta="Views/Template/tienda_Slider.php";
        require_once ( $viewRuta);
    }

    //Muestra tiendaBanner
	function tiendaBanner($data=""){
        $viewRuta="Views/Template/tienda_Banner.php";
        require_once ( $viewRuta);
    }

     //Muestra tiendaProducto
	function tiendaProducto($data=""){
        $viewRuta="Views/Template/tienda_Producto.php";
        require_once ( $viewRuta);
    }

    
    function getModal(string $nameModal, $data){
        $view_modal = "Views/Template/Modals/{$nameModal}.php";
        require_once $view_modal;        
    }

    function getFile(string $url, $data){
        ob_start();
        require_once("Views/{$url}.php");
        $file = ob_get_clean();
        return $file;        
    }
    
    //Envio de correos
    function enviarEmail($data,$template) {
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
        require_once("Views/Template/Email/".$template.".php");
        $mensaje = ob_get_clean();
        $send = mail($paraDestino, $asunto, $mensaje, $de);
        return $send;
    }

    function getPermisos(int $modIds){
        //require_once ("Models/PermisosModel.php");
        //$objPermisos = new PermisosModel();
        //$idrol = $_SESSION['usuarioData']['RolID'];//se obtiene el rol de la seccion
        //$usuId = $_SESSION['usuarioData']['UsuId'];
        //$empId = 1;
        //$arrPermisos = $objPermisos->permisosModulo($usuId,$empId,$idrol);
        /*$permisos = '';
        $permisosMod = '';
        if(count($arrPermisos) > 0 ){
            $permisos = $arrPermisos;
            $permisosMod = isset($arrPermisos[$modIds]) ? $arrPermisos[$modIds] : "";
        }*/
        //$_SESSION['permisos'] = $permisos;
        //$_SESSION['permisosMod'] = $permisosMod;
        
			$_SESSION['permisos'] = 0;
        	$_SESSION['permisosMod'] = 0;
    }

    function sessionUsuario(int $idsUsuario){
        require_once ("Models/LoginModel.php");
        $objLogin = new LoginModel();
        $request = $objLogin->sessionLogin($idsUsuario);
        return $request;
    }

    function uploadImage(array $data, string $name){
        //Nota Otorgar los Permisos a la Caperta Uploads en el servidor
        $url_temp = $data['tmp_name'];
        //$destino    = __DIR__.'/../Assets/images/uploads/'.$name; 
        $destino    = 'Assets/images/uploads/'.$name; 
        $move = move_uploaded_file($url_temp, $destino);
        return $move;
    }

    function deleteFile(string $name){
        unlink('Assets/images/uploads/'.$name);
    }

    function clear_cadena(string $cadena){
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
        $cadena );
 
        //Reemplazamos la I y i
        $cadena = str_replace(
        array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
        array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
        $cadena );
 
        //Reemplazamos la O y o
        $cadena = str_replace(
        array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
        array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
        $cadena );
 
        //Reemplazamos la U y u
        $cadena = str_replace(
        array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
        array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
        $cadena );
 
        //Reemplazamos la N, n, C y c
        $cadena = str_replace(
        array('Ñ', 'ñ', 'Ç', 'ç',',','.',';',':'),
        array('N', 'n', 'C', 'c','','','',''),
        $cadena
        );
        return $cadena;
    }

    function calculoCostos(float $costo,float $margen,float $numDecimal){
        //Aplica para los decuentos sin tener perdidas
        $precio = ($costo/((100-$margen)/100));
        return  number_format($precio,$numDecimal,SPD,SPM);
    }

    function add_ceros($numero, $ceros) {
        /* Ejemplos para usar.
          $numero="123";
          echo add_ceros($numero,8) */
        $insertar_ceros="";
        $order_diez = explode(".", $numero);
        $dif_diez = $ceros - strlen($order_diez[0]);
        for ($m = 0; $m < $dif_diez; $m++) {
            $insertar_ceros .= 0;
        }
        return $insertar_ceros .= $numero;
    }

    function Meses(){
        $meses = array("Enero", 
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
                      "Diciembre");
        return $meses;
    }

    function getCatFooter(){
        require_once ("Models/CategoriasModel.php");
        //$objCategoria = new CategoriasModel();
        //$request = $objCategoria->getCategoriasFooter();
        return $request;
    }

    function getInfoPage(int $idpagina){
        require_once("Libraries/Core/Mysql.php");
        $con = new Mysql();
        $sql = "SELECT * FROM post WHERE idpost = $idpagina";
        $request = $con->select($sql);
        return $request;
    }

    function getPageRout(string $ruta){
        require_once("Libraries/Core/Mysql.php");
        $con = new Mysql();
        $sql = "SELECT * FROM post WHERE ruta = '$ruta' AND status != 0 ";
        $request = $con->select($sql);
        if(!empty($request)){
            $request['portada'] = $request['portada'] != "" ? media()."/images/uploads/".$request['portada'] : "";
        }
        return $request;
    }

    function viewPage(int $idpagina){
        require_once("Libraries/Core/Mysql.php");
        $con = new Mysql();
        $sql = "SELECT * FROM post WHERE idpost = $idpagina ";
        $request = $con->select($sql);
        if( ($request['status'] == 2 AND isset($_SESSION['permisosMod']) AND $_SESSION['permisosMod']['u'] == true) OR $request['status'] == 1){
            return true;        
        }else{
            return false;
        }
    }

    function getGenerarMenu(){
        $menuApp=$_SESSION['menuData'];
        $menu='<ul class="app-menu">';
        foreach ($menuApp as $val) {
            $mod_id=$val['mod_id'];
            if(strlen($mod_id)==2){
                if(!existeSubMenu($menuApp,$mod_id)){
                    $menu .='<li>';
                    $menu .='<a class="app-menu__item active" href="'.base_url().'/dashboard">';
                    $menu .='<i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">'.$val['mod_nombre'].'</span>';
                    $menu .='</a>';
                    $menu .='</li>';
                }else{
                    $menu .='<li class="treeview">';
                    $menu .='<a class="app-menu__item" href="#" data-toggle="treeview">';
                    $menu .='<i class="app-menu__icon fa fa-laptop"></i>';
                    $menu .='<span class="app-menu__label">UI Elements</span>';
                    $menu .='<i class="treeview-indicator fa fa-angle-right"></i>';
                    $menu .='</a>';
                    $menu .='<ul class="treeview-menu">';
                    $menu .='<li>';
                    $menu .='<a class="treeview-item" href="bootstrap-components.html">';
                    $menu .='<i class="icon fa fa-circle-o"></i> Bootstrap Elements';
                    $menu .='</a>';
                    $menu .='</li>';
                    $menu .='<li><a class="treeview-item" href="https://fontawesome.com/v4.7.0/icons/" target="_blank" rel="noopener"><i class="icon fa fa-circle-o"></i> Font Icons</a></li>';
                    $menu .='<li><a class="treeview-item" href="ui-cards.html"><i class="icon fa fa-circle-o"></i> Cards</a></li>';
                    $menu .='<li><a class="treeview-item" href="widgets.html"><i class="icon fa fa-circle-o"></i> Widgets</a></li>';
                    $menu .='</ul>';
                    $menu .='</li>';


                }
                
                
            }
            
            
            //putMessageLogFile($val['mod_id']);
        }
        $menu .='</ul>';
        //echo $menu;
    }

    function getSubMenu(array $menuApp,string $menuRef,int $largo){
        foreach ($menuApp as $val) {
            $menu_id=$val['mod_id'];
            if($menuRef==substr($menu_id, 0, $largo)){

            }
            if(strlen($menu_id)>2){//Tiene Submen

            }
        }
    }
    function existeSubMenu(array $menuApp,string $menuRef){
        $largo=strlen($menuRef);//Extraigo el Tamaño
        foreach ($menuApp as $val) {
            $mod_id=$val['mod_id'];            
            if(strlen($mod_id)>$largo){//Tiene Submenu
                if($menuRef==substr($mod_id, 0, $largo)){
                    return true;//Existe SubMenu
                }
            }
        }
        return false;//Retorna Falso si no encuentra SubMenu
    }

    function retornaUser(){
        $dataSession = $_SESSION['usuarioData'];
		return strstr($dataSession['usu_correo'], '@', true);
    }

    
 

    
    

 ?>