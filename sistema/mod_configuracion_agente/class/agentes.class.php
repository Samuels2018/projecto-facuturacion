<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

if (!empty($_POST['action'])):
    session_start();
    include_once "../../conf/conf.php";
    include_once ENLACE_SERVIDOR . "mod_configuracion_agente/object/agente.object.php";

    $configuracionAgente = new Agente($dbh);

    switch ($_POST['action']):

    case 'nuevo_agente':
        $configuracionAgente->nombre = $_POST['nombre'];
        $configuracionAgente->email = $_POST['email'];
        $configuracionAgente->movil = $_POST['movil'];
        $configuracionAgente->telefono = $_POST['telefono'];
        $configuracionAgente->meta = $_POST['meta'];
        $configuracionAgente->comision = $_POST['comision'];
        $configuracionAgente->cedula = $_POST['cedula'];
        $configuracionAgente->fk_tipo_identificacion = $_POST['fk_tipo_identificacion'];
        $configuracionAgente->observacion = $_POST['observacion'];
        $configuracionAgente->activo = $_POST['activo'];
        $configuracionAgente->creado_fecha = date('Y-m-d H:i:s'); // O puedes usar NOW() en la consulta SQL
        $configuracionAgente->creado_fk_usuario = $_SESSION['usuario'];
        $configuracionAgente->entidad  = $_SESSION['Entidad'];

        $result = $configuracionAgente->nuevo();

        echo json_encode($result);
        break;

    case 'modificar_agente':
        $configuracionAgente->rowid = $_POST['rowid'];
        $configuracionAgente->nombre = $_POST['nombre'];
        $configuracionAgente->email = $_POST['email'];
        $configuracionAgente->fk_tipo_identificacion = $_POST['fk_tipo_identificacion'];
        $configuracionAgente->movil = $_POST['movil'];
        $configuracionAgente->telefono = $_POST['telefono'];
        $configuracionAgente->meta = $_POST['meta'];
        $configuracionAgente->comision = $_POST['comision'];
        $configuracionAgente->cedula = $_POST['cedula'];
        $configuracionAgente->observacion = $_POST['observacion'];
        $configuracionAgente->activo = $_POST['activo'];
        $configuracionAgente->creado_fecha = $_POST['creado_fecha'];
        $configuracionAgente->creado_fk_usuario = $_POST['creado_fk_usuario'];
        $configuracionAgente->entidad  = $_SESSION['Entidad'];
        
        $result = $configuracionAgente->modificar();

        echo json_encode($result);
        break;

    case 'eliminar_agente':
        $configuracionAgente->rowid = $_POST['rowid'];
        $configuracionAgente->borrado_fk_usuario = $_SESSION['usuario'];

        $result = $configuracionAgente->eliminar($configuracionAgente->rowid);

        echo json_encode($result);
        break;

        case 'actualizar_agente':
            $datos = new stdClass();
            $datos->fk_tercero = $_POST['fk_tercero'];
            $datos->fk_agente = $_POST['fk_agente'];
            $datos->creado_fk_usuario = $_SESSION['usuario'];
            $datos->borrado_fk_usuario = $_SESSION['usuario'];
        
            try {
                // Inicia la transacción
                $configuracionAgente->beginTransaction();
        
                // Desactiva el agente actual
                $desactivar = $configuracionAgente->desactivar_agente_actual($datos);
                if (!$desactivar['exito']) {
                    throw new Exception("Error al desactivar el agente actual: " . $desactivar['mensaje']);
                }
        
                // Registra el nuevo agente
                $registrar = $configuracionAgente->registrar_agente_actual($datos);
                if (!$registrar['exito']) {
                    throw new Exception("Error al registrar el nuevo agente: " . $registrar['mensaje']);
                }
        
                // Si todo va bien, confirma la transacción
                $configuracionAgente->commit();
                echo json_encode($registrar);
            } catch (Exception $e) {
                // Si algo falla, revierte la transacción
                $configuracionAgente->rollBack();
                // Devuelve un JSON con el mensaje de error
                echo json_encode(array('exito' => 0, 'mensaje' => $e->getMessage()));
            }
            break;
    default:
        echo 'Accion no definida';
        break;

    endswitch;
endif;
