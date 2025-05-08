<?php

include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/object/documento_mercantil.object.php");
 
class Albaran_compra   extends  documento_mercantil
{

    public  $db;

    public function __construct($db, $entidad)
    {

        //if (empty($entidad)){ /* No puede ser vacio */ return false; }
        $this->db = $db;
        $this->documento_txt['plural']      = "Albaranes Compras";
        $this->documento_txt['singular']    = "Albarán Compra";
        $this->tipo_aeat                    = "otros_no_aeat";
        $this->documento                    = "fi_europa_albaranes_compras";
        $this->documento_detalle            = "fi_europa_albaranes_compras_detalle";
        $this->documento_configuracion_serie= "Albaran-Venta-";
        $this->entidad                      = $entidad;
        $this->nombre_clase                 = "Albaran_compra";
        $this->cliente_proveedor            = "proveedor";
        $this->listado_url                  = "albaranes_listado";
        $this->ruta_detalle_contenido       = "mod_europa_albaran_compra/ajax/ver_compra_items.ajax.php";
        $this->ver_url                      = "ver_albaran";
        $this->diccionario                  = "diccionario_albarenes_compra_europa_diccionario";

        parent::__construct($db, $entidad);  // Esto inicializa la clase SEGURIDAD

        $this->cargar_configuracion_documento($this->entidad);

        $this->campos_minimos_borrador = ['monto_total_unico'];
    }


 
      

 
 
}
