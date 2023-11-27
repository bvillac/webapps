<!-- Modal -->
<div class="modal fade" id="modalFormAgenda" tabindex="-1" role="dialog" aria-hidden="true">
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
        <form id="formAgenda" name="formAgenda" class="form-horizontal">
          <input type="hidden" id="txth_idsModal" name="txth_idsModal" value="">
          <input type="hidden" id="txth_idsInstru" name="txth_idsInstru" value="">
          <input type="hidden" id="txth_hora" name="txth_hora" value="">
          <input type="hidden" id="txth_salon" name="txth_salon" value="">
          <input type="hidden" id="txth_diaLetra" name="txth_diaLetra" value="">
          <!--<p class="text-primary">Todos los campos son obligatorios.</p>-->



          <div class="form-row">
            <div class="form-group col-md-12">              
              <h4 class="line-head" id="lbl_Beneficiario"></h4>
            </div>

            <div class="col-sm">
              <div class="form-group col-md-12">
                <label for="cmb_nivel">Nivel/Libro</label>
                <select class="form-control" id="cmb_nivel" name="cmb_nivel">
                  <?php
                  echo '<option value="0">SELECCIONAR</option>';
                  foreach ($data['dataNivel'] as $opcion) {
                    echo '<option value="' . $opcion['Ids'] . '" >' . $opcion['Nombre'] . '</option>';
                  }
                  ?>
                </select>
              </div>
              
              <div class="form-group col-md-12">
                <label for="cmb_NumeroNivel">Unidad</label>
                <select class="form-control" id="cmb_NumeroNivel" name="cmb_NumeroNivel">
              
                </select>
              </div>
              
              <div class="form-group col-md-12">
                <label for="cmb_actividad">Actividad</label>
                <select class="form-control" id="cmb_actividad" name="cmb_actividad">
                  <?php
                  echo '<option value="0">SELECCIONAR</option>';
                  foreach ($data['dataActividad'] as $opcion) {
                    echo '<option value="' . $opcion['Ids'] . '" >' . $opcion['Nombre'] . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>

            <div class="col-sm">
              <h5 class="line-head" >Reservados</h5>
              <ul class="list-group" id="list_beneficiarios">
               
                
              </ul>
            </div>

          </div>

          <div class="tile-footer">
            <button id="btn_reservar" class="btn btn-primary" type="button"><i
                class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText"></span></button>&nbsp;&nbsp;&nbsp;
            <button class="btn btn-danger" type="button" data-dismiss="modal"><i
                class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>