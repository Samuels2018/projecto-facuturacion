<link href="<?php echo ENLACE_WEB; ?>bootstrap/assets/css/dark/components/timeline.css" rel="stylesheet" type="text/css" />
<link href="<?php echo ENLACE_WEB; ?>bootstrap/assets/css/light/components/timeline.css" rel="stylesheet" type="text/css" />

<div class="middle-content container-fluid p-0">
    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Licencias</li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->

    <!-- CONTENT AREA -->
    <div class="row layout-top-spacing">
    <?php 
    
            $Usuarios->buscar_data_usuario($_SESSION['usuario']);
 
    ?>
 

    <div class="col-lg-6 col-6 layout-spacing">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-header">
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <h4>Detalles De Licencia</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content widget-content-area">

                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th scope="text-center"></th>
                                                    <th class="text-center" scope="col">Status</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td>Sistema</td>
                                                    <td>
                                                        <i class="fas fa-tools"></i>
                                                        <a href="<?php echo ENLACE_WEB; ?>"  class="table-inner-text"><?php echo "sistema.factuguay.es"; ?></a>
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td>N&uacute;mero de Licencias</td>
                                                    <td>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="9" cy="7" r="4"></circle>
                                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                                    </svg>
                                                    <span class="table-inner-text">10 usuarios  / 9 Disponibles</span>
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td>Versi&oacute;n</td>
                                                    <td>
                                                     <span class="badge badge-light-info">1.0.15</span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>Fecha Versi&oacute;n</td>
                                                    <td>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                                        <span class="table-inner-text">Agosto 2024</span>
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td>Licencia Factuguay</td>
                                                    <td>
                                                     <span class="badge badge-light-info"><?php echo md5($_SESSION['Entidad']); ?></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Nombre Cliente</td>
                                                    <td>
                                                        <span class="table-inner-text">
                                                          <?php echo  ( $_SESSION['nombre_entidad'][0]); ?>
                                                         </span>
                                                    </td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td>Usuarios Activo(s)</td>
                                                    <td>
                                                        <span class="table-inner-text"><?php  echo ($Usuarios->nombre." ".$Usuarios->apellidos);  ?></span>
                                                         / <?php echo ( $_SESSION['nombre_entidad']); ?>
                                                    </td>
                                                    
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                    

                                </div>
                            </div>
                        </div>

                        

                        

                        


                        

                        <div   class="col-lg-6 col-6 layout-spacing">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-header">
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <h4>Factuguay 1.0.15</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content widget-content-area">
                                <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr><td>
                                        <strong>Avantec.DS SL</strong><br>
                                        CIF B70811112<br>
                                        Calle Cuenca 29 2A<br>
                                        24403 Ponferrada<br>
                                        Le&oacute;n<br>
                                        <br><br>
                                        Tel&eacute;fono +34 630167488
                                    </td>
                                </tr>
                                </table>
                                </div>
                            </div>    
                        </div>  


                         
    </div>


    <div class="row">

    
    <div class="col-lg-12 col-12 layout-spacing">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-header">
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <h4>Ultimas actualizaciones - Version Actual 1.0.15</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content widget-content-area">
                                <div class="mt-container mx-auto">
                                        <div class="timeline-line">
                                            
                                            <div class="item-timeline">
                                                <p class="t-time">1.0.15</p>
                                                <div class="t-dot t-dot-primary">
                                                </div>
                                                <div class="t-text">
                                                    <p>Verifactu</p>
                                                    <p  >Consumo Servicios Verifactu Segun MINISTERIO DE HACIENDA
22138 Orden HAC/1177/2024, de 17 de octubre, 
por la que se desarrollan las especificaciones técnicas, funcionales y de contenido referidas en el Reglamento que establece los requisitos que deben adoptar los sistemas 
y programas informáticos o electrónicos que soporten los procesos de facturación de empresarios y profesionales, y la estandarización de formatos de los registros de facturación, aprobado por el Real Decreto 1007/2023, de 5 de diciembre; 
y en el Reglamento por el que se regulan las obligaciones de facturación, aprobado por Real Decreto 1619/2012, de 30 de noviembre.</p>
                                                </div>
                                            </div>

                                            <div class="item-timeline">
                                                <p class="t-time"></p>
                                                <div class="t-dot t-dot-success">
                                                </div>
                                                <div class="t-text">
                                                    <p>Facturaci&oacute;n</p>
                                                    <p>Manejo de Categorias por Clientes & Ajustes de Limite de Cr&eacute;dito</p>
                                                </div>
                                                
                                            </div>


                                            <div class="item-timeline">
                                                <p class="t-time">10.0.14</p>
                                                <div class="t-dot t-dot-success">
                                                </div>
                                                <div class="t-text">
                                                    <p>CRM</p>
                                                    <p>Mejora en Listado de Categorias por Oportunidades </p>
                                                </div>
                                            </div>
                                    
                                        </div>



                                </div>
                            </div>
                        </div>
    </div>
    <!--  END CONTENT AREA  -->
</div>
