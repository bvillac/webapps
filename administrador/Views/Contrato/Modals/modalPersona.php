﻿<!-- Modal -->
<div class="modal fade" id="modalFormPersona" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document"><!-- modal-dialog-centered -->
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <!-- Cambiar de color-->
        <h5 class="modal-title" id="titleModal">Nueva Persona</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formPersona" name="formPersona" class="form-horizontal">
          <!--<input type="hidden" id="txth_ids" name="txth_ids" value="">-->
          <p class="text-primary">Todos los campos son obligatorios.</p>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_per_cedula">DNI</label>
              <input type="text" class="form-control valid validText" maxlength="15" id="txt_per_cedula" name="txt_per_cedula" required="" >
            </div>
            <div class="form-group col-md-6">
              <label for="dtp_fecha_nacimiento">Fecha de Nacimiento</label>
              <input type="date" class="form-control valid validText" id="dtp_fecha_nacimiento" name="dtp_fecha_nacimiento" placeholder="1988-01-01" pattern="^\d{4}\/\d{2}\/\d{2}$" required="">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_per_nombre">Nombre</label>
              <input type="text" class="form-control valid validText " maxlength="100" id="txt_per_nombre" name="txt_per_nombre" onkeyup="TextMayus(this);" required="">
            </div>
            <div class="form-group col-md-6">
              <label for="txt_per_apellido">Apellido</label>
              <input type="text" class="form-control valid validText" maxlength="100" id="txt_per_apellido" name="txt_per_apellido" onkeyup="TextMayus(this);" required="">
            </div>

          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_per_telefono">Teléfono/Celular</label>
              <input type="text" class="form-control valid validText" maxlength="20" id="txt_per_telefono" name="txt_per_telefono" required="">
            </div>
            <div class="form-group col-md-6">
              <label for="txt_per_direccion">Dirección Domiciliaria</label>
              <input type="text" class="form-control valid validText" maxlength="100" id="txt_per_direccion" name="txt_per_direccion" onkeyup="TextMayus(this);" required="">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_per_genero">Género</label>
              <select class="form-control" id="txt_per_genero" name="txt_per_genero" required="">
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              
            </div>
          </div>




          <div class="tile-footer">
            <button id="cmd_guardarPersona" class="btn btn-success" type="button" onclick="guardarPersona();"><i class="fa fa-fw fa-lg fa-check-circle" aria-hidden="true"></i> Agregar</button>
            <button id="cmd_retornarPersona" class="btn btn-danger" type="button" data-bs-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle" aria-hidden="true"></i> Retornar</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>