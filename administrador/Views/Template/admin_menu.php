    <!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
      <div class="app-sidebar__user">
        <img class="app-sidebar__user-avatar" src="<?= media() ?>/images/fotoAdmin.jpg" alt="User Image">
        <div>
          <p class="app-sidebar__user-name"><?= $_SESSION['usuarioData']['Nombres']; ?></p>
          <p class="app-sidebar__user-designation"><?= $_SESSION['usuarioData']['Rol']; ?></p>
        </div>
      </div>

      <ul class="app-menu">
        <li>
          <a class="app-menu__item" href="<?= base_url(); ?>/dashboard">
            <i class="app-menu__icon fa fa-dashboard"></i>
            <span class="app-menu__label">Dashboard</span>
          </a>
        </li>
        <li class="treeview">
          <a class="app-menu__item" href="#" data-toggle="treeview">
            <i class="app-menu__icon fa fa-users"></i>
            <span class="app-menu__label">Usuarios</span><i class="treeview-indicator fa fa-angle-right">
            </i>
          </a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" href="<?= base_url(); ?>/usuarios"><i class="icon fa fa-circle-o"></i> Usuarios</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>/roles"><i class="icon fa fa-circle-o"></i> Roles</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>/dashboard"><i class="icon fa fa-circle-o"></i> Instructores</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>/dashboard"><i class="icon fa fa-circle-o"></i> Beneficiarios</a></li>
          </ul>
        </li>

        <li><a class="app-menu__item" href="<?= base_url(); ?>/salida"><i class="app-menu__icon fa fa-sign-out"></i><span class="app-menu__label">Salir</span></a></li>
      </ul>
      <!--getGenerarMenu();-->
    </aside>