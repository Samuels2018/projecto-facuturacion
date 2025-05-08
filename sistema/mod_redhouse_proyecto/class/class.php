<?php


session_start();



include("../../conf/conf.php");

require_once ENLACE_SERVIDOR . 'mod_redhouse_cotizaciones/object/redhouse.cotizaciones.object.php';
require_once ENLACE_SERVIDOR . 'mod_redhouse_proyecto/object/proyecto_object.php';
require_once ENLACE_SERVIDOR . 'mod_usuarios/object/usuarios.object.php';

//Vamos a obtener la data de la cotizacion que estamos generandole el proyecto
$Cotizacion = new redhouse_Cotizacion($dbh, $_SESSION['Entidad']);
//Vamos a llamar el objeto del proyecto para hacer la insersión
$Proyecto = new redhouse_proyecto($dbh, $_SESSION['Entidad']);


switch ($_POST['action'])
{
    case 'generar_proyecto':
        
        $Cotizacion->fetch($_REQUEST['fk_cotizacion']);
        $Proyecto->fk_cotizacion = $_REQUEST['fk_cotizacion'];
        $Proyecto->proyecto_fecha = $Cotizacion->cotizacion_fecha_proyecto;
        $Proyecto->proyecto_descripcion = $Cotizacion->cotizacion_proyecto;
        $Proyecto->proyecto_lugar = $Cotizacion->cotizacion_lugar_proyecto;
        $Proyecto->proyecto_contacto = $Cotizacion->cotizacion_contacto_proyecto;
        $Proyecto->proyecto_tipo_cambio = $Cotizacion->cotizacion_tipo_cambio;
        $Proyecto->borrado = 0;
        $Proyecto->borrado_fecha = '';
        $Proyecto->borrado_fk_usuario = '';
        $Proyecto->creado_fk_usuario = $_SESSION['usuario'];
        $resultado = $Proyecto->insertar(); //vamos a insertarlo

        echo json_encode($resultado);

    break;

    default:
        # code...
    break;
}
