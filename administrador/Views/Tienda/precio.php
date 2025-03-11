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
                        <input class="form-control" id="txt_CodigoPersona" name="txt_CodigoPersona" type="text"
                            required="" placeholder="Buscar por Nombre o DNI">
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label for="dtp_fecha">Fecha</label>
                    <input type="date" class="form-control " id="dtp_fecha" name="mdtp_fecha" placeholder="1988-01-01"
                        pattern="^\d{4}\/\d{2}\/\d{2}$" required="">
                </div>

                <div class="form-group col-md-12">
                    <button type="button" class="btn btn-dark" id="btn_buscar"><i
                            class="fa fa-magnifying-glass"></i>Buscar</button>
                    <button type="button" class="btn btn-success" id="btn_imprimir"><i
                            class="fa fa-print"></i>Imprimir</button>
                </div>

            </div>
        </div>


        <div class="col-md-9">
            <div class="tile">
                <div id="list_tables">
                    <h3 class="tile-title">Horarios</h3>
                </div>


            </div>
        </div>
    </div>
</main>



<?php adminFooter($data); ?>