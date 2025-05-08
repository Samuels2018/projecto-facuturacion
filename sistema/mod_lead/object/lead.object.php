<?php

include_once(ENLACE_SERVIDOR . "mod_seguridad/object/seguridad.object.php");
class Lead extends Seguridad
{
    public $rowid;
    public $entidad;
    public $fk_funnel;
    public $fk_contacto;
    public $fk_tercero;
    public $fk_estado;
    public $modificado_fecha;
    public $fk_usuario_asignado;
    public $fk_usuario_modificado;
    public $creado_fecha;
    public $creado_fk_usuario;
    public $borrado;
    public $borrado_fecha;
    public $borrado_fk_usuario;
    // Nuevas propiedades
    public $etiqueta;
    public $nota;
    public $fk_modificado_fecha;
    public $servicios;
    public $tags;
    public $posicion_funnel;
    public $fk_funnel_detalle;
    public $consecutivo;
    public $importe;
    public $entidad_empresa;
    // Propiedades privadas
    private $db;

    public function __construct($db, $entidad_empresa)
    {

        $this->entidad_empresa = $entidad_empresa;
        $this->db = $db;
        
        $sql = "select  
        siguiente_oportunidad
     from fi_oportunidades_configuracion  
     where 
     entidad = " . $this->entidad_empresa . "    ";

        $dbh = $db->prepare($sql);
        $dbh->execute();
        $datos = $dbh->fetch(PDO::FETCH_ASSOC);

        $referencia = '0000' . $datos['siguiente_oportunidad'];
        $referencia = substr($referencia, -4);
        $this->consecutivo = $referencia;

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

    public function usuarios_disponibles()
	{

		$sql = "SELECT rowid, nombre, activo  FROM fi_usuarios  where activo = 1 AND entidad = :entidad order by nombre ASC  ";
       
        $db = $this->db->prepare($sql);
        $db->bindValue(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
		$db->execute();
		$this->usuarios_disponibles                              = $db->fetchAll(PDO::FETCH_OBJ);


		return $this->usuarios_disponibles;
	}

    public function diccionarioActividades()
	{

		$sql = "SELECT rowid, nombre, activo  FROM diccionario_crm_actividades  where activo = 1 ";
		$db = $this->db->prepare($sql);
		$db->execute();
		$this->diccionarioActividades                              = $db->fetchAll(PDO::FETCH_OBJ);


		return $this->diccionarioActividades;
	}

    public function fetch_oportunidad($id)
    {
        $sql = "SELECT 
        fo.*, 
        CONCAT(fu.nombre, ' ', fu.apellidos) AS usuario_asignado, 
        CONCAT(fu2.nombre, ' ', fu2.apellidos) AS usuario_modificado, 
        CONCAT(ft.nombre, ' ', ft.apellidos) AS cliente, 
        CONCAT(crm.nombre, ' ', crm.apellidos) AS contacto,
        crm.telefono AS contacto_telefono,
        crm.email as contacto_correo,
        (SELECT GROUP_CONCAT(fos.fk_servicio SEPARATOR ',') FROM fi_oportunidades_servicios fos WHERE fos.fk_oportunidad = fo.rowid AND borrado = 0) AS servicios
    FROM 
        fi_oportunidades fo 
    LEFT JOIN 
        fi_usuarios fu ON fo.fk_usuario_asignado = fu.rowid 
    LEFT JOIN 
        fi_usuarios fu2 ON fo.fk_usuario_modificado = fu2.rowid 
    LEFT JOIN 
        fi_terceros_crm_contactos crm ON fo.fk_contacto = crm.rowid 
    LEFT JOIN 
        fi_terceros ft ON fo.fk_tercero = ft.rowid 
    WHERE 
        fo.rowid = ? ";

        $dbh = $this->db->prepare($sql);
        $dbh->bindParam(1, $id);
        $dbh->execute();
        $row = $dbh->fetch(PDO::FETCH_ASSOC);

        // Asignar las propiedades de la clase con los valores de la fila
        $this->rowid = $row['rowid'];
        $this->entidad = $row['entidad'];
        $this->fk_funnel = $row['fk_funnel'];
        $this->fk_contacto = $row['fk_contacto'];
        $this->fk_tercero = $row['fk_tercero'];
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
        $this->cliente = $row['cliente'];
        $this->contacto = $row['contacto'];
        $this->contacto_telefono = $row['contacto_telefono'];
        $this->contacto_correo = $row['contacto_correo'];
        
        
        $this->tags = $row['tags'];
        $this->servicios = $row['servicios'];
        $this->fk_funnel_detalle = $row['fk_funnel_detalle'];
        $this->consecutivo = $row['consecutivo'];
        $this->importe = $row['importe'];

        return $this;
    }

    public function fetch($id)
    {
        $sql = "SELECT 
        fo.*, 
        CONCAT(fu.nombre, ' ', fu.apellidos) AS usuario_asignado, 
        CONCAT(fu2.nombre, ' ', fu2.apellidos) AS usuario_modificado, 
        CONCAT(ft.nombre, ' ', ft.apellidos) AS cliente, 
        CONCAT(crm.nombre, ' ', crm.apellidos) AS contacto,
        crm.telefono AS contacto_telefono,
        crm.email as contacto_correo,
        (SELECT GROUP_CONCAT(fos.fk_servicio SEPARATOR ',') FROM fi_oportunidades_servicios fos WHERE fos.fk_oportunidad = fo.rowid AND borrado = 0) AS servicios
    FROM 
        fi_oportunidades fo 
    INNER JOIN 
        fi_usuarios fu ON fo.fk_usuario_asignado = fu.rowid 
    LEFT JOIN 
        fi_usuarios fu2 ON fo.fk_usuario_modificado = fu2.rowid 
    INNER JOIN 
        fi_terceros_crm_contactos crm ON fo.fk_contacto = crm.rowid 
    INNER JOIN 
        fi_terceros ft ON fo.fk_tercero = ft.rowid 
    WHERE 
        fo.rowid = ? ";

        $dbh = $this->db->prepare($sql);
        $dbh->bindParam(1, $id);
        $dbh->execute();
        $row = $dbh->fetch(PDO::FETCH_ASSOC);

        // Asignar las propiedades de la clase con los valores de la fila
        $this->rowid = $row['rowid'];
        $this->entidad = $row['entidad'];
        $this->fk_funnel = $row['fk_funnel'];
        $this->fk_contacto = $row['fk_contacto'];
        $this->fk_tercero = $row['fk_tercero'];
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
        $this->cliente = $row['cliente'];
        $this->contacto = $row['contacto'];
        $this->contacto_telefono = $row['contacto_telefono'];
        $this->contacto_correo = $row['contacto_correo'];
        
        
        $this->tags = $row['tags'];
        $this->servicios = $row['servicios'];
        $this->fk_funnel_detalle = $row['fk_funnel_detalle'];
        $this->consecutivo = $row['consecutivo'];
        $this->importe = $row['importe'];

        return $this;
    }


    public function nuevo($datos)
    {
        // var_dump($datos);
        // die();
        if ($datos->tags != '') {
            $tags = json_decode($datos->tags);

            $tagsString = implode(', ', array_map(function ($tag) {
                return $tag->value;
            }, $tags));
        }

        $posicion_funnel = $this->ultima_posicion_lista($datos) + 1;

        $sql = "INSERT INTO fi_oportunidades (
            entidad,
            fk_funnel,
            fk_contacto,
            fk_tercero,
            fk_estado,
            etiqueta,
            nota,
            fk_usuario_asignado,
            creado_fecha,
            creado_fk_usuario,
            fk_funnel_detalle,
            tags,
            posicion_funnel,
            consecutivo,
            importe
          ) VALUES (
            :entidad,
            :fk_funnel,
            :fk_contacto,
            :fk_tercero,
            1,
            :etiqueta,
            :nota,
            :fk_usuario_asignado,
            now(),
            :creado_fk_usuario,
            :fk_funnel_detalle,
            :tags,
            :posicion_funnel,
            :consecutivo,
            :importe
          )";

        $dbh = $this->db->prepare($sql);

        $dbh->bindValue(':entidad', $datos->entidad, PDO::PARAM_INT);
        $dbh->bindValue(':fk_funnel', $datos->fk_funnel, PDO::PARAM_INT);
        $dbh->bindValue(':fk_contacto', $datos->fk_contacto, PDO::PARAM_INT);
        $dbh->bindValue(':fk_tercero', $datos->fk_tercero, PDO::PARAM_INT);
        $dbh->bindValue(':etiqueta', $datos->etiqueta, PDO::PARAM_STR);
        $dbh->bindValue(':nota', $datos->nota, PDO::PARAM_STR);
        $dbh->bindValue(':fk_usuario_asignado', $datos->fk_usuario_asignado, PDO::PARAM_INT);
        $dbh->bindValue(':creado_fk_usuario', $datos->creado_fk_usuario, PDO::PARAM_INT);
        $dbh->bindValue(':fk_funnel_detalle', $datos->fk_funnel_detalle, PDO::PARAM_INT);
        $dbh->bindValue(':tags', $datos->tags != '' ? $tagsString : null, PDO::PARAM_STR);
        $dbh->bindValue(':posicion_funnel', $posicion_funnel, PDO::PARAM_INT);
        $dbh->bindValue(':consecutivo', $this->consecutivo, PDO::PARAM_INT);
        $dbh->bindValue(':importe', $datos->importe, PDO::PARAM_INT);

        $a = $dbh->execute();

            //validar consecutivo
        $resultado = array();

        if ($a) {
            $id = $this->db->lastInsertId();
            $resultado['id'] = $id;
            $resultado['exito'] = true;
            $resultado['mensaje'] = "Oportunidad creada con éxito";
        } else {
            $resultado['exito'] = false;
            $resultado['mensaje'] = "Error al actualizar el lead: " . implode(", ", $dbh->errorInfo());
            $this->sql = $sql;
            $this->error = implode(", ", $dbh->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
            return $resultado; // Retorna el resultado inmediatamente si hay un error en la ejecución de la consulta SQL
        }

        if (!empty($datos->servicios)) {
            $datos->rowid = $id;
            // var_dump($id);
            // die();
            $crear_servicios = $this->crear_oportunidades_servicios($datos);
            if (!$crear_servicios['exito']) {
                $resultado['exito'] = false;
                $resultado['mensaje'] = "Error al crear servicios de la oportunidad: " . $crear_servicios['mensaje'];
                return $resultado;
            }
        }

        $sql = "update fi_oportunidades_configuracion set siguiente_oportunidad= (siguiente_oportunidad+1) where entidad = " . $this->entidad_empresa . "    ";
        $dbh = $this->db->prepare($sql);
        $dbh->execute();

        return $resultado;
    }

    public function modificar($datos)
    {
        // Procesamiento de tags si existen
        $tagsString = null;
        if ($datos->tags != '') {
            $tags = json_decode($datos->tags);
            $tagsString = implode(', ', array_map(function ($tag) {
                return $tag->value;
            }, $tags));
        }

        // Preparación y ejecución de la consulta SQL
        $sql = "UPDATE fi_oportunidades SET entidad = :entidad, fk_funnel = :fk_funnel, fk_contacto = :fk_contacto, fk_tercero = :fk_tercero, fk_estado = :fk_estado, etiqueta = :etiqueta, nota = :nota, modificado_fecha = NOW(), fk_usuario_asignado = :fk_usuario_asignado, fk_usuario_modificado = :fk_usuario_modificado, fk_estado = 1, fk_funnel_detalle = :fk_funnel_detalle, tags = :tags, importe = :importe WHERE rowid = :rowid";
        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':entidad', $datos->entidad, PDO::PARAM_INT);
        $dbh->bindValue(':fk_funnel', $datos->fk_funnel, PDO::PARAM_INT);
        $dbh->bindValue(':fk_contacto', $datos->fk_contacto, PDO::PARAM_INT);
        $dbh->bindValue(':fk_tercero', $datos->fk_tercero, PDO::PARAM_INT);
        $dbh->bindValue(':fk_estado', $datos->fk_estado, PDO::PARAM_INT);
        $dbh->bindValue(':etiqueta', $datos->etiqueta, PDO::PARAM_STR);
        $dbh->bindValue(':nota', $datos->nota, PDO::PARAM_STR);
        $dbh->bindValue(':fk_usuario_asignado', $datos->fk_usuario_asignado, PDO::PARAM_INT);
        $dbh->bindValue(':fk_usuario_modificado', $datos->fk_usuario_modificado, PDO::PARAM_INT);
        $dbh->bindValue(':fk_funnel_detalle', $datos->fk_funnel_detalle, PDO::PARAM_INT);
        $dbh->bindValue(':tags', $tagsString, PDO::PARAM_STR);
        $dbh->bindValue(':importe', $datos->importe, PDO::PARAM_STR);
        $dbh->bindValue(':rowid', $datos->rowid, PDO::PARAM_INT);

        $resultado = array();
        $a = $dbh->execute();

        if ($a) {
            $resultado['id'] = $datos->rowid;
            $resultado['exito'] = true;
            $resultado['mensaje'] = "Lead actualizado con éxito";
        } else {
            $resultado['exito'] = false;
            $resultado['mensaje'] = "Error al actualizar el lead: " . implode(", ", $dbh->errorInfo());
            $this->sql = $sql;
            $this->error = implode(", ", $dbh->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
            return $resultado; // Retorna el resultado inmediatamente si hay un error en la ejecución de la consulta SQL
        }

        // Procesamiento de servicios
        $servicios_actuales = explode(',', $this->fetch($datos->rowid)->servicios);

        if ($servicios_actuales != '') {

            $servicios_request = !empty($datos->servicios) ? $datos->servicios : [];

            $servicios_nuevos = array_diff($servicios_request, $servicios_actuales);
            $servicios_eliminar = array_diff($servicios_actuales, $servicios_request);

            // Validación y ejecución de las funciones de servicios
            if (!empty($servicios_nuevos)) {
                $datos->servicios = $servicios_nuevos;
                $crear_servicios = $this->crear_oportunidades_servicios($datos);
                if (!$crear_servicios['exito']) {
                    $resultado['exito'] = false;
                    $resultado['mensaje'] = "Error al crear servicios: " . $crear_servicios['mensaje'];
                    return $resultado;
                }
            }

            if (!empty($servicios_eliminar)) {
                $datos->servicios = $servicios_eliminar;
                $eliminar_servicios = $this->eliminar_oportunidades_servicios($datos);

                if (!$eliminar_servicios['exito']) {
                    $resultado['exito'] = false;
                    $resultado['mensaje'] = "Error al eliminar servicios: " . $eliminar_servicios['mensaje'];
                    return $resultado;
                }
            }
        } else {
            if (!empty($datos->servicios)) {

                $crear_servicios = $this->crear_oportunidades_servicios($datos);

                if (!$crear_servicios['exito']) {
                    $resultado['exito'] = false;
                    $resultado['mensaje'] = "Error al crear servicios: " . $crear_servicios['mensaje'];
                    return $resultado;
                }
            }
        }

        return $resultado;
    }

    public function eliminar($datos)
    {
        $sql = "UPDATE fi_oportunidades
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

    public function crear_oportunidades_servicios($datos)
    {
        $resultado = [];
        $exito = true;
        $mensaje = "";

        try {
            foreach ($datos->servicios as $servicio) {

                $sql = "INSERT INTO fi_oportunidades_servicios (
                        fk_oportunidad,
                        fk_servicio,
                        creado_fecha,
                        creado_fk_usuario
                    ) VALUES (
                        :fk_oportunidad,
                        :fk_servicio,
                         now(),
                        :creado_fk_usuario
                    )";

                $dbh = $this->db->prepare($sql);

                $dbh->bindValue(':fk_oportunidad', $datos->rowid, PDO::PARAM_INT);
                $dbh->bindValue(':fk_servicio', $servicio, PDO::PARAM_INT);
                $dbh->bindValue(':creado_fk_usuario', $datos->creado_fk_usuario, PDO::PARAM_INT);


                $a = $dbh->execute();

                if (!$a) {
                    throw new Exception("Error al insertar servicio: " . implode(", ", $dbh->errorInfo()));
                }
            }

            // Si todo salió bien, confirmar la transacción

            $resultado['exito'] = $exito;
            $resultado['mensaje'] = "Servicios asociados con éxito";
        } catch (Exception $e) {
            // Si algo falla, revertir la transacción

            $resultado['exito'] = false;
            $resultado['mensaje'] = $e->getMessage();
            $this->sql = $sql;
            $this->error = implode(", ", $dbh->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        return $resultado;
    }

    public function existe_servicio_tarea($datos)
    {

        $sql = "SELECT * FROM fi_oportunidades_servicios WHERE fk_oportunidad = :fk_oportunidad AND fk_servicio = :fk_servicio AND eliminado = 0";
        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':fk_oportunidad', $datos->fk_oportunidad, PDO::PARAM_INT);
        $dbh->bindValue(':fk_servicio', $datos->fk_servicio, PDO::PARAM_INT);
        $dbh->execute();
        $row = $dbh->fetch(PDO::FETCH_OBJ);

        return $row;
    }

    public function obtener_tareas_funnel($id, $fecha = '',$busqueda = '', $lista_usuarios = '', $categorias = '', $prioridades = '', $tagss = '')
    {
        $sql = "SELECT fo.*,
                    fod.posicion AS posicion_detalle, 
                    fo.rowid AS oportunidad_id,
                    Concat(fu.nombre, ' ', fu.apellidos) AS usuario_asignado,
                    IFNULL(Concat(ter.nombre, IF(ter.apellidos IS NOT NULL
                                        AND ter.apellidos != '', Concat(' ', ter.apellidos), '')), 'sin asignar') AS tercero,
                    Ifnull(fo.total, 0) AS importe,
                    cat.etiqueta AS categoria,
                    cat.prioridad AS prioridad_categoria,
                    cat.estilo AS estilo_categoria
                FROM   fi_oportunidades fo
                    LEFT JOIN fi_usuarios fu ON fu.rowid = fo.fk_usuario_asignado
                    LEFT JOIN fi_terceros ter ON ter.rowid = fo.fk_tercero
                    LEFT JOIN diccionario_crm_oportunidades_prioridades cat ON cat.rowid = fo.fk_prioridad
                    LEFT JOIN fi_funnel_detalle fod ON fo.fk_funnel_detalle = fod.rowid
                WHERE  fo.fk_funnel = :fk_funnel
                    AND fo.entidad = :entidad
                    AND fo.borrado = 0";

        //por busqueda LIKE
        // Búsqueda LIKE
        if (!empty($busqueda)) {
            $sql .= " AND fo.etiqueta LIKE :busqueda";
        }

        //Listado de usuarios
        if (!empty($lista_usuarios))
        {
            $sql .= " AND fo.fk_usuario_asignado  IN ($lista_usuarios)";
        }

        //Listado de categorias
        if(!empty($categorias))
        {
            $sql .= " AND fo.fk_categoria  IN ($categorias)";
        }

        //Listado de prioridades
        if(!empty($prioridades))
        {
            $sql .= " AND fo.fk_prioridad  IN ($prioridades)";
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
                $sql .= " AND (" . implode(' OR ', $tagsConditions) . ")";
            }
        }
        
        // Si $fecha no está vacío, agregamos la condición para el rango de fechas
        if (!empty($fecha))
        {
            // Separamos las fechas
            list($fecha_inicio, $fecha_fin) = explode('|', $fecha);
            // Aseguramos que las fechas estén en el formato correcto antes de añadir la cláusula
            $sql .= " AND fo.fecha BETWEEN :fecha_inicio AND :fecha_fin";
        }
        $sql .= " ORDER BY cat.prioridad ASC;";
        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':fk_funnel', $id, PDO::PARAM_INT);
        $dbh->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);

        // Asignar parámetros
        if (!empty($busqueda)) {
            $dbh->bindValue(':busqueda', '%' . $busqueda . '%', PDO::PARAM_STR);
        }

        // Si $fecha no está vacío, vinculamos los valores de las fechas
        if (!empty($fecha)) {
            $dbh->bindValue(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $dbh->bindValue(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
        }


        $dbh->execute();
        return $dbh->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cambiar_estado_oportunidad($datos)
    {
        $resultado = array();

        // Primera operación: actualizar el estado de la oportunidad
        $sql = "UPDATE fi_oportunidades SET 
        fk_funnel_detalle = :fk_funnel_detalle,
        posicion_funnel = :posicion_funnel 
        WHERE rowid = :rowid";

        $dbh = $this->db->prepare($sql);

        $dbh->bindValue(':fk_funnel_detalle', $datos->fk_funnel_detalle, PDO::PARAM_INT);
        $dbh->bindValue(':posicion_funnel', $datos->posicion_funnel, PDO::PARAM_INT); // Asegúrate de vincular también el rowid
        $dbh->bindValue(':rowid', $datos->rowid, PDO::PARAM_INT); // Asegúrate de vincular también el rowid

        if ($dbh->execute()) {
            $resultado['exito'] = true;
            $resultado['mensaje'] = "Registro actualizado con éxito";
        } else {

            $resultado['exito'] = false;
            $resultado['mensaje'] = implode(", ", $dbh->errorInfo());
            $this->sql = $sql;
            $this->error = implode(", ", $dbh->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();

            return $resultado;
        }


        return $resultado;
    }

    public function actualizar_posiciones_oportunidades($datos)
    {
        $sql = "UPDATE fi_oportunidades
            SET
            posicion_funnel = :posicion_funnel
            WHERE rowid = :rowid 
            AND fk_funnel_detalle = :fk_funnel_detalle
            AND borrado = 0 ";

        $resultado = array();
        foreach ($datos->posiciones as $item) {

            $dbh = $this->db->prepare($sql);


            $dbh->bindValue(':fk_funnel_detalle', $datos->fk_funnel_detalle, PDO::PARAM_INT);
            $dbh->bindValue(':posicion_funnel', $item['posicion_funnel'], PDO::PARAM_INT);
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

    public function registrar_log_oportunidad($datos)
    {
        // Preparar la consulta SQL para insertar un nuevo registro en la tabla
        $sql = "INSERT INTO fi_oportunidades_movimientos (fk_oportunidad, fk_oportunidad_detalle, modificado_fecha, modificado_fk_usuario) VALUES (:fk_oportunidad, :fk_oportunidad_detalle, NOW(), :modificado_fk_usuario)";

        // Crear un prepared statement
        $dbh = $this->db->prepare($sql);

        // Vincular los valores a los parámetros del prepared statement
        $dbh->bindValue(':fk_oportunidad', $datos->rowid, PDO::PARAM_INT);
        $dbh->bindValue(':fk_oportunidad_detalle', $datos->fk_funnel_detalle, PDO::PARAM_INT);
        $dbh->bindValue(':modificado_fk_usuario', $datos->fk_usuario_modificado, PDO::PARAM_INT);

        // Ejecutar el prepared statement
        if ($dbh->execute()) {
            // La inserción fue exitosa
            $resultado['exito'] = true;
            $resultado['mensaje'] = "Log actualizado con éxito";
        } else {
            // Hubo un error en la inserción
            $this->sql = $sql;
            $this->error = implode(", ", $dbh->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        return $resultado;
    }

    function ultima_posicion_lista($datos)
    {
        $sql = "SELECT MAX(posicion_funnel) AS posicion_funnel FROM fi_oportunidades 
        WHERE fk_funnel = :fk_funnel 
        AND fk_funnel_detalle = :fk_funnel_detalle 
        AND borrado = 0";

        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':fk_funnel', $datos->fk_funnel, PDO::PARAM_INT);
        $dbh->bindValue(':fk_funnel_detalle', $datos->fk_funnel_detalle, PDO::PARAM_INT);
        $dbh->execute();
        $row = $dbh->fetch(PDO::FETCH_OBJ);
        return $row->posicion_funnel;
    }


    public function obtener_servicios_tarea($datos)
    {
        $servicios = [];

        foreach ($datos->servicios as $servicio) {

            $sql = "SELECT 
            p.rowid, 
            p.descripcion, 
            ppc.impuesto, 
            ppc.subtotal, 
            ppc.total, 
            ppc.moneda,
            dm.simbolo AS simbolo 
        FROM 
            fi_productos p 
        JOIN 
            fi_productos_precios_clientes ppc ON p.rowid = ppc.fk_producto 
        JOIN 
            diccionario_monedas dm ON ppc.moneda = dm.rowid
        JOIN 
            fi_oportunidades_servicios os ON p.rowid = os.fk_servicio
        WHERE 
            p.rowid = :rowid 
            AND p.borrado = 0 
            AND p.entidad = :entidad 
        ORDER BY 
            ppc.rowid DESC 
        LIMIT 1";

            $dbh = $this->db->prepare($sql);

            $dbh->bindValue(':rowid', $servicio, PDO::PARAM_INT);
            $dbh->bindValue(':entidad', $datos->entidad, PDO::PARAM_INT);

            $dbh->execute();

            $servicios[] = $dbh->fetch(PDO::FETCH_OBJ);
        }

        return $servicios;
    }

    public function eliminar_oportunidades_servicios($datos)
    {
        $resultado = ['exito' => true]; // Inicializa 'exito' como true

        foreach ($datos->servicios as $servicio) {

            $sql = "UPDATE fi_oportunidades_servicios SET 
        borrado = 1 
        WHERE fk_oportunidad = :fk_oportunidad 
        AND fk_servicio = :fk_servicio AND borrado = 0";
            $dbh = $this->db->prepare($sql);

            $dbh->bindValue(':fk_oportunidad', $datos->rowid, PDO::PARAM_INT);
            $dbh->bindValue(':fk_servicio', $servicio, PDO::PARAM_INT);

            if (!$dbh->execute()) {
                $resultado['exito'] = false;
                $resultado['mensaje'] = "Error al eliminar servicios: " . implode(", ", $dbh->errorInfo());
                $this->sql = $sql;
                $this->error = implode(", ", $dbh->errorInfo());
                $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
                $this->Error_SQL();
                break; // Detiene el bucle si hay un error
            }
        }

        return $resultado;
    }
}
