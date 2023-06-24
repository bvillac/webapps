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
					<p><h4>REPORTE</h4><br>
						Usuario <strong><?= $cabReporte['usu_alias'] ?></strong><br>
						Fecha: <?= $cabReporte['fecha'] ?>  <br>						
					</p>
				</td> 
			</tr>
		</tbody>
	</table>
	<br>
	
</body>
</html>