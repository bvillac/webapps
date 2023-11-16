<?php
adminHeader($data);
adminMenu($data);
//filelang(Setlanguage,"general") 
//require_once "Views/Planificacion/Modals/modalSalon.php";
//putMessageLogFile($data);
?>
<script>
  const resultInst = <?= json_encode($data['dataInstructor']) ?>;
  const resultSalon = <?= json_encode($data['dataSalon']) ?>;
  const nLunes = <?= json_encode($data['pla_lunes']) ?>;
  const nMartes = <?= json_encode($data['pla_martes']) ?>;
  const nMiercoles = <?= json_encode($data['pla_miercoles']) ?>;
  const nJueves = <?= json_encode($data['pla_jueves']) ?>;
  const nViernes = <?= json_encode($data['pla_viernes']) ?>;
  const nSabado = <?= json_encode($data['pla_sabado']) ?>;
  const nDomingo = <?= json_encode($data['pla_domingo']) ?>;
  const fechaIni = "<?= $data['pla_fecha_incio'] ?>";
  const fechaFin = "<?= $data['pla_fecha_fin'] ?>";
  const accionFormAut = "Aut";
  const IdsTemp = <?= $data['pla_id'] ?>;
</script>

<div id="contentAjax"></div>
<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-calendar"></i>
        <?= $data['page_title'] ?>

      </h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>/planificacion">
          <?= $data['page_title'] ?>
        </a></li>
    </ul>
  </div>
  <div class="row">
    <input type="hidden" id="txth_ids" name="txth_ids" value="<?= $data['pla_id'] ?>">
    <div class="col-md-12">
      <div class="tile row">
        
          <div class="form-group col-md-4">
            <label for="cmb_CentroAtencion">Centro Atención</label>
            <select class="form-control" id="cmb_CentroAtencion" name="cmb_CentroAtencion" disabled>
              <?php
              echo '<option value="0">SELECCIONAR</option>';
              foreach ($data['centroAtencion'] as $opcion) {
                $seleted = ($opcion['Ids'] == $data['cat_id']) ? 'selected' : '';
                echo '<option value="' . $opcion['Ids'] . '" ' . $seleted . ' >' . $opcion['Nombre'] . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="form-group  col-md-4">
            <label for="dtp_fecha_desde">Fecha Desde</label>
            <input type="date" class="form-control " id="dtp_fecha_desde" name="dtp_fecha_desde" disabled
              placeholder="1988-01-01" pattern="^\d{4}\/\d{2}\/\d{2}$" required=""
              value="<?= $data['pla_fecha_incio'] ?>">
          </div>
          <div class="form-group  col-md-4">
            <label for="dtp_fecha_hasta">Fecha Hasta</label>
            <input type="date" class="form-control " id="dtp_fecha_hasta" name="dtp_fecha_hasta" disabled
              placeholder="1988-01-01" pattern="^\d{4}\/\d{2}\/\d{2}$" required=""
              value="<?= $data['pla_fecha_fin'] ?>">
          </div>


        
        <div class="col-md-12">
          <div class="row">
            <div class="col-sm">
              <button type="button" class="btn btn-dark" id="btn_anteriorAut">  < Anterior</button>
              <button type="button" class="btn btn-dark" id="btn_siguienteAut">Siguiente > </button>
            </div>
            <div class="col-sm">
              <h2><span id="FechaDia" class="badge badge-secondary"></span></h2>
            </div>
            <div class="col-sm">

            </div>
          </div>
          <br><br>
          <h2>Planificación</h2>
          <div class="table-responsive">
            <table class="table" id="dts_PlanificiacionAut">
              <thead>

              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</main>

<?php adminFooter($data); ?>