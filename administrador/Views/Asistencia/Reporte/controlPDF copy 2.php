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
		//$Estado = ($thoras[$h]['Estado'] == "A") ? "SI" : "NO";
		$nHora = "HORA: " . $thoras[$h]['ResHora'] . ":00 --> ";
		$nSalon = "SALÓN: " . $thoras[$h]['SalNombre'];
	?>
		<h5 class='tile-title'><?= $nHora . $nSalon ?></h5>
		<?php
		if ($auxHora != $thoras[$h]['ResHora']) {
			if ($h != 0) { ?>

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
				<br><br>



		<?php	}
			$ListaHoras[] = $thoras[$h];
			$auxHora = $thoras[$h]['ResHora'];
			//$ListaHoras=[];
		} else {
			$ListaHoras[] = $thoras[$h];
		}
		?>

	<?php
		$h++;
	} ?>

<?php
	$c++;
}
?>

<?php
require_once "Views/Template/Pdf/footer.php";
?>