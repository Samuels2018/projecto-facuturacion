<?php

session_start();
// USER
if (empty($_SESSION['usuario'])) {
    header("location: " . ENLACE_WEB . "inicio/");
    exit(1);
}

include_once "../../conf/conf.php";

require_once ENLACE_SERVIDOR . 'mod_crm/object/oportunidad.object.php';
require_once ENLACE_SERVIDOR . 'mod_crm_actividades/object/actividades.object.php';
require_once(ENLACE_SERVIDOR . 'mod_adjuntos/object/adjuntos.object.php');
 include_once ENLACE_SERVIDOR. "mod_crm_actividades/object/actividades.object.php";

$Oportunidad = new Oportunidad($dbh, $_SESSION['Entidad']);
$Actividad  = new Actividades($dbh, $_SESSION['Entidad']);
$Adjuntos = new Adjunto($dbh, $_SESSION['Entidad']);



switch ($_POST['action']) {




    case 'guardar':


        $Oportunidad->id                         = $_POST['oportunidad_id'];
        $Oportunidad->fecha                      = $_POST['fecha'];
        $Oportunidad->fk_tercero                 = $_POST['fk_tercero'];
        $Oportunidad->creado_fk_usuario          = $_SESSION['usuario'];
        $Oportunidad->tags                       = $_POST['tags'];
        $Oportunidad->tiempo_entrega  = $_POST['cotizacion_tiempo_entrega'];
        $Oportunidad->validez_oferta  = $_POST['cotizacion_validez_oferta'];
        $Oportunidad->nota            = $_POST['cotizacion_nota'];
        $Oportunidad->fk_categoria               = $_POST['fk_categoria'];
        $Oportunidad->fk_prioridad               = $_POST['fk_prioridad'];
        $Oportunidad->fk_usuario_asignado        = $_POST['fk_usuario_asignado'];
        $Oportunidad->importe_fk_moneda          = $_POST['importe_fk_moneda'];
        $Oportunidad->cotizacion_tipo_oferta                        =   $_POST['cotizacion_tipo_oferta'];
        $Oportunidad->a_medida_cisma_cotizaciones_recurso_humano    =   $_POST['a_medida_cisma_cotizaciones_recurso_humano'];
        $Oportunidad->fk_estado_a_medida_cisma_estado_cotizaciones  =   $_POST['fk_estado_a_medida_cisma_estado_cotizaciones'];
        $Oportunidad->fk_funnel_detalle                              =   $_POST['fk_funnel_detalle'];
        $Oportunidad->fk_funnel                                      =   $_POST['fk_funnel'];
        $Oportunidad->Actividad                  =      $Actividad;
        $Oportunidad->fk_contacto = $_POST['fk_contacto'];
        $Oportunidad->etiqueta = $_POST['etiqueta'];
        $Oportunidad->tipo_cambio = $_POST['tipo_cambio'];
        $Oportunidad->fecha_cierre = $_POST['fecha_cierre'];
        $Oportunidad->f_funnel_detalle_text = $_POST['f_funnel_detalle_text'];

        if ($Oportunidad->id > 0)
        {
            $respuesta = $Oportunidad->Update();
        } else {
            $respuesta = $Oportunidad->Crear();
        }

        $respuesta['a_medida_cisma_cotizaciones_recurso_humano'] = $_POST['a_medida_cisma_cotizaciones_recurso_humano'];

        echo json_encode($respuesta);

        break;


    case 'eliminar_oportunidad':

        $Oportunidad->borrado_fk_usuario = $_SESSION['usuario'];
        $result = $Oportunidad->eliminar_oportunidad($_POST['rowid']);
        echo json_encode($result);
        break;


    case 'guardarTarea':
        //instanciar propiedades
        $Actividad->fk_oportunidad = $_POST['fk_oportunidad'];
        $Actividad->fk_diccionario_actividad = $_POST['fk_diccionario_actividad'];
        $Actividad->vencimiento_fecha = $_POST['vencimiento_fecha'];
        $Actividad->fk_usuario_asignado = $_POST['fk_usuario_asignado'];
        $Actividad->comentario = $_POST['comentario'];
        $Actividad->creado_usuario = $_SESSION['usuario'];
        $Actividad->fk_entidad = $_SESSION['Entidad'];
        $Actividad->fk_estado = 1;
        $Actividad->comentario_cierre = null;
        $Actividad->tipo = 'tarea';
        $guardar = $Actividad->guardarTareaOportunidad();
        echo json_encode($guardar);
        break;


    case 'actualizarTarea':

        //instanciar propiedades
        $Actividad->rowid = $_POST['rowid'];
        $Actividad->comentario = $_POST['comentario'];
        $Actividad->fk_estado =  $_POST['fk_estado'];
        $Actividad->comentario_cierre =  $_POST['comentario_cierre'];
        $Actividad->fecha_cierre =  $_POST['fecha_cierre'];
        $Actividad->fk_usuario_fecha_cierre =  $_SESSION['usuario'];

        $guardar = $Actividad->actualizarActividad();

        echo json_encode($guardar);
        break;



    case 'guardar_servicio':

        $Oportunidad->id                             = $_POST['id'];
        $Oportunidad->cantidad           =  $_POST['cantidad'];
        $Oportunidad->precio_unitario    =  $_POST['precio_unitario'];
        $Oportunidad->label              =   (empty($_POST['nombre'])) ? ' ' : $_POST['nombre'];
        $Oportunidad->fk_producto        = $_POST['fk_producto'];
        $Oportunidad->descuento          = $_POST['descuento'];
        $Oportunidad->descuento_tipo     = $_POST['descuento_tipo'];
        $Oportunidad->tipo_impuesto          = $_POST['impuesto'];
        $Oportunidad->recargo_equivalencia   = $_POST['recargo_equivalencia'];
        $Oportunidad->retencion              = $_POST['retencion'];
        $Oportunidad->detalle     = $_POST['detalle'];
        $Oportunidad->entidad = $_SESSION["Entidad"];
        $guardar = $Oportunidad->servicios_insertar();
        echo json_encode($guardar);
        break;
    case 'actualizar_servicio':
        $Oportunidad->lineaMd5           = $_POST['linea'];
        $Oportunidad->cantidad           =  $_POST['cantidad'];
        $Oportunidad->precio_unitario    =  $_POST['precio_unitario'];
        $Oportunidad->label              =   (empty($_POST['nombre'])) ? ' ' : $_POST['nombre'];
        $Oportunidad->fk_producto        = $_POST['fk_producto'];
        $Oportunidad->descuento          = $_POST['descuento'];
        $Oportunidad->descuento_tipo     = $_POST['descuento_tipo'];
        $Oportunidad->tipo_impuesto          = $_POST['impuesto'];
        $Oportunidad->recargo_equivalencia   = $_POST['recargo_equivalencia'];
        $Oportunidad->retencion              = $_POST['retencion'];
        $Oportunidad->detalle     = $_POST['detalle'];
        $Oportunidad->entidad = $_SESSION["Entidad"];
        $guardar = $Oportunidad->servicios_actualizar();
        echo json_encode($guardar);
        break;

    case 'remover_servicio':
        $servicio_id = $_POST['rowid'];
        $Oportunidad->id = $_POST['oportunidad_id'];
        $result = $Oportunidad->servicios_remover($servicio_id);
        echo json_encode($result);
        break;

    case 'BorrarAdjunto':

        $datos = new stdClass();
        $datos->id = $_POST['id'];
        $datos->fk_documento = $_POST['fk_documento'];
        $datos->label = $_POST['label'];
        $datos->creado_fk_usuario = $_SESSION['usuario'];
        $datos->borrado_fk_usuario = $_SESSION['usuario'];
        $datos->entidad = $_SESSION['Entidad'];
        $datos->tipo_documento = 'oportunidad';
        $Adjuntos->entidad = $_SESSION['Entidad'];
        $result = $Adjuntos->borrar_adjunto($datos);
        echo json_encode($result);

        break;
    case 'ligar_documento':
        require_once ENLACE_SERVIDOR . 'mod_europa_presupuestos/object/presupuestos.object.php';

        $Oportunidad->fetch($_POST['documento']);

        $Presupuesto  = new Presupuesto($dbh, $_SESSION['Entidad']);
        $Presupuesto->moneda = $Presupuesto->configuracion['sistema_transacciones_fk_moneda'];
        $Presupuesto->fk_tercero = $Oportunidad->fk_tercero;
        $Presupuesto->forma_pago = $Oportunidad->forma_pago;
        $Presupuesto->fecha = date("Y-m-d");
        $Presupuesto->fecha_vencimiento = date("Y-m-d");
        $document_id = $Presupuesto->Crear($_SESSION['usuario']);

        if($document_id){
            $sql_update = "UPDATE fi_europa_presupuestos
                SET
                    forma_pago = (SELECT forma_pago from fi_terceros WHERE rowid = :fk_tercero LIMIT 1),
                    subtotal_pre_retencion = (SELECT subtotal_pre_retencion FROM fi_oportunidades WHERE rowid = :oportunidad_id LIMIT 1),
                    impuesto_iva = (SELECT impuesto_iva FROM fi_oportunidades WHERE rowid = :oportunidad_id LIMIT 1),
                    impuesto_iva_equivalencia = (SELECT impuesto_iva_equivalencia FROM fi_oportunidades WHERE rowid = :oportunidad_id LIMIT 1),
                    impuesto_retencion_irpf = (SELECT impuesto_retencion_irpf FROM fi_oportunidades WHERE rowid = :oportunidad_id LIMIT 1),
                    total = (SELECT total FROM fi_oportunidades WHERE rowid = :oportunidad_id LIMIT 1),
                    IVA_0 = (SELECT IVA_0 FROM fi_oportunidades WHERE rowid = :oportunidad_id LIMIT 1),
                    IVA_10 = (SELECT IVA_10 FROM fi_oportunidades WHERE rowid = :oportunidad_id LIMIT 1),
                    IVA_4 = (SELECT IVA_4 FROM fi_oportunidades WHERE rowid = :oportunidad_id LIMIT 1),
                    IVA_21 = (SELECT IVA_21 FROM fi_oportunidades WHERE rowid = :oportunidad_id LIMIT 1),
                    RE_5_2 = (SELECT RE_5_2 FROM fi_oportunidades WHERE rowid = :oportunidad_id LIMIT 1),
                    RE_1_4 = (SELECT RE_1_4 FROM fi_oportunidades WHERE rowid = :oportunidad_id LIMIT 1),
                    RE_0_5 = (SELECT RE_0_5 FROM fi_oportunidades WHERE rowid = :oportunidad_id LIMIT 1),
                    RE_0_75 = (SELECT RE_0_75 FROM fi_oportunidades WHERE rowid = :oportunidad_id LIMIT 1)
                WHERE rowid = :presupuesto_id;";

            $dbUpdate = $dbh->prepare($sql_update);
            $dbUpdate->bindValue(':oportunidad_id', $_POST["documento"], PDO::PARAM_INT);
            $dbUpdate->bindValue(':presupuesto_id', $document_id, PDO::PARAM_INT);
            $dbUpdate->bindValue(':fk_tercero', $Oportunidad->fk_tercero, PDO::PARAM_INT);
            $dbUpdate->execute();
            
            $sql_detalle = "INSERT fi_europa_presupuestos_detalle(
                entidad, fk_documento, fk_producto, tipo, num_linea, label, ref, label_extra, precio_original, precio_costo, precio_unitario,
                cantidad, descuento_tipo, descuento_aplicado, descuento_valor_final, subtotal_pre_retencion, subtotal,
                impuesto_iva_id, impuesto_iva_monto, impuesto_iva_porcentaje, impuesto_iva_equivalencia_aplica, 
                impuesto_iva_equivalencia_monto, impuesto_iva_equivalencia_porcentaje, impuesto_retencion_aplica, 
                impuesto_retencion_monto, impuesto_retencion_porcentaje, total, fecha_creacion, descripcion
            )
            (
                SELECT 
                f.entidad, :documento_creado, f.fk_producto, f.tipo, f.num_linea, f.label, f.ref, f.label_extra, f.precio_original, f.precio_costo, f.precio_unitario,
                f.cantidad, f.descuento_tipo, f.descuento_aplicado, f.descuento_valor_final, f.subtotal_pre_retencion, f.subtotal,
                f.impuesto_iva_id, f.impuesto_iva_monto, f.impuesto_iva_porcentaje, f.impuesto_iva_equivalencia_aplica, 
                f.impuesto_iva_equivalencia_monto, f.impuesto_iva_equivalencia_porcentaje, f.impuesto_retencion_aplica, 
                f.impuesto_retencion_monto, f.impuesto_retencion_porcentaje, f.total, f.fecha_creacion, f.descripcion
                FROM  fi_oportunidades_servicio   f
                WHERE f.fk_documento = :documento
            ); ";
            $db = $dbh->prepare($sql_detalle);
            $db->bindValue(':documento_creado', $document_id, PDO::PARAM_INT);
            $db->bindValue(':documento', $_POST["documento"], PDO::PARAM_INT);
            $db->execute();
            
            $Origen = new documento_mercantil($dbh, $_SESSION["Entidad"]);
            $Origen->id = $_POST['documento'];
            $Origen->entidad = $_SESSION['Entidad'];
            $Origen->documento = "Oportunidad";
            $Origen->documento_detalle = "fi_oportunidades_servicio";

            $result = $Origen->ligar_documento($Presupuesto, $_SESSION["usuario"]);
            echo json_encode($result);
        }
        break;
    default:
        break;
}




function sumarDiasHabiles($fecha, $n)
{
    // Convertir la fecha a un objeto DateTime
    $fechaObj = new DateTime($fecha);

    // Iterar hasta sumar N días hábiles
    while ($n > 0) {
        // Sumar un día
        $fechaObj->modify('+1 day');

        // Verificar si el día actual es laborable (lunes a viernes)
        if ($fechaObj->format('N') <= 5) {
            $n--;
        }
    }

    // Devolver la nueva fecha después de sumar N días hábiles
    return $fechaObj->format('Y-m-d');
}
