<?php
session_start();
$CRON = true;



require_once  '/home/facturac6/public_html/sistema/mod_api_wassenger/object/wassenger.object.php';

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$dbh = new PDO('mysql:host=localhost;dbname=facturac_sistema_GeNeRaL;charset=UTF8','facturac_Licenci', 'Np!z}MPksQQw', array(
        PDO::ATTR_PERSISTENT => true));
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


   $plataforma = new PDO('mysql:host=localhost;dbname=facturac_Licencias;charset=UTF8', 'facturac_Licenci', 'Np!z}MPksQQw', array(
        PDO::ATTR_PERSISTENT => true));

//-------------------------------------------------------------------
//
//
// Este se encarga de enviar los whatsapp con notificaciones
// De seguridad 
//
//  
//---------------------------------------------------------------------


// $contactos[]="+50663114020";
//  $contactos[]="+50671067422"; //SERGIO


$dbh2 = $plataforma;


$sql    =  " SELECT * FROM whatsapp_mensajes   where  enviado  =  0 ";
$db     = $dbh2->prepare($sql);
$db->execute();
$ips = array();

/*
    unset($contactos);
    $contactos = array();
    $contactos[] = "50663114020";
    */

   
 


while ($data = $db->fetch(PDO::FETCH_OBJ)) {
    if ($data->telefonos_destinatario != '') {
        $array = explode (',', $data->telefonos_destinatario);
        $contactos = $array; 

        $WhatsApp = new Wassenger($dbh);
        $WhatsApp->mensaje =  "🤖 " . $data->texto;

        $WhatsApp->mensaje  = preg_replace("/[\r\n|\n|\r]+/", PHP_EOL,  $WhatsApp->mensaje);
        $WhatsApp->mensaje  = str_replace('"', '',   $WhatsApp->mensaje);
        
        if($data->fk_cotizacion > 0){
            
            
             $_GET['id']     = $data->fk_cotizacion;
            $_GET['generar_temp']     = true;
          
         
           // hacer esto genera el pdf temporal y podemos ver su contenido
            require_once "/home/facturac6/public_html/sistema/include/pdflib/pdf_cotizacion_detalle.php";

          $adjunto = '/home/facturac6/public_html/sistema/_documentos/542/temp_document.pdf';
       
        //lo renombramos para que se envie con el nombre de la cotizacion
$nuevoNombre = '/home/facturac6/public_html/sistema/_documentos/542/'.$cotizacion->number.'.pdf';

if (!rename($adjunto, $nuevoNombre)) {
    echo "Hubo un error al intentar renombrar el archivo.";
} else {
    echo "El archivo ha sido renombrado exitosamente a nuevo_nombre.pdf";
}

$url_adjunto = 'https://facturacionpymes.tk/sistema/'.$cotizacion->number.'.pdf';

        }else{
       //    $url_adjunto = null;
        }
        
        
        
               if($data->fk_reserva > 0){
            
            
             $_GET['id']     = $data->fk_reserva;
            $_GET['generar_temp']     = true;
        
         
           // hacer esto genera el pdf temporal y podemos ver su contenido
            require_once "/home/facturac6/public_html/sistema/include/pdflib/pdf_reserva_cctravel.php";

          $adjunto = '/home/facturac6/public_html/sistema/_documentos/542/temp_document.pdf';
       
        //lo renombramos para que se envie con el nombre de la cotizacion
$nuevoNombre = '/home/facturac6/public_html/sistema/_documentos/542/'.$reserva->nro_confirmacion.'.pdf';

if (!rename($adjunto, $nuevoNombre)) {
    echo "Hubo un error al intentar renombrar el archivo.";
} else {
    echo "El archivo ha sido renombrado exitosamente a nuevo_nombre.pdf";
}

$url_adjunto = 'https://facturacionpymes.tk/sistema/'.$reserva->nro_confirmacion.'.pdf';

        }else{
          // $url_adjunto = null;
        }
    
    

        foreach ($contactos as $contacto) {
            $WhatsApp->telefono = $contacto;
            $json  = $WhatsApp->mensaje2($url_adjunto);
        }

        $query  = "UPDATE whatsapp_mensajes  SET enviado = 1, enviado_fecha = NOW()  WHERE rowid = " .$data->rowid;
        $db2    =  $dbh2->prepare($query);
        $result2 = $db2->execute();
    }
}
