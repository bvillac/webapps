<?php
require_once "Views/Template/Pdf/header.php";
$resultset = $data['Result'];
?>
<br>


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
				$Estado = ($row['Estado'] != 0) ? "Activo" : "Inactivo";
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

<?php
require_once "Views/Template/Pdf/footer.php";
?>