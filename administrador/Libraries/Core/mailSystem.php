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
        $this->mailer->Host = 'smtp.tuservidor.com';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 'tucorreo@dominio.com';
        $this->mailer->Password = 'tu_contraseña';
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = 587;

        $this->mailer->setFrom('tucorreo@dominio.com', 'Nombre Remitente');
        $this->mailer->isHTML(true);
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
}
