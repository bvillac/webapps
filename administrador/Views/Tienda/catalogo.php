<?php
adminHeader($data);
adminMenu($data);
//filelang(Setlanguage,"general"); 
require_once "Views/Tienda/Modals/modalGaleria.php";
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




        <div class="col-md-12">
            <div class="tile">
                <div id="list_tables">
                    <h3 class="mb-3 line-head" id="type-blockquotes">Cliente: <?= $data['nombreCliente'] ?></h3>                    
                </div>

                <div class="row">
                    
                    <div class="col">
                 
                            <label for="cmb_tiendas">Tiendas/Clientes</label>
                            <select class="form-control" data-live-search="true" id="cmb_tiendas" name="cmb_tiendas" required="">
                                <?php
                                // Recorre el array y genera las opciones del select
                                echo '<option value="0">SELECCIONAR</option>';
                                foreach ($data['tiendas'] as $opcion) {
                                    $seleted=($opcion['Ids']==$data['TiendaId'])?'selected':'';
                                    echo '<option value="' . $opcion['Ids'] . '" '.$seleted.' >' . $opcion['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        
                    </div>



                    <div class="col">

                        <label class="control-label">Buscar Producto</label>
                        <div class="input-group">
                            <input class="form-control" id="txtCodigoProducto" name="txtCodigoProducto" type="text"
                                required="" placeholder="Buscgitar Producto" oninput="filtrarTabla()">
                            <button id="cmd_buscarDatos" class="btn btn-primary" onclick="openModalBuscarPersona();"
                                type="button"><i class=" fa fa-search-plus"></i> Buscar</button>
                        </div>

                    </div>

                </div>
                <br>
                <button id="btn_GuardarTienda" class="btn btn-success" type="button" ><i class="fa fa-fw fa-lg fa-check-circle" aria-hidden="true"></i> Guardar</button>
                <br>
                <h3 class="tile-title">Productos</h3>
                <table class="table table-striped table-bordered table-hover" id="TbG_Tiendas">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)"></th>
                            <th>Codigo</th>
                            <th>Item</th>
                            <th>Img</th>
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