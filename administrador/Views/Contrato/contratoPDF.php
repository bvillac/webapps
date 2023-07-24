<?php 
	//$empresa = $data['empData'];
	$cabContrato = $data['cabData'];
	$detBeneficiario = $data['detData'];
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
		.wd5{
			width: 5%;
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
					<img src="<?= media() ?>/logo/<?= $_SESSION['empresaData']['Logo'] ?>" alt="Logo">
				</td>
				<td class="text-center wd33">				
					
					<p>
					<h4><strong>CONTRATO DEL PROGRAMA DE IDIOMAS MILLER TRAINING</strong></h4>						
					Email: <?= $_SESSION['empresaData']['Correo'] ?> </p>
				</td>
				<td class="text-right wd33">
					<p><strong>R.U.C.</strong><?= $_SESSION['empresaData']['Ruc'] ?> <br>
						Número° <strong><?= $cabContrato['Numero'] ?></strong><br>
						Fecha: <?= $cabContrato['FechaIni'] ?>  <br>						
					</p>
				</td> 
			</tr>
		</tbody>
	</table>
	<br>

	<table class="tbl-detalle">
		<thead>
			<tr>
			    <th class="wd5">Dni</th>
				<th class="wd15">Nombres</th>
				<th class="wd5">TipBen</th>
				<th class="wd5 text-right">Centro</th>
				<th class="wd5 text-right">Paquete</th>
				<th class="wd5 text-right">Meses</th>
				<th class="wd5 text-right">Horas</th>
				<th class="wd5 text-right">Modalidad</th>
				<th class="wd5 text-right">Idioma</th>
				<th class="wd5 text-center">Edad</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				foreach ($detBeneficiario as $beneficiario) {
     		 ?>
			<tr>
			    <td><?= $beneficiario['Dni'] ?></td>
				<td><?= $beneficiario['Nombres'] ?></td>
				<td><?= ($beneficiario['ben_tipo']=="1")?"T":"B" ?></td>
				<td><?= $beneficiario['CentroAtencion'] ?></td>
				<td><?= $beneficiario['Paquete'] ?></td>
				<td><?= $beneficiario['NMeses'] ?></td>
				<td><?= $beneficiario['NHoras'] ?></td>
				<td><?= $beneficiario['Modalidad'] ?></td>
				<td><?= $beneficiario['Idioma'] ?></td>
				<td><?= $beneficiario['Edad'] ?></td>
			</tr>
			<?php } ?>
		</tbody>
		
	</table>

	<div class="text-center">
		<p>Si tienes preguntas sobre tu pedido, <br> pongase en contacto con nombre, teléfono y Email</p>
		<h4>¡Gracias por preferirnos!</h4>
	</div>
</body>
</html>