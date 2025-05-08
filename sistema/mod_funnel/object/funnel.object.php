<?php

include_once(ENLACE_SERVIDOR . "mod_seguridad/object/seguridad.object.php");
class FiFunnel extends Seguridad
{
    private $db;
    public $rowid;
    public $titulo;
    public $descripcion;
    public $color;
    public $icono;
    public $creado_fecha;
    public $creado_fk_usuario;
    public $borrado;
    public $borrado_fecha;
    public $borrado_fk_usuario;
    public $entidad;
    public $detalles;
    public $fk_funnel;

    public function __construct($db)
    {
        $this->db = $db;
        parent::__construct();
    }

    public function beginTransaction()
    {
        $this->db->beginTransaction();
    }

    public function commit()
    {
        $this->db->commit();
    }

    public function rollBack()
    {
        $this->db->rollBack();
    }

    public function fetch($id)
    {
        $query = "SELECT * FROM fi_funnel WHERE rowid = ?";
        $dbh = $this->db->prepare($query);
        $dbh->bindParam(1, $id);
        $dbh->execute();
        $row = $dbh->fetch(PDO::FETCH_ASSOC);

        $this->rowid = $row['rowid'];
        $this->titulo = $row['titulo'];
        $this->descripcion = $row['descripcion'];
        $this->color = $row['color'];
        $this->icono = $row['icono'];
        $this->creado_fecha = $row['creado_fecha'];
        $this->creado_fk_usuario = $row['creado_fk_usuario'];
        $this->borrado = $row['borrado'];
        $this->borrado_fecha = $row['borrado_fecha'];
        $this->borrado_fk_usuario = $row['borrado_fk_usuario'];
        $this->entidad = $row['entidad'];

        $this->detalles =  $this->obtener_detalles_funnel($id);
    }

    function obtenerActividad()
    {
         $sql = " SELECT ca.*, fu.usuario, da.nombre as nombre_actividad 
         FROM oportunidad_actividades   ca
        LEFT JOIN diccionario_crm_actividades da on da.rowid = ca.fk_diccionario_actividad
        LEFT JOIN fi_usuarios  fu on fu.rowid = ca.fk_usuario_asignado
         WHERE ca.rowid = :rowid";
        $db = $this->db->prepare($sql);
        $db->bindValue(':rowid', $this->rowid, PDO::PARAM_INT);
        $db->execute();
        $result = $db->fetch(PDO::FETCH_OBJ);

        return $result;
    }



    public function actualizarActividad()
    {  
        
            $sql = "UPDATE oportunidad_actividades  SET comentario = :comentario, fk_estado = :fk_estado, comentario_cierre = :comentario_cierre WHERE rowid = :rowid";
            $db = $this->db->prepare($sql);
            $db->bindValue(':rowid', $this->rowid, PDO::PARAM_INT);
            $db->bindValue(':fk_estado', $this->fk_estado, PDO::PARAM_INT);
            $db->bindValue(':comentario', $this->comentario, PDO::PARAM_STR);
            $db->bindValue(':comentario_cierre', $this->comentario_cierre, PDO::PARAM_STR);
            $result = $db->execute();

            return $result;
         
    }


    function listaEstadoActividades()
    {
        $sql = "SELECT rowid, etiqueta FROM diccionario_crm_actividades_estado";
        $db = $this->db->prepare($sql);
        $db->execute();
        $result = $db->fetchAll();

        return $result;
    }
    

    public function guardarTarea()
{
    $sql = "INSERT INTO oportunidad_actividades
    SET
        fk_oportunidad = :fk_oportunidad,
        fk_diccionario_actividad = :fk_diccionario_actividad,
        creado_fecha = NOW(),
        vencimiento_fecha = :vencimiento_fecha,
        creado_usuario = :creado_usuario,
        comentario = :comentario,
        fk_usuario_asignado = :fk_usuario_asignado,
        fk_estado = :fk_estado,
        tipo = :tipo;";

    $dbh = $this->db->prepare($sql);

    $dbh->bindValue(':fk_oportunidad', $this->fk_oportunidad, PDO::PARAM_INT);
    $dbh->bindValue(':fk_diccionario_actividad', $this->fk_diccionario_actividad, PDO::PARAM_INT);
    $dbh->bindValue(':vencimiento_fecha', $this->vencimiento_fecha, PDO::PARAM_STR);
    $dbh->bindValue(':creado_usuario', $this->creado_usuario, PDO::PARAM_INT);
    $dbh->bindValue(':comentario', $this->comentario, PDO::PARAM_STR);
    $dbh->bindValue(':fk_usuario_asignado', $this->fk_usuario_asignado, PDO::PARAM_INT);
    $dbh->bindValue(':fk_estado', 1, PDO::PARAM_INT);
    $dbh->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
    $a = $dbh->execute();

    if ($a) {
        $resultado['id'] = $this->db->lastInsertId();
        $resultado['exito'] = $a;
        $resultado['mensaje'] = "Actividad creada con éxito";
    } else {
        $resultado['exito'] = 0;
        $resultado['mensaje'] = implode(", ", $dbh->errorInfo());
        $this->sql = $sql;
        $this->error = implode(", ", $dbh->errorInfo());
        $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
        $this->Error_SQL();
    }

    return $resultado;
}


    public function nuevo()
    {
        $sql = "INSERT INTO fi_funnel (
                entidad,
                titulo,
                descripcion,
                color,
                icono,
                creado_fecha,
                creado_fk_usuario
              ) VALUES (
                :entidad,
                :titulo,
                :descripcion,
                :color,
                :icono,
                :creado_fecha,
                :creado_fk_usuario
              )";

        $dbh = $this->db->prepare($sql);

        $dbh->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $dbh->bindValue(':titulo', $this->titulo, PDO::PARAM_STR);
        $dbh->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $dbh->bindValue(':color', $this->color, PDO::PARAM_STR);
        $dbh->bindValue(':icono', $this->icono, PDO::PARAM_STR);
        $dbh->bindValue(':creado_fecha', $this->creado_fecha, PDO::PARAM_STR);
        $dbh->bindValue(':creado_fk_usuario', $this->creado_fk_usuario, PDO::PARAM_INT);

        $a = $dbh->execute();

        if ($a) {

            $resultado['id']      =   $this->db->lastInsertId();
            $resultado['exito']   =   $a;
            $resultado['mensaje'] =   "Funnel creado con Exito";
        } else {
            $resultado['exito'] = 0;
            $resultado['mensaje'] =  implode(", ", $dbh->errorInfo());
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        return $resultado;
    }

    public function modificar($datos)
    {
        $sql = "UPDATE fi_funnel
            SET
            titulo = :titulo,
            descripcion = :descripcion,
            color = :color,
            icono = :icono
            WHERE rowid = :rowid";

        $dbh = $this->db->prepare($sql);

        $dbh->bindValue(':titulo', (empty($datos->titulo)) ? ' ' : $datos->titulo, PDO::PARAM_STR);
        $dbh->bindValue(':descripcion', (empty($datos->descripcion)) ? ' ' : $datos->descripcion, PDO::PARAM_STR);
        $dbh->bindValue(':color', (empty($datos->color)) ? ' ' : $datos->color, PDO::PARAM_STR);
        $dbh->bindValue(':icono', (empty($datos->icono)) ? ' ' : $datos->icono, PDO::PARAM_STR);
        $dbh->bindValue(':rowid', $datos->rowid, PDO::PARAM_INT);

        $resultado = array();
        if ($dbh->execute()) {
            $resultado['id'] = $datos->rowid;
            $resultado['exito'] = true;
            $resultado['mensaje'] = "Registro actualizado con éxito";
        } else {
            $resultado['exito'] = 0;
            $resultado['mensaje'] = implode(", ", $dbh->errorInfo());
            $this->sql = $sql;
            $this->error = implode(", ", $dbh->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        return $resultado;
    }

    public function eliminar($datos)
    {
        $sql = "UPDATE fi_funnel
            SET
            borrado = 1,
            borrado_fecha = NOW(),
            borrado_fk_usuario = :borrado_fk_usuario
            WHERE rowid = :rowid";

        $dbh = $this->db->prepare($sql);

        $dbh->bindValue(':borrado_fk_usuario', $datos->borrado_fk_usuario, PDO::PARAM_INT);
        $dbh->bindValue(':rowid', $datos->rowid, PDO::PARAM_INT);

        $resultado = array();
        if ($dbh->execute()) {
            $resultado['id'] = $datos->id;
            $resultado['exito'] = true;
            $resultado['mensaje'] = "Registro marcado como eliminado con éxito";
        } else {
            $resultado['exito'] = 0;
            $resultado['mensaje'] = implode(", ", $dbh->errorInfo());
            $this->sql = $sql;
            $this->error = implode(", ", $dbh->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        return $resultado;
    }

    public function obtener_diccionario_iconos()
    {

        $sql = "SELECT * FROM diccionario_iconos ORDER BY descripcion ASC";

        $dbh = $this->db->prepare($sql);
        $dbh->execute();

        return $dbh->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear_detalle($datos)
    {
        $nueva_posicion = $this->ultima_posicion_detalle($datos->fk_funnel) + 1;

        $sql = "INSERT INTO fi_funnel_detalle (
                fk_funnel,
                etiqueta,
                descripcion,
                creado_fecha,
                creado_fk_usuario,
                posicion,
                canvan_mostrar_como_columna
              ) VALUES (
                :fk_funnel,
                :etiqueta,
                :descripcion,
               now(),
                :creado_fk_usuario,
                :posicion,
                :canvan_mostrar_como_columna
              )";

        $dbh = $this->db->prepare($sql);

        $dbh->bindValue(':etiqueta', $datos->etiqueta, PDO::PARAM_STR);
        $dbh->bindValue(':descripcion', $datos->descripcion, PDO::PARAM_STR);
        $dbh->bindValue(':creado_fk_usuario', $datos->creado_fk_usuario, PDO::PARAM_INT);
        $dbh->bindValue(':fk_funnel', $datos->fk_funnel, PDO::PARAM_INT);
        $dbh->bindValue(':posicion', $nueva_posicion, PDO::PARAM_INT);
        $dbh->bindValue(':canvan_mostrar_como_columna', $datos->canvan_mostrar_como_columna, PDO::PARAM_INT);

        $a = $dbh->execute();

        if ($a) {
            $resultado['id']      =   $this->db->lastInsertId();
            $resultado['exito']   =   $a;
            $resultado['mensaje'] =   "Detalle creado con éxito";
        } else {
            $resultado['exito'] = 0;
            $resultado['mensaje'] = implode(", ", $dbh->errorInfo());
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        return $resultado;
    }

    public function actualizar_detalle($datos)
    {
        $sql = "UPDATE fi_funnel
            SET
            fk_estado = :fk_estado
            WHERE rowid = :rowid";

        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':detalle', $datos->detalle, PDO::PARAM_STR);
        $dbh->bindValue(':rowid', $datos->rowid, PDO::PARAM_INT);
        $a = $dbh->execute();

        $resultado = array();
        if ($dbh->execute()) {
            $resultado['id'] = $datos->rowid;
            $resultado['exito'] = true;
            $resultado['mensaje'] = "Registro actualizado con éxito";
        } else {
            $resultado['exito'] = 0;
            $resultado['mensaje'] = implode(", ", $dbh->errorInfo());
            $this->sql = $sql;
            $this->error = implode(", ", $dbh->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }
        return $resultado;
    }


    //Esto es para visualizarlos en el formulario
    public function obtener_detalles_funnel_generales($id)
    {
      $sql = "
            SELECT 
                ffd.*, 
                IFNULL((SELECT SUM(fo.total) 
                        FROM fi_oportunidades fo 
                        WHERE  fo.entidad = $this->entidad AND  fo.fk_funnel_detalle = ffd.rowid), 0) AS suma_total_funnel 
            FROM 
                fi_funnel_detalle ffd 
            WHERE 
                ffd.fk_funnel = :fk_funnel 
                AND ffd.borrado = 0 
               # AND ffd.canvan_mostrar_como_columna = 1 
            ORDER BY 
                ffd.posicion ASC
        ";
        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':fk_funnel', $id, PDO::PARAM_INT);
        $dbh->execute();

        return $dbh->fetchAll(PDO::FETCH_OBJ);
    }

    //obtener lista de detalles de un funnel
    public function obtener_listado_fi_funnel_detalle($fk_funnel)
    {
        $sql = 'SELECT * FROM `fi_funnel_detalle` WHERE fk_funnel = :fk_funnel AND borrado = 0 ORDER BY posicion ASC';

        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':fk_funnel', $fk_funnel, PDO::PARAM_INT);
        $dbh->execute();
        return $dbh->fetchAll(PDO::FETCH_OBJ);
    }



    //ESTE OBTIENE LOS DETALLES GENERALES
    public function obtener_detalles_funnel($id, $fecha = '', $busqueda='',$lista_usuarios = '', $categorias = '', $prioridades = '', $tagss = '')
    {

         // Si $fecha no está vacío, agregamos la condición para el rango de fechas
         $sql2= '';
         if (!empty($fecha))
         {
             // Separamos las fechas
             list($fecha_inicio, $fecha_fin) = explode('|', $fecha);
             // Aseguramos que las fechas estén en el formato correcto antes de añadir la cláusula
             $sql2 .= " AND fo.fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'  ";
         }

        // Búsqueda LIKE
      

        //lista_usuarios
        if(!empty($lista_usuarios))
        {
            $sql2 .= "AND fo.fk_usuario_asignado IN ($lista_usuarios)  ";

        }
        
        //Listado de categorias
        if(!empty($categorias))
        {
            $sql2 .= "AND fo.fk_categoria IN ($categorias) ";
        }

        //Listdo de prioridades
        if(!empty($prioridades))
        {
            $sql2 .= "AND fo.fk_prioridad IN ($prioridades) ";
        }

         // Listado de tags
         if (!empty($tagss))
         {
             // Dividir los tags en un array
             $tagsArray = explode(',', $tagss);
             // Construir condiciones FIND_IN_SET
             $tagsConditions = [];
             foreach ($tagsArray as $tag) {
                 $tagsConditions[] = "FIND_IN_SET('".trim($tag)."', fo.tags)";
             }
             // Unir todas las condiciones con OR
             if (!empty($tagsConditions)) {
                 $sql2 .= " AND (" . implode(' OR ', $tagsConditions) . ")";
             }
         }



        if (!empty($busqueda))
        {
            // Escapa el valor de busqueda para prevenir inyección SQL
            $sql2 .= " AND fo.etiqueta LIKE  :busqueda  ";
        }
 

      $sql = "
            SELECT 
                ffd.*, ffd.rowid AS id_funnel_detalle, 
                IFNULL((SELECT SUM(fo.total) 
                        FROM fi_oportunidades fo 
                        WHERE  fo.entidad = $this->entidad AND fo.fk_funnel_detalle = ffd.rowid  AND fo.borrado = 0  ".$sql2." ), 0) AS suma_total_funnel 
            FROM 
                fi_funnel_detalle ffd 
            WHERE 
                ffd.fk_funnel = :fk_funnel 
                AND ffd.borrado = 0 
                AND ffd.canvan_mostrar_como_columna = 1 
            ORDER BY 
                ffd.posicion ASC
        ";
        
    

        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':fk_funnel', $id, PDO::PARAM_INT);
        if (!empty($busqueda))
        {
            $dbh->bindValue(':busqueda', '%' . $busqueda . '%', PDO::PARAM_STR);
        }

        $dbh->execute();

        return $dbh->fetchAll(PDO::FETCH_OBJ);
    }

    public function eliminar_detalle($datos)
    {
        $sql = "UPDATE fi_funnel_detalle
            SET
            borrado = 1,
            borrado_fecha = NOW(),
            borrado_fk_usuario = :borrado_fk_usuario
            WHERE rowid = :rowid";

        $dbh = $this->db->prepare($sql);

        $dbh->bindValue(':borrado_fk_usuario', $datos->borrado_fk_usuario, PDO::PARAM_INT);
        $dbh->bindValue(':rowid', $datos->rowid, PDO::PARAM_INT);

        $resultado = array();
        if ($dbh->execute()) {
            $resultado['id'] = $datos->rowid;
            $resultado['exito'] = true;
            $resultado['mensaje'] = "Registro marcado como eliminado con éxito";
        } else {
            $resultado['exito'] = 0;
            $resultado['mensaje'] = implode(", ", $dbh->errorInfo());
            $this->sql = $sql;
            $this->error = implode(", ", $dbh->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        return $resultado;
    }

    public function eliminar_detalles_funnel($datos)
    {
        $sql = "UPDATE fi_funnel_detalle
            SET
            borrado = 1,
            borrado_fecha = NOW(),
            borrado_fk_usuario = :borrado_fk_usuario
            WHERE fk_funnel = :fk_funnel AND borrado = 0";

        $dbh = $this->db->prepare($sql);

        $dbh->bindValue(':borrado_fk_usuario', $datos->borrado_fk_usuario, PDO::PARAM_INT);
        $dbh->bindValue(':fk_funnel', $datos->rowid, PDO::PARAM_INT);

        $resultado = array();
        if ($dbh->execute()) {
            $resultado['id'] = $datos->rowid;
            $resultado['exito'] = true;
            $resultado['mensaje'] = "Registro marcado como eliminado con éxito";
        } else {
            $resultado['exito'] = 0;
            $resultado['mensaje'] = implode(", ", $dbh->errorInfo());
            $this->sql = $sql;
            $this->error = implode(", ", $dbh->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        return $resultado;
    }

    public function actualizar_posiciones_funnel($datos)
    {
        $sql = "UPDATE fi_funnel_detalle
            SET
            posicion = :posicion
            WHERE rowid = :rowid 
            AND borrado = 0 
            AND fk_funnel = :fk_funnel";

        $resultado = array();
        foreach ($datos->items as $item) {
            $dbh = $this->db->prepare($sql);

            $dbh->bindValue(':posicion', $item['posicion'], PDO::PARAM_INT);
            $dbh->bindValue(':fk_funnel', $datos->fk_funnel, PDO::PARAM_INT);
            $dbh->bindValue(':rowid', $item['rowid'], PDO::PARAM_INT);

            $a = $dbh->execute();

            if (!$a) {
                $resultado['exito'] = 0;
                $resultado['mensaje'] = implode(", ", $dbh->errorInfo());
                $this->sql = $sql;
                $this->error = implode(", ", $dbh->errorInfo());
                $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
                $this->Error_SQL();

                return $resultado;
            }
        }

        $resultado['id'] = $datos->fk_funnel; // Ajusta esta línea según la estructura de $datos
        $resultado['exito'] = true;
        $resultado['mensaje'] = "Funnel actualizado con éxito";

        return $resultado;
    }

    function ultima_posicion_detalle($id)
    {
        $sql = "SELECT MAX(posicion) AS posicion FROM fi_funnel_detalle WHERE fk_funnel = :fk_funnel AND borrado = 0";
        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':fk_funnel', $id, PDO::PARAM_INT);
        $dbh->execute();
        $row = $dbh->fetch(PDO::FETCH_OBJ);
        return $row->posicion;
    }

    function reasignar_posiciones_despues_de_eliminar($datos)
    {
        // Preparar la consulta para actualizar las posiciones de los elementos restantes
        $sql = "UPDATE fi_funnel_detalle
                SET posicion = posicion - 1
                WHERE fk_funnel = :fk_funnel AND borrado = 0 AND rowid > :rowid_eliminado";

        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':fk_funnel', $datos->fk_funnel, PDO::PARAM_INT);
        $dbh->bindValue(':rowid_eliminado', $datos->rowid, PDO::PARAM_INT);
        $a = $dbh->execute();

        if (!$a) {
            $resultado['exito'] = 0;
            $resultado['mensaje'] = implode(", ", $dbh->errorInfo());
            $this->sql = $sql;
            $this->error = implode(", ", $dbh->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();

            return $resultado;
        }

        $resultado['id'] = $datos->fk_funnel; // Ajusta esta línea según la estructura de $datos
        $resultado['exito'] = true;
        $resultado['mensaje'] = "Funnel actualizado con éxito";

        return $resultado;
    }

    public function cambiar_nombre_detalle($datos)
    {
        $sql = "UPDATE fi_funnel_detalle
        SET
        etiqueta = :etiqueta
        WHERE rowid = :rowid
        AND fk_funnel = :fk_funnel";

        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':etiqueta', $datos->etiqueta, PDO::PARAM_STR);
        $dbh->bindValue(':fk_funnel', $datos->fk_funnel, PDO::PARAM_INT);
        $dbh->bindValue(':rowid', $datos->rowid, PDO::PARAM_INT);
     
        $a = $dbh->execute();

        $resultado = array();
        if ($a) {
            $resultado['id'] = $datos->rowid;
            $resultado['exito'] = true;
            $resultado['mensaje'] = "Detalle actualizado con éxito";
        } else {
            $resultado['exito'] = 0;
            $resultado['mensaje'] = implode(", ", $dbh->errorInfo());
            $this->sql = $sql;
            $this->error = implode(", ", $dbh->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }
        return $resultado;
    }


    //cambiar_visualizacion_detalle 
    public function cambiar_visualizacion_detalle($datos)
    {

        $sql = "UPDATE fi_funnel_detalle
        SET
        canvan_mostrar_como_columna = :canvan_mostrar_como_columna
        WHERE rowid = :rowid
        AND fk_funnel = :fk_funnel";

        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':canvan_mostrar_como_columna', $datos->canvan_mostrar_como_columna, PDO::PARAM_INT);
        $dbh->bindValue(':fk_funnel', $datos->fk_funnel, PDO::PARAM_INT);
        $dbh->bindValue(':rowid', $datos->rowid, PDO::PARAM_INT);
     
        $a = $dbh->execute();

        $resultado = array();
        if ($a) {
            $resultado['id'] = $datos->rowid;
            $resultado['exito'] = true;
            $resultado['mensaje'] = "Visualización de detalle actualizado exitosamente";
        } else {
            $resultado['exito'] = 0;
            $resultado['mensaje'] = implode(", ", $dbh->errorInfo());
            $this->sql = $sql;
            $this->error = implode(", ", $dbh->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }
        return $resultado;
    }


}
