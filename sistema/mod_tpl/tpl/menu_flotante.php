<?php
$css =  ENLACE_WEB . 'bootstrap/css/crm.css';
?>
<link rel="stylesheet" type="text/css" href="<?php echo $css; ?>">

<!-- Menú flotante dentro del li con clases únicas -->
<div class="custom-dropdown-menu">

    <div class="" style="text-align: center;margin-bottom: 15px; font-size: 20px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-configuracion">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg>
        <span>Configuración</span>
    </div>


    <div class="custom-menu-icons">
        <div class="custom-menu-item">
            <a url="formas_pago" href="<?= ENLACE_WEB ?>formas_pago">
                <i class="fas fa-money-check-alt"></i>
                <span>Forma de pago</span>
            </a>
        </div>
        <div class="custom-menu-item">
            <a url="categorias_producto_listado" href="<?= ENLACE_WEB ?>categorias_producto_listado">
                <i class="fas fa-tags"></i>
                <span>Categorías productos</span>
            </a>
        </div>
        <div class="custom-menu-item">
            <a url="cliente_categorias" href="<?= ENLACE_WEB ?>cliente_categorias">
                <i class="fas fa-users-cog"></i>
                <span>Categorías Clientes</span>
            </a>
        </div>

        <div class="custom-menu-item">
            <a url="cliente_categorias" href="<?= ENLACE_WEB ?>categoria_crm_categorias">
                <i class="fa fa-plug" aria-hidden="true"></i>
                <span>Categorías Oportunidades</span>
            </a>
        </div>

        <div class="custom-menu-item">
            <a url="prioridades_listado" href="<?= ENLACE_WEB ?>prioridades_listado">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Diccionario Prioridades</span>
            </a>
        </div>
        <div class="custom-menu-item">
            <a url="monedas_listado" href="<?= ENLACE_WEB ?>monedas_listado">
                <i class="fas fa-coins"></i>
                <span>Diccionario Moneda</span>
            </a>
        </div>
        <div class="custom-menu-item">
            <a url="catalogo_listado" href="<?= ENLACE_WEB ?>catalogo_listado">
                <i class="fas fa-ruler-combined"></i>
                <span>Unidad medida</span>
            </a>
        </div>
        <div class="custom-menu-item">
            <a url="bancos_listado" href="<?= ENLACE_WEB ?>bancos_listado">
                <i class="fas fa-university"></i>
                <span>Diccionario de Bancos</span>
            </a>
        </div>
        <div class="custom-menu-item">
            <a url="bodega_listado" href="<?= ENLACE_WEB ?>bodega_listado">
                <i class="fas fa-store"></i>
                <span>Almacenes</span>
        </div>
        <div class="custom-menu-item">
            <a url="medios_pago" href="<?= ENLACE_WEB ?>medios_pago">
                <i class="fas fa-money-bill"></i>
                <span>Medios de pago</span>
            </a>
        </div>
        <div class="custom-menu-item">
            <a url="rutas_listado" href="<?= ENLACE_WEB ?>rutas_listado">
                <i class="fas fa-tag"></i>
                <span>Listado de Rutas</span>
            </a>
        </div>
        <div class="custom-menu-item">
            <a url="agentes_listado" href="<?= ENLACE_WEB ?>agentes_listado">
                <i class="fa fa-fw fa-user"></i>
                <span>Listado de Agentes</span>
            </a>
        </div>
        <div class="custom-menu-item">
            <a url="actividad_listado" href="<?= ENLACE_WEB ?>actividad_listado">
                <i class="fa-solid fa-fire"></i>
                <span>Tipos de Actividad</span>
            </a>
        </div>
        <div class="custom-menu-item">
            <a url="proyecto_listado" href="<?= ENLACE_WEB ?>proyecto_listado">
                <i class="fas fa-project-diagram"></i>
                <span>Listado de Proyectos</span>
            </a>
        </div>

        <div class="custom-menu-item">
            <a url="tiempo_entrega_listado" href="<?= ENLACE_WEB ?>tiempo_entrega_listado">
                <i class="fas fa-clock"></i>
                <span>Tiempos de entrega</span>
            </a>
        </div>
        <div class="custom-menu-item">
            <a url="validez_oferta_listado" href="<?= ENLACE_WEB ?>validez_oferta_listado">
                <i class="fas fa-file-contract"></i>
                <span>Validez de oferta</span>
            </a>
        </div>
        <div class="custom-menu-item">
            <a url="plantillas_listado" href="<?= ENLACE_WEB ?>plantillas_listado">
                <i class="fas fa-file-code"></i>
                <span>Gestión de plantillas</span>
            </a>
        </div>

    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        $('.custom-menu-flotante').on('click', function() {
            if ($(".custom-dropdown-menu").is(':visible') === true) {
                $('.custom-dropdown-menu').fadeOut(200);
            } else {
                $('.custom-dropdown-menu').fadeIn(200);
            }
        });

        // Obtener la URL actual
        var currentUrl = window.location.pathname.split('/').pop();

        // Iterar sobre cada enlace del menú
        $('.custom-menu-item').each(function() {
            var linkUrl = $(this).find("a").attr('url');
            // Verificar si el enlace es similar a la URL actual
            if (linkUrl === currentUrl) {
                $(this).addClass('activo'); // Agregar clase activa
            }
        });


    });
</script>