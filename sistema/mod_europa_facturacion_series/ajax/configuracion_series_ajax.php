<?php


    session_start();

    //si no hay usuario autenticado, cerrar conexion
    if (!isset($_SESSION['usuario'])) {
      echo acceso_invalido();
      exit(1);
    }


    include_once("../../conf/conf.php");
    include ENLACE_SERVIDOR . 'mod_europa_facturacion_series/object/configuracion_series_object.php';

    $obj = new Series($dbh, $_SESSION['Entidad']);
    $mensaje = array();


     if ($_POST['action'] == 'crear_serie' and empty($_POST['id'])) { 
              $obj->tipo                  = $_POST['tipo'];
              $obj->tipo_aeat             = $_POST['tipo_aeat'];
              $obj->siguiente_documento   = (int)$_POST['siguiente_documento'];
              $obj->siguiente_borrador    = (int)$_POST['siguiente_borrador'];
              $obj->fk_serie_modelo       = trim($_POST['fk_serie_modelo']);
              $obj->serie_reinicio_anual  = (int)$_POST['serie_reinicio_anual'];
              $obj->serie_por_defecto     = (int)$_POST['serie_por_defecto'];
              $obj->serie_activa          = (int)$_POST['serie_activa'];
              $obj->serie_descripcion     = trim($_POST['serie_descripcion']);
              $obj->creado_fk_usuario     = $_SESSION['usuario'];
              $obj->entidad               = $_SESSION['Entidad'];
              $obj->plantilla_fk   = (int)$_POST['plantilla_fk'];
              $result = $obj->crear_serie();
              echo json_encode($result);
    
     } else  if ($_POST['action'] == 'crear_serie' and !empty($_POST['id'])) { 
          
    
            $obj->id                    = $_POST['id'];
            $obj->tipo_aeat             = $_POST['tipo_aeat'];
            $obj->tipo                  = $_POST['tipo'];
            $obj->siguiente_documento   = (int)$_POST['siguiente_documento'];
            $obj->siguiente_borrador    = (int)$_POST['siguiente_borrador'];
            $obj->fk_serie_modelo       = trim($_POST['fk_serie_modelo']);
            $obj->serie_reinicio_anual  = (int)$_POST['serie_reinicio_anual'];
            $obj->serie_por_defecto     = (int)$_POST['serie_por_defecto'];
            $obj->serie_activa          = (int)$_POST['serie_activa'];
            $obj->serie_descripcion     = trim($_POST['serie_descripcion']);
            $obj->creado_fk_usuario     = $_SESSION['usuario'];
            $obj->entidad               = $_SESSION['Entidad'];
            $obj->plantilla_fk          = (int)$_POST['plantilla_fk'];
            

              
            $Anterior = new Series($dbh, $_SESSION['Entidad']);
            $Anterior->fetch($_POST['id']);
            
 /*
            if ($Anterior->total_documentos > 0 and $obj->tipo_aeat  != $Anterior->tipo_aeat  ){
              $obj->tipo_aeat  = $Anterior->tipo_aeat ;
              $mensaje[]  =  "Tipo AEAT No puede Ser Editado. Existen {$Anterior->total_documentos} Facturas Emitidas con este Numero de Serie";
            }
 
*/



              $result = $obj->actualizar_serie();
            
              if (sizeof($mensaje)>0) { $result['mensaje_extraordinario']= $mensaje;  $result['objeto']=$Anterior;  }

             


              echo json_encode($result);



      } else if ($_POST['action']= 'borrar_serie' and !empty($_POST['id'])) { 

 
      $obj->entidad            = $_SESSION['Entidad'];
      $obj->borrado_fk_usuario = $_SESSION['usuario'];
      $result = $obj->borrar_serie($_POST['id']); 
      echo json_encode($result);
 

      }  else {
        $result['exito'] = 0;
        $result['mensaje'] = "No se encontro la Accion";
        echo json_encode($result);

      }

