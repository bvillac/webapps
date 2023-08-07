<?php
adminHeader($data);
adminMenu($data);
//filelang(Setlanguage,"general") 
require_once "Views/Salon/Modals/modalSalon.php";
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
            <select class="form-control" data-live-search="true" id="cmb_instructor" name="cmb_instructor" onchange="fntHorasInstructor(this.value)">
              <?php
              // Recorre el array y genera las opciones del select
              echo '<option value="0">SELECCIONAR</option>';
              foreach ($data['instructor'] as $opcion) {
                echo '<option value="' . $opcion['Ids'] . '">' . $opcion['Nombre'] . '</option>';
              }
              ?>
            </select>
          </div>
          <!--<div id="external-events">
            <h4 class="mb-4">Horas</h4>
            <div class="fc-event">My Event 1</div>
            <div class="fc-event">My Event 2</div>
            <div class="fc-event">My Event 3</div>
            <div class="fc-event">My Event 4</div>
            <div class="fc-event">My Event 5</div>
            <p class="animated-checkbox mt-20">
              <label>
                <input id="drop-remove" type="checkbox"><span class="label-text">Remove after drop</span>
              </label>
            </p>
          </div>-->
          <div id="contenedor-padre">
            <!-- El contenido actual del contenedor padre -->
          </div>
          <button id="agregarBoton">Agregar Div</button>
        </div>
        <div class="col-md-9">
          <div id="calendar"></div>
        </div>
      </div>
    </div>
  </div>


</main>

<?php adminFooter($data); ?>