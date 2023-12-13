<?php
setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
//$empresa = $data['empData'];
//$cabContrato = $data['cabData'];
$resultset = $data['Result'];
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
					<h4><strong><?= $data['Titulo'] ?></strong></h4>
					</p>
				</td>
				<td class="text-right wd33">
					<h4>CONTRATO N° <strong><?= $data['Contrato'] ?></strong></h4><br>
					
				</td>
			</tr>
		</tbody>
	</table>
	<br>

	<div class="text-right">
		<h4>Guayaquil, <?= $fechaActual ?> </h4>
	</div>


	<br>

	<div class="table-responsive-sm">
		<table class="table tbl-detalle">
			<thead>
				<tr>
					<th>DNI</th>
					<th>Razon Social</th>
					<th>Direeción</th>
					<th>Correo</th>
					<th>Teléfono</th>
					<th>Estado</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($resultset as $row) {
					$Estado=($row['Estado']!=0)? "Activo":"Inactivo";
				?>
					<tr>
						<td class="text-left"><?= $row['Cedula'] ?></td>
						<td class="text-center"><?= $row['Nombre'] ?></td>
						<td class="text-left"><?= $row['Direccion'] ?></td>
						<td class="text-center"><?= $row['Correo'] ?></td>
						<td class="text-left"><?= $row['Telefono'] ?></td>
						<td class="text-left"><?= $Estado ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>

	</div>
	
	





</body>

</html>