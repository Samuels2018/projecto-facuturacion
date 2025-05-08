<?php
include_once(ENLACE_SERVIDOR . "mod_seguridad/object/seguridad.object.php");

trait TipoEntidadTrait
{
     public function metodoDeTipoEntidadTrait()
     {
          echo "Método de TipoEntidadTrait\n";
     }
}


class TipoEntidadClass extends Seguridad
{
     use TipoEntidadTrait;

     public $tipo_entidad;
     private $db;

     public function __construct($db)
     {
          $this->db = $db;
          parent::__construct();
     }

     public function obtenerDirecciones($rowid)
     {
          //    return $tipo_entidad.' - '.$rowid;
          $sql = "SELECT e.*, d.descripcion FROM fi_direccion_tipo_entidad e
          INNER JOIN diccionario_direccion d ON d.rowid = e.fk_direccion
        WHERE e.borrado = 0 AND e.activo = 1 AND e.entidad = :entidad
        AND e.id_tipo_entidad = :rowid 
        ORDER BY activo desc, rowid desc; ";

          try {
               $dbQuery = $this->db->prepare($sql);
               $dbQuery->bindValue(':entidad', $_SESSION["Entidad"]);
               $dbQuery->bindValue(':rowid', $rowid);
               $dbQuery->execute();
               $data = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
               return $data;
          } catch (Exception $ex) {
               return $ex->getMessage();
          }
     }
}
