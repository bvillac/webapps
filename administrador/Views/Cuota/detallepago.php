<?php 
    adminHeader($data);
    adminMenu($data); 
    //filelang(Setlanguage,"general") 
    //require_once "Views/Salon/Modals/modalSalon.php";
    //putMessageLogFile($data);
    $contrato = $data['contrato'];
    $movimientos = $data['movimiento'];
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
    <div class="col-md-3">

      <div class="card card-primary card-outline">
        <div class="card-body box-profile">
          <div class="text-center">
            <!--<img class="profile-user-img img-fluid img-circle" src="../../dist/img/user4-128x128.jpg" alt="User profile picture">-->
            <img class="app-sidebar__user-avatar" src="<?= media() ?>/images/fotoAdmin.jpg" alt="Perfil Cliente">
          </div>
          <h3 class="profile-username text-center"><?= $contrato['RazonSocial'] ?></h3>
          <p class="text-muted text-center"><?= $contrato['DNI'] ?></p>

          <!--<a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>-->
        </div>

      </div>


      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Información</h3>
        </div>

        <div class="card-body">
          <strong><!--<i class="fas fa-book mr-1"></i>--> Contrato</strong>
          <ul class="text-muted list-group list-group-unbordered mb-3">
            <li class="list-group-item">
              <b>Número</b> <a class="float-right"><?= $contrato['Contrato'] ?></a>
            </li>
            <li class="list-group-item">
              <b>Fecha Ingreso</b> <a class="float-right"><?= $contrato['FechaInicio'] ?></a>
            </li>
            <li class="list-group-item">
              <b>Número Pagos</b> <a class="float-right"><?= $contrato['Npagos'] ?></a>
            </li>
            <li class="list-group-item">
              <b>Valor Mensual</b> <a class="float-right"><?= $contrato['ValorMensual'] ?></a>
            </li>
          </ul>
          <!--<hr>-->
       

          
          <strong><i class="far fa-file-alt mr-1"></i> Observación</strong>
          <p class="text-muted">No tiene Observaciones</p>
        </div>

      </div>

    </div>

    <div class="col-md-9">
      <div class="card">
        <div class="card-header p-2">
          <h3>DETALLE PAGOS</h3>
        </div>
        <div class="card-body">
          <div class="tab-content">


            <table id="dts_Control" class="table table-hover table-striped" >
              <thead>
                <tr>
                  <th class="text-center">NUMERO</th>
                  <th class="text-center">F.VENCE</th>
                  <th class="text-center">F.ABONO</th>
                  <th class="text-center">ABONO</th>
                  <th class="text-center">SALDO</th>
                  <th class="text-center">ESTADO</th>                               
                  <th class="text-center"></th>
                </tr>
              </thead>
              <tbody>
              <?php
                  foreach ($movimientos as $movimiento) {
                    $bloqueo=($movimiento['ESTADO']=="C")?"disabled":""; 
                    $idsMov=$movimiento['IDS'];                   
                    //$valorPor=($control['Valor']!="")? $control['Valor'] ." %":"";
                    //$observacion='<a href="#" class="btn btn-sm bg-teal"><i class="fa fa-comments"></i></a>';
                    //$valorPor=($control['Valor']!="")? "<span class='badge bg-primary'>".$control['Valor'] ." %</span>":"";
                    //$estadoAccion=($movimiento['ESTADO']!="N")? "<button  id='COB_" . $movimiento['IDS'] . "' class='btn btn-primary  btn-sm' onClick='fntRegistarPago('".$movimiento['IDS'] ."')" . $bloqueo . "title='Hacer Pago'><i class='fa fa-money'></i></button>":"";
                    $estadoAccion=($movimiento['ESTADO']!="N")? "<button id='COB_{$idsMov}' class='btn btn-primary  btn-sm' onClick='fntRegistarPago({$idsMov})' " . $bloqueo . " title='Hacer Pago'><i class='fa fa-money'></i></button>":"";
                  ?>
                    <tr>
                      <td class="text-center"><?= $movimiento['NUMERO'] ?></td>
                      <td class="text-center"><?= $movimiento['FECHA_VENCE'] ?></td>
                      <td class="text-center"><?= $movimiento['FECHA_PAGO'] ?></td>                
                      <td class="text-right"><?= $movimiento['CREDITO'] ?></td>
                      <td class="text-right"><?= $movimiento['SALDO'] ?></td>
                      <td class="text-center"><?= $movimiento['CANCELADO'] ?></td>                                 
                      <td class="text-left"><?= $estadoAccion ?></td>
                    </tr>
                  <?php } ?>
              </tbody>
            </table>

          </div>
        </div>
      </div>

    </div>

  </div>



    </main>
<?php adminFooter($data); ?>
    