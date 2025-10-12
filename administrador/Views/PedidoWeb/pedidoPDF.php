<?php
$empresa = $_SESSION['empresaData'];
$cabCompra = $data['cabData'][0];
$detCompra = $data['detData'];
$cliente = $data['Cliente'];
$tienda = $data['Tienda'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Pedido Web </title>
	<style>
		* {
			box-sizing: border-box;
		}
		
		body {
			margin: 0;
			padding: 10px;
			font-family: Arial, sans-serif;
		}
		
		table {
			width: 100%;
			margin-bottom: 10px;
		}

		table td,
		table th {
			font-size: 11px;
			vertical-align: top;
		}

		h4 {
			margin: 5px 0;
			font-size: 14px;
		}

		p {
			margin: 3px 0;
			font-size: 10px;
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
			border-radius: 5px;
			padding: 8px;
		}

		.wd10 {
			width: 10%;
		}

		.wd15 {
			width: 15%;
		}

		.wd20 {
			width: 20%;
		}

		.wd25 {
			width: 25%;
		}

		.wd30 {
			width: 30%;
		}

		.wd40 {
			width: 40%;
		}

		.wd55 {
			width: 55%;
		}

		.tbl-detalle {
			border-collapse: collapse;
			table-layout: fixed;
			width: 100%;
			border: 1px solid #CCC;
		}

		.tbl-detalle thead th {
			padding: 8px 4px;
			background-color: #009688;
			color: #FFF;
			font-size: 10px;
			font-weight: bold;
			border: 1px solid #007a6b;
		}

		.tbl-detalle tbody td {
			border: 1px solid #DDD;
			padding: 6px 4px;
			word-wrap: break-word;
			overflow-wrap: break-word;
			font-size: 9px;
			line-height: 1.3;
			vertical-align: top;
		}

		.tbl-detalle tbody td.descripcion {
			hyphens: auto;
			word-break: break-word;
			overflow-wrap: anywhere;
			white-space: normal;
			max-width: 0;
			width: 30%;
		}

		.tbl-detalle tfoot td {
			padding: 8px 4px;
			font-size: 10px;
			font-weight: bold;
			border: 1px solid #CCC;
			background-color: #f8f9fa;
		}
		
		.img-logo {
			max-width: 180px;
			height: auto;
		}
	</style>
</head>

<body>
	<table class="tbl-hader">
		<tbody>
			<tr>
				<td class="wd33">
					<img src="<?= media() ?>/logo/<?= $empresa['Logo'] ?>" alt="Logo" class="img-logo">
				</td>
				<td class="text-center wd33">
					<h4><strong><?= $empresa['NombreComercial'] ?></strong></h4>
					<p><?= $empresa['Direccion'] ?><br>
						RUC: <?= $empresa['Ruc'] ?></p>
				</td>
				<td class="text-right wd33">
					<h4>DETALLE PEDIDO</h4>
					<p>No. Solicitud <strong><?= $cabCompra['numero'] ?></strong><br>
					Fecha: <?= $cabCompra['fechapedido'] ?></p>
				</td>
			</tr>
		</tbody>
	</table>
	<br>

	<table class="tbl-cliente">
		<tbody>
		<tr>
			<td class="wd10">RUC:</td>
			<td class="wd40"><?= htmlspecialchars($cliente['Cedula']) ?></td>
			<td class="wd10">Tienda:</td>
			<td class="wd40"><?= htmlspecialchars($tienda['NombreTienda']) ?></td>
		</tr>
		<tr>
			<td>Nombre:</td>
			<td><?= htmlspecialchars($cliente['Nombre']) ?></td>
			<td>Dirección:</td>
			<td><?= htmlspecialchars($cliente['Direccion']) ?></td>
		</tr>
		</tbody>
	</table>
	<br>

	<table class="tbl-detalle">
		<thead>
			<tr>
				<th class="wd20">Código</th>
				<th class="wd30">Descripción</th>
				<th class="wd15 text-right">Precio</th>
				<th class="wd15 text-center">Cant.</th>
				<th class="wd20 text-right">Total</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$subtotal = 0;
			foreach ($detCompra as $producto):
				$importe = (float)$producto['cantidad'] * (float)$producto['precio'];
				$subtotal += $importe;
				
				// Mostrar la descripción completa sin cortar
				$descripcion = htmlspecialchars($producto['nombre']);
			?>
			<tr>
				<td><?= htmlspecialchars($producto['codigo']) ?></td>
				<td class="descripcion"><?= $descripcion ?></td>
				<td class="text-right"><?= SMONEY . ' ' . formatMoney($producto['precio'], 2) ?></td>
				<td class="text-center"><?= number_format((float)$producto['cantidad'], 0) ?></td>
				<td class="text-right"><?= SMONEY . ' ' . formatMoney($importe, 2) ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4" class="text-right"><strong>TOTAL:</strong></td>
				<td class="text-right"><strong><?= SMONEY . ' ' . formatMoney($subtotal, 2) ?></strong></td>
			</tr>
		</tfoot>
	</table>
	<div class="text-center">
		<p>Si tienes preguntas sobre tu pedido, <br> pongase en contacto con nombre, teléfono y Email a
			<?= $data['correo_admin'] ?></p>
		<h4>¡Gracias por tu Pedido!</h4>
	</div>
</body>

</html>