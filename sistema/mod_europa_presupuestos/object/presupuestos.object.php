<?php

include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/object/documento_mercantil.object.php");
require_once ENLACE_SERVIDOR . "mod_logs/LoggerSistema.php";

class Presupuesto extends  documento_mercantil
{
    public  $db;

    // Función __construct que acepta una conexión a la base de datos
    public function __construct($db, $entidad)
    {
        //if (empty($entidad)){ /* No puede ser vacio */ return false; }
        $this->db = $db;
        $this->documento_txt['plural']      = "Presupuestos";
        $this->documento_txt['singular']    = "Presupuesto";
        $this->tipo_aeat                    = "otros_no_aeat";
        $this->documento                    = "fi_europa_presupuestos";
        $this->documento_detalle            = "fi_europa_presupuestos_detalle";
        $this->documento_configuracion_serie= "Pre-";
        $this->entidad                      = $entidad;
        $this->nombre_clase                 = "Presupuesto";
        $this->listado_url                  = "presupuesto_listado";
        $this->ver_url                      = "presupuesto";
        $this->ruta_detalle_contenido       = "mod_europa_presupuestos/ajax/ver_presupuesto_items.ajax.php";
        $this->diccionario                  = "diccionario_presupuesto_europa_diccionario";


        parent::__construct($db, $entidad);  // Esto inicializa la clase SEGURIDAD

        $this->cargar_configuracion_documento($this->entidad);

        $this->campos_minimos_borrador = ['monto_total_unico'];
    }
    
}