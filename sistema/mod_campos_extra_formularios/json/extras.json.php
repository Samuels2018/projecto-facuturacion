<?php 
 
    SESSION_START();
    require_once "../../conf/conf.php";

  //si no hay usuario autenticado, cerrar conexion
  if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
    exit(1);
  }


 /// Logica para no permitir editar algo que no me pertenece!   



  include_once(ENLACE_SERVIDOR . "mod_campos_extra_formularios/object/campos.extra.object.php");
  $Extra = new Extra($dbh, $_SESSION['Entidad']);
  $Extra->fk_documento = $_POST['documento'];
  $respuesta = array();
  $conteo=0;

foreach ($_POST as $key => $value) {
    // Verifica si la clave comienza con 'input-'
    if (strpos($key, 'input-') === 0) {
        // Separa el string por el guion
        $partes = explode('-', $key);

        // Asegura que tenga al menos dos partes
        if (isset($partes[1])) {
            $codigo = $partes[1];
            $mensaje[] = "Clave: $key => Código extraído: $codigo, Valor: $value\n";
            $Extra->guardar_Respuesta($codigo,$value);
            $conteo++;
        }
    }
}


$respuesta['mensaje'] = "{$conteo} Datos Actualizados";
echo json_encode($respuesta);
