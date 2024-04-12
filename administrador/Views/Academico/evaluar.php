<?php
adminHeader($data);
adminMenu($data);
//filelang(Setlanguage,"general") 
require_once "Views/Academico/Modals/modalAcademico.php";
//putMessageLogFile($data);
$controlAcad = $data['control'];
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
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>/<?= $data['page_back'] ?>"><?= $data['page_title'] ?></a></li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-3">

      <div class="card card-primary card-outline">
        <div class="card-body box-profile">
          <div class="text-center">
            <!--<img class="profile-user-img img-fluid img-circle" src="../../dist/img/user4-128x128.jpg" alt="User profile picture">-->
            <img class="app-sidebar__user-avatar" src="<?= media() ?>/images/fotoAdmin.jpg" alt="Perfil Usuario">
          </div>
          <h3 class="profile-username text-center"><?= $data['Nombres'] ?></h3>
          <p class="text-muted text-center"><?= $data['Tipo'] ?></p>

          <!--<a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>-->
        </div>

      </div>


      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Información</h3>
        </div>

        <div class="card-body">
          <strong><!--<i class="fas fa-book mr-1"></i>--> Contrato</strong>
          <ul class="text-muted list-group list-group-unbordered mb-3">
            <li class="list-group-item">
              <b>Número</b> <a class="float-right"><?= $data['Contrato'] ?></a>
            </li>
            <li class="list-group-item">
              <b>Fecha Ingreso</b> <a class="float-right"><?= $data['FechaIngreso'] ?></a>
            </li>
          </ul>
          <!--<hr>-->
          <strong><i class="fas fa-map-marker-alt mr-1"></i> Personal</strong>
          <ul class="text-muted list-group list-group-unbordered mb-3">
            <li class="list-group-item">
              <b>Fecha Nacimiento</b> <a class="float-right"><?= $data['FechaNac'] ?></a>
            </li>
            <li class="list-group-item">
              <b>Dirección</b> <a class="float-right"><?= $data['Direccion'] ?></a>
            </li>
            <li class="list-group-item">
              <b>Teléfono</b> <a class="float-right"><?= $data['Telefono'] ?></a>
            </li>
          </ul>

          <!--<strong><i class="fas fa-pencil-alt mr-1"></i> Skills</strong>
          <p class="text-muted">
            <span class="tag tag-danger">UI Design</span>
            <span class="tag tag-success">Coding</span>
            <span class="tag tag-info">Javascript</span>
            <span class="tag tag-warning">PHP</span>
            <span class="tag tag-primary">Node.js</span>
          </p>
          <hr>-->
          <strong><i class="far fa-file-alt mr-1"></i> Observación</strong>
          <p class="text-muted">No tiene Observaciones</p>
        </div>

      </div>

    </div>

    <div class="col-md-9">
      <div class="card">
        <div class="card-header p-2">
          <h3>CONTROL ACADEMICO</h3>
        </div>
        <div class="card-body">
          <div class="tab-content">

          <div class="table-responsive">
          <table id="dts_Control" class="table table-hover table-striped" >
              <thead>
                <tr>
                  <th>NIVEL</th>
                  <th>UNIDAD</th>
                  <th>ACTIVIDAD</th>
                  <th>HORA</th>
                  <th>TUTOR</th>
                  <th>F.ASISTENCÍA</th>
                  <th>F.EVALUACIÓN</th>
                  <th>VALORACION</th>
                  <th>VALOR</th>                  
                  <th></th>
                </tr>
              </thead>
              <tbody>
              <?php
                  foreach ($controlAcad as $control) {
                    $bloqueo=($control['Valoracion']!="")?"disabled":"";                    
                    $valorPor=($control['Valor']!="")? $control['Valor'] ." %":"";
                    $observacion='<a class="btn btn-primary  btn-sm" href="#" ><i class="fa fa-comments"></i></a>';
                    //$valorPor=($control['Valor']!="")? "<span class='badge bg-primary'>".$control['Valor'] ." %</span>":"";
                  ?>
                    <tr>
                      <td class="text-left"><?= $control['Nivel'] ?></td>
                      <td class="text-center"><?= $control['Unidad'] ?></td>
                      <td class="text-left"><?= $control['Actividad'] ?></td>
                      <td class="text-center"><?= $control['Hora'] ?>:00</td>
                      <td class="text-left"><?= $control['Instructor'] ?></td>
                      <td class="text-center"><?= $control['FechaAsistencia'] ?></td>
                      <td class="text-center"><?= $control['FechaEvaluacion'] ?></td>
                      <td class="text-left"><?= $control['Valoracion'] ?></td>
                      <td class="text-center"><?= $valorPor ?></td>                   
                      <td class="text-center">
                        <button class="btn btn-primary  btn-sm" onClick="evaluarModals('<?= $control['Ids'] ?>')" <?= $bloqueo ?> 
                          title="Evaluar Datos"><i class="fa fa-pencil-square-o"></i>
                        </button>
                      <?= $observacion ?>
                      </td>
                    </tr>
                  <?php } ?>
              </tbody>
            </table>
          </div>
           

          </div>
        </div>
      </div>

    </div>

  </div>





</main>
<?php adminFooter($data); ?>