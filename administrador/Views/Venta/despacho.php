<?php 
      adminHeader($data);
      adminMenu($data);  
      //getModal('modalItems',$data); 
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
                  <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="tableDespacho">
                      <thead>
                        <tr>
                          <th>N° Factura</th>
                          <th>Fecha</th> 
                          <th>Ruc/Cedula</th>                                         
                          <th>Cliente</th>
                          <th>Total</th>
                          <th>Forma Pago</th>
                          <th>Despacho</th>
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
    <script>
        //let accionData="index"; 
    </script>
 
    <?php adminFooter($data); ?>
    