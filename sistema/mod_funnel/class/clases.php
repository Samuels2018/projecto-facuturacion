<?php
SESSION_START();
// USER
if (empty($_SESSION['usuario'])) {
    header("location: " . ENLACE_WEB . "inicio/");
    exit(1);
}

include_once "../../conf/conf.php";

include_once ENLACE_SERVIDOR . "mod_funnel/object/funnel.object.php";




switch ($_POST['action']) {
   
          case 'guardarTarea':
                $actividad = new FiFunnel($dbh);
                //instanciar propiedades
                $actividad->fk_cotizacion = $_POST['fk_cotizacion'];
                $actividad->fk_diccionario_actividad = $_POST['fk_diccionario_actividad'];
                $actividad->vencimiento_fecha = $_POST['vencimiento_fecha'];
                $actividad->fk_usuario_asignado = $_POST['fk_usuario_asignado'];
                $actividad->comentario = $_POST['comentario'];
                $actividad->creado_usuario =$_SESSION['Usuario'];
                $actividad->fk_estado = 1;
                $actividad->comentario_cierre = null;
                $actividad->tipo = 'tarea';

               $guardar = $actividad->guardarTarea(); 
         
                echo json_encode($guardar);
                 break;


                 case 'actualizarTarea':
                    $actividad = new FiFunnel($dbh);
                    //instanciar propiedades
                    $actividad->rowid = $_POST['rowid'];
                    $actividad->comentario = $_POST['comentario'];
                    $actividad->fk_estado =  $_POST['fk_estado'];
                    $actividad->comentario_cierre =  $_POST['comentario_cierre'];
                   
                   $guardar = $actividad->actualizarActividad(); 
             
                    echo json_encode($guardar);
        break;

        case 'BuscarDetalleFunnel':
            
            $fk_funnel = $_REQUEST['fk_funnel'];
            $Funnel = new FiFunnel($dbh);
            $detalle_funnel = $Funnel->obtener_listado_fi_funnel_detalle($fk_funnel);
        
            $html = '';
            foreach($detalle_funnel as $key => $value){
                $html.='<option value="'.$detalle_funnel[$key]->rowid.'">'.$detalle_funnel[$key]->etiqueta.'</option>';
            }

            echo $html;

        break;  


    
    default:
        # code...
        break;
}




function sumarDiasHabiles($fecha, $n) {
    // Convertir la fecha a un objeto DateTime
    $fechaObj = new DateTime($fecha);

    // Iterar hasta sumar N días hábiles
    while ($n > 0) {
        // Sumar un día
        $fechaObj->modify('+1 day');

        // Verificar si el día actual es laborable (lunes a viernes)
        if ($fechaObj->format('N') <= 5) {
            $n--;
        }
    }

    // Devolver la nueva fecha después de sumar N días hábiles
    return $fechaObj->format('Y-m-d');
}
