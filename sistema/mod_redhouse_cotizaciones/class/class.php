<?php


session_start();



include("../../conf/conf.php");

require_once ENLACE_SERVIDOR . 'mod_redhouse_cotizaciones/object/redhouse.cotizaciones.object.php';

include_once ENLACE_SERVIDOR . "mod_redhouse_crm/object/actividades.object.php";

//Esto para enviar el correo
include_once(ENLACE_SERVIDOR . "mail_sys/mail/PHPMailer/src/EnviarCorreoSmtp.php");

require_once ENLACE_SERVIDOR . 'mod_usuarios/object/usuarios.object.php';




$Cotizacion = new redhouse_Cotizacion($dbh, $_SESSION['Entidad']);
$Actividad = new Actividades($dbh);


//Para traernos el correo
$usuario = new usuario($dbh);
$usuario->buscar_data_usuario($_SESSION['usuario']);




switch ($_POST['action']) {

    case 'guardar':


        $Cotizacion->id                         = $_POST['cotizacion_id'];
        $Cotizacion->cotizacion_fecha           = $_POST['cotizacion_fecha'];
        $Cotizacion->fk_tercero                 = $_POST['fk_tercero'];
        $Cotizacion->fk_contacto =              $_POST['fk_contacto'];
        $Cotizacion->creado_fk_usuario          = $_SESSION['usuario'];
        $Cotizacion->cotizacion_tags            = $_POST['tags'];
        $Cotizacion->cotizacion_tiempo_entrega  = $_POST['cotizacion_tiempo_entrega'];
        $Cotizacion->cotizacion_validez_oferta  = $_POST['cotizacion_validez_oferta'];
        $Cotizacion->cotizacion_nota            = $_POST['cotizacion_nota'];
        //Tomara por defecto Sin categoria
        $Cotizacion->fk_categoria               = !empty($_POST['fk_categoria']) ? $_POST['fk_categoria'] : 1;
        $Cotizacion->fk_usuario_asignado        = $_POST['fk_usuario_asignado'];
        $Cotizacion->fk_moneda                  = $_POST['fk_moneda'];


        $Cotizacion->Actividad          = $Actividad;
        $Cotizacion->cotizacion_tipo_oferta     =    $_POST['cotizacion_tipo_oferta'];
        $Cotizacion->a_medida_redhouse_cotizaciones_recurso_humano     = $_POST['a_medida_redhouse_cotizaciones_recurso_humano'];
        $Cotizacion->fk_estado_a_medida_redhouse_estado_cotizaciones   = $_POST['fk_estado_a_medida_redhouse_estado_cotizaciones'];


        $Cotizacion->cotizacion_proyecto = $_POST['cotizacion_proyecto'];

        $Cotizacion->cotizacion_descripcion_proyecto = $_POST['cotizacion_descripcion_proyecto'];

        $Cotizacion->cotizacion_lugar_proyecto = $_POST['cotizacion_lugar_proyecto'];
        $Cotizacion->cotizacion_fecha_proyecto = $_POST['cotizacion_fecha_proyecto'];

        $Cotizacion->cotizacion_contacto_proyecto = $_POST['cotizacion_contacto_proyecto'];
        $Cotizacion->cotizacion_tipo_cambio = $_POST['cotizacion_tipo_cambio'];



        if ($Cotizacion->id > 0) {
            $respuesta = $Cotizacion->Update();
        } else {
            $respuesta = $Cotizacion->Crear();
        }

        $respuesta['a_medida_redhouse_cotizaciones_recurso_humano'] = $_POST['a_medida_redhouse_cotizaciones_recurso_humano'];

        echo json_encode($respuesta);
        break;


    case 'guardarTarea':

        $actividad = new Actividades($dbh);
        //instanciar propiedades
        $actividad->fk_cotizacion = $_POST['fk_cotizacion'];
        $actividad->fk_diccionario_actividad = $_POST['fk_diccionario_actividad'];
        $actividad->vencimiento_fecha = $_POST['vencimiento_fecha'];
        $actividad->fk_usuario_asignado = $_POST['fk_usuario_asignado'];
        //Nuevo campo para enviar 
        $actividad->email_usuario_asignado = $_POST['email_usuario_asignado'];
        $actividad->comentario = $_POST['comentario'];
        $actividad->creado_usuario = $_SESSION['usuario'];
        $actividad->fk_estado = 1;
        $actividad->comentario_cierre = null;
        $actividad->tipo = 'tarea';

        $guardar = $actividad->actividad_insertar();


        $nombre_usuario_asignado = $_POST['nombre_usuario_asignado'];
        $nombre_actividad = $_POST['nombre_actividad'];

        $attachments = array();

        $subject = 'Asignación de tarea (' . $nombre_actividad . ')';

        $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
            }
            .container {
                width: 100%;
                max-width: 600px;
                margin: 0 auto;
                border: 1px solid #ddd;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            .header {
                background-color: #4CAF50;
                color: white;
                padding: 10px;
                text-align: center;
                border-top-left-radius: 5px;
                border-top-right-radius: 5px;
            }
            .content {
                padding: 20px;
            }
            .content p {
                margin: 0 0 10px;
            }
            .footer {
                background-color: #f9f9f9;
                padding: 10px;
                text-align: center;
                border-bottom-left-radius: 5px;
                border-bottom-right-radius: 5px;
                font-size: 12px;
                color: #777;
            }
            .table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }
            .table th, .table td {
                border: 1px solid #ddd;
                padding: 10px;
                text-align: left;
            }
            .table th {
                background-color: #f2f2f2;
            }
            .image-container {
                text-align: center;
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h2>Asignación de Tarea</h2>
            </div>
            <div class="content">
                <p>Estimado usuario <strong>' . $nombre_usuario_asignado . '</strong>,</p>
                <p>Se le ha asignado una tarea: <strong>' . $nombre_actividad . '</strong></p>
                <table class="table">
                    <tr>
                        <th>Fecha de Vencimiento</th>
                        <td>' . $_POST['vencimiento_fecha'] . '</td>
                    </tr>
                    <tr>
                        <th>Comentario</th>
                        <td>' . $_POST['comentario'] . '</td>
                    </tr>
                </table>
                <div class="image-container">
                    <img src="' . ENLACE_WEB . '/bootstrap/img/logo-redhouse.jpeg" width="350pt" alt="Redhouse PDF">
                </div>
                <p>Por favor, asegúrese de completar la tarea antes de la fecha de vencimiento.</p>
            </div>
            <div class="footer">
                <p>Este es un mensaje automático, por favor no responda a este correo.</p>
            </div>
        </div>
    </body>
    </html>
    ';

        //   $para = $_POST['email_usuario_asignado'];
        $para = 'zagrelocigra-5944@yopmail.com';
        $debug = 0;

        //$respuesta = Email_SMPT($dbh, $html, $para, $attachments,'enviar_email_logico.php', $subject, $debug,1,"Redhouse Cotización");

        $respuesta = Email_SMPT($dbh, $html, $para, $attachments, 'enviar_email_logico.php', $subject, $debug, 1, "Redhouse Cotización", $usuario->acceso_usuario);


        echo json_encode($guardar);
        break;


    case 'actualizarTarea':
        $actividad = new Actividades($dbh);
        //instanciar propiedades
        $actividad->rowid = $_POST['rowid'];
        $actividad->comentario = $_POST['comentario'];
        $actividad->fk_estado =  $_POST['fk_estado'];
        $actividad->comentario_cierre =  $_POST['comentario_cierre'];

        $guardar = $actividad->actualizarActividad();

        echo json_encode($guardar);
        break;

    case 'eliminar_cotizacion':


        $Cotizacion->borrado_fk_usuario = $_SESSION['usuario'];
        $result = $Cotizacion->eliminar_cotizacion($_POST['rowid']);
        echo json_encode($result);



    break;


    case 'guardar_servicio':

        $Cotizacion->id                             = $_POST['cotizacion_id'];
        $Cotizacion->fk_producto                    = $_POST['servicio_fk_producto'];
        $Cotizacion->servicio_comentario            = $_POST['servicio_comentario'];
        $Cotizacion->servicio_cantidad              = $_POST['servicio_cantidad'];
        $Cotizacion->servicio_precio_unitario       = $_POST['servicio_precio_unitario'];
        $Cotizacion->servicio_precio_tipo_impuesto  = $_POST['servicio_precio_tipo_impuesto'];
        $Cotizacion->creado_fk_usuario              = $_SESSION['usuario'];
        $Cotizacion->cantidad_dias                  = $_POST['servicio_cantidad_dias'];

        $Cotizacion->servicio_tipo_duracion = $_POST['servicio_tipo_duracion'];

        $guardar = $Cotizacion->servicios_insertar();
        echo json_encode($guardar);
        break;
    case 'actualizar_servicio':
        $Cotizacion->id                             = $_POST['cotizacion_id'];
        $Cotizacion->fk_producto                    = $_POST['servicio_fk_producto'];
        $Cotizacion->servicio_comentario            = $_POST['servicio_comentario'];
        $Cotizacion->servicio_cantidad              = $_POST['servicio_cantidad'];
        $Cotizacion->servicio_precio_unitario       = $_POST['servicio_precio_unitario'];
        $Cotizacion->servicio_precio_tipo_impuesto  = $_POST['servicio_precio_tipo_impuesto'];
        $Cotizacion->creado_fk_usuario              = $_SESSION['usuario'];

        $Cotizacion->cantidad_dias                  = $_POST['servicio_cantidad_dias'];

        $Cotizacion->servicio_tipo_duracion = $_POST['servicio_tipo_duracion'];

        $guardar = $Cotizacion->servicios_actualizar($_POST['rowid']);
        echo json_encode($guardar);
        break;

    case 'remover_servicio':
        $Cotizacion->id  = $_POST['cotizacion_id'];
        $resultado = $Cotizacion->servicios_remover($_POST['rowid']);
        echo json_encode($resultado);
        break;


    case 'BorrarAdjunto':

        $datos = new stdClass();
        $datos->id = $_POST['id'];
        $datos->fk_cotizacion = $_POST['fk_cotizacion'];
        $datos->label = $_POST['label'];
        $datos->creado_fk_usuario = $_SESSION['usuario'];
        $datos->borrado_fk_usuario = $_SESSION['usuario'];
        $result = $Cotizacion->borrado_adjunto_cotizacion($datos);
        echo json_encode($result);

        break;


    case 'RefrescarAdjuntos':
        $listado_adjuntos = $Cotizacion->obtener_adjuntos_cotizacion($_POST['fk_cotizacion']);
        foreach ($listado_adjuntos as $adjunto) {

            $url_enlace =  ENLACE_WEB . 'servir_adjuntos_cotizaciones?img=' . $_SESSION['Entidad'] . '/cotizacion/' . $adjunto->label;
?>
            <span class="badge badge-info">
                <a download="<?php echo $adjunto->descripcion; ?>" href="<?php echo $url_enlace; ?>" style="color: white;"><i class="fa fa-paperclip"></i><?php echo $adjunto->descripcion; ?></a>
            </span>
<?php }

        break;

    default:
        # code...
        break;
}
