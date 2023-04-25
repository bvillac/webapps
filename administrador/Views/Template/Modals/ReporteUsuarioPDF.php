<?php 
	$empresa = $data['empData'];
	$cabUsuario = $data['cabReporte'];
	$detUsuario = $data['detReporte'];
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
			font-size: 10px;
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
					<p><h4>REPORTE DE USUARIOS REGISTRADOS</h4><br>
					
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
				<td><?= $cabUsuario['Cedula'] ?></td>
				
			</tr>
			<tr>
				<td>Nombre de Usuario:</td>
				<td><?= $cabUsuario['Nombre'] ?></td>				
			</tr>
			<tr>
				<td>Apellido de Usuario:</td>
				<td><?= $cabUsuario['Apellido'] ?></td>				
			</tr>
			<tr>
				<td>Alias:</td>
				<td><?= $cabUsuario['Alias'] ?></td>				
			</tr>
		</tbody>
	</table>
	<br>

	<table class="tbl-detalle">
		<thead>
			<tr>
			   
				<th >Identificación Dni</th>
				<th >Nombres</th>
				<th >Apellidos</th>	
				<th >Álias</th>		
				<th >Correo electrónico</th>
				<th >Rol</th>
			</tr>
		</thead>
		<tbody>
		<?php 
				foreach ($detUsuario as $usuarios) {
					
			 ?>
		  <tr>
		      <td class="text-right"><?= $usuarios['per_cedula'] ?></td>
			  <td><?= $usuarios['per_nombre'] ?></td>
			  <td><?= $usuarios['per_apellido'] ?></td>
			  <td><?= $usuarios['usu_alias'] ?></td>
			  <td><?= $usuarios['usu_correo'] ?></td>
			  <td><?= $usuarios['rol_nombre'] ?></td>
		  </tr>
			  <?php } ?>

		</tbody>
		
	</table>
	<div class="text-center">
		<h4>¡Gracias!</h4>
	</div>
</body>
</html>