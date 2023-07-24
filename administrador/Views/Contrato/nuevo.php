<?php
adminHeader($data);
adminMenu($data);
//filelang(Setlanguage,"general") 
getModal('modalPersonaBuscar', $data);
//getModal('modalUsuarios', $data);
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
        <input type="hidden" id="txth_idsFPago" name="txth_idsFPago" value="">
        <input type="hidden" id="txth_per_idBenef" name="txth_per_idBenef" value="">

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
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#info">INFORMACIÓN</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#beneficiarios">BENEFICIARIOS</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#totales">TOTALES</a></li>
                                <!-- <li class="nav-item"><a class="nav-link disabled" href="#detalle">Detalle</a></li> -->
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade active show" id="info">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="txt_razon_social">Nombre Empresa <span class="required">*</span></label>
                                            <input class="form-control" id="txt_razon_social" name="txt_razon_social" type="text" disabled>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_cargo">Cargo <span class="required">*</span></label>
                                            <input class="form-control" id="txt_cargo" name="txt_cargo" type="text" disabled>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_ingreso_mensual">Ingreso Mensual <span class="required">*</span></label>
                                            <input class="form-control" id="txt_ingreso_mensual" name="txt_ingreso_mensual" type="text" disabled>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_antiguedad">Antiguedad <span class="required">*</span></label>
                                            <input class="form-control" id="txt_antiguedad" name="txt_antiguedad" type="text" disabled>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="txt_dir_domicilio">Dirección Domicilio <span class="required">*</span></label>
                                            <input class="form-control" id="txt_dir_domicilio" name="txt_dir_domicilio" type="text" disabled>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_tel_domicilio">Teléfono Domicilio <span class="required">*</span></label>
                                            <input class="form-control" id="txt_tel_domicilio" name="txt_tel_domicilio" type="text" disabled>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_dir_trabajo">Dirección Trabajo <span class="required">*</span></label>
                                            <input class="form-control" id="txt_dir_trabajo" name="txt_dir_trabajo" type="text" disabled>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_tel_trabajo">Teléfono Trabajo <span class="required">*</span></label>
                                            <input class="form-control" id="txt_tel_trabajo" name="txt_tel_trabajo" type="text" disabled>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="txt_referencia">Referencía Bancaria <span class="required">*</span></label>
                                            <input class="form-control" id="txt_referencia" name="txt_referencia" type="text" disabled>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_ocupacion">Ocupación <span class="required">*</span></label>
                                            <input class="form-control" id="txt_ocupacion" name="txt_ocupacion" type="text" disabled>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_forma_pago">Forma de Pago <span class="required">*</span></label>
                                            <input class="form-control" id="txt_forma_pago" name="txt_forma_pago" type="text" disabled>
                                        </div>
                                        <div class="form-group col-md-3">

                                        </div>
                                    </div>
                                    <h5 class="mb-3 line-head" id="type-blockquotes"></h5>
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="txt_numero_recibo">Número de Recibo Inscripción <span class="required">*</span></label>
                                            <input class="form-control valid validarNumber" id="txt_numero_recibo" name="txt_numero_recibo" type="text" maxlength="10">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_numero_deposito">Número de Deposito <span class="required">*</span></label>
                                            <input class="form-control valid validarNumber" id="txt_numero_deposito" name="txt_numero_deposito" type="text" maxlength="10">
                                        </div>
                                    </div>



                                </div>
                                <div class="tab-pane fade" id="beneficiarios">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="txt_CodigoBeneficiario">Buscar Persona por Nombre <span class="required">*</span></label>
                                            <input class="form-control" id="txt_CodigoBeneficiario" name="txt_CodigoBeneficiario" type="text" required="" placeholder="Buscar por Nombre o DNI">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_NombreBeneficirio">Nombre <span class="required">*</span></label>
                                            <input class="form-control" id="txt_NombreBeneficirio" name="txt_NombreBeneficirio" type="text" disabled>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_EdadBeneficirio">Edad <span class="required">*</span></label>
                                            <input class="form-control" id="txt_EdadBeneficirio" name="txt_EdadBeneficirio" type="text" disabled>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_TelefonoBeneficirio">Teléfono <span class="required">*</span></label>
                                            <input class="form-control" id="txt_TelefonoBeneficirio" name="txt_TelefonoBeneficirio" type="text" disabled>
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
                                                    echo '<option value="' . $opcion['Ids'] . '">' . $opcion['Nombre'] . '</option>';
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
                                                    echo '<option value="' . $opcion['IdsMes'] . '">' . $opcion['Nombre'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_numero_meses">Número de Meses <span class="required">*</span></label>
                                            <input class="form-control valid validarNumber" id="txt_numero_meses" name="txt_numero_meses" type="text" value="0" required="" onkeypress="return controlTagEvent(event);">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_numero_horas">Número de Horas <span class="required">*</span></label>
                                            <input class="form-control valid validarNumber" id="txt_numero_horas" name="txt_numero_horas" type="text" value="0" required="" onkeypress="return controlTagEvent(event);">
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
                                                    echo '<option value="' . $opcion['Ids'] . '">' . $opcion['Nombre'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="cmb_Idioma">Idioma</label>
                                            <select class="form-control" data-live-search="true" id="cmb_Idioma" name="cmb_Idioma" required="">
                                                <?php
                                                // Recorre el array y genera las opciones del select
                                                echo '<option value="0">SELECCIONAR</option>';
                                                foreach ($data['idioma'] as $opcion) {
                                                    echo '<option value="' . $opcion['Ids'] . '">' . $opcion['Nombre'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        
                                        <div class="form-group col-md-3 toggle-flip">                                           
                                            <label for="chk_tipoBeneficiario">Titular
                                            
                                                <input class="form-control"  type="checkbox" id="chk_tipoBeneficiario"><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                                                </label>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button id="cmd_agregarBeneficiario" class="btn btn-success" type="button" onclick="agregarItemsDoc();"><i class="fa fa-fw fa-lg fa-check-circle" aria-hidden="true"></i> Agregar Beneficiario</button>
                                    </div>
                                    <h5 class="mb-3 line-head" id="type-blockquotes">Datos Beneficiarios</h5>

                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered" id="TbG_tableBeneficiario">
                                            <thead>
                                                <tr>
                                                    <th>Dni</th>
                                                    <th>Nombres</th>
                                                    <th>Tipo</th>
                                                    <th>Centro</th>
                                                    <th>Paquete</th>
                                                    <th>Meses</th>
                                                    <th>Horas</th>
                                                    <th>Modalidad</th>
                                                    <th>Idioma</th>
                                                    <th>Edad</th>
                                                    <th>Teléfono</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="listaBeneficiarios">
                                            </tbody>
                                        </table>
                                    </div>


                                </div>
                                <div class="tab-pane fade" id="totales">
                                    <BR>
                                    <h5 class="mb-3 line-head" id="type-blockquotes">VALORES DE PAGO</h5>
                                    <div class="mb-3 row">

                                        <label for="txt_valor" class="col-sm-2 col-form-label text-right">Valor US$</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" id="txt_valor" value="0.00">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="txt_CuotaInicial" class="col-sm-2 col-form-label text-right">Cuota Inicial/Anticipo</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" id="txt_CuotaInicial" value="0.00">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="txt_SaldoTotal" class="col-sm-2 col-form-label text-right">Saldo</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" id="txt_SaldoTotal" value="0.00" disabled>
                                        </div>
                                    </div>
                                    <h5 class="mb-3 line-head" id="type-blockquotes">CUOTAS</h5>
                                    <div class="mb-3 row">
                                        <label for="txt_NumeroCuota" class="col-sm-2 col-form-label text-right">Número de Cuotas</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" id="txt_NumeroCuota" value="0">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="txt_ValorMensual" class="col-sm-2 col-form-label text-right">Mensualidades</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" id="txt_ValorMensual" value="0.00" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>



                    <div class="text-center">
                        <button id="cmd_guardar" class="btn btn-success" type="button" onclick="guardarContrato();"><i class="fa fa-fw fa-lg fa-check-circle" aria-hidden="true"></i> Guardar</button>
                        <button id="cmd_retornar" class="btn btn-danger" type="button" data-dismiss="modal"><i class="app-menu__icon fas fa-sign-out-alt" aria-hidden="true"></i> Retornar</button>
                    </div>




                </div>
            </div>
        </div>
    </div>
</main>

<script src="<?= media() ?>/js/cedulaRucPass.js"></script>


<?php adminFooter($data); ?>