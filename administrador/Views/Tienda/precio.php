<?php
adminHeader($data);
adminMenu($data);
//filelang(Setlanguage,"general") 
?>
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
        <div class="col-md-3">
            <div class="tile row">
                <div class="form-group col-md-12">
                    <label for="cmb_tienda">Tiendas</label>
                    <select class="form-control" data-live-search="true" id="cmb_tienda" name="cmb_tienda" required="">
                        <?php
                        // Recorre el array y genera las opciones del select
                        echo '<option value="0">SELECCIONAR</option>';
                        foreach ($data['tienda'] as $opcion) {
                            $seleted = ($opcion['Ids'] == $data['Ids']) ? 'selected' : '';
                            echo '<option value="' . $opcion['Ids'] . '" ' . $seleted . ' >' . $opcion['Nombre'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
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
                            required="" >
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <button type="button" class="btn btn-dark" id="btnAgregar"><i
                            class="fa fa-magnifying-glass"></i>Agregar</button>
                    <button type="button" class="btn btn-success" id="btn_imprimir"><i
                            class="fa fa-print"></i>Imprimir</button>
                </div>

            </div>
        </div>


        <div class="col-md-9">
            <div class="tile">
                <div id="list_tables">
                    <h3 class="tile-title">Productos</h3>
                    <button id="btnGuardar" class="btn btn-success" type="button" ><i class="fa fa-fw fa-lg fa-check-circle" aria-hidden="true"></i> Guardar</button>
                    <button id="btnRetornar" class="btn btn-danger" type="button"><i class="app-menu__icon fas fa-sign-out-alt" aria-hidden="true"></i> Retornar</button>
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