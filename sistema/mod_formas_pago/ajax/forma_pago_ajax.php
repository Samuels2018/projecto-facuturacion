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
  include ENLACE_SERVIDOR . 'mod_formas_pago/object/forma_pago_object.php';
  include ENLACE_SERVIDOR . 'mod_formas_pago/object/forma_pago_detalle_object.php';

  $obj = new Forma_pago($dbh);
  $obj->entidad = $_SESSION['Entidad'];


  //bloque validacion entidad
  if (!empty($_POST['id'])) {
    $obj->fetch($_POST['id']);
    if (intval($obj->entidad) != intval($_SESSION['Entidad'])) {
      echo json_encode(['exito' => 0, 'mensaje' => 'No tienes acceso a esta seccion']);

      exit(1);
    }
  }



  // VALID ACTION
  switch ($_POST['action']):


    case 'actualizar_forma_pago':


      // validacion al editar
      $id_actual =  $_POST['id'];
      if (encontrar_duplicado('diccionario_formas_pago', 'label', $_POST['label'], $_SESSION['Entidad'], $id_actual)['total'] > 0) {
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
      
      $obj->importes_iguales = 0;
      $obj->ultimo_dia = 0;
      if ($_POST['importes_iguales'] == 'true'){
        $obj->importes_iguales = 1; 
      }
      if ( $_POST['ultimo_dia']  == 'true' ){
        $obj->ultimo_dia = 1;
      }

      $obj->fk_usuario = $_SESSION['usuario'];
      $obj->entidad = $_SESSION['Entidad'];
      $result = $obj->actualizar_forma_pago();

      if ( $_POST['detalle'] != '' ){
        $obj_detalle = new Forma_pago_detalle($dbh);
        $obj_detalle->detalle = json_decode($_POST['detalle']);
        $obj_detalle->fk_usuario = $_SESSION['usuario'];
        $obj_detalle->rowid = $_POST["id"];
        $ret_detalle = $obj_detalle->actualizar_forma_pago_detalle();
      }

      echo json_encode($result);

      break;



    case 'ver_forma_pago':

      /************************************************************
      /*
      /*           Modificando  
      /*
      /**************************************************************/
      $obj->entidad = $_SESSION['Entidad'];
      $result = $obj->fetch($_POST['id']);
      echo json_encode($obj);

      break;


    case 'crear_forma_pago':

      /************************************************************
        /*
        /*           Creando  
        /*
        /**************************************************************/

      //validacion de la etiqueta
      if (encontrar_duplicado('diccionario_formas_pago', 'label', $_POST['label'], $_SESSION['Entidad'])['total'] > 0) {
        echo json_encode(['exito' => 0, 'mensaje' => 'Esta etiqueta ya existe']);
        exit;
      }


      // echo json_encode(['exito' => 0, 'mensaje' => json_encode($_POST['detalle']) ] );
      $obj->label = $_POST['label'];
      $obj->fk_usuario = $_SESSION['usuario'];
      $obj->entidad = $_SESSION['Entidad'];      
      $obj->importes_iguales = 0;
      $obj->ultimo_dia = 0;
      if ($_POST['importes_iguales'] == 'true'){
        $obj->importes_iguales = 1; 
      }
      if ( $_POST['ultimo_dia']  == 'true' ){
        $obj->ultimo_dia = 1;
      }
      $obj->activo = $_POST['estado'];
      $result = $obj->crear_forma_pago();
      
      if($result["rowid"] > 0){
        if ( $_POST['detalle'] != '' ){
          $obj_detalle = new Forma_pago_detalle($dbh);
          $obj_detalle->detalle = json_decode($_POST['detalle']);
          $obj_detalle->fk_usuario = $_SESSION['usuario'];
          $obj_detalle->rowid = $result["rowid"];
          $ret_detalle = $obj_detalle->crear_forma_pago_detalle();
        }
      }      
      echo json_encode($result);
      break;


    case 'borrar_forma_pago':
      $obj->entidad = $_SESSION['Entidad'];
      $obj->borrado_fk_usuario = $_SESSION['usuario'];
      $result = $obj->borrar_forma_pago($_POST['id']);
      echo json_encode($result);
      break;




  endswitch;
endif;
