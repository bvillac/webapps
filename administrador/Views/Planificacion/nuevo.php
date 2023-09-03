<?php
adminHeader($data);
adminMenu($data);
//filelang(Setlanguage,"general") 
require_once "Views/Planificacion/Modals/modalSalon.php";
?>
<div id="contentAjax"></div>
<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-calendar"></i> <?= $data['page_title'] ?>

      </h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>/planificacion"><?= $data['page_title'] ?></a></li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tile row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="dtp_fecha_desde">Fecha Desde</label>
            <input type="date" class="form-control " id="dtp_fecha_desde" name="dtp_fecha_desde" placeholder="1988-01-01" pattern="^\d{4}\/\d{2}\/\d{2}$" required="">
          </div>
          <div class="form-group">
            <label for="dtp_fecha_hasta">Fecha Hasta</label>
            <input type="date" class="form-control " id="dtp_fecha_hasta" name="dtp_fecha_hasta" placeholder="1988-01-01" pattern="^\d{4}\/\d{2}\/\d{2}$" required="">
          </div>
          <div class="form-group">
            <label for="cmb_CentroAtencion">Centro Atención</label>
            <select class="form-control" id="cmb_CentroAtencion" name="cmb_CentroAtencion" onchange="fntSalones(this.value)">
              <?php
              echo '<option value="0">SELECCIONAR</option>';
              foreach ($data['centroAtencion'] as $opcion) {
                echo '<option value="' . $opcion['Ids'] . '" >' . $opcion['Nombre'] . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label for="cmb_Salon">Salones</label>
            <select class="form-control" id="cmb_Salon" name="cmb_Salon" disabled>
            </select>
          </div>
          <div class="form-group">
            <label for="cmb_instructor">Instructor</label>
            <select class="form-control" data-live-search="true" id="cmb_instructor" name="cmb_instructor" title="SELECCIONAR INSTRUCTOR" onchange="fntHorasInstructor(this.value)">
              <?php
              /* Recorre el array y genera las opciones del select
              echo '<option value="0">SELECCIONAR</option>';
              foreach ($data['instructor'] as $opcion) {
                echo '<option value="' . $opcion['Ids'] . '">' . $opcion['Nombre'] . '</option>';
              }*/
              ?>
            </select>
          </div>
          <div id="TituloHoras"></div>
          <div id="contenedor-padre" class="d-flex align-content-end flex-wrap">
            <!-- El contenido actual del contenedor padre -->
          </div>
          <button type="button" class="btn btn-dark" id="btn_generar">Generar</button>
        </div>
        <div class="col-md-9">
          <div class="row">
            <div class="col-sm">
              <button type="button" class="btn btn-dark" id="btn_anterior">
                < Anterior</button>
                  <button type="button" class="btn btn-dark" id="btn_siguiente">Siguiente > </button>
            </div>
            <div class="col-sm">
              <h2><span id="FechaDia" class="badge badge-secondary"></span></h2>
            </div>
            <div class="col-sm">
              <button type="button" class="btn btn-dark" id="btn_saveTemp">Guardar Temporal</button>
              <button type="button" class="btn btn-dark" id="btn_saveAll">Terminar Planificación</button>
            </div>
          </div>
          <br><br><h2>Planificación</h2>
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