<!-- Modal -->
<div class="modal fade" id="modalFormPerfil" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header headerUpdate">
        <h5 class="modal-title" id="titleModal">Actualizar Datos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <form id="formPerfil" name="formPerfil" class="form-horizontal">
              <p class="text-primary">Los campos con asterisco (<span class="required">*</span>) son obligatorios.</p>
             
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="txtNombre">Nombres <span class="required">*</span></label>
                  <input type="text" class="form-control valid validarTexto" id="txt_nombre" name="txt_nombre" value="<?= $_SESSION['usuarioData']['per_nombre']; ?>" required="">
                </div>
                <div class="form-group col-md-6">
                  <label for="txtApellido">Apellidos <span class="required">*</span></label>
                  <input type="text" class="form-control valid validarTexto" id="txt_apellido" name="txt_apellido" value="<?= $_SESSION['usuarioData']['per_apellido']; ?>" required="">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="txtApellido">Dirección <span class="required">*</span></label>
                  <input type="text" class="form-control " id="txt_direccion" name="txt_direccion" value="<?= $_SESSION['usuarioData']['Direccion']; ?>" required="">
                </div>
                <div class="form-group col-md-6">
                  <label for="txtTelefono">Teléfono <span class="required">*</span></label>
                  <input type="text" class="form-control valid validNumber" id="txt_Telefono" name="txt_Telefono" value="<?= $_SESSION['usuarioData']['Telefono']; ?>" required="" ">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="txtTelefono">Alias <span class="required">*</span></label>
                  <input type="text" class="form-control  " id="txt_alias" name="txt_alias" value="<?= $_SESSION['usuarioData']['Alias']; ?>" required="" ">
                </div>
                <div class="form-group col-md-6">
                  <label for="txtEmail">Email</label>
                  <input type="email" class="form-control valid validEmail" id="txtEmail" name="txtEmail" value="<?= $_SESSION['usuarioData']['usu_correo']; ?>" required="" readonly disabled >
                </div>
              </div>
             <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="txtPassword">Clave</label>
                  <input type="password" class="form-control" id="txtPassword" name="txtPassword" >
                </div>
                <div class="form-group col-md-6">
                  <label for="txtPasswordConfirm">Confirmar Clave</label>
                  <input type="password" class="form-control" id="txtPasswordConfirm" name="txtPasswordConfirm" >
                </div>
             </div>
              <div class="tile-footer">
                <button id="btnActionForm" class="btn btn-info" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Actualizar</span></button>&nbsp;&nbsp;&nbsp;
                <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar</button>
              </div>
            </form>
      </div>
    </div>
  </div>
</div>