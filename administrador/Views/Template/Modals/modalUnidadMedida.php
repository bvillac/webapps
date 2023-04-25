<!-- Modal -->
<div class="modal fade" id="modalFormUnidadMedida" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document"><!-- modal-dialog-centered -->
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <!-- Cambiar de color-->
        <h5 class="modal-title" id="titleModal">Nueva Unidad de Medida</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formUnidadMedida" name="formUnidadMedida" class="form-horizontal">
          <input type="hidden" id="txth_ids" name="txth_ids" value="">
          <p class="text-primary">Todos los campos son obligatorios.</p>
         
          <div class="form-row">
          <div class="form-group col-md-6">
              <label for="txt_umed_nombre">Nombre de Unidad de Medida</label>
              <input type="text" class="form-control valid validText" id="txt_umed_nombre" name="txt_umed_nombre" onkeyup="TextMayus(this);"  required="" >
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_umed_nomenclatura">Nomenclatura</label>
              <input type="text" class="form-control valid validText" id="txt_umed_nomenclatura" name="txt_umed_nomenclatura" onkeyup="TextMayus(this);" required="" >
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_umed_factor_conversion">Factor de Conversión</label>
              <input type="float" class="form-control valid validText " id="txt_umed_factor_conversion" name="txt_umed_factor_conversion"  required="">
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
<div class="modal fade" id="modalViewUnidadMedida" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" >
    <div class="modal-content">
      <div class="modal-header header-primary">
        <h5 class="modal-title" id="titleModal">Unidad de Medida</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <td>Nombre Unidad de Medida:</td>
              <td id="lbl_nom_umed"></td>
            </tr>
            <tr>
              <td>Nomenclatura:</td>
              <td id="lbl_nomen_umed"></td>
            </tr>
            <tr>
              <td>Factor de Conversión:</td>
              <td id="lbl_faconv_umed"></td>
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

