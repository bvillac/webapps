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
      
          <img class="loguinInicio" src="<?= media() ?>/logo/logo-miller.jpg" alt="Miller Logo">
   
        <!--<h3 class="login-head"><?= TITULO_EMPRESA ?></h3>-->
        <div class="form-group">
          <label class="control-label">USUARIO</label>
          <input id="txt_Email" name="txt_Email" class="form-control" type="email" placeholder="Email" autofocus>
        </div>
        <div class="form-group">
          <label class="control-label">CLAVE</label>
          <input id="txt_clave" name="txt_clave" class="form-control" type="password" placeholder="Clave">
          <span class="mdi mdi-eye" id="mostrar"> <span class="pwdtxt" style="cursor:pointer;">Mostrar contraseña</span></span>
        </div>
        <div class="form-group">
          <div class="utility">
            <p class="semibold-text mb-2"><a href="#" data-toggle="flip">¿Recuperar tu clave?</a></p>
          </div>
        </div>
        <div id="alertLogin" class="text-center"></div>
        <div class="form-group btn-container">
          <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-sign-in-alt"></i> INICIAR SESIÓN</button>
        </div>
      </form>
      <form id="formRecetPass" name="formRecetPass" class="forget-form" action="">
        <h3 class="login-head"><i class="fa fa-lg fa-fw fa-lock"></i>¿Recuperar clave?</h3>
        <div class="form-group">
          <label class="control-label">EMAIL</label>
          <input id="txt_Email_Reset" name="txt_Email_Reset" class="form-control" type="email" placeholder="Email">
        </div>
        <div class="form-group btn-container">
          <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-unlock fa-lg fa-fw"></i>Recuperar</button>
        </div>
        <div class="form-group mt-3">
          <p class="semibold-text mb-0"><a href="#" data-toggle="flip"><i class="fa fa-angle-left fa-fw"></i> Regresar</a></p>
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
  <script src="<?= media(); ?>/js/main.js"></script>
  <!-- The javascript plugin to display page loading on top-->
  <script src="<?= media(); ?>/js/plugins/pace.min.js"></script>
  <script type="text/javascript" src="<?= media(); ?>/js/plugins/sweetalert.min.js"></script>
  <?= incluirJs() ?>
</body>

</html>