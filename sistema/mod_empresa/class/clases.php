<?php


if (!empty($_POST['action'])) :
    session_start();
    include_once("../../conf/conf.php");
    require_once ENLACE_SERVIDOR . 'mod_utilidad/object/utilidades.object.php';
    require_once ENLACE_SERVIDOR . 'mod_impuestos/object/impuestos_object.php';




    require_once ENLACE_SERVIDOR . "mod_entidad/object/Entidad.object.php";
    $Entidad = new Entidad($dbh,    $_SESSION['Entidad']);





    switch ($_POST['action']):

        case "ActualizarEmpresaImpuestos":

            $Entidad  = new Entidad($dbh, $_SESSION['Entidad']);

            if($_POST['retencionvalor'] == 'true'){
                $Entidad->retencion                         = 1;
                $Entidad->retencion_porcentaje              = $_POST['retencion_porcentaje'];
                if($_POST['retencion_porcentaje_rigue_hasta']!= ''){
                    $Entidad->retencion_porcentaje_rigue_hasta  = $_POST['retencion_porcentaje_rigue_hasta'];
                }else{
                    $Entidad->retencion_porcentaje_rigue_hasta  = NULL;
                }
            }
            if($_POST['retencionvalor'] == 'false'){
                $Entidad->retencion                         = 0;
                $Entidad->retencion_porcentaje = 0;
                $Entidad->retencion_porcentaje_rigue_hasta  = NULL;    
            }
            $Entidad->fk_usuario                        = $_SESSION['usuario'] ;

            $resultado = $Entidad->ActualizarConfiguracionImpuesto();
            echo json_encode($resultado);
            break;

        case 'BuscarComunidadesAutonomas':
            $Utilidades = new Utilidades($dbh);
            $fk_pais = $_REQUEST['fk_pais'];
            $fk_ccaa = $_REQUEST['fk_ccaa'];
            $comunidadesAutonomas =  $Utilidades->obtener_comunidades_autonomas($fk_pais);
            $html  = '<option value="">Seleccionar CCAA</option>';
            foreach ($comunidadesAutonomas as $comunidad) {
                $selected = (isset($fk_ccaa) && intval($fk_ccaa) == intval($comunidad->id) ? 'selected':'' );
                $html .= '<option value="' . $comunidad->id . '" '. $selected .' >' . $comunidad->nombre . '</option>';
            }
            echo $html;

            break;

        case 'BuscarProvincias':
            $Utilidades = new Utilidades($dbh);
            $fk_comunidad_autonoma = $_REQUEST['fk_comunidad_autonoma'];
            $fk_provincia = $_REQUEST['fk_provincia'];
            $provincias =  $Utilidades->obtener_provincias($fk_comunidad_autonoma);
            $html  = '<option value="">Seleccionar Provincia</option>';
            foreach ($provincias as $provincia) {
                $selected = (isset($fk_provincia) && intval($fk_provincia) == intval($provincia->id) ? 'selected':'' );
                $html .= '<option value="' . $provincia->id . '" '. $selected .' >' . $provincia->provincia . '</option>';
            }
            echo $html;

            break;

        case 'BuscarMunicipios':
            $Utilidades = new Utilidades($dbh);
            $fk_provincia = $_REQUEST['fk_provincia'];
            $fk_municipio = $_REQUEST['fk_municipio'];
            $municipios =  $Utilidades->obtener_municipios($fk_provincia);
            $html  = '<option value="">Seleccionar Municipios</option>';
            foreach ($municipios as $municipio) {
                $selected = (isset($fk_municipio) && intval($fk_municipio) == intval($municipio->id) ? 'selected':'' );
                $html .= '<option value="' . $municipio->id . '" '. $selected .' >' . $municipio->municipio . '</option>';
            }
            echo $html;

            break;

        case "actualizarAvatar":
            $entidad = $_SESSION['Entidad'];
            // Procesar la imagen contenida en $_FILES['filepond']
            if (isset($_FILES['filepond'])) {
                $errors = array();
                $file_name = $_FILES['filepond']['name'];
                $file_size = $_FILES['filepond']['size'];
                $file_tmp = $_FILES['filepond']['tmp_name'];
                $file_type = $_FILES['filepond']['type'];
                $file_ext = strtolower(end(explode('.', $_FILES['filepond']['name'])));
                $extensions = array("jpeg", "jpg", "png");
                if (in_array($file_ext, $extensions) === false) {
                    $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
                }
                $UploadDirectory = ENLACE_FILES_EMPRESAS . 'avatar_empresa/';
                if (empty($errors) == true) {
                    // $target_dir = ENLACE_SERVIDOR."files_empresa/images/entidad_$entidad/user_img/";
                    $target_dir = $UploadDirectory;
                    //CREAMOS LA CARPETA
                    if (!is_dir($target_dir)) {
                        if (!mkdir($target_dir, 0777, true)) {
                        }
                    }
                    /*$Random_Number = rand(0, 9999999999);
                    $NewFileName = $Random_Number.'.'.$file_ext; //new file name*/
                    $NewFileName  = $_SESSION['Entidad'] . '.' . $file_ext;
                    $target_file = $target_dir . $NewFileName;
                    cleanOldFiles($target_dir, $_SESSION['Entidad']);
                    if (move_uploaded_file($file_tmp, $target_file)) {
                        // Actualizar el avatar original
                        $Entidad->udpdateAvatar($_SESSION['Entidad'], $NewFileName);

                        // Crear el thumbnail
                        $thumbnailName = $_SESSION['Entidad'] . '-150x150.' . $file_ext;
                        $thumbnailPath = $target_dir . $thumbnailName;

                        // Llamar a la función para crear el thumbnail
                        if (createThumbnail($target_file, $thumbnailPath, 150, 150)) {
                            $result = array("status" => "success", "message" => "Image uploaded successfully", "url" => $target_file, "thumbnail_url" => $thumbnailPath);
                        } else {
                            $result = array("status" => "error", "message" => "Failed to create thumbnail");
                        }
                    } else {
                        $result = array("status" => "error", "message" => "Failed to upload image");
                    }
                } else {
                    $result = array("status" => "error", "message" => $errors);
                }
            } else {
                $result = array("status" => "error", "message" => "No file uploaded");
            }
            $result["url_encriptada"] = $Entidad->obtener_url_avatar_encriptada($_SESSION['Entidad']);
            echo json_encode($result);
            break;

        case "limpiarAvatar":
            $Entidad->udpdateAvatar($_SESSION['Entidad'], NULL);
            $result = array("status" => "success", "message" => "Image uploaded deleted", "url_encriptada" => $Entidad->obtener_url_avatar_encriptada($_SESSION['Entidad']) );
            echo json_encode($result);
        break;

        case "ActualizarVerifact":
            $errors = array();

            $UploadDirectory = ENLACE_FILES_EMPRESAS . 'certificados/';
            if (empty($errors) == true) {
                $target_dir = $UploadDirectory;
                if (!is_dir($target_dir)) {
                    if (!mkdir($target_dir, 0777, true)) {
                    }
                }
            }
            $nombre_humano_archivo = '';
            $filename_certificado = '';

            if (isset($_FILES['verifactum_certificado'])) {
                $file_name1 = $_FILES['verifactum_certificado']['name'];
                $file_size1 = $_FILES['verifactum_certificado']['size'];
                $file_tmp1 = $_FILES['verifactum_certificado']['tmp_name'];
                $file_type1 = $_FILES['verifactum_certificado']['type'];
                $file_ext1 = strtolower(end(explode('.', $_FILES['verifactum_certificado']['name'])));
                $extensions1 = array("pem");
                if (in_array($file_ext1, $extensions1) === false) {
                    $errors[] = "extension not allowed, please choose a PEM file.";
                }
                if (empty($errors) == true) {
                    $target_dir1 = $UploadDirectory;
                    $filename_certificado = $_SESSION['Entidad'] . '.' . $file_ext1;
                    $target_file_certificado = $target_dir1 . $filename_certificado;
                    $nombre_humano_archivo = $file_name1;
                    move_uploaded_file($file_tmp1, $target_file_certificado);
                }
            }

            $verifactum_produccion = $_REQUEST['verifactum_option'];
            $verifactum_clave = $_REQUEST['verifactum_clave'];
            $verifactum_certificado = $filename_certificado;

            if ($errors) {
                echo json_encode(array('mensaje' => $errors));
            } else {

                $resultado = $Entidad->ActualizarVerifactum($nombre_humano_archivo, $verifactum_certificado, $verifactum_clave, $verifactum_produccion);
                echo json_encode($resultado);
            }
            break;



        case "ActualizarInformacionEmpresa":
            $Entidad->tipo_persona                  = $_REQUEST['tipo_persona'];
            $Entidad->tipo_residencia               = $_REQUEST['tipo_residencia'];
            $Entidad->fk_tipo_identificacion_fiscal    = $_REQUEST['tipo_identificacion_fiscal'];
            $Entidad->numero_identificacion         = $_REQUEST['numero_identificacion'];
            $Entidad->nombre_empresa                = $_REQUEST['razon_social'];
            $Entidad->nombre_fantasia               = $_REQUEST['nombre_fantasia'];
            $Entidad->persona_nombre                = $_REQUEST['persona_nombre'];
            $Entidad->persona_apellido1             = $_REQUEST['apellido1'];
            $Entidad->persona_apellido2             = $_REQUEST['apellido2'];
            $Entidad->correo_electronico            = $_REQUEST['email'];
            $Entidad->codigo_postal                 = $_REQUEST['codigo_postal'];
            
            $Entidad->direccion_fk_pais        = $_REQUEST['direccion_fk_pais'];
            $Entidad->direccion_fk_ccaa        = $_REQUEST['direccion_fk_ccaa'];
            $Entidad->direccion_fk_provincia        = $_REQUEST['direccion_fk_provincia'];
            $Entidad->direccion_fk_municipio        = $_REQUEST['direccion_fk_municipio'];

            $Entidad->telefono_fijo                 = $_REQUEST['telefono_fijo'];
            $Entidad->telefono_movil                = $_REQUEST['telefono_movil'];
            $Entidad->nombre_direccion              = $_REQUEST['nombre_direccion'];
            // $Entidad->id                            = $_SESSION['Entidad'];
            $resultado = $Entidad->ActualizarInformacionEmpresa();
            echo json_encode($resultado);
            break;
        //actualizacion del maestro
        case 'actualizarEmpresa':
            $Entidad->nombre = $_REQUEST['nombre_empresa'];
            $Entidad->activo = $_REQUEST['estado_empresa'];
            $resultado = $Entidad->ActualizarEmpresa($_REQUEST['id']);
            echo json_encode($resultado);
        break;
        case 'crearEmpresa':
            $Entidad->nombre = $_REQUEST['nombre_empresa'];
            $Entidad->activo = $_REQUEST['estado_empresa'];
            $resultado = $Entidad->InsertarEmpresa();
            echo json_encode($resultado);
        break;



    endswitch;
endif;
