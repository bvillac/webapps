<?php 
    adminHeader($data);
    adminMenu($data);  
     //filelang(Setlanguage,"general") 
    //getModal("Views/Usuarios/Modals/",'modalUsuarios',$data);
    require_once "Views/Usuarios/Modals/modalUsuarios.php"; 
?>
    <main class="app-content">
      <div class="app-title">
        <div>
            <h1><i class="fas fa-user-tag"></i> <?= $data['page_title'] ?>
              <?php //if($_SESSION['permisosMod']['w']){ ?>
                <button class="btn btn-primary" type="button" onclick="openModal();" ><i class="fa fa-plus"></i> Nuevo</button>
              <?php //} ?>
         
   
            </h1>
        </div>
     
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="<?= base_url(); ?>/usuarios"><?= $data['page_title'] ?></a></li>
        </ul>
      </div>
        <div class="row">
            <div class="col-md-12">
              <div class="tile">
                <div class="tile-body">
                  <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="tableUsuarios">
                      <thead>
                        <tr>
                          <th>Identificación Dni</th>
                          <th>Nombres</th>
                          <th>Apellidos</th>
                          <th>Correo Electrónico</th>
                          <!--<th>Nombre Rol</th>-->
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
    