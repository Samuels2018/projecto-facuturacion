<?php
// VALID DEFINITIO ACTION
if (!empty($_POST['action'])) :
  session_start();
  include_once("../../conf/conf.php");
  include ENLACE_SERVIDOR . 'mod_categorias/object/categorias.object.php'; // Asegúrate de que la ruta y el nombre del archivo sean correctos

  $obj = new categoria_producto($dbh); // Creación del objeto categoría_producto
 
  // VALID ACTION
  switch ($_POST['action']):
   
    case 'actualizarCategoria':
      
      /************************************************************
      /*
      /*           Modificando una categoría 
      /*
      /**************************************************************/
      $obj->id = $_POST['id'];
      $obj->label = $_POST['label'];
      $obj->tipo = $_POST['tipo'];
      $obj->entidad = $_SESSION['Entidad'];
      $result = $obj->actualizarCategoria();
      echo json_encode($result);

    break;

    case 'crearCategoria':
      
      /************************************************************
      /*
      /*           Creando una categoría 
      /*
      /**************************************************************/
      $obj->label = $_POST['label'];
      $obj->tipo = $_POST['tipo'];
      $obj->entidad = $_SESSION['Entidad'];
      $obj->activo = 1; // Asumimos que al crear una categoría, está activa por defecto
      $result = $obj->insertarCategoria();
      echo json_encode($result);

    break;

    case 'eliminarCategoria':
      $result = $obj->desactivarCategoria($_POST['id']);
      echo json_encode($result);
    break;

    case 'activarCategoria':
      $result = $obj->activarCategoria($_POST['id']);
      echo json_encode($result);
    break;

  endswitch;
endif;
