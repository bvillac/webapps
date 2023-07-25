<?php
setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
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
					<h4><strong>CONTRATO DEL PROGRAMA DE IDIOMAS DE MILLER TRAINING</strong></h4>
					</p>
				</td>
				<td class="text-right wd33">
					
					<h4><strong>R.U.C. </strong><?= $_SESSION['empresaData']['Ruc'] ?></h4>
					<h4>CONTRATO N° <strong><?= $cabContrato['Numero'] ?></strong></h4><br>
					
				</td>
			</tr>
		</tbody>
	</table>
	<br>

	<div class="text-right">
		<h4>Guayaquil, <?= $fechaContrato ?> </h4>
	</div>

	<div>
		<p>
			<strong>MILLER TRAINING</strong> otorga al titular o usuario asesoria de óptima calildad en el sistema "MILLER IDIOMAS" sin costo
			adicional, a partir de la fecha del presente contrato.
		</p>
	</div>
	<br>
	<table class="table-responsive">
		<tbody>
			<tr>
				<td class="tituloLabel">Nombre Titular:</td>
				<td><?= $cabContrato['NombresCliente'] ?></td>
				<td class="wd2"></td>
				<td class="tituloLabel">Ocupación:</td>
				<td><?= $cabContrato['Ocupacion'] ?></td>
			</tr>
			<tr>
				<td class="tituloLabel">Empresa:</td>
				<td><?= $cabContrato['RazonSocial'] ?></td>
				<td class="wd2"></td>
				<td class="tituloLabel">Cargo:</td>
				<td><?= $cabContrato['Cargo'] ?></td>
			</tr>
			<tr>
				<td class="tituloLabel">Ingreso Mensual:</td>
				<td><?= $cabContrato['IngMensual'] ?></td>
				<td class="wd2"></td>
				<td class="tituloLabel">Antiguedad:</td>
				<td><?= $cabContrato['Antiguedad'] ?></td>
			</tr>
			<tr>
				<td class="tituloLabel">Dirección Domicilio:</td>
				<td><?= $cabContrato['DirDomicilio'] ?></td>
				<td class="wd2"></td>
				<td class="tituloLabel">Teléfono Domicilio:</td>
				<td><?= $cabContrato['TelDomicilio'] ?></td>
			</tr>
			<tr>
				<td class="tituloLabel">Dirección Oficina:</td>
				<td><?= $cabContrato['DirTrabajo'] ?></td>
				<td class="wd2"></td>
				<td class="tituloLabel">Teléfono Oficina:</td>
				<td><?= $cabContrato['TelOficina'] ?></td>
			</tr>
			<tr>
				<td class="tituloLabel">Referencía Bancaria:</td>
				<td><?= $cabContrato['RefBanco'] ?></td>
				<td class="wd2"></td>
				<td class="tituloLabel">Teléfono Celular:</td>
				<td><?= $cabContrato['TelCelular'] ?></td>
			</tr>
			<tr>
				<td class="tituloLabel">N° Recibo Inscripción:</td>
				<td><?= $cabContrato['NumRecibo'] ?></td>
				<td class="wd2"></td>
				<td class="tituloLabel">N° Deposito:</td>
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
					<th>Examen Internacional</th>
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
						<td class="text-center"><?= ($beneficiario['Examen'] == "1") ? "SI" : "NO" ?></td>
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
	<div>
		<p>
			Este contrato corresponde al costo total del material didáctico. Los pagos mensuales deben efectuarse a nuestro representante
			de cobranza o en las oficinas de <strong>MILLER</strong> quien a su vez expedirá un recibo oficial.
		</p>
	</div>
	<br>
	<div>
		<table class="tbl-detalle">
			<tfoot>
				<tr>
					<td class="text-right"><strong>Total:</strong></td>
					<td class="text-right"><?= SMONEY . ' ' . formatMoney($cabContrato['Total'], 2); ?></td>
					<td class="wd2"></td>
					<td class="text-right"><strong>Número Cuotas:</strong></td>
					<td class="text-right"><?= $cabContrato['Npagos'] ?></td>
				</tr>
				<tr>
					<td class="text-right"><strong>Cuota Inicial:</strong></td>
					<td class="text-right"><?= SMONEY . ' ' . formatMoney($cabContrato['CuoInicial'], 2) ?></td>
					<td class="wd2"></td>
					<td class="text-right"><strong>Mensualidades:</strong></td>
					<td class="text-right"><?= SMONEY . ' ' . formatMoney($cabContrato['Vmensual'], 2); ?></td>
				</tr>
				<tr>
					<td class="text-right"><strong>Saldo:</strong></td>
					<td class="text-right"><?= SMONEY . ' ' . formatMoney($cabContrato['Saldo'], 2); ?></td>
					<td class="wd2"></td>
					<td></td>
					<td></td>
				</tr>
			</tfoot>
		</table>
	</div>




<br>


	<table style="width: 100%;">
		<tbody>
			<tr style="height: 40px;">
				<td style="width:10% ;"></td>
				<td style="width:30% ;">
					<div class="text-center">
						
						<br><br><br><br>
						<hr style="border:0px; border-top: 1px dotted black;">
						Titular: <?= $cabContrato['NombresCliente'] ?><br>
							C.I. <?= $cabContrato['CedulaRucCli'] ?>
						
					</div>
				</td>
				<td style="width:20% ;"></td>
				<td style="width:30% ;">
					<div class="text-center">
						
						<br><br><br><br>
						<hr style="border:0px; border-top: 1px dotted black;">
						Firma Ejecutivo:<br>
						C.I.........................          
					</div>
				</td>
				<td style="width:10% ;"></td>
			</tr>
		</tbody>
	</table>




	<!--<div class="text-center">
		<p>Si tienes preguntas sobre tu pedido, <br> pongase en contacto con nombre, teléfono y Email</p>
		<h4>¡Gracias por preferirnos!</h4>
	</div>-->
</body>

</html>