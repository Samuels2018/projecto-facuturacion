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
  include ENLACE_SERVIDOR . 'mod_diccionario_categoria/object/diccionario_categoria_object.php';

  $obj = new Diccionario_categoria($dbh);



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


    case 'actualizar_diccionario_categoria':


      // validacion al editar
      $id_actual =  $_POST['id'];

      // $result = encontrar_duplicado('diccionario_categorias', 'label', $_POST['label'], $_SESSION['Entidad'], $id_actual);
      if ($_POST['fk_parent']) {
        if (encontrar_duplicado('diccionario_categorias', ['label', 'fk_parent'], [$_POST['label'], $_POST['fk_parent']], $_SESSION['Entidad'])['total'] > 0) {
          echo json_encode(['exito' => 0, 'mensaje' => 'Esta etiqueta ya existe']);
          exit;
        }
      } else {
        if (encontrar_duplicado('diccionario_categorias', 'label', $_POST['label'], $_SESSION['Entidad'])['total'] > 0) {
          echo json_encode(['exito' => 0, 'mensaje' => 'Esta etiqueta ya existe']);
          exit;
        }
      }

      // if ($result['total'] > 0) {
      //   echo json_encode(['exito' => 0, 'mensaje' => 'Esta etiqueta ya existe']);
      //   exit;
      // }


      /************************************************************
      /*
      /*           Modificando  
      /*
      /**************************************************************/
      $obj->id = $_POST['id'];
      $obj->label = $_POST['label'];
      $obj->fk_parent = $_POST['fk_parent'];

      $obj->activo = $_POST['estado'];
      $obj->fk_usuario = $_SESSION['usuario'];
      $obj->entidad = $_SESSION['Entidad'];
      $result = $obj->actualizar_diccionario_categoria();
      echo json_encode($result);

      break;



    case 'ver_diccionario_categoria':

      /************************************************************
      /*
      /*           Modificando  
      /*
      /**************************************************************/

      $result = $obj->fetch($_POST['id']);
      echo json_encode($obj);

      break;


    case 'crear_diccionario_categoria':

      //validacion de la etiqueta
      // if (encontrar_duplicado('diccionario_categorias', 'label', $_POST['label'], $_SESSION['Entidad'])['total'] > 0) {

      if ($_POST['fk_parent']) {
        if (encontrar_duplicado('diccionario_categorias', ['label', 'fk_parent'], [$_POST['label'], $_POST['fk_parent']], $_SESSION['Entidad'])['total'] > 0) {
          echo json_encode(['exito' => 0, 'mensaje' => 'Esta etiqueta ya existe']);
          exit;
        }
      } else {
        if (encontrar_duplicado('diccionario_categorias', 'label', $_POST['label'], $_SESSION['Entidad'])['total'] > 0) {
          echo json_encode(['exito' => 0, 'mensaje' => 'Esta etiqueta ya existe']);
          exit;
        }
      }
      // if (encontrar_duplicado('diccionario_categorias', ['label', 'fk_parent'], [$_POST['label'], $_POST['fk_parent']], $_SESSION['Entidad'])['total'] > 0) {
      //   echo json_encode(['exito' => 0, 'mensaje' => 'Esta etiqueta ya existe']);
      //   exit;
      // }

      /************************************************************
        /*
        /*           Creando  
        /*
        /**************************************************************/

      $obj->label = $_POST['label'];
      $obj->fk_usuario = $_SESSION['usuario'];
      $obj->entidad = $_SESSION['Entidad'];
      $obj->activo = $_POST['estado'];
      $obj->fk_parent = $_POST['fk_parent'];

      $result = $obj->crear_diccionario_categoria();
      echo json_encode($result);

      break;


    case 'borrar_diccionario_categoria':

      if (encontrar_duplicado('diccionario_categorias', 'fk_parent', $_POST['id'], $_SESSION['Entidad'])['total'] > 0) {
        echo json_encode(['exito' => 0, 'mensaje' => 'Este elemento no puede eliminarse' ]);
        exit;
      }

      $obj->entidad = $_SESSION['Entidad'];
      $obj->borrado_fk_usuario = $_SESSION['usuario'];
      $result = $obj->borrar_diccionario_categoria($_POST['id']);
      echo json_encode($result);
      break;




  endswitch;
endif;
