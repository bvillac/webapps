<!-- Modal -->
<div class="modal fade" id="modalFormClonar" tabindex="-1" role="dialog" aria-hidden="true">
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
        <form id="formClonar" name="formClonar" class="form-horizontal">
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
                  $seleted = 0; //($opcion['Ids']==$data['CentroId'])?'selected':'';
                  echo '<option value="' . $opcion['Ids'] . '" ' . $seleted . ' >' . $opcion['Nombre'] . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group col-md-6">
              
            </div>


          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="dtp_fecha_desde">Fecha Desde</label>
              <input type="date" class="form-control " id="dtp_fecha_desde" name="dtp_fecha_desde" 
                placeholder="1988-01-01" pattern="^\d{4}\/\d{2}\/\d{2}$" required=""
                value="<?= $data['tpla_fecha_incio'] ?>">  
            </div>
            <div class="form-group col-md-6">
            <label for="dtp_fecha_hasta">Fecha Hasta</label>
            <input type="date" class="form-control " id="dtp_fecha_hasta" name="dtp_fecha_hasta" 
              placeholder="1988-01-01" pattern="^\d{4}\/\d{2}\/\d{2}$" required=""
              value="<?= $data['tpla_fecha_fin'] ?>">  
            </div>
          </div>
       

          <div class="tile-footer">
            <button id="cmd_clonar" class="btn btn-primary" type="button"><i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Clonar</span></button>&nbsp;&nbsp;&nbsp;
            <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>