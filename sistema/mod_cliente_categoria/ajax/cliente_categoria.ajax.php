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
    include ENLACE_SERVIDOR . 'mod_cliente_categoria/object/cliente_categoria.object.php';

    $obj = new Categoria_cliente($dbh);

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

        case 'actualizar_categoria_cliente':
            // Validación al editar
            $id_actual = $_POST['id'];
            if (encontrar_duplicado('diccionario_clientes_categorias', 'label', $_POST['label'], $_SESSION['Entidad'], $id_actual)['total'] > 0) {
                echo json_encode(['exito' => 0, 'mensaje' => 'Esta etiqueta ya existe']);
                exit;
            }

            /************************************************************
            /*
            /*           Modificando
            /*
            /**************************************************************/
            $obj->id = $_POST['id'];
            $obj->label = $_POST['label'];
            $obj->estado = $_POST['estado'];
            $obj->fk_usuario = $_SESSION['usuario'];
            $obj->entidad = $_SESSION['Entidad'];

            $result = $obj->actualizar_categoria_cliente();
            echo json_encode($result);
            break;

        case 'ver_categoria_cliente':
            /************************************************************
            /*
            /*           Visualizando
            /*
            /**************************************************************/
            $result = $obj->fetch($_POST['id']);
            echo json_encode($obj);
            break;

        case 'crear_categoria_cliente':
            // Validación de la etiqueta
            if (encontrar_duplicado('diccionario_clientes_categorias', 'label', $_POST['label'], $_SESSION['Entidad'])['total'] > 0) {
                echo json_encode(['exito' => 0, 'mensaje' => 'Esta etiqueta ya existe']);
                exit;
            }

            /************************************************************
            /*
            /*           Creando
            /*
            /**************************************************************/
            $obj->label = $_POST['label'];
            $obj->fk_usuario = $_SESSION['usuario'];
            $obj->entidad = $_SESSION['Entidad'];

            $result = $obj->crear_categoria_cliente();
            echo json_encode($result);
            break;

        case 'borrar_categoria_cliente':
            $obj->entidad = $_SESSION['Entidad'];
            $obj->borrado_fk_usuario = $_SESSION['usuario'];
            $result = $obj->borrar_categoria_cliente($_POST['id']);
            echo json_encode($result);
            break;

    endswitch;

endif;
?>