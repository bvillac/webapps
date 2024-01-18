<?php
adminHeader($data);
adminMenu($data);
//filelang(Setlanguage,"general") 
require_once "Views/Instructor/Modals/modalPersonaBuscar.php";
require_once "Views/Instructor/Modals/modalPersona.php";
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
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>/instructor"><?= $data['page_title'] ?></a></li>
    </ul>
  </div>
  <div class="row">
    <input type="hidden" id="txth_ids" name="txth_ids" value="">
    <input type="hidden" id="txth_per_id" name="txth_per_id" value="">

    <div class="col-md-12">
      <div class="tile">
        <div class="tile-body">
          <div class="row">
            <div class="form-group col-md-3">
              <label class="control-label">Buscar Persona DNI</label>
              <div class="input-group">
                <input class="form-control" id="txtCodigoPersona" name="txtCodigoPersona" type="text" required="" placeholder="Buscar">
                <button id="cmd_buscarDatos" class="btn btn-primary" onclick="openModalBuscarPersona();" type="button"><i class=" fa fa-search-plus"></i></button>
              </div>
            </div>
            <div class="form-group col-md-3">
              <label class="control-label"></label>
              <div class="input-group">
                <button id="cmd_nueva_persona" class="btn btn-primary" type="button" onclick="openModalPersona();"><i class="fa fa-plus"></i> Nueva Persona</button>
              </div>



            </div>

          </div>
          <div class="row">
            <div class="form-group col-md-3">
              <label class="control-label">DNI</label>
              <input class="form-control" type="text" id="txt_cedula" name="txt_cedula" placeholder="" disabled>
            </div>
            <div class="form-group col-md-3">
              <label class="control-label">Nombres</label>
              <input class="form-control" type="text" id="txt_nombres" name="txt_nombres" placeholder="" disabled>
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-3">
              <label for="cmb_CentroAtencion">Centro Atención</label>
              <select class="form-control" id="cmb_CentroAtencion" name="cmb_CentroAtencion" onchange="fntSalones(this.value)">
                <?php
                echo '<option value="0">SELECCIONAR</option>';
                foreach ($data['centroAtencion'] as $opcion) {
                  echo '<option value="' . $opcion['Ids'] . '" >' . $opcion['Nombre'] . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group col-md-3">
              <label for="cmb_Salon">Presione CTRL y haga clic para seleccionar varias opciones a la vez.</label>
              <select class="form-control" multiple id="cmb_Salon" name="cmb_Salon" disabled>
              </select>
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



          <div class="table-responsive">
            <table class="table" id="dts_horas">
              <thead>
                <tr>
                  <th>Horas</th>
                  <th>Lunes</th>
                  <th>Martes</th>
                  <th>Miercoles</th>
                  <th>jueves</th>
                  <th>Viernes</th>
                  <th>Sabado</th>
                  <th>Domingo</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $numero = 8;
                //$modulos = $data['modulos'];//Obtiene lo que tiene el campo
                for ($i = 0; $i < 14; $i++) {

                ?>
                  <tr>
                    <td><?= $numero; ?>:00</td>
                    <td>
                      <div class="toggle-flip">
                        <label>
                          <input type="checkbox" id="LU<?= $numero ?>"><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                        </label>
                      </div>
                    </td>
                    <td>
                      <div class="toggle-flip">
                        <label>
                          <input type="checkbox" id="MA<?= $numero ?>"><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span> </label>
                      </div>
                    </td>
                    <td>
                      <div class="toggle-flip">
                        <label>
                          <input type="checkbox" id="MI<?= $numero ?>"><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                        </label>
                      </div>
                    </td>
                    <td>
                      <div class="toggle-flip">
                        <label>
                          <input type="checkbox" id="JU<?= $numero ?>"><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                        </label>
                      </div>
                    </td>
                    <td>
                      <div class="toggle-flip">
                        <label>
                          <input type="checkbox" id="VI<?= $numero ?>"><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                        </label>
                      </div>
                    </td>
                    <td>
                      <div class="toggle-flip">
                        <label>
                          <input type="checkbox" id="SA<?= $numero ?>"><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                        </label>
                      </div>
                    </td>
                    <td>
                      <div class="toggle-flip">
                        <label>
                          <input type="checkbox" id="DO<?= $numero ?>"><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                        </label>
                      </div>
                    </td>
                  </tr>
                <?php
                  $numero++;
                }
                ?>
              </tbody>
            </table>
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