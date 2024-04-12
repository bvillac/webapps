<?php
setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
//$empresa = $data['empData'];
//$cabContrato = $data['cabData'];
$detBeneficiario = $data['control'];
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
					<h4><strong>CONTROL ACADÉMICO DE MILLER TRAINING</strong></h4>
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
	<table class="table-responsive">
		<tbody>
			<tr>
				<td class="tituloLabel">Fecha Contrato:</td>
				<td><?= $data['FechaIngreso'] ?></td>
				<td class="wd2"></td>
				<td class="tituloLabel"></td>
				<td></td>
			</tr>
			<tr>
				<td class="tituloLabel">Nombre:</td>
				<td><?= $data['Nombres'] ?></td>
				<td class="wd2"></td>
				<td class="tituloLabel">DNI:</td>
				<td><?= $data['DNI'] ?></td>
			</tr>
			<tr>
				<td class="tituloLabel">Tipo:</td>
				<td><?= $data['Tipo'] ?></td>
				<td class="wd2"></td>
				<td class="tituloLabel">Género:</td>
				<td><?= $data['Genero'] ?></td>
			</tr>
			<tr>
				<td class="tituloLabel">Teléfono:</td>
				<td><?= $data['Telefono'] ?></td>
				<td class="wd2"></td>
				<td class="tituloLabel">Dirección:</td>
				<td><?= $data['Direccion'] ?></td>
			</tr>
			
		</tbody>
	</table>

	
	<BR>
	<div class="table-responsive-sm">
		<table class="table tbl-detalle">
			<thead>
				<tr>
					<th>Nivel</th>
					<th>Unidad</th>
					<th>Actividad</th>
					<th>Hora</th>
					<th>Tutor</th>
					<th>F.Asistencía</th>
					<th>F.Evaluación</th>
					<th>Valoración</th>
					<th>Valor</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($detBeneficiario as $beneficiario) {
					$valorPor=($beneficiario['Valor']!="")? $beneficiario['Valor'] ." %":"";
				?>
					<tr>
						<td class="text-left"><?= $beneficiario['Nivel'] ?></td>
						<td class="text-center"><?= $beneficiario['Unidad'] ?></td>
						<td class="text-left"><?= $beneficiario['Actividad'] ?></td>
						<td class="text-center"><?= $beneficiario['Hora'] ?></td>
						<td class="text-left"><?= $beneficiario['Instructor'] ?></td>
						<td class="text-center"><?= $beneficiario['FechaAsistencia'] ?></td>
						<td class="text-center"><?= $beneficiario['FechaEvaluacion'] ?></td>
						<td class="text-left"><?= $beneficiario['Valoracion'] ?></td>
						<td class="text-left"><?= $valorPor ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>

	</div>
	
	





</body>

</html>