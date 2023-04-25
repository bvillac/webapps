<?php 
	$empresa = $data['empData'];
	$cabItem = $data['cabReporte'];
	$detItem= $data['detReporte'];
 ?>
<!DOCTYPE html>
<html lang="es">
<head> 
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Reporte</title>
	<style>
		table{
			width: 100%;
		}
		table td, table th{
			font-size: 9px;
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
			border-radius: 8px;
			padding: 5px;
		}
		.wd10{
			width: 10%;
		}
		.wd15{
			width: 2%;
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
					<p><h4>REPORTE DE PRODUCTOS REGISTRADOS</h4><br>
								
					</p>
				</td> 
			</tr>
		</tbody>
	</table>
	<br>

	<table class="tbl-cliente">
		<tbody>
			<tr>
				<td> Identificación DNI:</td>
				<td><?= $cabItem['Cedula'] ?></td>
				
			</tr>
			<tr>
				<td>Nombre de Usuario:</td>
				<td><?= $cabItem['Nombre'] ?></td>				
			</tr>
			<tr>
				<td>Apellido de Usuario:</td>
				<td><?= $cabItem['Apellido'] ?></td>				
			</tr>
			<tr>
				<td>Alias:</td>
				<td><?= $cabItem['Alias'] ?></td>				
			</tr>
		</tbody>
	</table>
	<br>

	<table class="tbl-detalle">
		<thead>
			<tr>			   
				<th >Código</th>
				<th >Nombre</th>
				<th >Línea</th>
				<th >Stock</th>
				<th >Precio</th>				
			</tr>
		</thead>
		<tbody>
		<?php 
				foreach ($detItem as $Items) {
					
			 ?>
		  <tr>
		      <td class="text-right"><?=  $Items['Codigo'] ?></td>
			  <td class="wd15"><?= $Items['Nombre'] ?></td>
			  <td class="wd15"><?=  $Items['Linea'] ?></td>
			  <td class="wd15"><?=  $Items['Stock'] ?></td>
			  <td class="wd15"><?=  $Items['P_Costo'] ?></td>
		  </tr>
			  <?php } ?>

		</tbody>
		
	</table>
	<div class="text-center">
		<h4>¡Gracias!</h4>
	</div>
</body>
</html>