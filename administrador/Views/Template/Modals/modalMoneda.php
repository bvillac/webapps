<!-- Modal -->
<div class="modal fade" id="modalFormMoneda" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document"><!-- modal-dialog-centered -->
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <!-- Cambiar de color-->
        <h5 class="modal-title" id="titleModal">Nueva Moneda</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formMoneda" name="formMoneda" class="form-horizontal">
          <input type="hidden" id="txth_ids" name="txth_ids" value="">
          <p class="text-primary">Todos los campos son obligatorios.</p>
         
          <div class="form-row">
          <div class="form-group col-md-6">
              <label for="txt_mon_simbolo">Símbolo de Moneda</label>
              <input type="text" class="form-control valid validText" id="txt_mon_simbolo" name="txt_mon_simbolo"  required="" >
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_mon_nombre">Nombre de Moneda</label>
              <input type="text" class="form-control valid validText" id="txt_mon_nombre" name="txt_mon_nombre" onkeyup="TextMayus(this);"  required="" >
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_mon_unidad_cambio">Unidad de Cambio</label>
              <input type="float" class="form-control valid validText " id="txt_mon_unidad_cambio" name="txt_mon_unidad_cambio"  required="">
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
<div class="modal fade" id="modalViewMoneda" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" >
    <div class="modal-content">
      <div class="modal-header header-primary">
        <h5 class="modal-title" id="titleModal">Moneda</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <td>Símbolo de Moneda:</td>
              <td id="lbl_sim_moneda"></td>
            </tr>
            <tr>
              <td>Nombre de Moneda:</td>
              <td id="lbl_nom_moneda"></td>
            </tr>
            <tr>
              <td>Unidad de cambio:</td>
              <td id="lbl_unicam_moneda"></td>
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

