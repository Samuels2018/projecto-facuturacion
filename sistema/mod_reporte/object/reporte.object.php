
<?php


class Reporte  extends  seguridad
{
    public  $db;


    public $color_soportado;
    public $color_repercutido;
    public $color_a_pagar;
    public $condicion_documentos;

    public $DOCUMENTOS_DETALLES;


    public function __construct($db, $entidad)
    {
        $this->db = $db;
        $this->entidad                      = $entidad;
        parent::__construct($db, $entidad);  // Esto inicializa la clase SEGURIDAD

        $this->color_soportado      = "#1b9e77";
        $this->color_repercutido    = "#d95f02";
        $this->color_a_pagar        = "#7570b3";

        $this->condicion_documentos = array(
            'fi_europa_albaranes_compras' => " and f.estado > 0 and f.estado <> 3 ",
            'fi_europa_compras' => " and f.estado > 0 and f.estado <> 3 ",
            'fi_europa_presupuestos' => " and f.estado > 0 and f.estado <> 3 ",
            'fi_europa_pedidos' => " and f.estado > 0 and f.estado <> 3 ",
            'fi_europa_albaranes_ventas' => " and f.estado > 0 and f.estado <> 3 ",
            'fi_europa_facturas' => " and f.estado > 0 and f.estado <> 3 ",
        );
    }




    public function reporte_general_ivas($tabla = 'fi_europa_facturas', $primer_dia = NULL,  $ultimo_dia = NULL)
    {

        if ($tabla == 'fi_europa_facturas') {
            $where = $this->condicion_documentos[$tabla];
        } else {
            $where = " and estado > 0 ";
        }

        if (!is_null($primer_dia) and !is_null($ultimo_dia)) {
            $where .= "And  f.fecha >= '$primer_dia'  and f.fecha <=  '$ultimo_dia'  ";
        }

        // **Consulta SQL para exportar TODOS los registros**
        $sql = "
        SELECT 

        f.fk_tercero_telefono ,
        f.fk_tercero_identificacion ,
        f.fk_tercero_txt  ,

        f.referencia ,
            f.fecha ,
            YEAR(f.fecha) AS anyo,
            MONTH(f.fecha) AS mes ,
        f.subtotal_pre_retencion AS Base_Imponible,
        f.impuesto_iva AS IVA,
        f.impuesto_retencion_irpf AS Retencion_IRPF,
        
        f.IVA_21 ,
        f.IVA_4  ,
        f.IVA_0  ,
        f.IVA_10  ,
        f.total AS Total_Factura 

        FROM {$tabla}  f

        WHERE entidad = :entidad    $where  
      ";


        $db = $this->db->prepare($sql);
        $db->bindValue(":entidad", $this->entidad, PDO::PARAM_INT);
        $db->execute();
        $IVAS = array();
        $YEARS = array();

        $IVAS_PORCENTAJES[] = 'IVA_0';
        $IVAS_PORCENTAJES[] = 'IVA_4';
        $IVAS_PORCENTAJES[] = 'IVA_10';
        $IVAS_PORCENTAJES[] = 'IVA_21';

        $IVAS_TOTALES = array();


        while ($datos   = $db->fetch(PDO::FETCH_OBJ)) {
            $YEARS[$datos->anyo] = $datos->anyo;
            $IVAS[$datos->anyo][$datos->mes]['Base_imponible']   += $datos->Base_imponible;
            $IVAS[$datos->anyo][$datos->mes]['IVA']              += $datos->IVA;
            $IVAS[$datos->anyo][$datos->mes]['IVA_21']           += $datos->IVA_21;
            $IVAS[$datos->anyo][$datos->mes]['IVA_4']            += $datos->IVA_4;
            $IVAS[$datos->anyo][$datos->mes]['IVA_0']            += $datos->IVA_0;
            $IVAS[$datos->anyo][$datos->mes]['IVA_10']           += $datos->IVA_10;

            $IVAS_TOTALES_POR_IVA[$datos->anyo]['Base_imponible']   += $datos->Base_imponible;
            $IVAS_TOTALES_POR_IVA[$datos->anyo]['IVA']              += $datos->IVA;
            $IVAS_TOTALES_POR_IVA[$datos->anyo]['IVA_21']           += $datos->IVA_21;
            $IVAS_TOTALES_POR_IVA[$datos->anyo]['IVA_4']            += $datos->IVA_4;
            $IVAS_TOTALES_POR_IVA[$datos->anyo]['IVA_0']            += $datos->IVA_0;
            $IVAS_TOTALES_POR_IVA[$datos->anyo]['IVA_10']           += $datos->IVA_10;



            $IVAS_TOTALES_POR_MES[$datos->anyo][$datos->mes]['Base_imponible']   += $datos->Base_imponible;
            $IVAS_TOTALES_POR_MES[$datos->anyo][$datos->mes]['IVA']              += $datos->IVA;
            $IVAS_TOTALES_POR_MES[$datos->anyo][$datos->mes]['IVA_21']           += $datos->IVA_21;
            $IVAS_TOTALES_POR_MES[$datos->anyo][$datos->mes]['IVA_4']            += $datos->IVA_4;
            $IVAS_TOTALES_POR_MES[$datos->anyo][$datos->mes]['IVA_0']            += $datos->IVA_0;
            $IVAS_TOTALES_POR_MES[$datos->anyo][$datos->mes]['IVA_10']           += $datos->IVA_10;

            $DOCUMENTOS_DETALLES[] = $datos;
        }

        $this->IVAS_TOTALES_POR_MES  = $IVAS_TOTALES_POR_MES;
        $this->IVAS_TOTALES_POR_IVA  = $IVAS_TOTALES_POR_IVA;
        $this->IVAS                  = $IVAS;
        $this->IVAS_PORCENTAJES      = $IVAS_PORCENTAJES;
        $this->YEARS                 = $YEARS;
        $this->DOCUMENTOS_DETALLES                 = $DOCUMENTOS_DETALLES;

        return true;
    }
}
