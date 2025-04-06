<!-- Modal -->
<div class="modal fade" id="modalFormTienda" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document"><!-- modal-dialog-centered -->
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <!-- Cambiar de color-->
        <h5 class="modal-title" id="titleModal">Nuevo Usuario Tienda</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formTienda" name="formTienda" class="form-horizontal">
          <input type="hidden" id="txth_ids" name="txth_ids" value="">
          <input type="hidden" id="txth_UsuId" name="txth_UsuId" value="">
          <p class="text-primary">Todos los campos son obligatorios.</p>


          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="cmb_Cliente">Cliente</label>
              <select class="form-control" data-live-search="true" id="cmb_Cliente" name="cmb_Cliente" required="">
                <?php
                // Recorre el array y genera las opciones del select
                echo '<option value="0">SELECCIONAR</option>';
                foreach ($data['cliente'] as $opcion) {
                  $seleted = 0;//($opcion['Ids']==$data['CentroId'])?'selected':'';
                  echo '<option value="' . $opcion['Ids'] . '" ' . $seleted . ' >' . $opcion['Nombre'] . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="cmb_rol">Rol Asignado</label>
              <select class="form-control" data-live-search="true" id="cmb_rol" name="cmb_rol" required="">
                <?php
                // Recorre el array y genera las opciones del select
                echo '<option value="0">SELECCIONAR</option>';
                foreach ($data['roles'] as $opcion) {
                  echo '<option value="' . $opcion['Ids'] . '">' . $opcion['Nombre'] . '</option>';
                }
                ?>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="cmb_tienda">Tienda</label>
              <select class="form-control selectpicker" data-live-search="true" id="cmb_tienda" name="cmb_tienda" 
                    data-none-selected-text="SELECCIONAR TIENDA" title="SELECCIONAR TIENDA" required="">
                <?php
                // Recorre el array y genera las opciones del select
                echo '<option value="0">SELECCIONAR</option>';
                ?>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label class="control-label">Buscar Usuario</label>
              <div class="input-group">
                <input class="form-control" id="txt_buscarUser" name="txt_buscarUser" type="text" required=""
                  placeholder="Buscar Usuario">
                <!--<button id="cmd_buscarDatos" class="btn btn-primary" onclick="openModalBuscarPersona();"
                                type="button"><i class=" fa fa-search-plus"></i> Buscar</button>-->
              </div>

            </div>
          </div>
          <!--<div class="form-row">
            <div class="form-group col-md-3">
              <button type="button" class="btn btn-dark" id="btnAgregar"><i
                  class="fa fa-magnifying-glass"></i>Agregar</button>
            </div>
            <div class="form-group col-md-9">
               Contenedor donde aparecerán las opciones seleccionadas 
              <div id="selected-tags" class="tag-container"></div>

               Campo oculto para enviar los valores al backend 
              <input type="hidden" name="selectedValues" id="selectedValues">


            </div>
          </div>-->





          <div class="tile-footer">
            <button id="cmd_guardar" class="btn btn-primary" type="button"><i
                class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Guardar</span></button>&nbsp;&nbsp;&nbsp;
            <button class="btn btn-danger" type="button" data-bs-dismiss="modal"><i
                class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>