<!-- Modal -->
<div class="modal fade" id="modalFormSalon" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document"><!-- modal-dialog-centered -->
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <!-- Cambiar de color-->
        <h5 class="modal-title" id="titleModal">Cambiar Salón</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formSalon" name="formSalon" class="form-horizontal">
          <input type="hidden" id="txth_ids" name="txth_ids" value="">
          <p class="text-primary">Todos los campos son obligatorios.</p>


          

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_cupoMinimo">Cupo Minimo</label>
              <input type="text" class="form-control valid validarNumber" value="0" maxlength="2" id="txt_cupoMinimo" name="txt_cupoMinimo" onkeypress="return controlTagEvent(event);" required="">
            </div>
            <div class="form-group col-md-6">
              <label for="txt_cupoMaximo">Cupo Máximo</label>
              <input type="text" class="form-control valid validarNumber" value="0" maxlength="2" id="txt_cupoMaximo" name="txt_cupoMaximo" onkeypress="return controlTagEvent(event);" required="">
            </div>
          </div>
        

          <div class="tile-footer">
            <button id="cmd_guardar" class="btn btn-primary" type="button"><i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Guardar</span></button>&nbsp;&nbsp;&nbsp;
            <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>




