<?php
// VALID DEFINITIO ACTION
if (!empty($_POST['action'])) :
  session_start();

    //si no hay usuario autenticado, cerrar conexion
    if (!isset($_SESSION['usuario'])) {
      echo acceso_invalido();
           exit(1);
    }

  include_once("../../conf/conf.php");
  include ENLACE_SERVIDOR . 'mod_medios_pago/object/medio_pago_object.php';

  $obj = new Medio_pago($dbh);
  $obj->entidad = $_SESSION['Entidad'];


  //bloque validacion entidad
if (!empty($_POST['id'])) {
  $obj->fetch($_POST['id']);
  if ($obj->entidad != $_SESSION['Entidad']) {
    echo json_encode( ['exito' => 0, 'mensaje' => 'No tienes acceso a esta seccion']);
   
       exit(1);
  }
}



  // VALID ACTION
  switch ($_POST['action']):
   

    case 'actualizar_medio_pago':


// validacion al editar
$id_actual =  $_POST['id'];
if (encontrar_duplicado('diccionario_medios_pago', 'label', $_POST['label'], $_SESSION['Entidad'], $id_actual)['total'] > 0) {
    echo json_encode(['exito' => 0, 'mensaje' => 'Esta etiqueta ya existe']);
    exit;
}
  
      
/************************************************************
/*
/*           Modificando  
/*
/**************************************************************/
      $obj->id = $_POST['id'];
      $obj->label = $_POST['label'];
      $obj->activo = $_POST['estado'];
      $obj->fk_usuario = $_SESSION['usuario'];
      $obj->entidad = $_SESSION['Entidad'];
      $result = $obj->actualizar_medio_pago();
      echo json_encode($result);

    break;



    case 'ver_medio_pago':
      
      /************************************************************
      /*
      /*           Modificando  
      /*
      /**************************************************************/
       $obj->entidad = $_SESSION['Entidad'];
            $result = $obj->fetch($_POST['id']);
            echo json_encode($obj);
      
          break;
      

    case 'crear_medio_pago':
      
        /************************************************************
        /*
        /*           Creando  
        /*
        /**************************************************************/

//validacion de la etiqueta
if (encontrar_duplicado('diccionario_medios_pago', 'label', $_POST['label'], $_SESSION['Entidad'])['total'] > 0) {
  echo json_encode(['exito' => 0, 'mensaje' => 'Esta etiqueta ya existe']);
  exit;
}



              $obj->label = $_POST['label'];
              $obj->fk_usuario = $_SESSION['usuario'];
              $obj->entidad = $_SESSION['Entidad'];
              $obj->activo = $_POST['estado'];
            
              $result = $obj->crear_medio_pago();
              echo json_encode($result);

      break;


    case 'borrar_medio_pago':
          $obj->entidad = $_SESSION['Entidad'];
          $obj->borrado_fk_usuario = $_SESSION['usuario'];
          $result = $obj->borrar_medio_pago($_POST['id']);
          echo json_encode($result);
    break;



    
  endswitch;
endif;

