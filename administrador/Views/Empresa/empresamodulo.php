<?php
adminHeader($data);
adminMenu($data);
//filelang(Setlanguage,"general") 
//require_once "Views/Salon/Modals/modalSalon.php";
?>

<script>
  const nEmpresa = <?= json_encode($data['Modulos']) ?>;
</script>
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
                $seleted = 0;//($opcion['Ids']==$data['Eusu_id'])?'selected':'';
                echo '<option value="' . $opcion['Ids'] . '" ' . $seleted . ' >' . $opcion['NombreComercial'] . '</option>';
              }
              ?>
            </select>
          </div>
        </div>
        <div class="tile-body">
          <div class="row">
            <div class="form-group col-md-8">
              <h5>Presione CTRL y haga clic para seleccionar varias opciones a la vez.</h5>
            </div>
            <div class="form-group col-md-2">
              <button type="button" class="btn btn-dark" id="btn_guardarModulo"><i class="fa fa-fast-forward"
                  aria-hidden="true">&nbsp;&nbsp;GUARDAR</i>
              </button>
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
            <div class="form-group col-md-2 align-items-center">
              <br><br><br>
              <!-- <button type="button" class="btn btn-dark" id="btn_next_all"><i class="fa fa-fast-forward"
                  aria-hidden="true">&nbsp;&nbsp;TODO</i>
              </button> -->
              <button type="button" class="btn btn-dark" id="btn_next_one"><i class="fa fa-step-forward"
                  aria-hidden="true">&nbsp;&nbsp;AGREGAR</i>
              </button>
              <br><br>
              <!-- <button type="button" class="btn btn-dark" id="btn_back_all"><i class="fa fa-fast-backward"
                  aria-hidden="true">&nbsp;&nbsp;TODO</i>
              </button> -->
              <button type="button" class="btn btn-dark" id="btn_back_one"><i class="fa fa-step-backward"
                  aria-hidden="true">&nbsp;&nbsp;QUITAR</i>
              </button>
            </div>
            <div class="form-group col-md-5">
              <label for="txt_emp_ruta_logo">Empresa Módulos</label>
              <select class="form-control" multiple id="cmb_Emp_modulos" name="cmb_Emp_modulos" style="height: 300px;">
                <?php
                //foreach ($data['EmpModulo'] as $opcion) {
                //  echo '<option value="' . $opcion['Ids'] . '" >' . $opcion['Nombre'] . '</option>';
                //}
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
          <div class="form-group">
            <label for="cmb_empresa">
              <h3 class="title">Empresa Rol</h3>
            </label>
            <select class="form-control" id="cmb_empresa2" name="cmb_empresa2" onchange="fntEmpresaRoles(this.value)">
              <?php
              echo '<option value="0">SELECCIONAR</option>';
              foreach ($data['Empresas'] as $opcion) {
                $seleted = 0;//($opcion['Ids']==$data['Eusu_id'])?'selected':'';
                echo '<option value="' . $opcion['Ids'] . '" ' . $seleted . ' >' . $opcion['NombreComercial'] . '</option>';
              }
              ?>
            </select>
          </div>
        </div>
        <div class="tile-body">
          <div class="row">
            <div class="form-group col-md-8">
              <h5>Presione CTRL y haga clic para seleccionar varias opciones a la vez.</h5>
            </div>
            <div class="form-group col-md-2">
              <button type="button" class="btn btn-dark" id="btn_guardarRoles"><i class="fa fa-fast-forward"
                  aria-hidden="true">&nbsp;&nbsp;GUARDAR</i>
              </button>
            </div>

          </div>
          <div class="row">
            <div class="form-group col-md-5">
              <label for="cmb_roles">Roles</label>
              <select class="form-control" multiple id="cmb_roles" name="cmb_roles" style="height: 300px;">
                <?php
                foreach ($data['Roles'] as $opcion) {
                  echo '<option value="' . $opcion['Ids'] . '" >' . $opcion['Nombre'] . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group col-md-2 align-items-center">
              <br><br><br>

              <button type="button" class="btn btn-dark" id="btn_next_one_rol"><i class="fa fa-step-forward"
                  aria-hidden="true">&nbsp;&nbsp;AGREGAR</i>
              </button>
              <br><br>

              <button type="button" class="btn btn-dark" id="btn_back_one_rol"><i class="fa fa-step-backward"
                  aria-hidden="true">&nbsp;&nbsp;QUITAR</i>
              </button>
            </div>
            <div class="form-group col-md-5">
              <label for="cmb_Emp_roles">Empresa Roles</label>
              <select class="form-control" multiple id="cmb_Emp_roles" name="cmb_Emp_roles" style="height: 300px;">

              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">

      <div class="tile">
        <label for="cmb_empresa">
          <h3 class="title">Empresa Módulo Roles</h3>
        </label>
        <div class="tile-title-w-btn">

          <div class="form-group col-md-4">

            <label for="cmb_empresa3">
              Empresa Modulo Rol
            </label>
            <select class="form-control" id="cmb_empresa3" name="cmb_empresa3"
              onchange="fntEmpresaModuloRoles(this.value)">
              <?php
              echo '<option value="0">SELECCIONAR</option>';
              foreach ($data['Empresas'] as $opcion) {
                $seleted = 0;//($opcion['Ids']==$data['Eusu_id'])?'selected':'';
                echo '<option value="' . $opcion['Ids'] . '" ' . $seleted . ' >' . $opcion['NombreComercial'] . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label for="cmb_empresa_modulo_roles">Empresa Rol </label>
            <select class="form-control" id="cmb_empresa_modulo_roles" name="cmb_empresa_modulo_roles"
              onchange="fntListarModuloporRol(this.value)">
            </select>
          </div>
          <div class="form-group col-md-4">
            <label></label>
            <button type="button" class="btn btn-dark" id="btn_guardarRoles"><i class="fa fa-fast-forward"
                aria-hidden="true">&nbsp;&nbsp;GUARDAR</i>
            </button>
          </div>
        </div>
        <div class="tile-body">
          <div class="row">
            <div class="form-group col-md-12">
              Presione CTRL y haga clic para seleccionar varias opciones a la vez.
            </div>


          </div>
          <div class="row">
            <div class="form-group col-md-5">
              <label for="list_EmpresaModuloroles">Roles</label>
              <select class="form-control" multiple id="list_EmpresaModuloroles" name="list_EmpresaModuloroles"
                style="height: 300px;">

              </select>
            </div>
            <div class="form-group col-md-2 align-items-center">
              <br><br><br>

              <button type="button" class="btn btn-dark" id="btn_next_one_rol"><i class="fa fa-step-forward"
                  aria-hidden="true">&nbsp;&nbsp;AGREGAR</i>
              </button>
              <br><br>

              <button type="button" class="btn btn-dark" id="btn_back_one_rol"><i class="fa fa-step-backward"
                  aria-hidden="true">&nbsp;&nbsp;QUITAR</i>
              </button>
            </div>
            <div class="form-group col-md-5">
              <label for="list_EmpresaModulorolesSelect">Empresa Roles</label>
              <select class="form-control" multiple id="list_EmpresaModulorolesSelect"
                name="list_EmpresaModulorolesSelect" style="height: 300px;">

              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</main>
<?php adminFooter($data); ?>