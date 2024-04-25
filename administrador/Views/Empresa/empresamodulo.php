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
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>/<?= $data['page_back'] ?>"><?= $data['page_title'] ?></a>
      </li>
    </ul>
  </div>
  <input type="hidden" id="txth_ids" name="txth_ids" value="">

  <div class="row">
    <div class="col-md-6">
      <div class="tile">
        <div class="tile-title-w-btn">
          <div class="form-group">
            <label for="cmb_empresa">
              <h3 class="title">Empresa Módulo</h3>
            </label>
            <select class="form-control" id="cmb_empresa" name="cmb_empresa" onchange="fntEmpresaModulos(this.value)">
              <?php
              echo '<option value="0">SELECCIONAR</option>';
              foreach ($data['Empresas'] as $opcion) {
                $seleted=($opcion['Ids']==$data['Eusu_id'])?'selected':'';
                echo '<option value="' . $opcion['Ids'] . '" '.$seleted.' >' . $opcion['NombreComercial'] . '</option>';
              }
              ?>
            </select>
          </div>
        </div>
        <div class="tile-body">
          <div class="row">
            <div class="form-group col-md-12">
              <h5>Presione CTRL y haga clic para seleccionar varias opciones a la vez.</h5>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-5">
              <label for="txt_emp_ruta_logo">Módulos</label>
              <select class="form-control" multiple id="cmb_modulos" name="cmb_modulos" style="height: 300px;">
                <?php
                foreach ($data['Modulos'] as $opcion) {
                  echo '<option value="' . $opcion['Ids'] . '" >' . $opcion['Nombre'] . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group col-md-2 align-items-center" >
              <br><br><br>
              <button type="button" class="btn btn-dark" id="btn_next_all"><i class="fa fa-fast-forward"
                  aria-hidden="true">&nbsp;&nbsp;TODO</i>
              </button>
              <button type="button" class="btn btn-dark" id="btn_next_one"><i class="fa fa-step-forward"
                  aria-hidden="true">&nbsp;&nbsp;UNO</i>
              </button>
              <button type="button" class="btn btn-dark" id="btn_back_all"><i class="fa fa-fast-backward"
                  aria-hidden="true">&nbsp;&nbsp;TODO</i>
              </button>
              <button type="button" class="btn btn-dark" id="btn_back_one"><i class="fa fa-step-backward"
                  aria-hidden="true">&nbsp;&nbsp;UNO</i>
              </button>
            </div>
            <div class="form-group col-md-5">
              <label for="txt_emp_ruta_logo">Empresa Módulos</label>
              <select class="form-control" multiple id="cmb_Emp_modulos" name="cmb_Emp_modulos" style="height: 300px;">
                <?php
                foreach ($data['EmpModulo'] as $opcion) {
                  echo '<option value="' . $opcion['Ids'] . '" >' . $opcion['Nombre'] . '</option>';
                }
                ?>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="tile">
        <div class="tile-title-w-btn">
          <h3 class="title">SweetAlert</h3>
          <p><a class="btn btn-primary icon-btn" href="http://t4t5.github.io/sweetalert/" target="_blank"><i
                class="fa fa-file"></i>Docs</a></p>
        </div>
        <div class="tile-body">
          <p>This plugin can be used as the replacement of native javascript alert, confirm and prompt functions.</p>
          <h4>Demo</h4><a class="btn btn-info" id="demoSwal" href="#">Sample Alert</a>
        </div>
      </div>
    </div>
  </div>





</main>
<?php adminFooter($data); ?>