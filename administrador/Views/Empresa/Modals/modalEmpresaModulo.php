<!-- Modal -->
<div class="modal fade" id="modalFormEmpresaModulo" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document"><!-- modal-dialog-centered -->
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <!-- Cambiar de color-->
        <h5 class="modal-title" id="titleModal">Empresa Modulos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formEmpresaModulo" name="formEmpresa" class="form-horizontal">
          <input type="hidden" id="txth_idsM" name="txth_idsM" value="">
          <p class="text-primary">Todos los campos son obligatorios.</p>
          
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_emp_razon_social">Razón Social</label>
              <input type="text" class="form-control valid validText" id="txt_emp_razon_social" name="txt_emp_razon_social" onkeyup="TextMayus(this);" required="">
            </div>
            <div class="form-group col-md-6">
              <label for="txt_emp_nombre_comercial">Nombre Comercial</label>
              <input type="text" class="form-control valid validText" id="txt_emp_nombre_comercial" name="txt_emp_nombre_comercial" onkeyup="TextMayus(this);" required="">
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