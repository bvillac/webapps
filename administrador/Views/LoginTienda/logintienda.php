<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="description" content="<?= DESCRIPCION ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="<?= AUTOR ?>">
  <meta name="theme-color" content="#009688">
  <link rel="shortcut icon" href="<?= media(); ?>/images/site/favicon.ico">
  <!-- Main CSS-->
  <link rel="stylesheet" type="text/css" href="<?= media(); ?>/css/main.css">
  <link rel="stylesheet" type="text/css" href="<?= media(); ?>/css/style.css">

  <title><?= $data['page_name']; ?></title>
</head>

<body>
  <section class="material-half-bg">
    <div class="cover"></div>
  </section>
  <section class="login-content">

    <div class="login-box">

      <form class="login-form" name="frm_Login" id="frm_Login" action="">
        <div class="form-group">
              <label for="cmb_cliente">CLIENTE</label>
              <select class="form-control" id="cmb_cliente" name="cmb_cliente"  >
                <?php
                echo '<option value="0">SELECCIONAR</option>';
                foreach ($data['Cliente'] as $opcion) {
                  $seleted = "";//($opcion['Ids'] == $data['idsEmpresa']) ? 'selected' : '';
                  echo '<option value="' . $opcion['Ids'] . '" ' . $seleted . ' >' . $opcion['Nombre'] . '</option>';
                }
                ?>
              </select>
        </div>
        <div class="form-group">
          <label for="cmb_tienda">TIENDA</label>
          <select class="form-control" id="cmb_tienda" name="cmb_tienda" disabled>
                <?php
                //echo '<option value="0">SELECCIONAR</option>';
                ?>
          </select>
        </div>
        <!-- <div class="form-group">
          <label for="cmb_rol">ROL</label>
          <select class="form-control" id="cmb_rol" name="cmb_rol" disabled>
                <?php
                //echo '<option value="0">SELECCIONAR</option>';
                ?>
          </select>
        </div> -->
        
        
    
        <div id="alertLogin" class="text-center"></div>
        <div class="form-group btn-container">
          <!-- <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-sign-in-alt"></i> INICIAR SESIÓN</button> -->
          <button type="button" id="btn_login" class="btn btn-primary btn-block"><i class="fas fa-sign-in-alt"></i> INICIAR SESIÓN</button>
          <button type="button" id="btn_loginPedido" class="btn btn-danger btn-block"><i class="fa fa-sign-out fa-lg"></i> VOLVER A LOGIN</button>
        </div>
      </form>
    </div>
  </section>
  <script>
    //Ruta Globa Site 
    const base_url = "<?= base_url(); ?>";
  </script>
  <!-- Essential javascripts for application to work-->
  <script src="<?= media(); ?>/js/jquery-3.3.1.min.js"></script>
  <script src="<?= media(); ?>/js/popper.min.js"></script>
  <script src="<?= media(); ?>/js/bootstrap.min.js"></script>
  <script src="<?= media(); ?>/js/fontawesome.js"></script>
  <script src="<?= media(); ?>/js/crypto-js.js"></script>
  <script src="<?= media(); ?>/js/main.js"></script>
  <!-- The javascript plugin to display page loading on top-->
  <script src="<?= media(); ?>/js/plugins/pace.min.js"></script>
  <script type="text/javascript" src="<?= media(); ?>/js/plugins/sweetalert.min.js"></script>
  <?= incluirJs() ?>
</body>

</html>