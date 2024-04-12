<?php
adminHeader($data);
adminMenu($data);
//filelang(Setlanguage,"general") 
require_once "Views/Planificacion/Modals/modalSalon.php";
//putMessageLogFile($data);
?>
<script>
  const resultInst = <?= json_encode($data['dataInstructor']) ?>;
  const resultSalon = <?= json_encode($data['dataSalon']) ?>;
  const nLunes = <?= json_encode($data['tpla_lunes']) ?>;
  const nMartes = <?= json_encode($data['tpla_martes']) ?>;
  const nMiercoles = <?= json_encode($data['tpla_miercoles']) ?>;
  const nJueves = <?= json_encode($data['tpla_jueves']) ?>;
  const nViernes = <?= json_encode($data['tpla_viernes']) ?>;
  const nSabado = <?= json_encode($data['tpla_sabado']) ?>;
  const nDomingo = <?= json_encode($data['tpla_domingo']) ?>;
  const fechaIni = "<?= $data['tpla_fecha_incio'] ?>";
  const fechaFin = "<?= $data['tpla_fecha_fin'] ?>";
  const accionForm = "Edit";
  const IdsTemp = <?= $data['tpla_id'] ?>;
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
    <input type="hidden" id="txth_ids" name="txth_ids" value="<?= $data['tpla_id'] ?>">
    <div class="col-md-12">
      <div class="tile row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="dtp_fecha_desde">Fecha Desde</label>
            <input type="date" class="form-control " id="dtp_fecha_desde" name="dtp_fecha_desde" 
              placeholder="1988-01-01" pattern="^\d{4}\/\d{2}\/\d{2}$" required=""
              value="<?= $data['tpla_fecha_incio'] ?>">
          </div>
          <div class="form-group">
            <label for="dtp_fecha_hasta">Fecha Hasta</label>
            <input type="date" class="form-control " id="dtp_fecha_hasta" name="dtp_fecha_hasta" 
              placeholder="1988-01-01" pattern="^\d{4}\/\d{2}\/\d{2}$" required=""
              value="<?= $data['tpla_fecha_fin'] ?>">
          </div>
          <div class="form-group">
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
          <div class="form-group">
            <label for="cmb_Salon">Salones</label>
            <select class="form-control" id="cmb_Salon" name="cmb_Salon">
              <?php
              echo '<option value="0">NINGUNO</option>';
              foreach ($data['dataSalon'] as $opcion) {
                echo '<option value="' . $opcion['Ids'] . '" ' . $seleted . ' >' . $opcion['Nombre'] . '</option>';
              }
              ?>
            </select>
          </div>

          <div id="TituloHoras"></div>
          <div id="contenedor-padre" class="d-flex align-content-end flex-wrap">
            <!-- El contenido actual del contenedor padre -->
          </div>
          
        </div>
        <div class="col-md-9">
          <div class="row">
            <div class="col-sm">
              <button type="button" class="btn btn-dark" id="btn_anteriorEdit">
                < Anterior</button>
                  <button type="button" class="btn btn-dark" id="btn_siguienteEdit">Siguiente > </button>
            </div>
            <div class="col-sm">
              <h2><span id="FechaDia" class="badge badge-secondary"></span></h2>
            </div>
            <div class="col-sm">
              <button type="button" class="btn btn-dark" id="btn_saveTemp">Guardar Temporal</button>
              <button type="button" class="btn btn-dark" id="btn_saveAllEdit">Terminar Planificación</button>
            </div>
          </div>
          <br><br>
          <h2>Planificación</h2>
          <div class="table-responsive">
            <table class="table" id="dts_Planificiacion">
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