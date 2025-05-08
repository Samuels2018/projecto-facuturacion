<?php
ini_set('display_errors', 0);


if (!empty($_POST['action'])) :
    session_start();
    include_once "../../conf/conf.php";
    include_once ENLACE_SERVIDOR . "mod_lead/object/lead.object.php";
    include_once ENLACE_SERVIDOR . "mod_funnel/object/funnel.object.php";
    include_once ENLACE_SERVIDOR. "mod_crm_actividades/object/actividades.object.php";

    $oportunidad = new Lead($dbh, $_SESSION['Entidad']);
    $fiFunnel = new FiFunnel($dbh); 
    $Actividades  = new Actividades($dbh,$_SESSION['Entidad']);


    switch ($_POST['action']):

        case 'fetch':
            $oportunidad->fetch($_POST['rowid']);
            echo json_encode($oportunidad);
            break;

        case 'nueva_oportunidad':
            $datos = new stdClass();
            $datos->entidad = $_SESSION['Entidad'];
            $datos->fk_funnel = $_POST['fk_funnel'];
            $datos->fk_contacto = $_POST['fk_contacto'];
            $datos->fk_tercero = $_POST['fk_tercero'];
            $datos->fk_estado = $_POST['fk_estado'];
            $datos->etiqueta = $_POST['etiqueta'];
            $datos->nota = $_POST['nota'];
            $datos->fk_usuario_asignado = $_POST['fk_usuario_asignado'];
            $datos->creado_fk_usuario = $_SESSION['usuario'];
            $datos->servicios = $_POST['servicios'];
            $datos->fk_funnel_detalle = $_POST['fk_estado'];
            $datos->tags = $_POST['tags'];
            $datos->fk_funnel_detalle = $_POST['fk_funnel_detalle'];
            $datos->importe = $_POST['importe'];
           
            $result = $oportunidad->nuevo($datos);

            echo json_encode($result);
            break;

        case 'modificar_oportunidad':
            $datos = new stdClass();
            $datos->rowid = $_POST['rowid'];
            $datos->entidad = $_SESSION['Entidad'];
            $datos->fk_funnel = $_POST['fk_funnel'];
            $datos->fk_contacto = $_POST['fk_contacto'];
            $datos->fk_tercero = $_POST['fk_tercero'];
            $datos->fk_estado = $_POST['fk_estado'];
            $datos->etiqueta = $_POST['etiqueta'];
            $datos->nota = $_POST['nota'];
            $datos->fk_usuario_asignado = $_POST['fk_usuario_asignado'];
            $datos->fk_usuario_modificado = $_SESSION['usuario'];
            $datos->fk_funnel_detalle = $_POST['fk_funnel_detalle'];
            $datos->tags = $_POST['tags'];
            $datos->servicios = $_POST['servicios'];
            $datos->importe = $_POST['importe'];
        
            $result = $oportunidad->modificar($datos);
            echo json_encode($result);
            break;

        case 'eliminar_oportunidad':
            $datos = new stdClass();
            $datos->rowid = $_POST['rowid'];
            $datos->borrado_fk_usuario = $_SESSION['usuario'];

            // Desactiva la oportunidad
            $eliminar = $oportunidad->eliminar($datos);

            echo json_encode($eliminar);
            break;

        case 'obtener_tareas_funnel':

            $oportunidad->entidad = $_SESSION['Entidad'];
            $rangofecha = isset($_REQUEST['rangofecha']) ? $_REQUEST['rangofecha'] : '';
            $busqueda = isset($_REQUEST['busqueda']) ? $_REQUEST['busqueda'] : '';
            $lista_usuarios = isset($_REQUEST['lista_usuarios']) ? $_REQUEST['lista_usuarios'] : '';
            $categorias = isset($_REQUEST['categorias']) ? $_REQUEST['categorias'] : '';
            $tags = isset($_REQUEST['tags']) ? $_REQUEST['tags'] : '';
            $prioridades = isset($_REQUEST['prioridades']) ? $_REQUEST['prioridades'] : '';
            $result = $oportunidad->obtener_tareas_funnel($_POST['rowid'],$rangofecha,$busqueda,$lista_usuarios,$categorias, $prioridades,$tags);
            echo json_encode($result);
            break;

        case 'cambiar_estado_oportunidad':

            $datos = new stdClass();
            $datos->rowid = $_POST['rowid'];
            $datos->fk_funnel_detalle = $_POST['fk_funnel_detalle'];
            $datos->fk_usuario_modificado = $_SESSION['usuario'];
            $datos->posiciones = $_POST['posiciones'];

            $fecharango = $_REQUEST['fecharango'] ? $_REQUEST['fecharango'] : '';
            $busqueda = $_REQUEST['busqueda'] ? $_REQUEST['busqueda'] : '';
            $lista_usuarios = isset($_REQUEST['lista_usuarios']) ? $_REQUEST['lista_usuarios'] : '';
            $categorias = isset($_REQUEST['categorias']) ? $_REQUEST['categorias'] : '';
            $tags = isset($_REQUEST['tags']) ? $_REQUEST['tags'] : '';
            

            $prioridades = isset($_REQUEST['prioridades']) ? $_REQUEST['prioridades'] : '';

            try {
                // Inicia la transacción
                $oportunidad->beginTransaction();

                // acmbia estado
                $actualizar_detalle = $oportunidad->cambiar_estado_oportunidad($datos);
                if (!$actualizar_detalle['exito']) {
                    throw new Exception("Error actualizar el estado de la tarea: " . $actualizar_detalle['mensaje']);
                }

                //actualizar_posiciones_oportunidades($datos)
                $actualizar_posiciones = $oportunidad->actualizar_posiciones_oportunidades($datos);
                if (!$actualizar_posiciones['exito']) {
                    throw new Exception("Error actualizar el estado de la tarea: " . $actualizar_posiciones['mensaje']);
                }

                // Registra el log
                $registrar_log_oportunidad = $oportunidad->registrar_log_oportunidad($datos);
                if (!$registrar_log_oportunidad['exito']) {
                    throw new Exception("Error al registrar el log: " . $registrar_log_oportunidad['mensaje']);
                }

                //vamos a guardar los totales aqui
                $fiFunnel->entidad = $_SESSION['Entidad'];
                $adicional_totales = $fiFunnel->obtener_detalles_funnel($_REQUEST['fk_funnel'],$fecharango,$busqueda,$lista_usuarios,$categorias,$prioridades,$tags);
                $registrar_log_oportunidad['totales_funnel'] = $adicional_totales;

                // Si todo va bien, confirma la transacción
                $oportunidad->commit();

               $Actividades->fk_oportunidad                =    $_REQUEST['rowid'];
               $Actividades->fk_diccionario_actividad      =       10;
               $Actividades->vencimiento_fecha             =       date("Y-m-d");
               $Actividades->creado_usuario                =    $_REQUEST['fk_usuario_asignado'];
               $Actividades->comentario                    =    $_REQUEST['comentario_funnel_padre'];
               $Actividades->fk_usuario_asignado           =    $_REQUEST['fk_usuario_asignado'];
               $Actividades->fk_estado                     =       1; 
               $Actividades->comentario_cierre             =       "";
               $Actividades->tipo                          =       "timeline";
                //Guardar oportunidad
               $Actividades->guardarTareaOportunidad();
               echo json_encode($registrar_log_oportunidad);


            } catch (Exception $e) {
                // Si algo falla, revierte la transacción
                $oportunidad->rollBack();
                // Devuelve un JSON con el mensaje de error
                echo json_encode(array('exito' => 0, 'mensaje' => $e->getMessage()));
            }





            // $result = $oportunidad->cambiar_estado_oportunidad($datos);

            // echo json_encode($result);
            break;

        case 'obtener_servicios_tarea':
            $datos = new stdClass();
            $datos->servicios = $_POST['servicios'];
            $datos->entidad = $_SESSION['Entidad'];
            $datos->fk_oportunidad = $_POST['fk_oportunidad'];
            $result = $oportunidad->obtener_servicios_tarea($datos);

            echo json_encode($result);
            break;

        default:
            echo 'Accion no definida';
            break;

    endswitch;
endif;
