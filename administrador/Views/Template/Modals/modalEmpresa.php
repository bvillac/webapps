<!-- Modal -->
<div class="modal fade" id="modalFormEmpresa" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document"><!-- modal-dialog-centered -->
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <!-- Cambiar de color-->
        <h5 class="modal-title" id="titleModal">Nueva Empresa</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formEmpresa" name="formEmpresa" class="form-horizontal">
          <input type="hidden" id="txth_ids" name="txth_ids" value="">
          <p class="text-primary">Todos los campos son obligatorios.</p>
          <div class="form-row">
            
            <div class="form-group col-md-6">
              <label for="txt_emp_ruc">Ruc</label>
              <input type="text" class="form-control valid validarNumber " id="txt_emp_ruc" name="txt_emp_ruc"  required="">
            </div>
            <div class="form-group col-md-6">
              
            </div>

          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_emp_razon_social">Razón Social</label>
              <input type="text" class="form-control valid validText" id="txt_emp_razon_social" name="txt_emp_razon_social" onkeyup="TextMayus(this);" required="" >
            </div>
            <div class="form-group col-md-6">
              <label for="txt_emp_nombre_comercial">Nombre Comercial</label>
              <input type="text" class="form-control valid validText" id="txt_emp_nombre_comercial" name="txt_emp_nombre_comercial" onkeyup="TextMayus(this);" required="" >
            </div>

          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_emp_direccion">Dirección de Empresa</label>
              <input type="text" class="form-control valid validText" id="txt_emp_direccion" name="txt_emp_direccion" onkeyup="TextMayus(this);"  required="" >
            </div>
            <div class="form-group col-md-6">
              <label for="txt_emp_correo">Correo Electrónico</label>
              <input type="text" class="form-control valid validarEmail" id="txt_emp_correo" name="txt_emp_correo" placeholder="ejemplo@gmail.com"  required="" >
            </div>

          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_emp_ruta_logo">Ruta Logo</label>
              <input type="text" class="form-control valid validText" id="txt_emp_ruta_logo" name="txt_emp_ruta_logo"  required="" >
            </div>
            <div class="form-group col-md-6">
              <label for="cmb_moneda">Moneda</label>
              <select class="form-control" data-live-search="true" id="cmb_moneda" name="cmb_moneda"  required="" >
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
            <div class="form-group col-md-6">
             
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
<div class="modal fade" id="modalViewEmpresa" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" >
    <div class="modal-content">
      <div class="modal-header header-primary">
        <h5 class="modal-title" id="titleModal">Empresa</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody>

            <tr>
              <td>Moneda:</td>
              <td id="lbl_moneda"></td>
            </tr>

            <tr>
              <td>Ruc:</td>
              <td id="lbl_ruc"></td>
            </tr>

            <tr>
              <td>Razón Social:</td>
              <td id="lbl_razon"></td>
            </tr>

            <tr>
              <td>Nombre Comercial:</td>
              <td id="lbl_nombre"></td>
            </tr>

            <tr>
              <td>Dirección:</td>
              <td id="lbl_direccion"></td>
            </tr>

            <tr>
              <td>Correo:</td>
              <td id="lbl_correo"></td>
            </tr>

            <tr>
              <td>Ruta Logo:</td>
              <td id="lbl_logo"></td>
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

