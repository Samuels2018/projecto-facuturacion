<!-- Include jQuery Roadmap Plugin -->
<script src="<?= ENLACE_WEB ?>bootstrap/jquery.roadmap.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?= ENLACE_WEB ?>bootstrap/plugins/roadmap.css">
<?php
require_once ENLACE_SERVIDOR . 'mod_crm/object/oportunidad.object.php';
require_once ENLACE_SERVIDOR . 'mod_terceros/object/terceros.object.php';

require_once ENLACE_SERVIDOR . 'mod_tiempo_entrega/object/tiempo_entrega_object.php';
require_once ENLACE_SERVIDOR . 'mod_validez_oferta/object/validez_oferta_object.php';


$Oportunidad = new Oportunidad($dbh, $_SESSION['Entidad']);
$Oportunidad->fetch($_GET['id']);




$Terceros   = new FiTerceros($dbh, $_SESSION['Entidad']);
$Terceros->obtener_listado_terceros();



$Utilidades = new Utilidades($dbh);

$Tiempo_entrega = new Tiempo_entrega($dbh, $_SESSION['Entidad']);
$Validez_oferta = new Validez_oferta($dbh, $_SESSION['Entidad']);

$lista_tiempo_entrega = $Tiempo_entrega->listar();
$lista_validez_oferta = $Validez_oferta->listar();



$accion = ($Oportunidad->id > 0) ?  $Oportunidad->consecutivo : 'Nueva oportunidad';
$accion_boton = ($Oportunidad->id > 0) ?  "Guardar Cambios " . $Oportunidad->consecutivo : 'Crear Nueva oportunidad';


$lista_categorias = $Oportunidad->obtener_listado_categorias();
$lista_prioridades = $Oportunidad->obtener_listado_prioridades();




//vamos a buscar el tercero 
$html_select_contacto = '';
if (isset($_GET['id'])) {
    $cliente = new FiTerceros($dbh, $_SESSION['Entidad']);
    $cliente->rowid = $Oportunidad->fk_tercero;
    $result = $cliente->obtener_listado_contactos();
    foreach ($result as $key => $value) {
        $selected = '';
        if (intval($result[$key]['rowid']) === intval($Oportunidad->fk_tercero_contacto)) {
            $selected = 'selected=selected';
        }
        $html_select_contacto .= '<option  ' . $selected . ' value="' . $result[$key]["rowid"] . '">' . $result[$key]["nombre"] . ' ' . $result[$key]["apellidos"] . '</option>';
    }
}


// if (!$Oportunidad->fecha) {

//     $Oportunidad->fecha = date('Y-m-d');
// }

?>



<input type="hidden" id="oportunidad_id" value="<?php echo $Oportunidad->id; ?>">
<div class="page-meta">
    <nav class="breadcrumb-style-one" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Oportunidades</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $accion; ?></li>
        </ol>
    </nav>
</div>
<div class="row mt-3" style="">

    <div class="col-xs-12">
        <div class="card">
            <div class="card-body table-responsive no-padding" id="form_oportunidad">
                <div class="row">
                    <div class="col-md-8">
                        <?php if ($Oportunidad->id > 0) { ?>
                            <div class="form-group row mt-4">
                                <div class="col-md-3">
                                    <h4>Referencia:</h4>
                                </div>
                                <div class="col-md-3">
                                    <label>
                                        <strong><?php echo $Oportunidad->consecutivo; ?></strong>
                                    </label>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="row mt-4">
                            <div class="col-md-8">
                                <label for="nombre"><i class="fa fa-snowflake-o" aria-hidden="true"></i> Etiqueta</label>
                                <input required="required" type="text" id="etiqueta" name="etiqueta" class="form-control" placeholder="Descripción de la oportunidad" value="<?php echo $Oportunidad->etiqueta; ?>">
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-8">
                                <label for="nombre"><i class="fa fa-fw fa-calendar" aria-hidden="true"></i> Fecha</label>
                                <input required="required" type="date" name="fecha" class="form-control" id="fecha" value="<?php echo $Oportunidad->fecha!=null? date('Y-m-d',strtotime($Oportunidad->fecha)):''; ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <label for="apellidos"><i class="fa fa-fw fa-user" aria-hidden="true"></i> Cliente</label>
                                <select class="form-control select2" id="fk_tercero" name="fk_tercero" style="width: 100% !important;" >
                                    <option value="">Selecciona Cliente</option>
                                    <?php foreach ($Terceros->obtener_listado_terceros as $cliente) {
                                        echo "<option " . (($Oportunidad->fk_tercero == $cliente->rowid) ? "selected='selected'" : "") . " value='" . $cliente->rowid . "'>" . $cliente->nombre_cliente . "</option>";
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <label for="validez"><i class="far fa-file-code"></i> Contacto</label>
                                <select class="form-control" id="fk_contacto" name="fk_contacto" >
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <label for="apellidos"><i class="fa fa-fw fa-square" aria-hidden="true"></i> Categorías</label>
                                <select class="form-control" id="fk_categoria">
                                    <option value="0">Selecciona Categoría</option>
                                    <?php foreach ($lista_categorias as $categorias) {
                                        echo "<option " . (($Oportunidad->fk_categoria == $categorias['rowid']) ? "selected='selected'" : "") . " value='" . $categorias['rowid'] . "'>" . $categorias['etiqueta'] . "</option>";
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <label for="apellidos"><i class="fa fa-fw fa-square" aria-hidden="true"></i> Prioridad</label>
                                <div class="input-group">
                                    <select class="form-control" id="fk_prioridad">
                                        <option value="0">Selecciona prioridad</option>
                                        <?php foreach ($lista_prioridades as $prioridad) {
                                            echo "<option " . (($Oportunidad->fk_prioridad == $prioridad['rowid']) ? "selected='selected'" : "") . " value='" . $prioridad['rowid'] . "'>" . $prioridad['etiqueta'] . "</option>";
                                        } ?>
                                    </select>
                                    <a href="<?= ENLACE_WEB ?>prioridades_listado"></a>
                                    <button title="Editar prioridades" class="btn btn-success" type="button" aria-expanded="false"><i class="fas fa-cog fa-spin"></i></button>
                                </div>
                            </div>
                        </div>



                       
                    <div class="row mt-4">
                        <div class="col-md-8">
                            <label for="validez"><i class="far fa-file-code"></i> Tiempo de Entrega (Días hábiles)</label>
                            <select class="form-control" id="cotizacion_tiempo_entrega">
                                <option value="">Seleccionar</option>
                                <?php foreach($lista_tiempo_entrega as $tiempo_entrega){ ?>
                                    <option value="<?php echo $tiempo_entrega->label; ?>" <?php echo ($Oportunidad->cotizacion_tiempo_entrega == $tiempo_entrega->label) ? 'selected="selected"' : ""; ?>><?php echo $tiempo_entrega->label; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <label for="validez"><i class="far fa-file-code"></i> Validez de la Oferta (Días hábiles)</label>
                            <select class="form-control" id="cotizacion_validez_oferta">
                                <option value="">Seleccionar</option>
                                <?php foreach($lista_validez_oferta as $lista_validez_oferta){ ?>
                                    <option value="<?php echo $lista_validez_oferta->label; ?>" <?php echo ($Oportunidad->cotizacion_validez_oferta == $lista_validez_oferta->label) ? 'selected="selected"' : ""; ?>><?php echo $lista_validez_oferta->label; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                        <div class="row mt-4">
                            <div class="col-md-8">
                                <label for="validez"><i class="far fa-file-code"></i> Usuario Asignado a Oportunidad</label>
                                <select class="form-control" id="fk_usuario_asignado" required>
                                    <option value="">Seleccione una opción</option>
                                    <?php
                                    $Oportunidad->usuarios_disponibles();
                                    foreach ($Oportunidad->usuarios_disponibles as $valor) {
                                        echo "<option " . (($Oportunidad->fk_usuario_asignado == $valor->rowid) ? 'selected="selected"' : "") . " value='" . $valor->rowid . "'>" . $valor->nombre . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-8">
                                <label for="validez"><i class="far fa-user"></i> Recurso Humano</label>
                                <?php
                                $todos_usuarios_disponibles = $Oportunidad->obtener_recurso_humano();
                                $seleccionados_usuarios_disponibles = $Oportunidad->obtener_recurso_humano($Oportunidad->id);
                                ?>
                                <select class="form-control select2" name="a_medida_cisma_cotizaciones_recurso_humano" multiple='multiple' id="a_medida_cisma_cotizaciones_recurso_humano">
                                    <?php
                                    foreach ($todos_usuarios_disponibles as $rowid => $valor) {
                                        echo "<option " . (isset($seleccionados_usuarios_disponibles[$rowid]) ? 'selected="selected"' : "") . " value='" . $rowid . "'>" . $valor['usuario_txt'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-8">
                                <label for="validez">Nota</label>
                                <textarea id="cotizacion_nota" name="cotizacion_nota" class="form-control" rows="10" placeholder="✔ Notas" required><?php echo $Oportunidad->nota;?></textarea>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-8">
                                <i class="fa fa-compass"></i> Funnel:
                                <select id="fk_funnel" name="fk_funnel" class="form-control">
                                    <?php
                                    foreach ($Oportunidad->obtener_listado_estados_funnel() as $id => $valor) {
                                        echo "<option value='" . $id . "' " . (($Oportunidad->fk_funnel == $id) ? "selected='selected'" : "") . ">" . $valor['etiqueta'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-8">
                                <i class="fa fa-compass"></i> Estado:
                                <select id="fk_funnel_detalle" name="fk_funnel_detalle" class="form-control" required>
                                    <?php
                                    foreach ($Oportunidad->obtener_listado_estados_funnel_detalle() as $id => $valor) {
                                        echo "<option value='" . $id . "' " . (($Oportunidad->fk_funnel_detalle == $id) ? "selected='selected'" : "") . ">" . $valor['etiqueta'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <label for="tags"><i class="fa fa-tags" aria-hidden="true"></i> TAGS:</label>
                                <select class="form-control select2" name="tags" multiple='multiple' id="tags">
                                    <?php
                                    $arrayRecuperadoTags = explode(",", $Oportunidad->tags);
                                    foreach ($arrayRecuperadoTags as $item => $value) {
                                        if ($value === '') continue;
                                        echo '<option value="' . $value . '">' . $value . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-4" style="display:none">
                            <div class="col-md-12">
                                <label for="cotizacion_tipo_oferta"><i class="far fa-file-code"></i> Tipo Cotización</label>
                                <select class="form-control" id="cotizacion_tipo_oferta">
                                    <option value="1" <?php echo ($Oportunidad->cotizacion_tipo_oferta == 1) ? 'selected="selected"' : ""; ?>>Normal</option>
                                    <option value="2" <?php echo ($Oportunidad->cotizacion_tipo_oferta == 2) ? 'selected="selected"' : ""; ?>>SICOP u otras plataformas</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-8">
                                <label for="nombre"><i class="fa fa-fw fa-calendar" aria-hidden="true"></i> Fecha de Cierre</label>
                                <input type="date" name="fecha_cierre" class="form-control" id="fecha_cierre" value="<?php echo ($Oportunidad->fecha_cierre!=null?$Oportunidad->fecha_cierre:''); ?>">
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>



</div>
<div class="row mt-3">
    <div class="col-md-12 botonera-footer">
        <?php $href = ($Oportunidad->id > 0) ? 'ver_oportunidad/' . $Oportunidad->id : "ver_oportunidad";  ?>
        <a href="<?php echo ENLACE_WEB . 'oportunidades'; ?>" class="btn btn-outline-primary">Cancelar</a>
        <button href="<?php echo ENLACE_WEB; ?>cisma_cotizaciones_detalle_modificar/<?php echo $Oportunidad->id; ?>" class="btn btn-primary _effect--ripple waves-effect waves-light" onClick="guardar_cotizacion()"><?php echo $accion_boton; ?></button>
    </div>
</div>
<script>
    $("#fk_funnel").change(function() {
        fk_funnel = $(this).val();
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_funnel/class/clases.php",
            beforeSend: function(xhr) {},
            data: {
                "action": "BuscarDetalleFunnel",
                fk_funnel: fk_funnel
            },

        }).done(function(data) {
            $("#fk_funnel_detalle").html(data);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la petición AJAX:", textStatus, errorThrown);
        });
    });
    <?php
    if (!isset($_GET['id'])) {
    ?>
        $("#fk_funnel").trigger("change");
    <?php } ?>

    function guardar_cotizacion() {

        var fecha = $('#fecha').val();
        var oportunidad_id = $('#oportunidad_id').val();
        var fk_tercero = $('#fk_tercero').val();
        var fk_estado_a_medida_cisma_estado_cotizaciones = $("#fk_estado_a_medida_cisma_estado_cotizaciones ").val();
        var tags = $('#tags').val();
        var cotizacion_validez_oferta = $("#cotizacion_validez_oferta").val();
        var cotizacion_tiempo_entrega = $("#cotizacion_tiempo_entrega").val();
        var cotizacion_nota = $("#cotizacion_nota").val();
        var fk_categoria = $("#fk_categoria").val();
        var fk_prioridad = $("#fk_prioridad").val();
        var fk_usuario_asignado = $("#fk_usuario_asignado").val();
        var a_medida_cisma_cotizaciones_recurso_humano = $("#a_medida_cisma_cotizaciones_recurso_humano").val();
        var cotizacion_tipo_oferta = $("#cotizacion_tipo_oferta").val();
        var fk_funnel_detalle = $("#fk_funnel_detalle").val();
        var f_funnel_detalle_text = $("#fk_funnel_detalle").find('option:selected').text();
        var fk_funnel = $("#fk_funnel").val();
        var fk_contacto = $("#fk_contacto").val();
        var etiqueta = $("#etiqueta").val();
        var fecha_cierre = $("#fecha_cierre").val();

        let error = false;

        /* Valida los inputs requeridos */
        const inputTypes = [];
        $('#form_oportunidad input[name][id][value]').each(function(index, element) {
            inputTypes.push({
                name: $(this).attr('id'),
                value: $(this).val(),
                required: ($(this).attr('required') || false)
            })
        });
        $('#form_oportunidad select[name][id]').each(function(index, element) {
            inputTypes.push({
                name: $(this).attr('id'),
                value: $(this).val(),
                required: ($(this).attr('required') || false)
            })
        });
        $('#form_oportunidad textarea[name][id]').each(function(index, element) {
            inputTypes.push({
                name: $(this).attr('id'),
                value: $(this).val(),
                required: ($(this).attr('required') || false)
            })
        });
        inputTypes.push({
                name: 'fk_usuario_asignado',
                value: $('#fk_usuario_asignado').val(),
                required: 'required'
        })
        inputTypes.push({
                name: 'fk_tercero',
                value: $('#fk_tercero').val(),
               // required: 'required'
        })
        inputTypes.map(x => $('#' + x.name).removeClass('input_error'))
        inputTypes.map((x) => {
            if (x.required && x.value == '') {
                $('#' + x.name).addClass('input_error');
                error = true;
            }
            if (x.name == 'fk_usuario_asignado' && x.required) {
                if($('#fk_usuario_asignado').val() == ''){
                    $('#' + x.name).addClass('input_error');
                    error = true;
                }
            }
           
        })

        // Si hay errores, mostrar notificación y detener el envío del formulario
        if (error) {
            add_notification({
                text: 'Faltan Datos Obligatorios',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
            });
            return true;
        }
        /* Valida los inputs requeridos */

        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_crm/class/class.php",
            beforeSend: function(xhr) {

            },

            data: {
                "action": "guardar",
                oportunidad_id: oportunidad_id,
                fecha: fecha,
                fk_tercero: fk_tercero,
                fk_estado_a_medida_cisma_estado_cotizaciones: fk_estado_a_medida_cisma_estado_cotizaciones,
                tags: tags,
                cotizacion_tiempo_entrega: cotizacion_tiempo_entrega,
                cotizacion_validez_oferta: cotizacion_validez_oferta,
                cotizacion_nota: cotizacion_nota,
                fk_categoria: fk_categoria,
                fk_prioridad: fk_prioridad,
                fk_usuario_asignado: fk_usuario_asignado,
                a_medida_cisma_cotizaciones_recurso_humano: a_medida_cisma_cotizaciones_recurso_humano,
                cotizacion_tipo_oferta: cotizacion_tipo_oferta,
                fk_funnel: fk_funnel,
                fk_funnel_detalle: fk_funnel_detalle,
                f_funnel_detalle_text:f_funnel_detalle_text,
                fk_contacto: fk_contacto,
                etiqueta: etiqueta,
                fecha_cierre: fecha_cierre,
                tipo_cambio: '<?php echo $tipo_cambio; ?>'

            },

        }).done(function(data) {


            console.log(data);
            const response = JSON.parse(data);
            console.log('data es: ' + response.error);



            add_notification({
                text: response.mensaje_txt,
                pos: 'top-right',
                actionTextColor: '#fff',
                backgroundColor: '#00ab55',
            });

            if (response.id > 0 && response.error === 0) {
                window.location.href = '<?php echo ENLACE_WEB; ?>ver_oportunidad/' + response.id;
            }


            // Aquí puedes añadir código para actualizar la interfaz de usuario si es necesario
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la petición AJAX:", textStatus, errorThrown);

            add_notification({
                text: 'Error con la Peticion - Vuelve a Intentarlo',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            });


        });



    } // fin de la funcion 
</script>
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<!---------     Javascript de los select 2----------------->
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<?php
$coma = "";
foreach (explode(",", $Oportunidad->tags) as $array) {
    $tags_en_formato .= $coma . '"' . $array . '"';
    $coma = ",";
}
$coma           = "";
$listaDeOpciones = "";
foreach ($Oportunidad->usuarios_disponibles as  $valor) {
    $listaDeOpciones .= $coma . '"' . $valor->nombre . '"';
    $coma = ",";
}

?>
<script>
    $(document).ready(function() {


        let selectedOptions = [];


        //var listaDeOpciones = [<?php echo $listaDeOpciones; ?>];
        $("#fk_tercero").change(function() {
            fk_tercero = $(this).val();


            $.ajax({
                method: "POST",
                url: "<?php echo ENLACE_WEB; ?>mod_terceros/class/terceros.class.php",
                beforeSend: function(xhr) {},

                data: {
                    "action": "buscar_contactos_cliente",
                    fk_tercero: fk_tercero,
                    fk_tercero_selected: "<?php echo $Oportunidad->fk_tercero_contacto; ?>"
                },
            }).done(function(data) {
                $("#fk_contacto").html(data);
                // Aquí puedes añadir código para actualizar la interfaz de usuario si es necesario
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error("Error en la petición AJAX:", textStatus, errorThrown);

                add_notification({
                    text: 'Error con la Peticion - Vuelve a Intentarlo',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a'
                });
            });

        });

        $("#fk_tercero").trigger('change');

        $("#fk_tercero").select2();
        $('#a_medida_cisma_cotizaciones_recurso_humano').select2({
            //  data: listaDeOpciones,
            multiple: true,
            tags: true,
            tokenSeparators: [',', ' '],

            createTag: function(params) {
                // No permite la creación de nuevas etiquetas
                return null;
            },
            maximumInputLength: 0 // No permite la entrada de texto


        });

        // Set selected options
        $('#tags').val(selectedOptions);
        $('#tags').trigger('change');


    });
</script>
<script>
    $(document).ready(function() {

        var listaDeOpcionesTags = [<?php echo $tags_en_formato; ?>];


        $('#tags').val(listaDeOpcionesTags); // Establece las opciones seleccionadas


        $('#tags').select2();
        $('#tags').val(listaDeOpcionesTags); // Establece las opciones seleccionadas
        $('#tags').trigger('change'); // Notifica a Select2 para actualizar el estado
        // Inicializa Select2 tags
        $('#tags').select2({
            tags: true, // Habilitar la entrada de texto
            tokenSeparators: [',', ' '] // Separadores para nuevas etiquetas
        });

        // Desactivar todos los elementos del menú
        $(".menu").removeClass('active');

        $(".mod_crm").addClass('active');
        $(".mod_crm > .submenu").addClass('show');
        $("#nueva_oportunidad").addClass('active');




    });
</script>