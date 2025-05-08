<?php


 
if (!empty($_POST['action'])) :
    session_start();
    include_once("../../conf/conf.php");
    require_once ENLACE_SERVIDOR . '/mod_usuarios/object/usuarios.object.php';
    //MOVERLO DE UBICACION PARA SER ALGO MAS GLOBAL
    include_once(ENLACE_SERVIDOR."mail_sys/mail/PHPMailer/src/EnviarCorreoSmtp.php");


    switch ($_POST['action']):

        case "actualizarInformacionBasica":

            $obj = new usuario($dbh);
            $obj->nombre = $_REQUEST['nombre'];
            //Buscamos dentro de nuestro correo
            $email_actual  = $obj->buscar_campo_by_tabla_usuario('usuarios','acceso_usuario',$_REQUEST['fk_usuario']); 

            $obj->apellidos = $_REQUEST['apellidos'];
           
            $obj->fk_idioma = !empty($_REQUEST['fk_idioma']) ? $_REQUEST['fk_idioma'] : 0;

            $obj->usuario_telefono = $_REQUEST['usuario_telefono'];
            $obj->id = $_REQUEST['fk_usuario'];

            //ESTE ES EL EMAIL de la tabla licencias
            $obj->acceso_usuario = $_REQUEST['acceso_usuario'];
            
            //Vamos a guardar el correo y lo pasamos a TEMPORAL 
            if($_REQUEST['acceso_usuario'] != $email_actual)
            {   

                //el correo nuevo
                $obj->correo_temporal = $_REQUEST['acceso_usuario'];
                $obj->acceso_correo_estado = 'pendiente';
                $obj->actualizar_correo = true;
            }

            $resultado = $obj->ActualizarInformacionBasicaUsuario();
            echo json_encode($resultado);
        break;

        case "actualizarInfoHostEmail":
            $obj = new usuario($dbh);
            $obj->email_host = $_REQUEST['email_host'];
            $obj->email_port = $_REQUEST['email_port'];
            $obj->email_user_name = $_REQUEST['email_user_name'];
            $obj->email_password = $_REQUEST['email_password'];
            $obj->id = $_REQUEST['fk_usuario'];
            //Actualizaremos todo lo que es con el email Host
            $resultado = $obj->ActualizarEmailHost();
            echo json_encode($resultado);
        break;

        case 'EnviarEmailSmtpPrueba':
             $obj = new usuario($dbh);
             $obj->id = $_REQUEST['fk_usuario'];
             $obj->buscar_data_usuario($_REQUEST['fk_usuario']);
             $resultado = $obj->enviar_email_prueba_smtp($_REQUEST['email_user_prueba']);
             echo json_encode($resultado);
        break;


        //verificar correo temporal con los 6 digitos propuestos
        case "verificar_codigo":
            $obj = new usuario($dbh);
            $obj->acceso_correo_codigo = $_REQUEST['codigo_6_digitos'];
            $obj->id = $_REQUEST['fk_usuario'];
            //hacemos la verificacion del codigo por el codigo + el rowid del usuario
            $resultado = $obj->verificarCodigoActualizacionCorreo();
            echo json_encode($resultado);
        break;

        //Reenviar Codigo nuevamente de 6 digitos
        case 'reenviarCodigoEmail':
            $obj = new usuario($dbh);
            $correo_temporal  = $obj->buscar_campo_by_tabla_usuario('usuarios','correo_temporal',$_REQUEST['fk_usuario']); 
            $nombre =  $obj->buscar_campo_by_tabla_usuario('usuarios','nombre',$_REQUEST['fk_usuario']); 
            $apellidos =  $obj->buscar_campo_by_tabla_usuario('usuarios','apellidos',$_REQUEST['fk_usuario']); 

            $obj->correo_temporal = $correo_temporal;
            $obj->nombre = $nombre;
            $obj->apellidos = $apellidos;
            $obj->acceso_correo_estado = 'pendiente';
            $obj->id = $_REQUEST['fk_usuario'];

            //Enviamos nuevamente el correo electronico
            $resultado =  $obj->EnviarCodigoActualizacionCorreo();
            echo json_encode($resultado);

        break;

        case "actualizarAvatar":
            $obj = new usuario($dbh);
            $obj->rowid = $_POST['fk_usuario'];
            $entidad = $_SESSION['Entidad'];

            // Procesar la imagen contenida en $_FILES['filepond']
            if(isset($_FILES['filepond'])){
                $errors= array();
                $file_name = $_FILES['filepond']['name'];
                $file_size = $_FILES['filepond']['size'];
                $file_tmp = $_FILES['filepond']['tmp_name'];
                $file_type = $_FILES['filepond']['type'];
                $file_ext = strtolower(end(explode('.',$_FILES['filepond']['name'])));
                $extensions= array("jpeg","jpg","png");
                if(in_array($file_ext,$extensions)=== false){
                    $errors[]="extension not allowed, please choose a JPEG or PNG file.";
                }
                $UploadDirectory = ENLACE_FILES_EMPRESAS . 'avatar/';
                if(empty($errors)==true){
                   // $target_dir = ENLACE_SERVIDOR."files_empresa/images/entidad_$entidad/user_img/";
                    $target_dir = $UploadDirectory;
                    //CREAMOS LA CARPETA
                    if (!is_dir($target_dir))
                    {
                        if (!mkdir($target_dir, 0777, true)) 
                        {
                        }
                    }
                    /*$Random_Number = rand(0, 9999999999);
                    $NewFileName = $Random_Number.'.'.$file_ext; //new file name*/
                    $NewFileName  = $_SESSION['usuario'].'.'.$file_ext;
                    $target_file = $target_dir.$NewFileName;
                    cleanOldFiles($target_dir, $_SESSION['usuario']);
                    if(move_uploaded_file($file_tmp, $target_file)){
                        // Actualizar el avatar original
                        $obj->udpdateAvatar($_SESSION['usuario'],$NewFileName);

                        // Crear el thumbnail
                        $thumbnailName = $_SESSION['usuario'].'-150x150.'.$file_ext;
                        $thumbnailPath = $target_dir.$thumbnailName;

                        // Llamar a la función para crear el thumbnail
                        if(createThumbnail($target_file, $thumbnailPath, 150, 150)) {
                            $result = array("status" => "success", "message" => "Image uploaded successfully", "url" => $target_file, "thumbnail_url" => $thumbnailPath);
                        } else {
                            $result = array("status" => "error", "message" => "Failed to create thumbnail");
                        }
                    } else {
                        $result = array("status" => "error", "message" => "Failed to upload image");
                    }

                }else{
                    $result = array("status" => "error", "message" => $errors);
                }
            } else {
                $result = array("status" => "error", "message" => "No file uploaded");
            }

            $result["url_encriptada"] = $obj->obtener_url_avatar_encriptada($_SESSION['usuario']);
            echo json_encode($result);
        break;
        case "limpiarAvatar":
            $obj = new usuario($dbh);
            $entidad = $_SESSION['Entidad'];
            $obj->udpdateAvatar($_SESSION['usuario'], NULL);
            $result = array("status" => "success", "message" => "Image uploaded deleted", "url_encriptada" => $obj->obtener_url_avatar_encriptada($_SESSION['usuario']) );
            echo json_encode($result);
        break;
    endswitch;
endif;





