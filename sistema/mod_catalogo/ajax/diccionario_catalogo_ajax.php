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
  include ENLACE_SERVIDOR . 'mod_catalogo/object/catalogo_object.php';
  $obj = new DiccionarioCatalogo($dbh);



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
   

    case 'actualizar_unidad':


 // validacion al editar
 $id_actual =  $_POST['id'];
 if (encontrar_duplicado('diccionario_catalogo', 'codigo', $_POST['codigo'], $_SESSION['Entidad'], $id_actual)['total'] > 0) {
     echo json_encode(['exito' => 0, 'mensaje' => 'Este codigo ya existe']);
     exit;
 }

      
/************************************************************
/*
/*           Modificando  
/*
/**************************************************************/
      $obj->id = $_POST['id'];
      $obj->entidad = $_SESSION['Entidad'];
      $obj->codigo = $_POST['codigo'];
      $obj->detalle = $_POST['detalle'];
      $obj->tipo = $_POST['tipo'];
      $obj->activo = $_POST['activo'];

    
      $result = $obj->actualizar_unidad();
      echo json_encode($result);

    break;
      

    case 'crear_unidad':

               //validacion de la etiqueta
if (encontrar_duplicado('diccionario_catalogo', 'codigo', $_POST['codigo'], $_SESSION['Entidad'])['total'] > 0) {
  echo json_encode(['exito' => 0, 'mensaje' => 'Este codigo ya existe']);
  exit;
}
   
      
        /************************************************************
        /*
        /*           Creando  
        /*
        /**************************************************************/

       
            

              $obj->entidad = $_SESSION['Entidad'];
              $obj->codigo = $_POST['codigo'];
              $obj->detalle = $_POST['detalle'];
              $obj->tipo = $_POST['tipo'];
              $obj->activo = $_POST['activo'];
              $obj->creado_fk_usuario = $_SESSION['usuario'];


              $result = $obj->crear_unidad();
              echo json_encode($result);

      break;


    case 'borrar_catalogo':
          $obj->entidad = $_SESSION['Entidad'];
          $obj->borrado_fk_usuario = $_SESSION['usuario'];
          $result = $obj->borrar_catalogo($_POST['id']);
          echo json_encode($result);
    break;



    
  endswitch;
endif;

