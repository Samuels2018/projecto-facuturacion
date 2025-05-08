<?php


session_start();



include("../../conf/conf.php");

require_once ENLACE_SERVIDOR . 'mod_redhouse_cotizaciones/object/redhouse.cotizaciones.object.php';
require_once ENLACE_SERVIDOR . 'mod_redhouse_ordenes_compra/object/order_compra_object.php';

include_once ENLACE_SERVIDOR . "mod_redhouse_crm/object/actividades.object.php";

//Esto para enviar el correo
include_once(ENLACE_SERVIDOR . "mail_sys/mail/PHPMailer/src/EnviarCorreoSmtp.php");

require_once ENLACE_SERVIDOR . 'mod_usuarios/object/usuarios.object.php';



$Orden = new Orden($dbh, $_SESSION['Entidad']);
$Actividad = new Actividades($dbh);


//Para traernos el correo
$usuario = new usuario($dbh);
$usuario->buscar_data_usuario($_SESSION['usuario']);




switch ($_POST['action']) {


    

    case 'guardar_orden':

            parse_str($_POST['data'], $_POST);
        

            // Vamos a obtener los datos de la cabecera
            $Orden->rowid = $_POST['fiche'];
            $Orden->fk_proyecto = $_POST['fk_proyecto'];
            $Orden->fk_proveedor = $_POST['fk_proveedor'];
            $Orden->fk_moneda = $_POST['fk_moneda'];
            $Orden->fk_forma_pago = $_POST['fk_forma_pago'];
            $Orden->fecha_creacion = $_POST['fecha_creacion'];
            $Orden->fecha_vigencia = $_POST['fecha_vigencia'];
            $Orden->orden_notas = $_POST['order_notas'];
            $Orden->orden_estado = $_POST['order_estado'];

            // Llamar a la función de crear/actualizar la orden y devolver el resultado
            echo $Orden->crear_orden();
        
    break;

    case 'actualizar_informacion_orden':

        parse_str($_POST['data'], $_POST);
        

        // Vamos a obtener los datos de la cabecera
        $Orden->rowid = $_POST['fiche'];
        $Orden->fk_proyecto = $_POST['fk_proyecto'];
        $Orden->fk_proveedor = $_POST['fk_proveedor'];
        $Orden->fk_moneda = $_POST['fk_moneda'];
        $Orden->fk_forma_pago = $_POST['fk_forma_pago'];
        //$Orden->fecha_creacion = $_POST['fecha_creacion'];
        //$Orden->fecha_vigencia = $_POST['fecha_vigencia'];
        $Orden->orden_notas = $_POST['order_notas'];
        $Orden->orden_estado = $_POST['order_estado'];

        // Llamar a la función para actualizar la cabecera de la orden Sin cambiar consecutivo ni nada. 
        echo $Orden->actualizar_informacion_orden();

    break;
    

    case 'guardar_servicio':

        $Orden->fk_orden                         = $_POST['fk_orden'];
        $Orden->fk_producto                    = $_POST['servicio_fk_producto'];
        $Orden->servicio_comentario            = $_POST['servicio_comentario'];
        $Orden->servicio_cantidad              = $_POST['servicio_cantidad'];
        $Orden->servicio_precio_unitario       = $_POST['servicio_precio_unitario'];
        $Orden->servicio_precio_tipo_impuesto  = $_POST['servicio_precio_tipo_impuesto'];
        $Orden->creado_fk_usuario              = $_SESSION['usuario'];
        $Orden->cantidad_dias                  = $_POST['servicio_cantidad_dias'];
        $Orden->servicio_tipo_duracion = $_POST['servicio_tipo_duracion'];
        $guardar = $Orden->servicios_insertar();
        echo json_encode($guardar);
        break;



    case 'actualizar_servicio':
        $Orden->id                             = $_POST['cotizacion_id'];
        $Orden->fk_producto                    = $_POST['servicio_fk_producto'];
        $Orden->servicio_comentario            = $_POST['servicio_comentario'];
        $Orden->servicio_cantidad              = $_POST['servicio_cantidad'];
        $Orden->servicio_precio_unitario       = $_POST['servicio_precio_unitario'];
        $Orden->servicio_precio_tipo_impuesto  = $_POST['servicio_precio_tipo_impuesto'];
        $Orden->creado_fk_usuario              = $_SESSION['usuario'];

        $Orden->cantidad_dias                  = $_POST['servicio_cantidad_dias'];

        $Orden->servicio_tipo_duracion = $_POST['servicio_tipo_duracion'];

        $guardar = $Orden->servicios_actualizar($_POST['rowid']);
        echo json_encode($guardar);
        break;

    case 'remover_servicio':
        $Orden->fk_orden  = $_POST['fk_orden'];
        $resultado = $Orden->servicios_remover($_POST['rowid']);
        echo json_encode($resultado);
        break;




  

    default:
        # code...
        break;
}
