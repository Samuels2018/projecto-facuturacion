<?php

require_once(ENLACE_SERVIDOR . "mod_entidad/object/Entidad.object.php");



class documento_mercantil extends  Seguridad
{



    /// Atributos del Documento GEneral 


    public $id;
    public $idEncriptado; // ID con MD5

    public $moneda;
    public $moneda_tipo_cambio;
    public $fk_usuario_crear;
    public $fk_usuario_validar;
    public $fecha;
    public $fecha_vencimiento;
    public $referencia;
    public $referencia_proveedor;
    public $fk_proyecto;
    public $proyecto_nombre;
    public $proyecto_referencia;
    public $fk_agente;
    public $forma_pago;
    public $detalle;
    public $fk_tercero;
    public $fk_tercero_txt;
    public $subtotal;
    public $impuesto_iva;
    public $impuesto_iva_equivalencia;
    public $impuesto_retencion_irpf;
    public $total;
    public $descuento_valor_final;
    public $asesor_comercial_txt;

    public $IVA_0;
    public $IVA_10;
    public $IVA_21;
    public $IVA_4;
    public $RE_5_2;
    public $RE_1_4;
    public $RE_0_5;
    public $RE_0_75;

    public $estado;
    public $pagado;
    public $estado_pagada;

    public $creado_fecha;
    public $creado_fk_usuario;

    public $borrado;
    public $borrado_fecha;
    public $borrado_fk_usuario;



    /// fin atributos Documento General 

    public $documento_configuracion;
    public $documento_serie;


    public  $siguiente_documento;
    public  $siguiente_borrador;
    public  $configuracion;


    public $estado_verifactum_envio;
    public $estado_verifactum_registro;
    public $estado_hacienda;




    public  $entidad;  //variable entidad  
    public $Entidad; // Objeto Entidad

    public $diccionario_pago;
    public $diccionario_impuesto_iva;
    public $diccionario_impuesto_iva_equivalencia;
    public $diccionario_moneda;

    public $fk_tercero_identificacion;
    public $fk_tercero_telefono;
    public $fk_tercero_email;
    public $fk_tercero_direccion;
    public $entidad_razonsocial;
    public $entidad_fantasia;
    public $entidad_identificacion;
    public $entidad_email;
    public $entidad_direccion;
    public $entidad_telefonofijo;
    public $forma_pago_txt;
    public $listado_url;
    public $ruta_detalle_contenido;

    public $nombre_clase;


    public $diccionario_estados;
    public $diccionario;

    public $fk_serie_configuracion;
    public $fk_plantilla;
    public $fk_serie_plantilla;


    public $db;

    // PRopia de este objeto 
    public $documento_txt;
    public $documento;
    public $documento_detalle;
    public $documento_configuracion_serie; // "Para el manejo de Series


    public $campos_minimos_borrador;

    public $movimiento_origen = '';
    public $movimiento_fk_origen = [];
    public $movimiento_destinos = null;

    public function __construct($db, $entidad = 1)
    {
        parent::__construct($db, $entidad);  // Esto inicializa la clase SEGURIDAD

        $this->entidad          = $entidad;
        $this->db          = $db;



        $this->Entidad = new Entidad($db, $entidad);
        $this->configuracion_empresa();

        $this->moneda_simbolo   = "€";
    }



    /******************************************************************
     * 
     *      Funciones Depuradas
     * 
     *********************************************************************/

    public function cargar_configuracion_documento($entidad, $fk_serie = NULL)
    {
        /* $sql = "SELECT count(rowid) as total  FROM  {$this->documento}  WHERE entidad = $entidad ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->siguiente_borrador   = $result['total']+1;*/

        $sql = "SELECT MAX(CAST(REGEXP_SUBSTR(referencia, '[0-9]+$') AS UNSIGNED)) AS max_borrador 
        FROM {$this->documento} 
        WHERE entidad = :entidad AND referencia LIKE 'Borrador %'";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':entidad', $entidad, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $ultimo_borrador = $result['max_borrador'] ?? 0; // Si no hay registros, inicia en 0
        $this->siguiente_borrador = $ultimo_borrador + 1;
    }



    //-----------------------------------------------------------
    // 
    //     Carga toda la configuracion de la tabla fi_configuracion (donde tienes campo y valor)
    //     Viene desde Entidad
    //
    //-------------------------------------------------------------
    public function configuracion_empresa()
    {


        if (!empty($this->diccionario)) {
            $sql = " SELECT  d.etiqueta AS etiqueta , class, rowid  from  " . DB_NAME_UTILIDADES_APOYO . ".{$this->diccionario} d  ";
            $stmt = $this->db->prepare($sql);
            $a = $stmt->execute();

            if (!$a) {
                $this->sql     =   $sql;
                $this->error   =   implode(", ", $stmt->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
                $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
                $this->Error_SQL();
            }


            while ($estados = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $this->diccionario_estados[$estados['rowid']]['etiqueta'] = $estados['etiqueta'];
                $this->diccionario_estados[$estados['rowid']]['class'] = $estados['class'];
            }
        }


        return $this->configuracion = $this->Entidad->configuracion_empresa();
    }



    /*****************************************************
     * 
     * 
     * 
    
     * 
     * 
     ***********************************************************/

    function monedas()
    {
        $sql = "SELECT * FROM diccionario_monedas m WHERE entidad = :entidad ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $stmt->execute();
        while ($data =  $stmt->fetch(PDO::FETCH_OBJ)) {
            $this->diccionario_moneda[$data->rowid]['etiqueta'] = $data->etiqueta;
            $this->diccionario_moneda[$data->rowid]['simbolo']  = $data->simbolo;
            $this->diccionario_moneda[$data->rowid]['codigo']   = $data->codigo;
        }


        return $this->diccionario_moneda;
    }





    function diccionario_pago()
    {
        $sql = "SELECT * FROM diccionario_formas_pago p WHERE entidad = $this->entidad AND activo = 1 AND borrado = 0 ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        while ($data =  $stmt->fetch(PDO::FETCH_OBJ)) {
            $this->diccionario_pago[$data->rowid]['label'] = $data->label;
        }
        return $this->diccionario_pago;
    }


    function diccionario_impuesto_iva()
    {
        $sql = "SELECT * FROM diccionario_impuestos  m WHERE entidad = :entidad and activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $stmt->execute();
        while ($data =  $stmt->fetch(PDO::FETCH_OBJ)) {
            $this->diccionario_impuesto_iva[$data->rowid]['impuesto_texto']         = $data->impuesto_texto;
            $this->diccionario_impuesto_iva[$data->rowid]['impuesto']               = number_format($data->impuesto, 2, '.', '');
            $this->diccionario_impuesto_iva[$data->rowid]['recargo_equivalencia']   = $data->recargo_equivalencia;
            $this->diccionario_impuesto_iva_equivalencia[number_format($data->impuesto, 2, '.', '')] = $data->recargo_equivalencia;
        }


        return $this->diccionario_impuesto_iva;
    }


















    /**********************************************************
     * 
     * 
     *  Interacciones con la tablas
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * ************************************************************/





    /*************************************************
     * 
     * 
     *      Nuevo Crear en Function 
     *
     *  
     ****************************************************/
    public function Crear($usuario)
    {
        try {
            $query_tercero = "SELECT rowid FROM fi_terceros 
            WHERE activo= 1 
            AND rowid = ( SELECT valor FROM fi_configuracion WHERE entidad = :entidad AND configuracion = 'cliente_defecto' ) ";
            $stmtTercero = $this->db->prepare($query_tercero);
            $stmtTercero->bindParam(':entidad', $this->entidad, PDO::PARAM_INT);
            $stmtTercero->execute();
            $rowTercero = $stmtTercero->fetch(PDO::FETCH_ASSOC);
            $codigo_tercero_default = 0;
            if ($rowTercero) {
                $codigo_tercero_default = $rowTercero['rowid'];
            }

            if (!(empty($this->fk_agente) || $this->fk_agente == '0')) {
                $query_agente = "SELECT rowid, nombre FROM fi_agentes
                WHERE borrado = 0
                AND entidad = :entidad AND rowid = :asesorid ";
                $stmtAgente = $this->db->prepare($query_agente);
                $stmtAgente->bindParam(':entidad', $this->entidad, PDO::PARAM_INT);
                $stmtAgente->bindParam(':asesorid', $this->fk_agente, PDO::PARAM_INT);
                $stmtAgente->execute();
                $rowAgente = $stmtAgente->fetch(PDO::FETCH_ASSOC);
                $codigo_agente_default = 0;
                if ($rowAgente) {
                    $codigo_agente_default = $rowAgente['rowid'];
                    $this->asesor_comercial_txt = $rowAgente['nombre'];
                }
            }

            $provincia_empresa = $this->Entidad->direccion_fk_provincia;
            require_once ENLACE_SERVIDOR . 'mod_utilidad/object/utilidades.object.php';
            $Utilidades = new Utilidades($this->db);
            $ubigeo_seleccionado = $Utilidades->obtener_ubigeo_seleccionado($provincia_empresa);
            $direccion_empresa = $this->Entidad->nombre_direccion . ', ' . $ubigeo_seleccionado[0]->nombre_provincia . ', ' . $ubigeo_seleccionado[0]->nombre_comunidad_autonoma . ', ' . $ubigeo_seleccionado[0]->nombre_pais . ', ' . $this->Entidad->codigo_postal;

            // (SELECT CONCAT(IFNULL(pais,''), '-', IFNULL(provincia,''), '-', IFNULL(codigo_postal,''), '-', IFNULL(direccion,'')) FROM fi_terceros WHERE rowid = :fk_tercero),
            $sql = "INSERT INTO {$this->documento} 
                        (entidad, referencia, referencia_serie , fk_usuario_crear, fecha, fecha_vencimiento, fk_tercero, tipo, forma_pago, detalle, estado, creado_fecha,moneda , fk_proyecto, asesor_comercial_txt, fk_agente,
                        fk_tercero_identificacion, 
                        fk_tercero_txt, 
                        fk_tercero_telefono, 
                        fk_tercero_email, 
                        fk_tercero_direccion,

                        entidad_razonsocial,
                        entidad_fantasia,
                        entidad_identificacion,
                        entidad_email,
                        entidad_direccion,
                        forma_pago_txt,
                        entidad_telefonofijo ) 
                    VALUES 
                        (:entidad, :referencia, :referencia_serie,  :fk_usuario_crear, :fecha, :fecha_vencimiento, :fk_tercero, :tipo, :forma_pago, :detalle, :estado, NOW(),:moneda , :fk_proyecto, :asesor_comercial_txt, :fk_agente,
                        (SELECT cedula FROM fi_terceros WHERE rowid = :fk_tercero),
                        (SELECT (CASE tipo WHEN 'fisica' THEN CONCAT(nombre, ' ',apellidos) ELSE nombre  END) AS nombre FROM fi_terceros WHERE rowid = :fk_tercero),
                        (SELECT telefono FROM fi_terceros WHERE rowid = :fk_tercero),
                        (SELECT email FROM fi_terceros WHERE rowid = :fk_tercero),
                        
                        (SELECT CONCAT(IFNULL(pais.nombre,''), '-', IFNULL(ccaa.nombre,''), '-', IFNULL(prov.provincia,''), '-', IFNULL(t.codigo_postal,''), '-', IFNULL(t.direccion,'')) 
                            FROM fi_terceros t
                            LEFT JOIN " . $_ENV['DB_NAME_UTILIDADES_APOYO'] . ".diccionario_paises pais ON pais.rowid = t.fk_pais
                            LEFT JOIN " . $_ENV['DB_NAME_UTILIDADES_APOYO'] . ".diccionario_comunidades_autonomas_provincias prov ON prov.id = t.direccion_fk_provincia
                            LEFT JOIN " . $_ENV['DB_NAME_UTILIDADES_APOYO'] . ".diccionario_comunidades_autonomas ccaa ON ccaa.id = t.fk_poblacion WHERE t.rowid = :fk_tercero),
                        :entidad_razon_social,
                        :entidad_fantasia,
                        :entidad_identificacion,
                        :entidad_email,

                        :entidad_direccion,
                        (SELECT label FROM diccionario_formas_pago WHERE rowid= :forma_pago),
                        :entidad_telefonofijo
                        )";

            $dbh = $this->db->prepare($sql);
            $dbh->bindValue(':entidad',            $this->entidad,                                                                  PDO::PARAM_STR);
            $dbh->bindValue(':referencia',          'Borrador ' . $this->siguiente_borrador,                           PDO::PARAM_STR);
            $dbh->bindValue(':referencia_serie',    $this->referencia_serie,                                        PDO::PARAM_STR);

            $dbh->bindValue(':fecha', (empty($this->fecha)) ? date('Y-m-d') : $this->fecha,                           PDO::PARAM_STR);
            $dbh->bindValue(':fecha_vencimiento', (empty($this->fecha_vencimiento)) ? date('Y-m-d') : $this->fecha_vencimiento,   PDO::PARAM_STR);
            $dbh->bindValue(':fk_tercero', (empty($this->fk_tercero) || $this->fk_tercero == '0') ? NULL : $this->fk_tercero,                             PDO::PARAM_STR);
            $dbh->bindValue(':asesor_comercial_txt', $this->asesor_comercial_txt,                             PDO::PARAM_STR);
            $dbh->bindValue(':fk_agente', (empty($this->fk_agente) || $this->fk_agente == '0') ? $codigo_agente_default : $this->fk_agente,                             PDO::PARAM_INT);

            $dbh->bindValue(':tipo', 'F' . $this->siguiente_documento['simplificada']['referencia_serie'],                                   PDO::PARAM_STR);
            $dbh->bindValue(':forma_pago', (empty($this->forma_pago)) ? 6 : $this->forma_pago,                             PDO::PARAM_INT);
            $dbh->bindValue(':detalle', (empty($this->detalle)) ? ' ' : $this->detalle,                                 PDO::PARAM_STR);
            $dbh->bindValue(':estado',              0,                                                                              PDO::PARAM_INT);
            $dbh->bindValue(':fk_usuario_crear',    $usuario,                                                                       PDO::PARAM_INT);
            $dbh->bindValue(':moneda',              $this->moneda,                                                                  PDO::PARAM_INT);
            $dbh->bindValue(':fk_proyecto',         $this->fk_proyecto,                                                             PDO::PARAM_INT);

            $dbh->bindValue(':entidad_razon_social', $this->Entidad->nombre_empresa,                                 PDO::PARAM_STR);
            $dbh->bindValue(':entidad_fantasia', $this->Entidad->nombre_fantasia,                                 PDO::PARAM_STR);
            $dbh->bindValue(':entidad_identificacion', $this->Entidad->numero_identificacion,                                 PDO::PARAM_STR);
            $dbh->bindValue(':entidad_email', $this->Entidad->correo_electronico,                                 PDO::PARAM_STR);
            $dbh->bindValue(':entidad_telefonofijo', $this->Entidad->telefono_fijo,                                 PDO::PARAM_STR);

            $dbh->bindValue(':entidad_direccion', $direccion_empresa,                                 PDO::PARAM_STR);

            $a = $dbh->execute();


            if (!$a) {
                $this->sql     =   $sql;
                $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
                $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
                $this->Error_SQL();
            }




            $id = $this->db->lastInsertId();
            $this->id = $id;


            return $id;
        } catch (Exception $exc) {
            $this->sql     =  $exc->getMessage();
            $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();
            return "";
        }
    }

    public function actualizar_valor_campo($campo, $valor, $documentoId)
    {
        try {
            // Verificar que el campo es válido y pertenece al esquema
            $campos_permitidos = ['detalle', 'notageneral']; // Agrega más campos permitidos si los necesitas
            if (!in_array($campo, $campos_permitidos)) {
                throw new Exception("El campo '{$campo}' no es permitido.");
            }

            // Preparar la consulta para la actualización
            $sql = "UPDATE {$this->documento} 
                    SET {$campo} = :valor 
                    WHERE rowid = :documentoId AND entidad = :entidad";

            $dbh = $this->db->prepare($sql);
            $dbh->bindValue(':valor', $valor, PDO::PARAM_STR);
            $dbh->bindValue(':documentoId', $documentoId, PDO::PARAM_INT);
            $dbh->bindValue(':entidad', $this->entidad, PDO::PARAM_STR);

            // Ejecutar la consulta
            $ejecucion = $dbh->execute();
            $filas_afectadas = $dbh->rowCount();

            // Verificar si se actualizó algún registro
            if ($filas_afectadas === 0) {
                throw new Exception("No se encontró el documento o no se realizaron cambios.");
            }

            return [
                "success" => true,
                "message" => "El documento fue actualizado correctamente.",
                "rows_affected" => $filas_afectadas
            ];
        } catch (PDOException $e) {
            return [
                "success" => false,
                "message" => "Error al ejecutar la consulta: " . $e->getMessage()
            ];
        } catch (Exception $e) {
            return [
                "success" => false,
                "message" => $e->getMessage()
            ];
        }
    }


    /**************************************************************************************
     * 
     * 
     *      Logica del Detalle 
     * 
     * 
     *************************************************************************************/

    public function crear_detalle()
    {
        $debug = false;

        $this->diccionario_impuesto_iva(); /// Lo necesita para el recalculo de impuestos de RE 

        $sql = "INSERT INTO {$this->documento_detalle}  (entidad, fk_documento, fk_producto 
        , label             , precio_original      
        , descuento_tipo    , descuento_aplicado,   descuento_valor_final
        , precio_unitario
        , cantidad  ,subtotal_pre_retencion , subtotal 
        , impuesto_iva_monto , impuesto_iva_porcentaje 
        , impuesto_iva_equivalencia_aplica   , impuesto_iva_equivalencia_monto      , impuesto_iva_equivalencia_porcentaje   
        , impuesto_retencion_aplica          , impuesto_retencion_monto             , impuesto_retencion_porcentaje
                 , total     ) 
                    VALUES 
        (:entidad   , :fk_documento, :fk_producto
        ,:label     , :precio_original  
        , :descuento_tipo    , :descuento_aplicado,   :descuento_valor_final
        , :precio_unitario 
        , :cantidad  , :subtotal_pre_retencion,  :subtotal   
        , :impuesto_iva_monto , :impuesto_iva_porcentaje 
        , :impuesto_iva_equivalencia_aplica   , :impuesto_iva_equivalencia_monto    , :impuesto_iva_equivalencia_porcentaje   
        , :impuesto_retencion_aplica          , :impuesto_retencion_monto           , :impuesto_retencion_porcentaje
         , :total )";


        $dbh = $this->db->prepare($sql);



        $descuento_aplicado = (empty($this->descuento)) ? 0 : $this->descuento;
        $descuento_tipo     = $this->descuento_tipo;
        $precio_original    = $this->precio_unitario;
        $montoDescuento     = 0;


        $impuesto_iva_equivalencia_aplica       = 0;
        $impuesto_iva_equivalencia_monto        = 0;
        $impuesto_iva_equivalencia_porcentaje   = 0;

        $impuesto_retencion_aplica              = 0;
        $impuesto_retencion_monto               = 0;
        $impuesto_retencion_porcentaje          = 0;

        // Inicio  del Calculo del Descuento     
        $this->subtotal_pre_retencion    =   $this->precio_unitario * $this->cantidad;

        if ($debug) {
            echo "<tr><Td>";
            echo "<br>Pu:" .  $this->precio_unitario;
            echo "<br>CAntidad:" .  $this->cantidad;
            echo "<br>Pu:" .  $this->precio_unitario;
            echo "</td>";
            echo "</tr>";
        }


        if ($this->descuento > 0) {


            if ($descuento_tipo == "porcentual") {


                if ($this->descuento > 100) {
                    //$error_txt = "EL monto a descuentar no puede ser mayor al 100% de Subtotal de la linea";
                    $this->descuento = 100;
                }

                $descuento_valor_final        = ($this->subtotal_pre_retencion  * ($this->descuento / 100));
            } else if ($descuento_tipo == "absoluto") {

                if ($this->descuento > ($this->precio_unitario * $this->cantidad)) {
                    //$error_txt = "EL monto a descuentar no puede ser mayor al monto Subtotal de la linea";
                    $this->descuento = $this->precio_unitario * $this->cantidad;
                }


                $descuento_valor_final =  $this->descuento;
            }
        } else {
            $descuento_tipo = NULL;
        } // fin del Descuento 

        $this->subtotal_pre_retencion = $this->subtotal_pre_retencion - $descuento_valor_final;

        // Fin del Calculo del Descuento     

        if ($this->tipo_impuesto > 0) {
            $impuesto_iva_monto = ($this->subtotal_pre_retencion)  * (($this->tipo_impuesto / 100));
        } else {
            $impuesto_iva_monto = 0;
        }

        if ($debug) {
            echo "<tr><Td>";
            echo "Juan Carlos aqui el calculo <br>Descuento Por linea " .  $descuento_valor_final;
            echo "<br>Impuesto total IVA Por linea" .  $impuesto_iva_monto;
            echo "</td>";
            echo "</tr>";
        }

        if ($this->recargo_equivalencia == 1) {
            $impuesto_iva_equivalencia_aplica       = 1;
            $impuesto_iva_equivalencia_monto        = ($this->subtotal_pre_retencion) * (($this->diccionario_impuesto_iva_equivalencia[$this->tipo_impuesto] / 100));
            $impuesto_iva_equivalencia_porcentaje   = $this->diccionario_impuesto_iva_equivalencia[$this->tipo_impuesto];
        }


        if ($this->retencion == 1) {
            $impuesto_retencion_aplica       = 1;
            $impuesto_retencion_monto        = ($this->subtotal_pre_retencion) * (($this->Entidad->retencion_porcentaje  / 100));
            $impuesto_retencion_porcentaje   = $this->Entidad->retencion_porcentaje;
        }




        $subtotal   =   $this->subtotal_pre_retencion - $impuesto_retencion_monto;
        $total      =   $subtotal + $impuesto_iva_monto + $impuesto_iva_equivalencia_monto;

        $dbh->bindValue(':entidad',         $this->entidad,   PDO::PARAM_STR);
        $dbh->bindValue(':fk_documento',      $this->id,   PDO::PARAM_INT);
        $dbh->bindValue(':fk_producto',     $this->fk_producto,   PDO::PARAM_STR);
        $dbh->bindValue(':label',           $this->label,   PDO::PARAM_STR);
        $dbh->bindValue(':precio_original', $precio_original);
        $dbh->bindValue(':precio_unitario', $this->precio_unitario);
        $dbh->bindValue(':cantidad',        $this->cantidad);


        $dbh->bindValue(':descuento_tipo',              $descuento_tipo);
        $dbh->bindValue(':descuento_aplicado',          $descuento_aplicado);
        $dbh->bindValue(':descuento_valor_final',       $descuento_valor_final,   PDO::PARAM_STR);


        $dbh->bindValue(':subtotal_pre_retencion',       $this->subtotal_pre_retencion);
        $dbh->bindValue(':impuesto_iva_monto',       $impuesto_iva_monto);
        $dbh->bindValue(':impuesto_iva_porcentaje',       $this->tipo_impuesto);


        $dbh->bindValue(':impuesto_iva_equivalencia_aplica',       $impuesto_iva_equivalencia_aplica);
        $dbh->bindValue(':impuesto_iva_equivalencia_monto',       $impuesto_iva_equivalencia_monto);
        $dbh->bindValue(':impuesto_iva_equivalencia_porcentaje',       $impuesto_iva_equivalencia_porcentaje);



        $dbh->bindValue(':impuesto_retencion_aplica',       $impuesto_retencion_aplica);
        $dbh->bindValue(':impuesto_retencion_monto',       $impuesto_retencion_monto);
        $dbh->bindValue(':impuesto_retencion_porcentaje',       $impuesto_retencion_porcentaje);



        $dbh->bindValue(':subtotal',        $subtotal);
        $dbh->bindValue(':total',           $total);

        $a = $dbh->execute();


        if (!$a) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();
        }


        $this->recalculo_documento();
    }

    public function actualizar_detalle($linea_viene_de_albaran=false)
    {
        $cantidad_limite = 0;
        $this->diccionario_impuesto_iva(); /// Lo necesita para el recalculo de impuestos de RE 

        if ($this->movimiento_origen != '' && $linea_viene_de_albaran) {
            $cantidad_limite = $this->obtener_cantidad_limite();

            /* El total saldo disponible menos la cantidad que se desea ingresar, debería ser MAYOR a CERO 
               Sino, no se puede actualizar el detalle*/
            if ((floatval($cantidad_limite) - floatval($this->cantidad)) < 0) {
                return false;
            }
        }

        $sql = "UPDATE {$this->documento_detalle} SET 
        fk_producto = :fk_producto,  label = :label, cantidad=:cantidad, subtotal =:subtotal, total =:total, 
        precio_original = :precio_original, precio_unitario=:precio_unitario, descuento_tipo = :descuento_tipo, descuento_aplicado =:descuento_aplicado, 
        descuento_valor_final = :descuento_valor_final, subtotal_pre_retencion=:subtotal_pre_retencion, impuesto_iva_monto=:impuesto_iva_monto,
        impuesto_iva_porcentaje=:impuesto_iva_porcentaje, impuesto_iva_equivalencia_aplica=:impuesto_iva_equivalencia_aplica, impuesto_iva_equivalencia_monto=:impuesto_iva_equivalencia_monto, impuesto_iva_equivalencia_porcentaje=:impuesto_iva_equivalencia_porcentaje, impuesto_retencion_aplica=:impuesto_retencion_aplica, impuesto_retencion_monto=:impuesto_retencion_monto, impuesto_retencion_porcentaje=:impuesto_retencion_porcentaje
        WHERE md5(rowid) = :rowid
        ";

        $dbh = $this->db->prepare($sql);

        $retorno_calculo_importes = $this->obtener_importes_detalle();

        $descuento_tipo = $retorno_calculo_importes["descuento_tipo"];
        $descuento_aplicado = $retorno_calculo_importes["descuento_aplicado"];
        $descuento_valor_final = $retorno_calculo_importes["descuento_valor_final"];
        $subtotal_pre_retencion = $retorno_calculo_importes["subtotal_pre_retencion"];
        $impuesto_iva_monto = $retorno_calculo_importes["impuesto_iva_monto"];
        $precio_unitario = $retorno_calculo_importes["precio_unitario"];
        $descuento = $retorno_calculo_importes["descuento"];
        $impuesto_iva_equivalencia_aplica = $retorno_calculo_importes["impuesto_iva_equivalencia_aplica"];
        $impuesto_iva_equivalencia_monto = $retorno_calculo_importes["impuesto_iva_equivalencia_monto"];
        $impuesto_iva_equivalencia_porcentaje = $retorno_calculo_importes["impuesto_iva_equivalencia_porcentaje"];
        $impuesto_retencion_aplica = $retorno_calculo_importes["impuesto_retencion_aplica"];
        $impuesto_retencion_monto = $retorno_calculo_importes["impuesto_retencion_monto"];
        $impuesto_retencion_porcentaje = $retorno_calculo_importes["impuesto_retencion_porcentaje"];
        $subtotal = $retorno_calculo_importes["subtotal"];
        $total = $retorno_calculo_importes["total"];

        $dbh->bindValue(':rowid',         $this->lineaMd5,   PDO::PARAM_STR);
        $dbh->bindValue(':fk_producto',     $this->fk_producto,   PDO::PARAM_STR);
        $dbh->bindValue(':label',           $this->label,   PDO::PARAM_STR);
        $dbh->bindValue(':precio_original', $precio_unitario);
        $dbh->bindValue(':precio_unitario', $this->precio_unitario, PDO::PARAM_STR);

        $dbh->bindValue(':cantidad',        $this->cantidad, PDO::PARAM_STR);

        // if($this->movimiento_origen != ''){
        //     $dbh->bindValue(':cantidad',        $cantidad_limite, PDO::PARAM_STR);
        // }else{
        //     $dbh->bindValue(':cantidad',        $this->cantidad, PDO::PARAM_STR);
        // }        


        $dbh->bindValue(':descuento_tipo',              $descuento_tipo);
        $dbh->bindValue(':descuento_aplicado',          $descuento_aplicado);
        $dbh->bindValue(':descuento_valor_final',       $descuento_valor_final,   PDO::PARAM_STR);


        $dbh->bindValue(':subtotal_pre_retencion',       $subtotal_pre_retencion, PDO::PARAM_STR);
        $dbh->bindValue(':impuesto_iva_monto',       $impuesto_iva_monto, PDO::PARAM_STR);
        $dbh->bindValue(':impuesto_iva_porcentaje',       $this->tipo_impuesto, PDO::PARAM_STR);


        $dbh->bindValue(':impuesto_iva_equivalencia_aplica',       $impuesto_iva_equivalencia_aplica);
        $dbh->bindValue(':impuesto_iva_equivalencia_monto',       $impuesto_iva_equivalencia_monto);
        $dbh->bindValue(':impuesto_iva_equivalencia_porcentaje',       $impuesto_iva_equivalencia_porcentaje);



        $dbh->bindValue(':impuesto_retencion_aplica',       $impuesto_retencion_aplica);
        $dbh->bindValue(':impuesto_retencion_monto',       $impuesto_retencion_monto, PDO::PARAM_STR);
        $dbh->bindValue(':impuesto_retencion_porcentaje',       $impuesto_retencion_porcentaje, PDO::PARAM_STR);

        $dbh->bindValue(':subtotal',        $subtotal);
        $dbh->bindValue(':total',           $total);

        $a = $dbh->execute();

        if (!$a) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();
        }


        $this->recalculo_documento();
        return true;
    }
    public function obtener_cantidad_limite()
    {
        $cantidad_total_acumulado = 0;
        $cantidad_total_origen = 0;

        // Obtentg la cantidad acumulada para ese producto en todas las compras relacionadas al albaran
        /*
        $queryTotalAcumulado = "SELECT IFNULL(SUM(det.cantidad),0) as cantidad_total
            FROM {$this->documento_detalle} det WHERE det.rowid IN (
                SELECT mov.destino_fk_documento_detalle 
                FROM fi_europa_documentos_movimientos_detalles mov 
                WHERE mov.origen_documento = '{$this->movimiento_origen}'
                AND IFNULL(mov.borrado,0) = 0
                AND mov.origen_fk_documento = {$this->movimiento_fk_origen}
            ) 
            AND det.fk_producto = {$this->fk_producto} AND md5(det.rowid) != '$this->lineaMd5'";
        */
        $queryTotalAcumulado = "SELECT IFNULL(SUM(det.cantidad),0) as cantidad_total
            FROM {$this->documento_detalle} det WHERE det.rowid IN (
                SELECT mov.destino_fk_documento_detalle 
                FROM fi_europa_documentos_movimientos_detalles mov 
                WHERE mov.destino_documento = '{$this->documento}'
                AND IFNULL(mov.borrado,0) = 0
                AND mov.destino_fk_documento = {$this->id}
            ) 
            AND det.fk_producto = {$this->fk_producto} AND md5(det.rowid) != '$this->lineaMd5'";

        $stmt = $this->db->prepare($queryTotalAcumulado);
// echo 'QUERY1: '.$queryTotalAcumulado;
        $a  =  $stmt->execute();

        if (!$a) {
            $this->sql     =   $queryTotalAcumulado;
            $this->error   =   implode(", ", $stmt->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();
        }
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $cantidad_total_acumulado = $row["cantidad_total"];

        // Obtengo la cantidad del albaran
        /*
        $queryTotalOrigen = "SELECT IFNULL(SUM(det.cantidad),0) as cantidad_total
            FROM {$this->movimiento_origen}_detalle det 
            WHERE det.fk_documento = {$this->movimiento_fk_origen}
            AND det.fk_producto = {$this->fk_producto}";
        */
        $queryTotalOrigen = "SELECT IFNULL(SUM(det.cantidad),0) as cantidad_total
            FROM {$this->movimiento_origen}_detalle det 
            WHERE det.fk_documento IN (
                SELECT mov.origen_fk_documento 
                FROM fi_europa_documentos_movimientos_detalles mov 
                WHERE mov.destino_documento = '{$this->documento}'
                AND IFNULL(mov.borrado,0) = 0
                AND mov.destino_fk_documento = {$this->id}
            ) 
            AND det.fk_producto = {$this->fk_producto}";
// echo 'QUERY2: '.$queryTotalOrigen;
        $dtmt1 = $this->db->prepare($queryTotalOrigen);
        $a  =  $dtmt1->execute();

        if (!$a) {
            $this->sql     =   $queryTotalOrigen;
            $this->error   =   implode(", ", $dtmt1->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();
        }
        $row1 = $dtmt1->fetch(PDO::FETCH_ASSOC);
        $cantidad_total_origen = $row1["cantidad_total"];

        // // Obtengo la cantidad de la línea de la compra actual antes de ingresar la nueva cantidad
        // $queryTotalActual = "SELECT IFNULL(det.cantidad,0) as cantidad_total
        //     FROM {$this->documento_detalle} det 
        //     WHERE md5(det.rowid) = '$this->lineaMd5'";

        // $stmt2 = $this->db->prepare($queryTotalActual);
        // $a  =  $stmt2->execute();

        // if (!$a) {
        //     $this->sql     =   $queryTotalActual;
        //     $this->error   =   implode(", ", $stmt2->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
        //     $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
        //     $this->Error_SQL();
        // }
        // $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        // $cantidad_total_actual = $row2["cantidad_total"];

        // echo 'Cantidad Origen: '. $cantidad_total_origen .'Cantidad Acumulado: '. $cantidad_total_acumulado;

        // return $cantidad_total_origen - $cantidad_total_acumulado - $cantidad_total_actual;
        return $cantidad_total_origen - $cantidad_total_acumulado;
    }


    public function  eliminar_linea($linea_md5)
    {

        $sql = "DELETE FROM {$this->documento_detalle} WHERE md5(rowid) = :rowid and entidad = :fk_entidad  LIMIT 1 ";
        $db = $this->db->prepare($sql);
        $db->bindValue(':rowid', $linea_md5, PDO::PARAM_STR);
        $db->bindValue(':fk_entidad', $this->entidad, PDO::PARAM_INT);
        $a = $db->execute();


        if (!$a) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $db->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        $this->recalculo_documento();

        return $a;
    }

    public function obtener_importes_detalle()
    {
        $impuesto_iva_equivalencia_aplica       = 0;
        $impuesto_iva_equivalencia_monto        = 0;
        $impuesto_iva_equivalencia_porcentaje   = 0;
        $impuesto_retencion_aplica              = 0;
        $impuesto_retencion_monto               = 0;
        $impuesto_retencion_porcentaje          = 0;

        $descuento        = floatval($this->descuento);
        $precio_unitario        = floatval($this->precio_unitario);
        $cantidad               = floatval($this->cantidad);
        $descuento_aplicado     = (empty($this->descuento)) ? 0 : $this->descuento;
        $descuento_tipo         = $this->descuento_tipo;
        $subtotal_pre_retencion = $this->subtotal_pre_retencion;
        $tipo_impuesto          = $this->tipo_impuesto;
        $recargo_equivalencia   = $this->recargo_equivalencia;
        $retencion              = $this->retencion;
        $diccionario_impuesto   = $this->diccionario_impuesto_iva_equivalencia[$tipo_impuesto];
        $montoDescuento     = 0;

        // Inicio  del Calculo del Descuento     
        $subtotal_pre_retencion    =   $precio_unitario * $cantidad;

        if ($descuento > 0) {
            if ($descuento_tipo == "porcentual") {
                if ($descuento > 100) {
                    $descuento = 100;
                }
                $descuento_valor_final        = ($subtotal_pre_retencion  * ($descuento / 100));
            } else if ($descuento_tipo == "absoluto") {
                if ($descuento > ($precio_unitario * $cantidad)) {
                    //$error_txt = "EL monto a descuentar no puede ser mayor al monto Subtotal de la linea";
                    $descuento = $precio_unitario * $cantidad;
                }
                $descuento_valor_final =  $descuento;
            }
        } else {
            $descuento_tipo = NULL;
        } // fin del Descuento 
        $subtotal_pre_retencion = $subtotal_pre_retencion - $descuento_valor_final;

        if ($tipo_impuesto > 0) {
            $impuesto_iva_monto = ($subtotal_pre_retencion)  * (($tipo_impuesto / 100));
        } else {
            $impuesto_iva_monto = 0;
        }

        if ($recargo_equivalencia == 1) {
            $impuesto_iva_equivalencia_aplica       = 1;
            $impuesto_iva_equivalencia_monto        = ($subtotal_pre_retencion) * (($diccionario_impuesto / 100));
            $impuesto_iva_equivalencia_porcentaje   = $diccionario_impuesto;
        }
        if ($retencion == 1) {
            $impuesto_retencion_aplica       = 1;
            $impuesto_retencion_monto        = ($subtotal_pre_retencion) * (($this->Entidad->retencion_porcentaje  / 100));
            $impuesto_retencion_porcentaje   = $this->Entidad->retencion_porcentaje;
        }
        $subtotal   =   $subtotal_pre_retencion - $impuesto_retencion_monto;
        $total      =   $subtotal + $impuesto_iva_monto + $impuesto_iva_equivalencia_monto;

        return array(
            'descuento_tipo'                        => $descuento_tipo,
            'descuento_aplicado'                    => $descuento_aplicado,
            'descuento_valor_final'                 => $descuento_valor_final,
            'subtotal_pre_retencion'                => $subtotal_pre_retencion,
            'precio_unitario'                       => $precio_unitario,
            'descuento'                             => $descuento,

            'impuesto_iva_monto'                    => $impuesto_iva_monto,
            'impuesto_iva_equivalencia_aplica'      => $impuesto_iva_equivalencia_aplica,
            'impuesto_iva_equivalencia_monto'       => $impuesto_iva_equivalencia_monto,
            'impuesto_iva_equivalencia_porcentaje'  => $impuesto_iva_equivalencia_porcentaje,
            'impuesto_retencion_aplica'             => $impuesto_retencion_aplica,
            'impuesto_retencion_monto'              => $impuesto_retencion_monto,
            'impuesto_retencion_porcentaje'         => $impuesto_retencion_porcentaje,
            'subtotal'                              => $subtotal,
            'total'                                 => $total
        );
    }



    /**************************************************************************************
     * 
     * 
     *      Logica del Recalculo Total  
     * 
     * 
     *************************************************************************************/

    public function recalculo_documento()
    {



        $sql = "select     
                subtotal_pre_retencion          ,                
                impuesto_iva_monto              , 
                impuesto_iva_porcentaje         ,
                impuesto_iva_equivalencia_monto ,
                impuesto_iva_equivalencia_porcentaje,
                impuesto_retencion_monto        , 
                impuesto_retencion_porcentaje   ,
                descuento_valor_final,
                total 
                from {$this->documento_detalle}
                where fk_documento  =   :fk_documento
                and entidad       =   :entidad ";


        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':entidad',         $this->entidad,   PDO::PARAM_STR);
        $dbh->bindValue(':fk_documento',      $this->id,   PDO::PARAM_INT);
        $a = $dbh->execute();

        if (!$a) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();
        }

        $IVA        = array();
        $RE         = array();
        $impuesto_iva           = 0;
        $impuesto_iva_equivalencia = 0;
        $subtotal_pre_retencion = 0;
        $descuento_valor_final = 0;
        $retencion              = 0;
        $total                  = 0;




        while ($row = $dbh->fetch(PDO::FETCH_OBJ)) {
            $IVA[(int)$row->impuesto_iva_porcentaje]                += $row->impuesto_iva_monto;
            $RE[number_format($row->impuesto_iva_equivalencia_porcentaje, 2)]        += $row->impuesto_iva_equivalencia_monto;

            $impuesto_iva_equivalencia += $row->impuesto_iva_equivalencia_monto;
            $impuesto_iva   += $row->impuesto_iva_monto;
            $retencion      += $row->impuesto_retencion_monto;
            $subtotal_pre_retencion += $row->subtotal_pre_retencion;
            $descuento_valor_final += $row->descuento_valor_final;
            $total += $row->total;
        }

        /*
                        echo "<tr><Td>";
                         var_dump($RE);
                        echo "</td></Tr>";
                        */


        $sql = "update 
                            {$this->documento} 
                            set
                            total                       = 0{$total}                   ,
                            subtotal_pre_retencion      = 0{$subtotal_pre_retencion}  ,
                            impuesto_iva                = 0{$impuesto_iva}            ,
                            impuesto_retencion_irpf     = 0{$retencion}               ,
                            impuesto_iva_equivalencia   = 0{$impuesto_iva_equivalencia},

                            IVA_21                      = 0{$IVA[21]}                 ,
                            IVA_10                      = 0{$IVA[10]}                 ,
                            IVA_4                       = 0{$IVA[4]}                  ,
                            IVA_0                       = 0{$IVA[0]}                  ,

                            RE_5_2                       = 0{$RE["5.20"]}              ,
                            RE_1_4                       = 0{$RE["1.40"]}              ,
                            RE_0_5                       = 0{$RE["0.50"]}              ,
                            RE_0_75                      = 0{$RE["0"]}                 ,

                            TotalDescuentos              = 0{$descuento_valor_final}              

                            where rowid = :fk_documento
                            and entidad = :entidad 
                            ";

        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':entidad',         $this->entidad,   PDO::PARAM_STR);
        $dbh->bindValue(':fk_documento',      $this->id,   PDO::PARAM_INT);
        $a = $dbh->execute();


        if (!$a) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();

            echo "<Tr><Td colspan='5'>";
            echo $this->error;
            echo "<td></tr>";
        }
    }

    public function actualiza_documento_ligado($cambia_estado_origen = false)
    {
        $lineaMd5 = $this->lineaMd5; //Viene de VER_XXX_ITEMS_AJAX.PHP

        if ($cambia_estado_origen) {
            // Cambia a estado parcial porque viene de una Eliminación de la Línea
            // $sqlUpdateOrigen = "UPDATE {$this->movimiento_origen}
            //                 SET estado = 4
            //                 WHERE rowid = '{$this->movimiento_fk_origen}'  ";

            /*
                $sqlUpdateOrigen = "UPDATE {$this->movimiento_origen} 
                SET estado = (
                            CASE WHEN (
                                SELECT COUNT(1)>0 AS conteo FROM fi_europa_documentos_movimientos_detalles movdet
                                WHERE movdet.destino_documento = '{$this->documento}'
                                AND movdet.destino_fk_documento = {$this->id}
                                AND movdet.borrado = 0
                                AND md5(movdet.destino_fk_documento_detalle) != '{$lineaMd5}'

                                AND movdet.origen_documento = (
                                    SELECT fk_documento FROM fi_europa_compras_detalle WHERE md5(rowid) = '{$lineaMd5}'
                                )

                            ) > 0 THEN 4
                                ELSE 1
                            END )
                WHERE rowid = '{$this->movimiento_fk_origen}'  ";
            */

            $movimiento_fk_origen_ids = implode(",", $this->movimiento_fk_origen);
            $sqlUpdateOrigen = "UPDATE {$this->movimiento_origen}
                        SET estado = (
                            CASE WHEN (
                                SELECT COUNT(1)>0 AS conteo FROM fi_europa_documentos_movimientos_detalles movdet
                                WHERE movdet.destino_documento = '{$this->documento}'
                                AND movdet.destino_fk_documento = {$this->id}
                                AND movdet.borrado = 0
                                AND md5(movdet.destino_fk_documento_detalle) != '{$lineaMd5}'

                                AND movdet.origen_fk_documento = (
                                    SELECT DISTINCT movdet.origen_fk_documento
                                    FROM fi_europa_documentos_movimientos_detalles movdet
                                    WHERE movdet.origen_fk_documento_detalle IN (
                                        SELECT compdet.origen_fk_documento_detalle
                                        FROM {$this->documento_detalle} compdet
                                        WHERE md5(compdet.rowid) = '{$lineaMd5}'
                                    )
                                )
                            ) > 0 THEN 4
                                ELSE 1
                            END
                        )
            WHERE rowid IN (
                SELECT fk_documento FROM {$this->movimiento_origen}_detalle
                WHERE rowid IN (
                    SELECT origen_fk_documento_detalle FROM {$this->documento_detalle} 
                    WHERE md5(rowid) = '{$lineaMd5}'
                )            
            );  ";

            $dbhUpdateOrigen = $this->db->prepare($sqlUpdateOrigen);
            $resultUpdateOrigen = $dbhUpdateOrigen->execute();

            if (!$resultUpdateOrigen) {
                $this->sql     =   $sqlUpdateOrigen;
                $this->error   =   implode(", ", $dbhUpdateOrigen->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
                $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
                $this->Error_SQL();
            }

            // Se elimina el registro de la línea en los Movimientos
            /*
                $sqlDeleteMovimiento = "UPDATE fi_europa_documentos_movimientos_detalles
                SET borrado = 1, borrado_fecha = NOW(), borrado_fk_usuario = {$this->usuario}
                WHERE destino_documento = '{$this->documento}' AND destino_fk_documento = {$this->id} AND md5(destino_fk_documento_detalle) = '{$lineaMd5}';

                UPDATE fi_europa_documentos_movimientos
                SET borrado_fecha = NOW(), borrado_fk_usuario = {$this->usuario}, borrado = (
                    SELECT NOT COUNT(1)>0 AS conteo FROM fi_europa_documentos_movimientos_detalles movdet
                    WHERE movdet.destino_documento = '{$this->documento}'
                    AND movdet.destino_fk_documento = {$this->id}
                    AND movdet.borrado = 0
                    AND md5(movdet.destino_fk_documento_detalle) != '{$lineaMd5}'
                )
                WHERE destino_documento = '{$this->documento}' AND destino_fk_documento = {$this->id};
            */
            $sqlDeleteMovimiento = "UPDATE fi_europa_documentos_movimientos_detalles
            SET borrado = 1, borrado_fecha = NOW(), borrado_fk_usuario = {$this->usuario}
            WHERE destino_documento = '{$this->documento}' AND destino_fk_documento = {$this->id} AND md5(destino_fk_documento_detalle) = '{$lineaMd5}';

            UPDATE fi_europa_documentos_movimientos
            SET borrado = 1
            WHERE (
                    SELECT COUNT(1) FROM fi_europa_documentos_movimientos_detalles
                    WHERE origen_fk_documento =
                        (
                            SELECT origen_fk_documento FROM fi_europa_documentos_movimientos_detalles
                            WHERE destino_documento = '{$this->documento}' AND destino_fk_documento = {$this->id} AND md5(destino_fk_documento_detalle) = '{$lineaMd5}'
                            LIMIT 1
                        )
                    AND borrado = 0
                ) = 0
                AND origen_fk_documento = 
                (
                    SELECT origen_fk_documento FROM fi_europa_documentos_movimientos_detalles
                    WHERE destino_documento = '{$this->documento}' AND destino_fk_documento = {$this->id} AND md5(destino_fk_documento_detalle) = '{$lineaMd5}'
                    LIMIT 1
                );
            ";

            $dbhDeleteMovimiento = $this->db->prepare($sqlDeleteMovimiento);
            $resultDeleteMovimiento = $dbhDeleteMovimiento->execute();

            if (!$resultDeleteMovimiento) {
                $this->sql     =   $sqlDeleteMovimiento;
                $this->error   =   implode(", ", $dbhDeleteMovimiento->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
                $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
                $this->Error_SQL();
            }

            return array('estado_detalle' => 4, 'cantidad_inicial' => 0);
        }

        // Continúa para los casos que no sean Eliminación de la línea
        
        $sql = "SELECT detalle.cantidad as detalle_origen_cantidad, movimiento_detalle.*
            FROM {$this->documento} cabecera 
            INNER JOIN {$this->documento_detalle} detalle 
                ON cabecera.rowid = detalle.fk_documento
            INNER JOIN fi_europa_documentos_movimientos_detalles movimiento_detalle 
                ON detalle.rowid = movimiento_detalle.destino_fk_documento_detalle
                AND detalle.fk_documento = movimiento_detalle.destino_fk_documento
            WHERE detalle.fk_documento = {$this->id} AND md5(detalle.rowid) = '{$lineaMd5}'
            AND IFNULL(movimiento_detalle.borrado,0) = 0
            AND cabecera.estado = 0 LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $a  =  $stmt->execute();

        if (!$a) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $stmt->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $cantidad_origen = floatval($row["detalle_origen_cantidad"]);
            $cantidad_destino = floatval($row["destino_cantidad"]);
            $estado_detalle = NULL;

            if ($cantidad_destino <= $cantidad_origen) {
                $estado_detalle = 1;
            }
            if ($cantidad_destino > $cantidad_origen) {
                $estado_detalle = 0;
            }
            if ($estado_detalle == 0 || $estado_detalle == 1) {
                $sqlUpdate = "UPDATE {$this->documento_detalle} 
                            SET fk_estado_detalle = {$estado_detalle} 
                            WHERE md5(rowid) = '{$lineaMd5}' AND fk_documento = {$this->id} ";

                $dbhUpdate = $this->db->prepare($sqlUpdate);
                $resultUpdate = $dbhUpdate->execute();

                if (!$resultUpdate) {
                    $this->sql     =   $sqlUpdate;
                    $this->error   =   implode(", ", $dbhUpdate->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
                    $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
                    $this->Error_SQL();
                }

                if ($estado_detalle == 0) {
                    $estado_documento = 4;
                }
                if ($estado_detalle == 1) {
                    $estado_documento = 3;
                }
                $sqlUpdateOrigen = "UPDATE {$row["origen_documento"]} 
                            SET estado = {$estado_documento} 
                            WHERE rowid = '{$row["origen_fk_documento"]}'  ";
                $dbhUpdateOrigen = $this->db->prepare($sqlUpdateOrigen);
                $resultUpdateOrigen = $dbhUpdateOrigen->execute();

                if (!$resultUpdateOrigen) {
                    $this->sql     =   $sqlUpdateOrigen;
                    $this->error   =   implode(", ", $dbhUpdateOrigen->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
                    $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
                    $this->Error_SQL();
                }

                return array('estado_detalle' => $estado_detalle, 'cantidad_inicial' => $row["destino_cantidad"]);
            }
        }
    }


    public function recalculo_xml()
    {

        $sql = "select 
                    
        subtotal_pre_retencion          ,
        
        impuesto_iva_monto              , 
        impuesto_iva_porcentaje         , 

        impuesto_iva_equivalencia_monto ,
        impuesto_iva_equivalencia_porcentaje, 

        impuesto_retencion_monto        , 
        impuesto_retencion_porcentaje   ,
        total 

        from {$this->documento_detalle} 
        where 
                fk_documento        =   :fk_documento
        and     entidad           =   :entidad
        ";
        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':entidad',         $this->entidad,   PDO::PARAM_STR);
        $dbh->bindValue(':fk_documento',      $this->id,   PDO::PARAM_INT);
        $a = $dbh->execute();


        if (!$a) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();
        }

        $IVA        = array();
        $RE         = array();
        $impuesto_iva           = 0;
        $impuesto_iva_equivalencia = 0;
        $subtotal_pre_retencion = 0;
        $retencion              = 0;
        $total                  = 0;




        while ($row = $dbh->fetch(PDO::FETCH_OBJ)) {
            $IVA[(int)$row->impuesto_iva_porcentaje]['impuesto']                += $row->impuesto_iva_monto;
            $IVA[(int)$row->impuesto_iva_porcentaje]['subtotal']                += $row->subtotal_pre_retencion;
            $RE[number_format($row->impuesto_iva_equivalencia_porcentaje, 2)]['impuesto']         += $row->impuesto_iva_equivalencia_monto;
            $RE[number_format($row->impuesto_iva_equivalencia_porcentaje, 2)]['subtotal']         += $row->subtotal_pre_retencion;
        }

        $datos['IVA'] = $IVA;
        $datos['RE'] = $RE;

        return  $datos;
    }





    /****************************************************
     * 
     *   Funcciones No limpias 
     * 
     * 
     *****************************************************/

    public function fetch_encriptado($idMD5)
    {

        $query = "select rowid  from {$this->documento}  where md5(rowid) = ? ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $idMD5, PDO::PARAM_STR);
        $a  =  $stmt->execute();

        if (!$a) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $stmt->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();
        }


        $row = $stmt->fetch(PDO::FETCH_ASSOC);


        $this->fetch($row['rowid']);
    }




    public function fetch($id)
    {



        $query = "
        SELECT 
            c.*, 
            CASE 
                WHEN t.tipo = 'fisica' THEN CONCAT(t.nombre, ' ', t.apellidos) 
                WHEN t.tipo = 'juridica' THEN t.nombre 
                ELSE ''  
            END AS nombre_cliente, 
            dm.codigo AS codigo_moneda, 
            t.direccion AS direccion_cliente,
            t.email as email_cliente,
            t.telefono as telefono_cliente,
            t.cedula as cedula_cliente,
            t.pais as pais_cliente,
            t.poblacion as poblacion_cliente,
            t.codigo_postal as codigo_postal_cliente,
            t.provincia as provincia_cliente,
            IFNULL(p.referencia, '') as proyecto_referencia,
            IFNULL(p.nombre, '') as proyecto_nombre,
            IFNULL(config.plantilla_fk,0) as serie_plantilla
        FROM 
            {$this->documento} c 
        
        LEFT JOIN 
            fi_terceros t ON t.rowid = c.fk_tercero 
        LEFT JOIN 
            fi_proyectos p ON p.rowid = c.fk_proyecto
        LEFT JOIN 
            diccionario_monedas dm ON dm.rowid = c.moneda 
        LEFT JOIN 
            fi_europa_facturas_configuracion config ON config.rowid = c.fk_serie_configuracion
        WHERE c.entidad = :entidad
        AND c.rowid = :rowid ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":rowid", $id, PDO::PARAM_INT);
        $stmt->bindParam(":entidad", $this->entidad, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id       = $row['rowid'];
            $this->entidad  = $row['entidad'];
            $this->moneda   = $row['moneda'];

            $this->subtotal_pre_retencion       = $row['subtotal_pre_retencion'];
            $this->impuesto_retencion_irpf      = $row['impuesto_retencion_irpf'];
            $this->impuesto_iva_equivalencia    = $row['impuesto_iva_equivalencia'];
            $this->impuesto_iva                 = $row['impuesto_iva'];
            $this->total                        = $row['total'];
            $this->descuento_valor_final        = $row['TotalDescuentos'];
            $this->asesor_comercial_txt         = $row['asesor_comercial_txt'];


            $this->estado_hacienda              = $row['estado_hacienda'];
            $this->fk_tercero                   = $row['fk_tercero'];
            $this->fk_tercero_txt               = $row['fk_tercero_txt'];
            $this->fk_tercero_identificacion    = $row['fk_tercero_identificacion'];
            $this->fk_usuario_validar_fecha     = $row['fk_usuario_validar_fecha'];
            $this->referencia                   = $row['referencia'];
            $this->referencia_serie             = $row['referencia_serie'];
            $this->fk_proyecto                  = $row['fk_proyecto'];
            $this->proyecto_referencia          = $row['proyecto_referencia'];
            $this->proyecto_nombre              = $row['proyecto_nombre'];
            $this->fk_direccion                 = $row['fk_direccion'];

            $this->estado                       = $row['estado'];
            $this->tipo                         = $row['tipo'];



            // Unicos para Documentos XML 
            $this->xml_IDEmisorFactura          = $row['xml_IDEmisorFactura'];
            $this->xml_IDVersion                = $row['xml_IDVersion'];
            $this->xml_hacienda_enviado         = $row['xml_hacienda_enviado'];
            $this->xml_huella_sha256            = $row['xml_huella_sha256'];





            $this->estado_verifactum_envio       = $row['estado_verifactum_envio'];
            $this->estado_verifactum_registro   = $row['estado_verifactum_registro'];
            $this->estado_hacienda              = $row['estado_hacienda'];

            $this->verifactum_produccion         = $row['verifactum_produccion'];



            $this->fecha_entrega = $row['fecha_entrega'];

            $this->codigo_moneda = $row['codigo_moneda'];
            $this->moneda_tipo_cambio = $row['moneda_tipo_cambio'];
            $this->fk_usuario_crear = $row['fk_usuario_crear'];
            $this->fk_usuario_validar = $row['fk_usuario_validar'];
            $this->fecha = $row["fecha"];
            $this->fecha_vencimiento = $row['fecha_vencimiento'];
            $this->forma_pago = $row['forma_pago'];
            $this->detalle = $row['detalle'];
            $this->subtotal = $row['subtotal'];
            $this->pagado = $row['pagado'];
            $this->estado_pagada = $row['estado_pagada'];
            $this->fecha_creacion_server = $row['fecha_creacion_server'];
            $this->txt_cliente = $row['txt_cliente'];
            $this->direccion = $row['direccion'];

            //datos del cliente
            $this->nombre_cliente = $row['nombre_cliente'];
            $this->cedula_cliente = $row['cedula_cliente'];
            $this->telefono_cliente = $row['telefono_cliente'];
            $this->email_cliente = $row['email_cliente'];
            $this->direccion_cliente = $row['direccion_cliente'];

            $this->pais_cliente = $row['pais_cliente'];
            $this->poblacion_cliente = $row['poblacion_cliente'];
            $this->codigo_postal_cliente = $row['codigo_postal_cliente'];
            $this->provincia_cliente = $row['provincia_cliente'];

            // los distintos IVA
            $this->IVA_0 = $row['IVA_0'];
            $this->IVA_10 = $row['IVA_10'];
            $this->IVA_4 = $row['IVA_4'];
            $this->IVA_21 = $row['IVA_21'];

            $this->fk_tercero_identificacion = $row['fk_tercero_identificacion'];
            $this->fk_tercero_telefono = $row['fk_tercero_telefono'];
            $this->fk_tercero_email = $row['fk_tercero_email'];
            $this->fk_tercero_direccion = $row['fk_tercero_direccion'];
            $this->entidad_razonsocial = $row['entidad_razonsocial'];
            $this->entidad_fantasia = $row['entidad_fantasia'];
            $this->entidad_identificacion = $row['entidad_identificacion'];
            $this->entidad_email = $row['entidad_email'];
            $this->entidad_direccion = $row['entidad_direccion'];
            $this->entidad_telefonofijo = $row['entidad_telefonofijo'];
            $this->forma_pago_txt = $row['forma_pago_txt'];

            $this->fk_agente       = $row['fk_agente'];

            $this->idEncriptado    = md5($row['rowid']);

            $this->fk_plantilla       = $row['fk_plantilla'];
            $this->fk_serie_configuracion       = $row['fk_serie_configuracion'];
            $this->fk_serie_plantilla = $row['serie_plantilla'];
            
            $this->fetch_documento_origen();
        } else {
            return false;
        }
    }

    private function fetch_documento_origen()
    {
        $sql = "SELECT mov.rowid, mov.origen_documento, mov.origen_fk_documento FROM fi_europa_documentos_movimientos mov
            WHERE mov.destino_fk_documento = {$this->id} 
            AND IFNULL(mov.borrado,0) = 0
            AND mov.destino_documento = '{$this->documento}'
        ";
        $stmt = $this->db->prepare($sql);
        $a  =  $stmt->execute();

        if (!$a) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $stmt->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();
        }

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if($this->movimiento_origen == ''){
                $this->movimiento_origen = $row["origen_documento"];
            }
            $this->movimiento_fk_origen[] = $row["origen_fk_documento"];
        }
        // $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // if (!empty($row["rowid"])) {
        //     $this->movimiento_origen = $row["origen_documento"];
        //     $this->movimiento_fk_origen = $row["origen_fk_documento"];
        // }
    }
    public function obtener_documentos_destino()
    {
        $sql = "SELECT DISTINCT COALESCE(origen_compras.rowid, origen_alb_compras.rowid, origen_ventas.rowid, origen_facturas.rowid) AS rowid,
                    COALESCE(origen_compras.referencia, origen_alb_compras.referencia, origen_ventas.referencia, origen_facturas.referencia) AS referencia,
                    COALESCE(origen_compras.estado, origen_alb_compras.estado, origen_ventas.estado, origen_facturas.estado) AS estado,
                    mov.destino_documento,
                    mov.destino_fk_documento
            FROM fi_europa_documentos_movimientos mov
            LEFT JOIN fi_europa_compras origen_compras ON origen_compras.rowid = mov.destino_fk_documento AND mov.destino_documento = 'fi_europa_compras'
            LEFT JOIN fi_europa_albaranes_compras origen_alb_compras ON origen_alb_compras.rowid = mov.destino_fk_documento AND mov.destino_documento = 'fi_europa_albaranes_compras'
            LEFT JOIN fi_europa_albaranes_ventas origen_ventas ON origen_ventas.rowid = mov.destino_fk_documento AND mov.destino_documento = 'fi_europa_albaranes_ventas'
            LEFT JOIN fi_europa_facturas origen_facturas ON origen_facturas.rowid = mov.destino_fk_documento AND mov.destino_documento = 'fi_europa_facturas'
            WHERE mov.origen_documento = '{$this->documento}' 
            AND IFNULL(mov.borrado,0) = 0
            AND mov.origen_fk_documento = {$this->id} ;
        ";

        $stmt = $this->db->prepare($sql);
        $a  =  $stmt->execute();

        if (!$a) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $stmt->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();
        }

        while ($retorno = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->movimiento_destinos[] = array('rowid' => $retorno['rowid'], 'referencia' => $retorno['referencia'], 'destino_documento' => $retorno['destino_documento'], 'estado' => $retorno['estado']);
        }
    }

    public function obtener_historial_detalle()
    {
        $tabla_origen = '';
        $tabla_origen_id = [];
        if (!empty($this->movimiento_origen)) {
            $tabla_origen = $this->movimiento_origen;
        } else {
            $tabla_origen = $this->documento;
        }
        if (!empty($this->movimiento_origen)) {
            $tabla_origen_id = $this->movimiento_fk_origen;
        } else {
            $tabla_origen_id[] = $this->id;
        }

        // $sqlAlbaran = "SELECT cab.referencia as referencia_cab, cab.referencia, detalle.rowid, detalle.referencia as referencia_det, detalle.fk_producto, detalle.label, det.cantidad AS cantidad_cab, detalle.cantidad AS cantidad_det
        //     FROM {$tabla_origen} cab INNER JOIN {$tabla_origen}_detalle det ON cab.rowid = det.fk_documento
        //     INNER JOIN
        //     (
        //         SELECT comdet.rowid, comcab.referencia, comdet.fk_producto, pro.label, comdet.cantidad, comdet.origen_fk_documento_detalle
        //         FROM fi_europa_compras_detalle comdet
        //         INNER JOIN fi_europa_compras comcab ON comcab.rowid = comdet.fk_documento
        //         INNER JOIN fi_productos pro ON pro.rowid = comdet.fk_producto
        //         WHERE CONCAT(comdet.fk_documento, comdet.rowid) IN (
        //             SELECT CONCAT(movdet.destino_fk_documento, movdet.destino_fk_documento_detalle)
        //             FROM fi_europa_documentos_movimientos movcab
        //             INNER JOIN fi_europa_documentos_movimientos_detalles movdet ON movcab.rowid = movdet.fk_documento_movimiento
        //             WHERE movcab.origen_documento = '{$tabla_origen}'
        //             AND movcab.origen_fk_documento = {$tabla_origen_id}
        //         )
        //     ) detalle ON detalle.origen_fk_documento_detalle = det.rowid
        //     ORDER BY detalle.fk_producto
        // ";
        $sqlAlbaran = "SELECT cab.referencia as referencia_cab, cab.referencia, detalle.rowid, detalle.referencia as referencia_det, detalle.fk_producto, detalle.label, det.cantidad AS cantidad_cab, detalle.cantidad AS cantidad_det
            FROM {$tabla_origen} cab INNER JOIN {$tabla_origen}_detalle det ON cab.rowid = det.fk_documento
            INNER JOIN
            (
                SELECT comdet.rowid, comcab.referencia, comdet.fk_producto, pro.label, comdet.cantidad, comdet.origen_fk_documento_detalle
                FROM fi_europa_compras_detalle comdet
                INNER JOIN fi_europa_compras comcab ON comcab.rowid = comdet.fk_documento
                INNER JOIN fi_productos pro ON pro.rowid = comdet.fk_producto
                WHERE comdet.fk_documento IN (
                    SELECT movdet.destino_fk_documento
                    FROM fi_europa_documentos_movimientos movcab
                    INNER JOIN fi_europa_documentos_movimientos_detalles movdet ON movcab.rowid = movdet.fk_documento_movimiento
                    WHERE movcab.origen_documento = '{$tabla_origen}'
                    AND movcab.origen_fk_documento IN ( ".implode(',', $tabla_origen_id).")
                )
                and comcab.borrado = 0
            ) detalle ON detalle.origen_fk_documento_detalle = det.rowid
            ORDER BY detalle.fk_producto
        ";


        $dbAlbaran = $this->db->prepare($sqlAlbaran);
        $dbAlbaran->execute();
        $objAlbaran = $dbAlbaran->fetchAll(PDO::FETCH_ASSOC);
        // $datos_tooltip[] = $objAlbaran["referencia"];
        if ($objAlbaran) {
            return $objAlbaran;
        } else {
            return [];
        }
    }

    public function clonar_documento($id_documento, $usuario, $nombre_documento_base = '', $nombre_documento_detalle_base = '')
    {
        if ($nombre_documento_base == '') {
            $nombre_documento_base = $this->documento;
        }
        if ($nombre_documento_detalle_base == '') {
            $nombre_documento_detalle_base = $this->documento_detalle;
        }


        // if ($this->documento !== $nombre_documento_base) {
        $origen_documento_inicio   = "   ,   origen_documento            ";
        $origen_documento_fin      = "   ,   '{$nombre_documento_base}'  ";

        $origen_fk_documento_inicio = "   ,   origen_fk_documento_detalle   ";
        $origen_fk_documento_fin    = "   ,   origen_detalle.rowid                         ";
        // }



        $sql = "INSERT INTO {$this->documento} (
            entidad, 
            referencia, 
            referencia_serie, 
            fk_usuario_crear, 
            fecha, 
            fecha_vencimiento, 
            fk_tercero, 
            fk_tercero_txt, 
            fk_tercero_identificacion, 
            tipo, 
            forma_pago, 
            detalle, 
            estado, 
            creado_fecha, 
            moneda, 
            fk_proyecto, 
            moneda_tipo_cambio, 
            subtotal_pre_retencion, 
            impuesto_iva, 
            impuesto_iva_equivalencia, 
            impuesto_retencion_irpf, 
            total, 
            IVA_0, 
            IVA_10, 
            IVA_4, 
            IVA_21, 
            RE_5_2, 
            RE_1_4, 
            RE_0_5, 
            RE_0_75, 
            asesor_comercial_txt, 
            entidad_razonsocial, 
            entidad_fantasia, 
            entidad_identificacion, 
            entidad_email, 
            entidad_direccion, 
            entidad_telefonofijo,
            forma_pago_txt, 
            fk_agente
        ) 
        (SELECT 
            entidad, 
            :referencia, 
            :referencia_serie, 
            :fk_usuario_crear, 
            NOW(), 
            :fecha_vencimiento, 
            fk_tercero, 
            fk_tercero_txt, 
            fk_tercero_identificacion, 
            :tipo, 
            forma_pago, 
            detalle, 
            0, 
            creado_fecha, 
            moneda, 
            fk_proyecto, 
            moneda_tipo_cambio, 
            subtotal_pre_retencion, 
            impuesto_iva, 
            impuesto_iva_equivalencia, 
            impuesto_retencion_irpf, 
            total, 
            IVA_0, 
            IVA_10, 
            IVA_4, 
            IVA_21, 
            RE_5_2, 
            RE_1_4, 
            RE_0_5, 
            RE_0_75, 
            asesor_comercial_txt, 
            entidad_razonsocial, 
            entidad_fantasia, 
            entidad_identificacion, 
            entidad_email, 
            entidad_direccion, 
            entidad_telefonofijo,
            forma_pago_txt, 
            fk_agente
        FROM 
            {$nombre_documento_base}  
        WHERE 
            rowid = $id_documento
        )";



        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':referencia', 'Borrador ' . $this->siguiente_borrador,                                                 PDO::PARAM_STR);
        $dbh->bindValue(':referencia_serie', $this->referencia_serie,                                                           PDO::PARAM_STR);
        $dbh->bindValue(':fk_usuario_crear', $usuario,                                                                          PDO::PARAM_INT);
        $dbh->bindValue(':fecha_vencimiento', (empty($this->fecha_vencimiento)) ? date('Y-m-d') : $this->fecha_vencimiento,     PDO::PARAM_STR);
        $dbh->bindValue(':tipo', 'F' . $this->siguiente_documento['simplificada']['referencia_serie'],                          PDO::PARAM_STR);

        $a = $dbh->execute();

        if (!$a) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();
        }

        $id = $this->db->lastInsertId();
        $this->id = $id;

        // $this->clonar_documento_detalle($id, $id_documento, $nombre_documento_detalle_base, $origen_documento_inicio, $origen_fk_documento_inicio, $origen_documento_fin, $origen_fk_documento_fin);

        // return $id;
        return array(
            'id' => $id,
            'nombre_documento_detalle_base' => $nombre_documento_detalle_base,
            'origen_documento_inicio' => $origen_documento_inicio,
            'origen_fk_documento_inicio' => $origen_fk_documento_inicio,
            'origen_documento_fin' => $origen_documento_fin,
            'origen_fk_documento_fin' => $origen_fk_documento_fin
        );
    }
    public function clonar_documento_detalle($id_generado, $id_documento, $nombre_documento_detalle_base, $origen_documento_inicio, $origen_fk_documento_inicio, $origen_documento_fin, $origen_fk_documento_fin, $solo_clonado = true)
    {
        // Crea el Detalle de la Factura
        /*
            $sql = "INSERT INTO {$this->documento_detalle} (entidad, fk_documento, 
            fk_producto , tipo, num_linea
            , label             , ref,  label_extra, precio_original , precio_costo, precio_unitario
            , cantidad, descuento_tipo, descuento_aplicado,   descuento_valor_final  ,subtotal_pre_retencion , subtotal
            , impuesto_iva_id, impuesto_iva_monto , impuesto_iva_porcentaje 
            , impuesto_iva_equivalencia_aplica   , impuesto_iva_equivalencia_monto      , impuesto_iva_equivalencia_porcentaje   
            , impuesto_retencion_aplica          , impuesto_retencion_monto             , impuesto_retencion_porcentaje, total
            ,fecha_creacion, descripcion  {$origen_documento_inicio}  {$origen_fk_documento_inicio}    ) 
            (SELECT entidad            , " . $id_generado . "        ,   fk_producto , tipo, num_linea
            , label             , ref,  label_extra, precio_original , precio_costo, precio_unitario
            , cantidad, descuento_tipo, descuento_aplicado,   descuento_valor_final  ,subtotal_pre_retencion , subtotal
            , impuesto_iva_id, impuesto_iva_monto , impuesto_iva_porcentaje 
            , impuesto_iva_equivalencia_aplica   , impuesto_iva_equivalencia_monto      , impuesto_iva_equivalencia_porcentaje   
            , impuesto_retencion_aplica          , impuesto_retencion_monto             , impuesto_retencion_porcentaje, total
            ,fecha_creacion, descripcion   {$origen_documento_fin}   {$origen_fk_documento_fin}
            FROM {$nombre_documento_detalle_base}  WHERE fk_documento = $id_documento )";
        */

        /*
            $sql = "SELECT entidad            , " . $id_generado . "        ,   fk_producto , tipo, num_linea
            , label             , ref,  label_extra, precio_original , precio_costo, precio_unitario
            , cantidad, descuento_tipo, descuento_aplicado,   descuento_valor_final  ,subtotal_pre_retencion , subtotal
            , impuesto_iva_id, impuesto_iva_monto , impuesto_iva_porcentaje 
            , impuesto_iva_equivalencia_aplica   , impuesto_iva_equivalencia_monto      , impuesto_iva_equivalencia_porcentaje   
            , impuesto_retencion_aplica          , impuesto_retencion_monto             , impuesto_retencion_porcentaje, total
            ,fecha_creacion, descripcion   {$origen_documento_fin}   {$origen_fk_documento_fin}
            ,(
                SELECT origen_detalle.cantidad-SUM(destino_detalle.cantidad)
                FROM {$nombre_documento_detalle_base} origen_detalle 
                INNER JOIN {$this->documento_detalle} destino_detalle ON destino_detalle.origen_documento = '" . str_replace("_detalle", "", $nombre_documento_detalle_base) . "' AND 
                destino_detalle.origen_fk_documento_detalle = origen_detalle.rowid
                WHERE origen_detalle.fk_documento={$id_documento} AND destino_detalle.fk_estado_detalle = 0
                AND origen_detalle.fk_producto = {$nombre_documento_detalle_base}.fk_producto
            ) as cantidadmovimiento
            FROM {$nombre_documento_detalle_base}  WHERE fk_documento = $id_documento ";
        */

        $query_duplicado = '(origen_detalle.cantidad - IFNULL(destino_detalle.cantidad,0) )';
        if(!$solo_clonado){
            $query_duplicado = '(origen_detalle.cantidad - IFNULL(SUM(destino_detalle.cantidad),0) )';
        }
        $sql = "SELECT origen_detalle.entidad            , " . $id_generado . "        ,   origen_detalle.fk_producto , origen_detalle.tipo, origen_detalle.num_linea
            , origen_detalle.label             , origen_detalle.ref,  origen_detalle.label_extra, origen_detalle.precio_original , origen_detalle.precio_costo, origen_detalle.precio_unitario
            , origen_detalle.cantidad, origen_detalle.descuento_tipo, origen_detalle.descuento_aplicado,   origen_detalle.descuento_valor_final  ,origen_detalle.subtotal_pre_retencion , origen_detalle.subtotal
            , origen_detalle.impuesto_iva_id, origen_detalle.impuesto_iva_monto , origen_detalle.impuesto_iva_porcentaje 
            , origen_detalle.impuesto_iva_equivalencia_aplica   , origen_detalle.impuesto_iva_equivalencia_monto      , origen_detalle.impuesto_iva_equivalencia_porcentaje   
            , origen_detalle.impuesto_retencion_aplica          , origen_detalle.impuesto_retencion_monto             , origen_detalle.impuesto_retencion_porcentaje, origen_detalle.total
            , origen_detalle.fecha_creacion, origen_detalle.descripcion   {$origen_documento_fin}   {$origen_fk_documento_fin}
            ,{$query_duplicado} AS cantidadmovimiento
            FROM {$nombre_documento_detalle_base}  origen_detalle 
            LEFT JOIN {$this->documento_detalle} destino_detalle ON destino_detalle.origen_fk_documento_detalle = origen_detalle.rowid
            WHERE origen_detalle.fk_documento = $id_documento             
        ";
        if(!$solo_clonado){
            $sql .= ' GROUP BY fk_producto';
        }
        
        $dbh = $this->db->prepare($sql);
        $aaa  =  $dbh->execute();
        if (!$aaa) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();
        }

        $sqlInsert = "INSERT INTO {$this->documento_detalle} (
                    entidad,                            fk_documento,                           fk_producto ,               tipo,               num_linea
                , label,                                ref,                                    label_extra,                precio_original 
                , precio_costo,                         precio_unitario,                        cantidad,                   descuento_tipo
                , descuento_aplicado,                   descuento_valor_final,                  subtotal_pre_retencion,     subtotal
                , impuesto_iva_id,                      impuesto_iva_monto ,                    impuesto_iva_porcentaje,    impuesto_iva_equivalencia_aplica
                , impuesto_iva_equivalencia_monto,      impuesto_iva_equivalencia_porcentaje,   impuesto_retencion_aplica,  impuesto_retencion_monto
                , impuesto_retencion_porcentaje, total, fecha_creacion, descripcion  {$origen_documento_inicio}  {$origen_fk_documento_inicio}    
                ) 
                VALUES";
        while ($result = $dbh->fetch(PDO::FETCH_ASSOC)) {

            if($this->movimiento_origen != '' ){
                $cantidad_asignar = $result["cantidadmovimiento"];
                if ($cantidad_asignar == null) {
                    $cantidad_asignar = $result["cantidad"];
                }else{
                    if ($cantidad_asignar == 0) {
                        continue;
                    }
                }
            }else{
                $cantidad_asignar = $result["cantidad"];
            }
            // $cantidad_asignar = $result["cantidadmovimiento"];
            // if ($cantidad_asignar == null) {
            //     $cantidad_asignar = $result["cantidad"];
            // }
            $num_linea = empty($result["num_linea"]) ? 'NULL' : $result["num_linea"];
            $precio_costo = empty($result["precio_costo"]) ? 'NULL' : $result["precio_costo"];
            $precio_unitario = empty($result["precio_unitario"]) ? 'NULL' : $result["precio_unitario"];
            $precio_original = empty($result["precio_original"]) ? 'NULL' : $result["precio_original"];
            $descuento_aplicado = empty($result["descuento_aplicado"]) ? 'NULL' : $result["descuento_aplicado"];
            $descuento_valor_final = empty($result["descuento_valor_final"]) ? 'NULL' : $result["descuento_valor_final"];
            $subtotal_pre_retencion = empty($result["subtotal_pre_retencion"]) ? 'NULL' : $result["subtotal_pre_retencion"];
            $subtotal = empty($result["subtotal"]) ? 'NULL' : $result["subtotal"];
            $impuesto_iva_id = empty($result["impuesto_iva_id"]) ? 'NULL' : $result["impuesto_iva_id"];
            $impuesto_iva_monto = empty($result["impuesto_iva_monto"]) ? 'NULL' : $result["impuesto_iva_monto"];
            $impuesto_iva_porcentaje = empty($result["impuesto_iva_porcentaje"]) ? 'NULL' : $result["impuesto_iva_porcentaje"];
            $impuesto_iva_equivalencia_aplica = empty($result["impuesto_iva_equivalencia_aplica"]) ? 'NULL' : $result["impuesto_iva_equivalencia_aplica"];
            $impuesto_iva_equivalencia_monto = empty($result["impuesto_iva_equivalencia_monto"]) ? 'NULL' : $result["impuesto_iva_equivalencia_monto"];
            $impuesto_iva_equivalencia_porcentaje = empty($result["impuesto_iva_equivalencia_porcentaje"]) ? 'NULL' : $result["impuesto_iva_equivalencia_porcentaje"];
            $impuesto_retencion_aplica = empty($result["impuesto_retencion_aplica"]) ? 0 : $result["impuesto_retencion_aplica"];
            $impuesto_retencion_monto = empty($result["impuesto_retencion_monto"]) ? 'NULL' : $result["impuesto_retencion_monto"];
            $impuesto_retencion_porcentaje = empty($result["impuesto_retencion_porcentaje"]) ? 'NULL' : $result["impuesto_retencion_porcentaje"];


            $sqlInsert .= " (
            {$result["entidad"]},                   {$id_generado},                         {$result["fk_producto"]},                   {$result["tipo"]},  {$num_linea}
            , '{$result["label"]}',                 '{$result["ref"]}',                     '{$result["label_extra"]}',                 {$precio_original}
            ,  {$precio_costo},           {$precio_unitario},           {$cantidad_asignar},                        '{$result["descuento_tipo"]}'
            ,  {$descuento_aplicado},     {$descuento_valor_final},     {$subtotal_pre_retencion},        '{$subtotal}'
            ,  {$impuesto_iva_id},        {$impuesto_iva_monto},        {$impuesto_iva_porcentaje},       '{$impuesto_iva_equivalencia_aplica}'
            ,  {$impuesto_iva_equivalencia_monto},        {$impuesto_iva_equivalencia_porcentaje},        {$impuesto_retencion_aplica},       '{$impuesto_retencion_monto}'
            ,  {$impuesto_retencion_porcentaje},        {$result["total"]},       NOW(),                                      '{$result["descripcion"]}'      {$origen_documento_fin}   ,{$result["rowid"]}
            ),";
        }
        $sqlInsert = substr($sqlInsert, 0, -1);

        $stmt = $this->db->prepare($sqlInsert);
        $resultInsert = $stmt->execute();

        if (!$resultInsert) {
            $this->sql     =   $sqlInsert;
            $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();
        }
    }


    public function obtener_validaciones_tercero($fk_tercero, $monto_venta = 0, $forma_pago = 0, $forzar_venta = 0)
    {
        $sqlstr = '';
        $messages = array();

        try {
            /* Obtengo información del cliente */
            $sqlstr = "SELECT 
            t.rowid        ,
            t.saldo_credito,
            t.limite_credito, 
            t.saldo_credito, 
            t.limite_credito_estricto,
            t.moroso,
            t.credito_cerrado,
            t.motivo_cierre,
            IFNULL(config.rowid,0) as cliente_generico
            FROM fi_terceros t
            LEFT JOIN fi_configuracion config ON config.configuracion = 'cliente_defecto' AND config.valor= '" . $fk_tercero . "' AND config.entidad = :entidad
            WHERE t.rowid = :fk_tercero
            AND t.entidad = :entidad";
            $db_result = $this->db->prepare($sqlstr);
            $db_result->bindParam(':entidad', $this->entidad, PDO::PARAM_INT);
            $db_result->bindParam(':fk_tercero', $fk_tercero, PDO::PARAM_INT);
            $db_result->execute();
            $u = $db_result->fetch(PDO::FETCH_ASSOC);
            /* Obtengo información del cliente */

            if ($u) {
                if ($u["cliente_generico"] == "0") {
                    // Si no es cliente genérico
                    $saldo_credito = $u["saldo_credito"];
                    $limite_credito = $u["limite_credito"];
                    $limite_credito_estricto = $u["limite_credito_estricto"];
                    $moroso = $u["moroso"];
                    $credito_cerrado = $u["credito_cerrado"];
                    $motivo_cierre = $u["motivo_cierre"];

                    if ($moroso == "1" && $forzar_venta == 0) {
                        array_push($messages, array('mensaje' => 'Este cliente se encuentra moroso.', 'tipo' => 'warning'));
                    }
                    if ($credito_cerrado == "1"  && $forzar_venta == 0) {
                        array_push($messages, array('mensaje' => 'Este cliente tiene el crédito cerrado.', 'tipo' => 'warning'));
                    }
                    if ($saldo_credito < $monto_venta) {
                        array_push($messages, array('mensaje' => 'La compra excede el límite del saldo del cliente.', 'tipo' => 'error'));
                    }
                    if ($limite_credito_estricto == "1") {
                        array_push($messages, array('mensaje' => 'Este cliente tiene el crédito estricto.', 'tipo' => 'error'));
                    }
                }

                if (empty($fk_tercero)) {
                    array_push($messages, array('mensaje' => 'Utilizando Cliente Genérico', 'tipo' => 'warning'));
                }
            }
        } catch (Exception $ex) {
            $this->sql     =   $sqlstr;
            $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();
        }

        return $messages;
    }

    public function eliminar($rowid)
    {
        $resultado = ['success' => false, 'message' => ''];



        $sql = "select estado  from  {$this->documento}  WHERE rowid = :id and entidad = :entidad ";
        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':entidad', $this->entidad, PDO::PARAM_STR);
        $dbh->bindValue(':id', $rowid, PDO::PARAM_INT);
        $c = $dbh->execute();

        if (!$c) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();
        }
        $estado = $dbh->fetch(PDO::FETCH_ASSOC);

        if (isset($estado['estado']) && $estado['estado'] == 0) {


            $sql = "select rowid from  " . $this->documento_detalle . "  WHERE fk_documento = :id";
            $dbh = $this->db->prepare($sql);
            $dbh->bindValue(':id', $rowid, PDO::PARAM_INT);
            $a = $dbh->execute();
            $rows = $dbh->fetchAll(PDO::FETCH_ASSOC);

            // $this->eliminar_movimientos($rows);



            $sql = "DELETE FROM " . $this->documento_detalle . "  WHERE fk_documento = :id";
            $dbh = $this->db->prepare($sql);
            $dbh->bindValue(':id', $rowid, PDO::PARAM_INT);
            $a = $dbh->execute();

            if (!$a) {
                $this->sql     =   $sql;
                $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
                $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
                $this->Error_SQL();
                $resultado['message'] = $this->error;
            }


            $sql = "DELETE FROM " . $this->documento . " WHERE rowid = :id and entidad = :entidad  ";
            $dbh = $this->db->prepare($sql);
            $dbh->bindValue(':id', $rowid, PDO::PARAM_INT);
            $dbh->bindValue(':entidad', $this->entidad, PDO::PARAM_STR);
            $b = $dbh->execute();

            if (!$b) {
                $this->sql     =   $sql;
                $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
                $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
                $this->Error_SQL();
                $resultado['message'] .= $this->error;
            }



            if ($a and $b) {
                $resultado['success'] = true;
                $resultado['message'] = 'Información eliminada correctamente';
                $resultado['location'] = $this->listado_url;
            } else {
                $resultado['success'] = false;
            }
        } else {
            $resultado['success'] = false;
            $resultado['message'] = 'Solo Pueden Eliminarse Documentos en Borrador';
        }





        return $resultado;
    }
    public function actualiza_documento_origen()
    {
        $movimiento_fk_origen_ids = implode(',', $this->movimiento_fk_origen);
        $sqlUpdate = "UPDATE fi_europa_documentos_movimientos 
        SET borrado = 1, borrado_fecha = NOW(), borrado_fk_usuario = {$this->usuario}
        WHERE origen_documento = '{$this->movimiento_origen}' 
        AND origen_fk_documento IN ( {$movimiento_fk_origen_ids} )
        AND destino_documento = '{$this->documento}' 
        AND destino_fk_documento = {$this->id} 
        ";

        $dbhUpdate = $this->db->prepare($sqlUpdate);
        $resultUpdate = $dbhUpdate->execute();

        if (!$resultUpdate) {
            $this->sql     =   $sqlUpdate;
            $this->error   =   implode(", ", $dbhUpdate->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();
        }

        // Comprobar si existen más registros con las condiciones dadas
        $sqlExiste = "SELECT 1
            FROM fi_europa_documentos_movimientos
            WHERE origen_documento = '{$this->movimiento_origen}'
            AND origen_fk_documento IN ( {$movimiento_fk_origen_ids} )
            AND IFNULL(borrado,0) = 0
            LIMIT 1
        ";

        $dbExiste = $this->db->prepare($sqlExiste);
        $dbExiste->execute();

        if ($dbExiste->rowCount() > 0) {
            $sqlUpdateFinal = "UPDATE {$this->movimiento_origen}
                SET estado = 4
                WHERE rowid IN ( {$movimiento_fk_origen_ids} ) ";
        } else {
            $sqlUpdateFinal = "UPDATE {$this->movimiento_origen}
                SET estado = 1
                WHERE rowid IN ( {$movimiento_fk_origen_ids} ) ";
        }
        $dbhUpdateFinal = $this->db->prepare($sqlUpdateFinal);
        $resultUpdateFinal = $dbhUpdateFinal->execute();

        if (!$resultUpdateFinal) {
            $this->sql     =   $sqlUpdateFinal;
            $this->error   =   implode(", ", $dbhUpdateFinal->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();
        }

        return true;
    }


    /**********************************************************
     * 
     * 
     *      Validar el documento
     *      MRI 
     * 
     * 
     *************************************************************/
    public function validar($usuario, $serie = NULL)
    {

        //-----------------------------------
        // Tercero 
        $no_es_cliente_generico = false;


        if ($this->fk_tercero != '' && $this->fk_tercero != 0) {
            $no_es_cliente_generico = true;
        }




        if ($this->documento == "fi_europa_facturas") {

            if ($this->fk_tercero > 0) {
                $this->tipo_aeat = "F1";
                $this->documento_configuracion_serie;
            } else {
                $this->tipo_aeat = "F2";
                $this->documento_configuracion_serie = "Fac-Simplificada-";
            }
        }
        $series       =   $this->Obtener_Series($serie);


        $resultado = strpos($this->referencia, 'Borrador');


        if ($resultado !== FALSE) {

            $nuevo_serie_id                 =  $series['id'];
            $plantilla_fk                 =  $series['plantilla_fk'];
            $nuevo                          =  $series['referencia'];
            if ($this->documento == "fi_europa_facturas") {
                $nuevo_a_usar_referencia_serie  =  $series['tipo_aeat'];
            } else {
                $nuevo_a_usar_referencia_serie  =  'O' . $series['id'];
            }

            $this->Aumentar_Series($series['id']);
        } else {
            $nuevo                          = $this->referencia;
            $nuevo_a_usar_referencia_serie  = $this->referencia_serie;
        }

        // TODO LIST 
        // sI NO ENCUENTRA NADA DEBE REVENTAR ERROR Y CORREGUIRLO  

        /* Obtengo el detalle de la Empresa desde LICENCIAS */
        $provincia_empresa = $this->Entidad->direccion_fk_provincia;

        require_once ENLACE_SERVIDOR . 'mod_utilidad/object/utilidades.object.php';
        $Utilidades = new Utilidades($this->db);
        $ubigeo_seleccionado = $Utilidades->obtener_ubigeo_seleccionado($provincia_empresa);


        $direccion_empresa = $this->Entidad->nombre_direccion . ', ' . $ubigeo_seleccionado[0]->nombre_provincia . ', ' . $ubigeo_seleccionado[0]->nombre_comunidad_autonoma . ', ' . $ubigeo_seleccionado[0]->nombre_pais . ', ' . $this->Entidad->codigo_postal;

        // ,  fk_tercero_direccion      =   (SELECT CONCAT(IFNULL(pais,''), '-', IFNULL(provincia,''), '-', IFNULL(codigo_postal,''), '-', IFNULL(direccion,'')) FROM fi_terceros WHERE rowid = :fk_tercero)

        // Si es la tabla facturas analiza si la empresa tiene configurado el tema verifactu y marca la factura como No - dev - PRoduccion en verifactum
        $verifactum_produccion = ($this->documento == "fi_europa_facturas") ? " , verifactum_produccion = {$this->Entidad->verifactum_produccion}  " : '';

        $sql = "update  
                    {$this->documento}   
            set  
            
                estado                 = 1 
            ,   fk_usuario_validar     = :fk_usuario_validar 
            ,   referencia             = :referencia 
            ,   tipo                   = :tipo 
            ,   referencia_serie       = :referencia_serie
            ,   fk_usuario_validar_fecha  = NOW()  
            ,   xml_IDEmisorFactura       = :xml_IDEmisorFactura

            ,  fk_tercero_txt             =  (SELECT (CASE tipo WHEN 'fisica' THEN CONCAT(nombre, ' ',apellidos) ELSE nombre  END) AS nombre FROM fi_terceros WHERE rowid = :fk_tercero)
            ,  fk_tercero_identificacion  =  (SELECT cedula FROM fi_terceros WHERE rowid = :fk_tercero)
            ,  fk_tercero_telefono       =   (SELECT telefono FROM fi_terceros WHERE rowid = :fk_tercero)
            ,  fk_tercero_email          =   (SELECT email FROM fi_terceros WHERE rowid = :fk_tercero)
            
            ,  fk_tercero_direccion      =   (SELECT CONCAT(IFNULL(pais.nombre,''), '-', IFNULL(ccaa.nombre,''), '-', IFNULL(prov.provincia,''), '-', IFNULL(t.codigo_postal,''), '-', IFNULL(t.direccion,'')) 
                            FROM fi_terceros t
                            LEFT JOIN " . $_ENV['DB_NAME_UTILIDADES_APOYO'] . ".diccionario_paises pais ON pais.rowid = t.fk_pais
                            LEFT JOIN " . $_ENV['DB_NAME_UTILIDADES_APOYO'] . ".diccionario_comunidades_autonomas_provincias prov ON prov.id = t.direccion_fk_provincia
                            LEFT JOIN " . $_ENV['DB_NAME_UTILIDADES_APOYO'] . ".diccionario_comunidades_autonomas ccaa ON ccaa.id = t.fk_poblacion WHERE t.rowid = :fk_tercero)
            
            ,  entidad_razonsocial       = :entidad_razonsocial
            ,   entidad_fantasia         = :entidad_fantasia
            ,   entidad_identificacion   = :entidad_identificacion
            ,   entidad_email            = :entidad_email

            ,   entidad_direccion        =   :entidad_direccion
            ,   entidad_telefonofijo     =  :entidad_telefonofijo
            ,   forma_pago_txt           =   (SELECT label FROM diccionario_formas_pago WHERE rowid= :forma_pago) 
            ,   fk_serie_configuracion   = :nuevo_serie_id
            ,  fk_plantilla             = :fk_plantilla

                {$verifactum_produccion}

                where rowid = :rowid  and entidad = :entidad  ";

        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':rowid',   $this->id, PDO::PARAM_INT);
        $dbh->bindValue(':tipo', $nuevo_a_usar_referencia_serie,                                   PDO::PARAM_STR);
        $dbh->bindValue(':referencia',   $nuevo, PDO::PARAM_STR);
        $dbh->bindValue(':referencia_serie',   $nuevo_a_usar_referencia_serie, PDO::PARAM_STR);
        $dbh->bindValue(':fk_usuario_validar',   $usuario, PDO::PARAM_INT);
        $dbh->bindValue(':xml_IDEmisorFactura',   $this->Entidad->numero_identificacion, PDO::PARAM_STR);
        $dbh->bindValue(':fk_tercero',   $this->fk_tercero, PDO::PARAM_INT);
        $dbh->bindValue(':entidad',   $this->entidad, PDO::PARAM_INT);

        $dbh->bindValue(':entidad_razonsocial',   $this->Entidad->nombre_empresa, PDO::PARAM_STR);
        $dbh->bindValue(':entidad_fantasia',   $this->Entidad->nombre_fantasia, PDO::PARAM_STR);
        $dbh->bindValue(':entidad_identificacion',   $this->Entidad->numero_identificacion, PDO::PARAM_STR);
        $dbh->bindValue(':entidad_email',   $this->Entidad->correo_electronico, PDO::PARAM_STR);

        $dbh->bindValue(':entidad_direccion',   $direccion_empresa, PDO::PARAM_STR);
        $dbh->bindValue(':entidad_telefonofijo',   $this->Entidad->telefono_fijo, PDO::PARAM_STR);
        $dbh->bindValue(':forma_pago',   $this->forma_pago, PDO::PARAM_INT);

        if(isset($nuevo_serie_id) ){
            $dbh->bindValue(':nuevo_serie_id',   $nuevo_serie_id, PDO::PARAM_INT);
            $dbh->bindValue(':fk_plantilla',   $plantilla_fk, PDO::PARAM_INT);
        }else{
            $dbh->bindValue(':nuevo_serie_id',   NULL);
        }

        $a = $dbh->execute();



        // Actualizar el saldo_credito del Cliente de la Factura

        unset($resultado);


        $resultado['id']      =   $this->id;
        $resultado['exito']   =   $a;
        $resultado['mensaje'] =   "Factura Validada con Exito";
        if (!$a) {
            $resultado['exito'] = 0;
            $resultado['mensaje'] =  implode(", ", $dbh->errorInfo());
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(", ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . ' && ' . json_encode($a);
            $this->Error_SQL();
        } else {


            $this->registrar_log_documento($usuario, 1, "Documento Validado");
        }




        // Actualizar el saldo_credito del Cliente de la Factura
        // Actualizar tambien el campo emitio_facturas. Para indicar que tiene al menos 1 validada
        // Si no es un Cliente Genérico
        if ($resultado !== FALSE && $no_es_cliente_generico) {

            $sql_tercero = "UPDATE fi_terceros SET saldo_credito = (saldo_credito - :total ), emitio_facturas=1 WHERE rowid = :fk_tercero AND entidad = :entidad";
            $stmt_tercero = $this->db->prepare($sql_tercero);
            $stmt_tercero->bindValue(':total', $this->total, PDO::PARAM_STR);
            $stmt_tercero->bindValue(':fk_tercero', $this->fk_tercero, PDO::PARAM_INT);
            $stmt_tercero->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
            $stmt_tercero->execute();

            if (!$stmt_tercero) {
                $this->sql     =   $sql_tercero;
                $this->error   =   implode(", ", $dbh->errorInfo());
                $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
                $this->Error_SQL();
            }
        }

        return;
    }


    public function cambiar_estado($nuevo_estado)
    {
        // Actualiza el estado del documento
        $sql = "UPDATE {$this->documento} SET estado = :nuevoestado WHERE entidad = :entidad and  rowid = :rowid ";
        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $dbh->bindValue(':rowid', $this->id, PDO::PARAM_INT);
        $dbh->bindValue(':nuevoestado', $nuevo_estado, PDO::PARAM_INT);
        $this->estado = $nuevo_estado;
        $c = $dbh->execute();

        if (!$c) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();
        }
    }

    /*********************************************************************
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     *     Manejo de Series 
     * 
     * 
     * 
     * 
     * 
     * 
     ********************************************************************/


    public function enmascarar_($siguiente_documento, $mascara)
    {

        $mascara = str_replace("_Y_", date("Y"), $mascara);
        $mascara = str_replace("#", $siguiente_documento, $mascara);

        return $mascara;
    }


    public function Aumentar_Series($rowid)
    {

        $sql = "
                update 
                    fi_europa_facturas_configuracion          
                set 
                    siguiente_documento = (siguiente_documento + 1 )
                where   
                      rowid           = :rowid
              AND     entidad         = :entidad 
              AND     tipo            = '{$this->documento}' 
              
              ";


        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $dbh->bindValue(':rowid', $rowid, PDO::PARAM_INT);

        $c = $dbh->execute();

        if (!$c) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();
        }
    } // Obtener_Series




    public function Obtener_Series($series = NULL)
    {

        if (!is_null($series) and !empty($series)) {
            $where_series = " and rowid  = :rowid ";
        }

        if ($this->documento == "fi_europa_facturas") {
            $where_tipo_aeat = " and tipo_aeat = '{$this->tipo_aeat}' ";
        }

        $sql = "select
                    rowid               ,     
                    siguiente_documento ,
                    siguiente_borrador  ,
                    fk_serie_modelo     ,
                    plantilla_fk        ,
                    tipo_aeat           

                    from fi_europa_facturas_configuracion
                    
                    WHERE 
                            
                        serie_activa    = 1 
                AND     borrado         = 0 
                AND     entidad         = :entidad 
                AND     tipo            = '{$this->documento}'

                $where_series 

                $where_tipo_aeat

                        order by  serie_por_defecto DESC 
                ";


        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);

        if (!is_null($series) and !empty($series)) {
            $dbh->bindValue(':rowid', $series, PDO::PARAM_INT);
        }

        $c = $dbh->execute();

        if (!$c) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();
            $resultado['referencia'] =   "Error Asignando Referencia";
            return $resultado;
        }


        $data = $dbh->fetch(PDO::FETCH_ASSOC);


        // Si no EXISTE la serie creamos una GEnerica para este documento    
        if (empty($data)) {
            require(ENLACE_SERVIDOR . "mod_europa_facturacion_series/object/configuracion_series_object.php");
            $Serie = new Series($this->db, $this->entidad);

            $Serie->entidad             = $this->entidad;
            $Serie->tipo_aeat           =  (empty($this->tipo_aeat)) ? "otros_no_aeat" : $this->tipo_aeat;
            $Serie->tipo                =  trim($this->documento);
            $Serie->siguiente_documento = 1;
            $Serie->siguiente_borrador  = 1;
            $Serie->fk_serie_modelo      = $this->documento_configuracion_serie . "_Y_-#";
            $Serie->serie_reinicio_anual = 1;
            $Serie->serie_por_defecto    = 1;
            $Serie->serie_activa         = 1;
            $Serie->serie_descripcion    = "Serie {$this->documento} Creada Automáticamente {$Serie->fk_serie_modelo} ";
            $Serie->creado_fk_usuario    = $this->creado_fk_usuario;
            $control = $Serie->crear_serie();

            if ($control['exito']) {
                $resultado['referencia'] =   $this->enmascarar_($Serie->siguiente_documento,  $Serie->fk_serie_modelo);
                $resultado['id']         =   $control['id'];
                $resultado['tipo_aeat']         =   $control['tipo_aeat'];
            } else {
                $resultado['referencia'] =   "Error Asignando Referencia";
            }
        } else {
            $resultado['referencia'] =   $this->enmascarar_($data['siguiente_documento'],  $data['fk_serie_modelo']);
            $resultado['id']         =   $data['rowid'];
            $resultado['plantilla_fk']         =   $data['plantilla_fk'];
            $resultado['tipo_aeat']  =   $data['tipo_aeat'];
        }


        return $resultado;
    } // Obtener_Series



    /*********************************************************************
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     *      Movimientos De Ligado de documentos 
     * 
     * 
     * 
     * 
     * 
     * 
     ********************************************************************/
    public function ligar_documento($Documento, $usuario)
    {


        $sql = "insert into fi_europa_documentos_movimientos
                        (
                            `origen_documento`      ,
                            `origen_fk_documento`   ,
                            `destino_documento`     ,
                            `destino_fk_documento`  ,
                            `creado_fecha`          ,
                            `creado_fk_usuario`     
                        )   VALUES  
                        (
                        '{$this->documento}'        ,
                        {$this->id}                 ,
                        '{$Documento->documento}'   ,
                        {$Documento->id}            ,
                        NOW()                       ,
                        {$usuario}
                        )";

        $dbh = $this->db->prepare($sql);
        $c   = $dbh->execute();
        $id_documento_movimiento = $this->db->lastInsertId();

        if (!$c) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();

            $mensaje['mensaje'] = $this->error;
            $mensaje['error'] = 1;
            return $mensaje;
        }





        if ($id_documento_movimiento > 0) {

            // $this->ligar_documento_detalle($id_documento_movimiento, $Documento);
            $mensaje['mensaje'] = "Documento Ligado";
            $mensaje['exito'] = 1;
            $mensaje['documento_origen_id']  =  $this->id;
            $mensaje['documento_destino_id'] =  $Documento->id;
            $mensaje['location'] = $Documento->ver_url . "/" . $Documento->id;
            $mensaje['documento_movimiento_id'] = $id_documento_movimiento;

            return $mensaje;
        } else {
            $mensaje['mensaje'] = "No se generó el Movimiento correctamente";
            $mensaje['error'] = 1;
            return $mensaje;
        }
    }



    public function ligar_documento_detalle($id_documento_movimiento, $Documento)
    {
        $sql = " SELECT origen.rowid,
                    origen.entidad,
                    origen.cantidad,
                    destino.rowid as destino_fk_documento_detalle,
                    destino.cantidad as destino_cantidad
                FROM {$this->documento_detalle} origen  INNER JOIN {$Documento->documento_detalle} destino ON destino.origen_fk_documento_detalle = origen.rowid
                WHERE origen.fk_documento = {$this->id} ";

        $dbh = $this->db->prepare($sql);
        $c   = $dbh->execute();

        if (!$c) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();

            $mensaje['mensaje'] = $this->error;
            $mensaje['error'] = 1;
            return $mensaje;
        }
        while ($result = $dbh->fetch(PDO::FETCH_ASSOC)) {
            $sql = "INSERT INTO fi_europa_documentos_movimientos_detalles 
                (fk_documento_movimiento, origen_documento, origen_fk_documento, origen_fk_documento_detalle, origen_cantidad, destino_documento, destino_fk_documento, destino_fk_documento_detalle, destino_cantidad, creado_fecha, creado_fk_usuario) 
                VALUES 
                (:fk_documento_movimiento, :origen_documento, :origen_fk_documento, :origen_fk_documento_detalle, :origen_cantidad, :destino_documento, :destino_fk_documento, :destino_fk_documento_detalle, :destino_cantidad, :creado_fecha, :creado_fk_usuario)";

            $stmt = $this->db->prepare($sql);

            // Bind de parámetros
            $stmt->bindValue(':fk_documento_movimiento', $id_documento_movimiento, PDO::PARAM_INT);
            $stmt->bindValue(':origen_documento', $this->documento, PDO::PARAM_STR);
            $stmt->bindValue(':origen_fk_documento', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':origen_fk_documento_detalle', $result['rowid'], PDO::PARAM_INT);
            $stmt->bindValue(':origen_cantidad', $result['cantidad'], PDO::PARAM_STR);
            $stmt->bindValue(':destino_documento', $Documento->documento, PDO::PARAM_STR);
            $stmt->bindValue(':destino_fk_documento', $Documento->id, PDO::PARAM_INT);
            $stmt->bindValue(':destino_fk_documento_detalle', $result['destino_fk_documento_detalle'], PDO::PARAM_INT);
            $stmt->bindValue(':destino_cantidad', $result['destino_cantidad'], PDO::PARAM_STR);
            $stmt->bindValue(':creado_fecha', date('Y-m-d H:i:s'), PDO::PARAM_STR);
            $stmt->bindValue(':creado_fk_usuario', $this->entidad, PDO::PARAM_INT);

            // Ejecución del query
            $resultInsert = $stmt->execute();


            /* Actualizo el estado del documento destino. Dependiendo de la cantidad, es completa o parcial */
            if ($result['destino_cantidad'] == $result['cantidad']) {
                $estado_detalle = 1;
            }
            if ($result['destino_cantidad'] < $result['cantidad']) {
                $estado_detalle = 0;
            }
            $sqlUpdate = "UPDATE {$Documento->documento_detalle} 
                        SET fk_estado_detalle = {$estado_detalle} 
                        WHERE rowid = {$result['destino_fk_documento_detalle']} AND fk_documento = {$Documento->id} ";
            $dbhUpdate = $this->db->prepare($sqlUpdate);
            $resultUpdate = $dbhUpdate->execute();

            if (!$resultUpdate) {
                $this->sql     =   $sqlUpdate;
                $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
                $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
                $this->Error_SQL();
            }
            /* Actualizo el estado del documento destino. Dependiendo de la cantidad, es completa o parcial */
        }

        return $this->db->lastInsertId(); // Retornar el ID del registro insertado
    }



    public function eliminar_movimientos($id_detalles)
    {

        $sql = "delete from fi_europa_documentos_movimientos
                            where 
                            (  `origen_documento`     =   '{$this->documento}'  
                        AND    `origen_fk_documento`  =   '{$this->id}'   )

                        OR
                            (  `destino_documento`     =   '{$this->documento}'  
                        AND    `destino_fk_documento`  =   '{$this->id}'   )
                        
                        ";

        $dbh = $this->db->prepare($sql);
        $c   = $dbh->execute();

        if (!$c) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();

            $mensaje['mensaje'] = $this->error;
            $mensaje['error'] = 1;
            return $mensaje;
        }

        $in = implode(", ", $id_detalles);


        $sql = "delete from fi_europa_documentos_movimientos_detalles
        where 
        (  `origen_documento`     =   '{$this->documento}'  
        AND    `origen_fk_documento`  =   '{$this->id}'  
        AND    origen_fk_documento_detalle  in ({$in})   )

        OR
            (  `destino_documento`     =   '{$this->documento}'  
        AND    `destino_fk_documento`  =   '{$this->id}' 
        AMD    `destino_fk_documento_detalle` in ({$in})   )
        ";

        $dbh = $this->db->prepare($sql);
        $c   = $dbh->execute();

        if (!$c) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();

            $mensaje['mensaje'] = $this->error;
            $mensaje['error'] = 1;
            return $mensaje;
        }


        $mensaje['error'] = $c ? 1 : 0;

        return $mensaje;
    } // Eliminar Ligues 




    /***********************************************************
     * ***
     * 
     * 
     * 
     *  funciones solo para el LOG de movimientos 
     *  Esto noi tiene que ver con las unciones de documenthos si no con el documento propiaamente
     * 
     * 
     * 
     ******************************************************************/

    public function registrar_log_documento($usuario,  $estado, $comentario = "")
    {
        $sql = "INSERT INTO fi_europa_documentos_log
                (
                    `entidad`,
                    `documento`,
                    `estado`,
                    `fk_usuario`,
                    `fecha` ,
                    comentario ,
                    documento_fk 
                ) VALUES  
                (
                    :entidad,
                    '{$this->nombre_clase}',
                    :estado             ,
                    :usuario            ,
                    NOW()               ,
                    :comentario         ,
                    '{$this->id}'

                )";

        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':entidad', $this->entidad,   PDO::PARAM_INT);
        $dbh->bindValue(':usuario', $usuario,   PDO::PARAM_INT);
        $dbh->bindValue(':estado', $estado,   PDO::PARAM_INT);
        $dbh->bindValue(':comentario', $comentario,   PDO::PARAM_STR);


        $c = $dbh->execute();

        if (!$c) {
            $this->sql = $sql;
            $this->error = implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla fi_europa_documentos_log";
            $this->Error_SQL();

            $mensaje['mensaje'] = $this->error;
            $mensaje['error'] = 1;
            return $mensaje;
        }

        $mensaje['mensaje'] = "Registro insertado correctamente";
        $mensaje['error'] = 0;
        return $mensaje;
    }

    public function actualizar_plantilla($fk_plantilla)
    {
        $sql = "UPDATE {$this->documento} SET
                    fk_plantilla = :fk_plantilla
                WHERE rowid = {$this->id} AND entidad = :entidad";
        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':entidad', $this->entidad,   PDO::PARAM_INT);
        $dbh->bindValue(':fk_plantilla', $fk_plantilla,   PDO::PARAM_INT);

        $c = $dbh->execute();

        if (!$c) {
            $this->sql = $sql;
            $this->error = implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla fi_europa_documentos_log";
            $this->Error_SQL();

            $mensaje['mensaje'] = $this->error;
            $mensaje['error'] = 1;
            return $mensaje;
        }

        $mensaje['mensaje'] = "Registro actualizado correctamente";
        $mensaje['error'] = 0;
        return $mensaje;
    }

    public function generar_datos_correo()
    {
        /////////////PREPARANDO LOS DATOS PARA EL ASUNTO Y EL CUERPO DE CORREO 
        $cuerpoCorreo = "";
        if ($this->Entidad->configuracion["email_body_" . strtolower($this->nombre_clase)]) {
            $cuerpoCorreo = $this->Entidad->configuracion["email_body_" . strtolower($this->nombre_clase)];
        }

        $monedita = "EUR";
        $asunto = $this->entidad_razonsocial . ' - ' . $this->documento_txt['singular'] . ": " . $this->referencia;

        $cuerpoCorreo = str_replace("[Nombre del Cliente]", $this->fk_tercero_txt,                         $cuerpoCorreo);
        $cuerpoCorreo = str_replace("[Número de documento]",     $this->referencia,                             $cuerpoCorreo);
        $cuerpoCorreo = str_replace("[tipo_documento]",     strtolower($this->documento_txt['singular']),                             $cuerpoCorreo);
        $cuerpoCorreo = str_replace("MONEDA",         $monedita,                                     $cuerpoCorreo);
        $cuerpoCorreo = str_replace("[Importe en euros]",            $monedita . ' ' . numero_simple($this->total),                $cuerpoCorreo);
        $cuerpoCorreo = str_replace("[Fecha]",         date('d-m-Y', strtotime($this->fecha)),         $cuerpoCorreo);
        $cuerpoCorreo = str_replace("[Fecha Vencimiento]",         date('d-m-Y', strtotime($this->fecha_vencimiento)),         $cuerpoCorreo);
        $cuerpoCorreo = str_replace("[Nombre de tu empresa]",         $this->entidad_razonsocial,         $cuerpoCorreo);
        $cuerpoCorreo = str_replace("[Dirección de tu empresa]",         $this->entidad_direccion,         $cuerpoCorreo);
        $cuerpoCorreo = str_replace("[Correo electrónico]",         $this->entidad_email,         $cuerpoCorreo);
        $cuerpoCorreo = str_replace("[Teléfono]",         $this->entidad_telefonofijo,                     $cuerpoCorreo);
        if ($this->fk_tercero == null) {
            // Expresión regular para encontrar y eliminar el div con la clase "contact-info"
            $pattern = '/<div class="contact-info">.*?<\/div>/s';
            $cuerpoCorreo = preg_replace($pattern, '', $cuerpoCorreo);
        }

        $titulo = "Enviar  <i class='fa fa-fw fa-money'></i> <b> " . $this->documento_txt['singular'] . " " . $this->referencia . "</b>   Por Email";
        $empresa = $this->entidad_razonsocial;

        $retorno = array(
            'titulo' => $titulo,
            'asunto' => $asunto,
            'cuerpo' => $cuerpoCorreo,
            'empresa' => $empresa
        );

        return $retorno;
    }
} // fin del OBJETO 