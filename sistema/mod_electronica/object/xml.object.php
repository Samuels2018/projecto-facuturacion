<?php



require( '/home/facturac6/public_html/sistema/include/firmador/hacienda/firmador.php' );
   
use Hacienda\Firmador;


  
class XML_Hacienda{

//------------------------------------------------------------------------------------------
//
//
///     home/facturac6/public_html/sistema/mod_electronica43/object/xml.object.php
//      Mejoras para 4.3    
//      Dios es BUENO 
//        
//------------------------------------------------------------------------------------------


public $certificado;
public $clave;
 
 
 
public $documento_clave;          // numero lago
public $documento_consecutivo;    // numero corto

public function __construct ($db, $Empresa){  


      $this->db = $db;   
      $dbi = $db->prepare("select * from fi_configuracion_empresa where rowid = :rowid ");
      $dbi->bindValue(":rowid", $Empresa  ,PDO::PARAM_INT);
      $dbi->execute();
      
      $d=$dbi->fetch(PDO::FETCH_OBJ);
      $this->certificado                        = $d->electronica_certificado;
      $this->certificado_clave                  = $d->electronica_certificado_clave;
      $this->certificado_encriptado             = $d->electronica_certificado_encriptado;
      $this->empresaIDCarpeta                   = $Empresa;
      


      // Necesarios para el XML
      $this->electronica_nombre                 = $d->electronica_nombre;  
      $this->electronica_identificacion_numero  = $d->electronica_identificacion_numero;
      $this->electronica_identificacion_tipo    = $d->electronica_identificacion_tipo;
      $this->electronica_nombre_comercial       = $d->electronica_nombre_comercial ;
      $this->electronica_provincia              = $d->electronica_provincia ;
      $this->electronica_canton                 = $d->electronica_canton ;
      $this->electronica_distrito               = $d->electronica_distrito ;
      $this->electronica_barrio                 = $d->electronica_barrio ;
      $this->electronica_otras_senas            = $d->electronica_otras_senas ;
      $this->electronica_telefono               = $d->electronica_telefono ;
      $this->electronica_fax                    = $d->electronica_fax ;
      $this->electronico_correo                 = $d->electronico_correo ;
      $this->direccion_PDF                      = $d->nombre_direccion;
      $this->fk_sucursal                        = $d->fk_sucursal;
      $this->texto_hacienda                     = $d->texto_hacienda;
      

      ## Por defecto Asumimos que es una factura, ya que esto esta trabajando! 
      ##
      ##
      $this->consecutivo_tipo_documento         = "01";
    
         
   
   $this->carpeta_documentos   = (!empty($d->carpeta_documentos)) ? $d->carpeta_documentos :  '/home/facturac6/facturacion_electronica_documentos/';

   $this->carpeta_documentos   =   '/home/facturac6/facturacion_electronica_documentos/';  // luego debo volver aqui!

   


    $this->aceptacion_firma                        = $d->aceptacion_firma;
    $this->ventas_firma                            = $d->ventas_firma;
 
    return true;
    
    
} 


 
public function receptor($factura){


         if ( $this->consecutivo_tipo_documento=="01"){ $tabla="fi_facturas" ; } else { $tabla='fi_notas_credito'; }

    
       $sql="
      select t.* 
      from $tabla  f
      inner join fi_terceros t on t.rowid = f.fk_tercero 
      where f.rowid = $factura       ";
      
      $db = $this->db->prepare($sql);
      $db->execute();
      $datos=$db->fetch(PDO::FETCH_OBJ);
     

   //  Debemos eliminar esto!!
     $this->receptor_nombre                = ($datos->tipo=="fisica" or $datos->tipo=="dimex" ) ? $datos->nombre." ".$datos->apellidos : $datos->nombre;

    $patrones = array();
  $patrones[0] = '/[^0-9]/';  
  $remplazo = array();
  $remplazo[0] = '';

    $this->receptor_cedula                =  preg_replace($patrones, $remplazo, $datos->cedula);    
    //$this->receptor_cedula                =  $datos->cedula; 
     
     
    $this->receptor_comercial             =  $datos->electronica_nombre_comercial;
    $this->receptor_telefono              =  ereg_replace("[^0-9]", "", $datos->telefono);  
    $this->receptor_correo                =  ($datos->email);  
    $this->receptor_extranjero            =   $datos->extranjero;
 
 
   

       if ( $datos->tipo=="fisica" ) { $this->receptor_tipo =  "01"; }
            else if ( $datos->tipo=="juridica" ) { $this->receptor_tipo =  "02"; }
                else if ( $datos->tipo=="dimex" ) { $this->receptor_tipo =  "03"; }
                  else if ( $datos->tipo=="nite" ) { $this->receptor_tipo =  "04"; }

                        else {  $this->receptor_tipo =  "01"; }
     
     
     $this->receptor_nombre           = $this->limpieza($this->receptor_nombre);
     $this->receptor_comercial        = $this->limpieza($this->receptor_comercial);
     $this->receptor_correo           = $this->limpieza($this->receptor_correo);

      return true;
}   



public function venta($factura){
    
      $this->condicion_venta = $factura->condicion_venta;
      $this->plazo_credito   = $factura->plazo_credito  ;
      $this->medio_pago ="02";
    
}




//--------------------------------------------------------------------
//
//  Mejora 4.3
//  Para tema de Obtener clave directa
//  
//---------------------------------------------------------------------
public function getClaveSmart($factura){

 if ( $this->consecutivo_tipo_documento=="01" 
        or $this->consecutivo_tipo_documento=="04" 
            or $this->consecutivo_tipo_documento=="09" )
          { $tabla="fi_facturas" ; } 
              else { $tabla='fi_notas_credito'; }

     $sql= "
   
      SELECT   
      
        f.consecutivo
      , f.clave 
      , t.tipo           
      , t.cedula     
      , con.electronica_identificacion_numero    
      , con.electronica_identificacion_tipo    

      
      FROM  $tabla   f   
      left join  fi_terceros   t  on t.rowid = f.fk_tercero
      left join  fi_configuracion_empresa con on  con.rowid = f.entidad

          
      where f.rowid    = :factura  


      ";
    
      $dbi = $this->db->prepare($sql);
      $dbi->bindValue(":factura",                     $factura     ,PDO::PARAM_INT);
      $dbi->execute();

      $d=$dbi->fetch(PDO::FETCH_OBJ);    
 


      //----------------------------------------------------
      //
      //   RECEPTOR DATOS
      // 
      //---------------------------------------------------
       if ( $d->tipo=="fisica" ) { $this->receptor_tipo =  "01"; }
            else if ( $d->tipo=="juridica" ) { $this->receptor_tipo =  "02"; }
                else if ( $d->tipo=="dimex" ) { $this->receptor_tipo =  "03"; }
                  else if ( $d->tipo=="nite" ) { $this->receptor_tipo =  "04"; }
                        else {  $this->receptor_tipo =  ""; }
     

       $this->receptor_identificacion   =  mb_substr("000000000000".$d->cedula , -12);  






      //----------------------------------------------------
      //
      //   EMISOR  DATOS
      // 
      //---------------------------------------------------
    if ($d->electronica_identificacion_tipo==1){ $payload_emisor_tipo="01"; }
        else if ($d->electronica_identificacion_tipo==2){ $payload_emisor_tipo="02"; }
            else if ($d->electronica_identificacion_tipo==3){ $payload_emisor_tipo="03"; }
                else if ($d->electronica_identificacion_tipo==4){ $payload_emisor_tipo="04"; }
    
    
    $this->emisor_identificacion      =  $d->electronica_identificacion_numero ; 
    $this->emisor_identificacion      =  ereg_replace("[^0-9]", "", $this->emisor_identificacion);
    $this->emisor_tipo                =  $payload_emisor_tipo;
    $this->clave                      =  $d->clave;
    $this->consecutivo                =  $d->consecutivo;
    $this->documento_consecutivo      =  $d->consecutivo;
    $this->documento_clave            =  $d->clave;


 


    return $d->clave;


    



}



    
public function getClave($factura){
    
     if ( $this->consecutivo_tipo_documento=="01" or $this->consecutivo_tipo_documento=="04" or $this->consecutivo_tipo_documento=="09" )
          { $tabla="fi_facturas" ; } 
              else { $tabla='fi_notas_credito'; }


       $sql= "SELECT    e.*              ,
      t.tipo           ,
      t.cedula         ,
      f.entidad        ,
      f.rowid as IdFactura,
      con.electronica_identificacion_tipo ,
      con.electronica_identificacion_numero 
      
      
    FROM `electronica_digital_electronica` e 
          left join   $tabla   f  on f.rowid = e.fk_factura 
          left join  fi_terceros   t  on t.rowid = f.fk_tercero
          left join  fi_configuracion_empresa con on  con.rowid = f.entidad
          
    where e.fk_factura = :factura  and e.consecutivo_tipo_documento = '". $this->consecutivo_tipo_documento."' ";
    
    $dbi = $this->db->prepare($sql);

    ## MEjora para agregar las notas de Credito !
    $dbi->bindValue(":factura",                     $factura   ,PDO::PARAM_INT);
    // $dbi->bindValue(":consecutivo_tipo_documento",  $this->consecutivo_tipo_documento,PDO::PARAM_STR);
    
    
    $dbi->execute();
    
    $d=$dbi->fetch(PDO::FETCH_OBJ);
   
     
    //$ano, $mes, $dia;
    // establecimiento (001) + punto de venta (00001) + tipo documento (01) "factura electrónica" + número consecutivo (0000000001)
    $consecutivo = '001' . '00001' . '01' . '0000000070';

    /// Viene de Base de Datos 
    $consecutivo = $d->consecutivo_establecimiento;
    $consecutivo.= $d->consecutivo_punto_venta;
    $consecutivo.= $d->consecutivo_tipo_documento;
    $consecutivo.= $d->consecutivo_consecutivo; 
    
    
    
    $dia         = date('d',strtotime($d->consecutivo_fecha));
    $mes         = date('m',strtotime($d->consecutivo_fecha));
    $ano         = date('y',strtotime($d->consecutivo_fecha));

   
    
        if ( $d->tipo=="fisica" )          {  $this->receptor_tipo =  "01"; }
            else if ( $d->tipo=="juridica" ) { $this->receptor_tipo =  "02"; }
                else if ( $d->tipo=="dimex" ) { $this->receptor_tipo =  "03"; }
                  else if ( $d->tipo=="nite" )  { $this->receptor_tipo =  "04"; }
                    else { $this->receptor_tipo ="01";    }


    $this->receptor_identificacion   =  mb_substr("000000000000".$d->cedula , -12);  




    if ($d->electronica_identificacion_tipo==1){ $this->emisor_tipo="01"; }
        else if ($d->electronica_identificacion_tipo==2){ $this->emisor_tipo="02"; }
            else if ($d->electronica_identificacion_tipo==3){ $this->emisor_tipo="03"; }
                else if ($d->electronica_identificacion_tipo==4){ $this->emisor_tipo="04"; }
    
    
    $this->emisor_identificacion      = ($d->electronica_identificacion_numero); 
    $this->emisor_identificacion      =  ereg_replace("[^0-9]", "", $this->emisor_identificacion);



    // Prepare document
    // país (506) + día (01) + mes(12) + año (17) + cédula del emisor (000115010959) + consecutivo (00000000000000000001) + tipo (1) 'normal' + código de seguridad (12345678)
 
    $pais = '506';
    $tipo   = $this->electronica_identificacion_tipo;  //'1';
    
    
    $emisor =  mb_substr("0000000000".$this->electronica_identificacion_numero ,-12);


    
    $seguridad = '12345678';
    
    
    $clave = $pais . $dia . $mes . $ano .  $emisor . $consecutivo . $tipo . $seguridad;
    //esta es la valida
    //50627121700011501095900100001010000000070112345678
    
    //50627121700011501095900100001010000000070112345678
    $this->documento_clave = $clave;
    $this->documento_consecutivo=$consecutivo;
    $this->documento_fecha = $d->consecutivo_fecha;
    



     ## mejora para Poder Enviar Notas de credito , Debito y Factura
     if ($this->consecutivo_tipo_documento=="01"){ 
          $this->archivo="/home/facturac6/facturacion_electronica_documentos/facturas/".$d->entidad."/".$consecutivo.".xml";
     
     
     } else if ($this->consecutivo_tipo_documento=="02"){ 
          $this->archivo="/home/facturac6/facturacion_electronica_documentos/debitos/".$d->entidad."/".$consecutivo.".xml";
     

     } else if ($this->consecutivo_tipo_documento=="03"){ 
          $this->archivo="/home/facturac6/facturacion_electronica_documentos/creditos/".$d->entidad."/".$consecutivo.".xml";
     

     } else if ($this->consecutivo_tipo_documento=="04"){ 
          $this->archivo="/home/facturac6/facturacion_electronica_documentos/tiquetes/".$d->entidad."/".$consecutivo.".xml";

       
      } else  if ($this->consecutivo_tipo_documento=="09"){
          $this->archivo="/home/facturac6/facturacion_electronica_documentos/exportacion/".$d->entidad."/".$consecutivo.".xml";
    } 
        
    
      
    
    
    
    return $clave;
}


 
//--------------------------------------------------------------
//
//   Crear Clave con 4.3 
//
//--------------------------------------------------------------



public function crear_clave($factura){
    
    $sql="insert into electronica_digital_electronica 
          (fk_entidad  ,
           fk_factura  ,
           consecutivo_fecha  ,
           consecutivo_establecimiento  ,
           consecutivo_punto_venta      ,
           consecutivo_tipo_documento   ,
           consecutivo_consecutivo      ,
           
           /*  Generamos la clave !    */
           clave_fecha                  ,
           clave_cedula                 ,
           clave_situacion              ,
           clave_control            
           ) 
           
           VALUES 
         
           (
           '".$_SESSION['Entidad']."'  ,
           :factura  ,
           :consecutivo_fecha  ,
           :consecutivo_establecimiento  ,
           :consecutivo_punto_venta      ,
           :consecutivo_tipo_documento   ,
           :consecutivo_consecutivo      ,
           :clave_fecha                  ,
           :clave_cedula                 ,
           :clave_situacion              ,
           :clave_control             )
           
           
           ";
    
    $fecha_clave=date('d')."".date("m")."".date("y");
    $consecutivo=  mb_substr("0000000000".$factura->referencia ,-10);
  
    
    $consecutivo_establecimiento =   mb_substr("0000".$this->fk_sucursal ,-3);
    $consecutivo_punto_venta     =  "00001";
    $consecutivo_tipo_documento  =  $this->consecutivo_tipo_documento ;
    $consecutivo_consecutivo     =  $consecutivo;
    
    
    
    $clave_cedula    = mb_substr("0000000000".$this->electronica_identificacion_numero  ,-12);
    $clave_situacion = 1 ;
    $clave_control  =   mb_substr("0000000000".$factura->id  ,-8);

    $consecutivo_generado = $consecutivo_establecimiento.$consecutivo_punto_venta.$consecutivo_tipo_documento.$consecutivo_consecutivo;

    
    $clave.= "506";
    $clave.= $fecha_clave;
    $clave.= $clave_cedula;
    $clave.= $consecutivo_generado;
    $clave.= $clave_situacion;
    $clave.= $clave_control ;
    
    
    
    

    $db= $this->db->prepare($sql);
    $db->bindValue(":factura", $factura->id, PDO::PARAM_INT);
    $db->bindValue(":consecutivo_fecha", $factura->fecha, PDO::PARAM_STR);
    $db->bindValue(":consecutivo_establecimiento", "001", PDO::PARAM_STR);
    $db->bindValue(":consecutivo_punto_venta", "00001", PDO::PARAM_STR);
    $db->bindValue(":consecutivo_tipo_documento", $this->consecutivo_tipo_documento , PDO::PARAM_STR);
    $db->bindValue(":consecutivo_consecutivo", $consecutivo , PDO::PARAM_STR);
    
    
    $db->bindValue(":clave_fecha", $fecha_clave , PDO::PARAM_STR);
    $db->bindValue(":clave_cedula", $clave_cedula , PDO::PARAM_STR);
    $db->bindValue(":clave_situacion", $clave_situacion  , PDO::PARAM_STR);
    $db->bindValue(":clave_control",  $clave_control   , PDO::PARAM_STR);
    $db->execute();        
    $id=$this->db->lastInsertId(); 
 
       
       
  
    //--------------------------------------------------------------------------
    //
    //      Actualizacion en 4.3 
    //
    //--------------------------------------------------------------------------
  
    if ($this->consecutivo_tipo_documento == "01" or  $this->consecutivo_tipo_documento == "04"  )          {    $tabla = 'fi_facturas';      }
        else if ($this->consecutivo_tipo_documento == "02" or $this->consecutivo_tipo_documento == "03") {    $tabla = 'fi_notas_credito'; }

                $sql="  update  
                    $tabla  
                    set  
                     consecutivo  = '$consecutivo_generado'  
                ,    clave        = '$clave'  
                ,    version      = '4.3'  

                    where rowid = :rowid  
                    ";
            
            $dbh=$this->db->prepare($sql);  
            $dbh->bindValue(':rowid'        , $factura->id       ,PDO::PARAM_INT);
            $dbh->execute( );
     

  
    $this->getClaveSmart($factura->id);
    return $id;

 } 

 






public function crear($factura){
    

 
    // Version 4.3
    if ($factura->electronica_tipo=="tiquete") {  $this->consecutivo_tipo_documento="04"; }
        else if ($factura->electronica_tipo=="factura") {  $this->consecutivo_tipo_documento="01"; }
    
    
    // $CodigoTarifa  Para poder enviar el COdigo Tarifa

    $CodigoTarifa[0 ]    =  '01';
    $CodigoTarifa[1 ]    =  '02';
    $CodigoTarifa[2 ]    =  '03';
    $CodigoTarifa[3 ]    =  '04';
    $CodigoTarifa[4 ]    =  '06';
    $CodigoTarifa[8 ]    =  '07';
    $CodigoTarifa[13]    =  '08';


 
    $actividad         =  (!empty($factura->actividad)) ? $factura->actividad   :  $factura->actividad_por_defecto ; 
    $actividad         =   mb_substr("000000".$actividad  ,-6);

  
    $CodigoActividad = "<CodigoActividad>".$actividad."</CodigoActividad>";



    
     $this->factura = $factura->id;
    
     if (empty($this->consecutivo)) {  $this->crear_clave($factura); }
    
     $this->receptor( $factura->id);
     $this->venta( $factura->id);
   
     $fecha_ahora=date('Y-m-d H:i:s');
     


   if ($this->consecutivo_tipo_documento=="01"){  $documento_xml="facturaElectronica"; $documento_xml_padre='FacturaElectronica';  } 
      else   if ($this->consecutivo_tipo_documento=="02"){   $documento_xml="notaDebitoElectronica";    $documento_xml_padre='NotaDebitoElectronica'; } 
        else if ($this->consecutivo_tipo_documento=="03"){ $documento_xml="notaCreditoElectronica";    $documento_xml_padre='NotaCreditoElectronica';    }
                else   if ($this->consecutivo_tipo_documento=="04"){ $documento_xml="tiqueteElectronico";    $documento_xml_padre='TiqueteElectronico';    }
                    else{  $documento_xml="documentoError";    $documento_xml_padre='documentoError';   }
    



    //-----------------------------------------------------
    // 
    //            Mejora los Campos Optativos
    //
    //------------------------------------------------------
    $emisor_comercial = (!empty($this->electronica_nombre_comercial)) ? 
    '<NombreComercial>'
    .$this->electronica_nombre_comercial
    .'</NombreComercial>' :'';

   $emisor_barrio = (!empty($this->electronica_barrio)) ? 
    '<Barrio>'.$this->electronica_barrio.'</Barrio>':'';

   $emisor_telefono  = (!empty($this->electronica_telefono)) ? 
   '<Telefono><CodigoPais>506</CodigoPais><NumTelefono>'.$this->electronica_telefono.'</NumTelefono></Telefono>':'';

   $emisor_fax =  (!empty($this->electronica_fax)) ? 
   '<Fax><CodigoPais>506</CodigoPais><NumTelefono>'.$this->electronica_fax.'</NumTelefono></Fax>':'';



  $emisor='<?xml version="1.0" encoding="UTF-8"?><'
  . $documento_xml_padre
  .' xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/'.$documento_xml.'"   >'
  .'<Clave>'
  .$this->documento_clave.'</Clave>'
  .$CodigoActividad 
  .'<NumeroConsecutivo>'.$this->documento_consecutivo.'</NumeroConsecutivo><FechaEmision>'
  .date('Y-m-d', strtotime($fecha_ahora)).'T'.date('H:i:s',strtotime($fecha_ahora)).'-06:00</FechaEmision><Emisor><Nombre>'
  .$this->electronica_nombre.'</Nombre><Identificacion><Tipo>0'
  .$this->electronica_identificacion_tipo.'</Tipo><Numero>'
  .$this->electronica_identificacion_numero.'</Numero></Identificacion>'
  .$emisor_comercial
  .'<Ubicacion><Provincia>'
  .$this->electronica_provincia.'</Provincia><Canton>'
  .$this->electronica_canton.'</Canton><Distrito>'
  .$this->electronica_distrito.'</Distrito>'
  .$emisor_barrio.'<OtrasSenas>'
  .$this->electronica_otras_senas.'</OtrasSenas></Ubicacion>'
  .$emisor_telefono
  .$emisor_fax
  .'<CorreoElectronico>'.$this->electronico_correo.'</CorreoElectronico></Emisor>';
   

   /////////////////////////////////////////////////////////////////////////
   /////////////////////////////////////////////////////////////////////////
   //////////////////////Datos para el Receptor ////////////////////////////
   /////////////////////////////////////////////////////////////////////////
   /////////////////////////////////////////////////////////////////////////



   $IdentificacionExtranjero = ($this->receptor_extranjero==1) ?  "<IdentificacionExtranjero>".$this->receptor_cedula."</IdentificacionExtranjero>" : "";


    $identificacion = ( (empty($this->receptor_cedula) or strlen($this->receptor_cedula) < 8)  ) 
      ? ''
      : '<Identificacion><Tipo>'.$this->receptor_tipo.'</Tipo><Numero>'.$this->receptor_cedula.'</Numero></Identificacion>' ; 

    $identificacion = ($this->receptor_extranjero==1) ? '' : $identificacion;




    $NombreComercial = (!empty($this->receptor_comercial)) 
    ? '<NombreComercial>'.$this->receptor_comercial.'</NombreComercial>' 
    :''; 


    $receptor_telefono        =      (!empty($this->receptor_telefono)) 
    ?  '<Telefono><CodigoPais>506</CodigoPais><NumTelefono>'.$this->receptor_telefono.'</NumTelefono></Telefono>' 
    : '';


   $receptor_correo = (!empty($this->receptor_correo)) 
   ? "<CorreoElectronico>".$this->receptor_correo."</CorreoElectronico>"  
   : "";



   $receptor='<Receptor><Nombre>'.$this->receptor_nombre.'</Nombre>'.$identificacion.$IdentificacionExtranjero.$NombreComercial.$receptor_telefono.$receptor_correo.'</Receptor>'; 

 
   $medio_pago ="";
   $medio_pago.= (!empty($factura->pagos_[1] ))  ? '<MedioPago>01</MedioPago>' : '';
   $medio_pago.= (!empty($factura->pagos_[2] ))  ? '<MedioPago>02</MedioPago>' : '';
   $medio_pago.= (!empty($factura->pagos_[3] ))  ? '<MedioPago>03</MedioPago>' : '';
   $medio_pago.= (!empty($factura->pagos_[4] ))  ? '<MedioPago>04</MedioPago>' : '';
   $medio_pago.= (!empty($factura->pagos_[5] ))  ? '<MedioPago>05</MedioPago>' : '';
   $medio_pago.= (!empty($factura->pagos_[99]))  ? '<MedioPago>99</MedioPago>' : '';


   $medio_pago = (empty($medio_pago ))   ? '<MedioPago>01</MedioPago>' : $medio_pago;    

   $factura->condicion_venta = (empty($factura->condicion_venta )) ? "01" : $factura->condicion_venta;
   
 
   $forma_venta = '<CondicionVenta>'
                  .$factura->condicion_venta.'</CondicionVenta><PlazoCredito>'
                  .$factura->plazo_credito.'</PlazoCredito>'
                  .$medio_pago;
   
   
     $footer="</".$documento_xml_padre.">";




    //================================
    //
    // Formamos el detalle 
    //
    
    if ( $this->consecutivo_tipo_documento=="01" or $this->consecutivo_tipo_documento=="04"   ){
        $tabla="fi_facturas_detalle ";
    } else if ($this->consecutivo_tipo_documento=="02"){
        $tabla="fi_notas_credito_detalle";
    } else if ($this->consecutivo_tipo_documento=="03"){
        $tabla="fi_notas_credito_detalle";
    }
    
        
      $sql="
          select 
          t.*  ,
          p.unidad 

          from  $tabla  t 
          left join  fi_productos   p on  fk_producto = p.rowid and p.entidad = '".$factura->entidad."'
          
          where 
          
          t.fk_factura = ".$factura->id;
 
    $db = $this->db->prepare($sql);
    $db->execute();
    $i=1;
    while ($datos= $db->fetch(PDO::FETCH_OBJ)){
        
 
        //--------------------------
        //
        // cALCULO PARA los 11 totales diferentes
        //  
        
        $sql = "SELECT * FROM `fi_facturas_exonerar` WHERE `fk_detalle` = ".$datos->rowid." order by rowid desc";
        $dbExo = $this->db->prepare($sql);
        $dbExo->execute();
        if($dbExo->rowCount() > 0){
            $datosExonera = $dbExo->fetch(PDO::FETCH_OBJ);    
        }


        if ($datos->tipo==1){
            $unidad ="Unid";
             
 
            if(isset($datosExonera) and $datos->exoneracion == 1 ){
                    $precioOriginal = $datos->precio_original * $datos->cantidad;
                    $RESUMEN_PRODUCTOS_EXONERADOS    += $datos->precio_original * $datos->cantidad;    
 
            } else if ($datos->impuesto > 0 ){
                    $RESUMEN_PRODUCTOS_GRABADOS += $datos->precio_original * $datos->cantidad;  

                
            } else{            
                $RESUMEN_PRODUCTOS_EXENTOS  += $datos->precio_original * $datos->cantidad;  

            }
                                     
                                     
        
        }else { 

            $unidad ="Sp";
 
             if(isset($datosExonera) and $datos->exoneracion == 1 ){
                    $precioOriginal = $datos->precio_original * $datos->cantidad;
                    $RESUMEN_SERVICIOS_EXONERADOS    += $datos->precio_original * $datos->cantidad;    
            } else if ($datos->impuesto > 0 ){
                    $RESUMEN_SERVICIOS_GRABADOS += $datos->precio_original * $datos->cantidad;  
                 
            }else{            
                $RESUMEN_SERVICIOS_EXENTOS  += $datos->precio_original * $datos->cantidad;  
            }

        }
        
        
        
        
       // $datos->impuesto = (isset($datosExonera) and $datos->exoneracion == 1 ) ? 0 : $datos->impuesto;
        
        //david
        //7 Septiembre 2019 
        // Cambio General  Impuesto Pra exonerado
        // Comentar en caso de fallo por impuestos!
        $datos->impuesto = ( $datos->ImpuestoNeto   > 0  ) ? $datos->ImpuestoNeto : $datos->impuesto; // Mejora Exoneracion
        $RESUMEN_IMPUESTOS_SUMADOS+=$datos->impuesto;
        $RESUMEN_DESCUENTOS_APLICADOS+=$datos->descuento_valor_final;

        /////////////////////////////////////////////////
        //
        //
        ////////////////////////////////////////////////


        // --------  Unitario  (En nuestro sistema es el precio Original)
        //
        $unitario = $datos->precio_original;
        
        
        

        ///----------- Unitario Original * Cantidad
        $subtotal_linea   =$datos->cantidad * $datos->precio_original;
        $subtotal_final+= $datos->cantidad * $datos->subtotal;
        
        

        //------------ Descuento Aplicado
         $Monto_descuento_linea_aplicado=$datos->descuento_valor_final; 
         
         $TotalDescuentos+=$datos->descuento_valor_final; 
        
        if ($datos->descuento_valor_final > 0){ 
            $MontoDescuento="<Descuento>"
                              ."<MontoDescuento>".($this->decimalDinero($datos->descuento_valor_final))."</MontoDescuento>"
                              ."<NaturalezaDescuento>Cliente Preferente</NaturalezaDescuento>"
                              ."</Descuento>"; 
        }
          else { $MontoDescuento=""; }
        
        /*<xs:documentation>Se obtiene de la suma de todos los campo de monto de
                descuento concedido</xs:documentation>        */
        
        // Monto SubTotal Se obtiene de la resta del campo monto total menos monto de descuento concedido<
        $total=$subtotal_linea-$MontoDescuento;
        $total_final+=$total;
        
        // Impuesto 
         
        
        
        if ($datos->impuesto >0 or $datos->exoneracion == 1){

            $impuesto=$datos->impuesto;
            $TotalImpuesto+=$datos->impuesto;
            
            
            // Asi lo pide esta gente famosisima!
            $impuesto_tarifa=$this->decimal($datos->tipo_impuesto);
            $impuesto_codigo="01";
            $impuesto_monto= $this->decimalDinero($datos->impuesto);
            
            
            
            $exoneracion_cadena = "";
            $ImpuestoNeto       = "";
            
            /*  EXONERACIONES  */
            $sql = "SELECT * FROM `fi_facturas_exonerar` WHERE `fk_detalle` = ".$datos->rowid;
            $dbExo = $this->db->prepare($sql);
            $dbExo->execute();
            if($dbExo->rowCount() > 0){
                $exo                = $dbExo->fetch(PDO::FETCH_OBJ);
                $exo_monto          = $this->decimalDinero($exo->monto_impuesto);
                $exoneracion_cadena = "<Exoneracion><TipoDocumento>".$exo->tipo_documento."</TipoDocumento><NumeroDocumento>".$exo->numero_documento."</NumeroDocumento><NombreInstitucion>".$exo->nombre_institucion."</NombreInstitucion><FechaEmision>".date('Y-m-d', strtotime($exo->fecha_emision))."T".date('H:i:s',strtotime($exo->fecha_emision))."-06:00</FechaEmision><PorcentajeExoneracion>".$exo->porcentaje_compra."</PorcentajeExoneracion><MontoExoneracion>".$exo_monto."</MontoExoneracion></Exoneracion>";
                $impuesto_monto     = $this->decimalDinero($exo->monto_impuesto);
              //  $ImpuestoNeto       = "<ImpuestoNeto>0</ImpuestoNeto>";

                $ImpuestoNeto       = "<ImpuestoNeto>".$this->decimalDinero($datos->ImpuestoNeto)."</ImpuestoNeto>";

                
            }else{

                $exoneracion_cadena = "";
        
            }
            
                   
                $impuesto_cadena =  "<Impuesto>"
                                  . "<Codigo>"         .$impuesto_codigo."</Codigo>"
                                  . "<CodigoTarifa>"   .$CodigoTarifa[(int)$impuesto_tarifa]   ."</CodigoTarifa>"
                                  . "<Tarifa>"         .$impuesto_tarifa."</Tarifa>"
                                  . "<Monto>".$impuesto_monto."</Monto>"
                                  . $exoneracion_cadena
                                  . "</Impuesto>" 
                                  . $ImpuestoNeto;   
            
            
            
            //Total de los servicios gravados con IV<
            $TotalServGravados +=   $datos->total;
            $TotalMercanciasGravadas+=0;//
            $TotalGravado +=   $datos->total;
        }else{
           $TotalServExentos+=  $datos->total;
           $TotalMercanciasExentas+=0;
           $TotalExento+=$datos->total;
           $impuesto_cadena="";
           
        }
        
        $TotalVenta+=$datos->total;
        



/*
        if  (!empty($datos->unidad))  { $unidad =  $datos->unidad;  }
           else  if ($datos->tipo ==1 ){   $unidad =  "Sp";   }
                else  { $unidad =  "Unid"; }
*/


        $lineas.= "<LineaDetalle>"
                  ."<NumeroLinea>".$i."</NumeroLinea>"
                ."<Codigo>".$datos->CABYS_codigo."</Codigo>"
                  ."<Cantidad>".$datos->cantidad."</Cantidad>"
                  ."<UnidadMedida>$unidad</UnidadMedida>"
                  ."<UnidadMedidaComercial>" . ( $unidad) ."</UnidadMedidaComercial>"

                  ."<Detalle>".$this->limpieza($datos->label)."</Detalle>"
                  ."<PrecioUnitario>".($this->decimalDinero($unitario))."</PrecioUnitario>"
                  ."<MontoTotal>".($this->decimalDinero($subtotal_linea))."</MontoTotal>".$MontoDescuento."<SubTotal>".($this->decimalDinero($subtotal_linea-$Monto_descuento_linea_aplicado))."</SubTotal>$impuesto_cadena<MontoTotalLinea>".($this->decimalDinero($datos->total))."</MontoTotalLinea></LineaDetalle>";
        $i++;
    }
    


    $sql = "Select servicio_mesa, total, entidad  from fi_facturas where rowid = ".$factura->id." and servicio_mesa = 1";
  $dbServ = $this->db->prepare($sql);
  $dbServ->execute();
  if($dbServ->rowCount() > 0){
    $servicioMesa = $dbServ->fetch(PDO::FETCH_OBJ);
    $lineas.= "<LineaDetalle><NumeroLinea>".$i."</NumeroLinea><Codigo><Tipo>99</Tipo><Codigo>S</Codigo></Codigo><Cantidad>1</Cantidad><UnidadMedida>Unid</UnidadMedida><Detalle>Servicio Mesa</Detalle><PrecioUnitario>".($this->decimalDinero($servicioMesa->total * 0.10))."</PrecioUnitario><MontoTotal>".($this->decimalDinero($servicioMesa->total * 0.10))."</MontoTotal><SubTotal>".($this->decimalDinero($servicioMesa->total * 0.10))."</SubTotal><MontoTotalLinea>".($this->decimalDinero($servicioMesa->total * 0.10))."</MontoTotalLinea></LineaDetalle>";
    $RESUMEN_SERVICIOS_EXENTOS  += ($servicioMesa->total * 0.10);
  }
 
    
    $detalle="<DetalleServicio>$lineas</DetalleServicio>";
   
   
   
   
   
   
   
   
   
   
    //===========================================================
    //
    //  Ahora formamos el resultado Final de la factura
    //
    //
    
        if ($this->consecutivo_tipo_documento=="01"){
                $sql="select * from fi_facturas   where rowid = ".$factura->id;
        } else {
                $sql="select * from fi_notas_credito    where rowid = ".$factura->id;
        }


    $db = $this->db->prepare($sql);
    $db->execute();
    $datos= $db->fetch(PDO::FETCH_OBJ);
   
   
   
   
                    $RESUMEN_PRODUCTOS_GRABADOS;
                    $RESUMEN_PRODUCTOS_EXENTOS;
                    $RESUMEN_SERVICIOS_GRABADOS;
                    $RESUMEN_SERVICIOS_EXENTOS;
                    
                    $RESUMEN_GRABADO     =  $RESUMEN_PRODUCTOS_GRABADOS  +$RESUMEN_SERVICIOS_GRABADOS;
                    $RESUMEN_EXCENTOS    =  $RESUMEN_PRODUCTOS_EXENTOS   +$RESUMEN_SERVICIOS_EXENTOS;
                    $RESUMEN_EXONERADO   =    $RESUMEN_PRODUCTOS_EXONERADOS  +   $RESUMEN_SERVICIOS_EXONERADOS;

                    $RESUMEN_TOTAL_VENTA =  $RESUMEN_GRABADO +$RESUMEN_EXCENTOS + $RESUMEN_EXONERADO ;
                    
                    
                    $RESUMENVENTANETA= $RESUMEN_TOTAL_VENTA -  $RESUMEN_DESCUENTOS_APLICADOS;
                    
                    $RESUMEN_IMPUESTOS_SUMADOS;
                    $RESUMEN_DESCUENTOS_APLICADOS;

        /////////////////////////////////////////////////
        //
        //
        ////////////////////////////////////////////////
        
   
   
    //actualizado para poder ser multimoneda!!!
  //   $resumen ="<ResumenFactura><CodigoMoneda>".$factura->moneda_codigo."</CodigoMoneda><TipoCambio>".$factura->moneda_tipo_cambio."</TipoCambio><TotalServGravados>".($this->decimalDinero($TotalServGravados))."</TotalServGravados><TotalServExentos>".($this->decimalDinero($TotalServExentos))."</TotalServExentos><TotalMercanciasGravadas>".($this->decimalDinero($TotalMercanciasGravadas))."</TotalMercanciasGravadas><TotalMercanciasExentas>".($this->decimalDinero($TotalMercanciasExentas))."</TotalMercanciasExentas><TotalGravado>".($this->decimalDinero($TotalGravado))."</TotalGravado><TotalExento>".($this->decimalDinero($TotalExento))."</TotalExento><TotalVenta>".($this->decimalDinero($TotalVenta))."</TotalVenta><TotalDescuentos>".($this->decimalDinero($TotalDescuentos))."</TotalDescuentos><TotalVentaNeta>".($this->decimalDinero($datos->total-$TotalDescuentos))."</TotalVentaNeta><TotalImpuesto>".($this->decimalDinero($TotalImpuesto))."</TotalImpuesto><TotalComprobante>".($this->decimalDinero($datos->total))."</TotalComprobante></ResumenFactura>";
   
   
    //------------------------------------------------------------------------------------------ 
    //
    // Informacion Extra Para Nota de Credito y Debito
    
    if ($this->consecutivo_tipo_documento=="01"){
    $InformacionReferencia="";

    } else if ($this->consecutivo_tipo_documento=="03"){
  
        $sql = "Select fk_documento_modifica_tipo, codigoNota, razon, tipo from fi_notas_credito where rowid  = ".$this->factura;
    $db4 = $this->db->prepare($sql);
    $db4->execute();
    $tipoDocumentos = $db4->fetch(PDO::FETCH_OBJ);

    switch($tipoDocumentos->fk_documento_modifica_tipo){
      case 'factura':
        $tabla = "fi_facturas";
        $tipoConse = "'01'";
        $tipoCodigo = "01";
      break;
      case 'nota_credito':
        $tabla = "fi_notas_credito";
        $tipoConse = "'03'";
        $tipoCodigo = "03";
      break;
      case 'nota_debito':
        $tabla = "fi_notas_credito";
        $tipoConse = "'02'";
        $tipoCodigo = "02";
      break;
    }
    
    $sql=" select 
                CONCAT(consecutivo_establecimiento,consecutivo_punto_venta, consecutivo_tipo_documento,consecutivo_consecutivo) as  clave,
                (select electronica_enviada_fecha from ".$tabla." where rowid = '".$factura->fk_documento_modifica."'  limit 0,1) as fecha_emision                      ,
                (select total from ".$tabla." where rowid = '".$factura->fk_documento_modifica."'  limit 0,1) as total                      
                from electronica_digital_electronica where fk_factura = '".$factura->fk_documento_modifica."'  and consecutivo_tipo_documento = ".$tipoConse." ";

    $db3 = $this->db->prepare($sql);
    $db3->execute();
    $informacion= $db3->fetch(PDO::FETCH_OBJ);
   
    $FechaEmision="<FechaEmision>".date('Y-m-d', strtotime($informacion->fecha_emision)).'T'.date('H:i:s', strtotime($informacion->fecha_emision))."-06:00</FechaEmision>";
        
    $Codigo__= "<Codigo>".$tipoDocumentos->codigoNota."</Codigo>";
       
    $Numero="<Numero>".$informacion->clave."</Numero>";
     
    if ( $this->consecutivo_tipo_documento=="01"){ $tabla="fi_facturas" ; } else { $tabla='fi_notas_credito'; }

    $InformacionReferencia="<InformacionReferencia><TipoDoc>".$tipoCodigo."</TipoDoc>".$Numero.$FechaEmision.$Codigo__."<Razon>".$tipoDocumentos->razon."</Razon></InformacionReferencia>";
     
  
    } else if ($this->consecutivo_tipo_documento=="02"){
      
        $sql = "Select fk_documento_modifica_tipo, codigoNota, razon from fi_notas_credito where rowid  = ".$this->factura;
    $db4 = $this->db->prepare($sql);
    $db4->execute();
    $tipoDocumentos = $db4->fetch(PDO::FETCH_OBJ);
    
    switch($tipoDocumentos->fk_documento_modifica_tipo){
      case 'factura':
        $tabla = "fi_facturas";
        $tipoConse = "'01'";
        $tipoCodigo = "01";
      break;
      case 'nota_credito':
        $tabla = "fi_notas_credito";
        $tipoConse = "'03'";
        $tipoCodigo = "03";
      break;
      case 'nota_debito':
        $tabla = "fi_notas_credito";
        $tipoConse = "'02'";
        $tipoCodigo = "02";
      break;
    }
    
    $sql=" select 
                CONCAT(consecutivo_establecimiento,consecutivo_punto_venta, consecutivo_tipo_documento,consecutivo_consecutivo) as  clave,
                (select electronica_enviada_fecha from ".$tabla." where rowid = '".$factura->fk_documento_modifica."'  limit 0,1) as fecha_emision                      ,
                (select total from ".$tabla." where rowid = '".$factura->fk_documento_modifica."'  limit 0,1) as total                      
                from electronica_digital_electronica where fk_factura = '".$factura->fk_documento_modifica."' and consecutivo_tipo_documento = ".$tipoConse."  ";

    $db3 = $this->db->prepare($sql);
    $db3->execute();
    $informacion= $db3->fetch(PDO::FETCH_OBJ);
   
        
    $FechaEmision="<FechaEmision>".date('Y-m-d', strtotime($informacion->fecha_emision)).'T'.date('H:i:s', strtotime($informacion->fecha_emision))."-06:00</FechaEmision>";

    $Codigo__= "<Codigo>".$tipoDocumentos->codigoNota."</Codigo>";

    $Numero="<Numero>".$informacion->clave."</Numero>";

    $InformacionReferencia="<InformacionReferencia><TipoDoc>".$tipoCodigo."</TipoDoc>".$Numero.$FechaEmision.$Codigo__."<Razon>".$tipoDocumentos->razon."</Razon></InformacionReferencia>";
    
   
    }
    
    
    
   
   
   
   
   
   
   
      $total_para_no_fallar_decimales=($this->decimalDinero($RESUMENVENTANETA + $RESUMEN_IMPUESTOS_SUMADOS));
      
      
      $resumen ="<ResumenFactura><CodigoMoneda>".$factura->moneda_codigo."</CodigoMoneda><TipoCambio>".$factura->moneda_tipo_cambio."</TipoCambio><TotalServGravados>".($this->decimalDinero($RESUMEN_SERVICIOS_GRABADOS))."</TotalServGravados><TotalServExentos>".($this->decimalDinero($RESUMEN_SERVICIOS_EXENTOS))."</TotalServExentos><TotalMercanciasGravadas>".($this->decimalDinero($RESUMEN_PRODUCTOS_GRABADOS))."</TotalMercanciasGravadas><TotalMercanciasExentas>".($this->decimalDinero($RESUMEN_PRODUCTOS_EXENTOS))."</TotalMercanciasExentas><TotalGravado>".($this->decimalDinero($RESUMEN_GRABADO))."</TotalGravado><TotalExento>".($this->decimalDinero($RESUMEN_EXCENTOS))."</TotalExento><TotalVenta>".($this->decimalDinero($RESUMEN_TOTAL_VENTA))."</TotalVenta><TotalDescuentos>".($this->decimalDinero($RESUMEN_DESCUENTOS_APLICADOS))."</TotalDescuentos><TotalVentaNeta>".($this->decimalDinero($RESUMENVENTANETA))."</TotalVentaNeta><TotalImpuesto>".($this->decimalDinero($RESUMEN_IMPUESTOS_SUMADOS))."</TotalImpuesto><TotalComprobante>".$total_para_no_fallar_decimales."</TotalComprobante></ResumenFactura>".$InformacionReferencia;
   
      //-----------------------------------------------------------------------
      //  
      // Actualizacion para el ICE
      // ACtualizacion Generada para cumplir solicitud ICE 
      // 
      //-----------------------------------------------------------------------
      $sql ="select rowid ,  ocICE , ocICERazon , ocICEFecha   from fi_factura_ICE where fk_factura = :fk_factura  ";
      $db3 = $this->db->prepare($sql );
      $db3->bindValue(":fk_factura" , $this->factura , PDO::PARAM_INT);
      $db3->execute();
      $informacionICE= $db3->fetch(PDO::FETCH_OBJ);
      if (!empty($informacionICE->rowid)){ 
          
          //var_dump( $informacionICE );
          
        $InformacionReferenciaICE = "<InformacionReferencia>"
                                        ."<TipoDoc>99</TipoDoc>"
                                        ."<Numero>".trim($informacionICE->ocICE)."</Numero>"
                                        ."<FechaEmision>".date('Y-m-d', strtotime($informacionICE->ocICEFecha))."T".date('H:i:s',strtotime($informacionICE->ocICEFecha))."-06:00</FechaEmision>"
                                        ."<Codigo>99</Codigo>"
                                        ."<Razon>".$informacionICE->ocICERazon."</Razon>"
                                ."</InformacionReferencia>";
     
      }
      
      
      
      
      
      
      
   $resumen 
      ="<ResumenFactura><CodigoTipoMoneda>"
      ."<CodigoMoneda>".$factura->moneda_codigo."</CodigoMoneda><TipoCambio>".$factura->moneda_tipo_cambio."</TipoCambio></CodigoTipoMoneda>"
      ."<TotalServGravados>".($this->decimalDinero($RESUMEN_SERVICIOS_GRABADOS))."</TotalServGravados>"
      ."<TotalServExentos>".($this->decimalDinero($RESUMEN_SERVICIOS_EXENTOS))."</TotalServExentos>"
      ."<TotalServExonerado>".($this->decimalDinero( $RESUMEN_SERVICIOS_EXONERADOS   ))."</TotalServExonerado>"
      ."<TotalMercanciasGravadas>".($this->decimalDinero($RESUMEN_PRODUCTOS_GRABADOS))."</TotalMercanciasGravadas>"
      ."<TotalMercanciasExentas>".($this->decimalDinero($RESUMEN_PRODUCTOS_EXENTOS))."</TotalMercanciasExentas>"
      ."<TotalMercExonerada>".($this->decimalDinero(  $RESUMEN_PRODUCTOS_EXONERADOS  ))."</TotalMercExonerada>"
      ."<TotalGravado>".($this->decimalDinero($RESUMEN_GRABADO))."</TotalGravado>"
      ."<TotalExento>".($this->decimalDinero($RESUMEN_EXCENTOS))."</TotalExento>"
      ."<TotalExonerado>".($this->decimalDinero($RESUMEN_EXONERADO))."</TotalExonerado>"
      ."<TotalVenta>".($this->decimalDinero($RESUMEN_TOTAL_VENTA))."</TotalVenta>"
      ."<TotalDescuentos>".($this->decimalDinero($RESUMEN_DESCUENTOS_APLICADOS))."</TotalDescuentos>"
      ."<TotalVentaNeta>".($this->decimalDinero($RESUMENVENTANETA))."</TotalVentaNeta>"
      ."<TotalImpuesto>".($this->decimalDinero($RESUMEN_IMPUESTOS_SUMADOS))."</TotalImpuesto>"
      ."<TotalComprobante>".$total_para_no_fallar_decimales."</TotalComprobante>"
      ."</ResumenFactura>"
      . $InformacionReferencia
      . $InformacionReferenciaICE;

    $xml=  trim($emisor);
    $xml.= trim($receptor);
    $xml.= trim($forma_venta);
    $xml.= trim($detalle);
    $xml.= trim($resumen);
    $xml.= trim($footer);
   
    /*
    
    <?xml version="1.0" encoding="UTF-8"?>
<FacturaElectronica xmlns="https://tribunet.hacienda.go.cr/docs/esquemas/2017/v4.2/facturaElectronica"><Clave>50627121700011501095900100001010000000101112345678</Clave><NumeroConsecutivo>00100001010000000101</NumeroConsecutivo><FechaEmision>2017-12-27T10:16:00</FechaEmision><Emisor><Nombre>David Bermejo Porras</Nombre><Identificacion><Tipo>01</Tipo><Numero>115010959</Numero></Identificacion><NombreComercial>NAVARRA DESARROLLO DE SOFTWARE</NombreComercial><Ubicacion><Provincia>1</Provincia><Canton>01</Canton><Distrito>09</Distrito><Barrio>01</Barrio><OtrasSenas>ALFA</OtrasSenas></Ubicacion><Telefono><CodigoPais>506</CodigoPais><NumTelefono>22096000</NumTelefono></Telefono><Fax><CodigoPais>506</CodigoPais><NumTelefono>22096174</NumTelefono></Fax><CorreoElectronico>tu@email.com</CorreoElectronico></Emisor>



<Receptor><Nombre>COVIDIEN MANUFACTURING SOLUTIONS S.A.</Nombre><Identificacion><Tipo>02</Tipo><Numero>3101587185</Numero></Identificacion><NombreComercial>COVIDIEN MANUFACTURING SOLUTIONS S.A.</NombreComercial><Telefono><CodigoPais>506</CodigoPais><NumTelefono>24365700</NumTelefono></Telefono></Receptor>


<CondicionVenta>02</CondicionVenta><PlazoCredito>30</PlazoCredito><MedioPago>02</MedioPago>



<DetalleServicio><LineaDetalle><NumeroLinea>1</NumeroLinea><Codigo><Tipo>04</Tipo><Codigo>P</Codigo></Codigo><Cantidad>1.00</Cantidad><UnidadMedida>kg</UnidadMedida><Detalle>DHL EXPRESS WORLDWIDE- NON DOC</Detalle><PrecioUnitario>54.59000</PrecioUnitario><MontoTotal>54.59000</MontoTotal><SubTotal>54.59000</SubTotal><MontoTotalLinea>54.59000</MontoTotalLinea></LineaDetalle></DetalleServicio>



<ResumenFactura><CodigoMoneda>USD</CodigoMoneda><TipoCambio>569.83</TipoCambio><TotalServGravados>54.59000</TotalServGravados><TotalServExentos>0.00</TotalServExentos><TotalMercanciasGravadas>0.00</TotalMercanciasGravadas><TotalMercanciasExentas>0.00</TotalMercanciasExentas><TotalGravado>54.59000</TotalGravado><TotalExento>0.00</TotalExento><TotalVenta>54.59000</TotalVenta><TotalDescuentos>0.00</TotalDescuentos><TotalVentaNeta>54.59000</TotalVentaNeta><TotalImpuesto>0.00</TotalImpuesto><TotalComprobante>54.59000</TotalComprobante></ResumenFactura>

<Normativa><NumeroResolucion>DGT-R-48-2016</NumeroResolucion><FechaResolucion>07-10-2016 08:00:00</FechaResolucion></Normativa></FacturaElectronica>
    
    */
    
    
    


    ## Actulizamos la hora de creacion de la factura
    if ($this->consecutivo_tipo_documento == "01" or $this->consecutivo_tipo_documento=="04"){ $tabla=" fi_facturas  "; } 
        else { $tabla=" fi_notas_credito  "; }
    $this->db->query("update $tabla  set electronica_enviada_fecha =  '$fecha_ahora' where rowid = ".$factura->id);


 

    return $this->xml= $xml;

}


function salvar(){
 


 


    ## Mejora para la parte de Nota de Credito o Debito
    if ($this->consecutivo_tipo_documento=="01"){
        
        $BASE    = $this->carpeta_documentos."facturas_temporales/".$this->empresaIDCarpeta;  //mejora para multiples Bases de datos !
        $archivo = $this->empresaIDCarpeta.".".$this->documento_consecutivo.".sinfirmar.xml";
    

    } else  if ($this->consecutivo_tipo_documento=="04"){
        
        $BASE    = $this->carpeta_documentos."tiquetes_temporales/".$this->empresaIDCarpeta;  //mejora para multiples Bases de datos !
        $archivo = $this->empresaIDCarpeta.".".$this->documento_consecutivo.".sinfirmar.xml";


   } else  if ($this->consecutivo_tipo_documento=="02"){
        
        $BASE    = $this->carpeta_documentos."debitos_temporales/".$this->empresaIDCarpeta;  //mejora para multiples Bases de datos !
        $archivo = $this->empresaIDCarpeta.".".$this->documento_consecutivo.".sinfirmar.xml";


   } else  if ($this->consecutivo_tipo_documento=="09"){
        
        $BASE    = $this->carpeta_documentos."exportacion_temporales/".$this->empresaIDCarpeta;  //mejora para multiples Bases de datos !
        $archivo = $this->empresaIDCarpeta.".".$this->documento_consecutivo.".sinfirmar.xml";



    } else { // NOtas de credito van por aqui
        $BASE    = $this->carpeta_documentos."notas_temporales/".$this->empresaIDCarpeta;  //mejora para multiples Bases de datos !
        $archivo = $this->empresaIDCarpeta.".".$this->documento_consecutivo.".sinfirmar.xml";

    }
     
   
    
    $this->archivo = $BASE."/".$archivo;
  

    // si no existe pues lo creamos ya tu sabes!
    if(!is_dir($BASE )) {         mkdir($BASE , 0777); }
    $fp = fopen($BASE."/".$archivo, "w");

    $this->base=$BASE;
 

    if ( !$fp ) {
        throw new Exception('File open failed.' );
      }  
    fputs($fp, $this->xml);
    fclose($fp);
    
     
}







public function decimal($x) { return   number_format($x, 2, '.', ''); }



public function decimalDinero($x) { return number_format($x, 5 ,  '.' ,''); } 


//------------------------------------------------------------------
//
//
//  Funcion que Firma el XML cargado en HD
//
//  Recibe $this->archivo     // Direccion Archivo XML Sin FIRMAR
//  Recibe $this->consecutivo // numero ID de la factura, esto es solo para guardar en HD
//         $this->consecutivo_tipo_documento   // 01-02-03-04-08-09
//         $this->empresaIDCarpeta     = Entidad
//           
//--------------------------------------------------------------------

public function firmar(){


    // Ticket 
    // Factura
    // Factura Exportacion
 


  if ($this->consecutivo_tipo_documento=="01" ){
          $this->archivo_firmado  = '/home/facturac6/facturacion_electronica_documentos/facturas/'.$this->empresaIDCarpeta.'/'.$this->documento_consecutivo.'.xml'; 
          $BASE                   = "/home/facturac6/facturacion_electronica_documentos/facturas/".$this->empresaIDCarpeta;
          $ruta                   = '/home/facturac6/facturacion_electronica_documentos/facturas_temporales/'.$this->empresaIDCarpeta.'/'.$this->empresaIDCarpeta.'.'.$this->documento_consecutivo.'.sinfirmar.xml';


  }    else   if ($this->consecutivo_tipo_documento=="04" ){
          $this->archivo_firmado  = '/home/facturac6/facturacion_electronica_documentos/tiquetes/'.$this->empresaIDCarpeta.'/'.$this->documento_consecutivo.'.xml'; 
          $BASE                   = "/home/facturac6/facturacion_electronica_documentos/tiquetes/".$this->empresaIDCarpeta;
          $ruta                   = '/home/facturac6/facturacion_electronica_documentos/tiquetes_temporales/'.$this->empresaIDCarpeta.'/'.$this->empresaIDCarpeta.'.'.$this->documento_consecutivo.'.sinfirmar.xml';


  }    else  if ($this->consecutivo_tipo_documento=="09" ){
          $this->archivo_firmado  = '/home/facturac6/facturacion_electronica_documentos/exportacion/'.$this->empresaIDCarpeta.'/'.$this->documento_consecutivo.'.xml'; 
          $BASE                   = "/home/facturac6/facturacion_electronica_documentos/exportacion/".$this->empresaIDCarpeta;
          $ruta                   = '/home/facturac6/facturacion_electronica_documentos/exportacion_temporales/'.$this->empresaIDCarpeta.''.$this->empresaIDCarpeta.'/'.$this->documento_consecutivo.'.sinfirmar.xml';


  }    else  if ($this->consecutivo_tipo_documento=="02" ){
          $this->archivo_firmado  = '/home/facturac6/facturacion_electronica_documentos/debitos/'.$this->empresaIDCarpeta.'/'.$this->documento_consecutivo.'.xml'; 
          $BASE                   = "/home/facturac6/facturacion_electronica_documentos/debitos/".$this->empresaIDCarpeta;
          $ruta                   = '/home/facturac6/facturacion_electronica_documentos/debitos_temporales/'.$this->empresaIDCarpeta.''.$this->empresaIDCarpeta.'/'.$this->documento_consecutivo.'sinfirmar.xml';



  }    else {
          $this->archivo_firmado  = '/home/facturac6/facturacion_electronica_documentos/notas/'.$this->empresaIDCarpeta.'/'.$this->documento_consecutivo.'.xml'; 
          $BASE                   = "/home/facturac6/facturacion_electronica_documentos/notas/".$this->empresaIDCarpeta;
          $ruta                   = '/home/facturac6/facturacion_electronica_documentos/notas_temporales/'.$this->empresaIDCarpeta.'/'.$this->documento_consecutivo.'.xml';


    }


 




    if(!is_dir($BASE )) {         mkdir($BASE , 0777); } // Aseguramos q existe EL directorio


 

  if (is_readable($ruta)) {
        

  } else {
      
      echo  "No se accedio al archivo sin firmar ";

      return true;
  }



  if ($this->ventas_firma=="java"){


        $java='java -jar /home/facturac6/public_html/sistema/mod_factura_electronica2/xades-epes.jar  --certificate "/home/facturac6/facturacion_electronica_documentos/certificados/'.$this->empresaIDCarpeta.'/'.$this->certificado.'" --password "'.$this->certificado_clave.'" --input "'.$ruta.'" --output "'.$this->archivo_firmado.'"';

        $a =shell_exec($java); 
  


  }  else  {


 
    $firmador = new Firmador();
    $firmado       = $this->archivo_firmado ;
    $pfx      = '/home/facturac6/facturacion_electronica_documentos/certificados/'.$this->empresaIDCarpeta.'/'.$this->certificado;
    $xml      = $ruta;
    $pin      = $this->clave;
    
    $a   = $firmador->firmarXml($pfx, $pin, $xml , $firmador::TO_XML_FILE, $firmado  );
 

   }
  
  



   return $a;
   
  }
  
  
  
  
  
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


/*
   public function limpieza( $string ){
        $strip = array(
          "~"
        , "`"
        , "!"
        , "^"
        , "&"
        , "\\"
        , "|"
        , ";"
        , ":"
        , "\""
        , "'"
        , "&#8216;"
        , "&#8217;"
        , "&#8220;"
        , "&#8221;"
        , "&#8211;"
        , "&#8212;"
        , "â€”"
        , "â€“"
        , ","
        , "<"
        , "."
        , ">"
        , "/");
        $clean = trim(ereg_replace($strip, "", strip_tags($string)));
    
        return $clean;

   }

*/

  
  
  
  
  
  
  
  //---------------------------------------------------------------------------
  //
  //     Function para Revisar el Estado de la factura
  //
  //----------------------------------------------------------------------------
                            
        
 public function revisa_Estado( $access_token, $clave){
                                       
                                  $recepcionPath = trim($this->api_path);
           //                      echo "<br>112: -->". $recepcionPath ;
           //                      echo "<br>Clave: ->$clave<- ";
           //                      echo "<br>Token: ->$access_token<- ";
                                
                              
                                try {
                                    $client = new \GuzzleHttp\Client([
                                        'timeout' => 4.0,
                                    ]);
                                
                                    $response = $client->request(
                                        'GET',
                                        $recepcionPath . '/' . $clave,
                                        [
                                            'headers' =>
                                                [
                                                    'Authorization' => "Bearer {$access_token}",
                                                    'Content-Type' => 'application/json',
                                                ]
                                        ]
                                    );
                               
                                 
                                    $content = $response->getBody()->getContents();
                                    $contentObj = json_decode($content);
                                                                        

                                   // var_dump($contentObj);

                                    $respuestaXmlBase64 = $contentObj->{'respuesta-xml'};
                                    $respuestaXmlTxt = base64_decode($respuestaXmlBase64);
                                    $respuestaXml = new SimpleXMLElement($respuestaXmlTxt);
                                    
                                
                                    
                               //     var_dump($respuestaXml);
                                    
                                    $datos=array();
                                    $datos['respuesta_xml']=$respuestaXmlBase64;
                                    $datos['respuesta']=(string)$respuestaXml;
                                    $datos['mensaje'] = $respuestaXml->DetalleMensaje;
                                    $datos['estado']=(int) $respuestaXml->Mensaje ;
                                    
                                    if ((int) $respuestaXml->Mensaje === 3) {
                                            $datos['error']=$respuestaXml->DetalleMensaje."";
                                            $respuesta=3;

                                    }
                                        else  if ((int) $respuestaXml->Mensaje === 1) {
                                         //   echo "<h1>sali bien!!</h1>";
                                             $respuesta = 1;   

                                    }
                                    
                                    
                                    $datos['estado']=$respuesta;
                                    return $datos;
                                
                                
                                    /*if ((int) $respuestaXml->Mensaje === 3) {
                                        throw new Exception((string) );
                                    }*/ 
                                    
                                } catch(Exception $E) {   
                                                                    


                                  //  echo '<br>No se comunico con Hacienda <Br>Errores:' . "\n";
                                     $E->getMessage(). "\n";
                                    // var_dump($E);
                                    $datos['respuesta']="No comunico con Hacienda ";
                                     $datos['error']=$E->getMessage()."";
                                     $datos['no_comunico_hacienda']=true;
                                     
                                    return $datos;
                                    
                                    
                                }
                                       
        }
  
  
  
  
  

}