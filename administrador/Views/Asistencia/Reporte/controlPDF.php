<?php
require_once "Views/Template/Pdf/header.php";
$resultset = $data['result'];
?>
<br><br>
<?php ?>
<?php
$c = 0;
while ($c < sizeof($resultset)) {
?>
	<h5 class="tile-title">TUTOR: <?= $resultset[$c]['InsNombre'] ?></h5>
	<?php
	$thoras = $resultset[$c]['Reservado'];
	$h = 0;
	$auxHora = "";
	$ListaHoras = [];
	while ($h < sizeof($thoras)) {
		$nHora = "HORA: " . $thoras[$h]['Hora'] . ":00 --> ";
		$nSalon = "SALÓN: " . $thoras[$h]['Salon'];
	?>
		<h5 class='tile-title'><?= $nHora . $nSalon ?></h5>
		<?php
		if (sizeof($thoras[$h]['Horas']) > 0) {  ?>
			<div class="table-responsive-sm">
				<table class="table tbl-detalle">
					<thead>
						<tr>
							<th>NIVEL</th>
							<th>UNIDAD</th>
							<th>ACTIVIDAD</th>
							<th>USUARIO</th>
							<th>ASISTÍO</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$ListaHoras = $thoras[$h]['Horas'];
						$x = 0;
						while ($x < sizeof($ListaHoras)) {
							$Estado = ($ListaHoras[$x]['Estado'] == "A") ? "SI" : "NO";
						?>
							<tr>
								<td class="text-left"><?= $ListaHoras[$x]['NivNombre'] ?></td>
								<td class="text-left"><?= $ListaHoras[$x]['ResUnidad'] ?></td>
								<td class="text-left"><?= $ListaHoras[$x]['ActNombre'] ?></td>
								<td class="text-left"><?= $ListaHoras[$x]['BenNombre'] ?></td>
								<td class="text-center"><?= $Estado ?></td>
							</tr>
						<?php
							$x++;
						} ?>
					</tbody>
				</table>

			</div>
			
		<?php
			$h++;
		} ?>

	<?php
	}
	?>

<?php
	$c++;
}
?>

<?php
require_once "Views/Template/Pdf/footer.php";
?>