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
  include ENLACE_SERVIDOR . 'mod_bancos/object/bancos.object.php'; // Asegúrate de que la ruta y el nombre del archivo sean correctos

  $obj = new banco($dbh); // Creación del objeto Banco


  
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
   
    case 'actualizarBanco':

// validacion al editar
$id_actual =  $_POST['id'];
if (encontrar_duplicado('diccionario_bancos', 'nombre_banco', $_POST['nombre_banco'], $_SESSION['Entidad'], $id_actual)['total'] > 0) {
    echo json_encode(['exito' => 0, 'mensaje' => 'Este nombre de banco ya existe']);
    exit;
}
      
      /************************************************************
      /*
      /*           Modificando un banco 
      /*
      /**************************************************************/
      $obj->id = $_POST['id'];
      $obj->nombre_banco = $_POST['nombre_banco'];
      $obj->entidad = $_SESSION['Entidad'];
      $obj->activo = $_REQUEST['estado_banco'];
      $result = $obj->actualizarBanco();
      echo json_encode($result);

    break;

    case 'crearBanco':

                            //validacion de la etiqueta
if (encontrar_duplicado('diccionario_bancos', 'nombre_banco', $_POST['nombre_banco'], $_SESSION['Entidad'])['total'] > 0) {
  echo json_encode(['exito' => 0, 'mensaje' => 'Este nombre de banco ya existe']);
  exit;
}
      
      /************************************************************
      /*
      /*           Creando un banco 
      /*
      /**************************************************************/
      $obj->nombre_banco = $_POST['nombre_banco'];
      $obj->entidad = $_SESSION['Entidad'];
      $obj->activo = 1; // Asumimos que al crear un banco, está activo por defecto
      $result = $obj->insertarBanco();
      echo json_encode($result);

    break;

    case 'eliminarBanco':
      $obj->borrado_fk_usuario = $_SESSION['usuario'];
      $obj->entidad = $_SESSION['Entidad'];
      $result = $obj->eliminarBanco($_POST['id']);
      echo json_encode($result);
    break;

    case 'activarBanco':
      $result = $obj->activarBanco($_POST['id']);
      echo json_encode($result);
    break;

  endswitch;
endif;
