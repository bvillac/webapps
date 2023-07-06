<?php
adminHeader($data);
adminMenu($data);
//filelang(Setlanguage,"general") 
getModal('modalPersonaBuscar', $data);
getModal('modalUsuarios', $data);
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
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>/contrato"><?= $data['page_title'] ?></a></li>
        </ul>
    </div>
    <div class="row">
        <input type="hidden" id="txth_ids" name="txth_ids" value="">
        <input type="hidden" id="txth_per_id" name="txth_per_id" value="">

        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p>
                            <h5>CONTRATO DEL PROGRAMA DE IDIOMAS MILLER TRAINING</h5>
                            </p>
                            <p>
                            <h4>R.U.C. <?= $data['Ruc'] ?></h4>
                            </p>
                        </div>
                        <div class="col-md-3">
                            <p>
                            <h4 id="lbl_secuencia">N° <?= $data['secuencia'] ?></h4>
                            </p>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="control-label">Fecha del Contrato</label>
                            <div class="dflex">
                                <input class="date-picker form-control valid" id="dtp_fecha_inicio" name="dtp_fecha_inicio" placeholder="yyyy-mm-dd">
                            </div>
                        </div>
                    </div>
                    <h3 class="mb-3 line-head" id="type-blockquotes">Cliente</h3>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label class="control-label">Buscar Persona <span class="required">*</span></label>
                            <div class="input-group">
                                <input class="form-control" id="txt_CodigoPersona" name="txt_CodigoPersona" type="text" required="" placeholder="Buscar por Nombre o DNI">
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label class="control-label">Titular</label>
                            <input class="form-control" type="text" id="txt_nombres" name="txt_nombres" placeholder="" disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="control-label">DNI</label>
                            <input class="form-control" type="text" id="txt_cedula" name="txt_cedula" placeholder="" disabled>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs">
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#info">Información</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#beneficiarios">Beneficiarios</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#totales">Totales</a></li>
                                <!-- <li class="nav-item"><a class="nav-link disabled" href="#detalle">Detalle</a></li> -->
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade active show" id="info">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="txt_razon_social">Nombre Empresa <span class="required">*</span></label>
                                            <input class="form-control" id="txt_razon_social" name="txt_razon_social" type="text" >
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_cargo">Cargo <span class="required">*</span></label>
                                            <input class="form-control" id="txt_cargo" name="txt_cargo" type="text" >
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_ingreso_mensual">Ingreso Mensual <span class="required">*</span></label>
                                            <input class="form-control" id="txt_ingreso_mensual" name="txt_ingreso_mensual" type="text" >
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_antiguedad">Antiguedad <span class="required">*</span></label>
                                            <input class="form-control" id="txt_antiguedad" name="txt_antiguedad" type="text" >
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="txt_dir_domicilio">Dirección Domicilio <span class="required">*</span></label>
                                            <input class="form-control" id="txt_dir_domicilio" name="txt_dir_domicilio" type="text" >
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_tel_domicilio">Teléfono Domicilio <span class="required">*</span></label>
                                            <input class="form-control" id="txt_tel_domicilio" name="txt_tel_domicilio" type="text" >
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_dir_trabajo">Dirección Trabajo <span class="required">*</span></label>
                                            <input class="form-control" id="txt_dir_trabajo" name="txt_dir_trabajo" type="text" >
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_tel_trabajo">Teléfono Trabajo <span class="required">*</span></label>
                                            <input class="form-control" id="txt_tel_trabajo" name="txt_tel_trabajo" type="text" >
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="txt_dir_domicilio">Dirección Domicilio <span class="required">*</span></label>
                                            <input class="form-control" id="txt_dir_domicilio" name="txt_dir_domicilio" type="text" >
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_tel_domicilio">Teléfono Domicilio <span class="required">*</span></label>
                                            <input class="form-control" id="txt_tel_domicilio" name="txt_tel_domicilio" type="text" >
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_dir_trabajo">Dirección Trabajo <span class="required">*</span></label>
                                            <input class="form-control" id="txt_dir_trabajo" name="txt_dir_trabajo" type="text" >
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_tel_trabajo">Teléfono Trabajo <span class="required">*</span></label>
                                            <input class="form-control" id="txt_tel_trabajo" name="txt_tel_trabajo" type="text" >
                                        </div>
                                    </div>



                                </div>
                                <div class="tab-pane fade active show" id="beneficiarios">

                                </div>
                                <div class="tab-pane fade active show" id="totales">

                                </div>
                            </div>
                        </div>

                    </div>




                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="cmb_profesion">Ocupación <span class="required">*</span></label>
                            <select class="form-control" data-live-search="true" id="cmb_profesion" name="cmb_profesion" required="">
                                <?php
                                // Recorre el array y genera las opciones del select
                                echo '<option value="0">SELECCIONAR</option>';
                                foreach ($data['ocupacion'] as $opcion) {
                                    echo '<option value="' . $opcion['Ids'] . '">' . $opcion['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="txt_nom">Nombre Empresa <span class="required">*</span></label>
                            <input class="form-control" id="txtPercha" name="txtPercha" type="text" onkeyup="TextMayus(this);" required="">
                        </div>
                    </div>
                    <h3 class="mb-3 line-head" id="type-blockquotes">Horarios</h3>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label class="control-label">Laborables</label>
                            <input class="form-control valid validText" type="text" id="txt_horas_asignadas" name="txt_horas_asignadas" placeholder="" required="">
                        </div>
                        <div class="form-group col-md-3">
                            <label class="control-label">Extras</label>
                            <input class="form-control valid validText" type="text" id="txt_horas_extras" name="txt_horas_extras" placeholder="" required="">
                        </div>

                    </div>




                    <div class="text-center">
                        <button id="cmd_guardar" class="btn btn-success" type="button" onclick="guardarInstructor('Create');"><i class="fa fa-fw fa-lg fa-check-circle" aria-hidden="true"></i> Guardar</button>
                        <button id="cmd_retornar" class="btn btn-danger" type="button" data-dismiss="modal"><i class="app-menu__icon fas fa-sign-out-alt" aria-hidden="true"></i> Retornar</button>
                    </div>




                </div>
            </div>
        </div>
    </div>
</main>

<script src="<?= media() ?>/js/cedulaRucPass.js"></script>


<?php adminFooter($data); ?>