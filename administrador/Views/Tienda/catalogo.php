<?php
adminHeader($data);
adminMenu($data);
//filelang(Setlanguage,"general") 
?>
<script>
  const tipoDNI = "<?= $data['Tipo'] ?>";
  const tipoPago = "<?= $data['fpag_id'] ?>";
  const estado = "<?= $data['Estado'] ?>";
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
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>/<?= $data['page_back'] ?>"><?= $data['page_title'] ?></a></li>
        </ul>
    </div>
    <div class="row">
        <input type="hidden" id="txth_ids" name="txth_ids" value="<?= $data['Ids'] ?>">
        <input type="hidden" id="txth_per_id" name="txth_per_id" value="<?= $data['PerIds'] ?>">

        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <h3 class="mb-3 line-head" id="type-blockquotes">Cliente: <?= $data['nombreCliente'] ?></h3>
                    <div class="form-row">
                        <div class="form-group col-md-6">                          
                            <label for="cmb_tienda">Tiendas</label>
                            <select class="form-control" data-live-search="true" id="cmb_tienda" name="cmb_tienda" required="">
                                <?php
                                // Recorre el array y genera las opciones del select
                                echo '<option value="0">SELECCIONAR</option>';
                                foreach ($data['tienda'] as $opcion) {
                                    $seleted=($opcion['Ids']==$data['Ids'])?'selected':'';
                                    echo '<option value="' . $opcion['Ids'] . '" '.$seleted.' >' . $opcion['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                        <label for="txt_nombreTienda">Nombre Tienda</label>
              <input type="text" class="form-control valid validText " maxlength="100" id="txt_nombreTienda" name="txt_nombreTienda" onkeyup="TextMayus(this);" required="">
                        </div>
                    </div>
                    <div class="form-row">
                        
                    </div>
                    
                    
                    
                    
    
                    
                    <div class="text-center">
                        <button id="cmd_guardar" class="btn btn-success" type="button" onclick="guardarCliente('Edit');"><i class="fa fa-fw fa-lg fa-check-circle" aria-hidden="true"></i> Guardar</button>
                        <button id="cmd_retornar" class="btn btn-danger" type="button" data-dismiss="modal"><i class="app-menu__icon fas fa-sign-out-alt" aria-hidden="true"></i> Retornar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>



<?php adminFooter($data); ?>