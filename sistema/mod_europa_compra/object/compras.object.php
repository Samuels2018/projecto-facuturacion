<?php

require_once ENLACE_SERVIDOR . "mod_logs/LoggerSistema.php";
include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/object/documento_mercantil.object.php");

class Compra extends  documento_mercantil
{

    public  $db;

    public $serie_proveedor;

    // Función __construct que acepta una conexión a la base de datos
    public function __construct($db, $entidad)
    {

        //if (empty($entidad)){ /* No puede ser vacio */ return false; }
        $this->db = $db;
        $this->documento_txt['plural']      = "Compras";
        $this->documento_txt['singular']    = "Compra";
        $this->tipo_aeat                    = "otros_no_aeat";
        $this->documento                    = "fi_europa_compras";
        $this->documento_detalle            = "fi_europa_compras_detalle";
        $this->cliente_proveedor            = "proveedor";
        $this->documento_configuracion_serie= "Compra-";
        $this->entidad                      = $entidad;
        $this->nombre_clase                 = "Compra";
        $this->listado_url                  = "compra_listado";
        $this->ruta_detalle_contenido       = "mod_europa_compra/ajax/ver_compra_items.ajax.php";
        $this->ver_url                      = "compra";
        $this->diccionario                  = "diccionario_compra_europa_diccionario";

        parent::__construct($db, $entidad);  // Esto inicializa la clase SEGURIDAD

        $this->cargar_configuracion_documento($this->entidad);

        $this->campos_minimos_borrador = ['monto_total_unico'];
    }

    public function fetch_compra($id)
    {
        $query = "
        SELECT 
            c.serie_proveedor
        FROM 
            {$this->documento} c 
        WHERE 
        c.rowid = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->serie_proveedor       = $row['serie_proveedor'];
        } else {
            return false;
        }
    }

    public function actualizar_compra()
    {
        // Actualiza el estado del documento
        $sql = "UPDATE {$this->documento} SET serie_proveedor = :serie_proveedor WHERE entidad = :entidad and  rowid = :rowid ";
        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $dbh->bindValue(':rowid', $this->id, PDO::PARAM_INT);
        $dbh->bindValue(':serie_proveedor', $this->serie_proveedor, PDO::PARAM_STR);
        
        $c = $dbh->execute();

        if (!$c) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
            $this->Error_SQL();
        }
    }

    public function validar_serie_proveedor($serie_proveedor){
        $query = "SELECT c.serie_proveedor FROM  {$this->documento} c WHERE c.serie_proveedor = :serie_proveedor ";
        if( !empty($this->id) ){
            $query .= " AND rowid != :rowid ";
        }
        $query .= " LIMIT 1 ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":serie_proveedor", $serie_proveedor, PDO::PARAM_STR);
        if( !empty($this->id) ){
            $stmt->bindParam(":rowid", $this->id, PDO::PARAM_INT);
        }
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $mensaje['mensaje'] = "La Serie de Proveedor ya existe";
            $mensaje['error'] = 1;
        } else {
            $mensaje['mensaje'] = "La Serie de Proveedor no existe";
            $mensaje['error'] = 0;
        }
        return $mensaje;
    }
     
}
