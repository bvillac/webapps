<?php
  adminHeader($data);
  adminMenu($data);  
  getModal('modalPerfil',$data); 
 ?>
<main class="app-content">
  <div class="row user">
    <div class="col-md-12">
      <div class="profile">
        <div class="info"><img class="user-img" src="<?= media();?>/images/site/avatar.png">
          <h4><?= $_SESSION['usuarioData']['Nombres'] ?></h4>
          <p><?= $_SESSION['usuarioData']['Rol']; ?></p>
        </div>
        <div class="cover-image"></div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="tile p-0">
        <ul class="nav flex-column nav-tabs user-tabs">
          <li class="nav-item"><a class="nav-link active" href="#user-timeline" data-toggle="tab">Datos Personales</a></li>
          <!-- <li class="nav-item"><a class="nav-link" href="#user-settings" data-toggle="tab">Datos Usuario</a></li> -->
        </ul>
      </div>
    </div>
    <div class="col-md-9">
      <div class="tab-content">
        <div class="tab-pane active" id="user-timeline">
          <div class="timeline-post">
            <div class="post-media">
              <div class="content">
                <h5>INFORMACIÓN <button class="btn btn-sm btn-info" type="button" onclick="openModalPerfil();"><i class="fa fa-pencil" aria-hidden="true"></i></button></h5>
              </div>
            </div>

            <table class="table table-bordered">
              <tbody>
                <tr>
                  <td style="width:150px;">DNI:</td>
                  <td><?= $_SESSION['usuarioData']['Dni']; ?></td>
                </tr>
                <tr>
                  <td>Nombres:</td>
                  <td><?= $_SESSION['usuarioData']['Nombres']; ?></td>
                </tr>
                <tr>
                  <td>Dirección:</td>
                  <td><?= $_SESSION['usuarioData']['Direccion']; ?></td>
                </tr>
                <tr>
                  <td>Teléfono:</td>
                  <td><?= $_SESSION['usuarioData']['Telefono']; ?></td>
                </tr>
                <tr>
                  <td>Correo/Usuario:</td>
                  <td><?= $_SESSION['usuarioData']['usu_correo']; ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <!-- <div class="tab-pane fade" id="user-settings">
          <div class="tile user-settings">
            <h4 class="line-head">Datos fiscales</h4>
            <form id="formDataFiscal" name="formDataFiscal">
              <div class="row mb-4">
                <div class="col-md-6">
                  <label>Identificación Tributaria</label>
                  <input class="form-control" type="text" id="txtNit" name="txtNit" value="<?= $_SESSION['userData']['nit']; ?>">
                </div>
                <div class="col-md-6">
                  <label>Nombre fiscal</label>
                  <input class="form-control" type="text" id="txtNombreFiscal" name="txtNombreFiscal" value="<?= $_SESSION['userData']['nombrefiscal']; ?>" >
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 mb-4">
                  <label>Dirección fiscal</label>
                  <input class="form-control" type="text" id="txtDirFiscal" name="txtDirFiscal" value="<?= $_SESSION['userData']['direccionfiscal']; ?>">
                </div>
              </div>
              <div class="row mb-10">
                <div class="col-md-12">
                  <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Guardar</button>
                </div>
              </div>
            </form>
          </div>
        </div> -->
      </div>
    </div>
  </div>
</main>
<?php adminFooter($data); ?>