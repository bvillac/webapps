<?php
adminHeader($data);
adminMenu($data);

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
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>/<?= $data['page_back'] ?>"><?= $data['page_title'] ?></a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="tile">

        <div class="row mb-2">
          <div class="col-md-3">
            <label>Cliente</label>
            <select class="form-control" data-live-search="true" id="filtroCliente" name="filtroCliente">
              <?php
              // Recorre el array y genera las opciones del select
              echo '<option value="0">SELECCIONAR</option>';
              foreach ($data['cliente'] as $opcion) {
                $seleted = 0; //($opcion['Ids']==$data['CentroId'])?'selected':'';
                echo '<option value="' . $opcion['Ids'] . '" ' . $seleted . ' >' . $opcion['Nombre'] . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="col-md-3">
            <label>Tienda</label>
            <!-- <input type="text" id="filtroTienda" class="form-control" placeholder="Nombre tienda"> -->
            <select class="form-control" data-live-search="true" id="filtroTienda" name="filtroTienda"
                  data-none-selected-text="SELECCIONAR TIENDA" title="SELECCIONAR TIENDA" >
                  <?php
                  echo '<option value="0">TODOS</option>';
                  ?>
                </select>
          </div>
          <div class="col-md-3">
            <label>Fecha Inicio</label>
            <input type="date" id="filtroFechaInicio" class="form-control" placeholder="Fecha inicio">
          </div>
          <div class="col-md-3">
            <label>Fecha Fin</label>
            <input type="date" id="filtroFechaFin" class="form-control" placeholder="Fecha fin">
          </div>


        </div>
        <div class="row mb-3">

          <div class="col-md-3">
            <button id="btnFiltrar" class="btn btn-primary w-100">Buscar</button>
          </div>
        </div>


        <div class="tile-body">
          <div class="table-responsive">
            <table class="table table-hover table-bordered" id="tableReporteLiquidar" style="width:100%">
              <thead>
                <tr>
                  <th>O.Compra</th>
                  <th>N° Solicitud</th>
                  <th>Tienda</th>
                  <th>Codigo</th>
                  <th>Nombre</th>
                  <th>Cantidad</th>
                  <th>Total</th>
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