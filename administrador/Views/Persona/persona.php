<?php 
    adminHeader($data);
    adminMenu($data); 
    //filelang(Setlanguage,"general") 
    getModal('modalPersona',$data);
?>
    <div id="contentAjax"></div> 
    <main class="app-content">
      <div class="app-title">
        <div>
            <h1><i class="fas fa-user-tag"></i> <?= $data['page_title'] ?>
            <?php if($_SESSION['permisosMod']['w']){ ?>
                <button class="btn btn-primary" type="button" onclick="openModal();" ><i class="fa fa-plus"></i> Nuevo</button>
            <?php } ?> 
            </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="<?= base_url(); ?>/personas"><?= $data['page_title'] ?></a></li>
        </ul>
      </div>

        <div class="row">
            <div class="col-md-12">
              <div class="tile">
                <div class="tile-body">
                  <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="tablePersona">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Cédula</th>
                          <th>Nombre</th>
                          <th>Apellido</th>
                          <th>Fecha de Nacimiento</th>
                          <th>Teléfono</th>
                          <th>Dirección</th>
                          <th>Género</th>
                          <th>Estado</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </main>
<?php adminFooter($data); ?>
    