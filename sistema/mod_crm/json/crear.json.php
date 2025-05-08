<?php 
	
	if (!defined('ENLACE_SERVIDOR')) {
        session_start(); 
        require_once('../../conf/conf.php');
    }


    include(ENLACE_SERVIDOR."mod_cotizaciones/object/cotizaciones.object.php");
    include(ENLACE_SERVIDOR."mod_crm/object/oportunidad.object.php");
    include(ENLACE_SERVIDOR.'mod_crm_actividades/object/actividades.object.php');



    $Oportunidad  = new Oportunidad( $dbh  ,  $_SESSION['Entidad']  );
    $Oportunidad->fetch($_POST['fk_oportunidad']);


 


    //PRIMERO CREAREMOS LA COTIZACION PARA DESPUES CREAR LA ACTIVIDAD
    $fk_oportunidad = $_POST['fk_oportunidad'];
    $fk_moneda = $_POST['fk_moneda'];
    $Cotizacion =  new Cotizacion($dbh  ,  $_SESSION['Entidad'] );
    $Cotizacion->entidad            = $_SESSION['Entidad'];
    $Cotizacion->referencia         = 'Borrador 1';  // Esto normalmente se calculará de alguna forma
    $Cotizacion->fecha              = $Oportunidad->creado_fecha;
    $Cotizacion->fecha_vencimiento  = $Oportunidad->creado_fecha;
    $Cotizacion->fk_tercero         = $Oportunidad->fk_tercero;
    $Cotizacion->tipo               = 1;
    $Cotizacion->forma_pago         = 1;
    $Cotizacion->detalle            = 'Viene De la Generaci&oacute;n '.$Oportunidad->consecutivo;
    $Cotizacion->estado             = 0;
    $Cotizacion->moneda = $fk_moneda;
    $respuesta = $Cotizacion->nuevo_crear( $_SESSION['usuario'] );
    //lA VARIABLE RESPUESTA OBTENE EL ID de la cotizacion*/


    $consulta = "SELECT fi_oportunidades_servicios.*, fi_productos.label AS nombre_producto , fi_productos.descripcion AS descripcion_producto FROM fi_oportunidades_servicios, fi_productos WHERE fi_oportunidades_servicios.fk_oportunidad = $fk_oportunidad AND fi_oportunidades_servicios.fk_producto = fi_productos.rowid";

    $sqlA = $dbh->prepare($consulta); // Prepare your query with PDO
    $sqlA->execute(); // Once it is prepared execute it
    $servicios_items =  $sqlA->fetchAll(PDO::FETCH_OBJ);
   
    //Vamos a insertar en el detalle de la cotizacion  AQUI 
    foreach($servicios_items as $key => $value)
    {

            $impuesto = floatval($servicios_items[$key]->precio_subtotal)  * floatval($servicios_items[$key]->precio_tipo_impuesto)/100;

        // Asignación de las propiedades del objeto $Cotizacion
            $Cotizacion->descripcion_producto =  $servicios_items[$key]->descripcion_producto;
            $Cotizacion->entidad = $_SESSION['Entidad'];
            $Cotizacion->fk_factura = $respuesta;
            $Cotizacion->label = $servicios_items[$key]->nombre_producto;
            $Cotizacion->tipo_impuesto = intval($servicios_items[$key]->precio_tipo_impuesto);
            $Cotizacion->cantidad = $servicios_items[$key]->cantidad;
            $Cotizacion->subtotal = $servicios_items[$key]->precio_subtotal;
            $Cotizacion->precio_original = $servicios_items[$key]->precio_unitario;
            $Cotizacion->impuesto = $impuesto;
            $Cotizacion->total = $servicios_items[$key]->precio_total;
            $Cotizacion->fk_producto = $servicios_items[$key]->fk_producto;
            $Cotizacion->tipo = 2; //tipo 1 producto , tipo2 = servicio
            $Cotizacion->descuento_tipo = $servicios_items[$key]->tipo_descuento;
            $Cotizacion->descuento_aplicado = floatval($servicios_items[$key]->monto_descuento);
            $Cotizacion->descuento_valor_final = $servicios_items[$key]->monto_descuento;
            $Cotizacion->CABYS_codigo = '';
            $Cotizacion->fk_lote = ''; // Asumiendo que fk_lote es un string vacío en este caso

            // Construcción y ejecución de la consulta SQL
            $sql = 
                "INSERT INTO fi_cotizacion_detalle
                   (  fk_entidad
                    , fk_factura
                    , label
                    , tipo_impuesto
                    , cantidad
                    , subtotal
                    , precio_original
                    , impuesto
                    , total
                    , fk_producto
                    , tipo
                    , descuento_tipo
                    , descuento_aplicado
                    , descuento_valor_final
                    , CABYS_codigo
                    , fk_lote
                    , descripcion
                    )
                VALUES
                    (
                      :entidad
                      , :fk_factura
                      , :label
                      , :tipo_impuesto
                      , :cantidad
                      , :subtotal
                      , :precio_original
                      , :impuesto
                      , :total
                      , :fk_producto
                      , :tipo
                      , :descuento_tipo
                      , :descuento_aplicado
                      , :descuento_valor_final
                      , :CABYS_codigo
                      , :fk_lote
                      , :descripcion
                    )";

            try {
                $db = $dbh->prepare($sql);
                $db->bindValue(':entidad',$Cotizacion->entidad,PDO::PARAM_INT);
                $db->bindValue(':fk_factura', $Cotizacion->fk_factura,                            PDO::PARAM_INT);
                $db->bindValue(':label',(empty($Cotizacion->label)) ? ' ' : $Cotizacion->label, PDO::PARAM_STR);
                $db->bindValue(':tipo_impuesto',(empty($Cotizacion->tipo_impuesto)) ? '0' : $Cotizacion->tipo_impuesto, PDO::PARAM_STR);
                $db->bindValue(':cantidad',                (empty($Cotizacion->cantidad)) ? '0' : $Cotizacion->cantidad, PDO::PARAM_STR);
                $db->bindValue(':subtotal',                (empty($Cotizacion->subtotal)) ? '0' : $Cotizacion->subtotal, PDO::PARAM_STR);
                $db->bindValue(':precio_original',         (empty($Cotizacion->precio_original)) ? '0' : $Cotizacion->precio_original, PDO::PARAM_STR);
                $db->bindValue(':impuesto',                (empty($Cotizacion->impuesto)) ? '0' : $Cotizacion->impuesto, PDO::PARAM_STR);
                $db->bindValue(':total',                   $Cotizacion->total, PDO::PARAM_STR);
                $db->bindValue(':fk_producto',             (empty($Cotizacion->fk_producto)) ? '0' : $Cotizacion->fk_producto, PDO::PARAM_STR);
                $db->bindValue(':tipo',                    $Cotizacion->tipo, PDO::PARAM_STR);
                $db->bindValue(':descuento_tipo',          (empty($Cotizacion->descuento_tipo)) ? '' : $Cotizacion->descuento_tipo, PDO::PARAM_STR);
                $db->bindValue(':descuento_aplicado',      (empty($Cotizacion->descuento_aplicado)) ? 0 : $Cotizacion->descuento_aplicado, PDO::PARAM_STR);
                $db->bindValue(':descuento_valor_final',   (empty($Cotizacion->descuento_valor_final)) ? 0 : $Cotizacion->descuento_valor_final, PDO::PARAM_STR);
                $db->bindValue(':CABYS_codigo',            (empty($Cotizacion->CABYS_codigo)) ? '' : $Cotizacion->CABYS_codigo, PDO::PARAM_STR);
                $db->bindValue(':fk_lote',                 (empty($Cotizacion->fk_lote)) ? null : $Cotizacion->fk_lote, PDO::PARAM_INT);
                $db->bindValue(':descripcion',$servicios_items[$key]->descripcion_producto);

                $a = $db->execute();

                if ($a) {
                    echo "";
                } else {
                    $errorInfo = $db->errorInfo();
                    echo "SQLSTATE error code: " . $errorInfo[0] . "<br>";
                    echo "Driver-specific error code: " . $errorInfo[1] . "<br>";
                    echo "Driver-specific error message: " . $errorInfo[2] . "<br>";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }



    }



    
    //crear actividad    
    $Actividad  = new Actividades($dbh , $_SESSION['Entidad']); 
    $Actividad->fk_oportunidad                =       $Oportunidad->rowid;
    $Actividad->fk_diccionario_actividad      =       10;
    $Actividad->vencimiento_fecha	            =      date("Y-m-d");
    $Actividad->creado_usuario		        =       $_SESSION['usuario'];
    $Actividad->comentario			        =       "Cotizacion Creada ";
    $Actividad->fk_usuario_asignado           =      $_SESSION['usuario'];
    $Actividad->fk_estado 					=        1; 
    $Actividad->comentario_cierre 			=        "";
    $Actividad->fk_cotizacion = $respuesta;  //ID DE LA COTIZACION CREADA ARRIBA
    $Actividad->tipo                          =      "timeline";
    $Actividad->guardarTareaOportunidad();
  //  echo json_encode($respuesta);

       //REFRESCAMOS LAS COTIZACIONES    
    $listado_cotizaciones = $Oportunidad->obtener_cotizaciones_de_oportunidad($_POST['fk_oportunidad']);


                    //refrescamos las cotizaciones
                  foreach($listado_cotizaciones as $key => $value)
                  {
                ?>
                  <a style="font-size: 20px; margin-top: 10px;" href="<?php echo ENLACE_WEB.'ver_cotizacion/'.$listado_cotizaciones[$key]->fk_cotizacion; ?>"  class="badge badge-primary">#<?php echo $listado_cotizaciones[$key]->referencia; ?></a>
                <?php
                  }
                 
