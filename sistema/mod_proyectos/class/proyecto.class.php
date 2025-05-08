<?php

SESSION_START();
include_once("../../conf/conf.php");

// Verificar la sesión del usuario
if(empty($_SESSION['usuario']) or empty($_SESSION['Entidad'])) {
    echo acceso_invalido();
    exit(1);
}

require_once(ENLACE_SERVIDOR."mod_proyectos/object/Proyectos.object.php");
$Proyectos = new Proyectos($dbh, $_SESSION['Entidad']);

// Validar si se envió una acción
if (!empty($_POST['action'])):
    $nombre = $_POST['nombre'];
    $id = $_POST['id'];

    switch($_POST['action']):

        case 'editar_proyecto':
            $Proyectos->id              = $_POST['id'];
            $Proyectos->nombre          = $_POST['nombre'];
            $Proyectos->referencia             = $_POST['referencia'];
            $Proyectos->fk_tercero         = $_POST['fk_tercero'];
            $Proyectos->estado          = $_POST['estado'];
            $Proyectos->etiquetas_tags  = $_POST['etiquetas_tags'];
            $Proyectos->monto           = $_POST['monto'];
            $Proyectos->fecha_inicio    = $_POST['fecha_inicio'];
            $Proyectos->fecha_fin       = $_POST['fecha_fin'];
            $Proyectos->ubicacion_mapa  = $_POST['ubicacion_mapa'];
            $Proyectos->latitud_longitud  = $_POST['latitud_longitud'];

            if ($Proyectos->id > 0) { 
                echo json_encode($Proyectos->editar_proyecto());
            } else {
                echo json_encode($Proyectos->crear_proyecto());
            }
        break;

        case 'eliminar_proyecto':
            $Proyectos->id = $_POST['id'];
            $Proyectos->borrado = 1;
            $Proyectos->borrado_fecha = date('Y-m-d H:i:s');
            $Proyectos->borrado_fk_usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';
            echo json_encode($Proyectos->borrar_proyecto());
        break;

     

    endswitch;
endif;
