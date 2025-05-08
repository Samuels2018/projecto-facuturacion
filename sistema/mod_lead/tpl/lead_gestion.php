<?php

require_once 'mod_funnel/object/funnel.object.php';
require_once 'mod_terceros/object/terceros.object.php';
require_once ENLACE_SERVIDOR . 'mod_crm/object/oportunidad.object.php';


$funnel = new FiFunnel($dbh, $_SESSION['Entidad']);
$funnel->fetch($_REQUEST['fiche']);

$terceros = new FiTerceros($dbh);
$listado_terceros = $terceros->obtener_listado_terceros($funnel->entidad);

$Oportunidad= new Oportunidad($dbh, $_SESSION['Entidad']);
$Oportunidad->entidad = $_SESSION['Entidad'];
$Oportunidad->fk_funnel = $_GET['fiche'];


$terceros_company   = new FiTerceros($dbh, $_SESSION['Entidad']);
$terceros_company->obtener_listado_terceros();
$listado_terceros = $terceros_company->obtener_listado_terceros;

$listado_tags = $Oportunidad->get_tags_oportunidades();

$listado_usuarios_asignados = $Oportunidad->obtener_lista_usuarios_asignados_oportunidad();


//Vamos a listar las categorias y la lista de prioridades
$lista_categorias = $Oportunidad->obtener_listado_categorias();
$lista_prioridades = $Oportunidad->obtener_listado_prioridades();


?>
<link rel="stylesheet" type="text/css" href="<?php echo ENLACE_WEB ?>bootstrap/plugins/src/stepper/bsStepper.min.css">
<!-- <link rel="stylesheet" type="text/css" href=".<?php echo ENLACE_WEB ?>bootstrap/plugins/css/light/stepper/custom-bsStepper.css"> -->
<style>
    .ui-autocomplete {
        z-index: 2147483647 !important;
      
    }
    .filtro-perfil {
    display: flex; /* Usar flexbox */
    margin-top: -10px; /* Espaciado superior */
}

.perfil-item {
    display: flex; /* Usar flexbox para centrar contenido */
    justify-content: center; 
    align-items: center;
    width: 30px; /* Ancho fijo */
    height: 30px; /* Alto fijo */
    border-radius: 50%; /* Bordes redondeados para círculo */
    background-color: #6c757d; /* Color de fondo gris */
    color: white; /* Color del texto */
    margin-right: 5px; /* Espaciado entre items */
    font-weight: bold; /* Negrita para las letras */
}

.perfil-item.plus {
    background-color: #fd7e14; /* Color diferente para el "+2" */
}

.avatar-chip.canvan img{
    width: 40px !important;
    height:40px !important;
}
</style>



<link href="<?php echo ENLACE_WEB ?>bootstrap/assets/css/light/apps/scrumboard.css" rel="stylesheet" type="text/css" />

<div class="middle-content container-fluid container-data container-xxl p-0">

    <div class="action-btn layout-top-spacing mb-5">
        <!-- <button id="add-list" class="btn btn-secondary">Add List</button> -->
    </div>
    <input type="hidden" name="funnel_id" id="funnel_id" value="<?php echo $funnel->rowid; ?>">
    <div class="modal fade" id="addTaskModal" role="dialog" aria-labelledby="addTaskModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">


                <div class="modal-body">


                    <div id="wizard_Vertical_Validation">

                        <div class="widget-content widget-content-area">



                            <div class="modal-header">
                                <h5 class="modal-title" id="addTaskModalTitle">Crear Oportunidad</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="bs-stepper stepper-form-validation-one linear">
                                <div class="bs-stepper-header" role="tablist">
                                    <div class="step crossed" data-target="#validationStep-one">
                                        <button type="button" class="step-trigger" role="tab" aria-selected="false" disabled="disabled">
                                            <span class="bs-stepper-circle">1</span>
                                            <span class="bs-stepper-label">Paso 1</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step active" data-target="#validationStep-two">
                                        <button type="button" class="step-trigger" role="tab" aria-selected="true">
                                            <span class="bs-stepper-circle">2</span>
                                            <span class="bs-stepper-label">Paso</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#validationStep-three">
                                        <button type="button" class="step-trigger" role="tab" aria-selected="false" disabled="disabled">
                                            <span class="bs-stepper-circle">3</span>
                                            <span class="bs-stepper-label">
                                                <span class="bs-stepper-title">Paso 3</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                <div class="bs-stepper-content">
                                    <form class="needs-validation" onsubmit="return false" novalidate="">

                                        <div id="validationStep-one" class="content fade dstepper-block dstepper-none" role="tabpanel">
                                            <div id="test-form-1" class="needs-validation">
                                                <div class="form-group mb-4">
                                                    <input type="text" id="tarea_id" name="tarea_id" hidden>
                                                    <input type="hidden" name="fk_funnel_detalle" id="fk_funnel_detalle">
                                                    <label for="validationStepform-name">Cliente</label>

                                                    <div class="ui-widget" id="input_busqueda_tercero">
                                                        <input id="fk_tercero" name="fk_tercero" class="form-control form-control-sm ui-autocomplete-input" autocomplete="off">
                                                    </div>

                                                    <label for="validationStepform-name">Contacto</label>

                                                    <select id="fk_contacto" name="fk_contacto" class="form-control"></select>

                                                    <div class="invalid-feedback">Please enter your name</div>
                                                </div>
                                            </div>

                                            <div class="button-action mt-5">
                                                <button class="btn btn-secondary btn-prev me-3 _effect--ripple waves-effect waves-light" disabled="">Ant</button>
                                                <button class="btn btn-secondary btn-nxt _effect--ripple waves-effect waves-light">Sig</button>
                                            </div>
                                        </div>
                                        <div id="validationStep-two" class="content fade dstepper-block active" role="tabpanel">
                                            <div class="needs-validation">
                                                <div class="form-group mb-4">
                                                    <label for="fk_usuario_asignado">Usuario Asignado</label>
                                                    <input type="text" class="form-control" name="fk_usuario_asignado" id="fk_usuario_asignado">
                                                    <label for="etiqueta">Nombre de la Oportunidad</label>
                                                    <input type="text" class="form-control" name="etiqueta" id="etiqueta" placeholder="" required="">
                                                    <label for="nota">Nota</label>
                                                    <input type="text" class="form-control" name="nota" id="nota" placeholder="" required="">
                                                </div>
                                            </div>

                                            <div class="button-action mt-5">
                                                <button class="btn btn-secondary btn-prev me-3 _effect--ripple waves-effect waves-light">Ant</button>
                                                <button class="btn btn-secondary btn-nxt _effect--ripple waves-effect waves-light">Sig</button>
                                            </div>
                                        </div>
                                        <div id="validationStep-three" class="content fade dstepper-block dstepper-none" role="tabpanel">
                                            <div class="row g-3 needs-validation">
                                                <div class="col-12">
                                                    <label for="fk_servicio" class="form-label" multiple>Servicios</label>
                                                    <select id="fk_servicio" name="fk_servicio[]" multiple placeholder="Seleccione" autocomplete="off">
                                                    </select>
                                                    <label class="form-label" for="importe">Importe</label>
                                                    <input class="form-control" id="importe" name='importe' value=''>

                                                    <label class="form-label" for="tags">Tags</label>
                                                    <input class="form-control" id="tags" name='tags' value='' pattern='^[A-Za-z_✲ ]{1,15}$'>

                                                </div>
                                            </div>

                                            <div class="button-action mt-3">
                                                <button class="btn btn-secondary btn-prev me-3 _effect--ripple waves-effect waves-light">Ant</button>
                                                <button class="btn btn-success btn-submit _effect--ripple waves-effect waves-light" id="boton_crear_txt" onclick="validar_accion(event)">Crear</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="addListModal" tabindex="-1" role="dialog" aria-labelledby="addListModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title edit-list-title" id="addListModalTitleLabel2">Editar Detalle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="compose-box">
                        <div class="compose-content" id="addListModalTitle">
                            <form action="javascript:void(0);">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="list-title d-flex">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list me-3 align-self-center">
                                                <line x1="8" y1="6" x2="21" y2="6"></line>
                                                <line x1="8" y1="12" x2="21" y2="12"></line>
                                                <line x1="8" y1="18" x2="21" y2="18"></line>
                                                <line x1="3" y1="6" x2="3" y2="6"></line>
                                                <line x1="3" y1="12" x2="3" y2="12"></line>
                                                <line x1="3" y1="18" x2="3" y2="18"></line>
                                            </svg>
                                            <input type="hidden" name="detalle_id" id="detalle_id">
                                            <input id="lista_etiqueta" type="text" placeholder="Detalle" class="form-control" name="lista_etiqueta">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-bs-dismiss="modal"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg> <span class="btn-text-inner">Cancelar</span></button>
                    <button class="btn edit-list btn-primary" onclick="cambiar_nombre_detalle(event)">Modificar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteConformation" tabindex="-1" role="dialog" aria-labelledby="deleteConformationLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" id="deleteConformationLabel">
                <div class="modal-header">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                    </div>
                    <h5 class="modal-title" id="exampleModalLabel">Delete the task?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="">If you delete the task it will be gone forever. Are you sure you want to proceed?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" data-remove="task">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row scrumboard" id="cancel-row">
        <div class="col-lg-12 layout-spacing">

             <!-- Filtros de fecha -->
            <div class="filtros_tablero" style="margin-bottom:15px;">
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label for=""><i class="fa fa-search" aria-hidden="true"></i>Buscar</label>
                        <input type="text" id="buscar_oportunidad" class="form-control" placeholder="Busqueda"/>
                </div>

                <!-- Filtro por usuario-->

                <div class="col-sm-2">
                        <label for=""><i class="fa fa-user-circle-o" aria-hidden="true"></i> Usuarios</label>
                        <div class="filtro-perfil" style="margin-top: -2px;">
                            <?php 
                                // Lista de colores predefinidos
                                $colores = [
                                    '#3F5BE0', '#FF5733', '#FF33A1', 
                                    '#3357FF', '#F1C40F', '#8E44AD', '#E74C3C', 
                                    '#3498DB', '#2ECC71'
                                ];
                                foreach ($listado_usuarios_asignados as $key => $value) {
                                    // Asignar color de forma cíclica
                                    $color = $colores[$key % count($colores)];
                            ?>
                                <span class="avatar-chip canvan position-relative" style="padding: 0px; margin-left: 20px; cursor: pointer;" 
                                    data-toggle="tooltip" title="<?php echo $value->nombre_tercero.' '.$value->apellido_tercero; ?>" 
                                    data-placement="top">
                                    <input type="checkbox" class="avatar-checkbox" id="user-<?php echo $value->usuario_asignado; ?>" 
                                        value="<?php echo $value->usuario_asignado; ?>" style="display: none;">
                                    <img style=" border: none;padding: 3px; background-color: rgb(237, 242, 235);" src="https://ui-avatars.com/api/?name=<?php echo $value->nombre_tercero.' '.$value->apellido_tercero; ?>&background=<?php echo ltrim($color, '#'); ?>&color=fff" 
                                        alt="<?php echo $value->nombre_tercero.' '.$value->apellido_tercero; ?>" width="96" height="96">
                                </span>
                            <?php } ?>
                        </div>
                </div>


                <!-- Cierre del filtro por usuario-->


                
                <!-- Filtro por fecha-->
                <div class="col-sm-2">
                    <label for=""><i class="fa fa-calendar" aria-hidden="true"></i> Fecha de Creación</label>
                    <input type="text" id="fecha-rango" class="form-control" placeholder="Selecciona un rango de fechas" />
                </div>
                <!-- Cierre de filtro por fecha-->
                   
                    
                   <!-- Filtro Categorías-->
                <div class="col-sm-2">
                    <label for="filtro-categorias"><i class="fa fa-tasks" aria-hidden="true"></i> Categorías</label>
                    <select id="filtro-categorias" class="form-control" multiple="multiple">
                        <?php
                            foreach($lista_categorias as $categoria){
                        ?>                
                            <option value="<?php echo $categoria['rowid']; ?>"><?php echo $categoria['etiqueta'];  ?></option>
                            
                        <?php 
                            } 
                        ?>
                        <!-- Agrega más opciones según sea necesario -->
                    </select>
                </div>
                <!-- Cierre filtro categorías-->

                
                <!-- Filtro Prioridad-->
                <div class="col-sm-2">
                    <label for="filtro-prioridad"><i class="fa fa-thermometer-full" aria-hidden="true"></i> Prioridad</label>
                    <select id="filtro-prioridad" class="form-control" multiple="multiple">
                        <?php
                            foreach($lista_prioridades as $prioridad){
                        ?>                
                            <option value="<?php echo $prioridad['rowid']; ?>"><?php echo $prioridad['etiqueta'];  ?></option>
                            
                        <?php 
                            } 
                        ?>

                        <!-- Agrega más opciones según sea necesario -->
                    </select>
                </div>
                <!-- Cierre filtro prioridad-->

                  <!-- Filtro Tags-->
                  <div class="col-sm-2">
                    <label for="filtro-tags"><i class="fa fa-tasks" aria-hidden="true"></i> Tags</label>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle w-100" type="button" id="dropdownTags" data-bs-toggle="dropdown" aria-expanded="false">
                            Seleccionar etiquetas
                        </button>
                        <ul class="dropdown-menu p-3 w-100" aria-labelledby="dropdownTags">
                            <?php foreach($listado_tags as $key => $value) { 
                                if($listado_tags[$key] === '') continue; ?>
                                <li class="form-check">
                                    <input class="form-check-input" type="checkbox" value="<?php echo $listado_tags[$key]; ?>" id="tag-<?php echo $key; ?>">
                                    <label class="form-check-label" for="tag-<?php echo $key; ?>">
                                        <?php echo $listado_tags[$key]; ?>
                                    </label>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <!-- Cierre filtro categorías-->


                  
                </div>

            </div>


            <div class="task-list-section">
                <!-- se cargan listas y tareas de forma dinamica -->
            </div>


        </div>
    </div>
</div>

</div>


<!-- Modal nuevo contacto -->
<!-- Modal para agregar un nuevo contacto -->
<div class="modal fade" id="nuevoContactoModal" role="dialog" aria-labelledby="addTaskModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTaskModalTitle">Agregar Contacto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="mostrar_modal_tarea()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="form-group mb-4">
                    <input type="hidden" name="fk_tercero_contacto" id="fk_tercero_contacto">
                    <div class="form-label">Nombre</div>
                    <input type="text" class="form-control" id="contacto_nombre" name="contacto_nombre" placeholder="Nombre">
                    <div class="form-label">Apellido</div>
                    <input type="text" class="form-control" id="contacto_apellido" name="contacto_apellido" placeholder="Apellido">
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <button type="button" class="btn btn-primary" onclick="crear_contacto(event)">Agregar</button>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<!-- CSS de Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- JS de Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<!-- Modal nuevo contacto -->
<script>
    $( document ).ready(function() {
   
          // Desactivar todos los elementos del menú
      $(".menu").removeClass('active');

        $(".canvan").addClass('active');

       let intervalId = setInterval(function() {
            if ($(".container-fluid").hasClass('container-xxl')) {
                $(".container-fluid").removeClass('container-xxl');
                clearInterval(intervalId); // Detener el intervalo
            }
        }, 100);
                

});
</script>
<!--  END CONTENT AREA  -->
<script src="<?php echo ENLACE_WEB ?>bootstrap/plugins/src/stepper/bsStepper.min.js"></script>
<?php require_once ENLACE_SERVIDOR . "mod_lead/tpl/lead_gestion_scripts.php" ?>