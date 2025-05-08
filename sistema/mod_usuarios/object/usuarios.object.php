<?php
//  require_once ENLACE_SERVIDOR . "global/object/log.sistema.php";
include_once(ENLACE_SERVIDOR . "mod_seguridad/object/seguridad.object.php");
include_once(ENLACE_SERVIDOR . "mod_entidad/object/Entidad.object.php");

class usuario extends  Seguridad
{


    private $db;
    public  $id;
    public  $nombre;
    public  $apellidos;
    public  $activo;
    public  $activo_descripcion;
    public  $privilegios;
    public  $permisos;
    public  $administrador;
    public  $avatar;
    public  $telefono;
    public  $email;
    public  $sucursal;
    public  $email_host;
    public  $email_port;
    public  $email_user_name;
    public  $email_password;
    public  $firma;

    //Estos son de la tabla usuarios
    public  $acceso_usuario;
    public  $usuario_telefono;
    public  $actualizar_correo;
    public  $correo_temporal;
    public  $acceso_correo_estado;
    public  $acceso_correo_codigo;
    public  $fk_provincia;
    public  $fk_idioma;


    public $entidad;
    public $Entidad;

    function  __construct($db, $entidad = 1)
    {

        $this->db = $db;
        $this->entidad = $entidad;
        $this->Entidad = new Entidad($db, $entidad);
        $this->Entidad->configuracion_sistema();
        $this->Entidad->cargar_dueno_empresa();
        parent::__construct();  // Esto inicializa la clase SEGURIDAD
    }

    function fetch($id)
    {
        $sql = "select  u.*, licuser.acceso_usuario, licuser.fk_estado as usuario_estado, licuser.acceso_clave as usuario_clave
        from fi_usuarios u
        INNER JOIN ".$_ENV["DB_NAME_PLATAFORMA"].".usuarios licuser ON licuser.rowid = u.rowid
        where u.rowid = :rowid";

        $db = $this->db->prepare($sql);
        $db->bindValue('rowid', $id, PDO::PARAM_STR);
        $db->execute();
        $u = $db->fetch(PDO::FETCH_ASSOC);

        $this->id            = $u['rowid'];
        $this->nombre        = $u['nombre'];
        $this->apellidos     = $u['apellidos'];
        $this->activo        = $u['usuario_estado'];
        $this->clave        = $u['usuario_clave'];
        
        
        if(intval($this->activo)==0){ $this->activo_descripcion = 'Inactivo'; }
        if(intval($this->activo)==1){ $this->activo_descripcion = 'Activo'; }
        if(intval($this->activo)==3){ $this->activo_descripcion = 'Pendiente de Activar'; }

        $this->avatar        = $u['avatar'];
        $this->administrador = $u['administrador'];
        $this->telefono        = $u['telefono'];
        $this->email        = $u['acceso_usuario'];
        $this->fk_sucursal     = $u['fk_sucursal'];

        $this->descuento        = $u['descuento'];


        $this->email_host   = $u['email_host'];
        $this->email_port   = $u['email_port'];
        $this->email_user_name  = $u['email_user_name'];
        $this->email_password   = $u['email_password'];
        $this->firma   = $u['firma'];
        $this->entidad    = $u['entidad'];


        //------------- Mejora para ver si es un vendedor
        // $this->vendedor =  $u['vendedor'];


        $this->axmsa_edicion                    =  $u['axmsa_edicion'];
        $this->amsa_descuento_permitir          =  $u['amsa_descuento_permitir'];
        $this->amsa_descuento_permitir_maximo   =  $u['amsa_descuento_permitir_maximo'];


        //$this->getArrayPermissionsUser($this->id );
    }

    public function conexion_db_plataforma()
    {
        //Vamos a cambiarlo en el usuario primero 
        $dbh_plataforma = new PDO('mysql:host=' . $_ENV['DB_HOST_PLATAFORMA'] . ';dbname=' . $_ENV['DB_NAME_PLATAFORMA'] . ';charset=UTF8', $_ENV['DB_USER_PLATAFORMA'], $_ENV['DB_PASS_PLATAFORMA'], array(
            PDO::ATTR_PERSISTENT => true,
        ));
        return $dbh_plataforma;
    }

    //Funcion para buscar un campo Especifico de uusuarios o fi_usuarios
    public function buscar_campo_by_tabla_usuario($tabla, $campo, $id)
    {
        if ($tabla = 'usuarios') {
            $puente_sql =  $this->conexion_db_plataforma();
        } else {
            $puente_sql = $this->db;
        }
        /*Generaremos la consulta de un solo campo esto para hacer una busqueda rapida de algun campo espcifico nos servira para verificar el email del usuario, telefono, elementos individivuales */
        $sql = "SELECT $campo FROM $tabla  WHERE rowid = :rowid";
        $db = $puente_sql->prepare($sql);
        $db->bindValue('rowid', $id, PDO::PARAM_STR);
        $db->execute();
        $u = $db->fetch(PDO::FETCH_ASSOC);
        return $u[$campo];
    }





    //Buscaremos el usuario dentro de la
    public function buscar_data_usuario($id)
    {

        //Conexion con la tabla usuarios de la db plataforma
        $sql = "SELECT  u.rowid, u.nombre, u.acceso_clave, u.apellidos, u.fk_idioma, u.fk_provincia, u.acceso_usuario,u.usuario_telefono, u.correo_temporal , u.acceso_correo_estado, 
                u.fk_estado as activo, se.activo as activo_empresa
            FROM usuarios u INNER JOIN sistema_empresa_usuarios se ON u.rowid = se.fk_usuario
            WHERE u.rowid = :rowid";

        $db = $this->conexion_db_plataforma()->prepare($sql);
        $db->bindValue('rowid', $id, PDO::PARAM_STR);
        $db->execute();
        $u = $db->fetch(PDO::FETCH_ASSOC);


        $sql2 = "select  u.activo, u.email , u.fk_sucursal, u.telefono, u.movil, u.avatar, u.email_host, u.email_port, u.email_password, u.email_user_name  from fi_usuarios u  where u.rowid = :rowid";

        $db2 = $this->db->prepare($sql2);
        $db2->bindValue('rowid', $id, PDO::PARAM_STR);
        $db2->execute();
        $u2 = $db2->fetch(PDO::FETCH_ASSOC);

        $this->nombre = $u['nombre'];
        $this->apellidos = $u['apellidos'];
        $this->fk_idioma = $u['fk_idioma'];
        $this->fk_provincia = $u['fk_provincia'];
        $this->acceso_usuario = $u['acceso_usuario'];
        $this->correo_temporal = $u['correo_temporal'];
        $this->acceso_correo_estado = $u['acceso_correo_estado'];
        $this->usuario_telefono = $u['usuario_telefono'];
        $this->acceso_clave = $u['acceso_clave'];

        $this->email = $u2['email'];
        $this->telefono = $u2['movil'];
        $this->avatar = $u2['avatar'];
        $this->email_host = $u2['email_host'];
        $this->email_port = $u2['email_port'];
        $this->email_password = $u2['email_password'];
        $this->email_user_name = $u2['email_user_name'];
        $this->activo = $u['activo'];
        $this->activo_empresa = $u['activo_empresa'];

        $this->fk_idioma = $u['fk_idioma'];
    }



    // meotod crear usuarios
    public function nuevo()
    {
        $usuario_logeado = $this->usuario;
        $usuario_logeado_dueno_empresa = ($usuario_logeado == $this->Entidad->usuario_dueno_empresa["rowid"]);
        try {

            // Preparar la consulta SQL para insertar en la tabla 'usuarios'
            $stmt = $this->conexion_db_plataforma()->prepare("
                INSERT INTO usuarios
                SET nombre = :nombre,
                    apellidos = :apellidos,
                    acceso_usuario = :acceso_usuario,
                    acceso_clave = :acceso_clave,
                    usuario_telefono = :usuario_telefono,
                    fk_estado = :fk_estado,
                    fk_idioma = :fk_idioma
            ");

            // Asignar valores a los parámetros
            $stmt->bindValue(':nombre', $this->nombre);
            $stmt->bindValue(':apellidos', $this->apellidos);
            $stmt->bindValue(':acceso_usuario', $this->acceso_usuario);
            $stmt->bindValue(':usuario_telefono', $this->usuario_telefono);
            $stmt->bindValue(':acceso_clave', $this->acceso_clave);
            $stmt->bindValue(':fk_estado', ($usuario_logeado_dueno_empresa?1:3) ); //Pendiente de Activar
            $stmt->bindValue(':fk_idioma', $this->fk_idioma);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Obtener el ID generado
                $id = $this->conexion_db_plataforma()->lastInsertId();

                // Verificar si ya existe un registro en 'fi_usuarios' con el mismo ID
                $sqlcheck = "SELECT COUNT(*) FROM fi_usuarios WHERE rowid = :id";
                $check_stmt = $this->db->prepare($sqlcheck);
                $check_stmt->bindValue(':id', $id);
                $check_stmt->execute();
                $exists = $check_stmt->fetchColumn();

                if ($exists) {
                    // Si existe, actualizamos el registro
                    $update_stmt = $this->db->prepare("
                    UPDATE fi_usuarios
                    SET nombre = :nombre,
                        apellidos = :apellidos,
                        email = :acceso_usuario,
                        telefono = :usuario_telefono,
                        entidad = :entidad
                    WHERE rowid = :id
                ");
                    $update_stmt->bindValue(':id', $id);
                    $update_stmt->bindValue(':nombre', $this->nombre);
                    $update_stmt->bindValue(':apellidos', $this->apellidos);
                    $update_stmt->bindValue(':acceso_usuario', $this->acceso_usuario);
                    $update_stmt->bindValue(':usuario_telefono', $this->usuario_telefono);
                    $update_stmt->bindValue(':entidad', $this->entidad);

                    if (!$update_stmt->execute()) {

                        $this->sql = $sqlcheck;
                        $this->error = implode(", ", $update_stmt->errorInfo()) . implode(", ", $insert_stmt->errorInfo());
                        $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
                        $this->Error_SQL();

                        return ['exito' => 0, 'error_txt' => $update_stmt->errorInfo()];
                    }
                } else {
                    // Si no existe, insertamos un nuevo registro
                    $sqlcheck = "
                    INSERT INTO fi_usuarios (rowid, nombre, apellidos, telefono, entidad)
                    VALUES (:id, :nombre, :apellidos, :usuario_telefono, :entidad)
                ";
                    $insert_stmt = $this->db->prepare($sqlcheck);
                    $insert_stmt->bindValue(':id', $id);
                    $insert_stmt->bindValue(':nombre', $this->nombre);
                    $insert_stmt->bindValue(':apellidos', $this->apellidos);
                    $insert_stmt->bindValue(':usuario_telefono', $this->usuario_telefono);
                    $insert_stmt->bindValue(':entidad', $_SESSION['Entidad']);

                    if (!$insert_stmt->execute()) {

                        $this->sql = $sqlcheck;
                        $this->error = implode(", ", $insert_stmt->errorInfo()) . implode(", ", $insert_stmt->errorInfo());
                        $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
                        $this->Error_SQL();

                        return ['exito' => 0, 'error_txt' => $insert_stmt->errorInfo()];
                    }
                }

                // Insertar en la tabla 'sistema_empresa_usuarios'
                $sql_empresa = "
                INSERT INTO sistema_empresa_usuarios (fk_usuario, fk_empresa, fk_tipo_relacion, invitacion_aceptada, activo)
                VALUES (:fk_usuario, :fk_empresa, 3, 0, 1)"; //Se setea "3" para Relacion: Invitado

                $insert_empresa_stmt = $this->conexion_db_plataforma()->prepare($sql_empresa);
                $insert_empresa_stmt->bindValue(':fk_usuario', $id);
                $insert_empresa_stmt->bindValue(':fk_empresa', $this->entidad);


                //si es distinto de vacio vamos a asignarle los perfiles a añadirle / pero hacemos limpieza primero
                if (is_array($this->fk_perfil) && count($this->fk_perfil) > 0) {
                    $perfiles_usuario = $this->fk_perfil;
                    // Primero, eliminar los perfiles actuales del usuario si existen
                    $delete_perfiles = $this->db->prepare("
                            DELETE FROM fi_usuarios_perfiles_relacion WHERE fk_usuario = :fk_usuario
                        ");
                    $delete_perfiles->bindValue(':fk_usuario', $id);
                    $delete_perfiles->execute(); // Ejecutar el DELETE

                    // Luego, insertar los nuevos perfiles del usuario
                    foreach ($perfiles_usuario as $key => $value) {
                        $insert_perfil = $this->db->prepare("
                                INSERT INTO fi_usuarios_perfiles_relacion (fk_usuario, fk_usuario_perfil)
                                VALUES (:fk_usuario, :fk_usuario_perfil)
                            ");
                        $insert_perfil->bindValue(':fk_usuario', $id);
                        $insert_perfil->bindValue(':fk_usuario_perfil', $value);
                        $insert_perfil->execute(); // Ejecutar cada INSERT
                    }
                }


                if ($insert_empresa_stmt->execute()) {
                    $nombre_usuario = $this->nombre . ' ' . $this->apellidos;

                    if($usuario_logeado_dueno_empresa){
                        // Lo está creando el propio dueño de la compañía
                        $this->generar_correo_bienvenida($this->Entidad->usuario_dueno_empresa["acceso_usuario"], $this->acceso_clave, $nombre_usuario, $this->acceso_usuario);
                        $this->generar_correo_bienvenida($this->acceso_usuario, $this->acceso_clave, $nombre_usuario, $this->acceso_usuario);
                    }else{
                        $this->generar_correo_activacion($id, $nombre_usuario, $this->acceso_usuario);
                    }
                    return ['exito' => 1, 'id' => $id, 'inserted' => true];
                } else {
                    $this->sql = $sql_empresa;
                    $this->error = implode(", ", $insert_empresa_stmt->errorInfo()) . implode(", ", $insert_empresa_stmt->errorInfo());
                    $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
                    $this->Error_SQL();

                    return ['exito' => 0, 'error_txt' => $insert_empresa_stmt->errorInfo()];
                }
            } else {

                $this->sql = $sql_empresa;
                $this->error = implode(", ", $stmt->errorInfo()) . implode(", ", $stmt->errorInfo());
                $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
                $this->Error_SQL();

                // Si falló, devolver el error
                return ['exito' => 0, 'error_txt' => $stmt->errorInfo()];
            }
        } catch (PDOException $e) {
            // Manejar excepciones
            return ['exito' => 0, 'error_txt' => $e->getMessage()];
        }
    }

    function generar_correo_activacion($id_usuario, $nombre_usuario, $correo_usuario)
    {
        $retorno = false;
        include_once(ENLACE_SERVIDOR . "mail_sys/mail/PHPMailer/src/EnviarCorreoSmtp.php");

        $envio_activacion = $this->generar_token_envio_activacion($id_usuario);
        $hash_validar = $envio_activacion["url_validar"];
        $hash_rechazar = $envio_activacion["url_rechazar"];

        $cuerpoCorreo = "";
        if ($this->Entidad->configuracion_sistema["email_user_activacion"]) {
            $cuerpoCorreo = $this->Entidad->configuracion_sistema["email_user_activacion"];
        }
        $asunto = "Correo de Activación";

        $cuerpoCorreo = str_replace("[nombre_usuario]",      $nombre_usuario,         $cuerpoCorreo);
        $cuerpoCorreo = str_replace("[correo_usuario]",      $correo_usuario,         $cuerpoCorreo);
        $cuerpoCorreo = str_replace("[url_activacion]",         $hash_validar,         $cuerpoCorreo);
        $cuerpoCorreo = str_replace("[url_desactivacion]",      $hash_rechazar,         $cuerpoCorreo);

        $cuerpoCorreo = str_replace("[Nombre de tu empresa]",         $this->Entidad->entidad_razonsocial,         $cuerpoCorreo);
        $cuerpoCorreo = str_replace("[Dirección de tu empresa]",         $this->Entidad->entidad_direccion,         $cuerpoCorreo);
        $cuerpoCorreo = str_replace("[Correo electrónico]",         $this->Entidad->entidad_email,         $cuerpoCorreo);
        $cuerpoCorreo = str_replace("[Teléfono]",         $this->Entidad->entidad_telefonofijo,                     $cuerpoCorreo);

        if ($this->Entidad->usuario_dueno_empresa["acceso_usuario"] != '') {
            $retorno_email = Email_SMPT($this->db, $cuerpoCorreo, $this->Entidad->usuario_dueno_empresa["acceso_usuario"], NULL, NULL, $asunto);
            if (!$retorno_email["error"]) {
                $retorno = true;
            }
        }
        return $retorno;
    }
    function generar_correo_bienvenida($destinatario, $clave_usuario, $nombre_usuario, $correo_usuario)
    {
        $retorno = false;
        include_once(ENLACE_SERVIDOR . "mail_sys/mail/PHPMailer/src/EnviarCorreoSmtp.php");

        $cuerpoCorreo = "";
        if ($this->Entidad->configuracion_sistema["email_user_bienvenida"]) {
            $cuerpoCorreo = $this->Entidad->configuracion_sistema["email_user_bienvenida"];
        }
        $asunto = "Correo de Bienvenida";

        $cuerpoCorreo = str_replace("[nombre_usuario]",      $nombre_usuario,         $cuerpoCorreo);
        $cuerpoCorreo = str_replace("[correo_usuario]",      $correo_usuario,         $cuerpoCorreo);
        $cuerpoCorreo = str_replace("[password_usuario]",      $clave_usuario,         $cuerpoCorreo);

        $cuerpoCorreo = str_replace("[nombre_usuario_dueno]",      $this->Entidad->usuario_dueno_empresa["acceso_usuario"],         $cuerpoCorreo);

        $cuerpoCorreo = str_replace("[Nombre de tu empresa]",         $this->Entidad->nombre_empresa,         $cuerpoCorreo);
        $cuerpoCorreo = str_replace("[Dirección de tu empresa]",         $this->Entidad->nombre_direccion,         $cuerpoCorreo);
        $cuerpoCorreo = str_replace("[Correo electrónico]",         $this->Entidad->correo_electronico,         $cuerpoCorreo);
        $cuerpoCorreo = str_replace("[Teléfono]",         $this->Entidad->telefono_fijo,                     $cuerpoCorreo);

        
        $retorno_email = Email_SMPT($this->db, $cuerpoCorreo, $destinatario, NULL, NULL, $asunto);
        if (!$retorno_email["error"]) {
            $retorno = true;
        }
        return $retorno;
    }

    function generar_token_envio_activacion($id_usuario)
    {
        $key = $this->generar_clave_segura();
        $folder = tempnam(sys_get_temp_dir(), 'validateemail');
        file_put_contents($folder, $key);

        $hash_validar = $_ENV["ENLACE_WEB"] . 'validar_email/' . $this->encriptar_rowid($id_usuario . '_' . "1" . '_' . $folder, $key) . "___" . $key;
        $hash_rechazar = $_ENV["ENLACE_WEB"] . 'validar_email/' . $this->encriptar_rowid($id_usuario . '_' . "0" . '_' . $folder, $key) . "___" . $key;

        return array(
            'url_validar' => $hash_validar,
            'url_rechazar' => $hash_rechazar
        );
    }
    function validar_token_envio_activacion($token_completo)
    {
        $token_data = explode("___", $token_completo);
        $token_decrypt = $this->desencriptar_row_id($token_data[0], $token_data[1]);
        $data_decrypt = explode("_", $token_decrypt);

        $id = $data_decrypt[0];
        $activar = $data_decrypt[1];
        $folder = $data_decrypt[2];

        if (file_exists($folder)) {
            $contenido_file = file_get_contents($folder);
            if ($contenido_file == $token_data[1]) {
                unlink($folder);
                return true;
            }
        }
        return false;
    }


    //Validacion de correo
    public function validar_correo()
    {
        $sql = "SELECT COUNT(*) AS count FROM usuarios WHERE acceso_usuario = :email";
        $stmt = $this->conexion_db_plataforma()->prepare($sql);
        $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $response = array();
        if ($result['count'] > 0) {
            $response['error'] = 1;
            $response['datos'] = 'El correo electrónico ya existe.';
        } else {
            $response['error'] = 0;
            $response['datos'] = 'El correo electrónico es único.';
        }
        return $response;
    }


    ///Esta funcion no borra solo inactiva
    // function borrar_usuario($id)
    // {
    //     // Variables para almacenar las consultas SQL
    //     $sql_fi_usuarios = "UPDATE fi_usuarios SET activo = 0 WHERE rowid = :id";
    //     $sql_usuarios_plataforma = "UPDATE usuarios SET fk_estado = 0 WHERE rowid = :id";

    //     try {
    //         // Inicia la transacción
    //         $this->db->beginTransaction();

    //         // Actualización en la tabla fi_usuarios
    //         $update_stmt = $this->db->prepare($sql_fi_usuarios);
    //         $update_stmt->bindValue(':id', $id);
    //         $update_stmt->execute();

    //         // Actualización en la tabla usuarios de la plataforma
    //         $update_usuario = $this->conexion_db_plataforma()->prepare($sql_usuarios_plataforma);
    //         $update_usuario->bindValue(':id', $id);
    //         $update_usuario->execute();

    //         // Si todo va bien, se confirma la transacción
    //         $this->db->commit();

    //         return ['exito' => 1, 'id' => $id, 'update' => true];
    //     } catch (Exception $e) {
    //         // Si ocurre un error, se revierte la transacción
    //         $this->db->rollBack();

    //         // Identifica cuál consulta falló y registra el error
    //         if ($update_stmt->errorCode() !== '00000') {
    //             $this->sql = $sql_fi_usuarios;
    //             $this->error = implode(", ", $update_stmt->errorInfo());
    //         } elseif ($update_usuario->errorCode() !== '00000') {
    //             $this->sql = $sql_usuarios_plataforma;
    //             $this->error = implode(", ", $update_usuario->errorInfo());
    //         }

    //         $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
    //         $this->Error_SQL();

    //         return ['exito' => 0, 'error' => $this->error];
    //     }
    // }


    //Esta funcion nos permite activar nuevamente un usuario
    function activar_usuario($id, $activo = 1)
    {
        $usuario_logeado_dueno_empresa = ($this->usuario == $this->Entidad->usuario_dueno_empresa["rowid"]);
        if($usuario_logeado_dueno_empresa){

            // Variables para almacenar las consultas SQL
            // $sql_fi_usuarios = "UPDATE fi_usuarios SET activo = ".$activo." WHERE rowid = :id";
            $sql_usuarios_plataforma = "UPDATE usuarios SET fk_estado = " . $activo . " WHERE rowid = :id";
    
            try {
                // Inicia la transacción
                $this->db->beginTransaction();
    
                // // Actualización en la tabla fi_usuarios
                // $update_stmt = $this->db->prepare($sql_fi_usuarios);
                // $update_stmt->bindValue(':id', $id);
                // $update_stmt->execute();
    
                // Actualización en la tabla usuarios de la plataforma
                $update_usuario = $this->conexion_db_plataforma()->prepare($sql_usuarios_plataforma);
                $update_usuario->bindValue(':id', $id);
                $update_usuario->execute();
    
                // Si todo va bien, se confirma la transacción
                $this->db->commit();
    
                return ['exito' => 1, 'id' => $id, 'update' => true];
            } catch (Exception $e) {
                // Si ocurre un error, se revierte la transacción
                $this->db->rollBack();
    
                // Identifica cuál consulta falló y registra el error
                if ($update_usuario->errorCode() !== '00000') {
                    $this->sql = $sql_usuarios_plataforma;
                    $this->error = implode(", ", $update_usuario->errorInfo());
                }
    
                $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
                $this->Error_SQL();
    
                return ['exito' => 0, 'error' => $this->error];
            }

        }else{
            return ['exito' => 0, 'error' => "Ud. no puede aprobar o rechazar usuarios"];
        }

    }






    public function validPermissionUser($permission)
    {
        // RESULT 
        $result = (in_array($permission, $this->arrayPermissionObject));
        // RETURN
        return $result;
        //return true;
    }

    //FUNCION PARA RETORNAR UNA IMAGEN GENERADA POR NOMBRE DE USUARIO Y APELLIDO
    function verificar_url_avatar_path($url, $text)
    {
        if (is_file($url)) {
            return $url;
        } else {
            return 'https://ui-avatars.com/api/?name=' . $text . '&background=E7515A&color=fff';
        }
    }

    //FUNCION NUEVA PARA LA CARGA DE AVATAR obteniendo EL AVATAR URL
    public function obtener_url_avatar_encriptada($user_id) // ROWID en las tablas de fi_usuarios y usuarios
    {
        $key = $this->generar_clave_segura();
        $url_avatar_secure = ENLACE_WEB . 'servir_imagenes_avatar?img=' . $this->encriptar_rowid($user_id, $key) . '&token=' . $key;
        return $url_avatar_secure;
    }

    //OBTENER LA URL AVATAR DESENCRIPTADA PERO YA CON TODO Y DISCO DIRECTO
    public function obtener_url_avatar_desencriptada($rowid)
    {
        $sql = "SELECT avatar, nombre, apellidos FROM fi_usuarios WHERE rowid = :rowid";
        $db = $this->db->prepare($sql);
        $db->bindValue('rowid', $rowid, PDO::PARAM_STR);
        $db->execute();
        $u = $db->fetch(PDO::FETCH_ASSOC);
        $url_avatar_path = $u['avatar'];
        $nombre_completo = $u['nombre'] . ' ' . $u['apellidos'];

        // Desglosar el nombre del archivo y la extensión
        $file_info = pathinfo($url_avatar_path);
        $filename = $file_info['filename']; // Nombre del archivo sin extensión
        $extension = $file_info['extension']; // Extensión del archivo

        // Crear el nuevo nombre del archivo con el sufijo -150x150
        $thumbnail_filename = $filename . '-150x150.' . $extension;

        // Generar la ruta completa del archivo thumbnail
        $file = ENLACE_FILES_EMPRESAS . 'avatar/' . $thumbnail_filename;

        // Verificar la URL del avatar o generar imagen de texto
        $file = $this->verificar_url_avatar_path($file, $nombre_completo);

        return $file;
    }

    //FUNCION PARA DEVOLVER  IMAGEN AVATAR
    public function devolver_avatar_url_by_code($encrypted, $key)
    {
        $rowid = $this->desencriptar_row_id($encrypted, $key);
        $url_avatar_path = $this->obtener_url_avatar_desencriptada($rowid);
        return $url_avatar_path;
    }
    //ENCRIPTAR
    public function encriptar_rowid($data, $key)
    {
        $cipher = "aes-256-cbc";
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
        $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
        $encoded = base64_encode($encrypted . "::" . $iv);
        return strtr($encoded, '+/', '-_');
    }
    //DESENCRIPTAR ROWID
    public function desencriptar_row_id($data, $key)
    {
        $cipher = "aes-256-cbc";
        $decoded = base64_decode(strtr($data, '-_', '+/'));
        list($encrypted_data, $iv) = explode("::", $decoded, 2);
        return openssl_decrypt($encrypted_data, $cipher, $key, 0, $iv);
    }
    public function generar_clave_segura()
    {
        return bin2hex(openssl_random_pseudo_bytes(16)); // 16 bytes = 32 caracteres hexadecimales
    }



    //ACTUALIZAR AVATAR
    public function udpdateAvatar($id, $avatarurl)
    {
        // Preparar la consulta SQL para actualizar el avatar
        $sql1 = "UPDATE usuarios SET usuario_avatar = :avatarUrl WHERE rowid = :id";
        $stmt1 = $this->conexion_db_plataforma()->prepare($sql1);
        // Vincular los parámetros a la consulta
        $stmt1->bindParam(':avatarUrl', $avatarurl);
        $stmt1->bindParam(':id', $id);
        // Ejecutar la consulta
        $stmt1->execute();

        // Preparar la consulta SQL para actualizar el avatar
        $sql = "UPDATE fi_usuarios SET avatar = :avatarUrl WHERE rowid = :id";
        $stmt = $this->db->prepare($sql);
        // Vincular los parámetros a la consulta
        $stmt->bindParam(':avatarUrl', $avatarurl);
        $stmt->bindParam(':id', $id);
        // Ejecutar la consulta
        $stmt->execute();
    }

    //Actualizar email HOST
    public function ActualizarEmailHost()
    {
        $this->db->beginTransaction();
        $resultado = array('exito' => false, 'mensaje' => '');

        try {
            // Iniciar transacción para fi_usuarios
            // Preparar la consulta SQL para actualizar fi_usuarios
            $sql1 = "UPDATE fi_usuarios SET email_host = :email_host, email_port = :email_port , email_user_name = :email_user_name,  email_password = :email_password WHERE rowid = :id";
            $stmt1 = $this->db->prepare($sql1);
            // Vincular los parámetros a la consulta
            $stmt1->bindParam(':email_host', $this->email_host);
            $stmt1->bindParam(':email_port', $this->email_port);
            $stmt1->bindParam(':email_user_name', $this->email_user_name);
            $stmt1->bindParam(':email_password', $this->email_password);
            $stmt1->bindParam(':id', $this->id);
            // Ejecutar la consulta
            $stmt1->execute();
            $resultado['exito'] = true;
            $resultado['mensaje'] = 'Información actualizada correctamente';

            // Si todo salió bien, confirmar la transacción para fi_usuarios
            $this->db->commit();
        } catch (PDOException $e) {
            $resultado['mensaje'] = 'Error al actualizar la información ' . $e->getMessage();
            $this->db->rollBack();
        }
        return $resultado;
    }

    //Enviar email de prueba SMTP
    public function enviar_email_prueba_smtp($email_prueba)
    {
        $html = '<h2>Hola ' . $this->nombre . ' ' . $this->apellidos . '</h2>';
        $html .= '<p>Enviando un correo de prueba</p>';
        $subject = 'PRUEBA DE CORREO';
        $para = $email_prueba;
        $attachments = [];
        $debug = 0;
        $respuesta = Email_SMPT_desde_cliente($this->email_host, $this->email_user_name, $this->email_password, $this->email_port, $html, $para, $attachments, 'enviar_email_logico.php', $subject, $debug);
        return $respuesta;
    }



    //FUNCION PARA ACTUALIZAR LA DATA DEL USUARIO EN diversas tablas 
    public function ActualizarInformacionBasicaUsuario()
    {
        $cont_error = 0;
        $resultado = array('exito' => false, 'mensaje' => '');
        $conexion_plataforma = $this->conexion_db_plataforma();
        //INICIAR TRANSACCION
        $conexion_plataforma->beginTransaction();
        $this->db->beginTransaction();

        try {
            // Iniciar transacción para fi_usuarios

            // Preparar la consulta SQL para actualizar fi_usuarios
            $sql1 = "UPDATE fi_usuarios SET nombre = :nombre, apellidos = :apellidos WHERE rowid = :id";
            $stmt1 = $this->db->prepare($sql1);
            // Vincular los parámetros a la consulta
            $stmt1->bindParam(':nombre', $this->nombre);
            $stmt1->bindParam(':apellidos', $this->apellidos);
            $stmt1->bindParam(':id', $this->id);
            // Ejecutar la consulta
            $stmt1->execute();
            // Si todo salió bien, confirmar la transacción para fi_usuarios
            //$this->db->commit();
        } catch (PDOException $e) {
            // Si hubo algún error, revertir la transacción para fi_usuarios
            //$this->db->rollBack();
            $resultado['mensaje'] = 'Error al actualizar la información en fi_usuarios: ' . $e->getMessage();
            $cont_error++;
        }

        if ($cont_error <= 0) {
            try {

                if ($this->usuario_editar != 'yes') {
                    // Preparar la consulta SQL para actualizar usuarios
                    $sql = "UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, fk_idioma = :fk_idioma , fk_provincia = :fk_provincia , usuario_telefono = :usuario_telefono WHERE rowid = :id";
                    $stmt = $conexion_plataforma->prepare($sql);
                    // Vincular los parámetros a la consulta
                    $stmt->bindParam(':nombre', $this->nombre);
                    $stmt->bindParam(':apellidos', $this->apellidos);
                    $stmt->bindParam(':fk_idioma', $this->fk_idioma);
                    $stmt->bindParam(':fk_provincia', $this->fk_provincia);
                    $stmt->bindParam(':usuario_telefono', $this->usuario_telefono);
                    $stmt->bindParam(':id', $this->id);
                    // Ejecutar la consulta
                    $stmt->execute();
                } else {

                    // Preparar la consulta SQL para actualizar usuarios
                    $sql = "UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, fk_idioma = :fk_idioma , usuario_telefono = :usuario_telefono , acceso_clave = :acceso_clave , acceso_usuario = :acceso_usuario  WHERE rowid = :id";
                    $stmt = $conexion_plataforma->prepare($sql);
                    // Vincular los parámetros a la consulta
                    $stmt->bindParam(':nombre', $this->nombre);
                    $stmt->bindParam(':apellidos', $this->apellidos);
                    $stmt->bindParam(':fk_idioma', $this->fk_idioma);
                    $stmt->bindParam(':usuario_telefono', $this->usuario_telefono);
                    $stmt->bindParam(':acceso_clave', $this->acceso_clave);
                    $stmt->bindParam(':acceso_usuario', $this->acceso_usuario);
                    $stmt->bindParam(':id', $this->id);
                    $stmt->execute();


                    //Borrar todo para volver a cargar sus perfiles
                    //si es distinto de vacio vamos a asignarle los perfiles a añadirle / pero hacemos limpieza primero
                    if (is_array($this->fk_perfil) && count($this->fk_perfil) > 0) {
                        $perfiles_usuario = $this->fk_perfil;
                        // Primero, eliminar los perfiles actuales del usuario si existen
                        $delete_perfiles = $this->db->prepare("
                            DELETE FROM fi_usuarios_perfiles_relacion WHERE fk_usuario = :fk_usuario
                        ");
                        $delete_perfiles->bindValue(':fk_usuario', $this->id);
                        $delete_perfiles->execute(); // Ejecutar el DELETE

                        // Luego, insertar los nuevos perfiles del usuario
                        foreach ($perfiles_usuario as $key => $value) {
                            $insert_perfil = $this->db->prepare("
                                INSERT INTO fi_usuarios_perfiles_relacion (fk_usuario, fk_usuario_perfil)
                                VALUES (:fk_usuario, :fk_usuario_perfil)
                            ");
                            $insert_perfil->bindValue(':fk_usuario', $this->id);
                            $insert_perfil->bindValue(':fk_usuario_perfil', $value);
                            $insert_perfil->execute(); // Ejecutar cada INSERT
                        }
                    }
                }

                // Preparar la consulta SQL para actualizar usuarios
                $sql = "UPDATE sistema_empresa_usuarios SET activo = :activo_empresa WHERE fk_usuario = :id AND fk_empresa = :entidad";
                $stmt = $conexion_plataforma->prepare($sql);
                $stmt->bindParam(':activo_empresa', $this->activo_empresa);
                $stmt->bindParam(':id', $this->id);
                $stmt->bindParam(':entidad', $this->entidad);
                // Ejecutar la consulta
                $stmt->execute();


                // Si todo salió bien, confirmar la transacción para usuarios
                // $conexion_plataforma->commit();
                $resultado['exito'] = true;
                $resultado['mensaje'] = 'Información actualizada correctamente';
            } catch (PDOException $e) {
                // Si hubo algún error, revertir la transacción para usuarios
                //$conexion_plataforma->rollBack();
                $resultado['mensaje'] = 'Error al actualizar la información en usuarios: ' . $e->getMessage();
                $cont_error++;
            }
        }

        if ($cont_error === 0) {
            $this->db->commit();
            $conexion_plataforma->commit();
            //Haremos una actualizacion de correo electronico
            if ($this->actualizar_correo === true) {
                $mensaje_actualizacion = $this->EnviarCodigoActualizacionCorreo();
                $resultado['correo_cambio'] = true;
                $resultado['correo_estatus'] = $mensaje_actualizacion['correo'];
                $resultado['correo_nuevo'] =   $mensaje_actualizacion['correo_nuevo'];
                $resultado['correo_mensaje'] = $mensaje_actualizacion['correo_mensaje'];
            }
        } else {
            //AMBAS SE DESHACEN
            $this->db->rollBack();
            $conexion_plataforma->rollBack();
        }
        return $resultado;
    }





    //vamo a actualizar el correo lo guardamos en uno temporal
    public function EnviarCodigoActualizacionCorreo()
    {

        $mensaje = array();
        try {
            //Generar el codigo de 6 digitos al momento de enviar a lcorreo
            $codigo_6_digitos = rand(100000, 999999);

            // Preparar la consulta SQL para actualizar fi_usuarios
            $sql = "UPDATE usuarios SET correo_temporal = :correo_temporal , acceso_correo_estado = :acceso_correo_estado, acceso_correo_actualizado = NOW() , acceso_correo_codigo = :acceso_correo_codigo  WHERE rowid = :id";

            $stmt = $this->conexion_db_plataforma()->prepare($sql);
            // Vincular los parámetros a la consulta
            $stmt->bindParam(':correo_temporal', $this->correo_temporal);
            $stmt->bindParam(':acceso_correo_estado', $this->acceso_correo_estado);
            $stmt->bindParam(':acceso_correo_codigo', $codigo_6_digitos);
            $stmt->bindParam(':id', $this->id);

            // Ejecutar la consulta
            $stmt->execute();
            //Enviar correo
            //    include_once(ENLACE_SERVIDOR."mail_sys/mail/PHPMailer/src/EnviarCorreoSmtp.php");

            $html = '
            <h2>Hola ' . $this->nombre . ' ' . $this->apellidos . '</h2>
        ';
            $html .= '<p>Tu codigo de verificación es:  ' . $codigo_6_digitos . '</p>';
            $subject = 'Notificación para cambio de correo';
            $dbh = $this->db;
            $para = $this->correo_temporal;
            $attachments = [];
            $debug = 0;
            $respuesta = Email_SMPT($dbh, $html, $para, $attachments, 'enviar_email_logico.php', $subject, $debug);

            $mensaje['correo'] = 'sucess';
            $mensaje['correo_nuevo'] = $this->correo_temporal;
            $mensaje['correo_mensaje'] = 'Se ha enviado un correo a ' . $this->correo_temporal . ' con un codigo de 6 digitos para validar la actualización ';
        } catch (PDOException $e) {
            $mensaje['correo'] = 'error';
            $mensaje['correo_nuevo'] = $this->correo_temporal;
            $mensaje['correo_mensaje'] = 'Error al intentar actualizar el correo electronico';
        }
        return $mensaje;
    }




    //Funcion para actualizar el correo Electronico enbase a verificacion de codigo
    public function verificarCodigoActualizacionCorreo()
    {
        $resultado = array('exito' => false, 'mensaje' => '');
        //primero verificamos que exista
        $sql = "select  u.correo_temporal ,
                 u.acceso_correo_codigo
                from usuarios  u
                where u.rowid = :rowid AND u.acceso_correo_codigo = :acceso_correo_codigo ";

        $db = $this->conexion_db_plataforma()->prepare($sql);
        $db->bindValue('rowid', $this->id, PDO::PARAM_STR);
        $db->bindValue('acceso_correo_codigo', $this->acceso_correo_codigo, PDO::PARAM_STR);
        $db->execute();
        $u = $db->fetch(PDO::FETCH_ASSOC);
        //si encontro la coincidencia con el codigo y el usuario
        if (isset($u['acceso_correo_codigo'])) {

            $this->correo_temporal = '';
            $this->acceso_usuario = $u['correo_temporal'];
            $this->acceso_correo_estado = 'validado';
            $this->acceso_correo_codigo = '';
            //como vemos que si existe vamos a cambiar la información de la tabla en la base de datos
            $actualizar_correo = $this->ActualizarCorreoUsuarioVerificado();

            //hacemos el update
            $resultado['exito'] = true;
            $resultado['mensaje'] = 'Correo verificado exitosamente';
            $resultado['correo_nuevo'] = $u['correo_temporal'];
        } else {
            //no encontro y hay un error en ese caso
            $resultado['exito'] = false;
            $resultado['mensaje'] = 'El codigo de verificación es incorrecto o no existe';
        }
        return $resultado;
    }


    //Aqui actualizamos el correo electronico que ya ha sido verificado
    public function ActualizarCorreoUsuarioVerificado()
    {
        $sql = "UPDATE usuarios SET acceso_usuario = :acceso_usuario ,  correo_temporal = :correo_temporal , acceso_correo_estado = :acceso_correo_estado, acceso_correo_actualizado = NOW() , acceso_correo_codigo = :acceso_correo_codigo  WHERE rowid = :id";

        $stmt = $this->conexion_db_plataforma()->prepare($sql);
        // Vincular los parámetros a la consulta
        $stmt->bindParam(':acceso_usuario', $this->acceso_usuario);
        $stmt->bindParam(':correo_temporal', $this->correo_temporal);
        $stmt->bindParam(':acceso_correo_estado', $this->acceso_correo_estado);
        $stmt->bindParam(':acceso_correo_codigo', $this->acceso_correo_codigo);
        $stmt->bindParam(':id', $this->id);
        // Ejecutar la consulta
        $stmt->execute();
    }


    // FUNCTION GET ARRAY PERMISSION USER
    public function getArrayPermissionsUser($user)
    {

        // QUERY

        $sql =
            "SELECT
              FP.rowid,
              FP.codigo,
              FP.fk_modulo,
              FP.nombre,
              FP.descripcion,
              FP.activo
            FROM facturac_Licencias.fi_usuarios_permisos FP
            INNER JOIN facturac_Licencias.fi_usuarios_perfiles_permisos FPS ON FPS.fk_permiso = FP.rowid AND FPS.activo = 1
            INNER JOIN facturac_Licencias.fi_usuarios_perfiles_perfil FUP ON FUP.fk_perfil = FPS.fk_perfil  AND FUP.activo = 1
            WHERE FUP.fk_usuario  =:fk_usuario
            AND FP.activo = 1;";
        $db = $this->db->prepare($sql);

        $db->bindValue(":fk_usuario", $user, PDO::PARAM_INT);

        $result = $db->execute();

        // RUN RECORDS PERMISSIONS
        $in = 0;
        while ($data = $db->fetch(PDO::FETCH_OBJ)):
            $arrayPermission[] = $data->codigo;
            //echo "los valores del array son kN".$in. " = ".$arrayPermission[$in];
            $in++;
        endwhile;

        // DEFINRE ARRYA
        //define('arrayPermissionObject', $arrayPermission); 
        $this->arrayPermissionObject = $arrayPermission;
    }


    //Vamos añadir los perfiles de usuario
    public function listar_perfiles_usuario($entidad)
    {
        $sql = "SELECT * FROM fi_usuarios_perfiles WHERE entidad = $entidad ";
        $db = $this->db->prepare($sql);
        $db->execute();
        $data   = $db->fetchAll(PDO::FETCH_OBJ);
        return $data;
    }

    //TRAERME LOS PERFILES QUE POSEO COMO USUARIO
    public function listar_perfiles_del_usuario($user_id)
    {
        $sql = "SELECT * FROM fi_usuarios_perfiles_relacion WHERE fk_usuario = $user_id ";
        $db = $this->db->prepare($sql);
        $db->execute();
        $data   = $db->fetchAll(PDO::FETCH_OBJ);
        return $data;
    }


    public function obtener_disenadores($entidad)
    {
        try {
            // Preparar la consulta SQL
            $stmt = $this->db->prepare("
                SELECT 
                    u.rowid,
                    u.nombre,
                    u.apellidos
                FROM 
                    fi_usuarios_perfiles_relacion upr
                JOIN 
                    fi_usuarios u ON upr.fk_usuario = u.rowid
                JOIN 
                    fi_usuarios_perfiles up ON upr.fk_usuario_perfil = up.rowid
                WHERE 
                    up.entidad = :entidad
                    AND (up.rowid = 1 OR LOWER(up.etiqueta) = 'diseñadores')
                    AND u.activo = 1
            ");

            // Asignar el valor de la entidad al parámetro
            $stmt->bindValue(':entidad', $entidad, PDO::PARAM_INT);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener los resultados como un array de objetos
            $resultados = $stmt->fetchAll(PDO::FETCH_OBJ);

            // Devolver los resultados
            return $resultados;
        } catch (PDOException $e) {
            // Manejar errores
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }
}
