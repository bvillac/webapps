<?php
adminHeader($data);
adminMenu($data);
//filelang(Setlanguage,"general") 
//require_once "Views/Salon/Modals/modalSalon.php";
?>
<div id="contentAjax"></div>
<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fas fa-user-tag"></i> <?= $data['page_title'] ?>

      </h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>/<?= $data['page_back'] ?>"><?= $data['page_title'] ?></a></li>
    </ul>
  </div>
  

  <div class="row">
    <!-- <div class="col-md-12">
      <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
      <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
        Payment
      </button> 
      <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
        <i class="fa fa-download"></i> Generate PDF
      </button>
    </div> -->
    <div class="col-md-12">
      <div class="tile">
        <div class="tile-body">
          <div class="table-responsive">
            <table class="table table-hover table-bordered" id="tableCuota">
              <thead>
                <tr>
                  <th>DNI</th>
                  <th>Razón Social</th>
                  <th>Fecha Contrato</th>
                  <th>Nro.Contrato</th>
                  <th>Nro.Pagos</th>
                  <th>V.Mensual</th>
                  <th>V.Debito</th>
                  <th>V.Abono</th>
                  <th>Saldo</th>
                  <th>F.Ult.Pago</th>
                  <th>Estado</th>
                  <th>Acciones</th>
                </tr>
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