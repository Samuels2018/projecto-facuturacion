<?php

require_once(ENLACE_SERVIDOR . "mod_entidad/object/Entidad.object.php");

class Plantilla extends  Seguridad
{
     public $entidad;

     public $db;

     public $id;
     public $plantilla_html;
     public $plantilla_css;
     public $activo;
     public $orden;
     public $titulo;
     public $tipo;
     public $defecto;
     public $creado_fk_usuario;

     public function __construct($db, $entidad = 1)
     {
          parent::__construct($db, $entidad);
          $this->entidad          = $entidad;
          $this->db          = $db;
     }

     public function obtener_plantilla_serie($id_serie)
     {
          $sql = "SELECT datos.fuente, pla.rowid, pla.plantilla_html, pla.plantilla_css, pla.activo, pla.orden, pla.titulo, pla.defecto
          FROM
		(
			(  
               SELECT '0-serie' AS fuente, pla.rowid, pla.plantilla_html, pla.plantilla_css
               FROM fi_europa_facturas_configuracion con
               INNER JOIN fi_europa_documento_plantilla pla ON pla.rowid = con.plantilla_fk
               WHERE con.entidad = :entidad
               AND pla.entidad = :entidad
               AND pla.activo = 1
               AND con.rowid = :rowid
               )
          
               UNION 
               
               (
               SELECT '1-plantilla' AS fuente, pla.rowid, pla.plantilla_html, pla.plantilla_css
               FROM fi_europa_documento_plantilla pla
               WHERE pla.activo = 1 
               AND pla.entidad = :entidad
               AND pla.defecto = 1
               ORDER BY pla.defecto
			LIMIT 1
               )
		 ) AS datos
		INNER JOIN fi_europa_documento_plantilla pla
		ON datos.rowid = pla.rowid          
		ORDER BY datos.fuente ASC
		LIMIT 1
               ";
               
          $db = $this->db->prepare($sql);
          $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
          $db->bindValue(':rowid', (intval($id_serie)>0?$id_serie:NULL), PDO::PARAM_INT);
          $db->execute();
          $u = $db->fetch(PDO::FETCH_ASSOC);
          if ($u) {
               $resultado["id"]                = $u['rowid'];
               $resultado["plantilla_html"]    = $u['plantilla_html'];
               $resultado["plantilla_css"]     = $u['plantilla_css'];
               $resultado["activo"]            = $u['activo'];
               $resultado["orden"]             = $u['orden'];
               $resultado["titulo"]            = $u['titulo'];
               $resultado["defecto"]           = $u['defecto'];
          }
          return $resultado;
     }

     public function obtener_plantilla_tipo_documento($tipo_documento='')
     {
          $sql = "SELECT pl.* FROM fi_europa_documento_plantilla pl
                    WHERE pl.tipo = :tipo
                    AND pl.entidad = :entidad
                    AND pl.borrado = 0 and pl.activo = 1
                    ORDER BY pl.orden
                    ";
          $db = $this->db->prepare($sql);
          $db->bindValue(':tipo', $tipo_documento, PDO::PARAM_STR);
          $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
          $db->execute();
          return $db->fetchAll();
     }
     // public function obtener_plantilla_tipo_documento($tipo_documento='')
     // {
     //      $sql = "SELECT pl.* FROM fi_europa_facturas_configuracion config
     //                INNER JOIN fi_europa_documento_plantilla pl ON pl.rowid = config.plantilla_fk
     //                WHERE config.tipo = :tipo
     //                AND config.entidad = :entidad
     //                AND config.serie_activa = 1 AND config.borrado = 0
     //                AND pl.borrado = 0 and pl.activo = 1
     //                ORDER BY pl.orden
     //                ";

     //      $db = $this->db->prepare($sql);
     //      $db->bindValue(':tipo', $tipo_documento, PDO::PARAM_STR);
     //      $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
     //      $db->execute();
     //      return $db->fetchAll();
     // }
     public function obtener_plantilla_entidad()
     {
          $sql = "SELECT  * FROM fi_europa_documento_plantilla  
                WHERE entidad = :entidad and activo = 1 and borrado = 0";

          $db = $this->db->prepare($sql);
          $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
          $db->execute();
          return $db->fetchAll();
     }
     public function fetch($rowid)
     {
          $sql = "SELECT * FROM fi_europa_documento_plantilla  
                WHERE entidad = :entidad and rowid = :rowid";

          $db = $this->db->prepare($sql);
          $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
          $db->bindValue(':rowid', $rowid, PDO::PARAM_INT);
          $db->execute();
          $u = $db->fetch(PDO::FETCH_ASSOC);
          if ($u) {
               $this->id                = $u['rowid'];
               $this->entidad           = $u['entidad'];
               $this->plantilla_html    = $u['plantilla_html'];
               $this->plantilla_css     = $u['plantilla_css'];
               $this->activo            = $u['activo'];
               $this->orden             = $u['orden'];
               $this->titulo            = $u['titulo'];
               $this->defecto           = $u['defecto'];
               $this->tipo           = $u['tipo'];
               $this->borrado           = $u['borrado'];
          }
     }

     public function crear_plantilla()
     {

          if($this->defecto == 1){
               $sqlValidar = "SELECT defecto FROM fi_europa_documento_plantilla  WHERE defecto = 1 and entidad =  ". $this->entidad." LIMIT  1";
               $dbValidar = $this->db->prepare($sqlValidar);
               $dbValidar->execute();
               $dataValidar = $dbValidar->fetch(PDO::FETCH_ASSOC);
               if($dataValidar["defecto"] = 1){
                    $sqlUpdate = "UPDATE fi_europa_documento_plantilla SET defecto = 0 WHERE entidad = " . $this->entidad;
                    $update_stmt = $this->db->prepare($sqlUpdate);
                    $update_stmt->execute();
               }
          }

          $sql = "
                INSERT INTO 
                    fi_europa_documento_plantilla
                SET 
                    entidad                 = :entidad              ,
                    plantilla_html               = :plantilla_html            ,
                    plantilla_css                    = :plantilla_css                 ,
                    activo     = 1  ,
                    creado_fecha      = NOW()   ,
                    titulo       = :titulo   ,
                    orden       = :orden   ,
                    defecto       = :defecto   ,
                    tipo           = :tipo,
                    creado_fk_usuario = :creado_fk_usuario
            ";
          $insert_stmt = $this->db->prepare($sql);

          $insert_stmt->bindValue(':entidad', $this->entidad);
          $insert_stmt->bindValue(':plantilla_html', htmlspecialchars($this->plantilla_html, ENT_QUOTES, 'UTF-8'), PDO::PARAM_STR);
          $insert_stmt->bindValue(':plantilla_css', htmlspecialchars($this->plantilla_css, ENT_QUOTES, 'UTF-8'), PDO::PARAM_STR);
          $insert_stmt->bindValue(':orden', $this->orden, PDO::PARAM_INT);
          $insert_stmt->bindValue(':titulo', $this->titulo, PDO::PARAM_STR);
          $insert_stmt->bindValue(':defecto', $this->defecto, PDO::PARAM_INT);
          $insert_stmt->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
          $insert_stmt->bindValue(':creado_fk_usuario', $this->creado_fk_usuario, PDO::PARAM_INT);

          if ($insert_stmt->execute()) {
               $a = ['exito' => 1, 'mensaje' => 'Plantilla insertada correctamente', 'id' => $this->db->lastInsertId()];
          } else {
               $this->sql = $sql;
               $this->error = implode(", ", $insert_stmt->errorInfo()) . " " . implode(", ", $this->db->errorInfo());
               $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
               $this->Error_SQL();
               $a =  ['exito' => 0, 'mensaje' =>  $this->error];
          }

          return $a;
     }
     public function actualizar_plantilla()
     {
          if($this->defecto == 1){
               $sqlValidar = "SELECT defecto FROM fi_europa_documento_plantilla  WHERE defecto = 1 and rowid != ".$this->id." and entidad =  ". $this->entidad." LIMIT  1";
               $dbValidar = $this->db->prepare($sqlValidar);
               $dbValidar->execute();
               $dataValidar = $dbValidar->fetch(PDO::FETCH_ASSOC);
               if($dataValidar["defecto"] = 1){
                    $sqlUpdate = "UPDATE fi_europa_documento_plantilla SET defecto = 0 WHERE rowid != ".$this->id." and entidad = " . $this->entidad;
                    $update_stmt = $this->db->prepare($sqlUpdate);
                    $update_stmt->execute();
               }
          }
          
          $sql = "
                update 
                     fi_europa_documento_plantilla
                 SET 
                    plantilla_html               = :plantilla_html            ,
                    plantilla_css                    = :plantilla_css                 ,
                    orden     = :orden  ,
                    titulo       = :titulo   ,
                    defecto       = :defecto,
                    tipo       = :tipo,
                    activo         = :activo
                 WHERE rowid = :rowid and entidad = :entidad
             ";
          $insert_stmt = $this->db->prepare($sql);

          $insert_stmt->bindValue(':rowid', $this->id, PDO::PARAM_INT);
          $insert_stmt->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
          $insert_stmt->bindValue(':orden', $this->orden, PDO::PARAM_INT);
          $insert_stmt->bindValue(':plantilla_html', htmlspecialchars($this->plantilla_html, ENT_QUOTES, 'UTF-8'), PDO::PARAM_STR);
          $insert_stmt->bindValue(':plantilla_css', htmlspecialchars($this->plantilla_css, ENT_QUOTES, 'UTF-8'), PDO::PARAM_STR);
          $insert_stmt->bindValue(':titulo', htmlspecialchars($this->titulo, ENT_QUOTES, 'UTF-8'), PDO::PARAM_STR);
          $insert_stmt->bindValue(':defecto', $this->defecto, PDO::PARAM_INT);
          $insert_stmt->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
          $insert_stmt->bindValue(':activo', $this->activo, PDO::PARAM_INT);

          if ($insert_stmt->execute()) {
               $a = ['exito' => 1, 'mensaje' => 'Plantilla Actualizada correctamente'];
          } else {
               $this->sql = $sql;
               $this->error = implode(", ", $insert_stmt->errorInfo()) . " " . implode(", ", $this->db->errorInfo());
               $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
               $this->Error_SQL();
               $a =  ['exito' => 0, 'mensaje' =>  $this->error];
          }
          return $a;
     }
     public function borrar_plantilla($id)
     {
          $sql = "
             UPDATE fi_europa_documento_plantilla
             SET activo = 0, borrado = 1, borrado_fecha = NOW(), borrado_fk_usuario = :borrado_fk_usuario 
             WHERE rowid = :id AND entidad = :entidad
         ";

          $update_stmt = $this->db->prepare($sql);
          $update_stmt->bindValue(':id', $id);
          $update_stmt->bindValue(':entidad', $this->entidad);
          $update_stmt->bindValue(':borrado_fk_usuario', $this->borrado_fk_usuario);
          $update_stmt->execute();

          if ($update_stmt->execute()) {
               $a = ['exito' => 1, 'mensaje' => 'Plantilla Eliminada  correctamente'];
          } else {
               $this->sql = $sql;
               $this->error = implode(", ", $update_stmt->errorInfo()) . " " . implode(", ", $this->db->errorInfo());
               $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
               $this->Error_SQL();
               $a =  ['exito' => 0, 'mensaje' =>  $this->error];
          }
          return $a;
     }

     
     public function duplicar_plantilla($id)
     {
          $sql = "INSERT INTO fi_europa_documento_plantilla (
               entidad, 
               plantilla_html, 
               plantilla_css, 
               activo, 
               creado_fecha, 
               creado_fk_usuario, 
               borrado, 
               orden, 
               tipo,
               titulo, 
               defecto
           ) 
           (SELECT 
               entidad,
               plantilla_html,
               plantilla_css,
               1,
               NOW(), 
               :creado_fk_usuario, 
               0, 
               (SELECT (IFNULL(MAX(orden),0)+1) FROM fi_europa_documento_plantilla WHERE entidad = :entidad ), 
               CONCAT(titulo, (SELECT (IFNULL(MAX(orden),0)+1) FROM fi_europa_documento_plantilla WHERE entidad = :entidad )), 
               tipo,
               0
           FROM 
               fi_europa_documento_plantilla
           WHERE 
               rowid = :id
           )";
           
          $update_stmt = $this->db->prepare($sql);
          $update_stmt->bindValue(':id', $id);
          $update_stmt->bindValue(':entidad', $this->entidad);
          $update_stmt->bindValue(':creado_fk_usuario', $this->creado_fk_usuario);

          if ($update_stmt->execute()) {
               $a = ['exito' => 1, 'mensaje' => 'Plantilla duplicada correctamente'];
          } else {
               $this->sql = $sql;
               $this->error = implode(", ", $update_stmt->errorInfo()) . " " . implode(", ", $this->db->errorInfo());
               $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
               $this->Error_SQL();
               $a =  ['exito' => 0, 'mensaje' =>  $this->error];
          }
          return $a;
     }
}
