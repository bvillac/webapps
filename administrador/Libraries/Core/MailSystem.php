<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'vendor/autoload.php';

class MailSystem
{
    private $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);

        // Configuración del servidor SMTP
        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp.gmail.com';//'smtp.tuservidor.com';
        $this->mailer->SMTPAuth = true;
        //$this->mailer->Username = 'no-responder@solucionesvillacreses.com';//'tucorreo@dominio.com';
        $this->mailer->Username = 'byronvillacreses@gmail.com';//'tucorreo@dominio.com';
        $this->mailer->Password = 'vrjw taas gjmj vvno';
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = 587;//587;465

        //$this->mailer->setFrom('byron_villacresesf@hotmail.com', 'Byron Prueba');
        $this->mailer->setFrom('no-responder@solucionesvillacreses.com', 'Byron Prueba');
        $this->mailer->isHTML(true);

        $this->mailer->SMTPDebug = 3; // O 3 para más detalle
        $this->mailer->Debugoutput = 'html';
    }

    public function enviarPedido(string $destinatario, string $asunto, array $pedido, string $pdfPath = '', string $bcc = ''): array
    {
        try {
            $this->mailer->addAddress($destinatario);
            $this->mailer->Subject = $asunto;

            // Agregar copia oculta si se indicó
            if (!empty($bcc)) {
                $this->mailer->addBCC($bcc);
            }

            // Construir cuerpo del mensaje HTML
            $body = "<h3>Resumen del pedido</h3><table border='1' cellpadding='5'>";
            $body .= "<tr><th>Código</th><th>Nombre</th><th>Cantidad</th><th>Precio</th><th>Total</th></tr>";

            $totalGeneral = 0;
            foreach ($pedido as $item) {
                $body .= "<tr>
                            <td>{$item['codigo']}</td>
                            <td>{$item['nombre']}</td>
                            <td>{$item['cantidad']}</td>
                            <td>\${$item['precio']}</td>
                            <td>\$" . number_format($item['total'], 2) . "</td>
                          </tr>";
                $totalGeneral += $item['total'];
            }

            $body .= "<tr><td colspan='4'><strong>Total General</strong></td><td><strong>\$" . number_format($totalGeneral, 2) . "</strong></td></tr>";
            $body .= "</table>";

            $this->mailer->Body = $body;

            // Adjuntar PDF si se especificó
            if (!empty($pdfPath) && file_exists($pdfPath)) {
                $this->mailer->addAttachment($pdfPath);
            }

            $this->mailer->send();


            return ['status' => true, 'message' => 'Correo enviado correctamente'];
        } catch (Exception $e) {
            return ['status' => false, 'message' => 'Error al enviar el correo: ' . $e->getMessage()];
        }
    }


    public function enviarNotificacionxxxx(string $destinatario, string $asunto, array $pedido, string $pdfPath = '', string $bcc = ''): array
    {
        try {
            $this->mailer->addAddress($destinatario);
            $this->mailer->Subject = $asunto;

            // Agregar copia oculta si se indicó
            if (!empty($bcc)) {
                $this->mailer->addBCC($bcc);
            }

            // Construir cuerpo del mensaje HTML
            $body = "<h3>Resumen del pedido</h3><table border='1' cellpadding='5'>";
            $body .= "<tr><th>Código</th><th>Nombre</th><th>Cantidad</th><th>Precio</th><th>Total</th></tr>";

            $totalGeneral = 0;
            foreach ($pedido as $item) {
                $body .= "<tr>
                            <td>{$item['codigo']}</td>
                            <td>{$item['nombre']}</td>
                            <td>{$item['cantidad']}</td>
                            <td>\${$item['precio']}</td>
                            <td>\$" . number_format($item['total'], 2) . "</td>
                          </tr>";
                $totalGeneral += $item['total'];
            }

            $body .= "<tr><td colspan='4'><strong>Total General</strong></td><td><strong>\$" . number_format($totalGeneral, 2) . "</strong></td></tr>";
            $body .= "</table>";

            $this->mailer->Body = $body;

            // Adjuntar PDF si se especificó
            if (!empty($pdfPath) && file_exists($pdfPath)) {
                $this->mailer->addAttachment($pdfPath);
            }

            $this->mailer->send();

            return ['status' => true, 'message' => 'Correo enviado correctamente'];
        } catch (Exception $e) {
            return ['status' => false, 'message' => 'Error al enviar el correo: ' . $e->getMessage()];
        }
    }

    public function enviarNotificacion(
        string $destinatario,
        string $asunto,
        string $htmlMail,
        string $pdfPath = '',
        string $bcc = '',
        bool $borrarPDF = false
    ): array {
        putMessageLogFile("meto 2");
        try {
            // Reinicia el estado del mailer para evitar acumulación de direcciones o adjuntos
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
    
            // Configuración del correo
            $this->mailer->isHTML(true);
            $this->mailer->CharSet = 'UTF-8';
            $this->mailer->addAddress($destinatario);
            $this->mailer->Subject = $asunto;
            $this->mailer->Body = $htmlMail;
    
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
            putMessageLogFile("lleg");
            // Enviar correo
            $this->mailer->send();
    
            // Eliminar el archivo si se desea limpiar después del envío
            if ($borrarPDF && file_exists($pdfPath)) {
                unlink($pdfPath);
            }
            putMessageLogFile("lleg 2");
            return ['status' => true, 'message' => 'Correo enviado correctamente'];
    
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error al enviar el correo: ' . $this->mailer->ErrorInfo,
                'exception' => $e->getMessage()
            ];
        }
    }
    


}
