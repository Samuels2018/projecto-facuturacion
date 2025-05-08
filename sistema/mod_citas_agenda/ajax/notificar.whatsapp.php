<?php
session_start();
$CRON = true;

require_once("../../conf/conf.php");
require_once  ENLACE_SERVIDOR.'mod_api_wassenger/object/wassenger.object.php';

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

 

//-------------------------------------------------------------------
//
//
// Este se encarga de enviar los whatsapp con notificaciones
// De seguridad 
//
//  
//---------------------------------------------------------------------


        $contactos[]="+50663114020";
        $contactos[]= "+".$_REQUEST['telefono'];

 
     

        $WhatsApp = new Wassenger($dbh);
        $WhatsApp->mensaje =  "¡Hola {$_REQUEST['cliente']}! 👋
\nQueremos recordarte que tienes una cita programada con nosotros:

\n📅 Fecha: [Fecha de la cita]
\n⏰ Hora: [Hora de la cita]
\n📍 Lugar: [Dirección del centro estético]";


        $WhatsApp->telefono = "+34".$_REQUEST['telefono'];
        $json  = $WhatsApp->mensaje2($null);
    
 