<?php

class Series extends Seguridad
{
    public $entidad;

    function __construct($db , $entidad)
    {
        $this->db = $db;
        parent::__construct();  // Esto inicializa la clase SEGURIDAD
        $this->entidad = $entidad;

    }

    public function validar_serie_es_usada( ){
        $sql = "SELECT count(rowid) as total FROM fi_europa_facturas  WHERE referencia_serie = :referencia_serie and entidad = :entidad ";

        $db = $this->db->prepare($sql);
        $db->bindValue(':referencia_serie'     , $this->id                  , PDO::PARAM_INT);
        $db->bindValue(':entidad'              , $this->entidad             , PDO::PARAM_INT);
        $db->execute();
        $u = $db->fetch(PDO::FETCH_ASSOC);

        
        if ( $u ) {
            $total = $u['total'];
        } else {
            $this->sql = $sql;
            $this->error = implode(", ", $db->errorInfo())." ". implode(", ", $this->db->errorInfo() );
            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();
            $total = X;
            
        } 

        return $total;

    }



    function fetch($id)
    {
        $sql = "SELECT 
                con.* ,
                (SELECT count(rowid) as total FROM fi_europa_facturas  WHERE referencia_serie = con.rowid  and entidad = :entidad) as total_documentos ,
                pla.plantilla_html,
                pla.plantilla_css
                FROM fi_europa_facturas_configuracion con
                LEFT JOIN fi_europa_documento_plantilla pla ON con.plantilla_fk = pla.rowid
                WHERE con.rowid = :rowid and con.entidad = :entidad ";

        $db = $this->db->prepare($sql);
        $db->bindValue(':rowid'     , $id               , PDO::PARAM_INT);
        $db->bindValue(':entidad'   , $this->entidad    , PDO::PARAM_INT);

        $db->execute();
        $u = $db->fetch(PDO::FETCH_ASSOC);

        // Asignación de los valores obtenidos
        $this->id                   = $u['rowid'];
        $this->entidad              = $u['entidad'];
        $this->tipo                 = $u['tipo'];
        $this->tipo_aeat            = $u['tipo_aeat'];
        $this->siguiente_borrador   = $u['siguiente_borrador'];
        $this->siguiente_documento  = $u['siguiente_documento'];
        $this->fk_serie             = $u['fk_serie'];
        $this->fk_serie_modelo      = $u['fk_serie_modelo'];
        $this->serie_descripcion    = $u['serie_descripcion'];
        $this->serie_por_defecto    = $u['serie_por_defecto'];
        $this->serie_activa         = $u['serie_activa'];
        $this->creado_fecha         = $u['creado_fecha'];
        $this->creado_fk_usuario    = $u['creado_fk_usuario'];
        $this->borrado              = $u['borrado'];
        $this->borrado_fecha        = $u['borrado_fecha'];
        $this->borrado_fk_usuario   = $u['borrado_fk_usuario'];

        $this->total_documentos     = $u['total_documentos'];


        $this->plantilla_fk   = $u['plantilla_fk'];
        $this->plantilla_html   = $u['plantilla_html'];
        $this->plantilla_css   = $u['plantilla_css'];
    }


    
    public function enmascarar_( $siguiente_documento , $mascara ){

        $mascara = str_replace("_Y_", date("Y")                 , $mascara);
        $mascara = str_replace("#"  , $siguiente_documento      , $mascara);
    
    return $mascara;
    
    }



    public function crear_serie()
    {
      
            $sql = "
                INSERT INTO 
                    fi_europa_facturas_configuracion
                SET 
                    entidad                 = :entidad              ,
                    tipo_aeat               = :tipo_aeat            ,
                    tipo                    = :tipo                 ,
                    siguiente_documento     = :siguiente_documento  ,
                    siguiente_borrador      = :siguiente_borrador   ,
                    fk_serie_modelo         = :fk_serie_modelo      ,
                    serie_reinicio_anual    = :serie_reinicio_anual ,
                    serie_por_defecto       = :serie_por_defecto    ,
                    serie_activa            = :serie_activa         ,
                    serie_descripcion       = :serie_descripcion    ,

                    creado_fk_usuario = :creado_fk_usuario          ,
                    creado_fecha = NOW(),
                    plantilla_fk   = :plantilla_fk
            ";
            $insert_stmt = $this->db->prepare($sql);

            $insert_stmt->bindValue(':entidad'              , $this->entidad);
            $insert_stmt->bindValue(':tipo'                 , $this->tipo                   , PDO::PARAM_STR);
            $insert_stmt->bindValue(':tipo_aeat'            , ( ($this->tipo  == "fi_europa_facturas") ? $this->tipo_aeat    :"otros_no_aeat"  ) , PDO::PARAM_STR);
            $insert_stmt->bindValue(':siguiente_documento'  , $this->siguiente_documento    , PDO::PARAM_INT);
            $insert_stmt->bindValue(':siguiente_borrador'   , $this->siguiente_borrador     , PDO::PARAM_INT);
            $insert_stmt->bindValue(':fk_serie_modelo'      , $this->fk_serie_modelo        , PDO::PARAM_STR);
            $insert_stmt->bindValue(':serie_reinicio_anual' , $this->serie_reinicio_anual   , PDO::PARAM_INT);
            $insert_stmt->bindValue(':serie_por_defecto'    , $this->serie_por_defecto      , PDO::PARAM_INT);
            $insert_stmt->bindValue(':serie_activa'         , $this->serie_activa           , PDO::PARAM_INT);
            $insert_stmt->bindValue(':serie_descripcion'    , $this->serie_descripcion      , PDO::PARAM_STR);
            $insert_stmt->bindValue(':creado_fk_usuario'    , $this->creado_fk_usuario      , PDO::PARAM_INT);
            $insert_stmt->bindValue(':plantilla_fk'    , ($this->plantilla_fk==0?NULL:$this->plantilla_fk)      , PDO::PARAM_INT);

            

            if ($insert_stmt->execute()) {
                $a= ['exito' => 1, 'mensaje' => 'Serie insertada correctamente' , 'id' => $this->db->lastInsertId() ];
            } else {
                $this->sql = $sql;
                $this->error = implode(", ", $insert_stmt->errorInfo())." ". implode(", ", $this->db->errorInfo() );
                $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
                $this->Error_SQL();
                $a=  ['exito' => 0, 'mensaje' =>  $this->error ];
            } 

            return $a;
    }





    public function actualizar_serie()
    {
            

            if ($this->validar_serie_es_usada()){

                
            }


            $sql = "
               update 
                    fi_europa_facturas_configuracion
                SET 
                    entidad                 = :entidad              ,
                    tipo_aeat               = :tipo_aeat            ,
                    tipo                    = :tipo                 ,
                    siguiente_documento     = :siguiente_documento  ,
                    siguiente_borrador      = :siguiente_borrador   ,
                    fk_serie_modelo         = :fk_serie_modelo      ,
                    serie_reinicio_anual    = :serie_reinicio_anual ,
                    serie_por_defecto       = :serie_por_defecto    ,
                    serie_activa            = :serie_activa         ,
                    serie_descripcion       = :serie_descripcion    ,

                    creado_fk_usuario = :creado_fk_usuario          ,
                    creado_fecha = NOW()                ,
                    plantilla_fk   = :plantilla_fk

                    where rowid = :rowid and entidad = :entidad
            ";
            $insert_stmt = $this->db->prepare($sql);

            $insert_stmt->bindValue(':rowid'                , $this->id);
            $insert_stmt->bindValue(':entidad'              , $this->entidad);
           
           $insert_stmt->bindValue(':tipo_aeat'            , ( ($this->tipo  == "fi_europa_facturas") ? $this->tipo_aeat    :"otros_no_aeat"  ) , PDO::PARAM_STR);
 
           $insert_stmt->bindValue(':tipo_aeat'                 , $this->tipo_aeat                   , PDO::PARAM_STR);

           $insert_stmt->bindValue(':tipo'                 , $this->tipo                   , PDO::PARAM_STR);

            $insert_stmt->bindValue(':siguiente_documento'  , $this->siguiente_documento    , PDO::PARAM_INT);
            $insert_stmt->bindValue(':siguiente_borrador'   , $this->siguiente_borrador     , PDO::PARAM_INT);
            $insert_stmt->bindValue(':fk_serie_modelo'      , $this->fk_serie_modelo        , PDO::PARAM_STR);
            $insert_stmt->bindValue(':serie_reinicio_anual' , $this->serie_reinicio_anual   , PDO::PARAM_INT);
            $insert_stmt->bindValue(':serie_por_defecto'    , $this->serie_por_defecto      , PDO::PARAM_INT);
            $insert_stmt->bindValue(':serie_activa'         , $this->serie_activa           , PDO::PARAM_INT);
            $insert_stmt->bindValue(':serie_descripcion'    , $this->serie_descripcion      , PDO::PARAM_STR);
            $insert_stmt->bindValue(':creado_fk_usuario'    , $this->creado_fk_usuario      , PDO::PARAM_INT);
            $insert_stmt->bindValue(':plantilla_fk'    , ($this->plantilla_fk==0?NULL:$this->plantilla_fk)      , PDO::PARAM_INT);
            

            if ($insert_stmt->execute()) {
                $a= ['exito' => 1, 'mensaje' => 'Serie Actualizada correctamente'];
            } else {
                $this->sql = $sql;
                $this->error = implode(", ", $insert_stmt->errorInfo())." ". implode(", ", $this->db->errorInfo() );
                $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
                $this->Error_SQL();
                $a=  ['exito' => 0, 'mensaje' =>  $this->error ];
            } 

            return $a;
    }



 
    public function borrar_serie($id)
    {
        $sql = "
            UPDATE fi_europa_facturas_configuracion
            SET borrado = 1, borrado_fecha = NOW(), borrado_fk_usuario = :borrado_fk_usuario 
            WHERE rowid = :id AND entidad = :entidad
        ";

      
            $update_stmt = $this->db->prepare($sql);
            $update_stmt->bindValue(':id'               , $id                       );
            $update_stmt->bindValue(':entidad'          , $this->entidad            );
            $update_stmt->bindValue(':borrado_fk_usuario', $this->borrado_fk_usuario);
            $update_stmt->execute();
            
            if ($update_stmt->execute()) {
                $a= ['exito' => 1, 'mensaje' => 'Serie Eliminada  correctamente'];
            } else {
                $this->sql = $sql;
                $this->error = implode(", ", $update_stmt->errorInfo())." ". implode(", ", $this->db->errorInfo() );
                $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
                $this->Error_SQL();
                $a=  ['exito' => 0, 'mensaje' =>  $this->error ];
            } 


            return $a;

    }




}
