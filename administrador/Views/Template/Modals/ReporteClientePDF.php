<?php 
	$empresa = $data['empData'];
	$cabCliente = $data['cabReporte'];
	$detCliente= $data['detReporte'];
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
			font-size: 7px;
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
					<p><h4>REPORTE DE CLIENTES REGISTRADOS</h4><br>
								
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
				<td><?= $cabCliente['Cedula'] ?></td>
				
			</tr>
			<tr>
				<td>Nombre de Usuario:</td>
				<td><?= $cabCliente['Nombre'] ?></td>				
			</tr>
			<tr>
				<td>Apellido de Usuario:</td>
				<td><?= $cabCliente['Apellido'] ?></td>				
			</tr>
			<tr>
				<td>Alias:</td>
				<td><?= $cabCliente['Alias'] ?></td>				
			</tr>
		</tbody>
	</table>
	<br>

	<table class="tbl-detalle">
		<thead>
			<tr>
			   
				<th >Identificación Dni</th>
				<th >Nombres</th>
				<th >Dirección Domiciliaria</th>	
				<th >Correo Electrónico</th>		
				<th >Teléfono/Celular</th>
				<th >Distribuidor</th>
				<th >Precio</th>
				<th >Forma de Pago</th>

			</tr>
		</thead>
		<tbody>
		<?php 
				foreach ($detCliente as $clientes) {
					
			 ?>
		  <tr>
		      <td class="text-right"><?= $clientes['Cedula'] ?></td>
			  <td class="wd15"><?= $clientes['Nombre'] ?></td>
			  <td class="wd15"><?= $clientes['Direccion'] ?></td>
			  <td class="wd15"><?= $clientes['Correo'] ?></td>
			  <td class="wd15"><?= $clientes['Telefono'] ?></td>
			  <td class="wd15"><?= $clientes['Distribuidor'] ?></td>
			  <td class="wd15"><?= $clientes['Precio'] ?></td>
			  <td class="wd15"><?= $clientes['Pago'] ?></td>

		  </tr>
			  <?php } ?>

		</tbody>
		
	</table>
	<div class="text-center">
		<h4>¡Gracias!</h4>
	</div>
</body>
</html>