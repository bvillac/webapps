<?= adminHeader($data) ?>
<?= adminMenu($data) ?>
<?php //filelang(Setlanguage,"general") 
?>
<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-dashboard"></i> <?= $data['page_title'] ?></h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>/dashboard">Dashboard</a></li>
    </ul>
  </div>


  <div class="row">
    <div class="col-md-3 col-lg-3">
      <a href="<?= base_url() ?>/usuarios/reporteUsuariosPDF/" class="linkw">
        <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
          <div class="info">
            <h5>Reporte de Usuarios</h5>
            <p><b><?= $data['usuarios'] ?></b></p>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-3 col-lg-3">
      <a href="<?= base_url() ?>/clienteMiller/generarReporteClientesPDF/" class="linkw">
        <div class="widget-small info coloured-icon"><i class="icon fa fa-user fa-3x"></i>
          <div class="info">
            <h5>Reporte de Clientes</h5>
            <p><b><?= $data['clientes'] ?></b></p>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-3 col-lg-3">
      <a href="<?= base_url() ?>/beneficiario/generarReporteBeneficiarioPDF/" class="linkw">
        <div class="widget-small info coloured-icon"><i class="icon fa fa-user fa-3x"></i>
          <div class="info">
            <h5>Reporte de Beneficiarios</h5>
            <p><b><?= $data['beneficiario'] ?></b></p>
          </div>
        </div>
      </a>
    </div>


  </div>

  <div class="row">
    <?php //if(!empty($_SESSION['permisos'][5]['r'])){ 
    ?>
    <div class="col-md-6">
      <div class="tile">
        <h3 class="tile-title">Últimos Contratos</h3>
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>#</th>
              <th>Razón Social</th>
              <th>Estado</th>
              <th class="text-right">Total</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (count($data['lastContrato']) > 0) {
              foreach ($data['lastContrato'] as $pedido) {
                $Estado = ($pedido['Estado'] != 0) ? "Activo" : "Inactivo";
            ?>
                <tr>

                  <td><?= $pedido['Numero'] ?></td>
                  <td><?= $pedido['RazonSocial'] ?></td>
                  <td><?= $Estado ?></td>
                  <td class="text-right"><?= SMONEY . " " . formatMoney($pedido['Total'], 2) ?></td>
                  <td><a href="<?= base_url() ?>/Contrato/generarContratoPDF/<?= $pedido['Ids'] ?>" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                </tr>
            <?php  }
            } ?>

          </tbody>
        </table>
      </div>
    </div>
    <?php //} 
    ?>

    <div class="col-md-6">
      <div class="tile">
        <div class="container-title">
          <h3 class="tile-title">Ventas por mes</h3>
          <div class="dflex">
            <input class="date-picker ventasMes" name="ventasMes" placeholder="Mes y Año">
            <button type="button" class="btnVentasMes btn btn-info btn-sm" onclick="fntSearchVMes()"> <i class="fa fa-search"></i> </button>
          </div>
        </div>
        <div id="graficaMes"></div>
      </div>
    </div>


  </div>







</main>
<?= adminFooter($data) ?>