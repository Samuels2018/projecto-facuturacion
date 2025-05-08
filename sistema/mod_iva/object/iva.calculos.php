<?php

//-----------------------------------------------------
//
// Clase Creada para el Calculo de IVA
//
//
//-----------------------------------------------------

class ReportesIVA
{

    private $db;
    public $ivas;
 
    public function __construct($db, $idEntidad)
    {
        $this->db      = $db;
        $this->entidad = $idEntidad;
        ini_set('memory_limit', '2048M');}

    //-----------------------------------------------------
    //
    //  Calculo el ultimo del dia del mes de una fecha dada
    //
    //------------------------------------------------------
    public function ultimo_dia_mes($fecha)
    {
        $month = date('m', strtotime($fecha));
        $year  = date('Y', strtotime($fecha));
        $day   = date("Y-m-d", mktime(0, 0, 0, $month + 1, 0, $year));

        return $day;

    }

    //-----------------------------------------------------
    //
    //  Calculo el ultimo del dia del mes de una fecha dada
    //
    //------------------------------------------------------
    public function saber_mes($nombremes)
    {

        $mes[1]  = 'Enero';
        $mes[2]  = 'Febrero';
        $mes[3]  = 'Marzo';
        $mes[4]  = 'Abril';
        $mes[5]  = 'Mayo';
        $mes[6]  = 'Junio';
        $mes[7]  = 'Julio';
        $mes[8]  = 'Agosto';
        $mes[9]  = 'Setiembre';
        $mes[10] = 'Octubre';
        $mes[11] = 'Noviembre';
        $mes[12] = 'Diciembre';

        $fecha_mes = $mes[(int) date('m', strtotime($nombremes))];

        return $fecha_mes;

    }

    //---------------------------------------------------------------------
    //
    //  Obtiene los diferentes Tipos de IVA
    //
    //---------------------------------------------------------------------
    public function diccionario_datos()
    {

        $sql = "SELECT * FROM `diccionario_iva` where activo = 1  ";
        $db  = $this->db->prepare($sql);
        $db->execute();
        while ($obj = $db->fetch(PDO::FETCH_OBJ)) {
            $this->ivas[] = $obj->porcentaje;
        }

        $db->closeCursor();
        $db = null;

    }

   
    public function ventas_por_impuesto($inicio = null, $fin = null, $ACTIVIDAD = null)
    {
        
       

        //---------------------------------------------
        //  Totales a manejar
        //--------------------------------------------

     
        $ACTIVIDAD_FECHA = array();

        $ACTIVIDAD_FECHA_SOLO_ACEPTADOS                 = array();
        $ACTIVIDAD_FECHA_SOLO_ACEPTADOS_RESUMEN_MENSUAL = array();

        //--------------------------------------------
        $inicio    = (is_null($inicio)) ? " 2021-01-01" : $inicio;
        $fin       = (is_null($fin)) ? date("Y-m-d") : $fin;
        $actividad = (is_null($ACTIVIDAD)) ? "" : " and  (f.actividad = '$ACTIVIDAD' or f.actividad is NULL ) ";

          $sql = 
          "select
              f.electronica_enviada
            , f.electronica_resultado
            , f.electronica_error
            , f.actividad
            , f.fecha
            , f.moneda    /*obtiene la moneda, el TIPO lo sacamos de la BD General  */
            , f.consecutivo
            , f.referencia
            , f.rowid   as FacturaID
            , (CASE WHEN (LENGTH(fp.label) > 0) THEN fp.label ELSE d.label END) as label
            , (d.subtotal * d.cantidad) as Subtotal_armado
            , d.impuesto              /* Monto Total impuesto de la  linea*/
            , d.tipo_impuesto         /* Porcentaje  */
            , d.tipo                  /* Servicio o Producto */
            , d.descuento_aplicado    /* porcentaje de descuento */
            , d.descuento_valor_final /* monto de descuento */
          from        fi_facturas         f
          inner join  fi_facturas_detalle d on d.fk_factura = f.rowid
          left  join  fi_productos fp on fp.rowid  = d.fk_producto
          where f.entidad  =  " . $this->entidad . "
          and  f.estado   >  0
          $actividad
          and  f.fecha   BETWEEN   '$inicio'  and '$fin'
          order by f.moneda, f.rowid;";
          
   
          
        $db = $this->db->prepare($sql);
        $db->execute();

     //   echo "<tr> <td> <b style='color:blue;'> $sql </b> </td> </tr>  ";
        $i = 0;
        while ($obj = $db->fetch(PDO::FETCH_OBJ)) {

            if ($obj->electronica_enviada == 0) {$estado = 'pendiente';} else if ($obj->electronica_enviada == 1 && $obj->electronica_resultado == 0) {$estado = 'enviada';} else if ($obj->electronica_enviada == 1 && $obj->electronica_resultado == 1 && $obj->electronica_error == 1) {$estado = 'rechazada';} else if ($obj->electronica_enviada == 1 && $obj->electronica_resultado == 1 && $obj->electronica_error == 0) {$estado = 'aceptada';} else { $estado = "ERROR_EN_INFORME";}

           //
            if ($estado == "aceptada") { 
                 


                // CACULATE TOTAL
                $total       = (($obj->Subtotal_armado + $obj->impuesto) - $obj->descuento_valor_final);
 
 
                $ACTIVIDAD_FECHA_SOLO_ACEPTADOS_RESUMEN_MENSUAL_COLONIZADO
                [$obj->actividad]
                [date('Y', strtotime($obj->fecha))]
                [(int) date('m', strtotime($obj->fecha))]
                [$obj->tipo_impuesto] += $COLONIZADO;

                $factura['fecha']             = $obj->fecha;
                $factura['tipo_impuesto']     = $obj->tipo_impuesto;
                $factura['moneda']            = $obj->moneda;
                $factura['impuesto']          = $obj->impuesto;
                $factura['COLONIZADO']        = $COLONIZADO;
                 $factura['detalle']           = $obj->label;
                $factura['consecutivo']       = "Documento ". $obj->consecutivo;
                $factura['subtotal']          = $obj->Subtotal_armado;
                $factura['FacturaID']         = $obj->FacturaID;
                $factura['tipo']              = $obj->tipo;
                $factura['porc_descuento']    = $obj->descuento_aplicado;
                $factura['monto_descuento']   = $obj->descuento_valor_final;
                $factura['total']             = $total;

                $ACTIVIDAD_FECHA_SOLO_ACEPTADOS_RESUMEN_DETALLADO["" . $obj->actividad][] = $factura;
                unset($factura);

                $i++;

            }
        }

        //-------------------------------------------------------------------------------------------------------------------------
        //
        //   Ahora Restamos las NOTAS de CREDITO
        //
        //-------------------------------------------------------------------------------------------------------------------------

        $sql = 
          "select
              f.electronica_enviada
            , f.electronica_resultado
            , f.electronica_error
            , f.actividad
            , f.fecha
            , f.moneda    /*obtiene la moneda, el TIPO lo sacamos de la BD General  */
            , f.referencia
            , f.rowid   as FacturaID
            , fp.label
            , (d.subtotal * d.cantidad) as Subtotal_armado
            , d.impuesto              /* Monto Total impuesto de la  linea*/
            , d.tipo_impuesto         /* Porcentaje  */
            , d.tipo                  /* Servicio o Producto */
            , d.descuento_aplicado    /* porcentaje de descuento */
            , d.descuento_valor_final /* monto de descuento */
          from        fi_notas_credito          f
          inner join  fi_notas_credito_detalle  d on d.fk_nota  = f.rowid
          left join  fi_productos fp on fp.rowid  = d.fk_producto
          where f.entidad  =  " . $this->entidad . "
          and  f.estado   >  0
          $actividad
          and  f.fecha   BETWEEN   '$inicio'  and '$fin';";
        $db = $this->db->prepare($sql);
        $db->execute();
        $i++;

    //  echo "<tr> <td> <b style='color:orange;'> $sql </b> </td> </tr>  ";
        $i = 0;
        while ($obj = $db->fetch(PDO::FETCH_OBJ)) {

 
            if ($obj->electronica_enviada == 0) {$estado = 'pendiente';} else if ($obj->electronica_enviada == 1 && $obj->electronica_resultado == 0) {$estado = 'enviada';} else if ($obj->electronica_enviada == 1 && $obj->electronica_resultado == 1 && $obj->electronica_error == 1) {$estado = 'rechazada';} else if ($obj->electronica_enviada == 1 && $obj->electronica_resultado == 1 && $obj->electronica_error == 0) {$estado = 'aceptada';} else { $estado = "ERROR_EN_INFORME";}

           
            if ($estado == "aceptada") {
                
                  
            
            
                
                $ACTIVIDAD_FECHA_SOLO_ACEPTADOS_RESUMEN_MENSUAL_COLONIZADO
                [$obj->actividad]
                [date('Y', strtotime($obj->fecha))]
                [(int) date('m', strtotime($obj->fecha))]
                [$obj->tipo_impuesto] -= $COLONIZADO;

                $factura['fecha']             = $obj->fecha;
                $factura['tipo_impuesto']     = $obj->tipo_impuesto;
                $factura['moneda']            = $obj->moneda;
                $factura['impuesto']          = $obj->impuesto;
                $factura['COLONIZADO']        = $COLONIZADO * -1;
                $factura['detalle']           = $obj->label;
                $factura['consecutivo']       = "Nota Credito " . $obj->referencia;
                $factura['subtotal']          = $obj->Subtotal_armado;
                $factura['FacturaID']         = $obj->FacturaID;
                $factura['tipo']              = $obj->tipo;
                $factura['porc_descuento']    = $obj->descuento_aplicado;
                $factura['monto_descuento']   = $obj->descuento_valor_final;
                $factura['total']             = '-'.$total;

 
            
                $ACTIVIDAD_FECHA_SOLO_ACEPTADOS_RESUMEN_DETALLADO["" . $obj->actividad][] = $factura;
                unset($factura);

                $i++;

            }

        }

        //------------------------------------------------------------------------------------------------------------------------

        // Resultados GLOBALES
        $resultados['ACTIVIDAD_FECHA_SOLO_ACEPTADOS_RESUMEN_DETALLADO']          = $ACTIVIDAD_FECHA_SOLO_ACEPTADOS_RESUMEN_DETALLADO;
        $resultados['ACTIVIDAD_FECHA_SOLO_ACEPTADOS_RESUMEN_MENSUAL_COLONIZADO'] = $ACTIVIDAD_FECHA_SOLO_ACEPTADOS_RESUMEN_MENSUAL_COLONIZADO;

        return $resultados;
    }

} // fin de la CLASE
