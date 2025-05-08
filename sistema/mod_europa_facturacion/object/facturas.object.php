<?php

include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/object/documento_mercantil.object.php");
require_once ENLACE_SERVIDOR . "mod_logs/LoggerSistema.php";

class Factura extends  documento_mercantil
{
    public  $db;

    // Función __construct que acepta una conexión a la base de datos
    public function __construct($db, $entidad)
    {
        //if (empty($entidad)){ /* No puede ser vacio */ return false; }
        $this->db = $db;
        $this->documento_txt['plural']      = "Facturas";
        $this->documento_txt['singular']    = "Factura";
        $this->tipo_aeat                    = "F1";
        $this->documento                    = "fi_europa_facturas";
        $this->documento_detalle            = "fi_europa_facturas_detalle";
        $this->documento_configuracion_serie = "Factura-";
        $this->entidad                      = $entidad;
        $this->nombre_clase                 = "Factura";
        $this->cliente_proveedor            = "cliente";
        $this->listado_url                  = "factura_listado";
        $this->ruta_detalle_contenido       = "mod_europa_facturacion/ajax/ver_factura_items.ajax.php";
        $this->ver_url                      = "factura";
        $this->diccionario                  = "diccionario_factura_europa_diccionario";

        parent::__construct($db, $entidad);  // Esto inicializa la clase SEGURIDAD

        $this->cargar_configuracion_documento($this->entidad);

        $this->campos_minimos_borrador = ['monto_total_unico'];
    }




    public function factura_actualizar_verifactum_enviado($envio, $registro)
    {


        if (strtoupper($envio) == "INCORRECTO") {
            $estado_hacienda = " , estado_hacienda = 1";

            $this->mensaje_estado               = '<span class="badge badge-warning">Envio Incorrecto</span>';
            $this->mensaje_estado_error_o_no    =    "error";

            
        } else if (strtoupper($registro) == "INCORRECTO") {
            $estado_hacienda = " , estado_hacienda = 2";

            $this->mensaje_estado               = '<span class="badge badge-warning">Registo Incorrecto</span>';
            $this->mensaje_estado_error_o_no    =    "error";

        } else  if (strtoupper($envio) == "CORRECTO" and strtoupper($registro) == "CORRECTO") {
            $estado_hacienda = " , estado_hacienda = 4 ";
            $this->mensaje_estado               = '<span class="badge badge-success">Correcto</span>';;
            $this->mensaje_estado_error_o_no    =    "exito";
            $this->mensaje_hacienda             =  (empty($this->mensaje_hacienda)) ? "Correcto" : $this->mensaje_hacienda;
        } else  if (strtoupper($registro) == strtoupper("ParcialmenteCorrecto")) {

            $estado_hacienda = " , estado_hacienda = 3 ";
            $this->mensaje_estado = '<span class="badge badge-success">Aceptado con Errores</span>';;
            $this->mensaje_estado_error_o_no    =    "exito";
        } else {
            $estado_hacienda = "  ";
            $this->mensaje_estado = 'envio ' . $envio . '  registro:' . $registro;
            $this->mensaje_estado_error_o_no    =    "exito";
        }


        $sql = "update  {$this->documento} 
                    set  
                    estado_verifactum_envio         = :estado_verifactum_envio                  ,
                    estado_verifactum_registro      = :estado_verifactum_registro          
                    $estado_hacienda     
                    
                    where  rowid = :rowid and entidad = :entidad   ";

        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':rowid', $this->id,   PDO::PARAM_INT);
        $dbh->bindValue(':estado_verifactum_envio', $envio,   PDO::PARAM_STR);
        $dbh->bindValue(':estado_verifactum_registro', $registro,   PDO::PARAM_STR);
        $dbh->bindValue(':entidad', $this->entidad,   PDO::PARAM_INT);



        $a = $dbh->execute();

        if (!$a) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        return $a;
    }



    public function factura_actualizar_xml()
    {


        $sql = "update  {$this->documento} 
                        set  
                        xml_IDVersion = :xml_IDVersion              ,
                        xml_huella    = :xml_huella                 ,
                        xml_huella_sha256   =:xml_huella_sha256     ,
                        xml_FechaHoraHusoGenRegistro    =   :xml_FechaHoraHusoGenRegistro   ,
                        xml_hacienda_enviado_fecha      =   NOW()                           ,
                        xml_hacienda_enviado            =  :xml_hacienda_enviado
                        where  rowid = :rowid and entidad = :entidad   ";
        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':rowid', $this->id,   PDO::PARAM_INT);
        $dbh->bindValue(':xml_IDVersion', $this->xml_IDVersion,   PDO::PARAM_STR);
        $dbh->bindValue(':xml_huella', $this->Huella,   PDO::PARAM_STR);
        $dbh->bindValue(':xml_huella_sha256', $this->Huella_sha256,   PDO::PARAM_STR);
        $dbh->bindValue(':xml_FechaHoraHusoGenRegistro', $this->FechaHoraHusoGenRegistro,   PDO::PARAM_STR);
        $dbh->bindValue(':xml_hacienda_enviado', 1,   PDO::PARAM_INT);

        $dbh->bindValue(':entidad', $this->entidad,   PDO::PARAM_INT);



        $a = $dbh->execute();

        if (!$a) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        return $a;
    }


    public function recupera_huella_de_factura($factura, $huella = NULL)
    {

        $sql = "
                        select  
                          respuesta_descripcion_registro_descripcion
                        from 
                          fi_europa_facturas_huellas 
                        
                          where fk_documento = $factura and entidad = :entidad   limit 0 ,1  ";

        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':entidad', $this->entidad,   PDO::PARAM_INT);

        $a = $dbh->execute();


        if (!$a) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }


        $row = $dbh->fetch(PDO::FETCH_ASSOC);



        return $row;
    }


    /// Esta funcion, se espera DIos mediante que sea capaz de darnos la ultima huella dela misma empres,a tipo de factura y Serie
    function recuperar_ultima_huella()
    {


        $sql = "select  
                     xml_huella_sha256             ,
                     xml_IDEmisorFactura           , 
                     referencia                    ,
                     fecha                         

                     from    
                         {$this->documento} 
                     where
                    entidad                 = :entidad
                and estado                  = 1
                and xml_hacienda_enviado    = 1 
                $where 
                order by xml_hacienda_enviado_fecha   DESC limit 1

                ";

        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':entidad', $this->entidad,   PDO::PARAM_INT);

        $a = $dbh->execute();


        if (!$a) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }


        $row = $dbh->fetch(PDO::FETCH_ASSOC);



        return $row;
    }


    public function verificarRespuestaFactura($facturaId)
    {
        // Consulta para verificar si la respuesta está vacía o nula
        $sql = "
        SELECT 
            respuesta, NumSerieFactura
        FROM 
            fi_europa_facturas_huellas 
        WHERE 
            fk_documento = :id 
            AND entidad = :entidad";

        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':id', $facturaId, PDO::PARAM_INT);
        $dbh->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $dbh->execute();

        $result = $dbh->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Retorna true si la respuesta no es nula ni vacía
            return !empty($result['respuesta']);
        } else {
            // Retorna false si no hay resultados o si la respuesta es nula o vacía
            return false;
        }
    }


    public function factura_pendiente()
    {


        $sql = "select rowid from  
            fi_europa_facturas
            where 
             estado               = 1
        AND     
             xml_hacienda_enviado = 0 order by rowid ASC ";

        $dbh   = $this->db->prepare($sql);

        $a = $dbh->execute();

        if (!$a) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();
        }


        $data = $dbh->fetch(PDO::FETCH_OBJ);
        $this->fetch($data->rowid);
    }



    ///-----------------------------------------------------------------------




    public function actualizar_valor($datos)
    {
        // recibe diferentes parametros
        // ACtualiza aquellos que ESTAN e ignora aquellos que no son enviados


        $sql = "update  fi_cotizaciones  
                                         set  
                                         rowid = rowid   ";

        if (isset($datos['total'])) {
            $sql .= " , total = " . $datos['total'] . "   ";
        }
        if (isset($datos['subtotal'])) {
            $sql .= " , subtotal = " . $datos['subtotal'] . "   ";
        }
        if (isset($datos['impuesto'])) {
            $sql .= " , impuesto = " . $datos['impuesto'] . "   ";
        }
        if (isset($datos['servicio_mesa'])) {
            $sql .= ", servicio_mesa = " . $datos['servicio_mesa'] . " ";
        }
        if (isset($datos['pagado'])) {
            $sql .= " , pagado = ( pagado + " . $datos['pagado'] . ")   ";
        }



        $sql .= "where rowid = :rowid ";

        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':rowid', $datos['fiche'], PDO::PARAM_INT);

        $dbh->execute();
        return 1;
    }

    function set_pagado($id = "")
    {

        $sql .= "update  fi_cotizaciones    set  
                                         estado_pagada = 1  where rowid = :rowid ";
        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':rowid', (!empty($id)) ? $id : $this->id, PDO::PARAM_INT);

        $dbh->execute();
    }



    function set_no_pagado($id = "")
    {

        $sql .= "update  fi_cotizaciones    set  
                                         estado_pagada = 0  where rowid = :rowid ";
        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':rowid', (!empty($id)) ? $id : $this->id, PDO::PARAM_INT);
        $dbh->execute();
    }



    function actualizar_dato_pagado($id = "", $monto)
    {

        $sql .= "update  fi_cotizaciones    set  
                                         pagado  = $monto  where rowid = :rowid ";
        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':rowid', (!empty($id)) ? $id : $this->id, PDO::PARAM_INT);
        $dbh->execute();
    }
    

 

    //// Verifactu QR
    public function QR( ){
      // URL del servicio
      $url = ENLACE_WEB_QR;
      // Datos a enviar en la solicitud POST
      $data = array(
          'param_nif' => $this->entidad_identificacion,
          'param_serie' => $this->referencia,
          'param_fecha' => date("d-m-Y", strtotime($this->fecha)),
          'param_importe' => $this->total,
      );
  
      $curl = curl_init();            
      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json'
        ),
      ));            
      $response = curl_exec($curl);
      $base_64_qr = '';
      // Manejo de errores
      if (curl_errno($curl)) {
          $base_64_qr = "Error CURL Numero " . curl_errno($curl);;
      } else {
          $base_64_qr = $response;
          $base_64_qr = preg_replace('/^.*(?=data:)/s', '', $base_64_qr);
      }


      // Cerrar la conexión cURL
      curl_close($curl);
        return  $html_string = '<img style=" width: 100%;max-width: 200px; height: auto;" src="' . $base_64_qr . '" alt="QR Code">' ;

    }



    public function verifactum_color(){
        return ($this->verifactum_produccion ==1)? "#2196F3" :"#FFFFFF" ;
    }

}
