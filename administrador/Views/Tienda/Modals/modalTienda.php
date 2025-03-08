<!-- Modal -->
<div class="modal fade" id="modalFormTienda" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document"><!-- modal-dialog-centered -->
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <!-- Cambiar de color-->
        <h5 class="modal-title" id="titleModal">Nuevo Tienda</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formTienda" name="formTienda" class="form-horizontal">
          <input type="hidden" id="txth_ids" name="txth_ids" value="">
          <p class="text-primary">Todos los campos son obligatorios.</p>


          <div class="form-row">
            <div class="form-group col-md-6">
            <label for="cmb_Cliente">Cliente</label>
                            <select class="form-control" data-live-search="true" id="cmb_Cliente" name="cmb_Cliente" required="">
                                <?php
                                // Recorre el array y genera las opciones del select
                                echo '<option value="0">SELECCIONAR</option>';
                                foreach ($data['cliente'] as $opcion) {
                                    $seleted=0;//($opcion['Ids']==$data['CentroId'])?'selected':'';
                                    echo '<option value="' . $opcion['Ids'] . '" '.$seleted.' >' . $opcion['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
            </div>
            <div class="form-group col-md-6">
              <label for="txt_nombreTienda">Nombre Tienda</label>
              <input type="text" class="form-control valid validText " maxlength="100" id="txt_nombreTienda" name="txt_nombreTienda" onkeyup="TextMayus(this);" required="">
            </div>


          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_telefono">Teléfono/Celular</label>
              <input type="text" maxlength="10" class="form-control valid validarNumber" id="txt_telefono" name="txt_telefono" placeholder="0999999999" required="">
            </div>
            <div class="form-group col-md-6">
              <label for="txt_direccion">Dirección</label>
              <input type="text" class="form-control " id="txt_direccion" name="txt_direccion" onkeyup="TextMayus(this);" required="">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_contacto">Contacto</label>
              <input type="text" class="form-control " id="txt_contacto" name="txt_contacto" onkeyup="TextMayus(this);" required="">
            </div>
            <div class="form-group col-md-6">
              <label for="txt_lugar">Lugar Entrega</label>
              <input type="text" class="form-control " id="txt_lugar" name="txt_lugar" onkeyup="TextMayus(this);" required="">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_diainicio">Día Inicio</label>
              <input type="text" class="form-control valid validarNumber" value="0" maxlength="2" id="txt_diainicio" name="txt_diainicio" onkeypress="return controlTagEvent(event);" required="">
            </div>
            <div class="form-group col-md-6">
              <label for="txt_diafin">Día Fin</label>
              <input type="text" class="form-control valid validarNumber" value="0" maxlength="2" id="txt_diafin" name="txt_diafin" onkeypress="return controlTagEvent(event);" required="">
            </div>
          </div>
          <div class="form-row">
          <div class="form-group col-md-6">
              <label for="txt_cupo">Cupo Otorgado</label>
              <input type="text" class="form-control valid validarDecimal" value="0.00" maxlength="6" id="txt_cupo" name="txt_cupo" onkeypress="return controlTagEvent(event);" required="">
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
<div class="modal fade" id="modalViewTienda" tabindex="-1" role="dialog" aria-hidden="true">
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
              <td>Cliente:</td>
              <td id="lbl_cliente"></td>
            </tr>
            <tr>
              <td>Nombre Tienda:</td>
              <td id="lbl_nombre"></td>
            </tr>
            <tr>
              <td>Teléfono:</td>
              <td id="lbl_telefono"></td>
            </tr>
            <tr>
              <td>Dirección:</td>
              <td id="lbl_direccion"></td>
            </tr>
            <tr>
              <td>Contacto:</td>
              <td id="lbl_contacto"></td>
            </tr>
            <tr>
              <td>Lugar de Entrega:</td>
              <td id="lbl_lugar"></td>
            </tr>
            <tr>
              <td>Día Inicio:</td>
              <td id="lbl_diainicio"></td>
            </tr>
            <tr>
              <td>Día Fin:</td>
              <td id="lbl_diafin"></td>
            </tr>        
            <tr>
              <td>Cupo Asignado:</td>
              <td id="lbl_cupo"></td>
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

