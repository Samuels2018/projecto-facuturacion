<?php

include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/object/documento_mercantil.object.php");
 
class Albaran_venta   extends  documento_mercantil
{

    public  $db;

    public function __construct($db, $entidad)
    {

        //if (empty($entidad)){ /* No puede ser vacio */ return false; }
        $this->db = $db;
        $this->documento_txt['plural']      = "Albaranes Ventas";
        $this->documento_txt['singular']    = "Albarán Venta";
        $this->tipo_aeat                    = "otros_no_aeat";
        $this->documento                    = "fi_europa_albaranes_ventas";
        $this->documento_detalle            = "fi_europa_albaranes_ventas_detalle";
        $this->documento_configuracion_serie= "Albaran-";
        $this->entidad                      = $entidad;
        $this->nombre_clase                 = "Albaran_venta";
        $this->cliente_proveedor            = "cliente";
        $this->listado_url                  = "albaran_venta_listado";
        $this->ruta_detalle_contenido       = "mod_europa_albaran_venta/ajax/ver_venta_items.ajax.php";
        $this->ver_url                      = "albaran_venta";
        $this->diccionario                  = "diccionario_albarenes_venta_europa_diccionario";


        parent::__construct($db, $entidad);  // Esto inicializa la clase SEGURIDAD

        $this->cargar_configuracion_documento($this->entidad);

        $this->campos_minimos_borrador = ['monto_total_unico'];
    }


 
      

 
 
}
