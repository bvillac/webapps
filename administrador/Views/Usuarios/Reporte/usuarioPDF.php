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
				<th>Nombres</th>
				<th>Correo</th>
				<th>Estado</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($resultset as $row) {
				$Estado = ($row['Estado'] != 0) ? "Activo" : "Inactivo";
			?>
				<tr>
					<td class="text-left"><?= $row['per_cedula'] ?></td>
					<td class="text-left"><?= $row['per_nombre'] ." ". $row['per_apellido']?></td>
					<td class="text-left"><?= $row['usu_correo'] ?></td>
					<td class="text-center"><?= $Estado ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>

</div>

<?php
require_once "Views/Template/Pdf/footer.php";
?>