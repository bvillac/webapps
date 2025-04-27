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
	</style>
</head>

<body>
	<table class="tbl-hader">
		<tbody>
			<tr>
				<td class="wd33">
					<img src="<?= media() ?>/logo/<?= $empresa['Logo'] ?>" alt="Logo" width="200">
				</td>
				<td class="text-center wd33">
					<h4><strong><?= $empresa['NombreComercial'] ?></strong></h4>
					<p><?= $empresa['Direccion'] ?> <br>
						RUC: <?= $empresa['Ruc'] ?></p>
				</td>
				<td class="text-right wd33">
					<p>
					<h4>DETALLE PEDIDO</h4><br>
					No. Pedido <strong><?= $cabCompra['numero'] ?></strong><br>
					Fecha: <?= $cabCompra['fechapedido'] ?> <br>
					</p>
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
				<th class="wd15">Código</th>
				<th class="wd40">Descripción</th>
				<th class="wd15 text-right">Precio</th>
				<th class="wd15 text-center">Cantidad</th>
				<th class="wd15 text-right">Total</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$subtotal = 0;
			foreach ($detCompra as $producto):
				$importe = (float)$producto['cantidad'] * (float)$producto['precio'];
				$subtotal += $importe;
			?>
			<tr>
				<td><?= htmlspecialchars($producto['codigo']) ?></td>
				<td><?= htmlspecialchars($producto['nombre']) ?></td>
				<td class="text-right"><?= SMONEY . ' ' . formatMoney($producto['precio'], 2) ?></td>
				<td class="text-center"><?= (float)$producto['cantidad'] ?></td>
				<td class="text-right"><?= SMONEY . ' ' . formatMoney($importe, 2) ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>

		<tr>
				<td colspan="4" class="text-right"><strong>Total:</strong></td>
				<td class="text-right"><strong><?= SMONEY . ' ' . formatMoney($subtotal, 2) ?></strong></td>
			</tr>

		</tfoot>
	</table>
	<div class="text-center">
		<p>Si tienes preguntas sobre tu pedido, <br> pongase en contacto con nombre, teléfono y Email a
			<?= $empresa['Correo'] ?></p>
		<h4>¡Gracias por tu Pedido!</h4>
	</div>
</body>

</html>