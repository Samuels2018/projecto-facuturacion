<?php


SESSION_START();
error_reporting(E_ERROR |  E_PARSE);

require "conf/conf.php";
if ($_SESSION['usuario'] == NULL) {
   // header('location: ' . ENLACE_WEB);
}



 

$Entidad = true;

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title> Sistema | Facturacion Electronica Facil </title>
    <link rel="icon" type="image/x-icon" href="<?php echo ENLACE_WEB; ?>bootstrap/assets/img/favicon.ico" />
    <link href="<?php echo ENLACE_WEB; ?>bootstrap/css/light/loader.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo ENLACE_WEB; ?>bootstrap/css/dark/loader.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo ENLACE_WEB; ?>bootstrap/loader.js"></script>


    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="<?php echo ENLACE_WEB; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo ENLACE_WEB; ?>bootstrap/css/light/plugins.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo ENLACE_WEB; ?>bootstrap/css/dark/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="<?php echo ENLACE_WEB; ?>bootstrap/plugins/src/apex/apexcharts.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo ENLACE_WEB; ?>bootstrap/assets/css/light/scrollspyNav.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->


    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="<?php echo ENLACE_WEB; ?>bootstrap/assets/css/light/elements/alert.css">
    <link rel="stylesheet" type="text/css" href="<?php echo ENLACE_WEB; ?>bootstrap/assets/css/dark/elements/alert.css">

    <link rel="stylesheet" type="text/css" href="<?php echo ENLACE_WEB; ?>bootstrap/assets/css/light/elements/search.css">


    <!-- toastr -->
    <link href="<?php echo ENLACE_WEB; ?>bootstrap/plugins/src/notification/snackbar/snackbar.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo ENLACE_WEB; ?>bootstrap/plugins/css/light/notification/snackbar/custom-snackbar.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo ENLACE_WEB; ?>bootstrap/plugins/css/dark/notification/snackbar/custom-snackbar.css" rel="stylesheet" type="text/css" />

    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <!-- Estilos Datatables -->
    <link rel="stylesheet" type="text/css" href="<?php echo ENLACE_WEB ?>bootstrap/plugins/src/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="<?php echo ENLACE_WEB ?>bootstrap/plugins/css/light/table/datatable/dt-global_style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo ENLACE_WEB ?>bootstrap/plugins/css/dark/table/datatable/dt-global_style.css">

    <!-- FONTAWESOME -->
    <script src="https://kit.fontawesome.com/927002a984.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


    <script src="https://cdn.tiny.cloud/1/uhv4bp72ge78x6x2caz3nrz59xgyjgegszqwblvoiwr4bhs9/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

    <!-- sweet alert -->
    <link rel="stylesheet" href="<?php echo ENLACE_WEB ?>bootstrap/plugins/src/sweetalerts2/sweetalerts2.css">
    <!-- jquery-autocomplete -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.min.css">
    <!-- Custom tables -->
    <link rel="stylesheet" type="text/css" href="<?php echo ENLACE_WEB ?>bootstrap/css/light/custom-tables.css">
    <link rel="stylesheet" type="text/css" href="<?php echo ENLACE_WEB ?>bootstrap/assets/css/light/forms/switches.css">
    <link rel="stylesheet" href="<?php echo ENLACE_WEB ?>bootstrap/assets/css/light/components/tabs.css">
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- contactos -->
    <link href="<?php echo ENLACE_WEB; ?>bootstrap/assets/css/light/apps/contacts.css" rel="stylesheet" type="text/css" />
    <!-- Leaflet -->
    <link href="<?php echo ENLACE_WEB; ?>bootstrap/plugins/src/leaflet/leaflet.css" rel="stylesheet" type="text/css" />
    <!-- Tom-select -->
    <!-- tom-select -->
    <link rel="stylesheet" type="text/css" href="<?php echo ENLACE_WEB; ?>bootstrap/plugins/src/tomSelect/tom-select.default.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo ENLACE_WEB; ?>bootstrap/plugins/css/light/tomSelect/custom-tomSelect.css">
    <!-- tagify -->
    <link rel="stylesheet" type="text/css" href="<?php echo ENLACE_WEB; ?>bootstrap/plugins/src/tagify/tagify.css">
    <!-- Quill Editor -->
    <link rel="stylesheet" type="text/css" href="<?php echo ENLACE_WEB; ?>bootstrap/plugins/css/light/editors/quill/quill.snow.css">

    <!-- dark settings nav-->
    <link rel="stylesheet" type="text/css" href="<?php echo ENLACE_WEB; ?>bootstrap/assets/css/dark/components/tabs.css">


    <!-- Incluye SheetJS para exportar a Excel -->
    <script src="<?php echo ENLACE_WEB; ?>bootstrap/xlsx.full.min.js"></script>
    <!-- Incluye jsPDF para exportar a PDF -->
    <script src="<?php echo ENLACE_WEB; ?>bootstrap/jspdf.umd.min.js"></script>
    <script src="<?php echo ENLACE_WEB; ?>bootstrap/jspdf.plugin.autotable.min.js"></script>



    <style>
        body.dark .layout-px-spacing,
        .layout-px-spacing {
            min-height: calc(100vh - 155px) !important;
        }

        /* Estilo para el contenedor del autocompletado */
        .ui-autocomplete {
            background-color: #f9f9f9;
            /* Cambia el color de fondo del contenedor */
            border: 1px solid #ccc;
            /* Añade un borde al contenedor */
            border-radius: 4px;
            /* Redondea las esquinas del contenedor */
            width: 50%;
        }

        /* Estilo para cada elemento de la lista de resultados */
        .ui-menu-item {
            background-color: #fff;
            /* Cambia el color de fondo de cada elemento */
            border-bottom: 1px solid #ccc;
            /* Añade un borde inferior a cada elemento */
            padding: 5px 10px;
            width: 50%;
            /* Añade padding a cada elemento */
        }

        /* Estilo para el elemento de la lista de resultados cuando el mouse pasa sobre él */
        .ui-menu-item:hover {
            background-color: #e0e0e0;
            /* Cambia el color de fondo del elemento al pasar el mouse */
        }

        /* Estilo para el elemento de la lista de resultados que está seleccionado */
        .ui-menu-item.ui-state-focus {
            background-color: #d0d0d0;
            /* Cambia el color de fondo del elemento seleccionado */
        }


        /** estilos select 2 */
        .select2-selection__rendered {
            line-height: 31px !important;
            width: 100%;
        }

        .select2-container .select2-selection--single {
            height: 33px !important;
            width: 100%;
        }

        .select2-selection__arrow {
            height: 33px !important;
            width: 100%;
        }

        .select2-search-input .select2-search__field {
            width: 100%;
        }

        /* estilo para error en input/validaciones */
        .input_error {
            border: 1px solid #f00 !important;
            animation: shake 0.2s ease-in-out 0s 2;
            card-shadow: 0 0 0.5em red;
        }

        #export-buttons-container {
            min-width: 360px !important;
        }


        @keyframes shake {
            0% {
                margin-left: 0rem;
            }

            25% {
                margin-left: 0.5rem;
            }

            75% {
                margin-left: -0.5rem;
            }

            100% {
                margin-left: 0rem;
            }
        }

        /* ocultar */
        .ocultar {
            display: none !important;
        }


        .container-data {
            left: 255px !important;
        }
    </style>


    <link rel="stylesheet" href="<?php echo ENLACE_WEB ?>bootstrap/responsive.css">



    <script>
        var ENLACE_WEB = "<?php echo ENLACE_WEB; ?>";
    </script>

</head>

<body class="layout-boxed dark" data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="140">

    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    <div class="header-container container-fluid container-data">
        <header class="header navbar navbar-expand-sm expand-header">

            <a href="javascript:void(0);" class="sidebarCollapse">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </a>
            <?php 
                include_once ENLACE_SERVIDOR_ERRORES . "mod_tpl/tpl/buscador.php";
        ?>

        
            <ul class="navbar-item flex-row ms-lg-auto ms-0">
                
                <li class="nav-tem mt-2 menu-flotante custom-menu-flotante">
                    <a href="#">
                        <i class="fa fa-th-large" aria-hidden="true" style="font-size: 20px;margin-right: 15px;margin-top: 3px;"></i>
                    </a>  
                      <!-- Menú flotante dentro del li -->
                    <?php
                        include_once ENLACE_SERVIDOR_ERRORES . "mod_tpl/tpl/menu_flotante.php";
                    ?>
                </li>
               

                <li class="nav-item dropdown language-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="language-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="language-dropdown">
                        <?php
                        foreach ($Lan->idiomas as $key => $idioma) {
                         //   echo '<a class="dropdown-item d-flex" href="javascript:Idioma(' . $idioma['rowid'] . ');"><img src="' . ENLACE_WEB_FILES . '' . $idioma['flag'] . '" class="flag-width" alt="flag"> <span class="align-self-center">&nbsp;' . $idioma['etiqueta'] . '</span></a>';
                        }
                        ?>
                    </div>
                </li>

                <li class="nav-item theme-toggle-item">
                    <a href="javascript:void(0);" class="nav-link theme-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-moon dark-mode">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sun light-mode">
                            <circle cx="12" cy="12" r="5"></circle>
                            <line x1="12" y1="1" x2="12" y2="3"></line>
                            <line x1="12" y1="21" x2="12" y2="23"></line>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                            <line x1="1" y1="12" x2="3" y2="12"></line>
                            <line x1="21" y1="12" x2="23" y2="12"></line>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                        </svg>
                    </a>
                </li>



                <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="avatar-container">
                            <div class="avatar avatar-sm avatar-indicators avatar-online">
                             
                            </div>
                        </div>
                    </a>

                    <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                        <div class="user-profile-section">
                            <div class="media mx-auto">
                                <div class="emoji me-2">
                                    &#x1F44B;
                                </div>
                                <div class="media-body">
                                    <h5><?php echo $_SESSION['usuario_txt']; ?></h5>
                                    <p><?php echo ($Entidad->nombre_fantasia); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php
                        if (isset($_SESSION['multientidad']) && count($_SESSION['multientidad']) > 0) {
                            $multientidad = $_SESSION['multientidad'];
                            $multiusuario = $_SESSION['multiusuario'];
                            $nombre_entidad = $_SESSION['nombre_entidad'];
                        ?>
                            <div class="user-profile-section" style="padding-top: 0px;">
                                <strong style="font-size: 12px;">Cambiar Entidad: </strong>
                                <select class="form-control" id="selector_entidad">
                                    <?php
                                    foreach ($multientidad as $keyent => $valuen) {
                                    ?>
                                        <option <?php if (intval($_SESSION['Entidad']) === intval($multientidad[$keyent])) {
                                                    echo 'selected';
                                                } ?> usuario="<?php echo $multiusuario[$keyent]; ?>" value="<?php echo $multientidad[$keyent]; ?>"><?php echo $nombre_entidad[$keyent]; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>

                        <?php } ?>

                        <div class="dropdown-item">
                            <a href="<?= ENLACE_WEB . 'mi_perfil' ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg> <span>Mi Perfil</span>
                            </a>
                        </div>
                        <div class="dropdown-item">
                            <a href="<?= ENLACE_WEB . 'empresa' ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase">
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                </svg><span>Mi Empresa</span>
                            </a>
                        </div>
                        <div class="dropdown-item">
                            <a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=Licencias">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg> <span>Licencias</span>
                            </a>
                        </div>
                        
                        <div class="dropdown-item">
                            <a href="<?php echo ENLACE_WEB; ?>salir">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg> <span>
                                    Salir
                                </span>
                            </a>
                        </div>
                    </div>

                </li>

            </ul>
        </header>
    </div>
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container " id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <div class="sidebar-wrapper sidebar-theme">

            <nav id="sidebar">

                <?php
                
             

                include_once ENLACE_SERVIDOR_ERRORES . "mod_tpl/tpl/menu.inc.php"; ?>

            </nav>

        </div>
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <?php

                // Esto es una plantilla de Ejemplo VACIA !

                include ENLACE_SERVIDOR_ERRORES . "mod_switch/tpl/switch.tpl.php";
                if ($tpl != "") {
                    require ENLACE_SERVIDOR_ERRORES . $tpl;
                }

                ?>



            </div>

            <div class="footer-wrapper">
                <div class="footer-section f-section-1">
                    <p class="">Copyright © <span class="dynamic-year"><?php echo date("Y"); ?></span> <a target="_blank" href="https://designreset.com/cork-admin/">Avantec.DS</a>, All rights reserved.</p>
                </div>
                <div class="footer-section f-section-2">
                    <p class="">Desarrollado con <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg></p>
                </div>
            </div>

        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->



    <script>
        $(document).ready(function() {
            //       $(".<?= $accion ?>").addClass('active');
            //      $(".<?= $accion ?> > .submenu").addClass('show');
            //     $("#<?= $accion ?>").addClass('active');

            $.ajax({
                method: "POST",
                url: "<?= ENLACE_WEB ?>mod_crm_actividades/ajax/listado_actividades.ajax.php??filtro_pendiente=1",
                beforeSend: function(xhr) {},
                data: {
                    action: 'conteo_notificaciones_usuario_actividad',
                },
            }).done(function(data) {
                $(".conteo").text(data);
                $(".conteo").removeClass('ocultar');
            });

            //Selector Entidad
            $("#selector_entidad").change(function() {
                usuario = $(this).find('option:selected').attr('usuario');
                entidad = $(this).val();

                $.ajax({
                    method: "POST",
                    url: "<?= ENLACE_WEB ?>mod_perfiles/ajax/cambiar_entidad_perfil.php",
                    beforeSend: function(xhr) {},
                    data: {
                        usuario: usuario,
                        entidad: entidad,
                    },
                }).done(function(data) {
                    location.reload();
                });
            });

            let intervalId = setInterval(function() {
                if ($(".container-fluid").hasClass('container-xxl')) {
                    $(".container-fluid").removeClass('container-xxl');
                    clearInterval(intervalId); // Detener el intervalo
                }
            }, 100);

        });


  

    </script>

    <script src="<?php echo ENLACE_WEB; ?>bootstrap/bootstrap.bundle.min.js"></script>
    <script src="<?php echo ENLACE_WEB; ?>bootstrap/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="<?php echo ENLACE_WEB; ?>bootstrap/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="<?php echo ENLACE_WEB; ?>bootstrap/plugins/src/waves/waves.min.js"></script>
    <script src="<?php echo ENLACE_WEB; ?>bootstrap/app.js"></script>
    <script src="<?php echo ENLACE_WEB; ?>bootstrap/plugins/src/highlight/highlight.pack.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <!-- toastr -->
    <script src="<?php echo ENLACE_WEB; ?>bootstrap/plugins/src/notification/snackbar/snackbar.min.js"></script>

    <script src="<?php echo ENLACE_WEB; ?>bootstrap/Idioma.js"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

    <!-- Datatable -->
    <script src="<?php echo ENLACE_WEB ?>bootstrap/plugins/src/table/datatable/datatables.js"></script>
    <!-- Sweet Alert -->
    <script src="<?php echo ENLACE_WEB ?>bootstrap/plugins/src/sweetalerts2/sweetalerts2.min.js"></script>
    <!-- Script funciones personalizados -->
    <script src="<?php echo ENLACE_WEB ?>bootstrap/funciones_custom.js?v=<?php echo time(); ?>"></script>
    <!-- configuracion custom datable -->
    <script src="<?php echo ENLACE_WEB ?>bootstrap/config_datatable_custom.js?v=<?php echo time(); ?>"></script>
    <script src="<?php echo ENLACE_WEB ?>bootstrap/assets/js/scrollspyNav.js?v=<?php echo time(); ?>"></script>

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="<?php echo ENLACE_WEB; ?>bootstrap/plugins/src/apex/apexcharts.min.js"></script>
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Leaflet -->
    <script src="<?php echo ENLACE_WEB; ?>bootstrap/plugins/src/leaflet/leaflet.js"></script>
    <!-- tom-select -->
    <script src="<?php echo ENLACE_WEB; ?>bootstrap/plugins/src/tomSelect/tom-select.base.js"></script>
    <!-- <script src="<?php echo ENLACE_WEB; ?>bootstrap/plugins/src/tomSelect/custom-tom-select.js"></script> -->
    <!-- tagify -->
    <script src="<?php echo ENLACE_WEB; ?>bootstrap/plugins/src/tagify/tagify.min.js"></script>
    <!-- Quill Editor -->
    <script src="<?php echo ENLACE_WEB; ?>bootstrap/plugins/src/editors/quill/quill.js"></script>


    <!-- dark settings account-->
    <link rel="stylesheet" type="text/css" href="<?php echo ENLACE_WEB; ?>bootstrap/assets/css/dark/users/account-setting.css">

    <!--

    <link rel="stylesheet" href="<?php echo ENLACE_WEB; ?>include/leaflet/geocoder/Control.geocoder.css" />
    <script src="<?php echo ENLACE_WEB; ?>include/leaflet/geocoder/Control.geocoder.js"></script>-->

    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>




</body>

</html>