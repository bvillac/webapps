<?php
adminHeader($data);
adminMenu($data);
//filelang(Setlanguage,"general") 
//getModal('modalPersonaBuscar', $data);
//getModal('modalUsuarios', $data);

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
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>/lineas"><?= $data['page_title'] ?></a></li>
        </ul>
    </div>
    <div class="row">
        <input type="hidden" id="txth_ids" name="txth_ids" value="<?= $data['Ids'] ?>">
        <input type="hidden" id="txth_per_id" name="txth_per_id" value="<?= $data['PerIds'] ?>">

        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <h3 class="mb-3 line-head" id="type-blockquotes">Datos Cliente</h3>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="txt_codigo">Código</label>
                            <input type="text" class="form-control valid validText " id="txt_codigo" name="txt_codigo" value="<?= $data['Codigo'] ?>" onkeyup="TextMayus(this);" required="" disabled>
                        </div>
                        <div class="form-group col-md-6">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="txt_cli_tipo_dni">Tipo DNI</label>
                            <select class="form-control" id="txt_cli_tipo_dni" name="txt_cli_tipo_dni" value="<?= $data['Tipo'] ?>" required="">
                                <option value="01">Cédula</option>
                                <option value="02">Ruc</option>
                                <option value="03">Pasaporte</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="txt_cli_cedula_ruc">Identificación Cédula/Ruc</label>
                            <input type="text" class="form-control valid validarNumber " id="txt_cli_cedula_ruc" name="txt_cli_cedula_ruc" value="<?= $data['Cedula'] ?>" required="" onkeypress="return controlTagEvent(event);">
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
                            <label for="txt_cli_correo">Correo Electrónico</label>
                            <input type="text" class="form-control valid validarEmail " id="txt_cli_correo" name="txt_cli_correo"  value="<?= $data['Correo'] ?>" placeholder="ejemplo@gmail.com" required="" >
                        </div>
                        <div class="form-group col-md-6">
                            <label for="txt_cli_telefono">Teléfono/Celular</label>
                            <input type="text" maxlength="10" class="form-control valid validarNumber" id="txt_cli_telefono" name="txt_cli_telefono" value="<?= $data['Telefono'] ?>" placeholder="0999999999" required="" onkeypress="return controlTagEvent(event);">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="cmb_pago">Forma de Pago</label>
                            <select class="form-control" data-live-search="true" id="cmb_pago" name="cmb_pago" required="">
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="cmb_estado">Estado</label>
                            <select class="form-control" id="cmb_estado" name="cmb_estado" value="<?= $data['Estado'] ?>" required="">
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

<script src="<?= media() ?>/js/cedulaRucPass.js"></script>



<?php adminFooter($data); ?>