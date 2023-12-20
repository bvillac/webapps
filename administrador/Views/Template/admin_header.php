<!DOCTYPE html>
<html lang="en">
<?php //session_start(); ?>
<head>
  <meta charset="utf-8">
  <meta name="description" content="<?= DESCRIPCION ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="<?= AUTOR ?>">
  <meta name="theme-color" content="#009688">
  <link rel="shortcut icon" href="<?= media(); ?>/images/site/favicon.ico">
  <title><?= $data['page_name'] ?></title>
  <!-- Calendar plugin-->

  <!-- Main CSS-->
  <link rel="stylesheet" type="text/css" href="<?= media(); ?>/css/main.css">
  <link rel="stylesheet" type="text/css" href="<?= media(); ?>/css/bootstrap-select.min.css">
  <link rel="stylesheet" type="text/css" href="<?= media(); ?>/js/datepicker/jquery-ui.min.css">
  <link rel="stylesheet" type="text/css" href="<?= media(); ?>/css/style.css">
  <!-- Font-icon css-->
  <!-- <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> -->

  <style>
    #spinner {
      display: none;
      /* Agrega estilos para el spinner, por ejemplo, un spinner CSS o un mensaje de carga */
    }
  </style>

</head>

<body class="app sidebar-mini">
  <div id="spinner">Cargando...</div>
  <!-- Navbar-->
  <header class="app-header"><a class="app-header__logo font-weight-bold" href="<?= base_url(); ?>/dashboard"><?= TITULO_EMPRESA ?></a>
    <!-- Sidebar toggle button-->
    <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"><i class="fa fa-bars fa-lg"></i></a>
    <!-- Navbar Right Menu-->
    <ul class="app-nav">

      <!--Notification Menu-->

      <!-- User Menu-->
      <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
        <ul class="dropdown-menu settings-menu dropdown-menu-right">
          <!-- <li><a class="dropdown-item" href="<?= base_url(); ?>/page-user.html"><i class="fa fa-cog fa-lg"></i> Configuración</a></li> -->
          <li><a class="dropdown-item" href="<?= base_url(); ?>/usuarios/perfil"><i class="fa fa-user fa-lg"></i> Perfil</a></li>
          <li><a class="dropdown-item" href="<?= base_url(); ?>/salida"><i class="fa fa-sign-out fa-lg"></i> Salir</a></li>
        </ul>
      </li>
    </ul>
  </header>