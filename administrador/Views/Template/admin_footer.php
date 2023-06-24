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
    <script src="<?= media();?>/js/fontawesome.js"></script>
   
    <!-- The javascript plugin to display page loading on top-->
    <script src="<?= media(); ?>/js/plugins/pace.min.js"></script>
    <!-- Page specific javascripts-->
    <script type="text/javascript" src="<?= media(); ?>/js/plugins/sweetalert.min.js"></script>
    <script type="text/javascript" src="<?= media(); ?>/js/tinymce/tinymce.min.js"></script>
    <!-- Data table plugin-->
    <script type="text/javascript" src="<?= media(); ?>/js/plugins/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?= media(); ?>/js/plugins/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= media();?>/js/plugins/bootstrap-select.min.js"></script>
    <!-- Data table plugin Exportar-->
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <!-- Graficos plugin-->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <!-- Data Datepicker-->
    <script src="<?= media();?>/js/datepicker/jquery-ui.min.js"></script>
    <!-- Funciones de Objetos Metodos-->
    <script src="<?= media() ?>/js/funcionesAdmin.js"></script>

    <?= incluirJs() ?>

  </body>
</html>