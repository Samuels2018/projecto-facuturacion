

<link href="<?php echo ENLACE_WEB ?>bootstrap/plugins/src/drag-and-drop/dragula/dragula.css" rel="stylesheet" type="text/css">

<style>
    .dragula .media {
        background-color: #fff;
        border-radius: 6px;
        border: 1px solid #e0e6ed;
        padding: 10px 20px;
        margin: 5px;
        height: 63px;
        font-size: small;
    }

    .selector_iconos {
        margin-top: 15px;
        display: grid;
        grid-template-columns: repeat(4, 1fr); /* Crea 4 columnas de tamaño igual */
        gap: 10px; /* Espacio entre los iconos */
        padding: 10px; /* Padding alrededor de la grilla */
        background-color: #f4f4f4; /* Color de fondo del contenedor */
        border-radius: 8px; /* Bordes redondeados */
        box-shadow: 0 2px 5px rgba(0,0,0,0.15); /* Sombra suave para el contenedor */
    }

    .selector_iconos > i {
        font-size: 18px; /* Tamaño del icono */
        text-align: center; /* Centra los iconos horizontalmente */
        color: #333; /* Color de los iconos */
        cursor: pointer; /* Cambia el cursor al pasar el mouse */
        padding: 10px; /* Espaciado interno para cada icono */
        display: block; /* Asegura que cada icono ocupe toda la celda */
        transition: color 0.3s; /* Transición suave para cambios de color */
    }

    .selector_iconos > i:hover {
        color: #007BFF; /* Cambia el color al pasar el mouse */
    }


</style>
<form method="post" action="tu_script_de_procesamiento.php">

    <div class="modal-body">
        <div class="simple-pill">

            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Funnel</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Detalles</button>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">

                    <div class="form-group mt-4">
                        <input type="hidden" name="rowid" id="rowid">
                        <label for="titulo">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título del Funnel">
                    </div>
                    <div class="form-group mt-4">
                        <label for="descripcion">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Descripción del Funnel"></textarea>
                    </div>
                    <div class="form-group mt-4">
                        <label for="color">Color</label>
                        <input type="color" class="form-control" id="color" name="color">
                    </div>
                    <div class="form-group mt-4">
                        <label for="icono">Icono</label>
                        <div class="icon-selector"></div>

                         <!--<select class="form-control select2 " id="icono" name="icono" style="width: 100%;">
                            <option value=''></option>
                            <?php foreach ($iconos as $icono) { ?>
                                <option data-icon='<?php echo $icono['clase'] ?>' value="fa <?php echo $icono['clase'] ?>"><?php echo $icono['descripcion'] ?></option>
                            <?php } ?>
                        </select>-->
                        <!-- <input type="text" class="form-control" id="icono" name="icono" placeholder="Nombre del Icono"> -->
                        <input type="hidden" name="icono" id="icono">
                        <div class="selector_iconos" style="display: none;">
                             <?php foreach ($iconos as $icono) { ?>
                               <!-- <option data-icon='<?php echo $icono['clase'] ?>' value="fa <?php echo $icono['clase'] ?>"><?php echo $icono['descripcion'] ?></option>
                           --> 
                                <i  claseicon="fa <?php echo $icono['clase']; ?>" class="usar_icono fa <?php echo $icono['clase']; ?>"></i>
                            <?php } ?>
                            <!-- Añade más iconos según necesites -->
                        </div>


                    </div>
                </div>

                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                    <div id="form-detalles">
                        <div class="form-group mt-4">
                            <label for="etiqueta">Etiqueta</label>
                            <input type="text" class="form-control" id="etiqueta" name="etiqueta">
                        </div>
                        <div class="form-group mt-4">
                            <label for="descripcion">Descripci&oacute;n</label>
                            <input type="text" class="form-control" id="descripcion_funnel" name="descripcion_funnel">
                        </div>
                        <div class="form-group mt-4">
                            <label>Mostrar en Canvan</label>
                            <select name="canvan_mostrar_como_columna" id="canvan_mostrar_como_columna" class="form-control">
                                <option value="1">Si</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <div class="row mt-2 d-flex justify-content-end ">
                            <div class="col-md-2 mr-4">
                                <button type="button" class="btn btn-primary btn-block" onclick="crear_detalle(event)"><i class="fa fa-fw fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div id='funnel-order' class='dragula'>
                            <!-- Se muestra la lista de detalles -->
                        </div>
                    </div>
                </div>

            </div>
        </div>
</form>
<script src="<?php echo ENLACE_WEB ?>bootstrap/plugins/src/drag-and-drop/dragula/dragula.js"></script>