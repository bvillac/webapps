<script>
  //Ruta Globa Site 
  const base_url = "<?= base_url(); ?>";
  const cdnTable = "<?= cdnTableLink(); ?>";
</script>
<!-- Essential javascripts for application to work-->
<script src="<?= media() ?>/js/mainvar.js"></script>
<script src="<?= media() ?>/js/jquery-3.3.1.min.js"></script>
<script src="<?= media() ?>/js/popper.min.js"></script>
<script src="<?= media() ?>/js/bootstrap.min.js"></script>
<script src="<?= media() ?>/js/main.js"></script>
<script src="<?= media(); ?>/js/fontawesome.js"></script>

<!-- The javascript plugin to display page loading on top-->
<script src="<?= media(); ?>/js/plugins/pace.min.js"></script>
<!-- Page specific javascripts-->
<script type="text/javascript" src="<?= media(); ?>/js/plugins/sweetalert.min.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/tinymce/tinymce.min.js"></script>
<!-- Data table plugin-->
<script type="text/javascript" src="<?= media(); ?>/js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/plugins/bootstrap-select.min.js"></script>
<!-- Data table plugin Exportar-->
<script type="text/javascript" src="<?= media(); ?>/js/cdn/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/cdn/jszip.min.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/cdn/pdfmake.min.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/cdn/vfs_fonts.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/cdn/buttons.html5.min.js"></script>


<!-- Graficos plugin-->
<script type="text/javascript" src="<?= media(); ?>/js/highcharts/highcharts.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/highcharts/exporting.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/highcharts/export-data.js"></script>
<!-- Data Datepicker-->
<script src="<?= media(); ?>/js/datepicker/jquery-ui.min.js"></script>
<!-- Funciones de Objetos Metodos-->
<script src="<?= media() ?>/js/funcionesAdmin.js"></script>

<?= incluirJs() ?>

</body>

</html>