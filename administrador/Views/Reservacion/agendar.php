<?php
adminHeader($data);
adminMenu($data);
//filelang(Setlanguage,"general") 
require_once "Views/Reservacion/Modals/modalAgenda.php";
require_once "Views/Reservacion/Modals/modalPagos.php";
//putMessageLogFile($data['dataNivel']);
//putMessageLogFile("fecha ".$data['fechaDia']);
//putMessageLogFile("accion ".$data['accion']);
?>
<script>
  const resultInst = <?= json_encode($data['dataInstructor']) ?>;
  const resultSalon = <?= json_encode($data['dataSalon']) ?>;
  const resultNivel = <?= json_encode($data['dataNivel']) ?>;
  const resultNumRes = <?= json_encode($data['numero_reser']) ?>;
  const reservacion = <?= json_encode($data['reservacion']) ?>;
  const nLunes = <?= json_encode($data['pla_lunes']) ?>;
  const nMartes = <?= json_encode($data['pla_martes']) ?>;
  const nMiercoles = <?= json_encode($data['pla_miercoles']) ?>;
  const nJueves = <?= json_encode($data['pla_jueves']) ?>;
  const nViernes = <?= json_encode($data['pla_viernes']) ?>;
  const nSabado = <?= json_encode($data['pla_sabado']) ?>;
  const nDomingo = <?= json_encode($data['pla_domingo']) ?>;
  const accionFormAut = "<?= $data['accion'] ?>";
  const CentroIds = <?= $data['cat_id'] ?>;
  const IdsTemp = <?= $data['pla_id'] ?>;
  const fechaIni = "<?= $data['pla_fecha_incio'] ?>";
  const fechaFin = "<?= $data['pla_fecha_fin'] ?>";
  let fechaDia = "<?= $data['fechaDia'] ?>";
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
    <input type="hidden" id="txth_idBenef" name="txth_idBenef" value="">
    <input type="hidden" id="txth_fechaReservacion" name="txth_fechaReservacion" value="">
    <div class="col-md-12">



      <div class="tile row">
        <div class="form-group col-md-12">
          <h3>Centro: <span class="badge badge-secondary"><?= $data['Centro'] ?></span> Fecha Desde: <span class="badge badge-secondary"><?= $data['pla_fecha_incio'] ?></span> Fecha Hasta: <span class="badge badge-secondary"><?= $data['pla_fecha_fin'] ?></span></h3>
        </div>

        <div class="col-sm">
          <div class="row">
            <div class="form-group col-md-6">
              <label for="txt_NumeroContrato">Número Contrato<span class="required">*</span></label>
              <input class="form-control" id="txt_NumeroContrato" name="txt_NumeroContrato" type="text" required="" placeholder="Buscar por Contrato">
            </div>
            <div class="form-group col-md-6">
              <label for="txt_CodigoBeneficiario">DNI Beneficiario<span class="required">*</span></label>
              <input class="form-control" id="txt_CodigoBeneficiario" name="txt_CodigoBeneficiario" type="text" required="" placeholder="Buscar por Nombre o DNI">
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-12">
              <label for="txt_NombreBeneficirio">Nombre <span class="required">*</span></label>
              <input class="form-control" id="txt_NombreBeneficirio" name="txt_NombreBeneficirio" type="text" disabled>
            </div>
          </div>


        </div>
        <div class="col-sm">
          <h5 class="line-head">Reservados</h5>
          <ul class="list-group" id="list_beneficiariosPrimary">


          </ul>
        </div>







        <div class="col-md-12">
          <div class="row">
            <div class="col-sm">
              <button type="button" class="btn btn-dark" id="btn_anteriorAut">
                < Anterior</button>
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