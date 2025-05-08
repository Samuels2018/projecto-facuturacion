<?php

session_start();

include_once("conf/conf.php");
include_once ENLACE_SERVIDOR . 'mod_usuarios/object/usuarios.object.php';

$obj = new usuario($dbh, $_SESSION["Entidad"]);
$token_completo = $_REQUEST["token"];

$valida_token = $obj->validar_token_envio_activacion($token_completo);

$mensaje_validacion = '';
if ($valida_token) {
    $token_data = explode("___", $token_completo);
    $token_decrypt = $obj->desencriptar_row_id($token_data[0], $token_data[1]);
    $data_decrypt = explode("_", $token_decrypt);

    $id = $data_decrypt[0];
    $activar = $data_decrypt[1];
    $folder = $data_decrypt[2];
    
    $obj->usuario = $_SESSION["usuario"];
    $retorno = $obj->activar_usuario($id, $activar);
    
    if(!$retorno["exito"]){
        $mensaje_validacion = $retorno["error"];    
    }else{
        $objUsuario = new usuario($dbh, $_SESSION["Entidad"]);
        $objUsuario->fetch($id);
        $obj->generar_correo_bienvenida($objUsuario->email, $objUsuario->clave, $objUsuario->nombre.' '.$objUsuario->apellidos, $objUsuario->email);
    }
} else {
    $mensaje_validacion = "La URL ya no es válida. Por favor vuelva a generar otra solicitud";
}

?>

<div class="account-settings-container layout-top-spacing">

    <div class="text-center">
        <div class="row mb-3">
            <div class="col-md-12">
                <?php if (empty($mensaje_validacion)) { ?>
                    <h2><?php echo $activar == 1 ? 'Activación' : 'Rechazo'; ?> de Usuario</h2>
                <?php } else { ?>
                    <h2>Enlace de validación no válido</h2>
                <?php } ?>
            </div>
        </div>

        <div class="text-center section general-info pb-5 pt-5">
            <div class="col-lg-12">
                <?php if (empty($mensaje_validacion)) { ?>
                    <div class="row">
                        <label>Gracias por <?php echo $activar == 1 ? 'Activar' : 'Rechazar'; ?> el usuario</label>
                    </div>
                <?php } else { ?>
                    <div class="row">
                        <label><?php echo $mensaje_validacion; ?></label>
                    </div>
                <?php } ?>
                <div class="row pt-5">
                    <div class="form-group">
                        <a class="btn btn-primary" href="<?php echo $_ENV["ENLACE_WEB"] . "usuarios_listado" ?>">Ir al listado de Usuarios</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>