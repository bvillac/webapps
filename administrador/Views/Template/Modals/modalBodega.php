<!-- Modal -->
<div class="modal fade" id="modalFormBodega" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document"><!-- modal-dialog-centered -->
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <!-- Cambiar de color-->
        <h5 class="modal-title" id="titleModal">Nueva Bodega</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        if($_SESSION['permisosMod']['r']){
					$btnOpciones .=' <a title="Generar PDF" href="'.base_url().'/Usuarios/generarReporteUsuarioPDF/'.$arrData[$i]['Ids'].'" target="_blanck" class="btn btn-primary btn-sm"> <i class="fa fa-file-pdf-o"></i> </a> ';
				}
      </div>
      <div class="modal-body">
        <form id="formBodega" name="formBodega" class="form-horizontal">
          <input type="hidden" id="txth_ids" name="txth_ids" value="">
          <p class="text-primary">Todos los campos son obligatorios.</p>
         
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txt_bod_nombre">Nombre de Bodega</label>
              <input type="text" class="form-control valid validText" id="txt_bod_nombre" name="txt_bod_nombre" onkeyup="TextMayus(this);" required="" >
            </div>
            <div class="form-group col-md-6">
              <label for="txt_bod_direccion">Direccion de Bodega </label>
              <input type="text" class="form-control valid validText " id="txt_bod_direccion" name="txt_bod_direccion" onkeyup="TextMayus(this);"  required="">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="txt_bod_telefono">Teléfono/Celular</label>
              <input type="text" maxlength="10" class="form-control valid validarNumber" id="txt_bod_telefono" name="txt_bod_telefono" placeholder="0999999999" required="" >
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
<div class="modal fade" id="modalViewBodega" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" >
    <div class="modal-content">
      <div class="modal-header header-primary">
        <h5 class="modal-title" id="titleModal">Bodega</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <td>Nombre de Bodega:</td>
              <td id="lbl_nom_bodega"></td>
            </tr>
            <tr>
              <td>Dirección de Bodega:</td>
              <td id="lbl_dir_bodega"></td>
            </tr>
            <tr>
              <td>Teléfono/Celular de bodega:</td>
              <td id="lbl_tel_bodega"></td>
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

