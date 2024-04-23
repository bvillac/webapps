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
            <h1><i class="fas fa-user-tag"></i> <?= $data['page_title'] ?></h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="<?= base_url(); ?>/<?= $data['page_back'] ?>"><?= $data['page_title'] ?></a></li>
        </ul>
      </div>


      <div class="row">
        <div class="col-md-3">
          <div class="tile row">
              <div class="form-group col-md-12">
                <label for="cmb_CentroAtencion">Centro Atención</label>
                <select class="form-control" id="cmb_CentroAtencion" name="cmb_CentroAtencion" onchange="fntInstructor(this.value)">
                  <?php
                  echo '<option value="0">SELECCIONAR</option>';
                  foreach ($data['centroAtencion'] as $opcion) {
                    echo '<option value="' . $opcion['Ids'] . '" >' . $opcion['Nombre'] . '</option>';
                  }
                  ?>
                </select>
              </div>
              <div class="form-group col-md-12">
                <label for="cmb_instructor">Instructor</label>
                <select class="form-control" data-live-search="true" id="cmb_instructor" name="cmb_instructor" title="SELECCIONAR INSTRUCTOR" >
                </select>
              </div>
              <div class="form-group col-md-12">
                <label for="dtp_fecha">Fecha</label>
                <input type="date" class="form-control " id="dtp_fecha" name="mdtp_fecha" placeholder="1988-01-01" pattern="^\d{4}\/\d{2}\/\d{2}$" required="">
              </div>
              <div class="form-group col-md-12">
                <label for="cmb_hora">Hora</label>
                <select class="form-control" id="cmb_hora" name="cmb_hora" >
                  <?php
                  echo '<option value="0">SELECCIONAR</option>';
                  foreach ($data['horarios'] as $opcion) {
                    echo '<option value="' . $opcion . '" >' . $opcion . ':00</option>';
                  }
                  
                  ?>
                </select>
              </div>
              <div class="form-group col-md-12">
                <button type="button" class="btn btn-dark" id="btn_buscar"><i class="fa fa-magnifying-glass"></i>Buscar</button>
                <button type="button" class="btn btn-success" id="btn_imprimir"><i class="fa fa-print"></i>Imprimir</button>
              </div>
              
          </div>
        </div>


        <div class="col-md-9">
          <div class="tile">
            <div id="list_tables">
              <h3 class="tile-title">Horarios</h3>
            </div>
            
            
          </div>
        </div>
      </div>

       

         
    </main>
<?php adminFooter($data); ?>
    