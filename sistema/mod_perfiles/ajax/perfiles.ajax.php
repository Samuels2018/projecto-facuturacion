<?php
// VALID DEFINITIO ACTION
if (!empty($_POST['action'])) :
  session_start();
  include_once("../../conf/conf.php");
  include ENLACE_SERVIDOR . 'mod_perfiles/object/perfil.object.php';

  $obj = new perfil($dbh);


  // VALID ACTION
  switch ($_POST['action']):
   

    case 'actualizarPerfil':
      
    /************************************************************
    /*
    /*           Modificando un perfil 
    /*
    /**************************************************************/
      $obj->id = $_POST['id'];
      $obj->etiqueta = $_POST['etiqueta'];
      $obj->fk_usuario = $_SESSION['usuario'];
      $obj->entidad = $_SESSION['Entidad'];
      $result = $obj->actualizarPerfilUsuario();
      echo json_encode($result);

    break;

    case 'crearPerfil':
      
        /************************************************************
        /*
        /*           Creando un perfil 
        /*
        /**************************************************************/

              $obj->etiqueta = $_POST['etiqueta'];
              $obj->fk_usuario = $_SESSION['usuario'];
              $obj->entidad = $_SESSION['Entidad'];
            
              $result = $obj->insertarPerfilUsuario();
              echo json_encode($result);

      break;


    case 'eliminarPerfil':
          $result = $obj->borrarPerfil($_POST['id']);
          echo json_encode($result);
    break;

    case 'activarPerfil':
          $result = $obj->activarPerfil($_POST['id']);
          echo json_encode($result);
    break;
    case 'cambiartheme':
      /* TODO: Pendiente ajustar si sólo será para el Theme o para todas las variables de localstorage */
    break;

    
  endswitch;
endif;
// FUNCTION VALID BILLED USER
