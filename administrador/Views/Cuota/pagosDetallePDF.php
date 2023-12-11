<?php
setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
//$empresa = $data['empData'];
$cabContrato = $data['contrato'];
$detPagos = $data['movimiento'];
$fechaActual= strftime("%d de %B de %Y", strtotime(date("Y-m-d H:i:s")));
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

		
	</style>
</head>

<body>
	<table class="tbl-hader">
		<tbody>
			<tr>
				<td class="wd33">
					<img src="<?= media() ?>/logo/<?= $_SESSION['empresaData']['Logo'] ?>" alt="Logo">
				</td>
				<td class="text-center wd33">
					<p>
					<h4><strong>DETALLE DE PAGOS</strong></h4>
					</p>
				</td>
				<td class="text-right wd33">
					<h4>CONTRATO N° <strong><?= $cabContrato['Contrato'] ?></strong></h4><br>
				</td>
			</tr>
		</tbody>
	</table>
	<br>

	<!-- <div class="text-right">
		<h5>Guayaquil, <?php //$fechaActual ?> </h5>
	</div> -->


	<br>
	<table class="table-responsive">
		<tbody>
			<tr>
				<td class="tituloLabel">Fecha Contrato:</td>
				<td><?= $cabContrato['FechaInicio'] ?></td>
				<td class="wd2"></td>
				<td class="tituloLabel"></td>
				<td></td>
			</tr>
			<tr>
				<td class="tituloLabel">Cliente:</td>
				<td><?= $cabContrato['RazonSocial'] ?></td>
				<td class="wd2"></td>
				<td class="tituloLabel">DNI:</td>
				<td><?= $cabContrato['DNI'] ?></td>
			</tr>
			<tr>
				<td class="tituloLabel">Número Pagos:</td>
				<td><?= $cabContrato['Npagos'] ?></td>
				<td class="wd2"></td>
				<td class="tituloLabel">Valor Mensual:</td>
				<td><?= $cabContrato['ValorMensual'] ?></td>
			</tr>
			
			
		</tbody>
	</table>

	
	<BR>
	<div class="table-responsive table-responsive-sm">
		<table class="table tbl-detalle">
			<thead>
				<tr>
					<th>Número</th>
					<th>F.Vence</th>
					<th>F.Abono</th>
					<th>Abono</th>
					<th>Saldo</th>
					<th>Estado</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($detPagos as $pago) {
					//$valorPor=($pago['Valor']!="")? $pago['Valor'] ." %":"";
				?>
					<tr>
						<td class="text-left"><?= $pago['NUMERO'] ?></td>
						<td class="text-center"><?= $pago['FECHA_VENCE'] ?></td>
						<td class="text-left"><?= $pago['FECHA_PAGO'] ?></td>
						<td class="text-center"><?= $pago['CREDITO'] ?></td>
						<td class="text-left"><?= $pago['SALDO'] ?></td>
						<td class="text-center"><?= $pago['CANCELADO'] ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>

	</div>
	
	





</body>

</html>