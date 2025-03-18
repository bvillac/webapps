<?php
adminHeader($data);
adminMenu($data);
//filelang(Setlanguage,"general") 
?>
<script>
    const productos = <?php echo json_encode($data['ClienteProducto']); ?>;
    sessionStorage.setItem('dts_precioTienda', JSON.stringify(productos));
</script>
<div id="contentAjax"></div>
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fas fa-user-tag"></i> <?= $data['page_title'] ?>

            </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a
                    href="<?= base_url(); ?>/<?= $data['page_back'] ?>"><?= $data['page_title'] ?></a></li>
        </ul>
    </div>
    <div class="row">
        <input type="hidden" id="txth_ids" name="txth_ids" value="<?= $data['Ids'] ?>">
        <input type="hidden" id="txth_art_id" name="txth_art_id" value="">
        <input type="hidden" id="txth_cod_art" name="txth_cod_art" value="0">
        <input type="hidden" id="txth_i_m_iva" name="txth_i_m_iva" value="0">
        
        <div class="col-md-3">
            <div class="tile"> 
                <h3 class="mb-3 line-head" id="type-blockquotes">Cliente: <?= $data['nombreCliente'] ?></h3>         
                <div class="form-group col-md-12">
                    <label class="control-label">Buscar Productos <span class="required">*</span></label>
                    <div class="input-group">
                        <input class="form-control" id="txt_CodigoProducto" name="txt_CodigoProducto" type="text"
                            required="" placeholder="Buscar por Nombre o Código">
                    </div>
                </div>
                
                <div class="form-group col-md-12">
                    <label class="control-label">Precio US$ <span class="required">*</span></label>
                    <div class="input-group">
                        <input class="form-control valid validarDecimal" id="txt_PrecioProducto" name="txt_PrecioProducto" type="text" value="0.00"
                            required="" placeholder="0.00" >
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <button type="button" class="btn btn-dark" id="btnAgregar"><i
                            class="fa fa-magnifying-glass"></i>Agregar</button>
                </div>

            </div>
        </div>


        <div class="col-md-9">
            <div class="tile">
                <div id="list_tables">
                    <h3 class="tile-title">Productos</h3>
                    <button id="btnGuardar" class="btn btn-success" type="button" ><i class="fa fa-fw fa-lg fa-check-circle" aria-hidden="true"></i> Guardar</button>
                    <button id="btn_retornar" class="btn btn-danger" type="button"> Retornar</button>
                </div>
                <br>

                <table class="table table-striped table-bordered table-hover" id="TbG_Tiendas">
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Item</th>
                            <th>Precio</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>


            </div>
          
            
        </div>
    </div>
</main>



<?php adminFooter($data); ?>