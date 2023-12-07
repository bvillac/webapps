<!-- Modal -->
<div class="modal fade" id="modalFormEvaluar" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document"><!-- modal-dialog-centered -->
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <!-- Cambiar de color-->
        <h5 class="modal-title" id="titleModal">Nuevo Salón</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formEvaluar" name="formEvaluar" class="form-horizontal">
          <input type="hidden" id="txth_idsControl" name="txth_idsControl" value="">
          <p class="text-primary">Todos los campos son obligatorios.</p>


          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="cmb_valoracion">Valoración</label>
              <select class="form-control" data-live-search="true" id="cmb_valoracion" name="cmb_valoracion" required="">
                <?php
                // Recorre el array y genera las opciones del select
                echo '<option value="0">SELECCIONAR</option>';
                foreach ($data['valoracion'] as $opcion) {
                  $seleted = 0; //($opcion['Ids']==$data['CentroId'])?'selected':'';
                  echo '<option value="' . $opcion['Ids'] . '" ' . $seleted . ' >' . $opcion['Nombre'] . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group col-md-12">
              <label for="cmb_porcentaje">Valor Porcentaje</label>
              <select class="form-control" data-live-search="true" id="cmb_porcentaje" name="cmb_porcentaje" required="" disabled >
                <?php
                // Recorre el array y genera las opciones del select
                echo '<option value="0">SELECCIONAR</option>';
                foreach ($data['porcentaje'] as $opcion) {
                  $seleted = 0; //($opcion['Ids']==$data['CentroId'])?'selected':'';
                  echo '<option value="' . $opcion . '" ' . $seleted . ' >' . $opcion . ' %</option>';
                }
                ?>
              </select>
            </div>

            <div class="form-group col-md-12">
              <label for="txta_comentario">Observación</label>
              <textarea class="form-control" id="txta_comentario" rows="3"></textarea>
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