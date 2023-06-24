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
        <?php if (!empty($_SESSION['permisos'][1]['r'])) { ?>
          <li>
            <a class="app-menu__item" href="<?= base_url(); ?>/dashboard">
              <i class="app-menu__icon fa fa-dashboard"></i>
              <span class="app-menu__label">Dashboard</span>
            </a>
          </li>
        <?php } ?>
        <?php if (!empty($_SESSION['permisos'][2]['r'])) { ?>
          <li class="treeview">
            <a class="app-menu__item" href="#" data-toggle="treeview">
              <i class="app-menu__icon fa fa-cogs"></i>
              <span class="app-menu__label">General</span><i class="treeview-indicator fa fa-angle-right">
              </i>
            </a>
            <ul class="treeview-menu">
              <li><a class="treeview-item" href="<?= base_url(); ?>/moneda"><i class="icon fa fa-circle-o"></i> Moneda</a></li>
              <li><a class="treeview-item" href="<?= base_url(); ?>/pago"><i class="icon fa fa-circle-o"></i> Forma de Pago</a></li>
              <li><a class="treeview-item" href="<?= base_url(); ?>/secuencias"><i class="icon fa fa-circle-o"></i> Secuencias</a></li>
              <li><a class="treeview-item" href="<?= base_url(); ?>/modulo"><i class="icon fa fa-circle-o"></i> Módulo</a></li>


            </ul>
          </li>
        <?php } ?>
        <?php if (!empty($_SESSION['permisos'][3]['r'])) { ?>
          <li class="treeview">
            <a class="app-menu__item" href="#" data-toggle="treeview">
              <i class="app-menu__icon fa fa-users"></i>
              <span class="app-menu__label">Usuarios</span><i class="treeview-indicator fa fa-angle-right">
              </i>
            </a>
            <ul class="treeview-menu">
              <li><a class="treeview-item" href="<?= base_url(); ?>/usuarios"><i class="icon fa fa-circle-o"></i> Usuarios</a></li>
              <li><a class="treeview-item" href="<?= base_url(); ?>/roles"><i class="icon fa fa-circle-o"></i> Roles</a></li>
            </ul>
          </li>
        <?php } ?>
        <?php if (!empty($_SESSION['permisos'][4]['r'])) { ?>
          <?php //if(!empty($_SESSION['permisos'][4]['r']) || !empty($_SESSION['permisos'][x]['r'])){ 
          ?>
          <li class="treeview">
            <a class="app-menu__item" href="#" data-toggle="treeview">
              <i class="app-menu__icon fa fa-th-list"></i>
              <span class="app-menu__label">Almacenamiento</span><i class="treeview-indicator fa fa-angle-right">
              </i>
            </a>
            <ul class="treeview-menu">
              <li><a class="treeview-item" href="<?= base_url(); ?>/items"><i class="icon fa fa-circle-o"></i> Items (Productos)</a></li>
              <li><a class="treeview-item" href="<?= base_url(); ?>/linea"><i class="icon fa fa-circle-o"></i> Línea</a></li>
              <li><a class="treeview-item" href="<?= base_url(); ?>/TipoItem/tipoitem"><i class="icon fa fa-circle-o"></i> Tipo</a></li>
              <li><a class="treeview-item" href="<?= base_url(); ?>/marca"><i class="icon fa fa-circle-o"></i> Marca</a></li>
              <li><a class="treeview-item" href="<?= base_url(); ?>/UnidadMedida/unidadmedida"><i class="icon fa fa-circle-o"></i> Unidad de Medida</a></li>
              <li><a class="treeview-item" href="<?= base_url(); ?>/bodega"><i class="icon fa fa-circle-o"></i> Bodega</a>
                <!-- <li><a class="treeview-item" href="<?= base_url(); ?>/itembodega"><i class="icon fa fa-circle-o"></i> Stock</a></li>           -->
              <li><a class="treeview-item" href="<?= base_url(); ?>/movimiento"><i class="icon fa fa-circle-o"></i> Movimiento</a></li>
              <!-- <li><a class="treeview-item" href="<?= base_url(); ?>/ingreso"><i class="icon fa fa-circle-o"></i> Ingresos</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>/egreso"><i class="icon fa fa-circle-o"></i> Egresos/Transferencias</a></li> -->
            </ul>
          </li>
        <?php } ?>
        <?php if (!empty($_SESSION['permisos'][5]['r'])) { ?>
          <li class="treeview">
            <a class="app-menu__item" href="#" data-toggle="treeview">
              <i class="app-menu__icon fa fa-th-list"></i>
              <span class="app-menu__label">Adquisiciones</span><i class="treeview-indicator fa fa-angle-right">
              </i>
            </a>
            <ul class="treeview-menu">
              <li><a class="treeview-item" href="<?= base_url(); ?>/proveedor"><i class="icon fa fa-circle-o"></i> Proveedores</a></li>
              <li><a class="treeview-item" href="<?= base_url(); ?>/orden"><i class="icon fa fa-circle-o"></i> Orden de Pedido</a></li>
              <li><a class="treeview-item" href="<?= base_url(); ?>/Compra/ordenescompra"><i class="icon fa fa-circle-o"></i> Orden de Compra</a></li>
              <li><a class="treeview-item" href="<?= base_url(); ?>/compra"><i class="icon fa fa-circle-o"></i> Compras</a></li>

            </ul>
          </li>
        <?php } ?>
        <?php if (!empty($_SESSION['permisos'][6]['r'])) { ?>
          <li class="treeview">
            <a class="app-menu__item" href="#" data-toggle="treeview">
              <i class="app-menu__icon fa fa-th-list"></i>
              <span class="app-menu__label">Ventas</span><i class="treeview-indicator fa fa-angle-right">
              </i>
            </a>
            <ul class="treeview-menu">
              <li><a class="treeview-item" href="<?= base_url(); ?>/cliente"><i class="icon fa fa-circle-o"></i> Clientes</a></li>
              <li><a class="treeview-item" href="<?= base_url(); ?>/Venta/despacho"><i class="icon fa fa-circle-o"></i> Despachos</a></li>
              <li><a class="treeview-item" href="<?= base_url(); ?>/venta"><i class="icon fa fa-circle-o"></i> Facturación</a></li>
            </ul>
          </li>
        <?php } ?>
        <?php if (!empty($_SESSION['permisos'][7]['r'])) { ?>
          <li class="treeview">
            <a class="app-menu__item" href="#" data-toggle="treeview">
              <i class="app-menu__icon fa fa-th-list"></i>
              <span class="app-menu__label">Empresa</span><i class="treeview-indicator fa fa-angle-right">
              </i>
            </a>
            <ul class="treeview-menu">
              <li><a class="treeview-item" href="<?= base_url(); ?>/empresa"><i class="icon fa fa-circle-o"></i> Empresa</a></li>
              <li><a class="treeview-item" href="<?= base_url(); ?>/moneda"><i class="icon fa fa-circle-o"></i> Moneda</a></li>
              <li><a class="treeview-item" href="<?= base_url(); ?>/establecimiento"><i class="icon fa fa-circle-o"></i> Establecimiento</a></li>
              <li><a class="treeview-item" href="<?= base_url(); ?>/punto"><i class="icon fa fa-circle-o"></i> Punto Emisión</a></li>


            </ul>
          </li>
        <?php } ?>
        <!-- <li><a class="app-menu__item" href="<?= base_url(); ?>/clientes"><i class="app-menu__icon fa fa-user"></i><span class="app-menu__label">Clientes</span></a></li>
        <li><a class="app-menu__item" href="<?= base_url(); ?>/productos"><i class="app-menu__icon fa fa-archive"></i><span class="app-menu__label">Procudtos</span></a></li> -->
        <li><a class="app-menu__item" href="<?= base_url(); ?>/salida"><i class="app-menu__icon fa fa-sign-out"></i><span class="app-menu__label">Salir</span></a></li>
      </ul>


      <ul class="app-menu">
        <li class="treeview">
          <a class="app-menu__item" href="#" data-toggle="treeview">
            <i class="app-menu__icon fa fa-cogs"></i>
            <span class="app-menu__label">General</span><i class="treeview-indicator fa fa-angle-right">
            </i>
          </a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" href="<?= base_url(); ?>/moneda"><i class="icon fa fa-circle-o"></i> Moneda</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>/pago"><i class="icon fa fa-circle-o"></i> Forma de Pago</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>/secuencias"><i class="icon fa fa-circle-o"></i> Secuencias</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>/modulo"><i class="icon fa fa-circle-o"></i> Módulo</a></li>
          </ul>
        </li>

      </ul>

      <ul class="app-menu">
        <li class="treeview">
          <a class="app-menu__item" href="#" data-toggle="treeview">
            <i class="app-menu__icon fa fa-cogs"></i>
            <span class="app-menu__label">Cartera</span><i class="treeview-indicator fa fa-angle-right">
            </i>

          </a>
          <ul>
            <li class="treeview">
              <a class="app-menu__item" href="#" data-toggle="treeview">
                <i class="app-menu__icon fa fa-cogs"></i>
                <span class="app-menu__label">Gastos</span><i class="treeview-indicator fa fa-angle-right">
                </i>
              </a>
              <ul class="treeview-menu">
                <li><a class="treeview-item" href="<?= base_url(); ?>/moneda"><i class="icon fa fa-circle-o"></i> Moneda1</a></li>
                <li><a class="treeview-item" href="<?= base_url(); ?>/pago"><i class="icon fa fa-circle-o"></i> Forma de Pago2</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a class="app-menu__item" href="#" data-toggle="treeview">
                <i class="app-menu__icon fa fa-cogs"></i>
                <span class="app-menu__label">Gastos2</span><i class="treeview-indicator fa fa-angle-right">
                </i>
              </a>
              <ul class="treeview-menu">
                <li><a class="treeview-item" href="<?= base_url(); ?>/moneda"><i class="icon fa fa-circle-o"></i> Moneda</a></li>
                <li><a class="treeview-item" href="<?= base_url(); ?>/pago"><i class="icon fa fa-circle-o"></i> Forma de Pago</a></li>
              </ul>
            </li>
          </ul>

        </li>

      </ul>









    </aside>