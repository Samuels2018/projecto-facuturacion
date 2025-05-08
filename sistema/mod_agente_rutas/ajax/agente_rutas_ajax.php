<?php

// VALID DEFINITIO ACTION
if (!empty($_POST['action'])):
    session_start();

    // Si no hay usuario autenticado, cerrar conexión
    if (!isset($_SESSION['usuario'])) {
        echo acceso_invalido();
        exit(1);
    }

    include_once("../../conf/conf.php");
    include ENLACE_SERVIDOR . 'mod_agente_rutas/object/agente_rutas_object.php';

    $obj = new Agente_rutas($dbh);

    // Bloque validación entidad
    if (!empty($_POST['id'])) {
        $obj->fetch($_POST['id']);
        if ($obj->entidad != $_SESSION['Entidad']) {
            echo json_encode(['exito' => 0, 'mensaje' => 'No tienes acceso a esta sección']);
            exit(1);
        }
    }

    // VALID ACTION
    switch ($_POST['action']):

        case 'actualizar_agente_rutas':
            // Validación al editar
            $id_actual = $_POST['id'];
            if (encontrar_duplicado('diccionario_agente_rutas', 'descripcion', $_POST['descripcion'], $_SESSION['Entidad'], $id_actual)['total'] > 0) {
                echo json_encode(['exito' => 0, 'mensaje' => 'Esta ruta ya existe']);
                exit;
            }

            /************************************************************
            /*
            /*           Modificando
            /*
            /**************************************************************/
            $obj->id = $_POST['id'];
            $obj->fk_ruta = $_POST['fk_ruta'];
            $obj->fk_agente = $_POST['fk_agente'];
            $obj->activo = $_POST['activo'];
            $obj->fk_usuario = $_SESSION['usuario'];
            $obj->entidad = $_SESSION['Entidad'];
            $result = $obj->actualizar_agente_rutas();
            echo json_encode($result);
            break;

        case 'ver_agente_rutas':
            /************************************************************
            /*
            /*           Visualizando
            /*
            /**************************************************************/
            $result = $obj->fetch($_POST['id']);
            echo json_encode($obj);
            break;

        case 'crear_agente_rutas':
            // Validación de la ruta
            /*if (encontrar_duplicado('diccionario_agente_rutas', 'descripcion', $_POST['descripcion'], $_SESSION['Entidad'])['total'] > 0) {
                echo json_encode(['exito' => 0, 'mensaje' => 'Esta ruta ya existe']);
                exit;
            }*/

            /************************************************************
            /*
            /*           Creando
            /*
            /**************************************************************/
            $obj->fk_ruta = $_POST['fk_ruta'];
            $obj->activo = $_POST['activo'];
            $obj->fk_agente = $_POST['fk_agente'];
            $obj->fk_usuario = $_SESSION['usuario'];
            $obj->entidad = $_SESSION['Entidad'];
            $result = $obj->crear_agente_ruta();
            echo json_encode($result);
            break;

        case 'borrar_agente_rutas':
            $obj->entidad = $_SESSION['Entidad'];
            $obj->borrado_fk_usuario = $_SESSION['usuario'];
            $result = $obj->borrar_agente_rutas($_POST['id']);
            echo json_encode($result);
            break;

    endswitch;

endif;
?>
