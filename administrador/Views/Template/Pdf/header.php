<?php
setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
$fechaActual= strftime("%d de %B de %Y", strtotime(date("Y-m-d H:i:s")));
$FechaFormato= date("Y-m-d");
$HoraFormato= date("H:i:s");
//putMessageLogFile($_SESSION['empresaData']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato</title>
    <style>
        table {
            width: 100%;
        }

        table td,
        table th {
            font-size: 12px;
        }

        h4 {
            margin-bottom: 0px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .wd33 {
            width: 33.33%;
        }

        .tbl-cliente {
            border: 1px solid #CCC;
            border-radius: 10px;
            padding: 5px;
        }

        .tituloLabel {
            font-weight: bold;
        }

        .wd2 {
            width: 2%;
        }

        .wd5 {
            width: 5%;
        }

        .wd10 {
            width: 10%;
        }

        .wd15 {
            width: 15%;
        }

        .wd40 {
            width: 40%;
        }

        .wd55 {
            width: 55%;
        }

        .tbl-detalle {
            border-collapse: collapse;
        }

        .tbl-detalle thead th {
            padding: 5px;
            background-color: #009688;
            color: #FFF;
        }

        .tbl-detalle tbody td {
            border-bottom: 1px solid #CCC;
            padding: 5px;
        }

        .tbl-detalle tfoot td {
            padding: 5px;
        }

        /* Configuracion de Texto */
        /* Quitar la viñeta y establecer estilos para la lista */
        ul {
            list-style-type: none;
            padding: 0;
        }

        /* Estilos para cada elemento de la lista */
        li {
            display: flex;
            justify-content: space-between;
            align-items: center;            
            padding: 2px;
        }

        /* Estilos para el texto a la izquierda */
        .left-text {
            text-align: left;
        }

        /* Estilos para el texto a la derecha */
        .right-text {
            text-align: right;
        }
    </style>
</head>

<body>
    <page_header>
        <table class="tbl-hader">
            <tbody>
                <tr>
                    <td class="wd33">
                        <img src="<?= media() ?>/logo/<?= $_SESSION['empresaData']['Logo'] ?>" alt="Logo">
                    </td>
                    <td class="text-center wd33">
                        <p>
                        <h4><strong><?= $data['Titulo'] ?></strong></h4>
                        </p>
                    </td>
                    <td class="text-right wd33">
                        <ul>
                            <li>
                                <span class="left-text"><b>Usuario:</b></span>
                                <span class="right-text"><?= retornaUser() ?></span>
                            </li>
                            <li>
                                <span class="left-text"><b>Fecha:</b></span>
                                <span class="right-text"><?= $FechaFormato ?></span>
                            </li>
                            <li>
                                <span class="left-text"><b>Hora:</b></span>
                                <span class="right-text"><?= $HoraFormato ?></span>
                            </li>
                        </ul>

                    </td>
                </tr>
            </tbody>
        </table>
    </page_header>

    <br>
    <br>
    <br>
    <br>