<?php
// VALID DEFINITIO ACTION
if (!empty($_POST['action'])) :
    session_start();

    // Si no hay usuario autenticado, cerrar conexión
    if (!isset($_SESSION['usuario'])) {
        echo acceso_invalido();
        exit(1);
    }

    include_once("../../conf/conf.php");
    include ENLACE_SERVIDOR . 'mod_diccionario_listas_precios/object/lista_precios_object.php';

    $obj = new ListaPreciosClientes($dbh);

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

        case 'actualizar_lista_precios':

            // Validación al editar
            $id_actual = $_POST['id'];
            if (encontrar_duplicado('fi_productos_precios_clientes_listas', 'etiqueta', $_POST['etiqueta'], $_SESSION['Entidad'], $id_actual)['total'] > 0) {
                echo json_encode(['exito' => 0, 'mensaje' => 'Esta etiqueta ya existe']);
                exit;
            }

            /************************************************************
            /*
            /*           Modificando  
            /*
            /**************************************************************/
            $obj->id = $_POST['id'];
            $obj->etiqueta = $_POST['etiqueta'];
            $obj->activo = $_POST['estado'];
            $obj->creado_fk_usuario = $_SESSION['usuario'];
            $obj->entidad = $_SESSION['Entidad'];
            $result = $obj->actualizar_lista_precios();
            echo json_encode($result);

            break;

        case 'ver_lista_precios':

            /************************************************************
            /*
            /*           Ver  
            /*
            /**************************************************************/
            $result = $obj->fetch($_POST['id']);
            echo json_encode($obj);

            break;

        case 'crear_lista_precios':

            // Validación de la etiqueta
            if (encontrar_duplicado('fi_productos_precios_clientes_listas', 'etiqueta', $_POST['etiqueta'], $_SESSION['Entidad'])['total'] > 0) {
                echo json_encode(['exito' => 0, 'mensaje' => 'Esta etiqueta ya existe']);
                exit;
            }

            /************************************************************
            /*
            /*           Creando  
            /*
            /**************************************************************/

            $obj->etiqueta = $_POST['etiqueta'];
            $obj->creado_fk_usuario = $_SESSION['usuario'];
            $obj->entidad = $_SESSION['Entidad'];
            $obj->activo = $_POST['estado'];

            $result = $obj->crear_lista_precios();
            echo json_encode($result);

            break;

        case 'borrar_lista_precios':
            $obj->entidad = $_SESSION['Entidad'];
            $obj->borrado_fk_usuario = $_SESSION['usuario'];
            $result = $obj->borrar_lista_precios($_POST['id']);
            echo json_encode($result);
            break;

    endswitch;
endif;
