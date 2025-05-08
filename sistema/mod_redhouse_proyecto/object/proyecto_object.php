<?php

class redhouse_proyecto
{
    private $dbh;
    public function __construct($dbh, $entidad = 5)
    {
        global $_SESSION;
        $this->db = $dbh;
        $this->entidad = $entidad;

    }

    //Vamos a insertar la información de proyecto 
    public function insertar()
    {

        $sql = " SELECT COUNT(*) + 1 AS total_proyectos FROM a_medida_redhouse_proyecto WHERE YEAR(creado_fecha) = YEAR(CURDATE()); ";
        $db = $this->db->prepare($sql);
        $result =  $db->execute();
        $datos  =  $db->fetch(PDO::FETCH_OBJ);

        $consecutivo_proyecto  =  "PR-".substr("000000".$datos->total_proyectos, -5)."-". date("Y");    

        $query = "
            INSERT INTO 
            a_medida_redhouse_proyecto
            (fk_cotizacion, proyecto_consecutivo, proyecto_fecha, proyecto_descripcion, 
            proyecto_lugar, proyecto_contacto, proyecto_tipo_cambio, 
            creado_fecha, creado_fk_usuario, borrado,proyecto_estado) 
            VALUES 
            (:fk_cotizacion, :proyecto_consecutivo, :proyecto_fecha, :proyecto_descripcion, 
            :proyecto_lugar, :proyecto_contacto, :proyecto_tipo_cambio, 
            NOW(), :creado_fk_usuario, :borrado,:proyecto_estado)";

        $stmt = $this->db->prepare($query);

        // Asignar valores a los parámetros
        $params = [
            ':fk_cotizacion' => $this->fk_cotizacion,
            ':proyecto_consecutivo' => $consecutivo_proyecto,
            ':proyecto_fecha' => $this->proyecto_fecha,
            ':proyecto_descripcion' => $this->proyecto_descripcion,
            ':proyecto_lugar' => $this->proyecto_lugar,
            ':proyecto_contacto' => $this->proyecto_contacto,
            ':proyecto_tipo_cambio' => $this->proyecto_tipo_cambio,
            ':creado_fk_usuario' =>$this->creado_fk_usuario,
            ':borrado' =>0,
            ':proyecto_estado' =>1,
        ];

        // Muestra la consulta SQL con los valores
        $debugQuery = $this->debugQuery($query, $params);

        // Ejecutar la consulta
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        if ($stmt->execute()) {
            $idCreado = $this->db->lastInsertId();
            $this->id = $idCreado; //Aqui obtenemos el rowsid nuevo ahora vamos a insertar los detalles

           
            //La segunda consulta a utilizar para guardar los detalles
            // Consulta para obtener los registros
            $sql2 = "SELECT * FROM `a_medida_redhouse_cotizaciones_cotizaciones_servicios` WHERE fk_cotizacion = :fk_cotizacion";
            $db2 =  $this->db->prepare($sql2);
            $db2->bindValue(':fk_cotizacion', $this->fk_cotizacion);
            $db2->execute();

            // Preparar la consulta de inserción
            $insertSql = "
                INSERT INTO a_medida_redhouse_proyecto_presupuesto 
                (fk_proyecto, fk_producto, cantidad, precio_unitario, precio_subtotal, 
                precio_tipo_impuesto, precio_total, creado_fecha, creado_usuario, 
                comentario, cantidad_dias, tipo_duracion, fk_estado) 
                VALUES 
                (:fk_proyecto, :fk_producto, :cantidad, :precio_unitario, :precio_subtotal, 
                :precio_tipo_impuesto, :precio_total, :creado_fecha, :creado_usuario, 
                :comentario, :cantidad_dias, :tipo_duracion, :fk_estado)";

            // Preparar la sentencia de inserción
            $insertStmt =  $this->db->prepare($insertSql);

            // Ejecutar la inserción para cada registro obtenido
            while ($data = $db2->fetch(PDO::FETCH_OBJ))
            {
                $insertStmt->bindValue(':fk_proyecto',$idCreado); // Asumiendo que fk_proyecto es igual a fk_cotizacion
                $insertStmt->bindValue(':fk_producto', $data->fk_producto);
                $insertStmt->bindValue(':cantidad', $data->cantidad);
                $insertStmt->bindValue(':precio_unitario', $data->precio_unitario);
                $insertStmt->bindValue(':precio_subtotal', $data->precio_subtotal);
                $insertStmt->bindValue(':precio_tipo_impuesto', $data->precio_tipo_impuesto);
                $insertStmt->bindValue(':precio_total', $data->precio_total);
                $insertStmt->bindValue(':creado_fecha', $data->creado_fecha);
                $insertStmt->bindValue(':creado_usuario', $data->creado_usuario);
                $insertStmt->bindValue(':comentario', $data->comentario);
                $insertStmt->bindValue(':cantidad_dias', $data->cantidad_dias);
                $insertStmt->bindValue(':tipo_duracion', $data->tipo_duracion);
                $insertStmt->bindValue(':fk_estado', $data->fk_estado);
                // Ejecutar la inserción
                $insertStmt->execute();
            }
            //rEGISTRAMOS LA RESUPUESTA
            $respuesta = ['error' => 0, 'id' => $idCreado, 'mensaje_txt' => 'Proyecto creado con éxito', 'creada' => 1];


        } else {
            $respuesta = ['error' => 1, 'mensaje_txt' => $stmt->errorInfo()];
        }

        return $respuesta;

    }

    //Un proyecto
    public function fetch($fk_proyecto)
    {
         //La segunda consulta a utilizar para guardar los detalles
        // Consulta para obtener los registros
        $sql2 = "SELECT * FROM `a_medida_redhouse_proyecto` WHERE rowid = :fk_proyecto";
        $db2 =  $this->db->prepare($sql2);
        $db2->bindValue(':fk_proyecto', $fk_proyecto);
        $db2->execute();
        $data = $db2->fetch(PDO::FETCH_OBJ);
        return $data;
    }   
    //Todos los proyectos
    public function listar_proyectos()
    {
         //La segunda consulta a utilizar para guardar los detalles
        // Consulta para obtener los registros
        $sql2 = "SELECT * FROM `a_medida_redhouse_proyecto`";
        $db2 =  $this->db->prepare($sql2);
        $db2->execute();
        $data = $db2->fetchAll(PDO::FETCH_OBJ);
        return $data;
    } 


    //Vamos a verificar el proyecto asociado a la cotización
    public function buscar_cotizacion_proyecto($fk_cotizacion)
    {
         //La segunda consulta a utilizar para guardar los detalles
        // Consulta para obtener los registros
        $sql2 = "SELECT * FROM `a_medida_redhouse_proyecto` WHERE fk_cotizacion = :fk_cotizacion";
        $db2 =  $this->db->prepare($sql2);
        $db2->bindValue(':fk_cotizacion', $fk_cotizacion);
        $db2->execute();
        $data = $db2->fetch(PDO::FETCH_OBJ);
        return $data;
    }  


    public function debugQuery($query, $params) {
        foreach ($params as $key => $value) {
            // Reemplaza el marcador con el valor correspondiente
            // Asegúrate de manejar correctamente las comillas para cadenas
            $query = str_replace($key, is_null($value) ? 'NULL' : "'$value'", $query);
        }
        return $query;
    }
    
	//----------------------------------------------------------------
	//
	//
	//   Agregar los servivios
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

	  

} 