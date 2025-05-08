<?php
//----------------------------------------------------------------------------------------------------------
//
//          dbermejo@avancescr.com
//          David Bermejo
//          4001-6311
//
//----------------------------------------------------------------------------------------------------------

class Proyectos extends Seguridad
{
    private $db;
    private $entidad;

    public $id;
    public $nombre;
    public $referencia;
    public $ubicacion_mapa;
    public $fk_tercero;
    public $estado;
    public $etiquetas;
    public $monto;
    public $fecha_inicio;
    public $fecha_fin;
    public $creado_fecha;
    public $creado_fk_usuario;
    public $borrado;
    public $borrado_fecha;
    public $borrado_fk_usuario;

    function __construct($db, $entidad)
    {
        $this->db = $db;
        $this->entidad = $entidad;
        parent::__construct($db, $entidad);
    }

    public function crear_proyecto()
    {
        $sqlVerify = "SELECT COUNT(1) as conteo FROM fi_proyectos WHERE referencia = :referencia AND entidad = :entidad ";
        $dbVerify = $this->db->prepare($sqlVerify);
        $dbVerify->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $dbVerify->bindValue(':referencia', $this->referencia, PDO::PARAM_STR);
        $ejecutadoVerify = $dbVerify->execute();
        $conteoVerify = $dbVerify->fetch(PDO::FETCH_ASSOC);
        if($conteoVerify["conteo"]  != 0) {
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = "Ya existe un proyecto con la misma referencia";
            return $respuesta;
        }

        $sql = "INSERT INTO fi_proyectos 
                (nombre, referencia, ubicacion_mapa, fk_tercero, estado, etiquetas_tags, monto, fecha_inicio, fecha_fin, creado_fecha, creado_fk_usuario, entidad,latitud_longitud)
                VALUES 
                (:nombre, :referencia, :ubicacion_mapa, :fk_tercero, :estado, :etiquetas_tags, :monto, :fecha_inicio, :fecha_fin, NOW(), :creado_fk_usuario, :entidad , :latitud_longitud)";

        $db = $this->db->prepare($sql);
        $db->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $db->bindValue(':referencia', $this->referencia, PDO::PARAM_STR);
        $db->bindValue(':ubicacion_mapa', $this->ubicacion_mapa, PDO::PARAM_STR);
        $db->bindValue(':fk_tercero', $this->fk_tercero, PDO::PARAM_STR);
        $db->bindValue(':estado', $this->estado, PDO::PARAM_INT);
        $db->bindValue(':etiquetas_tags', $this->etiquetas_tags, PDO::PARAM_STR);
        $db->bindValue(':monto', $this->monto, PDO::PARAM_STR);
        $db->bindValue(':fecha_inicio', $this->fecha_inicio, PDO::PARAM_STR);
        $db->bindValue(':fecha_fin', $this->fecha_fin, PDO::PARAM_STR);
        $db->bindValue(':creado_fk_usuario', $this->creado_fk_usuario, PDO::PARAM_INT);
        $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $db->bindValue(':latitud_longitud', $this->latitud_longitud, PDO::PARAM_STR);

        

        $ejecutado = $db->execute();

        if (!$ejecutado) {
            $this->sql = $sql;
            $this->error = implode(", ", $db->errorInfo());
            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = $this->error;
        } else {
            $respuesta['exito'] = 1;
            $respuesta['mensaje'] = "Proyecto creado correctamente";
            $respuesta['id'] = $this->db->lastInsertId();
        }

        return $respuesta;
    }

    public function editar_proyecto()
    {
        $sqlVerify = "SELECT COUNT(1) as conteo FROM fi_proyectos WHERE referencia = :referencia AND entidad = :entidad AND rowid != :id";
        $dbVerify = $this->db->prepare($sqlVerify);        
        $dbVerify->bindValue(':id', $this->id, PDO::PARAM_INT);
        $dbVerify->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $dbVerify->bindValue(':referencia', $this->referencia, PDO::PARAM_STR);
        $ejecutado = $dbVerify->execute();
        $conteoVerify = $dbVerify->fetch(PDO::FETCH_ASSOC);

        if($conteoVerify["conteo"]  != 0) {
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = "Ya existe un proyecto con la misma referencia";
            return $respuesta;
        }

        $sql = "UPDATE fi_proyectos 
                SET nombre = :nombre, referencia = :ref, ubicacion_mapa = :ubicacion_mapa, fk_tercero = :fk_tercero, 
                    estado = :estado, etiquetas_tags = :etiquetas_tags, monto = :monto, fecha_inicio = :fecha_inicio, 
                    fecha_fin = :fecha_fin , latitud_longitud = :latitud_longitud
                WHERE entidad = :entidad AND rowid = :id";

        $db = $this->db->prepare($sql);
        $db->bindValue(':id', $this->id, PDO::PARAM_INT);
        $db->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $db->bindValue(':ref', $this->referencia, PDO::PARAM_STR);
        $db->bindValue(':ubicacion_mapa', $this->ubicacion_mapa, PDO::PARAM_STR);
        $db->bindValue(':fk_tercero', $this->fk_tercero, PDO::PARAM_STR);
        $db->bindValue(':estado', $this->estado, PDO::PARAM_INT);
        $db->bindValue(':etiquetas_tags', $this->etiquetas_tags, PDO::PARAM_STR);
        $db->bindValue(':monto', $this->monto, PDO::PARAM_STR);
        $db->bindValue(':fecha_inicio', $this->fecha_inicio, PDO::PARAM_STR);
        $db->bindValue(':fecha_fin', $this->fecha_fin, PDO::PARAM_STR);
        $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $db->bindValue(':latitud_longitud', $this->latitud_longitud, PDO::PARAM_STR);

        $ejecutado = $db->execute();

        if (!$ejecutado) {
            $this->sql = $sql;
            $this->error = implode(", ", $db->errorInfo());
            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = $this->error;
        } else {
            $respuesta['exito'] = 1;
            $respuesta['mensaje'] = "Proyecto actualizado correctamente";
        }

        return $respuesta;
    }

    public function borrar_proyecto()
    {
        $sql = "UPDATE fi_proyectos 
                SET borrado = 1, borrado_fecha = NOW(), borrado_fk_usuario = :borrado_fk_usuario
                WHERE entidad = :entidad AND rowid = :id";

        $db = $this->db->prepare($sql);
        $db->bindValue(':id', $this->id, PDO::PARAM_INT);
        $db->bindValue(':borrado_fk_usuario', $this->borrado_fk_usuario, PDO::PARAM_INT);
        $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);

        $ejecutado = $db->execute();

        if (!$ejecutado) {
            $this->sql = $sql;
            $this->error = implode(", ", $db->errorInfo());
            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = $this->error;
        } else {
            $respuesta['exito'] = 1;
            $respuesta['mensaje'] = "Proyecto eliminado correctamente";
        }

        return $respuesta;
    }

    
    public function listar_proyectos()
    {
        //Listamos los proyectos por Entidad y que no esten o borrados ni desactivados
        $sql = "SELECT * FROM fi_proyectos WHERE entidad = :entidad AND borrado = 0 AND estado = 1";
    
        $db = $this->db->prepare($sql);
        $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        
        $ejecutado = $db->execute();
        $proyectos = $db->fetchAll(PDO::FETCH_ASSOC);
    
        if ($proyectos) {
            $respuesta['exito'] = 1;
            $respuesta['mensaje'] = "Proyectos encontrados";
            $respuesta['data'] = $proyectos;
        } else {
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = "No se encontraron proyectos para la entidad";
            $respuesta['data'] = [];
        }
    
        return $respuesta;
    }
    


    public function fetch($id)
    {
        $sql = "SELECT p.*, 
                       t.nombre AS nombre_tercero, 
                       t.apellidos AS apellidos_tercero, 
                       t.email AS email_tercero 
                FROM fi_proyectos p
                LEFT JOIN fi_terceros t ON p.fk_tercero = t.rowid 
                WHERE p.entidad = :entidad AND p.rowid = :id";
    
        $db = $this->db->prepare($sql);
        $db->bindValue(':id', $id, PDO::PARAM_INT);
        $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
    
        $ejecutado = $db->execute();
        $proyecto = $db->fetch(PDO::FETCH_ASSOC);
    
        if ($proyecto) {
            $this->id = $proyecto['rowid'];
            $this->nombre = $proyecto['nombre'];
            $this->referencia = $proyecto['referencia'];
            $this->ubicacion_mapa = $proyecto['ubicacion_mapa'];
            $this->fk_tercero = $proyecto['fk_tercero'];
            $this->estado = $proyecto['estado'];
            $this->etiquetas_tags = $proyecto['etiquetas_tags'];
            $this->monto = $proyecto['monto'];
            $this->fecha_inicio = $proyecto['fecha_inicio'];
            $this->fecha_fin = $proyecto['fecha_fin'];
            $this->latitud_longitud = $proyecto['latitud_longitud'];
            $this->entidad_proyecto = $proyecto['entidad'];
            // Nuevos campos de fi_terceros
            $this->nombre_tercero = $proyecto['nombre_tercero'];
            $this->apellidos_tercero = $proyecto['apellidos_tercero'];
            $this->email_tercero = $proyecto['email_tercero'];
            $this->borrado = $proyecto['borrado'];
    
            $respuesta['exito'] = 1;
            $respuesta['mensaje'] = "Proyecto encontrado";
        } else {
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = "Proyecto no encontrado";
        }
    
        return $respuesta;
    }
}
?>
