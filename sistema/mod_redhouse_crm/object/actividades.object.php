<?php

class Actividades
{
    private $dbh;
    public $rowid;
    public $nombre;
    public $creado_fk_usuario;
    public $creado_fecha;
    public $color;
    public $icono;

    public function __construct($dbh)
    {
        global $_SESSION;
        $this->dbh = $dbh;
    }

//nuevo tipo de actividad
    public function nuevo($obj)
    {

        try {
            // COLUMNS
            $columns = "nombre = :nombre,
                        color = :color,
                        icono = :icono";
           
            if (strlen($this->rowid) == 0) {
                $columns .= " ,creado_fk_usuario = :creado_fk_usuario,
                                        creado_fecha 	  = :creado_fecha";
            }

            // QUERY
            $sql = strlen($this->rowid) == 0 ? "INSERT INTO a_medida_redhouse_cotizaciones_diccionario_crm_actividades SET  " . $columns : "UPDATE a_medida_redhouse_cotizaciones_diccionario_crm_actividades SET " . $columns . " WHERE rowid =:rowid ";

            $db = $this->dbh->prepare($sql);


            $db->bindValue(":nombre",                   $this->nombre, PDO::PARAM_STR);
            $db->bindValue(":color",                    $this->color, PDO::PARAM_STR);
            $db->bindValue(":icono",                    $this->icono, PDO::PARAM_STR);

            if ($this->rowid == 0) {
                $db->bindValue(":creado_fk_usuario",        $this->creado_fk_usuario, PDO::PARAM_INT);
                $db->bindValue(":creado_fecha",                $this->fechaActual(), PDO::PARAM_STR);
            } else {
                $db->bindValue(":rowid",        $this->rowid, PDO::PARAM_INT);
            }

            // EXECUTE
            $result = $db->execute();

            $idlast = $this->dbh->lastInsertId();

            $resultado = ["result" => $result, "idlast" => $idlast];

            // return $result;
            return $resultado;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function fetch($id)
    {

        $sql = "SELECT rowid,
    	 			   nombre,
                       icono,
                       color 
        		FROM diccionario_crm_actividades 
        		WHERE rowid = :rowid";

        $db = $this->dbh->prepare($sql);
        $db->bindValue(':rowid', $id, PDO::PARAM_INT);
        $db->execute();
        $u                              = $db->fetch(PDO::FETCH_ASSOC);
        $this->rowid                    = $u['rowid'];
        $this->nombre                   = $u['nombre'];
        $this->color                    = $u['color'];
        $this->icono                    = $u['icono'];
       
        // RETURN

        return $this;
    } /* fin de la funcion  FETCH */

    public function fechaActual()
    {
        return date('Y-m-d H:i:s');
    }

    public function cambioEstado($id)
    {
        try {
            $sql = "UPDATE a_medida_redhouse_cotizaciones_diccionario_crm_actividades SET activo = 0 WHERE rowid =:rowid ";

            $db = $this->dbh->prepare($sql);
            $db->bindValue(':rowid', $id, PDO::PARAM_INT);
           
            $result =  $db->execute();

            return $result;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function listaTipoActividades()
    {
        $sql = "SELECT rowid, nombre FROM a_medida_redhouse_cotizaciones_diccionario_crm_actividades WHERE activo = 1";
        $db = $this->dbh->prepare($sql);
        $db->execute();
        $result = $db->fetchAll();

        return $result;
    }


    public function conteo_notificaciones_usuario_actividad()
    {
        $sql = "SELECT COUNT(*) as conteo FROM cotizaciones_v20_actividades WHERE fk_estado = 1 AND tipo ='tarea' AND fk_usuario_asignado = :fk_usuario_asignado";
        $db = $this->dbh->prepare($sql);
        $db->bindValue(':fk_usuario_asignado', $_SESSION['Usuario'], PDO::PARAM_INT);
        $db->execute();
        $result = $db->fetch(PDO::FETCH_OBJ);

        return $result;
    }
//1 es estado pendiente, rangos de hora minima y maxima a filtrar
    public function detalle_todos_actividad_filtro($fk_estado, $horas_min, $horas_max)
    {
        $sql = "SELECT ca.*, fu.usuario, ft.nombre as nombre_cliente, da.nombre as nombre_actividad
        FROM cotizaciones_v20_actividades ca 
        LEFT JOIN cotizaciones_v20 cv on cv.rowid = ca.fk_cotizacion
        LEFT JOIN fi_usuario fu on fu.rowid = ca.fk_usuario_asignado
        LEFT JOIN fi_terceros ft on ft.rowid = cv.idfi_terceros
        LEFT JOIN a_medida_redhouse_cotizaciones_diccionario_crm_actividades da on da.rowid = ca.fk_diccionario_actividad
        WHERE ca.fk_estado = :fk_estado 
        AND TIMESTAMPDIFF(HOUR, NOW(), ca.vencimiento_fecha) <= :horas_max AND TIMESTAMPDIFF(HOUR, NOW(), ca.vencimiento_fecha) >= :horas_min";
        $db = $this->dbh->prepare($sql);
       
        $db->bindValue(':fk_estado', $fk_estado, PDO::PARAM_INT);
     
        $db->bindValue(':horas_min', $horas_min, PDO::PARAM_INT);
        $db->bindValue(':horas_max', $horas_max, PDO::PARAM_INT);
        
        $db->execute();
        $result = $db->fetchAll(PDO::FETCH_OBJ);

        return $result;
    }


    public function detalle_usuario_actividad_filtro($fk_estado, $fk_usuario, $horas_min, $horas_max)
    {
        $sql = "SELECT ca.*, fu.usuario, ft.nombre as nombre_cliente, da.nombre as nombre_actividad
        FROM cotizaciones_v20_actividades ca 
        LEFT JOIN cotizaciones_v20 cv on cv.rowid = ca.fk_cotizacion
        LEFT JOIN fi_usuario fu on fu.rowid = ca.fk_usuario_asignado
        LEFT JOIN fi_terceros ft on ft.rowid = cv.idfi_terceros
        LEFT JOIN a_medida_redhouse_cotizaciones_diccionario_crm_actividades da on da.rowid = ca.fk_diccionario_actividad
        WHERE ca.fk_estado = :fk_estado 
        AND ca.fk_usuario_asignado = :fk_usuario_asignado AND TIMESTAMPDIFF(HOUR, NOW(), ca.vencimiento_fecha) <= :horas_max AND TIMESTAMPDIFF(HOUR, NOW(), ca.vencimiento_fecha) >= :horas_min";
        $db = $this->dbh->prepare($sql);
        $db->bindValue(':fk_usuario_asignado', $fk_usuario, PDO::PARAM_INT);
        $db->bindValue(':fk_estado', $fk_estado, PDO::PARAM_INT);
        $db->bindValue(':horas_min', $horas_min, PDO::PARAM_INT);
        $db->bindValue(':horas_max', $horas_max, PDO::PARAM_INT);
        
        $db->execute();
        $result = $db->fetchAll(PDO::FETCH_OBJ);

        return $result;
    }

    
    function listaEstadoActividades()
    {
        $sql = "SELECT rowid, etiqueta FROM a_medida_redhous_cotizaciones_diccionario_crm_actividades_estado";
        $db = $this->dbh->prepare($sql);
        $db->execute();
        $result = $db->fetchAll();

        return $result;
    }

    function obtenerActividad()
    {
         $sql = " SELECT ca.*, fu.nombre, da.nombre as nombre_actividad FROM a_medida_redhouse_cotizaciones_cotizaciones_actividades   ca
        LEFT JOIN a_medida_redhouse_cotizaciones_diccionario_crm_actividades da on da.rowid = ca.fk_diccionario_actividad
        LEFT JOIN fi_usuarios  fu on fu.rowid = ca.fk_usuario_asignado
         WHERE ca.rowid = :rowid";
        $db = $this->dbh->prepare($sql);
        $db->bindValue(':rowid', $this->rowid, PDO::PARAM_INT);
        $db->execute();
        $result = $db->fetch(PDO::FETCH_OBJ);

        return $result;
    }

    public function fetchActividadesAgenda($obj)
    {   
        if ($obj['tipo']==='usuario') {
            $where = " WHERE fk_usuario_asignado =" .$_SESSION['Usuario'];
        }else{
            $where = "";
        }
       
        $sql = "SELECT
        ca.rowid, 
        c.number,
        da.nombre as actividad,
        ft.nombre as cliente,
        ca.creado_fecha,
        ca.vencimiento_fecha,
        ca.comentario,
        fu.usuario,
        de.etiqueta as estado,
        ca.fk_diccionario_actividad,
        ca.fk_estado,
        ca.comentario,
        ca.comentario_cierre,
        de.color,
        da.icono,
        DATEDIFF(ca.vencimiento_fecha, ca.creado_fecha) AS dias_vencimiento 
   FROM  cotizaciones_v20_actividades ca 
INNER JOIN cotizaciones_v20 c ON ca.fk_cotizacion = c.rowid 
INNER JOIN a_medida_redhouse_cotizaciones_diccionario_crm_actividades da ON ca.fk_diccionario_actividad = da.rowid
INNER JOIN fi_terceros ft ON c.idfi_terceros = ft.rowid
INNER JOIN fi_usuario fu ON ca.fk_usuario_asignado = fu.rowid
INNER JOIN a_medida_redhous_cotizaciones_diccionario_crm_actividades_estado as de ON ca.fk_estado = de.rowid".$where;
    
        $db = $this->dbh->prepare($sql);
        $db->execute();
        $eventosData = $db->fetchAll(PDO::FETCH_OBJ);

        $eventos = [];

        foreach ($eventosData as $events_info) {
       
            $eventos[] = [
                'title' => $events_info->actividad,
                'start' =>  $events_info->vencimiento_fecha ,
                'end' =>  $events_info->vencimiento_fecha ,
                'client' => $events_info->cliente,
                'user' => $events_info->usuario,
                'estado' => $events_info->estado,
                'id_estado' => $events_info->fk_estado,
                'id_actividad' => $events_info->rowid,
                'comentario' => $events_info->comentario,
                'dias_vencimiento' => $events_info->dias_vencimiento,
                'comentario_cierre' => $events_info->comentario_cierre,
                'icon' => 'fa '. $events_info->icono,
                'color' => $events_info->color,
                'duration' => 8
            ];
        }

        return $eventos;
    }

    public function actualizarActividad()
    {  
        
            $sql = "UPDATE a_medida_redhouse_cotizaciones_cotizaciones_actividades  SET comentario = :comentario, fk_estado = :fk_estado, comentario_cierre = :comentario_cierre WHERE rowid = :rowid";
            $db = $this->dbh->prepare($sql);
            $db->bindValue(':rowid', $this->rowid, PDO::PARAM_INT);
            $db->bindValue(':fk_estado', $this->fk_estado, PDO::PARAM_INT);
            $db->bindValue(':comentario', $this->comentario, PDO::PARAM_STR);
            $db->bindValue(':comentario_cierre', $this->comentario_cierre, PDO::PARAM_STR);
            $result = $db->execute();

            return $result;
         
    }

    public function actualizarCronActividades($obj)
    {
   
        for ($i = 1; $i < 8; $i++) {

            try {

                $sql = "UPDATE cotizaciones_v20_actividades_cronjobs SET fk_cron = :fk_cron , dia = :dia, inicio = :inicio, fin = :fin, activo = :activo 
                WHERE fk_cron = :fk_cron AND dia = :dia ";

                $db = $this->dbh->prepare($sql);

                $db->bindValue(":fk_cron",                $obj['fk_cron'], PDO::PARAM_INT);
                $db->bindValue(":dia",                    $obj['dia_'. $i], PDO::PARAM_INT);
                $db->bindValue(":inicio",                 $obj['inicio_' . $i], PDO::PARAM_STR);
                $db->bindValue(":fin",                    $obj['fin_' . $i], PDO::PARAM_STR);
                $db->bindValue(":activo",                 isset($obj['activo_' . $i]) ? 1 : 0, PDO::PARAM_INT);


                $result = $db->execute();
            } catch (PDOException $e) {
                // Si ocurre un error, se captura la excepción y se muestra la información del error
                $errorInfo = $db->errorInfo();
                echo "Error: " . $errorInfo[2];
            }
        }
        return $result;
    }  

    public function fetchCronActividades($id)
    {

        $sql = "SELECT rowid,
    	 			   fk_cron,
                       dia,
                       inicio,
                       fin,
                       activo 
        		FROM cotizaciones_v20_actividades_cronjobs 
        		WHERE fk_cron = :fk_cron ORDER BY dia ASC";

        $db = $this->dbh->prepare($sql);
        $db->bindValue(':fk_cron', $id, PDO::PARAM_INT);
        $db->execute();
        $cron                             = $db->fetchAll(PDO::FETCH_ASSOC);
       
        // RETURN

        return $cron;
    } 






    
	//----------------------------------------------------------------
	//
	//
	//   Agregar las actividades
	//
	//
	//-----------------------------------------------------------------

    public function servicio_insertar() {
        $sql = "
            INSERT INTO a_medida_redhouse_cotizaciones_cotizaciones_servicios
            (
                fk_cotizacion,
                fk_producto,
                cantidad,
                precio,
                total,
                creado_fecha,
                creado_usuario,
                comentario,
                fk_estado
            )
            VALUES
            (
                :fk_cotizacion,
                :fk_producto,
                :cantidad,
                :precio,
                :total,
                NOW(),
                :creado_usuario,
                :comentario,
                :fk_estado
            )";            
    
        $stmt = $this->dbh->prepare($sql);
    
        $stmt->bindValue(':fk_cotizacion', $this->fk_cotizacion, PDO::PARAM_STR);
        $stmt->bindValue(':fk_producto', $this->fk_producto, PDO::PARAM_STR);
        $stmt->bindValue(':cantidad', $this->cantidad, PDO::PARAM_STR);
        $stmt->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $stmt->bindValue(':total', $this->total, PDO::PARAM_STR);
        $stmt->bindValue(':creado_usuario', $_SESSION['usuario'], PDO::PARAM_STR);
        $stmt->bindValue(':comentario', $this->comentario, PDO::PARAM_STR);
        $stmt->bindValue(':fk_estado', 1, PDO::PARAM_STR);
   
    
        // Ejecutar la sentencia
        $insert = $stmt->execute();
    
        if (!$insert) {
            print_r($this->dbh->errorInfo());
            print_r($stmt->errorInfo());
        }
    
        return $insert;
    }

	    public function actividad_insertar(){

		  $sql = "
		insert 
		into 
		a_medida_redhouse_cotizaciones_cotizaciones_actividades
		(
        	fk_cotizacion
		, 	fk_diccionario_actividad
		, 	creado_fecha
		,  	vencimiento_fecha
		, 	creado_usuario
		, 	comentario
		, 	fk_usuario_asignado
		, 	fk_estado
		, 	comentario_cierre
		, 	tipo
		) 
			VALUES 
		(
			:fk_cotizacion
		, 	:fk_diccionario_actividad
		, 	 NOW( )
		, 	:vencimiento_fecha
		, 	:creado_usuario
		, 	:comentario
		, 	:fk_usuario_asignado
		, 	:fk_estado
		, 	:comentario_cierre
		, 	:tipo) 
		";            
   		
		
		    $stmt = $this->dbh->prepare($sql);

		  $stmt->bindValue(':fk_cotizacion'				, $this->fk_cotizacion			, PDO::PARAM_INT);
		  $stmt->bindValue(':fk_diccionario_actividad'	, $this->fk_diccionario_actividad, PDO::PARAM_INT);
		  $stmt->bindValue(':vencimiento_fecha'			, $this->vencimiento_fecha	, PDO::PARAM_STR);
		  $stmt->bindValue(':creado_usuario'			, $this->creado_usuario		, PDO::PARAM_INT);
		  $stmt->bindValue(':comentario'				, $this->comentario			, PDO::PARAM_STR);
		  $stmt->bindValue(':fk_usuario_asignado'		, $this->fk_usuario_asignado, PDO::PARAM_INT);
		  $stmt->bindValue(':fk_estado'					, $this->fk_estado			, PDO::PARAM_INT);
		  $stmt->bindValue(':comentario_cierre'			, $this->comentario_cierre	, PDO::PARAM_STR);
		  $stmt->bindValue(':tipo'						, $this->tipo				, PDO::PARAM_STR);

		 // Ejecutar la sentencia
		 $insert = $stmt->execute();

         if (!$insert){
            print_r($this->dbh->errorInfo());
            print_r($stmt->errorInfo());

         }
         return $insert;

       

	} // fin del insert




    public function obtenerRegiondeDia( $region )
	{
		$sql = "SELECT * FROM diccionario_cotizaciones_v20_regiones where rowid = :region ";
		$db = $this->dbh->prepare($sql);
		$db->bindValue(":region" , $region , PDO::PARAM_INT);
		$db->execute();
		$data   = $db->fetch(PDO::FETCH_OBJ);

		return $data->dias;

	}


} 