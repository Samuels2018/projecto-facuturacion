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
  include ENLACE_SERVIDOR . 'mod_diccionario_moneda/object/moneda_object.php';

  $obj = new Moneda($dbh);



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
   

    case 'actualizar_moneda':



 // validacion al editar
 $id_actual =  $_POST['id'];
 if (encontrar_duplicado('diccionario_monedas', 'etiqueta', $_POST['etiqueta'], $_SESSION['Entidad'], $id_actual)['total'] > 0) {
     echo json_encode(['exito' => 0, 'mensaje' => 'Esta etiqueta ya existe']);
     exit;
 }
       
/************************************************************
/*
/*           Modificando  
/*
/**************************************************************/
      $obj->id = $_POST['id'];
      $obj->etiqueta = $_POST['etiqueta'];
      $obj->simbolo = $_POST['simbolo'];
      $obj->codigo = $_POST['codigo'];
      $obj->activo = $_POST['estado'];
      $obj->fk_usuario = $_SESSION['usuario'];
      $obj->entidad = $_SESSION['Entidad'];
      $result = $obj->actualizar_moneda();
      echo json_encode($result);

    break;



    case 'ver_moneda':
      
      /************************************************************
      /*
      /*           ver  
      /*
      /**************************************************************/
      
            $result = $obj->fetch($_POST['id']);
            echo json_encode($obj);
      
          break;
      

    case 'crear_moneda':

               //validacion de la etiqueta
if (encontrar_duplicado('diccionario_monedas', 'etiqueta', $_POST['etiqueta'], $_SESSION['Entidad'])['total'] > 0) {
  echo json_encode(['exito' => 0, 'mensaje' => 'Esta etiqueta ya existe']);
  exit;
}
   
      
        /************************************************************
        /*
        /*           Creando  
        /*
        /**************************************************************/

              $obj->etiqueta = $_POST['etiqueta'];
              $obj->fk_usuario = $_SESSION['usuario'];
              $obj->entidad = $_SESSION['Entidad'];
              $obj->activo = $_POST['estado'];
              $obj->codigo = $_POST['codigo'];
              $obj->simbolo = $_POST['simbolo'];
            
              $result = $obj->crear_moneda();
              echo json_encode($result);

      break;


    case 'borrar_moneda':
          $obj->entidad = $_SESSION['Entidad'];
          $obj->borrado_fk_usuario = $_SESSION['usuario'];
          $result = $obj->borrar_moneda($_POST['id']);
          echo json_encode($result);
    break;



    
  endswitch;
endif;

