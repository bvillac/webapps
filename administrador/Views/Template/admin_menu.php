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
      <?= getGenerarMenu() ?>
    </aside>