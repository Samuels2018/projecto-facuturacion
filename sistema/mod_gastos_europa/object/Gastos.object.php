<?php
//----------------------------------------------------------------------------------------------------------
//
//          dbermejo@avancescr.com
//          David Bermejo
//          4001-6311
//
//----------------------------------------------------------------------------------------------------------

class Gastos  extends  Seguridad
{
    private     $db;
    private $entidad;




    public $id;
    public $fk_parent;
    public $nombre;
    public $require_cedula;
    public $activo;
    



    

    function  __construct($db, $entidad )
    {
        $this->db           = $db;
        $this->entidad      = $entidad;
        parent::__construct($db, $entidad);  // Esto inicializa la clase SEGURIDAD
    }




    /*************************************************************
     * 
     *           Gastos
     * 
     * 
     * ************************************************************* */ 
    public function editar_gasto( ){

        $sql = "UPDATE fi_europa_gastos  SET 
        
        recibo_numero   = :recibo_numero , 
        fecha           =:fecha          ,
        valor           =:valor          ,
        detalle         =:detalle        ,
        fk_gasto        =:fk_gasto       ,
        fk_tercero        =:fk_tercero ,
        fk_proyecto        =:fk_proyecto ,
        url_recibo        =:url_recibo 
              
        
        WHERE entidad = :entidad AND rowid = :rowid;";
        
        $db = $this->db->prepare($sql);
        $db->bindValue(':rowid'         , $this->id             ,   PDO::PARAM_INT);
        $db->bindValue(':entidad'       , $this->entidad        ,   PDO::PARAM_INT);
        $db->bindValue(':recibo_numero' , $this->recibo_numero  , 	PDO::PARAM_STR);
        $db->bindValue(':fecha'         , $this->fecha          , 	PDO::PARAM_STR);
        $db->bindValue(':valor'         , $this->valor          , 	PDO::PARAM_INT);
        $db->bindValue(':detalle'       , $this->detalle        , 	PDO::PARAM_STR);
        $db->bindValue(':fk_gasto'      , $this->fk_gasto       , 	PDO::PARAM_INT);
        $db->bindValue(':fk_tercero'      , $this->fk_tercero       , 	PDO::PARAM_INT);
        $db->bindValue(':url_recibo'      , $this->url_recibo       , 	PDO::PARAM_STR);
        $db->bindValue(':fk_proyecto'      , $this->fk_proyecto       , 	PDO::PARAM_INT);

        $ejecutado = $db->execute();
    
    
                if (!$ejecutado) {
                        $this->sql = $sql;
                        $this->error = implode(", ", $db->errorInfo())." ". implode(", ", $this->db->errorInfo() );
                        $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
                        $this->Error_SQL();
                        $respuesta['exito']     =   0;
                        $respuesta['mensaje']   =   $this->error;
                } else {
                    $respuesta['exito']     =   1;
                    $respuesta['mensaje']   =   "Cuenta Editada Correctamente";

                }
    
                $respuesta['id']   =   $this->id    ;

            return $respuesta;
        }

        //Borrar Gasto
        public function borrar_gasto( ){

            $sql = "UPDATE fi_europa_gastos  SET 
            
            borrado   = :borrado , 
            borrado_fecha           =:borrado_fecha  ,
            borrado_fk_usuario           =:borrado_fk_usuario       
            
            WHERE entidad = :entidad AND rowid = :rowid;";
            
            $db = $this->db->prepare($sql);
            $db->bindValue(':rowid'         , $this->id             ,   PDO::PARAM_INT);
            $db->bindValue(':entidad'       , $this->entidad        ,   PDO::PARAM_INT);
            $db->bindValue(':borrado' , $this->borrado  , 	PDO::PARAM_INT);
            $db->bindValue(':borrado_fecha'         , $this->borrado_fecha          , 	PDO::PARAM_STR);
            $db->bindValue(':borrado_fk_usuario'         , $this->borrado_fk_usuario          , 	PDO::PARAM_INT);
    
            $ejecutado = $db->execute();
        
        
                    if (!$ejecutado) {
                            $this->sql = $sql;
                            $this->error = implode(", ", $db->errorInfo())." ". implode(", ", $this->db->errorInfo() );
                            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
                            $this->Error_SQL();
                            $respuesta['exito']     =   0;
                            $respuesta['mensaje']   =   $this->error;
                    } else {
                        $respuesta['exito']     =   1;
                        $respuesta['mensaje']   =   "Cuenta Borrada Correctamente";
    
                    }
        
                    $respuesta['id']   =   $this->id    ;
    
                return $respuesta;
        }

        
        public function crear_gasto(){
                     
        $sql = 
                "INSERT INTO fi_europa_gastos 
                ( entidad, recibo_numero, creado_fecha , valor , fk_gasto , detalle, fk_tercero ,fecha,url_recibo, fk_proyecto)
                VALUE
                ( :entidad, :recibo_numero, NOW()  , :valor , :fk_gasto , :detalle, :fk_tercero,:fecha, :url_recibo,:fk_proyecto);";


        $db = $this->db->prepare($sql);

        $db->bindValue(':entidad'       , $this->entidad        ,   PDO::PARAM_INT);
        $db->bindValue(':recibo_numero' , $this->recibo_numero  , 	PDO::PARAM_STR);
        $db->bindValue(':valor'         , $this->valor          , 	PDO::PARAM_INT);
        $db->bindValue(':detalle'       , $this->detalle        , 	PDO::PARAM_STR);
        $db->bindValue(':fk_gasto'      , $this->fk_gasto       , 	PDO::PARAM_INT);
        $db->bindValue(':fk_tercero'      , $this->fk_tercero       , 	PDO::PARAM_INT);
        $db->bindValue(':fecha'      , $this->fecha       , 	PDO::PARAM_STR);
        $db->bindValue(':url_recibo'      , $this->url_recibo       , 	PDO::PARAM_STR);
        $db->bindValue(':fk_proyecto'      , $this->fk_proyecto       , 	PDO::PARAM_STR);
            
        $ejecutado = $db->execute();

      

        if (!$ejecutado) {
                $this->sql = $sql;
                $this->error = implode(", ", $db->errorInfo())." ". implode(", ", $this->db->errorInfo() );
                $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
                $this->Error_SQL();
                $respuesta['exito']     =   0;
                $respuesta['mensaje']   =   $this->error;
                
        } else {
            $respuesta['exito']      =   1;
            $respuesta['mensaje']    =   "Gasto Creado Correctamente";
            $respuesta['id']         =   $this->db->lastInsertId();
            $respuesta['accion']     =  "crear";

            
        }


    return $respuesta;
    }

    public function validar_existencia_gasto($fk_tercero, $recibo_numero, $id = 0)
    {
        if (empty($fk_tercero)) {
            return ['exito' => 1]; // Si el proveedor está vacío, permitir continuar.
        }

        // Construir la consulta SQL considerando si $id es mayor a 0 (caso de edición)
        $sql = "SELECT COUNT(*) as total 
                FROM fi_europa_gastos 
                WHERE fk_tercero = :fk_tercero 
                AND recibo_numero = :recibo_numero 
                AND entidad = :entidad";

        if ($id > 0) {
            $sql .= " AND rowid != :id"; // Excluir el registro actual si se está editando
        }

        $db = $this->db->prepare($sql);
        $db->bindValue(':fk_tercero', $fk_tercero, PDO::PARAM_INT);
        $db->bindValue(':recibo_numero', $recibo_numero, PDO::PARAM_STR);
        $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);

        // Solo enlazar el ID si se está editando (id > 0)
        if ($id > 0) {
            $db->bindValue(':id', $id, PDO::PARAM_INT);
        }

        $db->execute();
        $result = $db->fetch(PDO::FETCH_ASSOC);

        if ($result['total'] > 0) {
            return [
                'exito' => 0,
                'mensaje' => 'Ya existe una factura con este número asociada al proveedor.'
            ];
        }

        return ['exito' => 1]; // Si no existe, permitir continuar.
    }




    public function fetch( $id ){

        $sql = "SELECT 
                eg.rowid, 
                eg.recibo_numero, 
                eg.fecha, 
                eg.valor, 
                eg.url_recibo,
                eg.fk_gasto, 
                eg.fk_tercero, 
                eg.fk_proyecto, 
                eg.detalle,
                eg.pagado,
                eg.fecha_pago,
                CONCAT(t.nombre, ' ', IFNULL(t.apellidos, '')) AS nombre_proveedor,
                t.email,
                CONCAT(u.nombre, ' ', IFNULL(u.apellidos, '')) AS pagado_por
            FROM 
                fi_europa_gastos eg
            LEFT JOIN 
                fi_terceros t 
            ON 
                eg.fk_tercero = t.rowid
            LEFT JOIN 
                fi_usuarios u 
            ON 
                eg.fk_usuario_pagar = u.rowid
            WHERE 
                eg.entidad = :entidad AND eg.rowid = :rowid;";
    
    
        $db = $this->db->prepare($sql);
        $db->bindValue(':rowid'     , $id               ,   PDO::PARAM_INT);
        $db->bindValue(':entidad'   , $this->entidad    ,   PDO::PARAM_INT);
    
        $ejecutado = $db->execute();
    
        $u = $db->fetch(PDO::FETCH_ASSOC);
    
        $this->id               = $u['rowid'];
        $this->recibo_numero    = $u['recibo_numero'];
        $this->fecha            = $u['fecha'];
        $this->valor            = $u['valor'];
        $this->fk_gasto         = $u['fk_gasto'];
        $this->detalle          = $u['detalle'];
        $this->fk_tercero       = $u['fk_tercero'];
        $this->nombre_proveedor = $u['nombre_proveedor'];
        $this->email            = $u['email'];
        $this->pagado           = $u['pagado'];
        $this->pagado_por       = $u['pagado_por'];
        $this->fecha_pago       = $u['fecha_pago'];
        $this->url_recibo =     $u['url_recibo'];
        $this->fk_proyecto =     $u['fk_proyecto'];
    
    
        if (!$ejecutado) {
            $this->sql = $sql;
            $this->error = implode(", ", $db->errorInfo())." ". implode(", ", $this->db->errorInfo() );
            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();
            $respuesta['exito']     =   0;
            $respuesta['mensaje']   =   $this->error;
        } else {
            $respuesta['exito']     =   1;
            $respuesta['mensaje']   =   "Cuenta Editada Correctamente";
    
        }
    
        return $respuesta;
    }


    /*************************************************************
     * 
     *           Cuentas
     * 
     * 
     * ************************************************************* */ 
    
    public function editar_cuenta( ){

    $sql = "UPDATE fi_gastos_tipos SET nombre = :nombre , fk_parent  =:fk_parent  WHERE entidad = :entidad AND rowid = :rowid;";
    $db = $this->db->prepare($sql);
    $db->bindValue(':rowid'     , $this->id         ,   PDO::PARAM_INT);
    $db->bindValue(':entidad'   , $this->entidad    ,   PDO::PARAM_INT);
	$db->bindValue(':nombre'    , $this->nombre     , 	PDO::PARAM_STR);
    $db->bindValue(':fk_parent' , $this->fk_parent  , 	PDO::PARAM_INT);

    $ejecutado = $db->execute();


            if (!$ejecutado) {
                    $this->sql = $sql;
                    $this->error = implode(", ", $db->errorInfo())." ". implode(", ", $this->db->errorInfo() );
                    $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
                    $this->Error_SQL();
                    $respuesta['exito']     =   0;
                    $respuesta['mensaje']   =   $this->error;
            } else {
                $respuesta['exito']     =   1;
                $respuesta['mensaje']   =   "Cuenta Editada Correctamente";
                $respuesta['accion']    =   "edicion";

            }


        return $respuesta;
    }
    

    
    public function crear_cuenta(){

        $sql = 
                "INSERT INTO fi_gastos_tipos 
                (nombre, entidad, fk_parent, activo )
                VALUE
                (:nombre, :entidad, :fk_parent, 1);";


        $db = $this->db->prepare($sql);

        $db->bindValue(':entidad'   , $this->entidad    ,   PDO::PARAM_INT);
        $db->bindValue(':nombre'    , $this->nombre     , 	PDO::PARAM_STR);
        $db->bindValue(':fk_parent' , $this->fk_parent  , 	PDO::PARAM_INT);
                
        $ejecutado = $db->execute();


        if (!$ejecutado) {
                $this->sql = $sql;
                $this->error = implode(", ", $db->errorInfo())." ". implode(", ", $this->db->errorInfo() );
                $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
                $this->Error_SQL();
                $respuesta['exito']     =   0;
                $respuesta['mensaje']   =   $this->error;
        } else {
            $respuesta['exito']     =   1;
            $respuesta['mensaje']   =   "Cuenta Creada Correctamente";
            $respuesta['accion']    =   "crear";

        }


    return $respuesta;
    }

    public function fetch_cuenta( $id ){

        $sql = "SELECT u.* ,
                (select count(fi_europa_gastos.rowid) from fi_europa_gastos where fi_europa_gastos.fk_gasto  = u.rowid  and fi_europa_gastos.entidad = :entidad ) as documentos_afectados
        FROM fi_gastos_tipos u 

        WHERE u.rowid = :rowid";

        $db = $this->db->prepare($sql);
        $db->bindValue(':rowid'     , $id            , PDO::PARAM_INT);
        $db->bindValue(':entidad'   , $this->entidad , PDO::PARAM_INT);
        $ejecutado = $db->execute();
        
        $u = $db->fetch(PDO::FETCH_ASSOC);


        $this->id              = $u['rowid'];
        $this->fk_parent       = $u['fk_parent'];
        $this->nombre          = $u['nombre'];
        $this->entidad         = $u['entidad'];
        $this->require_cedula  = $u['require_cedula'];
        $this->activo          = $u['activo'];
        
        
        
        
        
        if ( $ejecutado ) {
            $respuesta['exito'] = 1;    
            $respuesta['id'] = $u['rowid'];
            $respuesta['fk_parent'] = $u['fk_parent'];
            $respuesta['nombre'] = $u['nombre'];
            $respuesta['entidad'] = $u['entidad'];
            $respuesta['require_cedula'] = $u['require_cedula'];
            $respuesta['activo'] = $u['activo'];
            $respuesta['documentos_afectados']  =   $u['documentos_afectados'];
            $respuesta['selector_html']     = $this->cargar_selector( $u['fk_parent']  );

        } else {
            $this->sql = $sql;
            $this->error = implode(", ", $db->errorInfo())." ". implode(", ", $this->db->errorInfo() );
            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();
            $respuesta['exito']     =   0;
            $respuesta['mensaje']   =   $this->error;

        } 


        
            return $respuesta;


    }


//'id' => $this->db->lastInsertId()



    public function obtener_cuentas( $fk_parent ){
        $respuesta['exito'] = 1;    
        $respuesta['selector_html']     = $this->cargar_selector( $fk_parent  );
        return $respuesta;
    }

    

  /**************************************************************************** **/
  //
  //  FUnction Que se encarga de Cargar el Select 
  //
  //************************************************************************** * */
  public function cargar_selector($selected){  

                $sql = "select * from fi_gastos_tipos  where (fk_parent is null or fk_parent = 0 ) and (entidad = ".$this->entidad." and  activo = 1 ) ";
                $db  = $this->db->prepare( $sql );
                $db->execute( );

                $resultado ="<option value=''>Cuenta Gastos</option>";
                while ( $gastos_primer_nivel = $db->fetch(PDO::FETCH_OBJ))
                {


                $sel     = ($selected == $gastos_primer_nivel->rowid) ? "selected='selected' " : "";
                $ico     = ($selected == $gastos_primer_nivel->rowid) ? "&#x2714;" : "";



                    $resultado.=
                    '<option 
                        '.$sel.'

                        value="'
                    . $gastos_primer_nivel->rowid 
                    . '" > '
                    . $ico ." ".$gastos_primer_nivel->nombre
                . '</option>';


                $resultado.= $this->cargar_selector_Otro_nivel( 
                        $gastos_primer_nivel->rowid 
                    , $gastos_primer_nivel->nombre   
                    , $selected );


                }


    return $resultado;
}


public function cargar_selector_Otro_nivel( $nivel , $detalle ,  $selected)  {  


                    $sql = "select * from fi_gastos_tipos where fk_parent   =  $nivel  and (entidad = ".$this->entidad."  and  activo = 1  )   ";
                    $db  = $this->db->prepare( $sql );
                    $db->execute( );

                    while ( $gastos_nivel = $db->fetch(PDO::FETCH_OBJ))
                {

                $sel     = ($selected == $gastos_nivel->rowid) ? "selected='selected' " : "";
                $ico     = ($selected == $gastos_nivel->rowid) ? "&#x2714;" : "";


                    $resultado.=
                    '<option 
                        '.$sel.'
                        value="'
                    . $gastos_nivel->rowid 
                    . '" > '
                    . $detalle
                    . ' > '
                    . $ico." "
                    . $gastos_nivel->nombre
                . '</option>';

                $detalle2 = $detalle. ' > '.$gastos_nivel->nombre;  

                $resultado.= $this->cargar_selector_Otro_nivel2( 
                        $gastos_nivel->rowid 
                    , $detalle2 
                    , $selected );



                }


    return $resultado;
}


            public function borrarGasto()
            {
                        try {

                            $sql = "UPDATE fi_gastos SET borrado = 1, borrado_fecha = now(), borrado_fk_usuario = :borrado_fk_usuario WHERE rowid = :rowid AND entidad = :entidad ";

                            $db = $this->db->prepare($sql);
                            $db->bindValue(':rowid', $this->rowid, PDO::PARAM_STR);
                            $db->bindValue(':borrado_fk_usuario', $this->borrado_fk_usuario, PDO::PARAM_STR);
                            $db->bindValue(':entidad', $this->entidad, PDO::PARAM_STR);
                            $result =    $db->execute();
                            $response = array('success' => true, 'message' => 'Registro borrado con éxito');

                            return $response;
                        } catch (PDOException $e) {
                            $response = array('success' => false, 'message' => $e->getMessage());
                            return $response;
                        }
                }


public function cargar_selector_Otro_nivel2( $nivel , $detalle ,  $selected)  {  


    $sql = "select * from fi_gastos_tipos  where fk_parent   =  $nivel  and (entidad = ".$this->entidad."  and  activo = 1  )    ";
    $db  = $this->db->prepare( $sql );
    $db->execute( );

    while ( $gastos_nivel = $db->fetch(PDO::FETCH_OBJ))
    {

      $sel  = ($selected == $gastos_nivel->rowid) ? "selected='selected' " : "";
      $ico  = ($selected == $gastos_nivel->rowid) ? "&#x2714;" : "";


        $resultado.=
        '<option 
             '.$sel.'
              value="'
           . $gastos_nivel->rowid 
           . '" > '
           . $detalle
           . ' > '
           . $ico." "
           . $gastos_nivel->nombre
      . '</option>';


    }


    return $resultado;
}


//Borrando un tipo de Gasto
public function eliminar_tipo_gasto()
{
    // Verificar si hay elementos asociados en la tabla 'fi_europa_gastos'
    $checkQuery = "SELECT COUNT(*) FROM fi_europa_gastos WHERE fk_gasto = :rowid;";
    $checkStmt = $this->db->prepare($checkQuery);
    $checkStmt->bindValue(':rowid', $this->fk_gasto, PDO::PARAM_INT);
    $checkStmt->execute();
    $count = $checkStmt->fetchColumn();

    $respuesta = [];

    if ($count > 0) {
        // Si hay elementos cargados, devolver un error
        $respuesta['exito'] = 0;
        $respuesta['mensaje'] = 'No se puede eliminar el tipo de gasto porque hay elementos cargados.';
        return $respuesta;
    }

    // Si no hay elementos, proceder a la eliminación
    $query = "UPDATE fi_gastos_tipos SET activo = 0 WHERE entidad = :entidad AND rowid = :rowid;";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
    $stmt->bindValue(':rowid', $this->fk_gasto, PDO::PARAM_INT);

    $a = $stmt->execute();

    if (!$a) {
        $this->sql = $query;
        $this->error = implode(", ", $this->db->errorInfo());
        $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
        $this->Error_SQL();

        $respuesta['exito'] = 0;
        $respuesta['mensaje'] = 'Error ' . $this->error;
    } else {
        $respuesta['exito'] = 1;
        $respuesta['mensaje'] = 'Eliminado con éxito';
        $respuesta['id'] = $this->fk_gasto;
    }

    return $respuesta;
}



public function actualizar_pago_gasto() {
    $sql = "UPDATE fi_europa_gastos SET pagado = 1 ,fk_usuario_pagar = :fk_usuario_pagar , fecha_pago = :fecha_pago WHERE entidad = :entidad AND rowid = :rowid;";
    $db = $this->db->prepare($sql);
    $db->bindValue(':rowid', $this->id, PDO::PARAM_INT);
    $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
    $db->bindValue(':fk_usuario_pagar', $this->fk_usuario_pagar, PDO::PARAM_INT);
    $db->bindValue(':fecha_pago', $this->fecha_pago, PDO::PARAM_STR);

    $ejecutado = $db->execute();

    if (!$ejecutado) {
        $this->sql = $sql;
        $this->error = implode(", ", $db->errorInfo()) . " " . implode(", ", $this->db->errorInfo());
        $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
        $this->Error_SQL();

        $respuesta['exito'] = 0;
        $respuesta['mensaje'] = $this->error;
    } else {
        $respuesta['exito'] = 1;
        $respuesta['mensaje'] = "Pago actualizado correctamente";
        $respuesta['accion'] = "actualizacion";
    }

    return $respuesta;
}





}
