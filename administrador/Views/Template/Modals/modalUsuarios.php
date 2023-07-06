<!-- Modal -->
<div class="modal fade" id="modalFormUsu" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document"><!-- modal-dialog-centered -->
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <!-- Cambiar de color-->
        <h5 class="modal-title" id="titleModal">Nuevo Registro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formUsu" name="formUsu" class="form-horizontal">
          <input type="hidden" id="txth_ids" name="txth_ids" value="">
          <input type="hidden" id="txth_perids" name="txth_perids" value="">
          <input type="hidden" id="txth_eusuids" name="txth_eusuids" value="">
          <p class="text-primary">Todos los campos son obligatorios.</p>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_dni">Identificación Dni</label>
              <input type="text" class="form-control valid validarNumber " id="txt_dni" name="txt_dni" required="">
            </div>
            <div class="form-group col-md-6">
              <label for="txt_fec_nacimiento">Fecha Nacimiento</label>
              <div class="dflex">
                <input class="date-picker form-control valid" id="dtp_fecha_nacimiento" name="dtp_fecha_nacimiento" placeholder="yyyy-mm-dd">

              </div>
              <!--<input type="text" class="form-control valid validText" id="txt_fec_nacimiento" name="txt_fec_nacimiento" placeholder="1988/01/01" pattern="^\d{4}\/\d{2}\/\d{2}$" required="" >-->
            </div>

          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_nombre">Nombre</label>
              <input type="text" class="form-control valid validarTexto" id="txt_nombre" name="txt_nombre" onkeyup="TextMayus(this);" required="">
            </div>
            <div class="form-group col-md-6">
              <label for="txt_apellido">Apellido</label>
              <input type="text" class="form-control valid validarTexto " id="txt_apellido" name="txt_apellido" onkeyup="TextMayus(this);" required="">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_telefono">Teléfono/Celular</label>
              <input type="text" maxlength="10" class="form-control valid validarNumber" id="txt_telefono" name="txt_telefono" placeholder="0999999999" required="">
            </div>
            <div class="form-group col-md-6">
              <label for="txt_direccion">Dirección Domiciliaria</label>
              <input type="text" class="form-control " id="txt_direccion" name="txt_direccion" onkeyup="TextMayus(this);" required="">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_alias">Alias</label>
              <input type="text" class="form-control valid validarTexto" id="txt_alias" name="txt_alias" onkeyup="TextMayus(this);" required="">
            </div>
            <div class="form-group col-md-6">
              <label for="cmb_genero">Género</label>
              <select class="form-control" id="cmb_genero" name="cmb_genero" required="">
                <option value="F">Femenino</option>
                <option value="M">Masculino</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_correo">Usuario/Correo Electrónico</label>
              <input type="email" class="form-control valid validarEmail" id="txt_correo" name="txt_correo" placeholder="ejemplo@gmail.com" required="">
            </div>
            <div class="form-group col-md-6">
              <label for="txt_Password">Clave</label>
              <input type="password" class="form-control" id="txt_Password" name="txt_Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="La contraseña debe contener 8 o más caracteres que son de por lo menos un número, una mayúscula y minúscula" placeholder="Abcdef123">
              <span class="mdi mdi-eye" id="mostrar"> <span class="pwdtxt" style="cursor:pointer;">Mostrar contraseña</span></span>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="cmb_rol">Rol Asignado</label>
              <select class="form-control" data-live-search="true" id="cmb_rol" name="cmb_rol" required="">
                <?php
                // Recorre el array y genera las opciones del select
                echo '<option value="0">SELECCIONAR</option>';
                foreach ($data['usuario_rol'] as $opcion) {
                  echo '<option value="' . $opcion['Ids'] . '">' . $opcion['Nombre'] . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="cmb_estado">Estado</label>
              <select class="form-control" id="cmb_estado" name="cmb_estado" required="">
                <option value="1">Activo</option>
                <option value="2">Inactivo</option>
              </select>
            </div>

          </div>


          <div class="tile-footer">
            <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Guardar</span></button>&nbsp;&nbsp;&nbsp;
            <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>


<!-- Modal View -->
<div class="modal fade" id="modalViewUsu" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header header-primary">
        <h5 class="modal-title" id="titleModal">Usuarios</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <td>Identificación Dni:</td>
              <td id="lbl_dni"></td>
            </tr>
            <tr>
              <td>Nombres:</td>
              <td id="lbl_nombres"></td>
            </tr>
            <tr>
              <td>Teléfono/Celular:</td>
              <td id="lbl_telefono"></td>
            </tr>
            <tr>
              <td>Dirección Domiciliaria:</td>
              <td id="lbl_direccion"></td>
            </tr>

            <tr>
              <td>Alias:</td>
              <td id="lbl_alias"></td>
            </tr>
            <tr>
              <td>Usuario:</td>
              <td id="lbl_usuario"></td>
            </tr>
            <tr>
              <td>Género:</td>
              <td id="lbl_genero"></td>
            </tr>
            <tr>
              <td>Rol Sistema:</td>
              <td id="lbl_rol"></td>
            </tr>
            <tr>
              <td>Estado:</td>
              <td id="lbl_estado"></td>
            </tr>
            <tr>
              <td>Fecha Ingreso:</td>
              <td id="lbl_fecIng"></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>