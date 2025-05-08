<?php
 
        header("Access-Control-Allow-Origin: *"); // Permite cualquier origen (*), puedes especificar un dominio
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Métodos permitidos
        header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Encabezados permitidos
        header("Access-Control-Allow-Credentials: true"); // Permite credenciales si es necesario

        // Manejo de la solicitud OPTIONS (preflight request)
        if ($_SERVER['REQUEST_METHOD'] == "OPTIONS") {
            http_response_code(200);
            exit();
        }

        // Mejora Verifactu


    @include("../sistema/conf/conf.php");
 
    error_reporting(1);
    ini_set('display_errors', '1');

    require_once (ENLACE_SERVIDOR."mod_entidad/object/Entidad.object.php");
    require_once (ENLACE_SERVIDOR."mod_europa_facturacion/object/facturas.object.php"   );
    require_once("xml.object.php");     

  

    $entidad =  $_REQUEST['Entidad'];
    $id      =  $_REQUEST['id'];



    $Respuestas['hora']     =date("d-m-Y H:i:s");
    $Respuestas['respuestas'][] ="Iniciamos" ;
    $Respuestas['respuestas'][] = $entidad ;
    $Respuestas['respuestas'][] = $id ;
    $Respuestas['respuestas'][] ="Fin ";
  

    $lectura = $FacturaXML = new XML_Hacienda_Spain(   $entidad  );


    if ( $FacturaXML->Entidad_OBJ->verifactum_produccion == 0 ){
                $respuesta['error']         =   1;
                $respuesta["mensaje"]       =  "Empresa No Configurada Verifactu";
                echo json_encode($respuesta);
                exit(1);

    }  


    if ( empty($FacturaXML->Entidad_OBJ->electronica_certificado)) {
                $respuesta['error']         =   1;
                $respuesta["mensaje"]       =  "Certificado No Encontrado - Favor Actualizar el Certificado Antes de Continuar";
                echo json_encode($respuesta);
                exit(1);

    }






    $FacturaXML->fetch_encriptado($id);
 
  
  

    if (!$lectura){    array_push( $Respuestas['respuestas'] , $FacturaXML->mensajes);  echo json_encode($Respuestas); exit(1);  }
    

    $Respuestas['certificado']= $FacturaXML->certificado;
     
     

    if (   $FacturaXML->estado == 0 ) {
        $respuesta['error']         =   1;
        $respuesta["mensaje"]       =  "Factura No esta en Validado";

        
    } else if (   ($FacturaXML->estado == 1) and $FacturaXML->xml_hacienda_enviado == 0 or 1) {
            $requestXml = $FacturaXML->crear_xml();
           // $FacturaXML->debug_verifactum = true;
            $FacturaXML->enviar_verifactum($requestXml);

            $respuesta['xml']                   =   $FacturaXML->xml;  
            $respuesta['error']                 =   0;
            $respuesta["mensaje"]               =   "Envio Procesado";
            $respuesta['Huella_sha256']         =   $FacturaXML->Huella_sha256;
            $respuesta['mensaje_hacienda']      =   $FacturaXML->mensaje_hacienda;
            $respuesta['mensaje_estado']        =   $FacturaXML->mensaje_estado;
            $respuesta['mensaje_estado_error']  =   $FacturaXML->mensaje_estado_error_o_no;

            


        } else {
            $respuesta['error']         =1;
            $respuesta["mensaje"]       ="No Existe Pendiente ";

        }
    
      
        
        echo json_encode($respuesta);

    