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
			$nHora = "HORA: ".$thoras[$h]['ResHora'].":00 --> ";
			$nSalon = "SALÓN: " . $thoras[$h]['SalNombre'];
			echo "<h4 class='tile-title'>{$nHora}  {$nSalon}</h4>";
			fntNewTable();
	
			/*if ($auxHora != $thoras[$h]['ResHora'] ) { 
				if ($h != 0) {
				  fntNewTable($auxHora,$thoras[$h],$strFila);
				  $strFila = "";
				}                         
				$auxHora = $thoras[$h]['ResHora'];
			  }*/
			//$strFila = fntRowHora($thoras[$h]);
			//echo $strFila;
			$h++;
		}
		
	?>
	
	
    
	<?php
		$c++;
	}
?>


<br>


<?php
	function fntNewTable()
	{
		$strtable = "<table class='table tbl-detalle'>";
		$strtable .= fntHeadHora();
		$strtable .= '<tbody>';
		//$strtable .= $strFila;
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