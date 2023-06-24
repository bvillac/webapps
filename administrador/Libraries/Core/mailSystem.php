<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';
class mailSystem{
	private $domEmpresa='solucionesvillacreses.com';
    //private $mailSMTP='mail.utimpor.com';
    private $mailSMTP='marquis.websitewelcome.com';
    private $noResponder='no-responder@utimpor.com';
    private $noResponderPass='tFxBrTzxeEGt60yf';
    public $Subject='Ha Recibido un(a)  Nuevo(a)!!! ';
    public $file_to_attachXML='';
    public $file_to_attachPDF='';
    public $fileXML='';
    public $filePDF='';

	public function __construct(){
		
	}

    //Valida si es un Email Correcto Devuelve True
    private function valid_email($val) {
        if (!filter_var($val, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }


     //Envio de correos
     function enviarMail($data,$template) {
        /*$asunto = $data['asunto'];
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
        return $send;*/

        $asunto = $data['asunto'];
        $paraDestino = $data['email'];

        $mail = new PHPMailer();        
        $mail->IsSMTP();
        $mail->SMTPSecure = "ssl";
        $mail->Port = 465;
        // la dirección del servidor, p. ej.: smtp.servidor.com
        $mail->Host = $this->mailSMTP;
        $mail->setFrom($this->noResponder, 'Servicio de envío automático '.$this->domEmpresa);

        // asunto y cuerpo alternativo del mensaje
        $mail->Subject = $this->Subject;
        $mail->AltBody = "Data alternativao";

        ob_start();
        require_once("Views/Template/Email/".$template.".php");
        $mensaje = ob_get_clean();

        // si el cuerpo del mensaje es HTML
        $mail->MsgHTML($mensaje);
        $mail->AddAddress($paraDestino, $asunto);   

        // si el SMTP necesita autenticación
        $mail->SMTPAuth = true;

        // credenciales usuario
        $mail->Username = $this->noResponder;
        $mail->Password = $this->noResponderPass;
        $mail->CharSet = 'UTF-8';
        //$mail->SMTPDebug = 4;//Muestra el Error

        if (!$mail->Send()) {
            //echo "Error enviando: " . $mail->ErrorInfo;
            //return $obj_var->messageSystem('NO_OK', "Error enviando: " . $mail->ErrorInfo, null, null, null);
            return false;
        } else {
            return true;
            //echo "¡¡Enviado!!";
            //return $obj_var->messageSystem('OK', "¡¡Enviado!!", null, null, null);
        }
    }

	
}

?>