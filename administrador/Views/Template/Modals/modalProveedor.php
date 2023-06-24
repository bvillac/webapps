<!-- Modal -->
<div class="modal fade" id="modalFormProveedor" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document"><!-- modal-dialog-centered -->
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <!-- Cambiar de color-->
        <h5 class="modal-title" id="titleModal">Nuevo Proveedor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formProveedor" name="formProveedor" class="form-horizontal">
        <input type="hidden" id="txth_ids" name="txth_ids" value=""> 
        <p class="text-primary">Todos los campos son obligatorios.</p>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_codigo">Código</label>
              <input type="text" class="form-control valid validText " id="txt_codigo" name="txt_codigo" placeholder="Ingrese Cédula" required="">
            </div>
            <div class="form-group col-md-6"></div>
          </div>
          <div class="form-row">
              <div class="form-group col-md-6">
                <label for="txt_pro_tipo_dni">Tipo DNI</label>
                <select class="form-control" id="txt_pro_tipo_dni" name="txt_pro_tipo_dni" required="">
                  <option value="01">Cédula</option>
                  <option value="02">Ruc</option>
                  <option value="03">Pasaporte</option>
                </select>
                </div>
                <div class="form-group col-md-6">
              <label for="txt_pro_cedula_ruc">Identificación Dni</label>
              <input type="text" class="form-control valid validarNumber " id="txt_pro_cedula_ruc" name="txt_pro_cedula_ruc" required="">
            </div>
            
          </div>
          <div class="form-row">           
            <div class="form-group col-md-6">
              <label for="txt_pro_nombre">Nombres y Apellidos</label>
              <input type="text" class="form-control valid validText" id="txt_pro_nombre" name="txt_pro_nombre" onkeyup="TextMayus(this);"  required="" >
            </div>                 
            <div class="form-group col-md-6">
              <label for="txt_pro_direccion">Dirección de Empresa</label>
              <input type="text" class="form-control valid validText " id="txt_pro_direccion" name="txt_pro_direccion" onkeyup="TextMayus(this);" required="">
            </div>
            </div>
            <div class="form-row">  
              <div class="form-group col-md-6">
                <label for="txt_pro_telefono">Teléfono/Celular</label>
                <input type="text" maxlength="10" class="form-control valid validarNumber" id="txt_pro_telefono" name="txt_pro_telefono" placeholder="0999999999" required="" >
              </div>         
              <div class="form-group col-md-6">
                <label for="txt_pro_correo">Correo Electrónico</label>
                <input type="text" class="form-control valid validarEmail " id="txt_pro_correo" name="txt_pro_correo" placeholder="ejemplo@gmail.com" required="">
              </div>  
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="cmb_pago">Forma de Pago</label>
                <select class="form-control" data-live-search="true" id="cmb_pago" name="cmb_pago" required="">
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
<div class="modal fade" id="modalViewProveedor" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" >
    <div class="modal-content">
      <div class="modal-header header-primary">
        <h5 class="modal-title" id="titleModal">Proveedores</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <td>Forma de Pago:</td>
              <td id="lbl_pago"></td>
            </tr>
            <tr>
              <td>Código:</td>
              <td id="lbl_ids"></td>
            </tr>
            <tr>
              <td>Tipo DNI:</td>
              <td id="lbl_tipo"></td>
            </tr>
            <tr>
              <td>Identificación Dni:</td>
              <td id="lbl_cedula"></td>
            </tr>
            <tr>
              <td>Nombre:</td>
              <td id="lbl_nombre"></td>
            </tr>
            <tr>
              <td>Dirección de Empresa:</td>
              <td id="lbl_direccion"></td>
            </tr>
            <tr>
              <td>Teléfono/Celular:</td>
              <td id="lbl_telefono"></td>
            </tr>
            <tr>
              <td>Correo Electrónico:</td>
              <td id="lbl_correo"></td>
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