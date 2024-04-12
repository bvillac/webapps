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
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>/lineas"><?= $data['page_title'] ?></a></li>
        </ul>
    </div>
    <div class="row">
        <input type="hidden" id="txth_ids" name="txth_ids" value="<?= $data['Ids'] ?>">
        <input type="hidden" id="txth_per_id" name="txth_per_id" >

        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="txt_dni">Cédula / DNI <span class="required">*</span></label>
                            <input class="form-control" value="<?= $data['Dni'] ?>" id="txt_dni" name="txt_dni" type="text" required="" placeholder="Buscar por Nombre o DNI" disabled>
                        </div>


                        <div class="form-group col-md-3">
                            <label for="txt_NombreBeneficirio">NombreS <span class="required">*</span></label>
                            <input class="form-control" value="<?= $data['Nombres'] ?>" id="txt_NombreBeneficirio" name="txt_NombreBeneficirio" type="text" disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="txt_EdadBeneficirio">Edad <span class="required">*</span></label>
                            <input class="form-control" value="<?= $data['Edad'] ?>" id="txt_EdadBeneficirio" name="txt_EdadBeneficirio" type="text" disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="txt_TelefonoBeneficirio">Teléfono <span class="required">*</span></label>
                            <input class="form-control" value="<?= $data['TelCelular'] ?>" id="txt_TelefonoBeneficirio" name="txt_TelefonoBeneficirio" type="text" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="cmb_CentroAtencion">Centro Atención</label>
                            <select class="form-control" data-live-search="true" id="cmb_CentroAtencion" name="cmb_CentroAtencion" required="">
                                <?php
                                // Recorre el array y genera las opciones del select
                                echo '<option value="0">SELECCIONAR</option>';
                                foreach ($data['centroAtencion'] as $opcion) {
                                    $seleted=($opcion['Ids']==$data['CentroId'])?'selected':'';
                                    echo '<option value="' . $opcion['Ids'] . '" '.$seleted.' >' . $opcion['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="cmb_PaqueteEstudios">Paquete/Estudios</label>
                            <select class="form-control" data-live-search="true" id="cmb_PaqueteEstudios" name="cmb_PaqueteEstudios" required="">
                                <?php
                                // Recorre el array y genera las opciones del select
                                echo '<option value="0">SELECCIONAR</option>';
                                foreach ($data['paqueteEstudios'] as $opcion) {
                                    $seleted=($opcion['Ids']==$data['PaqueteId'])?'selected':'';
                                    echo '<option value="' . $opcion['Ids'] . '" '.$seleted.' >' . $opcion['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="txt_numero_meses">Número de Meses <span class="required">*</span></label>
                            <input class="form-control valid validarNumber" value="<?= $data['NMeses'] ?>" id="txt_numero_meses" name="txt_numero_meses" type="text" value="0" required="" onkeypress="return controlTagEvent(event);">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="txt_numero_horas">Número de Horas <span class="required">*</span></label>
                            <input class="form-control valid validarNumber" value="<?= $data['NHoras'] ?>" id="txt_numero_horas" name="txt_numero_horas" type="text" value="0" required="" onkeypress="return controlTagEvent(event);">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="cmb_ModalidadEstudios">Modalidad/Estudios</label>
                            <select class="form-control" data-live-search="true" id="cmb_ModalidadEstudios" name="cmb_ModalidadEstudios" required="">
                                <?php
                                // Recorre el array y genera las opciones del select
                                echo '<option value="0">SELECCIONAR</option>';
                                foreach ($data['modalidadEstudios'] as $opcion) {
                                    $seleted=($opcion['Ids']==$data['ModalidadId'])?'selected':'';
                                    echo '<option value="' . $opcion['Ids'] . '" '.$seleted.' >' . $opcion['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="cmb_Idioma">Idioma</label>
                            <select class="form-control"  data-live-search="true" id="cmb_Idioma" name="cmb_Idioma" required="">
                                <?php
                                // Recorre el array y genera las opciones del select
                                echo '<option value="0">SELECCIONAR</option>';
                                foreach ($data['idioma'] as $opcion) {
                                    $seleted=($opcion['Ids']==$data['IdiomaId'])?'selected':'';
                                    echo '<option value="' . $opcion['Ids'] . '" '.$seleted.' >' . $opcion['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>


                        <div class="form-group col-md-3 toggle-flip">
                            <label for="chk_tipoBeneficiario">Titular

                                <input class="form-control" <?php if ($data['ben_tipo']) echo 'checked'; ?> type="checkbox" id="chk_tipoBeneficiario"><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                            </label>
                        </div>
                        <div class="form-group col-md-3 toggle-flip">
                            <label for="chk_ExamenInter">Exámen Internacional

                                <input class="form-control" <?php if ($data['Examen']) echo 'checked'; ?> type="checkbox" id="chk_ExamenInter"><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                            </label>
                        </div>
                    </div>

                    <div class="text-center">
                        <button id="cmd_guardar" class="btn btn-success" type="button" onclick="guardarBeneficiario('Edit');"><i class="fa fa-fw fa-lg fa-check-circle" aria-hidden="true"></i> Guardar</button>
                        <button id="cmd_retornar" class="btn btn-danger" type="button" data-dismiss="modal"><i class="app-menu__icon fas fa-sign-out-alt" aria-hidden="true"></i> Retornar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>




<?php adminFooter($data); ?>