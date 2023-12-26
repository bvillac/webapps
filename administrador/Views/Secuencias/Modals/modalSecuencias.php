<!-- Modal -->
<div class="modal fade" id="modalFormSec" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document"><!-- modal-dialog-centered -->
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <!-- Cambiar de color-->
        <h5 class="modal-title" id="titleModal">Nuevo Documento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formSec" name="formSec" class="form-horizontal">
          <input type="hidden" id="txth_ids" name="txth_ids" value="">
          <p class="text-primary">Todos los campos son obligatorios.</p>
          <div class="form-row">
            
            <div class="form-group col-md-6">
              <label for="cmb_establecimiento">Establecimiento</label>
              <select class="form-control" id="cmb_establecimiento" name="cmb_establecimiento" disabled>
                <?php
                echo '<option value="0">SELECCIONAR</option>';
                foreach ($data['Establecimiento'] as $opcion) {
                  $seleted = ($opcion['Ids'] == $data['idsEmpresa']) ? 'selected' : '';
                  echo '<option value="' . $opcion['Ids'] . '" ' . $seleted . ' >' . $opcion['Nombre'] . '</option>';
                }
                ?>
              </select>
            </div>
            
            <div class="form-group col-md-6">
              <label for="cmb_punto">Punto Emisión</label>
              <select class="form-control" id="cmb_punto" name="cmb_punto">
                <?php
                foreach ($data['Punto'] as $opcion) {
                  echo '<option value="' . $opcion['Ids'] . '"  >' . $opcion['Nombre'] . '</option>';
                }
                ?>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txtTelefono">Tipo Documento</label>
              <input type="text" class="form-control valid validText" id="txt_sec_tipo" name="txt_sec_tipo" onkeyup="TextMayus(this);" maxlength="2" required="" >
            </div>
            <div class="form-group col-md-6">
              <label for="txt_sec_numero">Secuencia</label>
              <input type="text" class="form-control valid validText " id="txt_sec_numero" value="0000000000"name="txt_sec_numero" maxlength="10" required="">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="txt_sec_nombre">Nombre</label>
              <input type="text" class="form-control valid validText" id="txt_sec_nombre" name="txt_sec_nombre" onkeyup="TextMayus(this);" required="" >
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
<div class="modal fade" id="modalViewSec" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" >
    <div class="modal-content">
      <div class="modal-header header-primary">
        <h5 class="modal-title" id="titleModal">Documentos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <td>Establecimiento:</td>
              <td id="lbl_establecimiento"></td>
            </tr>
            <tr>
              <td>Punto Emisión:</td>
              <td id="lbl_puntoEmi"></td>
            </tr>
            <tr>
              <td>Tipo Documento:</td>
              <td id="lbl_tipoDoc"></td>
            </tr>
            <tr>
              <td>Secuencia:</td>
              <td id="lbl_secuencia"></td>
            </tr>
            
            <tr>
              <td>Nombre:</td>
              <td id="lbl_nombre"></td>
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

