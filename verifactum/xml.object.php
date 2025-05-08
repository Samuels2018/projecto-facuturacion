<?php



    


  
class XML_Hacienda_Spain extends Factura {

//------------------------------------------------------------------------------------------
//
//
//      Dios es BUENO 
//      Juan Carlos se encuentra entre Nosotros 
//        
//------------------------------------------------------------------------------------------


public $certificado;
public $clave;
 
public $datos_anteriores;
public $registro_id_huella;
public $debug_verifactum;

public $configuracion;

public $mensajes ; // MAnejo de Mensajes
public $Entidad_OBJ;  //Objeto Entidad

public function __construct ( $entidad){  


      if (empty($entidad)){
            $this->mensajes[]="Error Entidad No recibida";
            return false;
            exit(1);
      
        } else if (is_int($entidad)) {

            $db= $this->conexionBD($entidad);

      } else {
            $entidad = $this->empresa_fetch_desencriptado($entidad);
            $db= $this->conexionBD($entidad);
      }

        
        $this->db = $db;

        $this->Entidad_OBJ = new Entidad($db, $entidad);
        $this->Entidad_OBJ->fetch( );


        $this->debug_verifactum = false;
        parent::__construct($db , $this->entidad );  // Esto inicializa la clase SEGURIDAD

        $this->certificado                        = $this->Entidad_OBJ->electronica_certificado;
        $this->certificado_clave                  = $this->Entidad_OBJ->electronica_certificado_clave;
        $this->ENLACE_FIRMA         = ENLACE_FILES_XML ."certificados/{$entidad}/";
        $this->ENLACE_FILES_XML     = ENLACE_FILES_XML;

    return true;
    
    
} 


    // Se utiliza para poder conectar desde los CronJobs a las licencias y las BD operativas
    public function conexionBD( $entidad ){
        
        $dbh_plataforma = $this->conexion_db_plataforma();

        $db  = $dbh_plataforma->prepare("select * from sistema_empresa_licencias where rowid = (select fk_sistema_empresa_licencias from sistema_empresa where rowid = :licencia )");
        $db->bindValue(":licencia", $entidad ,PDO::PARAM_INT);
        $db->execute();
        $row = $db->fetch(PDO::FETCH_ASSOC);
      
        $dbh = new PDO('mysql:host='.$row['server'].';dbname='. sanitize_string($row['bd']) .';charset=UTF8', sanitize_string($row['user']) ,  sanitize_string($row['pass'])  , array(
            PDO::ATTR_PERSISTENT => true,
        ));
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

        return $dbh;
    

    }
  

    private function conexion_db_plataforma()
    {
        // Cambiamos esto para unificar Entidad y Empresa 
        // Por seguridad se usa y se destruye
        $dbh_plataforma = new PDO('mysql:host=' . $_ENV['DB_HOST_PLATAFORMA'] . ';dbname=' . $_ENV['DB_NAME_PLATAFORMA'] . ';charset=UTF8', $_ENV['DB_USER_PLATAFORMA'], $_ENV['DB_PASS_PLATAFORMA'], array(
            PDO::ATTR_PERSISTENT => true,
        ));

        return $dbh_plataforma;
    }



    public function empresa_fetch_desencriptado( $idEncriptado_MD5) {
       
        $sql = "select rowid  from sistema_empresa  where  md5(rowid)  = :fk_entidad  ";
        $plataforma = $this->conexion_db_plataforma();
        $stmt = $plataforma->prepare($sql);
        $stmt->bindParam(':fk_entidad', $idEncriptado_MD5 , PDO::PARAM_STR);
        $a = $stmt->execute();
        

        if ($a) {   
            $resultados = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->entidad = $resultados['rowid'];
           
        } else {

            $this->sql     =   $sql;
            $this->error   =   implode(", ", $stmt->errorInfo()) . " " . implode(" , ", $plataforma->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }
            return $this->entidad;
    }

   

 
/******************************************************************************

                        Ejemplo de Huella
                        $huella.="IDEmisorFactura=$DNI";
                        $huella.="&NumSerieFactura=".($factura-1);
                        $huella.="&FechaExpedicionFactura=$fecha_expedicion";
                        $huella.="&TipoFactura=F1";
                        $huella.="&CuotaTotal=12.35";
                        $huella.="&ImporteTotal=123.45";
                        $huella.="&Huella=";
                        $huella.="&FechaHoraHusoGenRegistro={$fecha}T{$hora}+01:00";
                        $huella_anterior= hash('sha256', $huella);
****************************************************************************/



public function crear_huella() {

     $hora =date("H:i:s"); 
     $fecha = date("Y-m-d");
     $this->datos_anteriores            = $this->recuperar_ultima_huella();   
     $this->xml_IDVersion               = "1.0";
     $this->FechaExpedicionFactura      = $this->fecha;
     $this->TipoFactura                 = $this->tipo;
     $this->CuotaTotal                  = $this->impuesto_iva + $this->impuesto_iva_equivalencia;
     $this->ImporteTotal                = $this->total ;
     $this->FechaHoraHusoGenRegistro    = "{$fecha}T{$hora}+01:00";
     $this->huella_anterior             =  $this->datos_anteriores['xml_huella_sha256'];


      $sql = "INSERT INTO fi_europa_facturas_huellas (
      fk_documento,
      entidad,
      IDEmisorFactura,
      NumSerieFactura,
      FechaExpedicionFactura,
      TipoFactura,
      CuotaTotal,
      ImporteTotal,
      Huella,
      FechaHoraHusoGenRegistro,
      huella_anterior
  ) VALUES (
      :fk_documento,
      :entidad,
      :IDEmisorFactura,
      :NumSerieFactura,
      :FechaExpedicionFactura,
      :TipoFactura,
      :CuotaTotal,
      :ImporteTotal,
      :Huella,
      :FechaHoraHusoGenRegistro,
      :huella_anterior
  )";



$huella = "";
$huella.="IDEmisorFactura=".    $this->xml_IDEmisorFactura;
$huella.="&NumSerieFactura=".   $this->referencia       ;
$huella.="&FechaExpedicionFactura=".date("d-m-Y", strtotime($this->FechaExpedicionFactura));
$huella.="&TipoFactura=".       $this->TipoFactura;
$huella.="&CuotaTotal=".        $this->CuotaTotal;
$huella.="&ImporteTotal=".      $this->ImporteTotal;
$huella.="&Huella=".$this->huella_anterior;
$huella.="&FechaHoraHusoGenRegistro=".$this->FechaHoraHusoGenRegistro;
$this->Huella        = $huella;
$this->Huella_sha256 = strtoupper(hash('sha256', $huella));




                // Preparar la sentencia
                $stmt = $this->db->prepare($sql);
                // Vincular valores usando bindValue
                $stmt->bindValue(':fk_documento'            , $this->id                     , PDO::PARAM_INT);
                $stmt->bindValue(':entidad'                 , $this->entidad                , PDO::PARAM_INT);
                $stmt->bindValue(':IDEmisorFactura'         , $this->xml_IDEmisorFactura    , PDO::PARAM_STR);
                $stmt->bindValue(':NumSerieFactura'         , $this->referencia             , PDO::PARAM_STR);
                $stmt->bindValue(':FechaExpedicionFactura'  , $this->FechaExpedicionFactura , PDO::PARAM_STR);
                $stmt->bindValue(':TipoFactura'             , $this->TipoFactura            , PDO::PARAM_STR);
                $stmt->bindValue(':Huella'                  , $this->Huella                 , PDO::PARAM_STR);
                $stmt->bindValue(':CuotaTotal'              , $this->CuotaTotal             , PDO::PARAM_STR);
                $stmt->bindValue(':ImporteTotal'            , $this->ImporteTotal           , PDO::PARAM_STR);
                $stmt->bindValue(':FechaHoraHusoGenRegistro', $this->FechaHoraHusoGenRegistro   , PDO::PARAM_STR);
                $stmt->bindValue(':huella_anterior'         , $this->huella_anterior            , PDO::PARAM_STR);

                // Ejecutar la consulta
                $a = $stmt->execute();





                if (!$a){
                $this->sql     =   $sql;
                $this->error   =   implode(", ", $stmt->errorInfo())." - ".implode(", ", $this->db->errorInfo());
                $this->proceso = __FUNCTION__ ." Del Objeto ".__CLASS__;
                $this->Error_SQL();
                }  

                $this->registro_id_huella = $this->db->lastInsertId();
                $this->registro_id_huella ;

                $this->factura_actualizar_xml();




}
  



public function respuesta_hacienda_capa_error_envio_cliente($respuesta = NULL ){

    $dom = new DOMDocument();
    $dom->loadXML($respuesta);
    
    // Crear un nuevo XPath para buscar los nodos
    $xpath = new DOMXPath($dom);
    
    // Registrar el namespace para que XPath lo reconozca
    $xpath->registerNamespace('env', 'http://schemas.xmlsoap.org/soap/envelope/');
    
    // Obtener el nodo <Fault>
    $faultNode = $xpath->query('//env:Fault');
    
    // Verificar si existe el nodo
    if ($faultNode->length > 0) {
        $fault = $faultNode->item(0);
    
        // Extraer <faultcode>
        $faultCodeNode  = $xpath->query('./faultcode', $fault);
        $faultCode      = $faultCodeNode->length > 0 ? $faultCodeNode->item(0)->nodeValue : 'No disponible';
    
        // Extraer <faultstring>
        $faultStringNode    = $xpath->query('./faultstring', $fault);
        $faultString        = $faultStringNode->length > 0 ? $faultStringNode->item(0)->nodeValue : 'No disponible';
    

        // Extraer <detail>/<callstack>
        $detailNode = $xpath->query('./detail/callstack', $fault);
        $detail = $detailNode->length > 0 ? $detailNode->item(0)->nodeValue : 'No disponible';
    
        /*// Mostrar los resultados
        echo "Fault Code:   $faultCode\n";
        echo "Fault String: $faultString\n";
        echo "Detail:       $detail\n";
*/

    } else {
       return false;
    }
 

 

 
           $sql= "update 
                fi_europa_facturas_huellas 
                set 
                respuesta                                   =   :respuesta                      ,
                respuesta_estado_registro                   =   :respuesta_estado_registro      ,
                respuesta_descripcion_registro_descripcion  =   :respuesta_descripcion_registro_descripcion ,
                respuesta_tipo_operacion                    =   :respuesta_tipo_operacion                   ,
                respuesta_codigo                            =   :respuesta_codigo                           ,
                respuesta_estado_envio                      =   :respuesta_estado_envio
                where 
                    rowid       =   :rowid 
                and fk_documento  =   :fk_documento
                and entidad     =   :entidad ";
/*
echo "id ". $this->id  ;
echo "entidad ". $this->entidad  ;
echo "registro_id_huella ". $this->registro_id_huella  ;
*/
    $this->mensaje_hacienda   = $faultString;

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':fk_documento'            , $this->id                     , PDO::PARAM_INT);
    $stmt->bindValue(':entidad'                 , $this->entidad                , PDO::PARAM_INT);
    $stmt->bindValue(':rowid'                   , $this->registro_id_huella     , PDO::PARAM_INT);
    $stmt->bindValue(':respuesta'               , $respuesta                    , PDO::PARAM_STR);
    $stmt->bindValue(':respuesta_estado_registro', $faultString, PDO::PARAM_STR);
    $stmt->bindValue(':respuesta_codigo'         ,  $faultCode , PDO::PARAM_STR);
    $stmt->bindValue(':respuesta_descripcion_registro_descripcion', substr($faultString ,0,499) , PDO::PARAM_STR);
    $stmt->bindValue(':respuesta_estado_envio'   , substr($faultString ,0,249) , PDO::PARAM_STR);
    $stmt->bindValue(':respuesta_tipo_operacion' ,  "" , PDO::PARAM_STR);
 

    $a = $stmt->execute();

    if (!$a){
        $this->sql     =   $sql;
        $this->error   =    implode(", ", $stmt->errorInfo())." - ".implode(", ", $this->db->errorInfo());
        $this->proceso = __FUNCTION__ ." Del Objeto ".__CLASS__;
        $this->Error_SQL();


    }  
  


     $this->factura_actualizar_verifactum_enviado(  "incorrecto", "incorrecto"   ); // ESto es  dentro de facturas.object.php 

  return true;



} 
   
public function respuesta_hacienda_capa_respuesta_hacienda($respuesta = NULL ){



    $dom = new DOMDocument();
    $dom->loadXML($respuesta);
    
    // Crear un nuevo XPath para buscar el nodo
    $xpath = new DOMXPath($dom);
    
    // Registrar los espacios de nombres para las consultas
    $xpath->registerNamespace('env', 'http://schemas.xmlsoap.org/soap/envelope/');
    $xpath->registerNamespace('tikR', 'https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/tike/cont/ws/RespuestaSuministro.xsd');
    $xpath->registerNamespace('tik', 'https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/tike/cont/ws/SuministroInformacion.xsd');

    // Buscar el nodo EstadoRegistro
    $estadoRegistro             = $xpath->query('//tikR:EstadoRegistro');
    $descripcionerrorregistro   = $xpath->query('//tikR:DescripcionErrorRegistro');
    $CodigoErrorRegistro   = $xpath->query('//tikR:CodigoErrorRegistro');
    $EstadoEnvio = $xpath->query('//tikR:EstadoEnvio');
    $TipoOperacion              = $xpath->query('//tik:TipoOperacion');

    $this->mensaje_hacienda   = $descripcionerrorregistro[0]->nodeValue;

 /*
echo "<hr>";
    var_dump($estadoRegistro[0]->nodeValue);
    var_dump($descripcionerrorregistro[0]->nodeValue);
    var_dump($CodigoErrorRegistro[0]->nodeValue);
    var_dump($TipoOperacion);
    var_dump($EstadoEnvio);
echo "<hr>";
*/
 


//$xml = new SimpleXMLElement($respuesta);
 

/*
 echo "<span style='style:color:blue'><hr>";

 $dom = new DOMDocument();

 $dom->loadXML($respuesta);
    var_dump($dom);

echo "<hr></span>";
*/

 

 
         $sql= "update 
                fi_europa_facturas_huellas 
                set 
                respuesta                                   =   :respuesta                      ,
                respuesta_estado_registro                   =   :respuesta_estado_registro      ,
                respuesta_descripcion_registro_descripcion  =   :respuesta_descripcion_registro_descripcion ,
                respuesta_tipo_operacion                    =   :respuesta_tipo_operacion                   ,
                respuesta_codigo                            =   :respuesta_codigo                           ,
                respuesta_estado_envio                      =   :respuesta_estado_envio
                where 
                    rowid       =   :rowid 
                and fk_documento  =   :fk_documento
                and entidad     =   :entidad ";


    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':fk_documento'            , $this->id                     , PDO::PARAM_INT);
    $stmt->bindValue(':entidad'                 , $this->entidad                , PDO::PARAM_INT);
    $stmt->bindValue(':rowid'                   , $this->registro_id_huella     , PDO::PARAM_INT);
    $stmt->bindValue(':respuesta'               , $respuesta                    , PDO::PARAM_STR);
    $stmt->bindValue(':respuesta_estado_registro',  $estadoRegistro[0]->nodeValue , PDO::PARAM_STR);
    $stmt->bindValue(':respuesta_codigo'         ,  $CodigoErrorRegistro[0]->nodeValue , PDO::PARAM_STR);
    $stmt->bindValue(':respuesta_descripcion_registro_descripcion', substr($descripcionerrorregistro[0]->nodeValue ,0,499) , PDO::PARAM_STR);
    $stmt->bindValue(':respuesta_estado_envio'   , substr($EstadoEnvio[0]->nodeValue ,0,249) , PDO::PARAM_STR);
    $stmt->bindValue(':respuesta_tipo_operacion' ,  $TipoOperacion[0]->nodeValue , PDO::PARAM_STR);


    

    

    $a = $stmt->execute();

    if (!$a){
        $this->sql     =   $sql;
        $this->error   =    implode(", ", $stmt->errorInfo())." - ".implode(", ", $this->db->errorInfo());
        $this->proceso = __FUNCTION__ ." Del Objeto ".__CLASS__;
        $this->Error_SQL();


    }  
  


    $this->factura_actualizar_verifactum_enviado(   $estadoRegistro[0]->nodeValue , substr($EstadoEnvio[0]->nodeValue ,0,249)    ); // ESto es  dentro de facturas.object.php 


}




public function registrar_huella_comunicacion( $status_code , $status_respuesta ){
 
   //   $status_respuesta =   htmlspecialchars(trim($status_respuesta), ENT_QUOTES, 'UTF-8');
 

    $sql = "INSERT INTO fi_europa_facturas_huellas_comunicacion 
                    (fk_factura, entidad, status_code, status_respuesta, status_fecha) 
            VALUES 
                    (:fk_factura, :entidad, :status_code, :status_respuesta, NOW() )";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':fk_factura', $this->id , PDO::PARAM_INT);
        $stmt->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $stmt->bindValue(':status_code', $status_code, PDO::PARAM_STR);
        $stmt->bindValue(':status_respuesta', substr($status_respuesta, 0 , 599 ), PDO::PARAM_STR);
          
    $a = $stmt->execute();

    if (!$a){
        $this->sql     =   $sql;
        $this->error   =   implode(", ", $stmt->errorInfo())." - ".implode(", ", $this->db->errorInfo());
        $this->proceso = __FUNCTION__ ." Del Objeto ".__CLASS__;
        $this->Error_SQL();
    }  


}


public function crear_xml(){
    
    $xml_envelope='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
xmlns:sum="https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/tike/cont/ws/SuministroLR.xsd"
xmlns:sum1="https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/tike/cont/ws/SuministroInformacion.xsd" xmlns:xd="http://www.w3.org/2000/09/xmldsig#">';


  $this->crear_huella();
  $DNI      = $this->xml_IDEmisorFactura;
  $factura  = $this->referencia;
  $EMISOR_NombreRazon = $this->entidad_razonsocial;

  $fecha_expedicion = date("d-m-Y",strtotime($this->fecha));

  $xml_header  = "";

  $impuestos     = $this->recalculo_xml();
  $xml_impuestos = "";
  $xml_tipo_recargo_equivalencias  = "";

/*
<sum1:DetalleDesglose>
<sum1:ClaveRegimen>01</sum1:ClaveRegimen>
<sum1:CalificacionOperacion>S1</sum1:CalificacionOperacion>
<sum1:TipoImpositivo>4</sum1:TipoImpositivo>
<sum1:BaseImponibleOimporteNoSujeto>10</sum1:BaseImponibleOimporteNoSujeto>
<sum1:CuotaRepercutida>0.4</sum1:CuotaRepercutida>
</sum1:DetalleDesglose>
*/


  foreach ($impuestos['IVA'] as $impuesto_porcentaje => $valor) {
 
  //  $xml_tipo_recargo_equivalencias "<sum1:TipoRecargoEquivalencia>".."</sum1:TipoRecargoEquivalencia>"

$xml_impuestos.="<sum1:DetalleDesglose>
<sum1:ClaveRegimen>01</sum1:ClaveRegimen> 
<sum1:CalificacionOperacion>S1</sum1:CalificacionOperacion>
<sum1:TipoImpositivo>{$impuesto_porcentaje}</sum1:TipoImpositivo>
<sum1:BaseImponibleOimporteNoSujeto>".round($valor['subtotal'],2)."</sum1:BaseImponibleOimporteNoSujeto>"
.$xml_tipo_recargo_equivalencias
."<sum1:CuotaRepercutida>".round($valor['impuesto'],2)."</sum1:CuotaRepercutida>
</sum1:DetalleDesglose>";

  }


  
  $xml_header.="<soapenv:Header/>
<soapenv:Body>
<sum:RegFactuSistemaFacturacion>
<sum:Cabecera>
<sum1:ObligadoEmision>
<sum1:NombreRazon>{$EMISOR_NombreRazon}</sum1:NombreRazon>
<sum1:NIF>{$DNI}</sum1:NIF>
</sum1:ObligadoEmision>
</sum:Cabecera>";

 /*
$subsanacion    ="<sum1:Subsanacion>S</sum1:Subsanacion>";
$rechazoprevio  ="<sum1:RechazoPrevio>X</sum1:RechazoPrevio>";
 */

 


$xml_cuerpo="
<sum:RegistroFactura>
<sum1:RegistroAlta>
<sum1:IDVersion>{$this->xml_IDVersion}</sum1:IDVersion>
<sum1:IDFactura>
<sum1:IDEmisorFactura>{$DNI}</sum1:IDEmisorFactura>
<sum1:NumSerieFactura>{$factura}</sum1:NumSerieFactura>
<sum1:FechaExpedicionFactura>{$fecha_expedicion}</sum1:FechaExpedicionFactura>
</sum1:IDFactura>
<sum1:NombreRazonEmisor>{$EMISOR_NombreRazon}</sum1:NombreRazonEmisor>
{$subsanacion}{$rechazoprevio}
<sum1:TipoFactura>{$this->TipoFactura}</sum1:TipoFactura>
<sum1:DescripcionOperacion>Descripc</sum1:DescripcionOperacion>
<sum1:Destinatarios>
<sum1:IDDestinatario>
<sum1:NombreRazon>{$this->fk_tercero_txt}</sum1:NombreRazon>
<sum1:NIF>{$this->fk_tercero_identificacion}</sum1:NIF>
</sum1:IDDestinatario>
</sum1:Destinatarios>
<sum1:Desglose>
{$xml_impuestos}
</sum1:Desglose>
<sum1:CuotaTotal>".($this->impuesto_iva_equivalencia+$this->impuesto_iva)."</sum1:CuotaTotal>
<sum1:ImporteTotal>{$this->total}</sum1:ImporteTotal>";


$xml_encadenamiento="<sum1:Encadenamiento>
<sum1:RegistroAnterior>
<sum1:IDEmisorFactura>".trim($this->datos_anteriores['xml_IDEmisorFactura'])."</sum1:IDEmisorFactura>
<sum1:NumSerieFactura>".trim($this->datos_anteriores['referencia'])."</sum1:NumSerieFactura>
<sum1:FechaExpedicionFactura>".trim(date("d-m-Y", strtotime($this->datos_anteriores['fecha'])))."</sum1:FechaExpedicionFactura>
<sum1:Huella>{$this->huella_anterior}</sum1:Huella>
</sum1:RegistroAnterior>
</sum1:Encadenamiento>";

 
$xml_si.="<sum1:SistemaInformatico>
<sum1:NombreRazon>Avantec.DS SL</sum1:NombreRazon>
<sum1:NIF>B70811112</sum1:NIF>
<sum1:NombreSistemaInformatico>NombreSistemaInformatico</sum1:NombreSistemaInformatico>
<sum1:IdSistemaInformatico>77</sum1:IdSistemaInformatico>
<sum1:Version>1.0.03</sum1:Version>
<sum1:NumeroInstalacion>383</sum1:NumeroInstalacion>
<sum1:TipoUsoPosibleSoloVerifactu>N</sum1:TipoUsoPosibleSoloVerifactu>
<sum1:TipoUsoPosibleMultiOT>S</sum1:TipoUsoPosibleMultiOT>
<sum1:IndicadorMultiplesOT>S</sum1:IndicadorMultiplesOT>
</sum1:SistemaInformatico>
<sum1:FechaHoraHusoGenRegistro>{$this->FechaHoraHusoGenRegistro}</sum1:FechaHoraHusoGenRegistro>
<sum1:TipoHuella>01</sum1:TipoHuella>
<sum1:Huella>{$this->Huella_sha256}</sum1:Huella>
</sum1:RegistroAlta>
</sum:RegistroFactura>
</sum:RegFactuSistemaFacturacion>
</soapenv:Body>
</soapenv:Envelope>";

    $xml=  trim($xml_envelope);
    $xml.= trim($xml_header);
    $xml.= trim($xml_cuerpo);
    $xml.= trim($xml_encadenamiento);
    $xml.= trim($xml_si);

    

  //  $xml.= trim($detalle);
  //  $xml.= trim($resumen);
  //  $xml.= trim($footer);
    
  $this->xml= $xml;
    $this->salvar();
    return $this->xml= $xml;

}

/*****************************************************************************************
 * 
 * 
 *      El Orden es el siguiente
 *      XML
 *      /home/factuguay-dev/htdocs/dev.factuguay.es/files_xml/
 *      /home/factuguay-dev/htdocs/dev.factuguay.es/files_xml/#Entidad/F1
 *      /home/factuguay-dev/htdocs/dev.factuguay.es/files_xml/{Entidad}/{tipo}/{referencia}
 * 
 ****************************************************************************************/


function salvar(){
 
 
   
    $archivo = $this->referencia.".xml";
       
    $BASE    = $this->ENLACE_FILES_XML.$this->entidad;  //mejora para multiples Bases de datos !
    if(!is_dir($BASE )) {         mkdir($BASE , 0777); }
    
    $BASE    = $this->ENLACE_FILES_XML.$this->entidad."/".$this->tipo;  //mejora para multiples Bases de datos !
    if(!is_dir($BASE )) {         mkdir($BASE , 0777); }
    
    $year = date("Y");
    $BASE    = $this->ENLACE_FILES_XML.$this->entidad."/".$this->tipo."/".$year;  //mejora para multiples Bases de datos !
    if(!is_dir($BASE )) {         mkdir($BASE , 0777); }
    
    
    $this->archivo = $BASE."/".$archivo;
  
    $fp = fopen($BASE."/".$archivo, "w");
    $this->base=$BASE;
 
    if ( !$fp ) {
        throw new Exception('File open failed.' );
      }  
    fputs($fp, $this->xml);
    fclose($fp);
    
     
}





 

public function decimalDinero($x) { return number_format($x, 5 ,  '.' ,''); } 

 

 
 
  
  
  
  
   //-------------------------------------------------------------------------------
   //
   //   Funcion para limpiar las cadenas  para no fallar ne la firma
   //
   //-----------------------------------------------------------------------------------



   public function limpieza( $string ){
    $patrones = array();
    $patrones[0] = '/[^A-Za-z0-9@. ]/';   
    $remplazo = array();
    $remplazo[0] = '';

    
    
        $clean = trim(preg_replace($patrones, $remplazo , strip_tags($string)));
    
        return $clean;

   }

 
   
  
  
  //---------------------------------------------------------------------------
  //
  //     Function para Revisar el Estado de la factura
  //
  //----------------------------------------------------------------------------
                            
        
 public function enviar_verifactum( $requestXml  ){
                                       


    require_once 'comunicacion/autoload.php';

    $this->ENLACE_FIRMA.$this->certificado ; // certificado

    $url = "https://prewww1.aeat.es/wlpl/TIKE-CONT/ws/SistemaFacturacion/VerifactuSOAP";


    $client = new \GuzzleHttp\Client([
        'base_uri' => $url,
        'timeout'  => 40.0,
        'verify'   => false, // Opcional: Deshabilita la verificación del peer
        'cert'     => [$this->ENLACE_FIRMA . $this->certificado, $this->certificado_clave],
    ]);


    try {
        $response = $client->post('', [
            'headers' => [
                'Content-Type' => 'text/xml; charset=utf-8',
                'SOAPAction' => '', // SOAPAction puede ser requerido
            ],
            'body' => $requestXml,
        ]);
    
        echo ($this->debug_verifactum)?   "Código HTTP: " . $response->getStatusCode() . "\n" :""; 
        $this->registrar_huella_comunicacion($response->getStatusCode() , $response->getBody() );
        echo ($this->debug_verifactum)?   "Respuesta SOAP:\n" . $response->getBody() : "";
        
        
        // Primera capa fault code :: env:cliente , si no va a la capa de Respuesta hacienda 
        if (! $this->respuesta_hacienda_capa_error_envio_cliente($response->getBody() )){
                    $this->respuesta_hacienda_capa_respuesta_hacienda($response->getBody() );
        }


    } catch (\GuzzleHttp\Exception\RequestException $e) {
        echo "Error en la solicitud: " . $e->getMessage();


    if ($e->hasResponse()) {
       
        $this->registrar_huella_comunicacion( $e->getResponse()->getStatusCode() , $e->getMessage()  );
    } else {
        
        echo ($this->debug_verifactum)? "No se recibió respuesta del servidor.\n" :"" ;

        $this->registrar_huella_comunicacion( "404-Respuesta Quemada en enviar_verifactum" , $e->getMessage() );
    }



    }
    

    
        } // fin de la funcion 
  
  
  
  
  

}