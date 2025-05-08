<?php

class TipoActividad extends  Seguridad
{

    private $db;

    public  $id;
    public  $nombre;
    public  $color;
    public  $icono;
    public  $activo;
    public  $borrado;
    public  $entidad;


    public function __construct($db, $entidad)
    {
        $this->db       = $db;
        $this->entidad  = $entidad;
        parent::__construct($db, $entidad);
    }


    public function fetch($id)
    {
        $sql = "select * from diccionario_crm_actividades   where rowid = :rowid ";
        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':rowid', $id, PDO::PARAM_INT);
        $dbh->execute();
        $datos = $dbh->fetch(PDO::FETCH_ASSOC);
        $this->nombre                     = $datos['nombre'];
        $this->color                      = $datos['color'];
        $this->icono                      = $datos['icono'];
        $this->activo                     = $datos['activo'];
        $this->id                         = $datos['rowid'];
        $this->borrado                         = $datos['borrado'];
        $this->activo                         = $datos['activo'];
    }


    public function obtener_todos($entidad)
    {
        $sql = "SELECT * FROM diccionario_crm_actividades WHERE entidad  = '" . $entidad . "' AND borrado = 0 ORDER BY nombre ASC";
        $db = $this->db->prepare($sql);
        $db->execute();
        $data = $db->fetchAll(PDO::FETCH_OBJ);
        return $data;
    }


    public function crear()
    {
        $sql = "
      INSERT INTO diccionario_crm_actividades 
      ( nombre, color, icono, activo, creado_fecha, creado_fk_usuario, entidad)
      VALUES 
      ( :nombre, :color, :icono, :activo, NOW(), :creado_fk_usuario, :entidad)
      ";

        $db = $this->db->prepare($sql);

        $db->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $db->bindValue(':color', $this->color, PDO::PARAM_STR);
        $db->bindValue(':icono', $this->icono, PDO::PARAM_STR);
        $db->bindValue(':activo', $this->activo, PDO::PARAM_INT);
        $db->bindValue(':creado_fk_usuario', $this->fk_usuario, PDO::PARAM_INT);
        $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);

        $ejecutado = $db->execute();

        if (!$ejecutado) {
            $this->sql = $sql;
            $this->error = implode(", ", $db->errorInfo()) . " " . implode(", ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = $this->error;
        } else {
            $this->id = $this->db->lastInsertId();

            $respuesta['exito'] = 1;
            $respuesta['mensaje'] = "Tipo actividad insertada correctamente";
            $respuesta['id'] = $this->id;
            $respuesta['accion'] = "crear";
        }
        return $respuesta;
    }


    public function actualizar()
    {
        $sql = "UPDATE diccionario_crm_actividades
            SET nombre = :nombre, 
                color = :color, 
                icono = :icono,
                activo = :activo
            WHERE rowid = :rowid
            AND entidad = :entidad";

        $db = $this->db->prepare($sql);

        $db->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $db->bindValue(':color', $this->color, PDO::PARAM_STR);
        $db->bindValue(':icono', $this->icono, PDO::PARAM_STR);
        $db->bindValue(':activo', $this->activo, PDO::PARAM_INT);
        $db->bindValue(':rowid', $this->id, PDO::PARAM_INT);
        $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);

        $ejecutado = $db->execute();

        if (!$ejecutado) {
            $this->sql = $sql;
            $this->error = implode(", ", $db->errorInfo()) . " " . implode(", ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = $this->error;
        } else {
            $respuesta['exito'] = 1;
            $respuesta['mensaje'] = "Tipo actividad actualizada correctamente";
            $respuesta['accion'] = "actualizar";
        }
        return $respuesta;
    }

    public function eliminar($id)
    {
        $sql = "UPDATE diccionario_crm_actividades 
            SET activo = 0, borrado = 1, borrado_fecha = NOW(), borrado_fk_usuario = :borrado_fk_usuario 
            WHERE rowid = :id   AND entidad = :entidad
        ";

        $db = $this->db->prepare($sql);

        $db->bindValue(':id', $id, PDO::PARAM_INT);
        $db->bindValue(':borrado_fk_usuario', $this->borrado_fk_usuario, PDO::PARAM_INT);
        $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);

        $ejecutado = $db->execute();

        if (!$ejecutado) {
            $this->sql = $sql;
            $this->error = implode(", ", $db->errorInfo()) . " " . implode(", ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = $this->error;
        } else {
            $respuesta['exito'] = 1;
            $respuesta['mensaje'] = "Tipo actividad borrada correctamente";
            $respuesta['accion'] = "borrar";
            $respuesta['id'] = $id;
        }
        return $respuesta;
    }

}
