<?php 

 
class Redhouse_Cotizacion extends  Seguridad
{

    private $db;
    public  $entidad;

    public $diccionario_estado_cotizaciones;
    public $rescurso_humano;
    public $PDF_textos;

    public $categorias;

   
   
    // Función __construct que acepta una conexión a la base de datos
    public function __construct($db, $entidad = 5 ) // 2 es rEDHOUSE
    {
        $this->db = $db;
        $this->entidad = $entidad;
        parent::__construct();  // Esto inicializa la clase SEGURIDAD

    } // Funcion Constructor


    public function fetch($id)
    {
        $query = "SELECT cc.*,
            fu.nombre as usuario_creador,
            concat(ft.nombre, ' ', ft.apellidos) as nombre_cliente,
            (select concat(nombre, ' ', apellidos) from fi_usuarios  where cc.fk_usuario_asignado = rowid ) as usuario_txt , 
            (select concat(nombre, ' ', apellidos) from  fi_terceros_crm_contactos   where cc.fk_tercero_contacto = rowid  ) as contacto_txt ,
            cc.creado_fecha,
            cc.creado_fk_usuario,
            cc.borrado,
            cc.borrado_fecha,
            cc.borrado_fk_usuario ,

            cc.cotizacion_validez_oferta ,
            cc.cotizacion_tiempo_entrega ,

            cc.cotizacion_fecha  ,

            ec.etiqueta as estado_etiqueta ,
            ec.estilo   as estado_estilo   ,
            
            cate.etiqueta as nombre_categoria_txt ,  
            cate.estilo   as estilo_categoria     ,

            moneda.etiqueta as moneda_txt ,
            moneda.simbolo  as moneda_simbolo, 
            moneda.codigo  as moneda_codigo 


            FROM a_medida_redhouse_cotizaciones cc
            LEFT JOIN fi_usuarios fu ON fu.rowid = cc.creado_fk_usuario
            LEFT JOIN fi_terceros ft ON ft.rowid = cc.fk_tercero 
            left join a_medida_redhouse_cotizaciones_estado    ec on ec.rowid = cc.fk_estado_a_medida_redhouse_estado_cotizaciones
            left join diccionario_monedas  moneda on moneda.rowid = cc.fk_moneda 

            left join a_medida_redhouse_cotizaciones_diccionario_categorias cate on cate.rowid = cc.fk_categoria
            WHERE cc.rowid = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        $this->id  = $row['rowid'];
        $this->fk_tercero = $row['fk_tercero'];
        $this->cotizacion_referencia = $row['cotizacion_referencia'];
        $this->fk_tercero_contacto = $row['fk_tercero_contacto'];
        $this->fk_estado_a_medida_redhouse_estado_cotizaciones = $row['fk_estado_a_medida_redhouse_estado_cotizaciones'];
        $this->cotizacion_nota = $row['cotizacion_nota'];
        $this->usuario_creador = $row['usuario_creador'];
        $this->nombre_cliente = $row['nombre_cliente'];
        $this->creado_fecha = $row['creado_fecha'];
        $this->creado_fk_usuario = $row['creado_fk_usuario'];
        $this->borrado = $row['borrado'];
        $this->borrado_fecha = $row['borrado_fecha'];
        $this->borrado_fk_usuario = $row['borrado_fk_usuario'];

        $this->cotizacion_fecha = $row['cotizacion_fecha'];
        $this->usuario_txt      = $row['usuario_txt'];
        
        $this->cotizacion_validez_oferta      = $row['cotizacion_validez_oferta'];
        $this->cotizacion_tiempo_entrega      = $row['cotizacion_tiempo_entrega'];

        $this->estado_estilo      = $row['estado_estilo'];
        $this->estado_etiqueta      = $row['estado_etiqueta'];
        
        $this->cotizacion_tags      = $row['cotizacion_tags'];

        $this->estilo_categoria         = $row['estilo_categoria'];
        $this->nombre_categoria_txt     = $row['nombre_categoria_txt'];
        $this->contacto_txt             = $row['contacto_txt'];
        $this->fk_usuario_asignado      = $row['fk_usuario_asignado'];
        $this->fk_categoria             = $row['fk_categoria'];

        $this->cotizacion_tipo_oferta  = $row['cotizacion_tipo_oferta'];

        $this->fk_moneda                = $row['fk_moneda'];
        $this->moneda_simbolo           = $row['moneda_simbolo'];
        $this->moneda_txt               = $row['moneda_txt'];
        $this->cotizacion_proyecto = $row['cotizacion_proyecto'];
        $this->cotizacion_descripcion_proyecto = $row['cotizacion_descripcion_proyecto'];

            
        $this->cotizacion_lugar_proyecto = $row['cotizacion_lugar_proyecto'];
        $this->cotizacion_fecha_proyecto = $row['cotizacion_fecha_proyecto'];
        $this->cotizacion_contacto_proyecto = $row['cotizacion_contacto_proyecto'];
        $this->cotizacion_tipo_cambio = $row['cotizacion_tipo_cambio'];
        $this->moneda_codigo = $row['moneda_codigo'];


        return $row['rowid'];
    }

    /*********************************************/
    /*Obtener los adjuntos de una cotizacion*/
    /*********************************************/
    public function obtener_adjuntos_cotizacion($id)
    {
         $sql = "select * from a_medida_redhouse_cotizaciones_adjuntos where  fk_cotizacion   = " . $id . " and borrado = 0   order by rowid  DESC ";
        $dbj = $this->db->prepare($sql);
        $dbj->execute();
        $data = $dbj->fetchAll(PDO::FETCH_OBJ);
        return $data;

    }   

    /*Borrar el adjunto de una coti*/
    function borrado_adjunto_cotizacion($datos)
    {
            $rutaArchivo = ENLACE_FILES_EMPRESAS . 'imagenes/entidad_' . $_SESSION['Entidad'] . '/cotizacion/' . $datos->label;


            if (file_exists($rutaArchivo)) {
              if (unlink($rutaArchivo)) {
                // El archivo se eliminó con éxito
              } else {
                // No se pudo eliminar el archivo
                $consulta['error'] = 1;
                $consulta['datos'] = "No se pudo eliminar la imagen";
                return $consulta;
              }
            }

            $sql = "UPDATE a_medida_redhouse_cotizaciones_adjuntos SET borrado = 1, borrado_fk_usuario = :borrado_fk_usuario, borrado_fecha = now()  where rowid  = :rowid  and  fk_cotizacion = :fk_cotizacion ";
            $db = $this->db->prepare($sql);
            $db->bindValue(':fk_cotizacion', $datos->fk_cotizacion, PDO::PARAM_INT);
            $db->bindValue(':rowid', $datos->id, PDO::PARAM_STR);
            $db->bindValue(':borrado_fk_usuario', $datos->borrado_fk_usuario, PDO::PARAM_INT);
            $result = $db->execute();

            if ($result) {
              $consulta['error'] = 0;
              $consulta['datos'] = $consulta;
            } else {
              $a = implode('-', $db->errorInfo());
              $a .= implode('-', $this->db->errorInfo());
              $consulta['error'] = 1;
              $consulta['datos'] = $a;
            }

            return $consulta;
          }


          public function eliminar_cotizacion($id)
          {
              // Consulta SQL para borrar en la tabla 'a_medida_redhouse_cotizaciones'
              $sql = "UPDATE a_medida_redhouse_cotizaciones SET borrado = 1,
               borrado_fecha = now(), borrado_fk_usuario = :borrado_fk_usuario WHERE rowid = :id";
          
              try {
                  // Inicia la transacción
                  $this->db->beginTransaction();
          
                  // Actualización en la tabla a_medida_redhouse_cotizaciones
                  $update_stmt = $this->db->prepare($sql);
                  $update_stmt->bindValue(':id', $id);
                  $update_stmt->bindValue(':borrado_fk_usuario', $this->borrado_fk_usuario);
                  $update_stmt->execute();
          
                  // Si todo va bien, se confirma la transacción
                  $this->db->commit();
          
                  return ['error' => 0, 'id' => $id, 'update' => true];
              } catch (Exception $e) {
                  // Si ocurre un error, se revierte la transacción
                  $this->db->rollBack();
          
                  // Identifica cuál consulta falló y registra el error
                  if ($update_stmt->errorCode() !== '00000') {
                      $this->sql = $sql;
                      $this->error = implode(", ", $update_stmt->errorInfo());
                  }
          
                  $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
                  $this->Error_SQL();
          
                  return ['error' => 1, 'error_txt' => $this->error];
              }
          }



    /****************************************************
     * 
     * 
     *   Obtiene el listado de recursos humanos disponibles 
     *   o en su defecto listado para una coti (si se envia el ID coti)
     * 
     * 
     */
    public function obtener_recurso_humano($Cotizacion_id = NULL){

        if ($Cotizacion_id > 0){
                        $consulta ="
                        select 
                        u.rowid   ,
                        u.avatar , 
                        concat(nombre, ' ', apellidos)  as usuario_txt,
                        fk_usuario  
                        from a_medida_redhouse_cotizaciones_recurso_humano rh 
                        left join fi_usuarios u on u.rowid = rh.fk_usuario
                        where rh.fk_cotizacion = ?  ";
                        $parametro = $Cotizacion_id;
        }  else {

                        $consulta ="
                        select 
                        rowid   ,
                        concat(nombre, ' ', apellidos)  as usuario_txt,
                        rowid as fk_usuario  
                        from fi_usuarios 
                        
                        where entidad =  ?  ";
                        $parametro = $this->entidad;

        }

 
 
        unset ($this->rescurso_humano);

        $stmt = $this->db->prepare($consulta);
        $stmt->bindParam(1, $parametro);
        

        if($stmt->execute()) {
            
        } else {
            return ['error' => 1, 'mensaje_txt' => $stmt->errorInfo()];
        }


        while($data =  $stmt->fetch(PDO::FETCH_OBJ)){
 
            $this->rescurso_humano[$data->rowid]['fk_usuario']  = $data->fk_usuario; 
            $this->rescurso_humano[$data->rowid]['usuario_txt'] = $data->usuario_txt; 
            $this->rescurso_humano[$data->rowid]['avatar']      = $data->avatar; 

        }

        return $this->rescurso_humano;
    }

    public function diccionario_estado_cotizaciones(){
        $sql = "SELECT * FROM a_medida_redhouse_cotizaciones_estado p ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        while($data =  $stmt->fetch(PDO::FETCH_OBJ)){
            $this->diccionario_estado_cotizaciones[$data->rowid]['etiqueta'] = $data->etiqueta; 
            $this->diccionario_estado_cotizaciones[$data->rowid]['estilo'] = $data->estilo; 
            $this->diccionario_estado_cotizaciones[$data->rowid]['activo'] = $data->activo; 

        }
  
    }



    //vamos a cargar la firma de manera indivudal
    public function cargar_firma($fk_cotizacion)
    {


        $sql = "SELECT * FROM a_medida_redhouse_cotizaciones_recurso_humano_atestados   where fk_usuario  = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $this->fk_usuario_asignado);
        $stmt->execute();
        $data =  $stmt->fetch(PDO::FETCH_OBJ);
        return $data;
    }
    
    public function cargar_PDF_Textos($fk_cotizacion){

        $sql = "SELECT * FROM a_medida_redhouse_cotizaciones_PDF  where fk_cotizacion = ?  ORDER BY orden ASC";

        $this->id = $fk_cotizacion;
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $this->id);

        $stmt->execute();
        
        while($data =  $stmt->fetch(PDO::FETCH_OBJ)){

            $this->PDF_textos[$data->rowid]['texto'] = $data->texto; 
            $this->PDF_textos[$data->rowid]['titulo'] = $data->titulo; 
            $this->PDF_textos[$data->rowid]['activo'] = $data->activo; 

        }


        $sql = "SELECT * FROM a_medida_redhouse_cotizaciones_recurso_humano_atestados   where fk_usuario  = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $this->fk_usuario_asignado);
        $stmt->execute();
        $data =  $stmt->fetch(PDO::FETCH_OBJ);
        $this->PDF_textos[1]['firma']=$data->firma;
      //  $this->PDF_textos['atestados']['firma']=$data->firma;


    }



    /******************************************************************
     * 
     * 
     *                          Funcion Crear 
     * 
     * 
     *********************************************************************/

    public function Crear() {


        $sql = " SELECT COUNT(*) + 1 AS total_cotizaciones FROM a_medida_redhouse_cotizaciones WHERE YEAR(creado_fecha) = YEAR(CURDATE()); ";
        $db = $this->db->prepare($sql);
        $result =  $db->execute();
        $datos  =  $db->fetch(PDO::FETCH_OBJ);

        $cotizacion_referencia  =substr("000000".$datos->total_cotizaciones, -5)."-". date("Y");    
        
        $query = "
            INSERT INTO 
            a_medida_redhouse_cotizaciones
            (fk_tercero, cotizacion_referencia, cotizacion_fecha , fk_tercero_contacto, 
            fk_estado_a_medida_redhouse_estado_cotizaciones, cotizacion_nota,
            creado_fecha, creado_fk_usuario ,
            cotizacion_tags ,
            cotizacion_validez_oferta ,
            cotizacion_tiempo_entrega ,
            fk_categoria              ,
            fk_usuario_asignado       ,
            cotizacion_tipo_oferta    ,
            fk_moneda,
            cotizacion_proyecto,
            cotizacion_descripcion_proyecto,
            cotizacion_lugar_proyecto,
            cotizacion_fecha_proyecto,
            cotizacion_contacto_proyecto,
            cotizacion_tipo_cambio
            ) 
            VALUES 
            (:fk_tercero, :cotizacion_referencia,:cotizacion_fecha,  :fk_tercero_contacto, 
            :fk_estado_a_medida_redhouse_estado_cotizaciones, :cotizacion_nota,
            NOW(), :creado_fk_usuario , :cotizacion_tags ,
            :cotizacion_validez_oferta ,
            :cotizacion_tiempo_entrega ,
            :fk_categoria              ,
            :fk_usuario_asignado       ,
            :cotizacion_tipo_oferta    ,
            :fk_moneda,
            :cotizacion_proyecto,
            :cotizacion_descripcion_proyecto,
            :cotizacion_lugar_proyecto,
            :cotizacion_fecha_proyecto,
            :cotizacion_contacto_proyecto,
            :cotizacion_tipo_cambio
        )";

        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':fk_moneda'              , $this->fk_moneda);

        $stmt->bindValue(':fk_tercero'              , $this->fk_tercero);
        $stmt->bindValue(':cotizacion_referencia'   , $cotizacion_referencia);
        $stmt->bindValue(':fk_tercero_contacto'     , $this->fk_contacto);
        $stmt->bindValue(':fk_estado_a_medida_redhouse_estado_cotizaciones', $this->fk_estado_a_medida_redhouse_estado_cotizaciones);
        $stmt->bindValue(':cotizacion_nota'         , $this->cotizacion_nota);
        $stmt->bindValue(':creado_fk_usuario'       , $this->creado_fk_usuario);
        $stmt->bindValue(':cotizacion_fecha'        , $this->cotizacion_fecha);
        $stmt->bindValue(':cotizacion_validez_oferta'           , $this->cotizacion_validez_oferta , PDO::PARAM_INT);
        $stmt->bindValue(':cotizacion_tiempo_entrega'           , $this->cotizacion_tiempo_entrega , PDO::PARAM_INT);
        $stmt->bindValue(':fk_categoria'                        , $this->fk_categoria              , PDO::PARAM_INT);
        $stmt->bindValue(':fk_usuario_asignado'                 , $this->fk_usuario_asignado       , PDO::PARAM_INT);
       
        $stmt->bindValue(':cotizacion_tipo_oferta'              , $this->cotizacion_tipo_oferta    , PDO::PARAM_INT);

        $stmt->bindParam(':cotizacion_proyecto', $this->cotizacion_proyecto, PDO::PARAM_STR);
      
        $stmt->bindParam(':cotizacion_descripcion_proyecto', $this->cotizacion_descripcion_proyecto, PDO::PARAM_STR);
            

        $stmt->bindParam(':cotizacion_lugar_proyecto', $this->cotizacion_lugar_proyecto, PDO::PARAM_STR);


        $stmt->bindParam(':cotizacion_fecha_proyecto', $this->cotizacion_fecha_proyecto, PDO::PARAM_STR);


        $stmt->bindParam(':cotizacion_contacto_proyecto', $this->cotizacion_contacto_proyecto, PDO::PARAM_STR);
        
        $stmt->bindParam(':cotizacion_tipo_cambio', $this->cotizacion_tipo_cambio, PDO::PARAM_STR);


        $tags = (  (is_array( $this->cotizacion_tags))   ) ? implode(",", $this->cotizacion_tags) : $this->cotizacion_tags ;
        $stmt->bindParam(':cotizacion_tags', $tags, PDO::PARAM_STR);
        if($stmt->execute()) {
            $idCreado =  $this->db->lastInsertId();
            $this->id =  $idCreado;

            $respuesta= ['error' => 0, 'id' => $idCreado , 'mensaje_txt' => 'Coptizacion Creada Con Exito' ,'creada' => 1];
        } else {
            $respuesta = ['error' => 1, 'mensaje_txt' => $stmt->errorInfo()];
        }
       

            
                $this->Actividad->fk_cotizacion                 =       $idCreado;
                $this->Actividad->fk_diccionario_actividad      =       10;
                $this->Actividad->vencimiento_fecha             =       date("Y-m-d");
                $this->Actividad->creado_usuario                =       $this->creado_fk_usuario;
                $this->Actividad->comentario                    =       "Cotizacion Creada";
                $this->Actividad->fk_usuario_asignado           =       $this->creado_fk_usuario;
                $this->Actividad->fk_estado                     =       1; 
                $this->Actividad->comentario_cierre             =       "";
                $this->Actividad->tipo                          =       "timeline";
            $this->Actividad->actividad_insertar();


            $this->insertar_a_medida_redhouse_cotizaciones_recurso_humano();
            $this->insertar_a_medida_redhouse_cotizaciones_PDF();

        return $respuesta;

    
    }




    /******************************************************************
     * 
     * 
     *                          Funcion Update --->  
     * 
     * 
     *********************************************************************/

     public function Update() {
        $query = "UPDATE a_medida_redhouse_cotizaciones SET 
            fk_tercero              = :fk_tercero, 
            fk_tercero_contacto     = :fk_tercero_contacto, 
            fk_estado_a_medida_redhouse_estado_cotizaciones = :fk_estado_a_medida_redhouse_estado_cotizaciones, 
            cotizacion_nota         = :cotizacion_nota ,
            cotizacion_fecha        = :cotizacion_fecha ,
            cotizacion_tags         = :cotizacion_tags  ,

            cotizacion_tiempo_entrega   = :cotizacion_tiempo_entrega    ,
            cotizacion_validez_oferta   = :cotizacion_validez_oferta    ,
            fk_categoria                = :fk_categoria                 ,
            fk_usuario_asignado         = :fk_usuario_asignado          ,
            cotizacion_tipo_oferta      = :cotizacion_tipo_oferta       ,
            fk_moneda                   = :fk_moneda,
            cotizacion_proyecto = :cotizacion_proyecto,
            cotizacion_descripcion_proyecto = :cotizacion_descripcion_proyecto,
            cotizacion_lugar_proyecto = :cotizacion_lugar_proyecto,
            cotizacion_fecha_proyecto = :cotizacion_fecha_proyecto,
            cotizacion_contacto_proyecto = :cotizacion_contacto_proyecto
            
            WHERE 


            
            rowid = :rowid
            ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':fk_moneda'              , $this->fk_moneda);

        $stmt->bindValue(':fk_tercero', $this->fk_tercero);
        $stmt->bindValue(':fk_tercero_contacto',  $this->fk_contacto);
        $stmt->bindValue(':fk_estado_a_medida_redhouse_estado_cotizaciones', $this->fk_estado_a_medida_redhouse_estado_cotizaciones);
        $stmt->bindValue(':cotizacion_nota'     , $this->cotizacion_nota);
        $stmt->bindValue(':cotizacion_fecha'    , $this->cotizacion_fecha);
        $stmt->bindValue(':cotizacion_validez_oferta'        , $this->cotizacion_validez_oferta , PDO::PARAM_INT);
        $stmt->bindValue(':cotizacion_tiempo_entrega'        , $this->cotizacion_tiempo_entrega , PDO::PARAM_INT);
        $stmt->bindValue(':fk_categoria'                        , $this->fk_categoria              , PDO::PARAM_INT);
        $stmt->bindValue(':fk_usuario_asignado'                 , $this->fk_usuario_asignado       , PDO::PARAM_INT);
        $stmt->bindValue(':cotizacion_tipo_oferta'              , $this->cotizacion_tipo_oferta    , PDO::PARAM_INT);



        $tags = (  (is_array( $this->cotizacion_tags))   ) ? implode(",", $this->cotizacion_tags) : $this->cotizacion_tags ;
        $stmt->bindParam(':cotizacion_tags', $tags, PDO::PARAM_STR);
        $stmt->bindParam(':cotizacion_proyecto', $this->cotizacion_proyecto, PDO::PARAM_STR);
        $stmt->bindParam(':cotizacion_descripcion_proyecto', $this->cotizacion_descripcion_proyecto, PDO::PARAM_STR);
       

        $stmt->bindParam(':cotizacion_lugar_proyecto', $this->cotizacion_lugar_proyecto, PDO::PARAM_STR);

        $stmt->bindParam(':cotizacion_fecha_proyecto', $this->cotizacion_fecha_proyecto, PDO::PARAM_STR);

        $stmt->bindParam(':cotizacion_contacto_proyecto', $this->cotizacion_contacto_proyecto, PDO::PARAM_STR);



        $stmt->bindValue(':rowid', $this->id);

        $this->insertar_a_medida_redhouse_cotizaciones_recurso_humano();

        if($stmt->execute()) {
            $respuesta['error']         =  0 ;
            $respuesta['mensaje_txt']   = "Cotizacion Actualizada con Exito";
            $respuesta['id']            =  $this->id;
            return $respuesta;
        } else {
            $respuesta['error']         =  1 ;
            $respuesta['mensaje_txt']   = $stmt->errorInfo();
            return $respuesta;
        }

    } // update 

    

public function insertar_a_medida_redhouse_cotizaciones_recurso_humano(){

    $stmt = $this->db->prepare("DELETE FROM a_medida_redhouse_cotizaciones_recurso_humano WHERE fk_cotizacion = :cotizacion ");  
    $stmt->bindValue(':cotizacion', $this->id);
    $stmt->execute();

    foreach($this->a_medida_redhouse_cotizaciones_recurso_humano  as  $valor){
          $sql="insert into a_medida_redhouse_cotizaciones_recurso_humano (fk_cotizacion,fk_usuario,creado_fecha,creado_fk_usuario ) 
                    values (:fk_cotizacion, :fk_usuario, NOW(), :creado_fk_usuario) ";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindValue(':fk_cotizacion'       , $this->id);
                    $stmt->bindValue(':fk_usuario'          , $valor);
                    $stmt->bindValue(':creado_fk_usuario'   , $this->creado_fk_usuario);
                    $stmt->execute();     

                    //var_dump($stmt->errorInfo());
    }

}

    public function insertar_a_medida_redhouse_cotizaciones_PDF(){

            $sql ="
            INSERT INTO 
            a_medida_redhouse_cotizaciones_PDF 
            (fk_cotizacion
            , fk_machote_pdf
            , titulo
            , texto
            , orden
            , activo
            , creado_fecha
            , creado_fk_usuario
            )
            
            SELECT
            :fk_cotizacion  , -- Cambia el valor según sea necesario
            rowid as fk_machote_pdf,
            titulo,
            texto,
            orden,
            activo,
            NOW(),
            :creado_fk_usuario

            FROM

            a_medida_redhouse_cotizaciones_MACHOTE_PDF where activo = 1 and borrado = 0 order by orden ASC   ;
            ";
    
            $db = $this->db->prepare($sql);
            $db->bindValue(":creado_fk_usuario" , $this->creado_fk_usuario , PDO::PARAM_INT);
            $db->bindValue(":fk_cotizacion"     , $this->id                , PDO::PARAM_INT);
            $a = $db->execute();


    if ($a) {
        return true;
        
    } else {
       
        $this->sql = $sql;
        $this->error = implode(", ", $db->errorInfo()).implode(", ", $this->db->errorInfo());
        $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
        $this->Error_SQL();
        return false;

    }



    }


    public function obtener_listado_estados()
    {
        $sql = "SELECT  
                activo 
                estilo      ,
                etiqueta    ,
                rowid 
          
          FROM a_medida_redhouse_cotizaciones_estado   ORDER BY rowid ASC";
        $db = $this->db->prepare($sql);
        $result =  $db->execute();

        while ($datos =  $db->fetch(PDO::FETCH_OBJ)){

            $this->estados[$datos->rowid]['etiqueta'] = $datos->etiqueta;
            $this->estados[$datos->rowid]['estilo'] = $datos->estilo;
            $this->estados[$datos->rowid]['activo'] = $datos->activo;

        }
            return $this->estados;
    }


    public function obtener_listado_categorias()
    {
        $sql = "SELECT  
                activo 
                estilo      ,
                etiqueta    ,
                rowid 
          
          FROM a_medida_redhouse_cotizaciones_diccionario_categorias    ORDER BY rowid ASC";
        $db = $this->db->prepare($sql);
        $result =  $db->execute();

        while ($datos =  $db->fetch(PDO::FETCH_OBJ)){

            $this->categorias[$datos->rowid]['etiqueta']    = $datos->etiqueta;
            $this->categorias[$datos->rowid]['estilo']      = $datos->estilo;
            $this->categorias[$datos->rowid]['activo']      = $datos->activo;
            $this->categorias[$datos->rowid]['rowid']       = $datos->rowid;


        }
            return $this->categorias;
    }

    
    public function diccionarioActividades()
    {

        $sql = "SELECT rowid, nombre, activo  FROM a_medida_redhouse_cotizaciones_diccionario_crm_actividades   where activo = 1 ";
        $db = $this->db->prepare($sql);
        $db->execute();
        $this->diccionarioActividades                              = $db->fetchAll(PDO::FETCH_OBJ);


        return $this->diccionarioActividades;
    }

    public function usuarios_disponibles()
    {

        $sql = "SELECT fi_usuarios.rowid, fi_usuarios.nombre,fi_usuarios.apellidos, fi_usuarios.activo ,fi_usuarios.entidad , ".$_ENV['DB_NAME_PLATAFORMA'].".usuarios.acceso_usuario AS email  FROM fi_usuarios, ".$_ENV['DB_NAME_PLATAFORMA'].".sistema_empresa_usuarios , ".$_ENV['DB_NAME_PLATAFORMA'].".usuarios where ".$_ENV['DB_NAME_PLATAFORMA'].".sistema_empresa_usuarios.fk_empresa = :entidad AND fi_usuarios.rowid = ".$_ENV['DB_NAME_PLATAFORMA'].".sistema_empresa_usuarios.fk_usuario 
            AND fi_usuarios.activo = 1
            AND ".$_ENV['DB_NAME_PLATAFORMA'].".usuarios.rowid = ".$_ENV['DB_NAME_PLATAFORMA'].".sistema_empresa_usuarios.fk_usuario;
        ";

        $db = $this->db->prepare($sql);
        $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $db->execute();
        $this->usuarios_disponibles                              = $db->fetchAll(PDO::FETCH_OBJ);


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

     
        public function  servicios_insertar() {

            $sql = "
            INSERT INTO 
            a_medida_redhouse_cotizaciones_cotizaciones_servicios 
            (
                fk_cotizacion,
                fk_producto,
                cantidad,
                precio_unitario,
                precio_subtotal,
                precio_tipo_impuesto,
                precio_total,
                creado_fecha,
                creado_usuario,
                comentario,
                cantidad_dias,
                fk_estado,
                tipo_duracion
            ) VALUES (
                :fk_cotizacion,
                :fk_producto,
                :cantidad,
                :precio_unitario,
                :precio_subtotal,
                :precio_tipo_impuesto,
                :precio_total,
                NOW(),
                :creado_usuario,
                :comentario,
                :cantidad_dias,
                1,
                :tipo_duracion
            )
            ";

            if(intval($this->cantidad_dias)<=0)
            {
                $cantidad_dias = 1;
            }else{
              $cantidad_dias = intval($this->cantidad_dias);
            }
            
            if(intval($this->servicio_tipo_duracion)<=0)
            {
                $this->servicio_tipo_duracion = 1;
                $cantidad_horas = 1;
            }else{
                $cantidad_horas = intval($this->servicio_tipo_duracion);
            }
        

            //Generamos el total sin impuesto
            //Aqui le sumamos las horas y los dias tambien
            $total_sin_impuesto = ($this->servicio_precio_unitario * $this->servicio_cantidad * $cantidad_horas * $cantidad_dias);
 
           $subtotal = $total_sin_impuesto;
           $impuesto = $subtotal * floatval($this->servicio_precio_tipo_impuesto)/100; 
            //TOTAL GENERAL
            $total_general = $subtotal + $impuesto;


            $db = $this->db->prepare($sql);

            // Bind de los valores usando las propiedades de $this
            $db->bindValue(":fk_cotizacion" , $this->id                         , PDO::PARAM_INT);
            $db->bindValue(":fk_producto"   , $this->fk_producto                , PDO::PARAM_INT);
            $db->bindValue(":cantidad"      , $this->servicio_cantidad          , PDO::PARAM_INT);
            $db->bindValue(":precio_unitario", $this->servicio_precio_unitario  , PDO::PARAM_STR);
            $db->bindValue(":precio_subtotal",$subtotal , PDO::PARAM_STR);
            $db->bindValue(":precio_tipo_impuesto" ,$impuesto, PDO::PARAM_STR);
            
            $db->bindValue(":precio_total",$total_general, PDO::PARAM_STR);

            $db->bindValue(":creado_usuario", $this->creado_fk_usuario, PDO::PARAM_INT);
            $db->bindValue(":comentario", $this->servicio_comentario, PDO::PARAM_STR);
            $db->bindValue(":cantidad_dias", $this->cantidad_dias, PDO::PARAM_INT);

            $db->bindValue(":tipo_duracion", $this->servicio_tipo_duracion, PDO::PARAM_STR);
            

            $a = $db->execute();
            $idCreado =  $this->db->lastInsertId();


                if ($a) {
                    $respuesta['error'] = false;
                    $respuesta['id'] = $idCreado;
                    $respuesta['respuesta'] = "Item Creado con Exito";

                    return $respuesta ;
                    
                } else {
                
                    $this->sql = $sql;
                    $this->error = implode(", ", $db->errorInfo()).implode(", ", $this->db->errorInfo());
                    $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
                    $this->Error_SQL();
                    
                    $respuesta['error']     = true;
                    $respuesta['id']        = 0;
                    $respuesta['respuesta'] =  $this->error;

                    return $respuesta ;

                }

            
    } // fin de la funcion 

    public function servicios_remover($id_servicio)
    {

        // Preparamos la consulta SQL para eliminar el registro
        $sql = "DELETE FROM a_medida_redhouse_cotizaciones_cotizaciones_servicios 
                WHERE rowid = :rowid AND fk_cotizacion = :fk_cotizacion";

        try {
            // Preparamos la declaración
            $stmt = $this->db->prepare($sql);

            // Vinculamos los parámetros a los valores
            $stmt->bindParam(':rowid', $id_servicio, PDO::PARAM_INT);
            $stmt->bindParam(':fk_cotizacion', $this->id, PDO::PARAM_INT);

            // Ejecutamos la consulta
            $stmt->execute();

            // Comprobamos si se eliminaron filas
            if ($stmt->rowCount() > 0) {
                $respuesta['error'] = false;
                $respuesta['respuesta'] = "Item Eliminado con Exito";
                return $respuesta;
            } else {
                    $this->error = implode(", ", $db->errorInfo()).implode(", ", $this->db->errorInfo());
                    $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
                    $this->Error_SQL();
                    $respuesta['error']     = true;
                    $respuesta['respuesta'] = $this->error;
                    return $respuesta;
            }
        } catch (PDOException $e) {
            // Manejo de errores
            return [
                'error' => true,
                'mensaje' => "Error al eliminar el servicio: " . $e->getMessage()
            ];
        }
    }


    //aCTUALIZAR SERVICIO
    public function servicios_actualizar($id_servicio)
    {
        $sql = "
                UPDATE a_medida_redhouse_cotizaciones_cotizaciones_servicios 
                SET
                    cantidad = :cantidad,
                    precio_unitario = :precio_unitario,
                    precio_subtotal = :precio_subtotal,
                    precio_tipo_impuesto = :precio_tipo_impuesto,
                    precio_total = :precio_total,
                    creado_fecha = NOW(),
                    creado_usuario = :creado_usuario,
                    comentario = :comentario,
                    cantidad_dias = :cantidad_dias,
                    tipo_duracion = :tipo_duracion
                WHERE
                    fk_cotizacion = :fk_cotizacion AND
                    rowid = :rowid
                ";

                $db = $this->db->prepare($sql);


                if(intval($this->cantidad_dias)<=0)
                {
                    $cantidad_dias = 1;
                }else{
                  $cantidad_dias = intval($this->cantidad_dias);
                }
                
                if(intval($this->servicio_tipo_duracion)<=0)
                {
                    $this->servicio_tipo_duracion = 1;
                    $cantidad_horas = 1;
                }else{
                    $cantidad_horas = intval($this->servicio_tipo_duracion);
                }


                //Generamos el total sin impuesto
                //Añadiendole ahora los nuevos elementos
                $total_sin_impuesto = ($this->servicio_precio_unitario * $this->servicio_cantidad * $cantidad_horas * $cantidad_dias);

               $subtotal = $total_sin_impuesto;
               $impuesto = $subtotal * floatval($this->servicio_precio_tipo_impuesto)/100; 
                //TOTAL GENERAL
                $total_general = $subtotal + $impuesto;

                // Bind de los valores usando las propiedades de $this
                $db->bindValue(":fk_cotizacion", $this->id, PDO::PARAM_INT);
                $db->bindValue(":rowid", $id_servicio, PDO::PARAM_INT);
               // $db->bindValue(":fk_producto", $this->fk_producto, PDO::PARAM_INT);
                $db->bindValue(":cantidad", $this->servicio_cantidad, PDO::PARAM_INT);
                $db->bindValue(":precio_unitario", $this->servicio_precio_unitario, PDO::PARAM_STR);
                $db->bindValue(":precio_subtotal",$subtotal, PDO::PARAM_STR);
                $db->bindValue(":precio_tipo_impuesto", $impuesto, PDO::PARAM_STR);
                $db->bindValue(":precio_total",$total_general, PDO::PARAM_STR);
                $db->bindValue(":creado_usuario", $this->creado_fk_usuario, PDO::PARAM_INT);
                $db->bindValue(":comentario", $this->servicio_comentario, PDO::PARAM_STR);
                $db->bindValue(":cantidad_dias", $this->cantidad_dias, PDO::PARAM_INT);

                //Tipo de duración
                $db->bindValue(":tipo_duracion", $this->servicio_tipo_duracion, PDO::PARAM_STR);
            

                $a = $db->execute();
                if ($a) {
                    $respuesta['error'] = false;
                    $respuesta['respuesta'] = "Item Actualizado con Exito";
                    return $respuesta;
                } else {
                    $this->sql = $sql;
                    $this->error = implode(", ", $db->errorInfo()).implode(", ", $this->db->errorInfo());
                    $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
                    $this->Error_SQL();
                    $respuesta['error']     = true;
                    $respuesta['respuesta'] = $this->error;
                    return $respuesta;
                }
        }

} // Fin Objeto 

