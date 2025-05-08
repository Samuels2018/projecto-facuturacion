<nav id="sidebar">
    <div class="navbar-nav theme-brand flex-row text-center">
        <div class="nav-logo">
            <div class="nav-item theme-logo">
                <a href="<?php echo ENLACE_WEB_CUENTAS; ?>">
                    <img style="min-width: 175px;
    min-height: 65px;" alt="avatar" src="<?php echo ENLACE_WEB;  ?>/bootstrap/img/logo-factuguay-2.gif" class="rounded-circle">
                </a>
            </div>
            <div class="nav-item theme-text">
                <a href="<?php echo ENLACE_WEB_CUENTAS; ?>" class="nav-link"> <?php //echo ($Entidad->nombre_fantasia); ?> </a>
            </div>
        </div>
        <div class="nav-item sidebar-toggle">
            <div class="btn-toggle sidebarCollapse">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left">
                    <polyline points="11 17 6 12 11 7"></polyline>
                    <polyline points="18 17 13 12 18 7"></polyline>
                </svg>
            </div>
        </div>
    </div>
    <div class="profile-info">
        <div class="user-info">
            <div class="profile-img">
                <img alt="avatar" src="<?php echo $Usuarios->verificar_url_avatar_path('',$datos_empresa['nombre']); ?>" class="rounded-circle">
            </div>
            <div class="profile-content">
                <h6 class="" title="<?php echo $datos_empresa['nombre']; ?>"><?php echo $datos_empresa['nombre']; ?></h6>
                <p class=""><?php //echo ($Entidad->nombre_fantasia); ?></p>
            </div>
        </div>
    </div>

    <div class="shadow-bottom"></div>
    
    <ul class="list-unstyled menu-categories" id="accordionExample">
        <li class="menu facturacion">
        <a href="<?php echo ENLACE_WEB_CUENTAS ?>kit_digital_listado" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                    <span>Licencias</span>
                </div>
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </a>
        </li>

        <li class="menu salir">
            <a href="<?php echo ENLACE_WEB_CUENTAS ?>salir" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <span>Salir</span>
                </div>
            </a>
        </li>
    </ul>
</nav>
