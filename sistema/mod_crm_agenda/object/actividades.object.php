<?php

include_once(ENLACE_SERVIDOR . "mod_seguridad/object/seguridad.object.php");


class Actividades_
{
    private $dbh;
    /*public $rowid;
    public $nombre;
    public $creado_fk_usuario;
    public $creado_fecha;
    public $color;
    public $icono;
*/
    public function __construct($dbh)
    {
        global $_SESSION;
        $this->dbh = $dbh;
    }


    public function fetchActividadesAgenda($obj, $usuario, $entidad)
    {
        $where = " ";

        if ($obj['tipo'] === 'usuario') {
            $where .= " and  t.fk_usuario_asignado =" . $usuario;
        } else {
            $where = " ";
        }

        $start = $obj['start'];
        $end = $obj['end'];
        $where .= " and t.creado_fecha BETWEEN '" . $start . "' AND '" . $end . "'";
        $where .= " and t.entidad=" . $entidad;
         $sql = "SELECT 
         t.rowid, 
		 da.nombre as tarea	,
         concat(te.nombre,' ',te.apellidos)  as cliente,
         DATEDIFF(t.vencimiento_fecha, t.creado_fecha) AS dias_vencimiento,
 		 concat(fu.nombre, ' ', fu.apellidos) as usuario,

        da.color,
         da.icono,
        t.creado_fecha,
         t.vencimiento_fecha,
         de.etiqueta as estado,
         t.fk_usuario_asignado,
         t.comentario,
         t.comentario_cierre,
         t.fk_estado

        FROM  fi_oportunidades_actividades t 
        INNER JOIN ".$_ENV["DB_NAME_PLATAFORMA"].".usuarios fu ON t.fk_usuario_asignado = fu.rowid
        INNER JOIN diccionario_crm_actividades da ON t.fk_diccionario_actividad = da.rowid
        INNER JOIN fi_oportunidades o ON t.fk_oportunidad = o.rowid 
		left JOIN fi_terceros te ON te.rowid = o.fk_tercero
		INNER JOIN diccionario_crm_actividades_estado as de ON t.fk_estado = de.rowid

        where t.tipo='tarea' " . $where;

        $db = $this->dbh->prepare($sql);
        $db->execute();
        $eventosData = $db->fetchAll(PDO::FETCH_OBJ);

        $eventos = [];
        $horaExtra = 0; // Variable para almacenar las horas extra que se deben agregar
        $minutosExtra = 0;
        foreach ($eventosData as $events_info) {
            // Convertir las fechas a objetos DateTime para poder manipularlas
            $fechaInicio = new DateTime($events_info->vencimiento_fecha);
            $fechaInicio->add(new DateInterval('PT' . $minutosExtra . 'M'));
            // Ajustar la fecha de fin para que el evento dure solo 30 minutos
            $fechaFin = clone $fechaInicio;
            $fechaFin->add(new DateInterval('PT30M'));
            setlocale(LC_TIME, 'spanish'); // Configura la localización a español


            $eventos[] = [
                'title' => $events_info->tarea,
                'client' => $events_info->cliente,
                'user' => $events_info->usuario,
                'icon' => 'fa ' . $events_info->icono,
                'color' => $events_info->color, // Color del evento
                'textColor' => $events_info->color, // Color del texto del evento
                'start_original' => date('d-m-Y H:i', strtotime($events_info->creado_fecha)),
                'end_original' => date('d-m-Y H:i', strtotime($events_info->vencimiento_fecha)),
                'end_original2' =>  date('d-m-Y', strtotime($events_info->vencimiento_fecha)),

                'start' =>  $fechaInicio->format('Y-m-d H:i:s'),
                'end' =>  $fechaFin->format('Y-m-d H:i:s'),
                'estado' => $events_info->estado,
                'duration' => 10,
                'comentario'=>$events_info->comentario,
                'comentario_cierre'=>$events_info->comentario_cierre,
                'fk_estado'=>$events_info->fk_estado,
                'id_oportunidad_actividad'=>$events_info->rowid
            ];
            $minutosExtra = $minutosExtra + 30;
        }
        return $eventos;
    }

    public function select_estados()
    {
        $sql = "SELECT rowid, etiqueta, color FROM diccionario_crm_actividades_estado";
        $db = $this->dbh->prepare($sql);
        $db->execute();
        
        $resultados = $db->fetchAll(PDO::FETCH_OBJ);

        $opciones = '';
        foreach ($resultados as $resultado) {
            $opciones .= '<option value="' . $resultado->rowid . '">' . $resultado->etiqueta . '</option>';
        }
    
        return $opciones;
    }
}
