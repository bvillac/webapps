<div class="modal fade modalPermisos" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title h4" >Permisos Roles </h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <?php 
                //dep($data);
             ?>
            <div class="col-md-12">
              <div class="tile">
                <form action="" id="formPermisos" name="formPermisos">
                  <input type="hidden" id="rol_id" name="rol_id" value="<?= $data['idrol']; ?>" required="">
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Módulo</th>
                            <th>Ver</th>
                            <th>Crear</th>
                            <th>Actualizar</th>
                            <th>Eliminar</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $numero=1;
                                $modulos = $data['modulos'];//Obtiene lo que tiene el campo
                                for ($i=0; $i < count($modulos); $i++) { 
                                    $permisos = $modulos[$i]['permisos'];//obtiene la opcion permisos
                                    $rCheck = $permisos['r'] == 1 ? " checked " : "";//Asigna el Check segun lo selecinado
                                    $wCheck = $permisos['w'] == 1 ? " checked " : "";
                                    $uCheck = $permisos['u'] == 1 ? " checked " : "";
                                    $dCheck = $permisos['d'] == 1 ? " checked " : "";
                                    $idmod = $modulos[$i]['mod_id'];
                            ?>
                              <tr>
                                <td>
                                    <?= $idmod; ?>
                                    <input type="hidden" name="modulos[<?= $i; ?>][mod_id]" value="<?= $idmod ?>" required >
                                </td>
                                <td>
                                    <?= $modulos[$i]['mod_nombre']; ?>
                                </td>
                                <td><div class="toggle-flip">
                                      <label>
                                        <input type="checkbox" name="modulos[<?= $i; ?>][r]" <?= $rCheck ?> ><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                                      </label>
                                    </div>
                                </td>
                                <td><div class="toggle-flip">
                                      <label>
                                        <input type="checkbox" name="modulos[<?= $i; ?>][w]" <?= $wCheck ?>><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                                      </label>
                                    </div>
                                </td>
                                <td><div class="toggle-flip">
                                      <label>
                                        <input type="checkbox" name="modulos[<?= $i; ?>][u]" <?= $uCheck ?>><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                                      </label>
                                    </div>
                                </td>
                                <td><div class="toggle-flip">
                                      <label>
                                        <input type="checkbox" name="modulos[<?= $i; ?>][d]" <?= $dCheck ?>><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
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
                        <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle" aria-hidden="true"></i> Guardar</button>
                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal"><i class="app-menu__icon fas fa-sign-out-alt" aria-hidden="true"></i> Salir</button>
                    </div>
                </form>
              </div>
            </div>
        </div>

    </div>
  </div>
</div>