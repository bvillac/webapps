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
                    <h3 class="mb-3 line-head" id="type-blockquotes">Datos Cliente</h3>
                    <!-- <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="txt_codigo">Código</label>
                            <input type="text" class="form-control valid validText " id="txt_codigo" name="txt_codigo" value="<?= $data['Codigo'] ?>" onkeyup="TextMayus(this);" required="" disabled>
                        </div>
                        <div class="form-group col-md-6">
                        </div>
                    </div> -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="txt_cli_tipo_dni">Tipo DNI</label>
                            <select class="form-control" id="txt_cli_tipo_dni" name="txt_cli_tipo_dni" value="<?= $data['Tipo'] ?>" required="" disabled>
                                <option value="01">Cédula</option>
                                <option value="02">Ruc</option>
                                <option value="03">Pasaporte</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="txt_cli_cedula_ruc">Identificación Cédula/Ruc</label>
                            <input type="text" class="form-control valid validarNumber " id="txt_cli_cedula_ruc" name="txt_cli_cedula_ruc" value="<?= $data['Cedula'] ?>" required="" onkeypress="return controlTagEvent(event);" >
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="txt_cli_razon_social">Razón Social</label>
                            <input type="text" class="form-control valid validText" id="txt_cli_razon_social" name="txt_cli_razon_social" value="<?= $data['Nombre'] ?>" onkeyup="TextMayus(this);" required="" >
                        </div>
                        <div class="form-group col-md-6">
                            <label for="txt_cli_direccion">Dirección Comercial</label>
                            <input type="text" class="form-control valid validText " id="txt_cli_direccion" name="txt_cli_direccion" value="<?= $data['Direccion'] ?>" onkeyup="TextMayus(this);" required="" >
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="txt_cli_telefono">Teléfono/Celular</label>
                            <input type="text" maxlength="10" class="form-control valid validarNumber" value="<?= $data['Telefono'] ?>" id="txt_cli_telefono" name="txt_cli_telefono" placeholder="0999999999" required="" onkeypress="return controlTagEvent(event);">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="txt_cli_telefono_oficina">Teléfono Oficina</label>
                            <input type="text" maxlength="10" class="form-control valid validarNumber" value="<?= $data['TelefOficina'] ?>" id="txt_cli_telefono_oficina" name="txt_cli_telefono_oficina" placeholder="0999999999" required="" onkeypress="return controlTagEvent(event);">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="txt_cli_correo">Correo Electrónico</label>
                            <input type="text" class="form-control valid validarEmail " id="txt_cli_correo" value="<?= $data['Correo'] ?>" name="txt_cli_correo" placeholder="ejemplo@gmail.com" required="">
                        </div>
                        <!-- <div class="form-group col-md-6">
                            <label for="txt_cli_referencia_bancaria">Referencía Bancaria</label>
                            <input type="text" maxlength="100" class="form-control valid validText" value="<?= $data['RefBanco'] ?>" id="txt_cli_referencia_bancaria" onkeyup="TextMayus(this);" name="txt_cli_referencia_bancaria" placeholder="Referencía Bancaria" required="">
                        </div> -->
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="cmb_ocupacion">Ocupación</label>
                            <select class="form-control" data-live-search="true" id="cmb_ocupacion" name="cmb_ocupacion" required="">
                                <?php
                                // Recorre el array y genera las opciones del select
                                echo '<option value="0">SELECCIONAR</option>';
                                foreach ($data['ocupacion'] as $opcion) {                                    
                                    if ($opcion['Ids'] == $data['OcupId']) {
                                        echo '<option value="' . $opcion['Ids'] . '" selected>' . $opcion['Nombre'] . '</option>';
                                    }else{
                                        echo '<option value="' . $opcion['Ids'] . '">' . $opcion['Nombre'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="txt_cli_cargo">Cargo</label>
                            <input type="text" maxlength="100" class="form-control valid validText" value="<?= $data['Cargo'] ?>"  id="txt_cli_cargo" name="txt_cli_cargo" onkeyup="TextMayus(this);" placeholder="Cargo" required="">
                        </div>
                        
                    </div>
                    <!-- <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="txt_cli_ingreso_mensual">Ingresos Mensuales</label>
                            <input type="text" maxlength="10" class="form-control valid validText" value="<?= $data['IngMensual'] ?>" id="txt_cli_ingreso_mensual" onkeyup="TextMayus(this);" name="txt_cli_ingreso_mensual" placeholder="Ingresos Mensuales" required="">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="txt_cli_antiguedad">Antiguedad</label>
                            <input type="text" maxlength="20" class="form-control valid validText" value="<?= $data['Antiguedad'] ?>"  id="txt_cli_antiguedad" name="txt_cli_antiguedad" onkeyup="TextMayus(this);" placeholder="Antiguedad" required="">
                        </div>
                    </div>
 -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="cmb_pago">Forma de Pago</label>
                            <select class="form-control" data-live-search="true" id="cmb_pago" name="cmb_pago" required="">
                                <?php
                                // Recorre el array y genera las opciones del select
                                echo '<option value="0">SELECCIONAR</option>';
                                foreach ($data['forma_pago'] as $opcion) {
                                    echo '<option value="' . $opcion['Ids'] . '">' . $opcion['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="cmb_estado">Estado</label>
                            <select class="form-control" id="cmb_estado" name="cmb_estado" required="">
                                <option value="1">Activo</option>
                                <option value="2">Inactivo</option>
                            </select>
                        </div>
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