<!-- Modal -->
<div class="modal fade" id="modalFormSalon" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document"><!-- modal-dialog-centered -->
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <!-- Cambiar de color-->
        <h5 class="modal-title" id="titleModal">Nuevo Salón</h5>
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
            <label for="cmb_CentroAtencion">Centro Atención</label>
                            <select class="form-control" data-live-search="true" id="cmb_CentroAtencion" name="cmb_CentroAtencion" required="">
                                <?php
                                // Recorre el array y genera las opciones del select
                                echo '<option value="0">SELECCIONAR</option>';
                                foreach ($data['centroAtencion'] as $opcion) {
                                    $seleted=0;//($opcion['Ids']==$data['CentroId'])?'selected':'';
                                    echo '<option value="' . $opcion['Ids'] . '" '.$seleted.' >' . $opcion['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
            </div>
            <div class="form-group col-md-6">
              <label for="txt_nombreSalon">Nombre Salon</label>
              <input type="text" class="form-control valid validText " maxlength="100" id="txt_nombreSalon" name="txt_nombreSalon" onkeyup="TextMayus(this);" required="">
            </div>


          </div>

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
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_color">Color Salón</label>
              <input type="color" class="form-control"  id="txt_color" name="txt_color" >
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
            <button id="cmd_guardar" class="btn btn-primary" type="button"><i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Guardar</span></button>&nbsp;&nbsp;&nbsp;
            <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>



<!-- Modal View -->
<div class="modal fade" id="modalViewSalon" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" >
    <div class="modal-content">
      <div class="modal-header header-primary">
        <h5 class="modal-title" id="titleModal">Salón</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <td>Centro Atención:</td>
              <td id="lbl_centro"></td>
            </tr>
            <tr>
              <td>Nombre:</td>
              <td id="lbl_nombre"></td>
            </tr>
            <tr>
              <td>Centro Cupo Minimo:</td>
              <td id="lbl_cupominimo"></td>
            </tr>
            <tr>
              <td>Cupo Máximo:</td>
              <td id="lbl_cupomaximo"></td>
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

