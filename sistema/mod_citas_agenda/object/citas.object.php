<?php


class Citas extends Seguridad
{
    public $entidad;
    public $tipo;
    public $activo;
    public $creado;
    public $label;
    public $id;

    public $diccionario_estados;


    function __construct($db){
        $this->db = $db; 
        parent::__construct();  // Esto inicializa la clase SEGURIDAD
    }
 


        function fetch($id) {
                    $sql = "SELECT *
                            FROM agenda_citas
                            WHERE rowid = :rowid";
                
                            $db = $this->db->prepare($sql); // Preparar la consulta SQL
                            $db->bindValue('rowid', $id, PDO::PARAM_INT); // Enlazar el valor del parámetro
                            $db->execute(); // Ejecutar la consulta

                            $result = $db->fetch(PDO::FETCH_ASSOC); // Obtener los resultados como un array asociativo

                    if ($result) {
                    
                            $this->rowid        = $result['rowid'];
                            $this->fecha        = $result['fecha'];
                            $this->hora_inicio  = $result['hora_inicio'];
                            $this->hora_fin     = $result['hora_fin'];

                    } else {

                            $respuesta = ['error' => 1, 'mensaje_txt' => $db->errorInfo()];
                            $this->sql = $sql;
                            $this->error = implode(", ", $this->d->errorInfo()) . implode(", ", $this->db->errorInfo());
                            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
                            $this->Error_SQL();

                    }


                    return $rowid;

        } //  Funciones del Fetch





        function insert( )
        {
            $sql = "INSERT INTO agenda_citas set
             fk_cliente = :fk_cliente,
                fk_estado = :fk_estado,
                  fk_producto = :fk_producto,
                      entidad = :entidad,
                  fecha =:fecha,
               hora_inicio = :hora_inicio,
                hora_fin = :hora_fin,
                 creado_fecha = now();";
            
            $db = $this->db->prepare($sql); // Preparar la consulta SQL

            // Enlazar los valores de los parámetros
            $db->bindValue(':fecha'         , $this->fecha          , PDO::PARAM_STR); // Fecha en formato 'YYYY-MM-DD'
            $db->bindValue(':hora_inicio'   , $this->hora_inicio    , PDO::PARAM_STR); // Hora en formato 'HH:MM:SS'
            $db->bindValue(':hora_fin'      , $this->hora_fin       , PDO::PARAM_STR); // Hora en formato 'HH:MM:SS'

            $db->bindValue(':fk_cliente'      , $this->fk_cliente       , PDO::PARAM_STR);
            $db->bindValue(':fk_estado'      , $this->fk_estado       , PDO::PARAM_STR);
            $db->bindValue(':fk_producto'      , $this->fk_producto       , PDO::PARAM_STR);
            $db->bindValue(':entidad'      , $this->entidad       , PDO::PARAM_STR);
          

            
            $a =  $db->execute(); // Ejecutar la consulta

           
            
            
            if (!$a) {
                $this->sql     =   $sql;
                $this->error   =   implode(", ", $db->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
                $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
                $this->Error_SQL();

            }   else {
                
                $this->rowid = $this->db->lastInsertId();
                return $this->rowid; // Retornar el ID insertado

            }



        }  ///  fin de la funcion Insert 



/*****************************************************************************
 * 
 *          Funcion para recoger todos los datos 
 *          25 Noviembre 2024    
 * 
 ******************************************************************************/

        
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
                $where .= " and t.fecha  BETWEEN '" . $start . "' AND '" . $end . "'";
                $where .= " and t.entidad=" . $entidad;
                $sql = "SELECT 
                    t.rowid, 
                    da.estado as tarea	,
                    concat(te.nombre,' ',te.apellidos)  as cliente,

            
                da.color,
                da.icono,
                t.fecha 

                FROM  agenda_citas  t 
                INNER JOIN agenda_citas_estados da ON t.fk_estado   = da.rowid 
                left  JOIN fi_terceros          te ON te.rowid      = t.fk_cliente

                where 1  " . $where;

                $db = $this->db->prepare($sql); // Preparar la consulta SQL
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
                        'id'=>$events_info->rowid,
                        
                        'id_oportunidad_actividad'=>$events_info->rowid
                    ];
                    $minutosExtra = $minutosExtra + 30;
                }
                return $eventos;
            }






/*****************************************************************************
 * 
 *          Funcion para los estados
 *          26 Noviembre 2024    
 * 
 ******************************************************************************/

 public function diccionario_Estados( ){
            
            $sql = "SELECT *
                    FROM agenda_citas_estados
                    ";

            $db = $this->db->prepare($sql);
            $a = $db->execute(); 


            if ($a) {

                  while( $result = $db->fetch(PDO::FETCH_ASSOC)){

                        $this->diccionario_estados[$result['rowid'] ]['etiqueta']   = $result['estado'];
                        $this->diccionario_estados[$result['rowid'] ]['color']      = $result['color'];
                        $this->diccionario_estados[$result['rowid'] ]['icono']      = $result['icono'];
                        $this->diccionario_estados[$result['rowid'] ]['activo']     = $result['activo'];
                   }

                   return $this->diccionario_estados;

            
            } else {

            $respuesta = ['error' => 1, 'mensaje_txt' => $db->errorInfo()];
            $this->sql = $sql;
            $this->error = implode(", ", $this->d->errorInfo()) . implode(", ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();

            }

}


} // Object
