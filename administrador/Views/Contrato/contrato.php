<?php
adminHeader($data);
adminMenu($data);
//filelang(Setlanguage,"general") 
//getModal('modalInstructor', $data);
?>
<div id="contentAjax"></div>
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fas fa-user-tag"></i> <?= $data['page_title'] ?>
                <?php if ($_SESSION['permisosMod']['w']) {
                ?>
                    <button class="btn btn-primary" type="button" id="btn_nuevo"><i class="fa fa-plus"></i> Nuevo</button>
                <?php }
                ?>
            </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>/contrato"><?= $data['page_title'] ?></a></li>
        </ul>
    </div>



    <div class="row">

    
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="tableContrato">
                            <thead>
                                <tr>
                                    <th>Número</th>
                                    <th>F.Inicio</th>
                                    <th>Razón Social</th>
                                    <th>Total</th>
                                    <th>C.Inicial</th>
                                    <th>Saldo</th>
                                    <th>N.Pagos</th>
                                    <th>C.Mensual</th>
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