<?php
require_once "Views/Template/Pdf/header.php";
$resultset = $data['result'];
?>
<br>
<?php
 	$c=0;
	//putMessageLogFile(sizeof($resultset));
	while ($c < sizeof($resultset)) {
?>
	<h5 class="tile-title">TUTOR: <?= $resultset[$c]['InsNombre'] ?></h5>
	<?php ?>
	<?php
		$auxHora = "";
		$thoras =$resultset[$c]['Reservado'];
		$h=0;
		$strFila = "";
		while ($h < sizeof($thoras)) {
			if ($auxHora != $thoras[$h]['ResHora'] ) { 
				if ($h != 0) {
				  fntNewTable($auxHora,$thoras[$h],$strFila);
				  $strFila = "";
				}                         
				$auxHora = $thoras[$h]['ResHora'];
			  }
			//$strFila = fntRowHora($thoras[$h]);
			//echo $strFila;
			$h++;
		}
		
	?>
	
    <h1 class="tile-title">HORA: 9:00 --&gt; SALÓN: GUAYAS </h1>
<?php
		$c++;
	}
?>


<br>


<?php
	function fntNewTable($auxHora, $thoras, $strFila)
	{
		$nHora = "HORA: {$auxHora}:00 --> ";
		$nSalon = "SALÓN: " . $thoras['SalNombre'];
		$strtable = "<h1 class='tile-title'>{$nHora}  {$nSalon}</h1>";
		$strtable .= "<table id='tabHor_{$auxHora}' class='table table-hover'>";
		$strtable .= fntHeadHora();
		$strtable .= '<tbody>';
		$strtable .= $strFila;
		$strtable .= '</tbody>';
		$strtable .= '</table>';
		$strtable .= '<br>';
		echo $strtable;
	}

	function fntHeadHora() {
		$strtable = '<thead>';
			$strtable .= '<tr>';
				$strtable .= '<th>NIVEL</th>';
				$strtable .= '<th>UNIDAD</th>';
				$strtable .= '<th>ACTIVIDAD</th>';
				$strtable .= '<th>USUARIO</th>';
				$strtable .= '<th>ASISTÍO</th>';
			$strtable .= '</tr>';
		$strtable .= '</thead>';
		return $strtable;
	  }

	  function fntRowHora($thoras) {
		$strFila = '<td>' . $thoras['NivNombre'] . '</td>';
		$strFila .= '<td>' . $thoras['ResUnidad'] . '</td>';
		$strFila .= '<td>' . $thoras['ActNombre'] . '</td>';
		$strFila .= '<td>' . $thoras['BenNombre'] . '</td>';
		$nCheck=($thoras['Estado']=="A")?"SI":"NO";	
		$strFila .= '<td>' . $nCheck . '</td>';		
		return '<tr>' . $strFila . '</tr>';
	  }
?>


<?php
require_once "Views/Template/Pdf/footer.php";
?>