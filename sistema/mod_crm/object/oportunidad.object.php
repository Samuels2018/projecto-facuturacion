<?php

require_once(ENLACE_SERVIDOR . "mod_entidad/object/Entidad.object.php");
include_once(ENLACE_SERVIDOR . "mod_seguridad/object/seguridad.object.php");

class Oportunidad extends  Seguridad
{

    private $db;
    public  $entidad;
    public $Entidad;

    public $diccionario_estado_cotizaciones;
    public $rescurso_humano;
    public $PDF_textos;

    public $categorias;
    public $prioridad;
    public $Utilidades;




    // Función __construct que acepta una conexión a la base de datos
    public function __construct($db, $entidad)
    {
        $this->db = $db;

        if (empty($entidad)) {
            echo "Debe indicarse la entidad antes de continuar ";
            exit(1);
        }
        parent::__construct();  // Esto inicializa la clase SEGURIDAD

        $this->entidad = $entidad;
        $this->Entidad = new Entidad($db, $entidad);
        $this->configuracion_empresa();
    } // Funcion Constructor

    public function configuracion_empresa()
    {
        return $this->configuracion = $this->Entidad->configuracion_empresa();
    }


    public function fetch($id)
    {


        $sql = "SELECT 
        cat.etiqueta AS categoria_etiqueta, 
        fo.rowid,
        fo.entidad,
        fo.fk_funnel,
        fo.fk_funnel_detalle,
        fo.fk_contacto,
        fo.fk_tercero,
        fo.fk_tercero_contacto,
        fo.fk_estado,
        fo.fk_categoria,
        fo.fk_prioridad,
        fo.fecha,

        CASE
            WHEN fo.fecha_cierre NOT REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'
            OR STR_TO_DATE(fo.fecha_cierre, '%Y-%m-%d') IS NULL
            OR YEAR(fo.fecha_cierre) < 1000
            THEN NULL
            ELSE fo.fecha_cierre
        END AS fecha_cierre,

        fo.tiempo_entrega,
        fo.validez_oferta,
        fo.consecutivo,
        fo.etiqueta,
        fo.nota,
        fo.fk_usuario_asignado,
        fo.fk_usuario_modificado,
        fo.modificado_fecha,
        fo.creado_fecha,
        fo.creado_fk_usuario,
        fo.borrado,
        fo.borrado_fecha,
        fo.borrado_fk_usuario,
        fo.tags,
        fo.posicion_funnel,
        fo.importe,
        fo.tipo_oferta,
        fo.campo_extra_1,
        fo.campo_extra_2,
        fo.campo_extra_3,
        fo.campo_extra_4,
        fo.campo_extra_5,
        fo.campo_extra_6,
        fo.campo_extra_7,
        fo.campo_extra_8,
        fo.campo_extra_9,
        fo.campo_extra_10,
        CONCAT(fu.nombre, ' ', fu.apellidos) AS usuario_asignado, 
        
        CASE 
                WHEN ft.tipo = 'fisica' THEN CONCAT(ft.nombre, ' ', ft.apellidos)
                ELSE ft.nombre 
        END AS cliente_txt,

        CONCAT(crm.nombre, ' ', crm.apellidos) AS contacto,
        crm.telefono AS contacto_telefono,
        crm.email as contacto_correo,
        (SELECT GROUP_CONCAT(fos.fk_producto SEPARATOR ',') FROM fi_oportunidades_servicio fos WHERE fos.fk_documento = fo.rowid ) AS servicios ,

      
        
        detalle.etiqueta as funnel_detalle_txt ,  
        detalle.estilo   as funnel_detalle_estilo,     

        fo.total,
        fo.subtotal_pre_retencion,
        fo.impuesto_iva,
        fo.impuesto_retencion_irpf,
        fo.impuesto_iva_equivalencia,
        fo.IVA_21,
        fo.IVA_10,
        fo.IVA_4,
        fo.IVA_0,
        fo.RE_5_2,
        fo.RE_1_4,
        fo.RE_0_5,
        fo.RE_0_75

        FROM fi_oportunidades fo 
        LEFT JOIN diccionario_crm_oportunidades_categorias cat ON cat.rowid = fo.fk_categoria
        LEFT JOIN fi_usuarios fu ON fo.fk_usuario_asignado = fu.rowid 
        LEFT JOIN fi_terceros_crm_contactos crm ON fo.fk_contacto = crm.rowid 
        LEFT JOIN fi_terceros ft ON fo.fk_tercero = ft.rowid 
        left join fi_funnel_detalle   detalle on detalle.rowid = fo.fk_funnel_detalle 

         WHERE 
        fo.rowid = :rowid 
        AND fo.entidad = :entidad AND fo.borrado = 0
        ";

        $dbh = $this->db->prepare($sql);
        $dbh->bindParam(':rowid', $id);
        $dbh->bindParam(':entidad', $this->entidad);
        $dbh->execute();
        $row = $dbh->fetch(PDO::FETCH_ASSOC);

        // Asignar las propiedades de la clase con los valores de la fila
        $this->rowid = $row['rowid'];
        // $this->entidad = $row['entidad'];
        $this->fk_funnel = $row['fk_funnel'];
        $this->fk_contacto = $row['fk_contacto'];
        $this->fk_tercero = $row['fk_tercero'];
        $this->fk_tercero_contacto = $row['fk_tercero_contacto'];
        $this->fk_estado = $row['fk_estado'];
        $this->etiqueta = $row['etiqueta'];
        $this->nota = $row['nota'];
        $this->fk_modificado_fecha = $row['fk_modificado_fecha'];
        $this->fk_usuario_asignado = $row['fk_usuario_asignado'];
        $this->fk_usuario_modificado = $row['fk_usuario_modificado'];
        $this->creado_fecha = $row['creado_fecha'];
        $this->creado_fk_usuario = $row['creado_fk_usuario'];
        $this->borrado = $row['borrado'];
        $this->borrado_fecha = $row['borrado_fecha'];
        $this->borrado_fk_usuario = $row['borrado_fk_usuario'];
        $this->usuario_asignado = $row['usuario_asignado'];
        $this->usuario_modificado = $row['usuario_modificado'];
        $this->cliente_txt = $row['cliente_txt'];
        $this->contacto = $row['contacto'];
        $this->contacto_telefono = $row['contacto_telefono'];
        $this->contacto_correo = $row['contacto_correo'];
        $this->cotizacion_tiempo_entrega = $row['tiempo_entrega'];
        $this->cotizacion_validez_oferta = $row['validez_oferta'];
        $this->tags = $row['tags'];
        $this->servicios = $row['servicios'];
        $this->fk_funnel_detalle = $row['fk_funnel_detalle'];
        $this->consecutivo = $row['consecutivo'];

        $this->funnel_detalle_txt           = $row['funnel_detalle_txt'];
        $this->funnel_detalle_estilo        = $row['funnel_detalle_estilo'];
        $this->fecha                        = $row['fecha'];
        // $this->fecha                        = isset($row['fecha']) && !is_null($row['fecha']) ? date('d-m-Y',strtotime($row['fecha'])) : '';

        $this->importe                      = $row['importe'];

        $this->id                           = $row['rowid'];
        $this->validez_oferta   = $row['validez_oferta'];
        $this->categorias = $row['fk_categoria'];
        $this->fk_categoria = $row['fk_categoria'];
        $this->fk_prioridad = $row['fk_prioridad'];
        $this->categorias_etiqueta = $row['categoria_etiqueta'];
        $this->importe_dolarizado = $row['importe_dolarizado'] ?? '0.0';
        // $this->fecha_cierre = $row['fecha_cierre'];
        $this->fecha_cierre = $row['fecha_cierre'];
        // $this->fecha_cierre = isset($row['fecha_cierre']) && !is_null($row['fecha_cierre']) ? date('d-m-Y',strtotime($row['fecha_cierre'])) : 'Sin fecha de cierre';

        $this->total = $row['total'];
        $this->subtotal_pre_retencion = $row['subtotal_pre_retencion'];
        $this->impuesto_iva = $row['impuesto_iva'];
        $this->impuesto_retencion_irpf = $row['impuesto_retencion_irpf'];
        $this->impuesto_iva_equivalencia = $row['impuesto_iva_equivalencia'];
        $this->IVA_21 = $row['IVA_21'];
        $this->IVA_10 = $row['IVA_10'];
        $this->IVA_4 = $row['IVA_4'];
        $this->IVA_0 = $row['IVA_0'];
        $this->RE_5_2 = $row['RE_5_2'];
        $this->RE_1_4 = $row['RE_1_4'];
        $this->RE_0_5 = $row['RE_0_5'];
        $this->RE_0_7 = $row['RE_0_7'];

        return $row['rowid'];
    }


    public function obtener_datos_oportunidad($id)
    {

        $sql = "SELECT 
        cat.etiqueta AS categoria_etiqueta, 
        fo.*, 
        CONCAT(fu.nombre, ' ', fu.apellidos) AS usuario_asignado, 
        
        CASE 
                WHEN ft.tipo = 'fisica' THEN CONCAT(ft.nombre, ' ', ft.apellidos)
                ELSE ft.nombre 
        END AS cliente_txt,

        CONCAT(crm.nombre, ' ', crm.apellidos) AS contacto,
        crm.telefono AS contacto_telefono,
        crm.email as contacto_correo,
        (SELECT GROUP_CONCAT(fos.fk_producto SEPARATOR ',') FROM fi_oportunidades_servicio fos WHERE fos.fk_oportunidad = fo.rowid ) AS servicios ,


        detalle.etiqueta as funnel_detalle_txt ,  
        detalle.estilo   as funnel_detalle_estilo      


        FROM fi_oportunidades fo 
        LEFT JOIN diccionario_crm_oportunidades_categorias cat ON cat.rowid = fo.fk_categoria
        LEFT JOIN fi_usuarios fu ON fo.fk_usuario_asignado = fu.rowid 
        LEFT JOIN fi_terceros_crm_contactos crm ON fo.fk_contacto = crm.rowid 
        LEFT JOIN fi_terceros ft ON fo.fk_tercero = ft.rowid 
        left join fi_funnel_detalle   detalle on detalle.rowid = fo.fk_funnel_detalle 

         WHERE 
        fo.rowid = :rowid 
        AND fo.entidad = :entidad
        ";

        $dbh = $this->db->prepare($sql);
        $dbh->bindParam(':rowid', $id);
        $dbh->bindParam(':entidad', $this->entidad);
        $dbh->execute();
        $row = $dbh->fetch(PDO::FETCH_ASSOC);

        return $row;
    }



    /******************************************************************
     * 
     * 
     *                          Funcion Crear 
     * 
     * 
     *********************************************************************/

    public function Crear()
    {


        $sql = " SELECT COUNT(*) + 1 AS total_oportunidades FROM fi_oportunidades  WHERE entidad = :fk_entidad and  YEAR(creado_fecha) = YEAR(CURDATE()); ";
        $db = $this->db->prepare($sql);
        $db->bindValue(":fk_entidad", $this->entidad,  PDO::PARAM_INT);
        $result =  $db->execute();
        $datos  =  $db->fetch(PDO::FETCH_OBJ);

        $consecutivo  = substr("000000" . ($datos->total_oportunidades + 1), -5) . "-" . date("Y");


        $sql = "INSERT INTO fi_oportunidades (
                        consecutivo ,
                        fk_tercero, 
                        fk_tercero_contacto, 
                        nota,
                        fecha,
                        tags,
                        tiempo_entrega,
                        validez_oferta,
                        fk_categoria,
                        fk_prioridad,
                        fk_usuario_asignado,
                        tipo_oferta,
                        creado_fecha      ,
                        fk_funnel         ,
                        fk_funnel_detalle ,
                        entidad,
                        fk_contacto,
                        etiqueta,
                        fk_estado,
                         fecha_cierre
                    ) VALUES (
                        
                        
                        
                        
                        :consecutivo  , 
                        :fk_tercero, 
                        :fk_tercero_contacto, 
                        :nota,
                        :fecha,
                        :tags,
                        :tiempo_entrega,
                        :validez_oferta,
                        :fk_categoria,
                        :fk_prioridad,
                        :fk_usuario_asignado,
                        :tipo_oferta,
                        NOW()                   ,
                        :fk_funnel              ,
                        :fk_funnel_detalle      ,
                        :fk_entidad,
                        :fk_contacto,
                        :etiqueta,
                        1,
                         :fecha_cierre

                    )";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':consecutivo', $consecutivo, PDO::PARAM_STR);
        $stmt->bindValue(':fk_tercero', $this->fk_tercero);
        $stmt->bindValue(':fk_tercero_contacto', $this->fk_contacto);
        $stmt->bindValue(':nota', $this->nota);
        $stmt->bindValue(':fecha', $this->fecha);
        $stmt->bindValue(':fecha_cierre', $this->fecha_cierre);
        //Llamando los campos correctos 
        $stmt->bindValue(':tiempo_entrega', $this->tiempo_entrega);
        $stmt->bindValue(':validez_oferta', $this->validez_oferta);

        $stmt->bindValue(':fk_categoria', $this->fk_categoria);
        $stmt->bindValue(':fk_prioridad', $this->fk_prioridad);
        $stmt->bindValue(':fk_usuario_asignado', $this->fk_usuario_asignado);
        $stmt->bindValue(':tipo_oferta', $this->tipo_oferta);
        $stmt->bindValue(":fk_entidad", $this->entidad,  PDO::PARAM_INT);
        $stmt->bindValue(':fk_funnel_detalle', $this->fk_funnel_detalle);
        $stmt->bindValue(':fk_funnel', $this->fk_funnel);
        $stmt->bindValue(':fk_contacto', $this->fk_contacto);
        $stmt->bindValue(':etiqueta', $this->etiqueta);
        $tags = (is_array($this->tags)) ? implode(",", $this->tags) : $this->tags;
        $stmt->bindValue(':tags', $tags, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $idCreado =  $this->db->lastInsertId();
            $this->id =  $idCreado;
            $respuesta = ['error' => 0, 'id' => $idCreado, 'mensaje_txt' => 'Oportunidad Creada Con Exito', 'creada' => 1];
        } else {
            $respuesta = ['error' => 1, 'mensaje_txt' => $stmt->errorInfo()];
            $this->sql = $sql;
            $this->error = implode(", ", $stmt->errorInfo()) . implode(", ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }



        $this->Actividad->fk_oportunidad                =       $idCreado;
        $this->Actividad->fk_diccionario_actividad      =       10;
        $this->Actividad->vencimiento_fecha                =       date("Y-m-d");
        $this->Actividad->creado_usuario                =       $this->creado_fk_usuario;
        $this->Actividad->comentario                    =       "Creada";
        $this->Actividad->fk_usuario_asignado           =       $this->creado_fk_usuario;
        $this->Actividad->fk_estado                     =       1;
        $this->Actividad->comentario_cierre             =       "";
        $this->Actividad->tipo                          =       "timeline";
        $this->Actividad->guardarTareaOportunidad();

        /*
            $this->insertar_a_medida_cisma_cotizaciones_recurso_humano();
            $this->insertar_a_medida_cisma_cotizaciones_PDF();
        */



        return $respuesta;
    }



    public function cotizaciones_oportunidad_aceptada($fk_oportunidad)
    {
        $sql = 'SELECT fo.fk_oportunidad,  fo.fk_cotizacion, fc.estado as estado_cotizacion FROM fi_oportunidades_actividades fo

            LEFT JOIN fi_cotizaciones fc on fc.rowid = fo.fk_cotizacion
            where fo.fk_oportunidad = :fk_oportunidad and fo.entidad = :entidad and fo.fk_cotizacion > 0 and fc.estado = 1';

        $db = $this->db->prepare($sql);
        $db->bindValue(":entidad",  $this->entidad);
        $db->bindValue(":fk_oportunidad",  $fk_oportunidad);
        $db->execute();
        $result = $db->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }


    public function cotizaciones_oportunidad_todas($fk_oportunidad)
    {
        $sql = 'SELECT fo.fk_oportunidad,  fo.fk_cotizacion, fc.estado as estado_cotizacion FROM fi_oportunidades_actividades fo

            LEFT JOIN fi_cotizaciones fc on fc.rowid = fo.fk_cotizacion
            where fo.fk_oportunidad = :fk_oportunidad and fo.entidad = :entidad and fo.fk_cotizacion > 0';

        $db = $this->db->prepare($sql);
        $db->bindValue(":entidad",  $this->entidad);
        $db->bindValue(":fk_oportunidad",  $fk_oportunidad);
        $db->execute();
        $result = $db->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }




    public function eliminar_oportunidad($id)
    {

        // validacion de que no existan cotizaciones con estado aceptadas
        $conteo_aceptada = count($this->cotizaciones_oportunidad_aceptada($id));
        if ($conteo_aceptada > 0) {
            return ['error' => 1, 'error_txt' => 'Se encontraron: ' . $conteo_aceptada . ' cotizaciones con estado aceptado'];
            die();
        } else {
            $array_borrados = array();
            $data = $this->cotizaciones_oportunidad_todas($id);
            foreach ($data as $item) {
                if ($item->estado_cotizacion != 1) {
                    // incluir objeto cotizacion
                    include_once(ENLACE_SERVIDOR . "mod_cotizaciones/object/cotizaciones.object.php");
                    // instanciar objeto e invocar metodo eliminar
                    $Cotizacion = new Cotizacion($this->db);
                    $Cotizacion->eliminar($item->fk_cotizacion);
                    // guardar una lista de ids borrado y retornarlos
                    array_push($array_borrados, $item->fk_cotizacion);
                }
            }
        }





        // Consulta SQL para borrar en la tabla 'a_medida_redhouse_cotizaciones'
        $sql = "UPDATE fi_oportunidades SET borrado = 1,
     borrado_fecha = now(), borrado_fk_usuario = :borrado_fk_usuario WHERE rowid = :id";
        try {
            // Inicia la transacción
            $this->db->beginTransaction();
            // Actualización en la tabla a_medida_redhouse_cotizaciones
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':borrado_fk_usuario', $this->borrado_fk_usuario);
            $stmt->execute();
            // Si todo va bien, se confirma la transacción
            $this->db->commit();
            return ['error' => 0, 'id' => $id, 'update' => true, 'cotizaciones_borradas' => $array_borrados];
        } catch (Exception $e) {
            // Si ocurre un error, se revierte la transacción
            $this->db->rollBack();
            // Identifica cuál consulta falló y registra el error
            if ($stmt->errorCode() !== '00000') {
                $this->sql = $sql;
                $this->error = implode(", ", $stmt->errorInfo());
            }
            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();
            return ['error' => 1, 'error_txt' => $this->error, 'cotizaciones_borradas' => $array_borrados];
        }
    }


    /******************************************************************
     * 
     * 
     *                          Funcion Update --->  
     * 
     * 
     *********************************************************************/

    public function Update()
    {
        $query = "UPDATE fi_oportunidades SET 
            fk_tercero              = :fk_tercero, 
            fk_tercero_contacto     = :fk_tercero_contacto, 
            nota                    = :nota,
            fecha                   = :fecha,
            tags                    = :tags,
            tiempo_entrega          = :tiempo_entrega,
            validez_oferta          = :validez_oferta,
            fk_categoria            = :fk_categoria,
            fk_prioridad            = :fk_prioridad,
            fk_usuario_asignado     = :fk_usuario_asignado,
            tipo_oferta             = :tipo_oferta,
            fk_contacto       = :fk_contacto,
            etiqueta       = :etiqueta,
            fk_funnel     = :fk_funnel,
            fk_funnel_detalle = :fk_funnel_detalle,
            fecha_cierre = :fecha_cierre

        WHERE 
            rowid = :rowid";

        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':fk_tercero', $this->fk_tercero);
        $stmt->bindValue(':fk_tercero_contacto', $this->fk_contacto);
        $stmt->bindValue(':nota', $this->nota);
        $stmt->bindValue(':fecha', $this->fecha);
        $stmt->bindValue(':fecha_cierre', $this->fecha_cierre);
        $stmt->bindValue(':tiempo_entrega', $this->tiempo_entrega);
        $stmt->bindValue(':validez_oferta', $this->validez_oferta);
        $stmt->bindValue(':fk_categoria', $this->fk_categoria);
        $stmt->bindValue(':fk_prioridad', $this->fk_prioridad);
        $stmt->bindValue(':fk_usuario_asignado', $this->fk_usuario_asignado);
        $stmt->bindValue(':tipo_oferta', $this->tipo_oferta);
        $stmt->bindValue(':fk_contacto', $this->fk_contacto);
        $stmt->bindValue(':etiqueta', $this->etiqueta);
        $stmt->bindValue(':fk_funnel', $this->fk_funnel);
        $stmt->bindValue(':fk_funnel_detalle', $this->fk_funnel_detalle);




        $tags = ((is_array($this->tags))) ? implode(",", $this->tags) : $this->tags;
        $stmt->bindParam(':tags', $tags, PDO::PARAM_STR);
        $stmt->bindValue(':rowid', $this->id);

        $this->insertar_a_medida_cisma_cotizaciones_recurso_humano();

        if ($stmt->execute()) {
            $respuesta['error']         =  0;
            $respuesta['mensaje_txt']   = "Oportunidad Actualizada con Exito";
            $respuesta['id']            =  $this->id;
            
        } else {
            $respuesta['error']         =  1;
            $respuesta['mensaje_txt']   =  implode(", ", $this->db->errorInfo()) . " - " .   implode(", ", $stmt->errorInfo());
           
        }

        //Actualizado
        $this->Actividad->fk_oportunidad                =       $this->id;
        $this->Actividad->fk_diccionario_actividad      =       10;
        $this->Actividad->vencimiento_fecha                =       date("Y-m-d");
        $this->Actividad->creado_usuario                =       $this->creado_fk_usuario;
        $this->Actividad->comentario                    =       $this->f_funnel_detalle_text;
        $this->Actividad->fk_usuario_asignado           =       $this->creado_fk_usuario;
        $this->Actividad->fk_estado                     =       1;
        $this->Actividad->comentario_cierre             =       "";
        $this->Actividad->tipo                          =       "timeline";
        $this->Actividad->guardarTareaOportunidad();

        return $respuesta;

    } // update 

    /*oBTENER cotizaciones de una oportunidad /  De aqui vamos a obntener solo los qu etiene cotizacion NUMERICA los nulos nmo van a estar */

    public function obtener_cotizaciones_de_oportunidad($fk_oportunidad)
    {
        $sql = "SELECT  fi_cotizaciones.referencia as referencia,  fi_oportunidades_actividades.*  FROM fi_oportunidades_actividades, fi_cotizaciones 
        WHERE fi_oportunidades_actividades.fk_oportunidad = :fk_oportunidad 
        AND fi_oportunidades_actividades.fk_cotizacion = fi_cotizaciones.rowid
        AND fi_oportunidades_actividades.fk_cotizacion <> 'NULL' 
        AND fi_oportunidades_actividades.entidad = :entidad
        ";

        $db = $this->db->prepare($sql);
        $db->bindValue(':fk_oportunidad', $fk_oportunidad);
        $db->bindValue(':entidad', $this->entidad);
        $result =  $db->execute();
        $datos =  $db->fetchAll(PDO::FETCH_OBJ);
        return $datos;
    }





    /****************************************************************
     * 
     * 
     * 
     *  funciones de apoyo de OPORTUNIDADES
     * 
     * 
     * 
     ********************************************************************/

    //Obtener el listado de funnels completo
    public function obtener_listado_funnels()
    {
        $sql = "SELECT  * from fi_funnel where entidad = :fk_entidad
            ";

        $db = $this->db->prepare($sql);
        $db->bindValue(':fk_entidad', $this->entidad);
        $result =  $db->execute();
        $datos =  $db->fetchAll(PDO::FETCH_OBJ);
        return $datos;
    }


    //Obtener un funnel por defecto
    public function funnel_por_defecto()
    {
        $sql = "SELECT  
                rowid  as fk_funnel ,
                (select rowid from fi_funnel_detalle where fk_funnel = (SELECT  rowid from fi_funnel where entidad = :fk_entidad  order by rowid DESC limit 1  ) limit 1   ) as  fk_funnel_detalle
                from fi_funnel where entidad = :fk_entidad  order by rowid DESC limit 1;
            ";

        $db = $this->db->prepare($sql);
        $db->bindValue(':fk_entidad', $this->entidad);
        $result =  $db->execute();
        $datos =  $db->fetch(PDO::FETCH_OBJ);




        $this->fk_funnel            =   $datos->fk_funnel;
        $this->fk_funnel_detalle    =   $datos->fk_funnel_detalle;


        if ($result) {
            return true;
        } else {

            $this->sql = $sql;
            $this->error = implode(", ", $db->errorInfo()) . implode(", ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
            return false;
        }
    }

    //funcion para obtener tabs de todas las oportunidades de la entidad
    public function get_tags_oportunidades()
    {
        $sql = "SELECT tags FROM fi_oportunidades WHERE entidad = :fk_entidad AND borrado = 0";
        $db = $this->db->prepare($sql);
        $db->bindValue(':fk_entidad', $this->entidad, PDO::PARAM_INT);
        $db->execute();

        // Inicializar un array para almacenar todos los tags
        $all_tags = [];

        // Recorrer los resultados de la consulta
        while ($row = $db->fetch(PDO::FETCH_ASSOC)) {
            // Dividir los tags por comas y agregar al array de todos los tags
            $tags = explode(',', $row['tags']);
            $all_tags = array_merge($all_tags, $tags);
        }

        // Eliminar duplicados y limpiar espacios en blanco
        $unique_tags = array_unique(array_map('trim', $all_tags));

        // Retornar el array único de tags
        return $unique_tags;
    }

    public function obtener_listado_estados_funnel()
    {
        $sql = "SELECT  
                color   as estilo     ,
                titulo as etiqueta    ,
                rowid 
          
          FROM fi_funnel  where entidad  = :fk_entidad  and borrado = 0  order by rowid ASC ";
        $db = $this->db->prepare($sql);
        $db->bindValue(':fk_entidad', $this->entidad, PDO::PARAM_INT);

        $result =  $db->execute();

        while ($datos =  $db->fetch(PDO::FETCH_OBJ)) {
            $this->funnel_datos[$datos->rowid]['etiqueta'] = $datos->etiqueta;
            $this->funnel_datos[$datos->rowid]['estilo'] = $datos->estilo;
            $this->funnel_datos[$datos->rowid]['activo'] = $datos->activo;
        }
        return  $this->funnel_datos;
    } // fin de Listado Funnel

    //fUNCION PARA OBTENER el monto total de un detalle de funnel por usuario
    public function obtener_monto_total_usuario_por_detalle_funnel($fk_funnel_detalle, $fk_usuario_asignado, $fecha_desde = '', $fecha_hasta = '')
    {
        $sql = 'SELECT SUM(fi_oportunidades.total) AS suma_total
        FROM fi_oportunidades
        WHERE fk_funnel_detalle = :fk_funnel_detalle     
        AND fk_usuario_asignado = :fk_usuario_asignado';

        if ($fecha_desde != '') {
            if ($fecha_hasta == '') {
                $fecha_hasta = $fecha_desde;
            }

            $sql .= " AND fecha BETWEEN '$fecha_desde' AND '$fecha_hasta'";
        }

        $db = $this->db->prepare($sql);
        $db->bindValue(':fk_funnel_detalle', $fk_funnel_detalle);
        $db->bindValue(':fk_usuario_asignado', $fk_usuario_asignado);
        $db->execute();
        $result = $db->fetch(PDO::FETCH_OBJ);
        $importe_dolarizado = ($result->suma_total === null) ? 0 : $result->suma_total;
        return floatval($importe_dolarizado);
    }


    //Obtener usuarios asignados a una oportunidad 
    public function obtener_lista_usuarios_asignados_oportunidad()
    {

        $sql = 'SELECT fiu.rowid AS usuario_asignado, fiu.nombre AS nombre_tercero, fiu.apellidos AS apellidos_tercero FROM fi_oportunidades fo LEFT JOIN fi_usuarios fiu ON fiu.rowid = fo.fk_usuario_asignado WHERE fo.fk_funnel = :fk_funnel AND fo.entidad = :entidad GROUP BY fiu.rowid';
        $db = $this->db->prepare($sql);
        $db->bindValue(':entidad', $this->entidad);
        $db->bindValue(':fk_funnel', $this->fk_funnel);
        $db->execute();
        $result = $db->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }


    //Obtener listado de usuarios por funnel / aqui buscaremos todos los usuarios pertenecientes
    //a la entidad que tienen registrada oportunidades 
    public function obtener_lista_usuarios_por_funnel($fecha_desde = '', $fecha_hasta = '')
    {
        $sql = 'SELECT fiu.rowid AS usuario_asignado, fiu.nombre AS nombre_tercero, fiu.apellidos AS apellidos_tercero FROM fi_oportunidades fo LEFT JOIN fi_usuarios fiu ON fiu.rowid = fo.fk_usuario_asignado WHERE fo.fk_funnel = :fk_funnel AND fo.entidad = :entidad GROUP BY fiu.rowid';
        $db = $this->db->prepare($sql);
        $db->bindValue(':entidad', $this->entidad);
        $db->bindValue(':fk_funnel', $this->fk_funnel);
        $db->execute();
        $result = $db->fetchAll(PDO::FETCH_OBJ);
        $array_dato = array();
        $array_general = array();
        array_push($array_dato, 'Usuarios');

        //QUI OBTENDREMOS TODOS LOS DETALLES DE UN FUNNEL 
        $estados_funnel_detalle = $this->obtener_listado_estados_funnel_detalle();

        foreach ($result as $key => $value) {
            array_push($array_dato, $result[$key]->nombre_tercero);

            //Aqui haremos otra consulta en donde por usuario obtendremos los Montos por detalle de funnel 
            foreach ($estados_funnel_detalle as $key2 => $value2) {
                $array_general[$estados_funnel_detalle[$key2]['etiqueta']][] = $this->obtener_monto_total_usuario_por_detalle_funnel($estados_funnel_detalle[$key2]['rowid'], $result[$key]->usuario_asignado, $fecha_desde, $fecha_hasta);
            }
        }
        $array_general['usuarios'] = json_encode($array_dato);

        return $array_general;
    }






    //Obtener listado detalles por id de funnel
    public function obtener_listado_estados_funnel_detalle()
    {
        $sql = "SELECT  
                estilo      ,
                etiqueta    ,
                rowid,
                posicion
          
          FROM fi_funnel_detalle  where fk_funnel = :fk_funnel and borrado = 0  ORDER BY posicion  ASC";
        $db = $this->db->prepare($sql);


        if (empty($this->fk_funnel)) {
            $this->funnel_por_defecto();
        }


        $db->bindValue(':fk_funnel', $this->fk_funnel);


        $result =  $db->execute();

        while ($datos =  $db->fetch(PDO::FETCH_OBJ)) {

            $this->estados[$datos->rowid]['etiqueta'] = $datos->etiqueta;
            $this->estados[$datos->rowid]['estilo'] = $datos->estilo;
            $this->estados[$datos->rowid]['activo'] = $datos->activo;
            $this->estados[$datos->rowid]['rowid'] = $datos->rowid;
            $this->estados[$datos->rowid]['posicion'] = $datos->posicion;
        }
        return $this->estados;
    }


    public function obtener_listado_categorias()
    {
        // Inicializa $this->categorias como un array
        $this->categorias = array();

        $sql = "SELECT  
                    activo, 
                    estilo,
                    etiqueta,
                    rowid 
                    FROM `diccionario_crm_oportunidades_categorias` 
                    WHERE entidad = :fk_entidad 
                    ORDER BY rowid ASC";

        $db = $this->db->prepare($sql);
        $db->bindValue(":fk_entidad", $this->entidad);
        $result = $db->execute();

        while ($datos = $db->fetch(PDO::FETCH_OBJ)) {
            $this->categorias[$datos->rowid]['etiqueta'] = $datos->etiqueta;
            $this->categorias[$datos->rowid]['estilo'] = $datos->estilo;
            $this->categorias[$datos->rowid]['activo'] = $datos->activo;
            $this->categorias[$datos->rowid]['rowid'] = $datos->rowid;
        }

        return $this->categorias;
    }


    public function obtener_listado_prioridades()
    {
        // Inicializa $this->categorias como un array
        $this->prioridad = array();

        $sql = "SELECT  
                    activo, 
                    estilo,
                    etiqueta,
                    rowid 
                    FROM `diccionario_crm_oportunidades_prioridades` 
                    WHERE entidad = :fk_entidad 
                    ORDER BY rowid ASC";

        $db = $this->db->prepare($sql);
        $db->bindValue(":fk_entidad", $this->entidad);
        $result = $db->execute();

        while ($datos = $db->fetch(PDO::FETCH_OBJ)) {
            $this->prioridad[$datos->rowid]['etiqueta'] = $datos->etiqueta;
            $this->prioridad[$datos->rowid]['estilo'] = $datos->estilo;
            $this->prioridad[$datos->rowid]['activo'] = $datos->activo;
            $this->prioridad[$datos->rowid]['rowid'] = $datos->rowid;
        }

        return $this->prioridad;
    }





    /*Vamos a obtenber el listado de las oportunidades con futuras Ganancias en el futuro esto para el tema del impulso en el seguimiento de las mismas
    */

    public function obtener_oportunidades_futuras_monto()
    {
        $sql = 'SELECT COALESCE(SUM(fi_oportunidades.total), 0) AS total_importe_dolarizado, COUNT(fi_oportunidades.rowid) AS cantidad_oportunidades FROM fi_oportunidades JOIN fi_funnel_detalle ON fi_funnel_detalle.rowid = fi_oportunidades.fk_funnel_detalle WHERE fi_oportunidades.entidad = :entidad AND fi_funnel_detalle.posicion = 2;
            ';

        $db = $this->db->prepare($sql);
        $db->bindValue(":entidad",  $this->entidad);
        $db->execute();
        $result = $db->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    public function obtener_total_oportunidades_por_tipo($posicion)
    {
        $sql = 'SELECT COALESCE(SUM(fi_oportunidades.total), 0) 
            AS total_importe_dolarizado, COUNT(fi_oportunidades.rowid) 
            AS cantidad_oportunidades
            FROM 
                fi_oportunidades
            JOIN 
                fi_funnel_detalle 
            ON 
                fi_funnel_detalle.rowid = fi_oportunidades.fk_funnel_detalle
            WHERE 
                fi_oportunidades.entidad = :entidad 
            AND 
                fi_funnel_detalle.posicion = :posicion
            AND fi_funnel_detalle.fk_funnel = :fk_funnel;
            ';

        $db = $this->db->prepare($sql);
        $db->bindValue(":entidad",  $this->entidad);
        $db->bindValue(":posicion",  $posicion);
        $db->bindValue(":fk_funnel",  $this->fk_funnel);
        $db->execute();
        $result = $db->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }


    /****************************************************
     * 
     * 
     *   Obtiene el listado de recursos humanos disponibles 
     *   o en su defecto listado para una coti (si se envia el ID coti)
     * 
     * 
     */
    public function obtener_recurso_humano($id = NULL)
    {

        if ($id > 0) {
            $consulta = "
                        select 
                        u.rowid   ,
                        u.avatar , 
                        concat(nombre, ' ', apellidos)  as usuario_txt,
                        fk_usuario  
                        from fi_oportunidades_recurso_humano rh 
                        left join fi_usuarios u on u.rowid = rh.fk_usuario
                        where rh.fk_oportunidad = ?  ";
            $parametro = $id;
        } else {

            $consulta = "
                        select 
                        rowid   ,
                        concat(nombre, ' ', apellidos)  as usuario_txt,
                        rowid as fk_usuario  
                        from fi_usuarios 
                        
                        where entidad =  ?  ";
            $parametro = $this->entidad;
        }



        unset($this->rescurso_humano);

        $stmt = $this->db->prepare($consulta);
        $stmt->bindParam(1, $parametro);


        if ($stmt->execute()) {
        } else {
            return ['error' => 1, 'mensaje_txt' => $stmt->errorInfo()];
        }


        while ($data =  $stmt->fetch(PDO::FETCH_OBJ)) {

            $this->rescurso_humano[$data->rowid]['fk_usuario']  = $data->fk_usuario;
            $this->rescurso_humano[$data->rowid]['usuario_txt'] = $data->usuario_txt;
            $this->rescurso_humano[$data->rowid]['avatar']      = $data->avatar;
        }

        return $this->rescurso_humano;
    }

    public function diccionario_estado_cotizaciones()
    {
        $sql = "SELECT * FROM a_medida_cisma_cotizaciones_estado p ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        while ($data =  $stmt->fetch(PDO::FETCH_OBJ)) {
            $this->diccionario_estado_cotizaciones[$data->rowid]['etiqueta'] = $data->etiqueta;
            $this->diccionario_estado_cotizaciones[$data->rowid]['estilo'] = $data->estilo;
            $this->diccionario_estado_cotizaciones[$data->rowid]['activo'] = $data->activo;
        }
    }



    public function insertar_a_medida_cisma_cotizaciones_recurso_humano()
    {

        $stmt = $this->db->prepare("DELETE FROM fi_oportunidades_recurso_humano WHERE fk_oportunidad = :cotizacion ");
        $stmt->bindValue(':cotizacion', $this->id);
        $stmt->execute();

        foreach ($this->a_medida_cisma_cotizaciones_recurso_humano  as  $valor) {
            $sql = "insert into fi_oportunidades_recurso_humano (fk_oportunidad,fk_usuario,creado_fecha,creado_fk_usuario ) 
                    values (:fk_oportunidad, :fk_usuario, NOW(), :creado_fk_usuario) ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':fk_oportunidad', $this->id);
            $stmt->bindValue(':fk_usuario', $valor);
            $stmt->bindValue(':creado_fk_usuario', $this->creado_fk_usuario);
            $a = $stmt->execute();
            if (!$a) {
                $this->sql = $sql;
                $this->error = implode(", ", $stmt->errorInfo()) . implode(", ", $this->db->errorInfo());
                $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
                $this->Error_SQL();
            }

            //var_dump($stmt->errorInfo());
        }
    }



    public function diccionarioActividades()
	{

		$sql = "SELECT rowid, nombre, activo  
        FROM diccionario_crm_actividades  
        WHERE activo = 1 AND entidad = :entidad  
        ORDER BY nombre ASC";
		$db = $this->db->prepare($sql);
        $db->bindValue(":entidad", $this->entidad , PDO::PARAM_INT);
		$db->execute();

		$this->diccionarioActividades                              = $db->fetchAll(PDO::FETCH_OBJ);


		return $this->diccionarioActividades;
	}

    public function usuarios_disponibles()
    {
        
        $sql = "SELECT fi_usuarios.rowid, fi_usuarios.nombre,fi_usuarios.apellidos,fi_usuarios.entidad 
        FROM fi_usuarios, " . $_ENV['DB_NAME_PLATAFORMA'] . ".sistema_empresa_usuarios 
        WHERE " . $_ENV['DB_NAME_PLATAFORMA'] . ".sistema_empresa_usuarios.fk_empresa = :entidad 
        AND fi_usuarios.rowid = " . $_ENV['DB_NAME_PLATAFORMA'] . ".sistema_empresa_usuarios.fk_usuario 
        AND fi_usuarios.entidad = :entidad";
        $db = $this->db->prepare($sql);
        $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $db->execute();
        $this->usuarios_disponibles = $db->fetchAll(PDO::FETCH_OBJ);


        return $this->usuarios_disponibles;
    }



    /********************************************************************
     * 
     * 
     *      Sistema de Manejo de Servicios
     * 
     * 
     * 
     ***********************************************************************/


    public function  servicios_insertar()
    {
        $this->diccionario_impuesto_iva(); /// Lo necesita para el recalculo de impuestos de RE 
        $sql = "INSERT INTO fi_oportunidades_servicio  (entidad, fk_documento, fk_producto 
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


        $db = $this->db->prepare($sql);

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

        if ($debug){
        echo "<tr><Td>";
        echo "<br>Pu:".  $this->precio_unitario;
        echo "<br>CAntidad:".  $this->cantidad;
        echo "<br>Pu:".  $this->precio_unitario;
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

                if ($this->descuento > ($this->precio_unitario* $this->cantidad)) {
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
            $impuesto_iva_monto = ($this->subtotal_pre_retencion  )  * (($this->tipo_impuesto / 100));

        } else {
            $impuesto_iva_monto = 0;
        }

        if ($debug){
            echo "<tr><Td>";
            echo "Juan Carlos aqui el calculo <br>Descuento Por linea ".  $descuento_valor_final;
            echo "<br>Impuesto total IVA Por linea".  $impuesto_iva_monto;
            echo "</td>";
            echo "</tr>";

        }

        if ($this->recargo_equivalencia == 1) {
            $impuesto_iva_equivalencia_aplica       = 1;
            $impuesto_iva_equivalencia_monto        = ($this->subtotal_pre_retencion ) * (($this->diccionario_impuesto_iva_equivalencia[$this->tipo_impuesto] / 100));
            $impuesto_iva_equivalencia_porcentaje   = $this->diccionario_impuesto_iva_equivalencia[$this->tipo_impuesto];
        }


        if ($this->retencion == 1) {
            $impuesto_retencion_aplica       = 1;
            $impuesto_retencion_monto        = ( $this->subtotal_pre_retencion ) * (($this->Entidad->retencion_porcentaje  / 100));
            $impuesto_retencion_porcentaje   = $this->Entidad->retencion_porcentaje;
        }




        $subtotal   =   $this->subtotal_pre_retencion - $impuesto_retencion_monto;
        $total      =   $subtotal + $impuesto_iva_monto + $impuesto_iva_equivalencia_monto;

        $db->bindValue(':entidad',         $this->entidad      ,   PDO::PARAM_STR);
        $db->bindValue(':fk_documento',      $this->id           ,   PDO::PARAM_INT);
        $db->bindValue(':fk_producto',     $this->fk_producto  ,   PDO::PARAM_STR);
        $db->bindValue(':label',           $this->label        ,   PDO::PARAM_STR);
        $db->bindValue(':precio_original', $precio_original                       );
        $db->bindValue(':precio_unitario', $this->precio_unitario);
        $db->bindValue(':cantidad',        $this->cantidad);


        $db->bindValue(':descuento_tipo',              $descuento_tipo);
        $db->bindValue(':descuento_aplicado',          $descuento_aplicado);
        $db->bindValue(':descuento_valor_final',       $descuento_valor_final  ,   PDO::PARAM_STR);
 

        $db->bindValue(':subtotal_pre_retencion',       $this->subtotal_pre_retencion);
        $db->bindValue(':impuesto_iva_monto',       $impuesto_iva_monto);
        $db->bindValue(':impuesto_iva_porcentaje',       $this->tipo_impuesto);


        $db->bindValue(':impuesto_iva_equivalencia_aplica',       $impuesto_iva_equivalencia_aplica);
        $db->bindValue(':impuesto_iva_equivalencia_monto',       $impuesto_iva_equivalencia_monto);
        $db->bindValue(':impuesto_iva_equivalencia_porcentaje',       $impuesto_iva_equivalencia_porcentaje);



        $db->bindValue(':impuesto_retencion_aplica',       $impuesto_retencion_aplica);
        $db->bindValue(':impuesto_retencion_monto',       $impuesto_retencion_monto);
        $db->bindValue(':impuesto_retencion_porcentaje',       $impuesto_retencion_porcentaje);



        $db->bindValue(':subtotal',        $subtotal);
        $db->bindValue(':total',           $total);

        $a = $db->execute();


        if (!$a) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $db->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();

            $respuesta['error']     = true;
            $respuesta['id']        = 0;
            $respuesta['respuesta'] =  $db->errorInfo();
        }


        $this->recalculo_documento();

        $respuesta['error']     = false;
        $respuesta['id']        = $this->id;
        $respuesta['respuesta'] =  $total;

        return $respuesta;
    } // fin de la funcion 

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
                total 
                from fi_oportunidades_servicio
                where fk_documento  =   :fk_documento
                and entidad       =   :entidad ";


        $db = $this->db->prepare($sql);
        $db->bindValue(':entidad',         $this->entidad,   PDO::PARAM_STR);
        $db->bindValue(':fk_documento',      $this->id,   PDO::PARAM_INT);
        $a = $db->execute();

        if (!$a) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $db->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
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

        while ($row = $db->fetch(PDO::FETCH_OBJ)) {
            $IVA[(int)$row->impuesto_iva_porcentaje]                += $row->impuesto_iva_monto;
            $RE[number_format($row->impuesto_iva_equivalencia_porcentaje, 2)]        += $row->impuesto_iva_equivalencia_monto;

            $impuesto_iva_equivalencia += $row->impuesto_iva_equivalencia_monto;
            $impuesto_iva   += $row->impuesto_iva_monto;
            $retencion      += $row->impuesto_retencion_monto;
            $subtotal_pre_retencion += $row->subtotal_pre_retencion;
            $total += $row->total;
        }

        /*
                        echo "<tr><Td>";
                         var_dump($RE);
                        echo "</td></Tr>";
                        */


        $sql = "update 
                            fi_oportunidades
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
                            RE_0_75                      = 0{$RE["0"]}                  


                            where rowid = :fk_documento
                            and entidad = :entidad 
                            ";

        $db = $this->db->prepare($sql);
        $db->bindValue(':entidad',         $this->entidad,   PDO::PARAM_STR);
        $db->bindValue(':fk_documento',      $this->id,   PDO::PARAM_INT);
        $a = $db->execute();


        if (!$a) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $db->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();

            echo "<Tr><Td colspan='5'>";
            echo $this->error;
            echo "<td></tr>";
        }
    }

    public function  obtener_importe_dolarizado_total_oportunidad()
    {
        $sql = "
          SELECT 
            IFNULL(importe,0) as importe
          FROM 
            fi_oportunidades
          WHERE 
            rowid = :rowid
        ";
        $db = $this->db->prepare($sql);
        $db->bindValue(":rowid", $this->id, PDO::PARAM_INT);
        $db->execute();
        $row = $db->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    //Vamos a obtener el importe totalizado de una oportunidad junto a su tipo de moneda
    public function obtener_suma_importe_oportunidad($fk_oportunidad)
    {
        $sql = 'SELECT SUM(IFNULL(precio_total,0)) AS precio_total  from fi_oportunidades_servicio where fk_oportunidad  = :fk_oportunidad ';
        $db = $this->db->prepare($sql);
        $db->bindValue(":fk_oportunidad", $fk_oportunidad, PDO::PARAM_INT);
        $a = $db->execute();
        $datos  =  $db->fetch(PDO::FETCH_OBJ);
        return $datos;
    }

    public function totalizar_oportunidad()
    {

        //Aqui obtenemos la suma total de lo que tiene
        $dato_detalle_oportunidad = $this->obtener_suma_importe_oportunidad($this->id);

        $suma_total = 0;
        if($dato_detalle_oportunidad){
            $suma_total = $dato_detalle_oportunidad->precio_total;
        }


        // $sql = "update fi_oportunidades set  importe_dolarizado = $importe_dolarizado , importe = $suma_total
        //  where rowid = :fk_oportunidad ";
        $sql = "update fi_oportunidades set importe = :importe
         where rowid = :fk_oportunidad ";
        $db = $this->db->prepare($sql);
        $db->bindValue(":fk_oportunidad", $this->id, PDO::PARAM_INT);
        $db->bindValue(":importe", $suma_total, PDO::PARAM_STR);
        
        $a = $db->execute();

        if (!$a) {

            $this->sql = $sql;
            $this->error = implode(", ", $db->errorInfo()) . implode(", ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();

            $respuesta['error']     = true;
            $respuesta['id']        = 0;
            $respuesta['respuesta'] =  $this->error;

            return $respuesta;
        } else {
            $data_importe = $this->obtener_importe_dolarizado_total_oportunidad();
            $this->importe = $data_importe['importe'];
        }
    }

    public function servicios_remover($id_servicio)
    {

        // Preparamos la consulta SQL para eliminar el registro
        $sql = "DELETE FROM fi_oportunidades_servicio WHERE md5(rowid) = :rowid and entidad = :fk_entidad  ";
        try {
            $db = $this->db->prepare($sql);
            $db->bindValue('rowid', $id_servicio, PDO::PARAM_STR);
            $db->bindValue(':fk_entidad', $this->entidad, PDO::PARAM_INT);
            $a = $db->execute();

            if (!$a) {
                $this->sql     =   $sql;
                $this->error   =   implode(", ", $db->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
                $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
                $this->Error_SQL();
                $respuesta['error'] = true;
                $respuesta['respuesta'] = $db->errorInfo();
            }
    
            $sqlQuery = "SELECT fk_documento FROM fi_oportunidades_servicio WHERE  md5(rowid) = :rowid and entidad = :entidad ";
            $dbQuery = $this->db->prepare($sqlQuery);
            $dbQuery->bindValue(":rowid", $id_servicio, PDO::PARAM_STR);
            $dbQuery->bindValue(":entidad", $this->entidad, PDO::PARAM_INT);
            $dbQuery->execute();
            $rowQuery = $dbQuery->fetch(PDO::FETCH_ASSOC);

            $this->id = $rowQuery["fk_documento"];

            $this->recalculo_documento();
            $respuesta['error'] = false;
            $respuesta['respuesta'] = "Item Eliminado con Exito";
            return $respuesta;
        } catch (PDOException $e) {
            // Manejo de errores
            return [
                'error' => true,
                'mensaje' => "Error al eliminar el servicio: " . $e->getMessage()
            ];
        }
    }


    //aCTUALIZAR SERVICIO
    public function servicios_actualizar()
    {
        $this->diccionario_impuesto_iva(); /// Lo necesita para el recalculo de impuestos de RE 

        $sql = "UPDATE fi_oportunidades_servicio SET 
        fk_producto = :fk_producto,  label = :label, cantidad=:cantidad, subtotal =:subtotal, total =:total, 
        precio_original = :precio_original, precio_unitario=:precio_unitario, descuento_tipo = :descuento_tipo, descuento_aplicado =:descuento_aplicado, 
        descuento_valor_final = :descuento_valor_final, subtotal_pre_retencion=:subtotal_pre_retencion, impuesto_iva_monto=:impuesto_iva_monto,
        impuesto_iva_porcentaje=:impuesto_iva_porcentaje, impuesto_iva_equivalencia_aplica=:impuesto_iva_equivalencia_aplica, impuesto_iva_equivalencia_monto=:impuesto_iva_equivalencia_monto, impuesto_iva_equivalencia_porcentaje=:impuesto_iva_equivalencia_porcentaje, impuesto_retencion_aplica=:impuesto_retencion_aplica, impuesto_retencion_monto=:impuesto_retencion_monto, impuesto_retencion_porcentaje=:impuesto_retencion_porcentaje
        WHERE md5(rowid) = :rowid
        ";
        $dbh = $this->db->prepare($sql);

        $descuento_aplicado = (empty($this->descuento)) ? 0 : $this->descuento;
        $descuento_tipo     = $this->descuento_tipo;
        $precio_original    = $this->precio_unitario;


        $impuesto_iva_equivalencia_aplica       = 0;
        $impuesto_iva_equivalencia_monto        = 0;
        $impuesto_iva_equivalencia_porcentaje   = 0;

        $impuesto_retencion_aplica              = 0;
        $impuesto_retencion_monto               = 0;
        $impuesto_retencion_porcentaje          = 0;

        $this->subtotal_pre_retencion    =   $this->precio_unitario * $this->cantidad;      
        
        // Inicio  del Calculo del Descuento
        if ($this->descuento > 0) {


            if ($descuento_tipo == "porcentual") {
                if ($this->descuento > 100) {
                    //$error_txt = "EL monto a descuentar no puede ser mayor al 100% de Subtotal de la linea";
                    $this->descuento = 100;
                }                
                $descuento_valor_final        = ($this->subtotal_pre_retencion  * ($this->descuento / 100));
            } else if ($descuento_tipo == "absoluto") {
                if ($this->descuento > ($this->precio_unitario* $this->cantidad)) {
                    //$error_txt = "EL monto a descuentar no puede ser mayor al monto Subtotal de la linea";
                    $this->descuento = $this->precio_unitario * $this->cantidad;
                }
                $descuento_valor_final =  $this->descuento;
             }
        } else {
            $descuento_tipo = NULL;
        }
        // Fin del Calculo del Descuento     

        $this->subtotal_pre_retencion = $this->subtotal_pre_retencion - $descuento_valor_final;


        if ($this->tipo_impuesto > 0) {
            $impuesto_iva_monto = ($this->subtotal_pre_retencion  )  * (($this->tipo_impuesto / 100));
        } else {
            $impuesto_iva_monto = 0;
        }

        if ($this->recargo_equivalencia == 1) {
            $impuesto_iva_equivalencia_aplica       = 1;
            $impuesto_iva_equivalencia_monto        = ($this->subtotal_pre_retencion ) * (($this->diccionario_impuesto_iva_equivalencia[$this->tipo_impuesto] / 100));
            $impuesto_iva_equivalencia_porcentaje   = $this->diccionario_impuesto_iva_equivalencia[$this->tipo_impuesto];
        }

        if ($this->retencion == 1) {
            $impuesto_retencion_aplica       = 1;
            $impuesto_retencion_monto        = ( $this->subtotal_pre_retencion ) * (($this->Entidad->retencion_porcentaje  / 100));
            $impuesto_retencion_porcentaje   = $this->Entidad->retencion_porcentaje;
        }

        $subtotal   =   $this->subtotal_pre_retencion - $impuesto_retencion_monto;
        $total      =   $subtotal + $impuesto_iva_monto + $impuesto_iva_equivalencia_monto;
        $this->total = $total;

 


        $dbh->bindValue(':rowid',         $this->lineaMd5      ,   PDO::PARAM_STR);
        $dbh->bindValue(':fk_producto',     $this->fk_producto  ,   PDO::PARAM_STR);
        $dbh->bindValue(':label',           $this->label        ,   PDO::PARAM_STR);
        $dbh->bindValue(':precio_original', $precio_original                       );
        $dbh->bindValue(':precio_unitario', $this->precio_unitario);
        $dbh->bindValue(':cantidad',        $this->cantidad);


        $dbh->bindValue(':descuento_tipo',              $descuento_tipo);
        $dbh->bindValue(':descuento_aplicado',          $descuento_aplicado);
        $dbh->bindValue(':descuento_valor_final',       $descuento_valor_final  ,   PDO::PARAM_STR);
 

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


        
        $sqlQuery = "SELECT fk_documento FROM fi_oportunidades_servicio WHERE  md5(rowid) = :rowid and entidad = :entidad ";
        $dbQuery = $this->db->prepare($sqlQuery);
        $dbQuery->bindValue(":rowid", $this->lineaMd5, PDO::PARAM_STR);
        $dbQuery->bindValue(":entidad", $this->entidad, PDO::PARAM_INT);        
        $dbQuery->execute();
        $rowQuery = $dbQuery->fetch(PDO::FETCH_ASSOC);

        $this->id = $rowQuery["fk_documento"];
        
        $this->recalculo_documento();
        if ($a) {
            $respuesta['error'] = false;
            $respuesta['respuesta'] = "Item Actualizado con Exito";
            return $respuesta;
        } else {
            $this->sql = $sql;
            $this->error = implode(", ", $db->errorInfo()) . implode(", ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
            $respuesta['error']     = true;
            $respuesta['respuesta'] = $this->error;
            return $respuesta;
        }
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

} // Fin Objeto 
