<?php

if (!empty($_POST['action'])):
    session_start();

    if (!isset($_SESSION['usuario'])) {
        echo acceso_invalido();
        exit(1);
    }

    include_once("../../conf/conf.php");
    include ENLACE_SERVIDOR . 'mod_rutas/object/rutas.object.php';

    $obj = new Diccionario_ruta($dbh);

    if (!empty($_POST['id'])) {
        $obj->fetch($_POST['id']);
        if ($obj->entidad != $_SESSION['Entidad']) {
            echo json_encode(['exito' => 0, 'mensaje' => 'No tienes acceso a esta sección']);
            exit(1);
        }
    }

    switch ($_POST['action']):

        case 'actualizar_ruta':

            $id_actual = $_POST['id'];
            if (encontrar_duplicado('diccionario_rutas', 'label', $_POST['label'], $_SESSION['Entidad'], $id_actual)['total'] > 0) {
                echo json_encode(['exito' => 0, 'mensaje' => 'Esta etiqueta ya existe']);
                exit;
            }

            $obj->id = $_POST['id'];
            $obj->label = $_POST['label'];
            $obj->estado = $_POST['estado'];

            $obj->fk_usuario = $_SESSION['usuario'];
            $obj->entidad = $_SESSION['Entidad'];

            $result = $obj->actualizar_ruta();
            echo json_encode($result);
            break;

        case 'ver_rutas':

            $result = $obj->fetch($_POST['id']);
            echo json_encode($obj);
            break;

        case 'crear_ruta':

            if (encontrar_duplicado('diccionario_rutas', 'label', $_POST['label'], $_SESSION['Entidad'])['total'] > 0) {
                echo json_encode(['exito' => 0, 'mensaje' => 'Esta etiqueta ya existe']);
                exit;
            }

            $obj->label = $_POST['label'];
            $obj->fk_usuario = $_SESSION['usuario'];
            $obj->entidad = $_SESSION['Entidad'];
            $obj->estado = $_POST['estado'];

            $result = $obj->crear_ruta();
            echo json_encode($result);
            break;

        case 'borrar_ruta':
            $obj->entidad = $_SESSION['Entidad'];
            $obj->borrado_fk_usuario = $_SESSION['usuario'];
            $result = $obj->borrar_ruta($_POST['id']);
            echo json_encode($result);
            break;

    endswitch;

endif;
?>