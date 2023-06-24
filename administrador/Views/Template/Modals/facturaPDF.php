<?php 
	$empresa = $data['empData'];
	$cabCompra = $data['cabData'];
	$detCompra = $data['detData'];
 ?>
<!DOCTYPE html>
<html lang="es">
<head> 
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Factura</title>
	<style>
		table{
			width: 100%;
		}
		table td, table th{
			font-size: 12px;
		}
		h4{
			margin-bottom: 0px;
		}
		.text-center{
			text-align: center;
		}
		.text-right{
			text-align: right;
		}
		.wd33{
			width: 33.33%;
		}
		.tbl-cliente{
			border: 1px solid #CCC;
			border-radius: 10px;
			padding: 5px;
		}
		.wd10{
			width: 10%;
		}
		.wd15{
			width: 15%;
		}
		.wd40{
			width: 40%;
		}
		.wd55{
			width: 55%;
		}
		.tbl-detalle{
			border-collapse: collapse;
		}
		.tbl-detalle thead th{
			padding: 5px;
			background-color: #009688;
			color: #FFF;
		}
		.tbl-detalle tbody td{
			border-bottom: 1px solid #CCC;
			padding: 5px;
		}
		.tbl-detalle tfoot td{
			padding: 5px;
		}
	</style>
</head>
<body>
	<table class="tbl-hader">
		<tbody>
			<tr>
				<td class="wd33">
					<img src="<?= media() ?>/logo/logo-v3.jpg" alt="Logo">
				</td>
				<td class="text-center wd33">
					<h4><strong><?= $empresa['Razon'] ?></strong></h4>
					<p><?= $empresa['Direccion'] ?> <br>
					Email: <?= $empresa['Correo']  ?></p>
				</td>
				<td class="text-right wd33">
					<p><h4>DETALLE DE PEDIDO</h4><br>					
						No. <strong><?= $cabCompra['ped_numero']?></strong><br>
						Fecha: <?= $cabCompra['ped_fecha'] ?>  <br>						
					</p>
				</td> 
			</tr>
		</tbody>
	</table>
	<br>

	<table class="tbl-cliente">
		<tbody>
			<tr>
				<td class="wd10">DNI:</td>
				<td class="wd40"><?= $cabCompra['Cedula'] ?></td>
				<td class="wd10"></td>
				<td class="wd40"></td>
			</tr>
			<tr>
				<td>Nombre:</td>
				<td><?= $cabCompra['Nombre'] ?></td>
				<td>Dirección:</td>
				<td><?= $cabCompra['Direccion'] ?></td>
			</tr>
		</tbody>
	</table>
	<br>

	<table class="tbl-detalle">
		<thead>
			<tr>
			    <th class="wd15">Código</th>
				<th class="wd40">Descripción</th>
				<th class="wd15 text-right">Cantidad</th>
				<th class="wd15 text-right">Precio</th>				
				<th class="wd15 text-right">Total</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				$subtotal = 0;
				foreach ($detCompra as $producto) {
					$importe = $producto['pdet_cantidad'] * $producto['pdet_precio'];
					$subtotal = $subtotal + $importe;
			 ?>
			<tr>
			    <td><?= $producto['pdet_item_id'] ?></td>
				<td><?= $producto['Nombre'] ?></td>
				<td class="text-right"><?= $producto['pdet_cantidad'] ?></td>
				<td class="text-right"><?= SMONEY.' '.formatMoney($producto['pdet_precio'],2) ?></td>
				
				<td class="text-right"><?= SMONEY.' '.formatMoney($importe,2) ?></td>
			</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4" class="text-right">Subtotal:</td>
				<td class="text-right"><?= SMONEY.' '.formatMoney($cabCompra['ped_valor_bruto'],2) ?></td>
			</tr>
			<tr>
				<td colspan="4" class="text-right">Base 0%:</td>
				<td class="text-right"><?= SMONEY.' '.formatMoney($cabCompra['ped_biva0'],2); ?></td>
			</tr>
			<tr>
				<td colspan="4" class="text-right">Base Impuesto %:</td>
				<td class="text-right"><?= SMONEY.' '.formatMoney($cabCompra['ped_biva12'],2); ?></td>
			</tr>
			<tr>
				<td colspan="4" class="text-right">Iva 12%:</td>
				<td class="text-right"><?= SMONEY.' '.formatMoney($cabCompra['ped_valor_iva'],2); ?></td>
			</tr>
			<tr>
				<td colspan="4" class="text-right">Total:</td>
				<td class="text-right"><?= SMONEY.' '.formatMoney($cabCompra['ped_valor_neto'],2); ?></td>
			</tr>
			
		</tfoot>
	</table>
	<div class="text-center">
		<p>Si tienes preguntas sobre tu pedido, <br> pongase en contacto con nombre, teléfono y Email</p>
		<h4>¡Gracias por tu compra!</h4>
	</div>
</body>
</html>