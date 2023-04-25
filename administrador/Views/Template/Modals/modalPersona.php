<!-- Modal -->
<div class="modal fade" id="modalFormPersona" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document"><!-- modal-dialog-centered -->
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <!-- Cambiar de color-->
        <h5 class="modal-title" id="titleModal">Nueva Persona</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formPersona" name="formPersona" class="form-horizontal">
          <input type="hidden" id="txth_ids" name="txth_ids" value="">
          <p class="text-primary">Todos los campos son obligatorios.</p>
         
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_per_cedula">Cédula</label>
              <input type="text" class="form-control valid validText" id="txt_per_cedula" name="txt_per_cedula"  required="" >
            </div>
            <div class="form-group col-md-6">
              <label for="txt_per_nombre">Nombre</label>
              <input type="text" class="form-control valid validText " id="txt_per_nombre" name="txt_per_nombre" onkeyup="TextMayus(this);" required="">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_per_apellido">Apellido</label>
              <input type="text" class="form-control valid validText" id="txt_per_apellido" name="txt_per_apellido" onkeyup="TextMayus(this);" required="" >
            </div>
            <div class="form-group col-md-6">
              <label for="txt_per_fecha_nacimiento">Fecha de Nacimiento</label>
              <input type="date" class="form-control valid validText" id="txt_per_fecha_nacimiento" name="txt_per_fecha_nacimiento" placeholder="1988/01/01" pattern="^\d{4}\/\d{2}\/\d{2}$" required="" >
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_per_telefono">Teléfono/Celular</label>
              <input type="number" class="form-control valid validText" id="txt_per_telefono" name="txt_per_telefono"  required="" >
            </div>
            <div class="form-group col-md-6">
              <label for="txt_per_direccion">Dirección Domiciliaria</label>
              <input type="text" class="form-control valid validText" id="txt_per_direccion" name="txt_per_direccion" onkeyup="TextMayus(this);" required="" >
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_per_genero">Género</label>
              <select class="form-control" id="txt_per_genero" name="txt_per_genero" required="">
                <option value="M">Femenino</option>
                <option value="F">Masculino</option>
              </select>
            </div>
            </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="cmb_estado">Estado</label>
              <select class="form-control" id="cmb_estado" name="cmb_estado" required="">
                <option value="1">Activo</option>
                <option value="2">Inactivo</option>
              </select>
            </div>
            <div class="form-group col-md-6"></div>
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
<div class="modal fade" id="modalViewPersona" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" >
    <div class="modal-content">
      <div class="modal-header header-primary">
        <h5 class="modal-title" id="titleModal">Persona</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <td>Cédula:</td>
              <td id="lbl_ced_persona"></td>
            </tr>
            <tr>
              <td>Nombre:</td>
              <td id="lbl_nom_persona"></td>
            </tr>
            <tr>
              <td>Apellido:</td>
              <td id="lbl_ape_persona"></td>
            </tr>
            <tr>
              <td>Fecha de Nacimiento:</td>
              <td id="lbl_fecnac_persona"></td>
            </tr>
            <tr>
              <td>Teléfono:</td>
              <td id="lbl_tel_persona"></td>
            </tr>
            <tr>
              <td>Dirección:</td>
              <td id="lbl_dir_persona"></td>
            </tr>
            <tr>
              <td>Género:</td>
              <td id="lbl_gen_persona"></td>
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

