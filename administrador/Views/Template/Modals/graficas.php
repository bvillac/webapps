
<?php 
	if($grafica = "ventasMes"){
		$ventasMes = $data;
 ?>
<script>
	  Highcharts.chart('graficaMes', {
	      chart: {
	          type: 'line'
	      },
	      title: {
	          text: 'Ventas de <?= $ventasMes['mes'].' del '.$ventasMes['anio'] ?>'
	      },
	      subtitle: {
	          text: 'Total Ventas <?= SMONEY.'. '.formatMoney($ventasMes['total'],2) ?> '
	      },
	      xAxis: {
	          categories: [
	            <?php 
	                foreach ($ventasMes['ventas'] as $dia) {
	                  echo $dia['dia'].",";
	                }
	            ?>
	          ]
	      },
	      yAxis: {
	          title: {
	              text: ''
	          }
	      },
	      plotOptions: {
	          line: {
	              dataLabels: {
	                  enabled: true
	              },
	              enableMouseTracking: false
	          }
	      },
	      series: [{
	          name: '',
	          data: [
	            <?php 
	                foreach ($ventasMes['ventas'] as $dia) {
	                  echo $dia['total'].",";
	                }
	            ?>
	          ]
	      }]
	  });
</script>
 <?php } ?>

 