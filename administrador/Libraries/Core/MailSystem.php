<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'vendor/autoload.php';

class MailSystem
{
    private $mailer;

    public function __construct(string $host = 'smtp.gmail.com', int $port = 587, string $username = '', string $password = ''  )
    {
        $this->mailer = new PHPMailer(true);

        // Configuración del servidor SMTP
        $this->mailer->isSMTP();
        $this->mailer->Host = $host;//'smtp.gmail.com';
        $this->mailer->SMTPAuth = true;
        //$this->mailer->Username = 'no-responder@solucionesvillacreses.com';
        //$this->mailer->Username = 'byronvillacreses@gmail.com';
        //$this->mailer->Password = 'vrjw taas gjmj vvno';
        $this->mailer->Username = $username;//'docelectronicoscomputics@gmail.com';
        $this->mailer->Password = $password;//'wftd aqkb uonh fusa';
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = $port;//587;//587;465
      
        //$this->mailer->SMTPDebug = 3; // O 3 para más detalle
        //$this->mailer->Debugoutput = 'html';
    }


    /**
     * Enviar notificación por correo electrónico.
     *
     * @param array $params Parámetros del correo, incluyendo destinatario, asunto, cuerpo HTML, ruta del PDF, CC y BCC.
     * @return array Resultado del envío con estado y mensaje.
     */

    public function enviarNotificacion(array $params): array {
        try {
            $destinatario = $params['destinatario'] ?? '';
            $nombreEmpresa = $params['nombreEmpresa'] ?? '';
            $no_responder = $params['no_responder'] ?? '';
            $asunto = $params['asunto'] ?? '';
            $htmlMail = $params['html'] ?? '';
            $pdfPath = $params['pdf'] ?? '';
            $cc = $params['cc'] ?? '';
            $bcc = $params['bcc'] ?? '';
            $borrarPDF = $params['borrarPDF'] ?? false;
            // Reinicia el estado del mailer para evitar acumulación de direcciones o adjuntos
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
    
            // Configuración del correo
            $this->mailer->setFrom( $no_responder, $nombreEmpresa);
            $this->mailer->isHTML(true);
            $this->mailer->CharSet = 'UTF-8';
            $this->mailer->addAddress($destinatario);
            $this->mailer->Subject = $asunto;
            $this->mailer->Body = $htmlMail;

             // Copia opcional
            if (!empty($cc)) {
                $this->mailer->addBCC($cc);
            }
            // Copia oculta opcional
            if (!empty($bcc)) {
                $this->mailer->addBCC($bcc);
            }
    
            // Adjuntar PDF si existe
            if (!empty($pdfPath)) {
                if (file_exists($pdfPath)) {
                    $this->mailer->addAttachment($pdfPath);
                } else {
                    return ['status' => false, 'message' => "El archivo PDF no fue encontrado en: $pdfPath"];
                }
            }
            // Enviar correo
            $this->mailer->send();
    
            // Eliminar el archivo si se desea limpiar después del envío
            if ($borrarPDF && file_exists($pdfPath)) {
                unlink($pdfPath);
            }
            return ['status' => true, 'message' => 'Correo enviado correctamente'];
    
        } catch (Exception $e) {
            logFileSystem("Error al enviarNotificacion: " . $e->getMessage(), "ERROR");
            return [
                'status' => false,
                'message' => 'Error al enviar el correo: ' . $this->mailer->ErrorInfo,
                'exception' => $e->getMessage()
            ];
        }
    }
    


}
