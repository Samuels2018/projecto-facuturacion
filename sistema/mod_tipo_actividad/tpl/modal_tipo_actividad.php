<?php
session_start();

//si no hay usuario autenticado, cerrar conexion
if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
    exit(1);
}

include_once("../../conf/conf.php");
include ENLACE_SERVIDOR . 'mod_tipo_actividad/object/tipo_actividad.object.php';
$obj = new TipoActividad($dbh, $_SESSION['Entidad']);


if (!empty($_REQUEST['fiche'])) {
    $obj->fetch($_REQUEST['fiche']);
}else{
    $obj->id = 0;
}



if ($obj->id > 0) {
    $titulo = 'Modificar';
} else {
    $titulo = 'Crear';
}
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.8.2/dist/themes/classic.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    #icono {
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        border: 1px solid #ddd;
        z-index: 1;
        max-height: 200px;
        overflow-y: auto;
    }
    .icon-item {
        padding: 8px 16px;
        cursor: pointer;
    }
    .icon-item:hover {
        background-color: #f1f1f1;
    }
    .icon-item i {
        margin-right: 8px;
    }
</style>

<div class="modal-dialog" role="document">
    <div class="modal-content">

        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><?= $titulo ?> Tipo Actividad</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

            </button>
        </div>
        <div class="modal-body">
            <form role="form" method="POST" action="" id="formulario">
                <input type="hidden" name="modal_fiche" id="modal_fiche" value="<?= $obj->id; ?>">

                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">
                            <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Nombre</label>
                            <input required="required" placeholder="Nombre del tipo actividad" type="text" name="modal_nombre" id="modal_nombre" class="form-control" value="<?php echo $obj->nombre; ?>" <?php echo $disabled; ?>>
                        </div>

                        <div class="col-md-6">
                            <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Estado</label>
                            <select name="modal_activo" id="modal_activo" class="form-control" required>
                                <option value="1" <?php if (!isset($obj->activo) || $obj->activo == 1) echo 'selected'; ?>>Activo</option>
                                <option value="0" <?php if (isset($obj->activo) && $obj->activo == 0) echo 'selected'; ?>>Inactivo</option>


                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Color</label>
                            <input type="hidden" id="modal_color" name="modal_color" value="<?php echo $obj->color; ?>" >
                            <input required="required" placeholder="Color identificativo" type="text" name="color_mask" id="color_mask" class="form-control" value="<?php echo $obj->color; ?>" <?php echo $disabled; ?>>
                        </div>

                        <div class="col-md-12">
                            <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Ícono</label>
                            <input type="text" id="modal_icono" name="modal_icono" placeholder="Haz clic para seleccionar un ícono" readonly class="form-control" required value="<?php echo $obj->icono; ?>" >
                            <div id="iconoInput" name="iconoInput" class="dropdown-content" style="display:none;" required>
                            </div>                            
                        </div>

                    </div>

                </div>

            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn btn-light-dark" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i>Cancelar</button>

            <?php if (empty($_REQUEST['fiche'])) { ?>
                <button type="button" class="btn btn-primary" id="agregar_bodega" onclick="guardar(event)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Guardar</button>
            <?php } else { ?>
                <button type="button" class="btn btn-danger" id="borrar_bodega" onclick="borrar(<?= $obj->id; ?>)"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Borrar</button>
                <button type="button" class="btn btn-primary" id="agregar_bodega" onclick="guardar(event)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Actualizar</button>

            <?php
            } ?>

        </div>

    </div>
</div>

<script>
    $(document).ready(function() {
        const pickr = Pickr.create({
            el: '#color_mask',
            theme: 'classic', // o 'monolith', 'nano'
            components: {
                // Main components
                preview: true,
                opacity: true,
                hue: true,
                // Input / output Options
                interaction: {
                    hex: true,
                    // rgba: true,
                    input: true,
                    save: true
                }
            }
        });
        pickr.on('save', (color, instance) => {
            $('#modal_color').val(color.toHEXA().toString(0));
            pickr.hide();
        });

        const icons = [
            'fas fa-address-book',
            'fas fa-check',
            'fas fa-phone',
            'fas fa-cog',
            'fas fa-home',
            'fas fa-user',
            'fas fa-envelope',
            'fas fa-camera',
        ];
        icons.forEach(function(icon) {
            $('#iconoInput').append('<div class="icon-item"><i class="' + icon + '"></i></div>');
        });
        $('#modal_icono').click(function() {
            $('#iconoInput').toggle();
        });

        $('.icon-item').on('click', function(){
            const selectedIcon = $(this).find('i').attr('class');
            $('#modal_icono').val(selectedIcon);
            $('#iconoInput').hide();
        })


        

    });
</script>