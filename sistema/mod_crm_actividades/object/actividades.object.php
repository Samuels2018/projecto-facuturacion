<?php

class Actividades extends Seguridad
{
    private $db;


    public function __construct($db, $entidad)
    {
        $this->db = $db;
        parent::__construct();
        $this->fk_entidad = $entidad;
    }




    function obtenerActividad()
    {
        $sql = " SELECT ca.*, CONCAT(fu.nombre, ' ',fu.apellidos) as usuario_asignado_txt, 
        CONCAT(fucierre.nombre, ' ',fucierre.apellidos) as usuario_cierre_txt, 
        da.nombre as nombre_actividad 
         FROM fi_oportunidades_actividades   ca
        LEFT JOIN diccionario_crm_actividades da on da.rowid = ca.fk_diccionario_actividad
        LEFT JOIN fi_usuarios  fu on fu.rowid = ca.fk_usuario_asignado
        LEFT JOIN fi_usuarios  fucierre on fucierre.rowid = ca.fk_usuario_fecha_cierre
         WHERE ca.rowid = :rowid";
        $db = $this->db->prepare($sql);
        $db->bindValue(':rowid', $this->rowid, PDO::PARAM_INT);
        $db->execute();
        $result = $db->fetch(PDO::FETCH_OBJ);

        return $result;
    }

    public function guardarTareaOportunidad()
    {

        $sql = " SELECT COUNT(*) + 1 AS total_oportunidades_actividades FROM fi_oportunidades_actividades  WHERE entidad = :fk_entidad and  YEAR(creado_fecha) = YEAR(CURDATE()); ";
        $db = $this->db->prepare($sql);
        $db->bindValue(":fk_entidad", $this->fk_entidad,  PDO::PARAM_INT);
        $result =  $db->execute();
        $datos  =  $db->fetch(PDO::FETCH_OBJ);

        $consecutivo  = substr("000000" . ($datos->total_oportunidades_actividades + 1), -5) . "-" . date("Y");

        $sql = "INSERT INTO fi_oportunidades_actividades
        SET
            fk_oportunidad = :fk_oportunidad,
            fk_diccionario_actividad = :fk_diccionario_actividad,
            creado_fecha = NOW(),
            vencimiento_fecha = :vencimiento_fecha,
            creado_usuario = :creado_usuario,
            comentario = :comentario,
            fk_usuario_asignado = :fk_usuario_asignado,
            fk_estado = :fk_estado,
            entidad = :entidad,
            tipo = :tipo,
            fk_cotizacion = :fk_cotizacion,
            consecutivo = :consecutivo
            ";

        $dbh = $this->db->prepare($sql);

        $dbh->bindValue(':fk_oportunidad', $this->fk_oportunidad, PDO::PARAM_INT);
        $dbh->bindValue(':fk_diccionario_actividad', $this->fk_diccionario_actividad, PDO::PARAM_INT);
        $dbh->bindValue(':vencimiento_fecha', $this->vencimiento_fecha, PDO::PARAM_STR);
        $dbh->bindValue(':creado_usuario', $this->creado_usuario, PDO::PARAM_INT);
        $dbh->bindValue(':comentario', $this->comentario, PDO::PARAM_STR);
        $dbh->bindValue(':fk_usuario_asignado', $this->fk_usuario_asignado, PDO::PARAM_INT);
        $dbh->bindValue(':fk_estado', 1, PDO::PARAM_INT);
        $dbh->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $dbh->bindValue(':entidad', $this->fk_entidad, PDO::PARAM_INT);
        $dbh->bindValue(':fk_cotizacion', $this->fk_cotizacion, PDO::PARAM_INT);
        $dbh->bindValue(':consecutivo', $consecutivo,  PDO::PARAM_STR);

        $a = $dbh->execute();

        if ($a) {
            $resultado['id'] = $this->db->lastInsertId();
            $resultado['exito'] = $a;
            $resultado['mensaje'] = "Actividad creada con éxito";
        } else {
            $resultado['exito'] = 0;
            $resultado['mensaje'] = implode(", ", $dbh->errorInfo());
            $this->sql = $sql;
            $this->error = implode(", ", $dbh->errorInfo()) . " " . implode(", ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        return $resultado;
    }

    public function actualizarActividad()
    {

        $sql = "UPDATE fi_oportunidades_actividades  SET comentario = :comentario, fk_estado = :fk_estado, comentario_cierre = :comentario_cierre,
            fecha_cierre = :fecha_cierre, fk_usuario_fecha_cierre = :fk_usuario_fecha_cierre
            WHERE rowid = :rowid";
        $db = $this->db->prepare($sql);
        $db->bindValue(':rowid', $this->rowid, PDO::PARAM_INT);
        $db->bindValue(':fk_estado', $this->fk_estado, PDO::PARAM_INT);
        $db->bindValue(':comentario', $this->comentario, PDO::PARAM_STR);
        $db->bindValue(':comentario_cierre', $this->comentario_cierre, PDO::PARAM_STR);
        $db->bindValue(':fecha_cierre', $this->fecha_cierre, PDO::PARAM_STR);
        $db->bindValue(':fk_usuario_fecha_cierre', $this->fk_usuario_fecha_cierre, PDO::PARAM_INT);
        $result = $db->execute();

        return $result;
    }


    function listaEstadoActividades()
    {
        $sql = "SELECT rowid, etiqueta FROM diccionario_crm_actividades_estado";
        $db = $this->db->prepare($sql);
        $db->execute();
        $result = $db->fetchAll();

        return $result;
    }

    public function guardarTarea()
    {
        $sql = "INSERT INTO fi_oportunidades_actividades
    SET
        fk_oportunidad = :fk_oportunidad,
        fk_diccionario_actividad = :fk_diccionario_actividad,
        creado_fecha = NOW(),
        vencimiento_fecha = :vencimiento_fecha,
        creado_usuario = :creado_usuario,
        comentario = :comentario,
        fk_usuario_asignado = :fk_usuario_asignado,
        fk_estado = :fk_estado,
        tipo = :tipo;";

        $dbh = $this->db->prepare($sql);

        $dbh->bindValue(':fk_oportunidad', $this->fk_oportunidad, PDO::PARAM_INT);
        $dbh->bindValue(':fk_diccionario_actividad', $this->fk_diccionario_actividad, PDO::PARAM_INT);
        $dbh->bindValue(':vencimiento_fecha', $this->vencimiento_fecha, PDO::PARAM_STR);
        $dbh->bindValue(':creado_usuario', $this->creado_usuario, PDO::PARAM_INT);
        $dbh->bindValue(':comentario', $this->comentario, PDO::PARAM_STR);
        $dbh->bindValue(':fk_usuario_asignado', $this->fk_usuario_asignado, PDO::PARAM_INT);
        $dbh->bindValue(':fk_estado', 1, PDO::PARAM_INT);
        $dbh->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $a = $dbh->execute();

        if ($a) {
            $resultado['id'] = $this->db->lastInsertId();
            $resultado['exito'] = $a;
            $resultado['mensaje'] = "Actividad creada con éxito";
        } else {
            $resultado['exito'] = 0;
            $resultado['mensaje'] = implode(", ", $dbh->errorInfo());
            $this->sql = $sql;
            $this->error = implode(", ", $dbh->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        return $resultado;
    }
}
