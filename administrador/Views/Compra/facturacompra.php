<?php 
      adminHeader($data);
      adminMenu($data);  
      getModal('modalProveedorBuscar',$data); 
      getModal('modalItemsBuscar',$data); 

?>
    <main class="app-content">
      <div class="app-title">
        <div>
            <h1><i class="fas fa-box"></i> <?= $data['page_title'] ?></h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="<?= base_url(); ?>/Items"><?= $data['page_title'] ?></a></li>
        </ul>
      </div>
      
      
        <div class="row">
            <div class="col-md-12">
              <div class="tile">
                <div class="tile-body">

                <form id="formProductos" name="formProductos" class="form-horizontal">
                  <input type="hidden" id="idOrden" name="idOrden" value="">
                  <p class="text-primary">Los campos con asterisco (<span class="required">*</span>) son obligatorios.</p>
                  <div class="card mb-12 text-black bg-light">
                      <div class="card-body">
                          <blockquote class="card-blockquote">
                      

                              <div class="row">
                                <div class="form-group col-md-3">                   
                                    <!-- <label for="txtCodproveedor">Código Proveedor <span class="required">*</span></label> -->
                                    <div class="input-group">
                                      <input class="form-control" id="txtCodproveedor" value="<?= $data['cabData']['pro_codigo'] ?>" name="txtCodproveedor" type="text" required="" disabled>                                 
                                      <!-- <button id="cmd_buscarProveedor" onclick="openModalProveedorBuscar();" class="btn btn-primary" type="button"><i class=" fa fa-search-plus"></i></button> -->
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                  <h5 class="text-right">N° Orden: <span id="lbl_Orden"><?= $data['cabData']['com_numero_orden'] ?></span></h5>
                                </div>   
                                <div class="form-group col-md-3">
                                  <h5 class="text-right">N° Compra: <span id="lbl_Secuencia"><?= $data['Secuencia'] ?></span></h5>
                                </div>
                                <div class="form-group col-md-3">
                                  <h5 class="text-right">Fecha: <?= date("Y-m-d") ?></h5>                                  
                                </div>                      
                              </div>

                              <div class="row">
                                <div class="form-group col-md-2">                   
                                    <b>Ruc :</b><span id="lbl_Ruc"><?= $data['cabData']['Cedula'] ?></span>
                                </div>   
                                <div class="form-group col-md-4">
                                    <b>Nombre :</b><span id="lbl_Nombre"><?= $data['cabData']['Nombre'] ?></span>
                                </div>
                                <div class="form-group col-md-4">
                                    <b>Dirección :</b><span id="lbl_Direccion"><?= $data['cabData']['Direccion'] ?></span>                           
                                </div> 
                                <div class="form-group col-md-2">
                                  <?php if($data['btn_save']){ ?>
                                    <button id="cmd_guardar" class="btn btn-primary" type="button">Guardar</button>
                                  <?php } ?>
                                                      
                                </div>                     
                              </div>

                  
                              
                          </blockquote>
                      </div>

                  </div>
                  <br>

                </form>

                <div class="table-responsive"  style="display: none;">
                  <table class="table table-hover table-bordered" >
                    <thead>
                        <tr>
                          <th>Código</th>                          
                          <th>Descripción</th>
                          <!-- <th>Existencía</th> -->
                          <th>Cantidad</th>
                          <th>Precio</th>
                          <th>Total</th>
                          <th>Acciones</th>
                        </tr>
                        <tr>
                          <td>
                              <div class="input-group">
                              <input class="form-control" id="txtCodigoItem" name="txtCodigoItem" type="text" required="">                                         
                                <button id="cmd_buscarItem" class="btn btn-primary" onclick="openModalBuscar();" type="button"><i class=" fa fa-search-plus"></i></button>
                              </div>
                                         
                          </td>
                          <input type="hidden" id="txthCargaIva" name="txthCargaIva" value="">
                          <td><input class="form-control" id="txtDetalleItem" name="txtDetalleItem" type="text" value="" disabled></td>
                          <!-- <td><input class="form-control" id="txtStockItem" name="txtStockItem" type="text" value="0" disabled></td> -->
                          <td><input class="form-control" id="txtCantidadItem" name="txtCantidadItem" type="text" value="0"></td>
                          <td><input class="form-control" id="txtPrecioItem" name="txtPrecioItem" type="text" value="0.00"></td>
                          <td><input class="form-control" id="txtTotalItem" name="txtTotalItem" type="text" value="0.00" disabled></td>
                          <td>
                           
                            <button id="cmd_agregar" class="btn btn-primary" type="button">Agregar</button>
                            
                          </td>                        
                        </tr>

                    </thead>
                    
                  </table>
                </div>


                  <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="TbG_tableItem">
                      <thead>
                        <tr>
                          <th>Código</th>
                          <th colspan="2">Descripción</th>
                          <th>Cantidad</th>
                          <th class="textright">Precio</th>
                          <th class="textright">Total</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody id="detalle_venta">
                   
                        <!-- <tr>
                          <td>1</td>
                          <td colspan="2">lapiz</td>
                          <td>1</td>
                          <td class="textright">100.00</td>
                          <td class="textright">100-00</td>
                          <td>
                            <a href="#" id="cmd_delete" class="link_delete" onclick="event.preventDefault();eliminarDetalle(1);"><i class="fa fa-trash"></i>
                            </a>
                          </td>
                        </tr> -->
                        
                        
                      </tbody>
                      
                    </table>
                  </div>

                  <div class="table-responsive">
                  <table class="table table-hover " >
                    <tbody>
                        <tr>                          
                          <td  class="textright">SubTotal : <span id="lbl_SubTotal">0.00</span></td>
                          <td  class="textright">Base 0 : <span id="lbl_Base0">0.00</span></td>
                          <td  class="textright">Base Imponible : <span id="lbl_Base12">0.00</span></td>
                          <td  class="textright">Iva 12% : <span id="lbl_Iva">0.00</span></td>
                          <td  class="textright">Total : <span id="lbl_Total">0.00</span></td>
                        </tr>
                    </tbody>
                    
                  </table>
                </div>



            
                </div>
              </div>
            </div>
        </div>
    </main>

    
    <script>
        let accionData="factura";
        let detData=<?= $data['detData'] ?>;
    </script>
    
    <?php adminFooter($data); ?>
    