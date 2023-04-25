<?php
adminHeader($data);
adminMenu($data);

getModal('modalItemsBuscar', $data);
?>
<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fas fa-box"></i> <?= $data['page_title'] ?>

      </h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>/Items"><?= $data['page_title'] ?></a></li>
    </ul>
  </div>
  <div class="row">


    <div class="col-md-12">
      <div class="tile">
        <div class="tile-body">
          <div class="row">
            <div class="form-group col-md-6">
              <label for="cmb_bodega">Bodega <span class="required">*</span></label>
              <select class="form-control" data-live-search="true" id="cmb_bodega" name="cmb_bodega" required=""></select>
            </div>
            <div class="form-group col-md-6"></div>

          </div>

          <div class="row">
            <div class="form-group col-md-3">
              <div class="input-group">
                <input class="form-control" id="txtCodigoItem" name="txtCodigoItem" type="text" required="" placeholder="Buscar Item">
                <button id="cmd_buscarItem" class="btn btn-primary" onclick="openModalBuscar();" type="button"><i class=" fa fa-search-plus"></i></button>
              </div>
            </div>
            <div class="form-group col-md-3">
              <input class="form-control" id="txtDetalleItem" name="txtDetalleItem" type="text" value="" disabled>
            </div>
            <div class="form-group col-md-3">
              <button id="cmd_buscar" class="btn btn-primary" type="button">Buscar</button>
            </div>

            <div class="form-group col-md-3">
              <!-- <h5 class="text-right">Fecha: <?= date("Y-m-d") ?></h5> -->
            </div>
          </div>


          <div class="table-responsive">
            <table class="table table-hover table-bordered" id="tableMovimiento">
              <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Ingreso</th>
                  <th>Egreso</th>
                  <th>Cantidad</th>
                  <th>Saldo</th>
                  <th>Estado</th>
                  <th>Referencia</th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>


          <div class="table-responsive">
            <table class="table table-hover ">
              <tbody>
                <tr>
                  <td class="textright">Total Ingresos : <span id="lbl_tIngreso">0.00</span></td>
                  <td class="textright">Total Egresos : <span id="lbl_tEgreso">0.00</span></td>
                  <td class="textright">Total Saldo : <span id="lbl_tSaldo">0.00</span></td>                 
                </tr>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>
</main>
<?php adminFooter($data); ?>