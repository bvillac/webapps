<?php
adminHeader($data);
adminMenu($data);
//filelang(Setlanguage,"general") 
require_once "Views/PedidoWeb/Modals/modalGaleria.php";
?>
<script>
    //const productos = <?php //echo json_encode($data['ClienteProducto']); ?>;
    //sessionStorage.setItem('dts_precioTienda', JSON.stringify(productos));
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
        <!-- <input type="hidden" id="txth_ids" name="txth_ids" value="<?= $data['Ids'] ?>"> -->
        <input type="hidden" id="txth_ids" name="txth_ids" value="0">
        <input type="hidden" id="txth_art_id" name="txth_art_id" value="">
        <input type="hidden" id="txth_cod_art" name="txth_cod_art" value="0">

        <div class="col-md-3">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Cliente: <?= $data['nombreCliente'] ?></h3>
                </div>

                <div class="form-group col-md-12">
                    <label for="cmb_tienda"><strong><!--<i class="fas fa-book mr-1"></i>--> Tienda</strong></label>

                    <select class="form-control" data-live-search="true" id="cmb_tienda" name="cmb_tienda" required="">
                        <?php
                        // Recorre el array y genera las opciones del select
                        echo '<option value="0">SELECCIONAR TIENDA</option>';
                        foreach ($data['tienda'] as $opcion) {
                            echo '<option value="' . $opcion['Ids'] . '">' . $opcion['Nombre'] . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="card-body">
                    <strong><i class="fas fa-map-marker-alt mr-1"></i> Información de Tienda</strong>
                    <ul class="text-muted list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Cupo Otorgado</b> <a class="float-right"><label id="lbl_cupo">0.00</label></a>
                        </li>
                        <li class="list-group-item">
                            <b>Cupo Usado</b> <a class="float-right"><label id="lbl_cupoUsado">0.00</label></a>
                        </li>
                        <li class="list-group-item">
                            <b>Cupo Saldo</b> <a class="float-right"><label id="lbl_cupoSaldo">0.00</label></a>
                        </li>
                        <li class="list-group-item">
                            <b>Contacto</b> <a class="float-right"><label id="lbl_contacto"></label></a>
                        </li>
                        <li class="list-group-item">
                            <b>Dirección</b> <a class="float-right"><label id="lbl_direccion"></label></a>
                        </li>
                        <li class="list-group-item">
                            <b>Teléfono</b> <a class="float-right"><label id="lbl_telefono"></label></a>
                        </li>
                    </ul>

                    <strong><i class="far fa-file-alt mr-1"></i> Observación</strong>
                    <p class="text-muted">No tiene Observaciones</p>
                </div>

            </div>

        </div>

        <div class="col-md-9">
            <div class="tile">
                <div id="list_tables">
                    <h3 class="tile-title">Pedido N°:</h3>
                    
                </div>
                <br>

            

                <div id="alerta-cupo" class="alert alert-dismissible fade show d-none" role="alert"
                    style="position: sticky; top: 0; z-index: 1000;">
                    <span id="alerta-cupo-mensaje"><strong>¡Atención!</strong> Mensaje dinámico aquí.</span>
                    <button type="button" class="btn-close ms-auto" aria-label="Cerrar"
                        onclick="ocultarAlertaCupo()"></button>
                </div>

                <div class="row">
                    <div class="col">
                        <!-- <label class="control-label">Buscar Producto</label> -->
                        <div class="input-group">
                            <input class="form-control" id="txtCodigoProducto" name="txtCodigoProducto" type="text"
                                required="" placeholder="Buscgitar Producto" oninput="filtrarTabla()">
                        </div>
                    </div>
                    <div class="col">
                        <div id="lblTotalGeneral" class="fw-bold mt-3">Total General: 0.00</div>
                    </div>
                    <div class="col">
                        <button id="btnGuardar" class="btn btn-success" type="button"><i
                                class="fa fa-fw fa-lg fa-check-circle" aria-hidden="true"></i><span id="btnText">Guardar</span></button>
                        <button id="btn_retornar" class="btn btn-danger" type="button"> Retornar</button>
                    </div>



                    

                </div>
                <br>

                <table class="table table-striped table-bordered table-hover" id="TbG_ListaItems">
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Item</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Total</th>
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