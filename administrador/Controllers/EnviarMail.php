<?php
//require_once 'vendor/autoload.php';
require_once 'MailSystem.php'; // Tu clase para envío
//require_once 'conexion.php';   // Donde tengas tu $pdo

// Recibir datos desde exec
$data = json_decode($argv[1], true);
$correo = new MailSystem();
$resultado = $correo->enviarNotificacion(
    $data['destinatario'],
    $data['asunto'],
    $data['pedido'],
    $data['bcc'] ?? null,
    $data['cli_id']
);


//echo json_encode($resultado);

