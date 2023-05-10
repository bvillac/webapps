<?= adminHeader($data) ?>
<?= adminMenu($data) ?>
<?php //filelang(Setlanguage,"general") ?>
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
          <a href="<?= base_url() ?>/usuarios/generarReporteUsuarioPDF/" class="linkw">
            <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
              <div class="info">
                <h5>Reporte de Usuarios</h5>
                <p><b><?= $data['usuarios'] ?></b></p>
              </div>
            </div>
          </a>
        </div>
        <div class="col-md-3 col-lg-3">
          <a href="<?= base_url() ?>/cliente/generarReporteClientePDF/" class="linkw">
            <div class="widget-small info coloured-icon"><i class="icon fa fa-user fa-3x"></i>
              <div class="info">
                <h5>Reporte de Clientes</h5>
                <p><b><?= $data['clientes'] ?></b></p>
              </div>
            </div>
          </a>
        </div>
        <div class="col-md-3 col-lg-3">
          <a href="<?= base_url() ?>/proveedor/generarReporteProveedorPDF/" class="linkw">
            <div class="widget-small primary coloured-icon"><i class="icon fa fa-user-circle fa-3x"></i>
              <div class="info">
                <h5>Reporte de Proveedores</h5>
                <p><b><?= $data['proveedores'] ?></b></p>
              </div>
            </div>
          </a>
        </div>
       <!-- <div class="col-md-3 col-lg-3">
          <a href="<?= base_url() ?>/items/generarReporteItemPDF/" class="linkw">
            <div class="widget-small warning coloured-icon"><i class="icon fa fa fa-archive fa-3x"></i>
              <div class="info">
                <h5>Reporte de Productos</h5>
                <p><b><?php //$data['productos'] ?></b></p>
              </div>
            </div>
          </a>
        </div>
       <div class="col-md-3 col-lg-3">
          <a href="<?= base_url() ?>/venta" class="linkw">
            <div class="widget-small danger coloured-icon"><i class="icon fa fa-shopping-cart fa-3x"></i>
              <div class="info">
                <h4>Ventas</h4>
                <p><b><?php //$data['pedidos'] ?></b></p>
              </div>
            </div>
          </a>
        </div>-->
        <!-- <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">Create a beautiful dashboard <?php //$varlang['title'] ?></div>
                 <?php //dep($_SESSION['usuarioData']);
                    //dep($_SESSION['permisos']);                    
                    //dep($_SESSION['permisosMod']);
                 ?>

            </div>
        </div> -->
    </div>

    <div class="row">
        <?php //if(!empty($_SESSION['permisos'][5]['r'])){ ?>
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Últimas Ventas</h3>
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Cliente</th>
                  <th>Estado</th>
                  <th class="text-right">Total</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php 
                    if(count($data['lastOrders']) > 0 ){
                      foreach ($data['lastOrders'] as $pedido) {
                 ?>
                <tr>
                
                  <td><?= $pedido['Numero'] ?></td>
                  <td><?= $pedido['Nombre'] ?></td>
                  <td><?= $pedido['Estado'] ?></td>
                  <td class="text-right"><?= SMONEY." ".formatMoney($pedido['Monto'],2) ?></td>
                  <td></td>
                  <!-- <td><a href="<?= base_url() ?>/pedidos/orden/<?= $pedido['Ids'] ?>" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a></td> -->
                </tr>
                    <?php  } 
                   } ?>

              </tbody>
            </table>
          </div>
        </div>
        <?php //} ?>

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

    <!-- COMPRAS -->
    <div class="row">
        <?php //if(!empty($_SESSION['permisos'][5]['r'])){ ?>
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Últimas Compras</h3>
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Proveedor</th>
                  <th>Estado</th>
                  <th class="text-right">Total</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php 
                    if(count($data['lastCompras']) > 0 ){
                      foreach ($data['lastCompras'] as $pedido) {
                 ?>
                <tr>
                
                  <td><?= $pedido['Numero'] ?></td>
                  <td><?= $pedido['Nombre'] ?></td>
                  <td><?= $pedido['Estado'] ?></td>
                  <td class="text-right"><?= SMONEY." ".formatMoney($pedido['Monto'],2) ?></td>
                  <td></td>
                  <!-- <td><a href="<?= base_url() ?>/pedidos/orden/<?= $pedido['Ids'] ?>" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a></td> -->
                </tr>
                    <?php  } 
                   } ?>

              </tbody>
            </table>
          </div>
        </div>
        <?php //} ?>

         <div class="col-md-6">
            <div class="tile">
                <div class="container-title">
                <h3 class="tile-title">Compras por mes</h3>
                <div class="dflex">
                    <input class="date-picker ventasCompraMes" name="ventasCompraMes" placeholder="Mes y Año">
                    <button type="button" class="btnVentasMes btn btn-info btn-sm" onclick="fntSearchCMes()"> <i class="fa fa-search"></i> </button>
                </div>
                </div>
            <div id="graficaCompraMes"></div>
          </div>
        </div>  


    </div>

      <!-- Utilidad Items -->
      <div class="row">
        <?php //if(!empty($_SESSION['permisos'][5]['r'])){ ?>
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Útilidad de Productos</h3>
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th>Codigo</th>
                  <th>Nombre</th>
                  <th>Utilidad</th>
                  <th class="text-right">%</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                    if(count($data['itemUtilidad']) > 0 ){
                      foreach ($data['itemUtilidad'] as $pedido) {
                 ?>
                <tr>

        
                
                  <td><?= $pedido['Codigo'] ?></td>
                  <td><?= $pedido['Nombre'] ?></td>
                  <td><?= SMONEY." ".formatMoney($pedido['Utilidad'],2) ?></td>
                  <td class="text-right"><?= formatMoney($pedido['POR'],2) ."%" ?></td>
                  <td></td>
                  <!-- <td><a href="<?= base_url() ?>/pedidos/orden/<?= $pedido['Ids'] ?>" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a></td> -->
                </tr>
                    <?php  } 
                   } ?>

              </tbody>
            </table>
          </div>
        </div>
        <?php //} ?>
        <!-- Utilidad Marcas -->
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Útilidad por Marca</h3>
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th>Marca</th>
                  <th>Utilidad</th>
                  <th class="text-right">%</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                    if(count($data['itemMarca']) > 0 ){
                      foreach ($data['itemMarca'] as $pedido) {
                 ?>
                <tr>

        
                  <td><?= $pedido['Marca'] ?></td>
                  <td><?= SMONEY." ".formatMoney($pedido['Utilidad'],2) ?></td>
                  <td class="text-right"><?= formatMoney($pedido['POR'],2) ."%" ?></td>
                  <td></td>
                  <!-- <td><a href="<?= base_url() ?>/pedidos/orden/<?= $pedido['Ids'] ?>" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a></td> -->
                </tr>
                    <?php  } 
                   } ?>

              </tbody>
            </table>
          </div>
        </div>

          <!-- Existencia Minima -->
          <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Existencía Mínima</h3>
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th>Codigo</th>
                  <th>Nombre</th>                  
                  <th class="text-right">Mínima</th>
                  <th class="text-right">Stock</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                    if(count($data['itemMinima']) > 0 ){
                      foreach ($data['itemMinima'] as $pedido) {
                 ?>
                <tr>
                  <td><?= $pedido['Codigo'] ?></td>
                  <td><?= $pedido['Nombre'] ?></td>
                  <td><?= $pedido['Minima'] ?></td>
                  <td><?= $pedido['Stock'] ?></td>
                </tr>
                    <?php  } 
                   } ?>
              </tbody>
            </table>
          </div>
        </div>

        
      </div>


 
     

</main>
<?= adminFooter($data) ?>