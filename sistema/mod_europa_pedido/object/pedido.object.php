<?php

include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/object/documento_mercantil.object.php");
require_once ENLACE_SERVIDOR . "mod_logs/LoggerSistema.php";

class Pedido extends  documento_mercantil
{
    public  $db;
    
    // Función __construct que acepta una conexión a la base de datos
    public function __construct($db, $entidad)
    {
        //if (empty($entidad)){ /* No puede ser vacio */ return false; }
        $this->db = $db;
        $this->documento_txt['plural']      = "Pedidos";
        $this->documento_txt['singular']    = "Pedido";
        $this->tipo_aeat                    = "otros_no_aeat";
        $this->documento                    = "fi_europa_pedidos";
        $this->documento_detalle            = "fi_europa_pedidos_detalle";
        $this->documento_configuracion_serie= "Pedido-";
        $this->entidad                      = $entidad;
        $this->nombre_clase                 = "Pedido";
        $this->cliente_proveedor            = "cliente";
        $this->listado_url                  = "pedido_listado";
        $this->ruta_detalle_contenido       = "mod_europa_pedido/ajax/ver_pedido_items.ajax.php";
        $this->ver_url                      = "pedido";
        $this->diccionario                  = "diccionario_pedidos_europa_diccionario";




        parent::__construct($db, $entidad);  // Esto inicializa la clase SEGURIDAD

        $this->cargar_configuracion_documento($this->entidad);

        $this->campos_minimos_borrador = ['monto_total_unico'];
    }
    
}