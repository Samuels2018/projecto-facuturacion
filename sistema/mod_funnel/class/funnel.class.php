<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);


if (!empty($_POST['action'])) :
    session_start();
    include_once "../../conf/conf.php";
    include_once ENLACE_SERVIDOR . "mod_seguridad/object/seguridad.object.php";
    include_once ENLACE_SERVIDOR . "mod_funnel/object/funnel.object.php";

    $fiFunnel = new FiFunnel($dbh);

    switch ($_POST['action']):

        case 'fetch':

            $fiFunnel->fetch($_POST['rowid']);

            echo json_encode($fiFunnel);
            break;

        case 'nuevo_funnel':

            $fiFunnel->titulo = $_POST['titulo'];
            $fiFunnel->descripcion = $_POST['descripcion'];
            $fiFunnel->color = $_POST['color'];
            $fiFunnel->icono = $_POST['icono'];
            $fiFunnel->creado_fecha = date('Y-m-d H:i:s'); // O puedes usar NOW() en la consulta SQL
            $fiFunnel->creado_fk_usuario = $_SESSION['usuario'];
            $fiFunnel->entidad = $_SESSION['Entidad'];

            $result = $fiFunnel->nuevo();

            echo json_encode($result);
            break;


            case 'guardarTarea':
         
                $fiFunnel->fk_oportunidad = $_POST['fk_oportunidad'];
                $fiFunnel->fk_diccionario_actividad = $_POST['fk_diccionario_actividad'];
                $fiFunnel->vencimiento_fecha = $_POST['vencimiento_fecha'];
                $fiFunnel->fk_usuario_asignado = 1;
                $fiFunnel->comentario = $_POST['comentario']; 
                $fiFunnel->creado_usuario = $_SESSION['usuario'];
                $fiFunnel->entidad = $_SESSION['Entidad'];
                $fiFunnel->tipo = 'tarea';
            
                $result = $fiFunnel->guardarTarea();
            
                echo json_encode($result);
                break;

        case 'modificar_funnel':
            $datos = new stdClass();
            $datos->rowid = $_POST['rowid'];
            $datos->titulo = $_POST['titulo'];
            $datos->descripcion = $_POST['descripcion'];
            $datos->color = $_POST['color'];
            $datos->icono = $_POST['icono'];

            $result = $fiFunnel->modificar($datos);

            echo json_encode($result);
            break;

        case 'eliminar_funnel':
            $datos = new stdClass();
            $datos->rowid = $_POST['rowid'];
            $datos->borrado_fk_usuario = $_SESSION['usuario'];

            try {
                // Inicia la transacción
                $fiFunnel->beginTransaction();

                // Desactiva el agente actual
                $eliminar = $fiFunnel->eliminar($datos);
                if (!$eliminar['exito']) {
                    throw new Exception("Error al eliminar el funnel: " . $eliminar['mensaje']);
                }

                // Registra el nuevo agente
                $eliminar_detalles = $fiFunnel->eliminar_detalles_funnel($datos);
                if (!$eliminar_detalles['exito']) {
                    throw new Exception("Error al eliminar los detalles del funnel: " . $eliminar_detalles['mensaje']);
                }

                // Si todo va bien, confirma la transacción
                $fiFunnel->commit();
                echo json_encode($eliminar_detalles);
            } catch (Exception $e) {
                // Si algo falla, revierte la transacción
                $fiFunnel->rollBack();
                // Devuelve un JSON con el mensaje de error
                echo json_encode(array('exito' => 0, 'mensaje' => $e->getMessage()));
            }

            break;

        case 'obtener_iconos':
            $iconos = $fiFunnel->obtener_diccionario_iconos();

            echo json_encode($iconos);
            break;

        case 'actualizar_detalle':

            $datos = new stdClass();
            $datos->rowid = $_POST['rowid'];
            $datos->estado = $_POST['estado'];
            $result = $fiFunnel->actualizar_detalle($datos);

            echo json_encode($result);
            break;

        case 'crear_detalle':

            $datos = new stdClass();
            $datos->fk_funnel = $_POST['fk_funnel'];
            $datos->etiqueta = $_POST['etiqueta'];
            $datos->descripcion = $_POST['descripcion'];
            $datos->creado_fk_usuario = $_SESSION['usuario'];
            $datos->canvan_mostrar_como_columna = $_REQUEST['canvan_mostrar_como_columna'];

            $result = $fiFunnel->crear_detalle($datos);
            echo json_encode($result);

            break;

        case 'actualizar_detalle':

            $datos = new stdClass();
            $datos->fk_estado = $_POST['fk_estado'];
            $datos->rowid = $_POST['rowid'];

            $result = $fiFunnel->actualizar_detalle($datos);
            echo json_encode($result);

            break;

        case 'eliminar_detalle':

            $datos = new stdClass();
            $datos->rowid = $_POST['rowid'];
            $datos->borrado_fk_usuario = $_SESSION['usuario'];
            $datos->fk_funnel =  $_POST['fk_funnel'];

            try {
                // Inicia la transacción
                $fiFunnel->beginTransaction();

                // Desactiva el agente actual
                $eliminar_detalle = $fiFunnel->eliminar_detalle($datos);
                if (!$eliminar_detalle['exito']) {
                    throw new Exception("Error al eliminar el funnel: " . $eliminar_detalle['mensaje']);
                }

                // Registra el nuevo agente
                $reajustar_posiciones = $fiFunnel->reasignar_posiciones_despues_de_eliminar($datos);
                if (!$reajustar_posiciones['exito']) {
                    throw new Exception("Error al reajustar las posiciones del funnel: " . $reajustar_posiciones['mensaje']);
                }

                // Si todo va bien, confirma la transacción
                $fiFunnel->commit();
                echo json_encode($reajustar_posiciones);
            } catch (Exception $e) {
                // Si algo falla, revierte la transacción
                $fiFunnel->rollBack();
                // Devuelve un JSON con el mensaje de error
                echo json_encode(array('exito' => 0, 'mensaje' => $e->getMessage()));
            }

            break;

        case 'obtener_detalles_funnel_general':

            $fiFunnel->entidad = $_SESSION['Entidad'];
            $result = $fiFunnel->obtener_detalles_funnel_generales($_POST['rowid']);
            echo json_encode($result);
            
        break;

        case 'obtener_detalles_funnel':
            $fiFunnel->entidad = $_SESSION['Entidad'];
            $fecharango = isset($_REQUEST['fecharango']) ? $_REQUEST['fecharango'] : '';
            $busqueda = isset($_REQUEST['busqueda']) ? $_REQUEST['busqueda'] : '';
            $lista_usuarios = isset($_REQUEST['lista_usuarios']) ? $_REQUEST['lista_usuarios'] : '';
            $categorias = isset($_REQUEST['categorias']) ? $_REQUEST['categorias'] : '';
            $tags = isset($_REQUEST['tags']) ? $_REQUEST['tags'] : '';
            $prioridades = isset($_REQUEST['prioridades']) ? $_REQUEST['prioridades'] : '';
            $result = $fiFunnel->obtener_detalles_funnel($_POST['rowid'],$fecharango,$busqueda,$lista_usuarios,$categorias, $prioridades,$tags);
            echo json_encode($result);
        break;


        case 'actualizar_posiciones':

            $datos = new stdClass();
            $datos->fk_funnel = $_POST['fk_funnel'];
            $datos->items = $_POST['itemsData'];
            $result = $fiFunnel->actualizar_posiciones_funnel($datos);
            echo json_encode($result);
        break;

        case 'cambiar_visualizacion_detalle':
           
            $datos = new stdClass();
            $datos->rowid = $_POST['rowid'];
            $datos->fk_funnel = $_POST['fk_funnel'];
            $datos->canvan_mostrar_como_columna = $_POST['canvan_mostrar_como_columna'];
            $result = $fiFunnel->cambiar_visualizacion_detalle($datos);
            echo json_encode($result);

        break;

        case 'cambiar_nombre_detalle':

            $datos = new stdClass();
            $datos->rowid = $_POST['rowid'];
            $datos->fk_funnel = $_POST['fk_funnel'];
            $datos->etiqueta = $_POST['etiqueta'];
            $result = $fiFunnel->cambiar_nombre_detalle($datos);

            echo json_encode($result);
        break;

        default:
            echo 'Accion no definida';
            break;

    endswitch;
endif;
