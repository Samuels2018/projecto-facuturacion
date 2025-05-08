<nav id="sidebar">
    <div class="navbar-nav theme-brand flex-row  text-center">
        <div class="nav-logo">
            <div class="nav-item theme-logo">
                <a href="<?php echo ENLACE_WEB_ERRORES; ?>">
      
                    
                </a>
            </div>
            <div class="nav-item theme-text">
                <a href="<?php echo ENLACE_WEB_ERRORES; ?>" class="nav-link"> <?php echo 'Administrador'; ?> </a>
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
               
            </div>
            <div class="profile-content">
                <h6 class=""><?php echo $_SESSION['usuario_txt']; ?></h6>
                <p class=""><?php echo 'Administrador'; ?></p>
            </div>
        </div>
    </div>

    <div class="shadow-bottom"></div>
    <ul class="list-unstyled menu-categories" id="accordionExample">
        <li class="menu dashboard" id="dashboard">
            <a href="<?= ENLACE_WEB_ERRORES ?>" class="dropdown-toggle">
                <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    <span>Dashboard</span>
                </div>
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </a>
        </li>

        <li class="menu menu-heading">
            <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg><span>Aplicaciones</span></div>
        </li>


        <?php if ($Entidad->modulo_activo[10]) {
            $funnels = $Oportunidad->obtener_listado_funnels();
         ?>
<!-- CRM -->
 
 <?php 
    if(count($funnels)>0 && count($funnels)<=1)
    {
        $Oportunidad->funnel_por_defecto();
        $enlace = ENLACE_WEB_ERRORES.'funnel/'.$Oportunidad->fk_funnel;
 ?>
<li class="menu canvan"> 
                        <a href="<?php echo $enlace; ?>" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            <span>Canvan</span>
                            </div>
                        </a>
</li>
<?php  }else{ ?>

<!-- SUBMENU DEL CANVAN-->

<li class="menu canvan">
        <a href="#menu-canvan" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            <div class="">
                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            <span>Canvan</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>
        <ul class="submenu list-unstyled collapse" id="menu-canvan" data-bs-parent="#accordionExample" style="">
        <?php 
            foreach($funnels as $key => $value){
        ?>
            <li>
                <a href="<?= ENLACE_WEB_ERRORES ?>funnel/<?php echo $funnels[$key]->rowid; ?>"><?php echo $funnels[$key]->titulo; ?></a>
            </li>
        <?php } ?>
        </ul>
</li>




<?php } ?>

<li class="menu crm">
                        <a href="<?php echo ENLACE_WEB_ERRORES; ?>agenda" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg><span>Agenda</span>
                            </div>
                        </a>
</li>


<li class="menu mod_crm">
        <a href="#submenu_mod_crm" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">
            <div class="">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                <span>CRM</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>
        <ul class="submenu list-unstyled collapse" id="submenu_mod_crm" data-bs-parent="#accordionExample" style="">
            <li id="listado_oportunidades">
            <a href="<?= ENLACE_WEB_ERRORES ?>oportunidades">Listado Oportunidades</a>
            </li>
            <li id="nueva_oportunidad">
                <a href="<?= ENLACE_WEB_ERRORES ?>nueva_oportunidad">Nueva oportunidad</a>
            </li>

            <li id="actividades">
                <a href="<?= ENLACE_WEB_ERRORES ?>actividades">Actividades</a>
            </li>

            <li id="mis_pendientes">
                <a href="<?= ENLACE_WEB_ERRORES ?>mis_pendientes/1"><span>Mis Actividades<span>
                <span class="badge badge-primary sidebar-label conteo ocultar"> </span>

                </a>
            </li> 
            
         
        
        </ul>
    </li>
    <!-- Fin Funnel -->
 

<?php } // 10  ?>




        <?php if ($Entidad->modulo_activo[1]) { ?> 
    <li class="menu facturacion">
        <a href="#submenu_facturacion" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
                <span>Facturación</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>
        <ul class="submenu list-unstyled collapse" id="submenu_facturacion" data-bs-parent="#accordionExample" style="">
            <li id="facturacion">
                <a href="<?= ENLACE_WEB_ERRORES ?>factura_listado"> Listado de facturas</a>
            </li>
            <li id="nueva_factura">
                <a href="<?= ENLACE_WEB_ERRORES ?>nueva_factura"> Nueva Factura</a>
            </li>
        </ul>
    </li>
<?php } // fin del if del permiso 1 ?>

<?php if ($Entidad->modulo_activo[2]) { ?>
    <!-- Albaranes -->
    <li class="menu albaranes">
        <a href="#submenu_albaranes" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book-open">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
                <span>Albaranes</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>
        <ul class="submenu list-unstyled collapse" id="submenu_albaranes" data-bs-parent="#accordionExample" style="">
            <li id="listado_albaranes">
                <a href="<?php echo ENLACE_WEB_ERRORES ?>albaranes_listado"> Listado de albaranes</a>
            </li>
            <li id="nueva_albaran">
                <a href="<?php echo ENLACE_WEB_ERRORES ?>nuevo_albaran"> Nuevo Albaran</a>
            </li>
        </ul>
    </li>
<?php } // fin del if del permiso 2 ?>

<?php if ($Entidad->modulo_activo[3]) { ?>
    <li class="menu listado_cotizaciones">
        <a href="#submenu_cotizacion" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <span>Cotizaciones</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>
        <ul class="submenu list-unstyled collapse" id="submenu_cotizacion" data-bs-parent="#accordionExample" style="">
            <li id="listado_cotizaciones">
                <a href="<?= ENLACE_WEB_ERRORES ?>listado_cotizaciones"> Listado de Cotizaciones</a>
            </li>
            <li id="nueva_cotizacion">
                <a href="<?= ENLACE_WEB_ERRORES ?>nueva_cotizacion"> Nueva Cotizacion</a>
            </li>
        </ul>
    </li>
<?php } // fin del if del permiso 3 ?>


<?php if ($Entidad->modulo_activo[5]) { ?>
    <li class="menu clientes">
        <a href="<?php echo ENLACE_WEB_ERRORES ?>clientes_listado" aria-expanded="false" class="dropdown-toggle">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <span>Clientes</span>
            </div>
        </a>
    </li>
<?php } // fin del if del permiso 5 ?>



<li class="menu reportes">
        <a href="#submenu_reportes" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">
            <div class="">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book-open">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
                <span>Errores</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>
        <ul class="submenu list-unstyled collapse" id="submenu_reportes" data-bs-parent="#accordionExample">
            <li id="reportes_iva">
                <a href="<?= ENLACE_WEB_ERRORES ?>reportes_iva"> Ver listado errores</a>
            </li>
            
        </ul>
    </li>


<?php if ($Entidad->modulo_activo[9]) { ?>
    <li class="menu contactos">
        <a href="<?php echo ENLACE_WEB_ERRORES ?>contactos_crm_listado" aria-expanded="false" class="dropdown-toggle">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone-call">
                    <path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                </svg>
                <span>Contactos</span>
            </div>
        </a>
    </li>
<?php } // fin del if del permiso 9 ?>


<?php if ($Entidad->modulo_activo[4]) { ?>
    <li class="menu productos">
        <a href="#submenu_productos" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
                <span>Articulos & Servicios</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>
        <ul class="submenu list-unstyled collapse" id="submenu_productos" data-bs-parent="#accordionExample" style="">
            <li id="productos_listado">
                <a href="<?= ENLACE_WEB_ERRORES ?>productos_listado"> Listado de articulos</a>
            </li>
            <li id="productos_nuevo">
                <a href="<?= ENLACE_WEB_ERRORES ?>productos_nuevo"> Crear articulo</a>
            </li>
        </ul>
    </li>
<?php } // fin del if del permiso 4 ?>

<?php if ($Entidad->modulo_activo[12]) { ?>
    <!-- Redhouse Cotizaciones -->
    <li class="menu redhouse_cotizaciones">
        <a href="#submenu_redhouse_cotizaciones" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book-open">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
                <span>RedHouse Cotizaciones</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>
        <ul class="submenu list-unstyled collapse" id="submenu_redhouse_cotizaciones" data-bs-parent="#accordionExample" style="">
            <li id="listado_albaranes">
                <a href="<?php echo ENLACE_WEB_ERRORES ?>redhouse_cotizaciones"> Listado de Cotizaciones</a>
            </li>
            <li id="nueva_albaran">
                <a href="<?php echo ENLACE_WEB_ERRORES ?>redhouse_cotizaciones_nueva"> Nueva Cotización</a>
            </li>
        </ul>
    </li>
    <!--Redhouse Proyectos--> 
    <li class="menu redhouse_proyectos">
        <a href="#submenu_redhouse_proyectos" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book-open">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
                <span>RedHouse Proyectos</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>
        <ul class="submenu list-unstyled collapse" id="submenu_redhouse_proyectos" data-bs-parent="#accordionExample" style="">
            <li id="listado_albaranes">
                <a href="<?php echo ENLACE_WEB_ERRORES ?>redhouse_proyectos"> Listado de Proyectos</a>
            </li>
        </ul>
    </li>


    <!--Redhouse Ordenes de compra--> 
    <li class="menu redhouse_ordenes_compra">
        <a href="#submenu_redhouse_ordenes_compra" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book-open">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
                <span>RedHouse Ordenes de compra</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>
        <ul class="submenu list-unstyled collapse" id="submenu_redhouse_ordenes_compra" data-bs-parent="#accordionExample" style="">
            <li id="listado_ordenes_compra">
                <a href="<?php echo ENLACE_WEB_ERRORES ?>redhouse_ordenes_compra_nuevo">Nueva Orden</a>
            </li>
            <li id="listado_ordenes_compra">
                <a href="<?php echo ENLACE_WEB_ERRORES ?>redhouse_ordenes_compra">Listado</a>
            </li>
        </ul>
    </li>

<?php } // fin del if del permiso 12 ?>




<?php if ($Entidad->modulo_activo[6]) { ?>
    <!--
    <li class="menu cisma_cotizaciones">
        <a href="#submenu_cisma_cotizaciones" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book-open">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
                <span>Cisma Cotizaciones</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>
        <ul class="submenu list-unstyled collapse" id="submenu_cisma_cotizaciones" data-bs-parent="#accordionExample" style="">
            <li id="listado_albaranes">
                <a href="<?php echo ENLACE_WEB_ERRORES ?>cisma_cotizaciones"> Listado de Cotizaciones</a>
            </li>
            <li id="nueva_albaran">
                <a href="<?php echo ENLACE_WEB_ERRORES ?>cisma_cotizaciones_nueva"> Nueva Cotización</a>
            </li>
        </ul>
    </li-->
<?php } // fin del if del permiso 6 ?>


<?php if ($Entidad->modulo_activo[7]) { ?>
    <!-- >
    <li class="menu configuracion_cisma">
        <a href="#submenu_configuracion_cisma" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
                <span>Cisma Configuracion</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>

        <ul class="submenu list-unstyled collapse" id="submenu_configuracion_cisma" data-bs-parent="#accordionExample" style="">
            <li>
                <a href="<?= ENLACE_WEB_ERRORES ?>cisma_atestados">Atestados</a>
            </li>
            <li>
                <a href="<?= ENLACE_WEB_ERRORES ?>cisma_anexos_pdf">Anexos Pdfs</a>
            </li>
            <li>
                <a href="<?= ENLACE_WEB_ERRORES ?>cisma_categorias_cotizaciones"> Categorias Cotizaciones</a>
            </li>
            <li>
                <a href="<?= ENLACE_WEB_ERRORES ?>cisma_diccionario_actividades"> Diccionario Actividades</a>
            </li>
        </ul>
    </li-->
<?php } // fin del if del permiso 7 ?>

<?php if ($Entidad->modulo_activo[8]) { ?>
    <li class="menu proveedores">
        <a href="<?php echo ENLACE_WEB_ERRORES ?>proveedores_listado" aria-expanded="false" class="dropdown-toggle">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers">
                    <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                    <polyline points="2 17 12 22 22 17"></polyline>
                    <polyline points="2 12 12 17 22 12"></polyline>
                </svg>
                <span>Proveedores</span>
            </div>
        </a>
    </li>
<?php } // fin del if del permiso 8 ?>




<?php if ($Entidad->modulo_activo[10]) { ?>
    <!-- Funnel 
    <li class="menu crm_configuracion">
        <a href="#submenu_menu-oportunidades" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
                <span>CRM Configuracion</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>
        <ul class="submenu list-unstyled collapse" id="submenu_menu-oportunidades" data-bs-parent="#accordionExample" style="">
           
            <li>
                <a  href="<?= ENLACE_WEB_ERRORES ?>funnel_listado">Funnels</a>
            </li>
            
        </ul>
    </li>
-->
<?php } // fin del if del permiso 10 ?>

<?php if ($Entidad->modulo_activo[11]) { ?>
    <li class="menu configuracion">
        <a href="#submenu_configuracion" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">
            <div class="">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
                <span>Configuracion CRM</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>
        <ul class="submenu list-unstyled collapse" id="submenu_configuracion" data-bs-parent="#accordionExample" style="">
            <li id="agentes_listado">
                <a href="<?= ENLACE_WEB_ERRORES ?>agentes_listado"> Agentes</a>
            </li>
            <li id="agentes_nuevo">
                <a href="<?= ENLACE_WEB_ERRORES ?>agentes_nuevo">Crear agente</a>
            </li>
                
        
            <li id="funnel_listado">
                <a href="<?= ENLACE_WEB_ERRORES ?>funnel_listado">Funnels</a>
            </li>


            <?php if( $Entidad->sincronizaciones[1] ): ?>
                <li id="configuracion_quickbooks">
                    <a href="<?= ENLACE_WEB_ERRORES ?>configuracion_quickbooks">Configuracion quickbooks</a>
                </li>
            <?php endif; ?>
        </ul>
    </li>


    
    <li class="menu configuracion_general">
        <a href="#submenu_configuracion_general" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">
            <div class="">
           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-configuracion">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <span>Configuración</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>
        <ul class="submenu list-unstyled collapse" id="submenu_configuracion_general" data-bs-parent="#accordionExample">


        <li id="formas_pago">
                <a href="<?= ENLACE_WEB_ERRORES ?>formas_pago">Forma de pago</a>
            </li>


        <li id="categorias_producto_listado">
                <a href="<?= ENLACE_WEB_ERRORES ?>categorias_producto_listado">Categorias productos</a>
            </li>

            <li id="cliente_categorias">
                <a href="<?= ENLACE_WEB_ERRORES ?>cliente_categorias">Categorias Clientes</a>
            </li>
            <!-- 20241030: Ivan Tapia -->
            <li id="direcciones">
                <a href="<?= ENLACE_WEB_ERRORES ?>direcciones">Direcciones</a>
            </li>
            <!-- 20241030: Ivan Tapia -->
            <li id="prioridades_listado">
                <a href="<?= ENLACE_WEB_ERRORES ?>prioridades_listado">Diccionario Prioridades</a>
            </li>

            <li id="monedas_listado">
                <a href="<?= ENLACE_WEB_ERRORES ?>monedas_listado">Diccionario Moneda</a>
            </li>


            <li id="catalogo_listado">
                <a href="<?= ENLACE_WEB_ERRORES ?>catalogo_listado">Unidad medida</a>
            </li>



            <li id="bancos_listado">
                <a href="<?= ENLACE_WEB_ERRORES ?>bancos_listado">Diccionario de Bancos</a>
            </li>

            
          

            <li id="configuracion_parametros">
                <a href="<?= ENLACE_WEB_ERRORES ?>configuracion_parametros">Configuración Parametros</a>
            </li>

       

            
            <li id="configuracion_parametros">
                <a href="<?= ENLACE_WEB_ERRORES ?>dashboard.php?accion=configuracion_series"> <i class="fas fa-list" aria-hidden="true"></i> Configuración Series</a>
            </li>





      

         
        </ul>
    </li>


     <li class="menu configuracion">
        <a href="#submenu_usuarios" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">
            <div class="">
           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <span>Usuarios</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>
        <ul class="submenu list-unstyled collapse" id="submenu_usuarios" data-bs-parent="#accordionExample" style="">

            <li id="usuarios_listado">
                <a href="<?= ENLACE_WEB_ERRORES ?>usuarios_listado">Lista de usuarios</a>
            </li>

            <li id="perfiles_listado">
                <a href="<?= ENLACE_WEB_ERRORES ?>perfiles_listado">Perfiles</a>
            </li>

        </ul>
    </li>
<?php } // fin del if del permiso 11 ?>


        <li class="menu salir">
            <a href="<?php echo ENLACE_WEB_ERRORES ?>salir" aria-expanded="false" class="dropdown-toggle">
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