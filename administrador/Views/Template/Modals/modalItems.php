<!-- Modal -->
<div class="modal fade" id="modalFormProductos" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title" id="titleModal">Nuevo Producto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formProductos" name="formProductos" class="form-horizontal">
          <input type="hidden" id="idProducto" name="idProducto" value="">
          <input type="hidden" id="txth_stock" name="txth_stock" value="0">
          <p class="text-primary">Los campos con asterisco (<span class="required">*</span>) son obligatorios.</p>
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label class="control-label">Nombre Producto <span class="required">*</span></label>
                <input class="form-control" id="txtNombre" name="txtNombre" type="text" onkeyup="TextMayus(this);" required="">
              </div>
              <ul class="nav nav-tabs">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#info">Información</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#precios">Precios</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#detalle">Detalle</a></li>
                <!-- <li class="nav-item"><a class="nav-link disabled" href="#detalle">Detalle</a></li> -->
              </ul>
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade active show" id="info">
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label for="cmb_linea">Línea <span class="required">*</span></label>
                      <select class="form-control" data-live-search="true" id="cmb_linea" name="cmb_linea" required=""></select>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="cmb_tipo">Tipo <span class="required">*</span></label>
                      <select class="form-control" data-live-search="true" id="cmb_tipo" name="cmb_tipo" required=""></select>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label for="cmb_marca">Marca <span class="required">*</span></label>
                      <select class="form-control" data-live-search="true" id="cmb_marca" name="cmb_marca" required=""></select>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="cmb_medida">Unidad Medida <span class="required">*</span></label>
                      <select class="form-control" data-live-search="true" id="cmb_medida" name="cmb_medida" required=""></select>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-12">
                      <label for="txtPercha">Nombre Percha <span class="required">*</span></label>
                      <input class="form-control" id="txtPercha" name="txtPercha" type="text" onkeyup="TextMayus(this);" required="">
                    </div>                    
                  </div>
                  <div class="row">
                    <div class="form-group col-md-12">
                      <label for="txtUbicacion">Ubicación <span class="required">*</span></label>
                      <input class="form-control" id="txtUbicacion" name="txtUbicacion" type="text" onkeyup="TextMayus(this);" required="">
                    </div>                    
                  </div>
                  
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label for="cmb_iva">Grava IVA <span class="required">*</span></label>
                      <select class="form-control selectpicker" id="cmb_iva" name="cmb_iva" required="">
                        <option value="0">No</option>
                        <option value="1">Si</option>
                      </select>
                    </div>                   
                    <div class="form-group col-md-6">
                      <label for="cmb_estado">Estado <span class="required">*</span></label>
                      <select class="form-control selectpicker" id="cmb_estado" name="cmb_estado" required="">
                        <option value="1">Activo</option>
                        <option value="2">Inactivo</option>
                      </select>
                    </div>                    
                  </div>
                  <!-- <div class="row">
                    <div class="form-group col-md-6">
                      <label for="cmb_bodega">Bodega <span class="required">*</span></label>
                      <select class="form-control" data-live-search="true" id="cmb_bodega" name="cmb_bodega" required=""></select>
                    </div>                   
                    <div class="form-group col-md-6">                      
                    </div>                    
                  </div> -->
                </div>
                <div class="tab-pane fade" id="precios">
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label for="txtLista">Precio Compra<span class="required">*</span></label>
                      <input class="form-control" id="txtLista" value="0" name="txtLista" type="text" required="">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="txtPromedio">Precio Promedio<span class="required">*</span></label>
                      <input class="form-control" id="txtPromedio" value="0" name="txtPromedio" type="text" required="">
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-4">
                      <label for="txtPorCostos">Porcentaje Costos<span class="required">*</span></label>
                      <input class="form-control" id="txtPorCostos" value="10.00" name="txtPorCostos" type="text" required="" >
                    </div>
                    <div class="form-group col-md-8">
                      <label for="txtCostos">Precio Costos<span class="required">*</span></label>
                      <input class="form-control" id="txtCostos" value="0" name="txtCostos" type="text" required="">
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-4">
                      <label for="txtPor1">1 Ganacia %<span class="required">*</span></label>
                      <input class="form-control" id="txtPor1" value="10.00" name="txtPor1" type="text" required="">
                    </div>
                    <div class="form-group col-md-8">
                      <label for="txtPrecio1">Precio 1<span class="required">*</span></label>
                      <input class="form-control" id="txtPrecio1" value="0" name="txtPrecio1" type="text" required="">
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-4">
                      <label for="txtPor2">2 Ganacia %<span class="required">*</span></label>
                      <input class="form-control" id="txtPor2" name="txtPor2" value="20.00" type="text" required="">
                    </div>
                    <div class="form-group col-md-8">
                      <label for="txtPrecio2">Precio 2<span class="required">*</span></label>
                      <input class="form-control" id="txtPrecio2" name="txtPrecio2" value="0" type="text" required="">
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-4">
                      <label for="txtPor3">3 Ganacia %<span class="required">*</span></label>
                      <input class="form-control" id="txtPor3" name="txtPor3" value="30.00" type="text" required="">
                    </div>
                    <div class="form-group col-md-8">
                      <label for="txtPrecio3">Precio 3<span class="required">*</span></label>
                      <input class="form-control" id="txtPrecio3" name="txtPrecio3" value="0" type="text" required="">
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-4">
                      <label for="txtPor4">4 Ganacia %<span class="required">*</span></label>
                      <input class="form-control" id="txtPor4" name="txtPor4" value="40.00" type="text" required="">
                    </div>
                    <div class="form-group col-md-8">
                      <label for="txtPrecio4">Precio Venta<span class="required">*</span></label>
                      <input class="form-control" id="txtPrecio4" name="txtPrecio4" value="0" type="text" required="">
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label for="txtMax">Existencia Máxima<span class="required">*</span></label>
                      <input class="form-control" id="txtMax" name="txtMax" value="0" type="text" required="">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="txtMin">Existencia MÍnima<span class="required">*</span></label>
                      <input class="form-control" id="txtMin" name="txtMin" value="0" type="text" required="">
                    </div>
                  </div>
                  
                </div>
                <div class="tab-pane fade" id="detalle">
                  <div class="col-md-12">
                    <div class="form-group">
                      <br>
                      <label class="control-label">Descripción Producto</label>
                      <textarea class="form-control" id="txtDescripcion" name="txtDescripcion" onkeyup="TextMayus(this);"></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label">Código <span class="required">*</span></label>
                <input class="form-control" id="txtCodigo" name="txtCodigo" type="text" placeholder="Código de barra" onkeyup="TextMayus(this);"  required="">
                <br>
                <div id="divBarCode" class="notblock textcenter">
                  <div id="printCode">
                    <svg id="barcode"></svg>
                  </div>
                  <button class="btn btn-success btn-sm" type="button" onClick="fntPrintBarcode('#printCode')"><i class="fa fa-print"></i> Imprimir</button>
                </div>
              </div>
              <div class="form-group">
                <button id="btnActionForm" class="btn btn-primary " type="submit"><i class="fa fa-fw fa-lg fa fa-check-circle-o"></i><span id="btnText">Guardar</span></button>
                <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle-o"></i>Cerrar</button>
              </div>
 
            </div>
          </div>
          <div class="tile-footer">
            <div class="form-group col-md-12">
              <div id="containerGallery">
                <span>Agregar foto (522 x 522) (.JPG) </span>
                <button class="btnAddImage btn btn-info btn-sm" type="button">
                  <i class="fa fa-plus"></i>
                </button>
              </div>
              <hr>
              <div id="containerImages">
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalViewProducto" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header header-primary">
        <h5 class="modal-title" id="titleModal">Datos del Producto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <td>Código:</td>
              <td id="celCodigo"></td>
            </tr>
            <tr>
              <td>Nombres:</td>
              <td id="celNombre"></td>
            </tr>
            <tr>
              <td>Precio:</td>
              <td id="celPrecio"></td>
            </tr>
            <tr>
              <td>Stock:</td>
              <td id="celStock"></td>
            </tr>
            <!-- <tr>
              <td>Categoría:</td>
              <td id="celCategoria"></td>
            </tr> -->
            <tr>
              <td>Estado:</td>
              <td id="celStatus"></td>
            </tr>
            <tr>
              <td>Descripción:</td>
              <td id="celDescripcion"></td>
            </tr>
            <tr>
              <td>Fotos:</td>
              <td id="celFotos">
              </td>
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