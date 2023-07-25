<?php
setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
//$empresa = $data['empData'];
$cabContrato = $data['cabData'];
$detBeneficiario = $data['detData'];
$fechaContrato = strftime("%d de %B de %Y", strtotime($cabContrato['FechaIni']));
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
					<h4><strong>CONTRATO DEL PROGRAMA DE IDIOMAS DE MILLER TRAINING</strong></h4>
					</p>
				</td>
				<td class="text-right wd33">
					<p>
						<h4><strong>R.U.C. </strong><?= $_SESSION['empresaData']['Ruc'] ?></h4>
						<h4>CONTRATO N° <strong><?= $cabContrato['Numero'] ?></strong></h4><br>
					</p>
				</td>
			</tr>
		</tbody>
	</table>
	<br>

	<div class="text-left">
		<h4>Guayaquil, <?= $fechaContrato ?> </h4>
	</div>

	<div>
		<p>
			<strong>MILLER TRAINING</strong> otorga al titular o usuario asesoria de óptima calildad en el sistema "MILLER IDIOMAS" sin costo
			adicional, a partir de la fecha del presente contrato.
		</p>
	</div>
	<br>
	<table>
		<tbody>
			<tr>
				<td class="wd10">Nombre Titular:</td>
				<td class="wd40"><?= $cabContrato['NombresCliente'] ?></td>
				<td class="wd10">Ocupación:</td>
				<td class="wd40"><?= $cabContrato['Ocupacion'] ?></td>
			</tr>
			<tr>
				<td>Empresa:</td>
				<td><?= $cabContrato['RazonSocial'] ?></td>
				<td>Cargo:</td>
				<td><?= $cabContrato['Cargo'] ?></td>
			</tr>
			<tr>
				<td>Ingreso Mensual:</td>
				<td><?= $cabContrato['IngMensual'] ?></td>
				<td>Antiguedad:</td>
				<td><?= $cabContrato['Antiguedad'] ?></td>
			</tr>
			<tr>
				<td>Dirección Domicilio:</td>
				<td><?= $cabContrato['DirDomicilio'] ?></td>
				<td>Teléfono Domicilio:</td>
				<td><?= $cabContrato['TelDomicilio'] ?></td>
			</tr>
			<tr>
				<td>Dirección Oficina:</td>
				<td><?= $cabContrato['DirTrabajo'] ?></td>
				<td>Teléfono Oficina:</td>
				<td><?= $cabContrato['TelOficina'] ?></td>
			</tr>
			<tr>
				<td>Referencía Bancaria:</td>
				<td><?= $cabContrato['RefBanco'] ?></td>
				<td>Teléfono Celular:</td>
				<td><?= $cabContrato['TelCelular'] ?></td>
			</tr>
			<tr>
				<td>N° Recibo Inscripción:</td>
				<td><?= $cabContrato['NumRecibo'] ?></td>
				<td>N° Deposito:</td>
				<td><?= $cabContrato['NumDeposito'] ?></td>
			</tr>
		</tbody>
	</table>

    <p>Beneficiario (os):(Nombres y apellidos)</p>
	<div class="table-responsive-sm">
		<table class="table tbl-detalle">
			<thead>
				<tr>
					<th>C.I.</th>					
					<th>Nombres</th>
					<th>Tipo</th>
					<th>Edad</th>
					<th>Teléfono</th>
					<th>Teléfono</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($detBeneficiario as $beneficiario) {
				?>
					<tr>
					    <td class="text-left"><?= $beneficiario['Dni'] ?></td>
						<td class="text-left"><?= strtoupper($beneficiario['Nombres']) ?></td>
						<td class="text-center"><?= ($beneficiario['ben_tipo'] == "1") ? "T" : "B" ?></td>
						<td class="text-center"><?= $beneficiario['Edad'] ?></td>
						<td class="text-left"><?= $beneficiario['TelCelular'] ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>

	</div>

	<div>
		<p>
			<strong>MILLER</strong> efectúa la entrega del siguiente material para el aprendisaje del Idioma a.
		</p>
	</div>
	<br>
	<div class="table-responsive-sm">
		<table class="table tbl-detalle">
			<thead>
				<tr>				
					<th>Nombres</th>
					<th>Centro</th>
					<th>Paquete</th>
					<th>Meses</th>
					<th>Horas</th>
					<th>Modalidad</th>
					<th>Idioma</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($detBeneficiario as $beneficiario) {
				?>
					<tr>
						<td class="text-left"><?= strtoupper($beneficiario['Nombres']) ?></td>
						<td class="text-left"><?= $beneficiario['CentroAtencion'] ?></td>
						<td class="text-left"><?= $beneficiario['Paquete'] ?></td>
						<td class="text-center"><?= $beneficiario['NMeses'] ?></td>
						<td class="text-center"><?= $beneficiario['NHoras'] ?></td>
						<td class="text-left"><?= $beneficiario['Modalidad'] ?></td>
						<td class="text-left"><?= $beneficiario['Idioma'] ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>

	</div>

	

	<!--<div class="text-center">
		<p>Si tienes preguntas sobre tu pedido, <br> pongase en contacto con nombre, teléfono y Email</p>
		<h4>¡Gracias por preferirnos!</h4>
	</div>-->
</body>

</html>