<?php
//----------------------------------------------------------------------------------------------------------
//
//          dbermejo@avancescr.com
//          David Bermejo
//          4001-6311
//
//----------------------------------------------------------------------------------------------------------

class Entidad  extends  Seguridad
{
    private     $db;
    public      $entidad;


    public      $modulo_activo; // contiene el listado de modulos activos disponibles 
    public      $nombre_empresa;
    public      $nombre_fantasia;



    public      $sincronizaciones = [];
    public      $configuracion    = [];
    public      $configuracion_sistema = [];


    public $nombre;
    public $activo;
    public $electronica_certificado;
    public $electronica_certificado_encriptado;
    public $electronica_certificado_clave;
    public $verifactum_produccion;
    public $verifactum_produccion_fecha;

    public $direccion_fk_ccaa;
    public $direccion_fk_pais;
    public $direccion_fk_provincia;
    public $direccion_fk_municipio;

    public $telefono_fijo;
    public $telefono_movil;
    public $website;
    public $creado_fecha;
    public $creado_fk_usuario;
    public $borrado;
    public $borrado_fecha;
    public $borrado_fk_usuario;
    public $fk_sistema_empresa_licencias;
    public $company_externo;
    public $avatar;
    public $kit_aplica_kit_digital;
    public $kit_fk_tipo;
    public $kit_pdf_firmado;
    public $kit_pdf_firmado_url_en_disco;
    public $kit_direccion_completa;
    public $kit_codigo_postal;
    public $kit_factura_emitida;
    public $kit_factura_emitida_fecha;
    public $kit_factura_emitida_pagada;
    public $kit_monto_aprobado;
    public $kit_monto_comision;
    public $kit_monto_comision_pagada;
    public $vendedor_fk_usuario;
    public $tipo;
    public $cedula;
    public $notas_empresa;
    public $fk_kit_digital_estado;
    public $electronica_activo;
    public $aceptacion_firma;
    public $ventas_firma;
    public $utiliza_inventario;
    public $permitir_inventario_negativo;
    public $integracion_romanas;
    public $fk_empresa_parent;
    public $tipo_cuenta;
    public $fecha_cobro;
    public $cobro_monto;
    public $cobro_moneda;
    public $periocidad_cobro;
    public $funcion_cuenta;
    public $vendedor;

    public $tipo_persona;
    public $tipo_residencia;
    public $persona_nombre;
    public $persona_apellido1;
    public $persona_apellido2;
    public $fk_tipo_identificacion_fiscal;
    public $numero_identificacion;
    public $codigo_postal;
    public $correo_electronico;
    public $fk_sucursal;
    public $nombre_direccion;
    public $cedula_juridica;
    public $sujeto_impuesto;
    public $electronica_nombre;
    public $electronica_identificacion_tipo;
    public $electronica_identificacion_numero;
    public $electronica_nombre_comercial;
    public $electronica_provincia;
    public $electronica_canton;
    public $electronica_distrito;
    public $electronica_barrio;
    public $electronica_otras_senas;
    public $electronica_telefono;
    public $electronica_fax;
    public $retencion;
    public $retencion_porcentaje;
    public $retencion_porcentaje_rigue_hasta;
    public $electronico_correo;
    public $api_access_token;
    public $api_cliente;
    public $api_usuario;
    public $api_password;
    public $api_path;
    public $GTIN_distribuidor;
    public $cuentaBanco;
    public $body_correos;
    public $cron_job_correo_crm_actividades_por_vencer;

    public $usuario_dueno_empresa;

    function  __construct($db, $entidad = NULL)
    {
        $this->db          = $db;
        $this->entidad      = $entidad;
        parent::__construct($db, $entidad);  // Esto inicializa la clase SEGURIDAD
        $this->modulos_activos();
        $this->conexion_db_plataforma();
        $this->conexion_db_utilitario();

        if ($entidad !== NULL) {
            $this->fetch();
        }
    }

    public function fetch_desencriptado( $idEncriptado_MD5) {
       
        $sql = "select rowid  from sistema_empresa  where  md5(rowid)  = :fk_entidad  ";
        $plataforma = $this->conexion_db_plataforma();
        $stmt = $plataforma->prepare($sql);
        $stmt->bindParam(':fk_entidad', $idEncriptado_MD5 , PDO::PARAM_STR);
        $a = $stmt->execute();
        

        if ($a) {   
            $resultados = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->entidad = $resultados['rowid'];
            $this->fetch();

        } else {

            $this->sql     =   $sql;
            $this->error   =   implode(", ", $stmt->errorInfo()) . " " . implode(" , ", $plataforma->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }
            return $this->entidad;
    }
 

    private function conexion_db_plataforma()
    {
        // Cambiamos esto para unificar Entidad y Empresa 
        // Por seguridad se usa y se destruye
        $dbh_plataforma = new PDO('mysql:host=' . $_ENV['DB_HOST_PLATAFORMA'] . ';dbname=' . $_ENV['DB_NAME_PLATAFORMA'] . ';charset=UTF8', $_ENV['DB_USER_PLATAFORMA'], $_ENV['DB_PASS_PLATAFORMA'], array(
            PDO::ATTR_PERSISTENT => true,
        ));

        return $dbh_plataforma;
    }


    private function conexion_db_utilitario()
    {
        // Cambiamos esto para unificar Entidad y Empresa 
        // Por seguridad se usa y se destruye
        $dbh_utilitario= new PDO('mysql:host=' . $_ENV['DB_HOST_UTILIDADES_APOYO'] . ';dbname=' . $_ENV['DB_NAME_UTILIDADES_APOYO'] . ';charset=UTF8', $_ENV['DB_USER_UTILIDADES_APOYO'], $_ENV['DB_PASS_UTILIDADES_APOYO'], array(
            PDO::ATTR_PERSISTENT => true,
        ));

        return $dbh_utilitario;
    }



    public function  modulos_activos()
    {
        $sql = "select * from fi_configuracion_empresa_modulos_activos where fk_entidad = :fk_entidad  ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':fk_entidad', $this->entidad, PDO::PARAM_INT);
        $a = $stmt->execute();

        if ($a) {
            while ($result = $stmt->fetch(PDO::FETCH_OBJ)) {
                $this->modulo_activo[$result->fk_modulo] = 1;
            }
        } else {

            $this->sql     =   $sql;
            $this->error   =   implode(", ", $stmt->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        return $a;
    } // fin de modulos_activos() 


    public function cargar_sincronizaciones()
    {
        // Consulta a la tabla sistema_externo_sincronizaciones
        $sql = "SELECT fk_sistema_a_sincronizar FROM sistema_externo_sincronizaciones where fk_entidad = :fk_entidad ";
        $db = $this->db->prepare($sql);
        $db->bindParam(':fk_entidad', $this->entidad, PDO::PARAM_INT);
        $db->execute();
        $resultados = $db->fetchAll(PDO::FETCH_ASSOC);

        // Cargar los resultados en la propiedad $this->sincronizaciones
        $this->sincronizaciones = [];
        foreach ($resultados as $fila) {
            $this->sincronizaciones[$fila['fk_sistema_a_sincronizar']] = 1;
        }
    }

    // Url QuickBooks Dinamico - @rojasarmando - 14-06-2024
    public function obtener_url_externa($id_sistema_externo)
    {
        // Consulta a la tabla sistema_externo_sincronizaciones
        $sql = "SELECT t.url FROM sistema_externo_sincronizaciones as t where t.fk_entidad = :fk_entidad and t.fk_sistema_a_sincronizar = :id_sistema_externo ";

        $db = $this->db->prepare($sql);

        $db->bindParam(':fk_entidad'        , $this->entidad        , PDO::PARAM_INT);
        $db->bindParam(':id_sistema_externo', $id_sistema_externo   , PDO::PARAM_INT);
        $db->execute();

        $resultados = $db->fetch(PDO::FETCH_OBJ);

        return $resultados->url;
    }
    //---------------------------

    public function  fetch()
    {


        $sql = "select 
                    
            rowid,
            nombre,
            direccion_fk_pais,
            direccion_fk_ccaa,
            direccion_fk_provincia,
            direccion_fk_municipio,
            telefono_fijo,
            telefono_movil,
            website,
            fk_estado,
            creado_fecha,
            creado_fk_usuario,
            borrado,
            borrado_fecha,
            borrado_fk_usuario,
            fk_sistema_empresa_licencias,
            company_externo,
            avatar,
            kit_aplica_kit_digital,
            kit_fk_tipo,
            kit_pdf_firmado,
            kit_pdf_firmado_url_en_disco,
            kit_direccion_completa,
            kit_codigo_postal,
            kit_factura_emitida,
            kit_factura_emitida_fecha,
            kit_factura_emitida_pagada,
            kit_monto_aprobado,
            kit_monto_comision,
            kit_monto_comision_pagada,
            vendedor_fk_usuario,
            tipo,
            cedula,
            notas_empresa,
            fk_kit_digital_estado,
            verifactum_produccion,
            verifactum_produccion_fecha,
            electronica_activo,
            aceptacion_firma,
            ventas_firma,
            utiliza_inventario,
            permitir_inventario_negativo,
            integracion_romanas,
            fk_empresa_parent,
            tipo_cuenta,
            fecha_cobro,
            cobro_monto,
            cobro_moneda,
            periocidad_cobro,
            funcion_cuenta,
            vendedor,
            tipo_persona,
            tipo_residencia,
            persona_nombre,
            persona_apellido1,
            persona_apellido2,
            nombre_empresa,
            fk_tipo_identificacion_fiscal,
            numero_identificacion,
            codigo_postal,
            correo_electronico,
            fk_sucursal,
            nombre_fantasia,
            nombre_direccion,
            cedula_juridica,
            sujeto_impuesto,
            electronica_certificado,
            electronica_certificado_encriptado,
            electronica_certificado_clave,
            electronica_nombre,
            electronica_identificacion_tipo,
            electronica_identificacion_numero,
            electronica_nombre_comercial,
            electronica_provincia,
            electronica_canton,
            electronica_distrito,
            electronica_barrio,
            electronica_otras_senas,
            electronica_telefono,
            electronica_fax,
            retencion,
            retencion_porcentaje,
            retencion_porcentaje_rigue_hasta ,
            electronico_correo,
            api_access_token,
            api_cliente,
            api_usuario,
            api_password,
            api_path,
            GTIN_distribuidor,
            cuentaBanco,
            body_correos,
            cron_job_correo_crm_actividades_por_vencer 
             
            FROM sistema_empresa 
            WHERE rowid = :fk_entidad  ";

        $plataforma = $this->conexion_db_plataforma();
        $stmt = $plataforma->prepare($sql);

        $stmt->bindParam(':fk_entidad', $this->entidad, PDO::PARAM_INT);
        $a = $stmt->execute();

        if ($a) {

            $result = $stmt->fetch(PDO::FETCH_OBJ);

            $this->nombre_empresa   =  $result->nombre_empresa;
            $this->nombre_fantasia  =  $result->nombre_fantasia;

            $this->id               = $result->rowid;
            $this->nombre           = $result->nombre;
            $this->activo           = isset($result->fk_estado) ? $result->fk_estado : 1;
            $this->electronica_certificado              = $result->electronica_certificado;
            $this->electronica_certificado_encriptado   = $result->electronica_certificado_encriptado;
            $this->electronica_certificado_clave        = $result->electronica_certificado_clave;
            $this->verifactum_produccion                = $result->verifactum_produccion;
            $this->verifactum_produccion_fecha          = $result->verifactum_produccion_fecha;

            $this->direccion_fk_pais                    = $result->direccion_fk_pais;
            $this->direccion_fk_ccaa                    = $result->direccion_fk_ccaa;
            $this->direccion_fk_provincia               = $result->direccion_fk_provincia;
            $this->direccion_fk_municipio               = $result->direccion_fk_municipio;

            $this->telefono_fijo                        = $result->telefono_fijo;
            $this->telefono_movil                       = $result->telefono_movil;
            $this->website                              = $result->website;
            $this->creado_fecha                         = $result->creado_fecha;
            $this->creado_fk_usuario                    = $result->creado_fk_usuario;
            $this->borrado                              = $result->borrado;
            $this->borrado_fecha                        = $result->borrado_fecha;
            $this->borrado_fk_usuario                   = $result->borrado_fk_usuario;
            $this->fk_sistema_empresa_licencias         = $result->fk_sistema_empresa_licencias;
            $this->company_externo                      = $result->company_externo;
            $this->avatar                               = $result->avatar;
            $this->kit_aplica_kit_digital               = $result->kit_aplica_kit_digital;
            $this->kit_fk_tipo                          = $result->kit_fk_tipo;
            $this->kit_pdf_firmado                      = $result->kit_pdf_firmado;
            $this->kit_pdf_firmado_url_en_disco         = $result->kit_pdf_firmado_url_en_disco;
            $this->kit_direccion_completa               = $result->kit_direccion_completa;
            $this->kit_codigo_postal                    = $result->kit_codigo_postal;
            $this->kit_factura_emitida                  = $result->kit_factura_emitida;
            $this->kit_factura_emitida_fecha            = $result->kit_factura_emitida_fecha;
            $this->kit_factura_emitida_pagada           = $result->kit_factura_emitida_pagada;
            $this->kit_monto_aprobado                   = $result->kit_monto_aprobado;
            $this->kit_monto_comision                   = $result->kit_monto_comision;
            $this->kit_monto_comision_pagada            = $result->kit_monto_comision_pagada;
            $this->vendedor_fk_usuario                  = $result->vendedor_fk_usuario;
            $this->tipo                                 = $result->tipo;
            $this->cedula                               = $result->cedula;
            $this->notas_empresa                        = $result->notas_empresa;
            $this->fk_kit_digital_estado                = $result->fk_kit_digital_estado;
            $this->electronica_activo                   = $result->electronica_activo;
            $this->aceptacion_firma                     = $result->aceptacion_firma;
            $this->ventas_firma                         = $result->ventas_firma;
            $this->utiliza_inventario                   = $result->utiliza_inventario;
            $this->permitir_inventario_negativo         = $result->permitir_inventario_negativo;
            $this->integracion_romanas                  = $result->integracion_romanas;
            $this->fk_empresa_parent                    = $result->fk_empresa_parent;
            $this->tipo_cuenta                          = $result->tipo_cuenta;
            $this->fecha_cobro                          = $result->fecha_cobro;
            $this->cobro_monto                          = $result->cobro_monto;
            $this->cobro_moneda                         = $result->cobro_moneda;
            $this->periocidad_cobro                     = $result->periocidad_cobro;
            $this->funcion_cuenta                       = $result->funcion_cuenta;
            $this->vendedor                             = $result->vendedor;
            // $this->entidad                              = $result->rowid;
            $this->tipo_persona                         = $result->tipo_persona;
            $this->tipo_residencia                      = $result->tipo_residencia;
            $this->persona_nombre                       = $result->persona_nombre;
            $this->persona_apellido1                    = $result->persona_apellido1;
            $this->persona_apellido2                    = $result->persona_apellido2;
            $this->fk_tipo_identificacion_fiscal        = $result->fk_tipo_identificacion_fiscal;
            $this->numero_identificacion                = $result->numero_identificacion;
            $this->codigo_postal                        = $result->codigo_postal;
            $this->correo_electronico                   = $result->correo_electronico;
            $this->fk_sucursal                          = $result->fk_sucursal;
            $this->nombre_direccion                     = $result->nombre_direccion;
            $this->cedula_juridica                      = $result->cedula_juridica;
            $this->sujeto_impuesto                      = $result->sujeto_impuesto;
            $this->electronica_nombre                   = $result->electronica_nombre;
            $this->electronica_identificacion_tipo      = $result->electronica_identificacion_tipo;
            $this->electronica_identificacion_numero    = $result->electronica_identificacion_numero;
            $this->electronica_nombre_comercial         = $result->electronica_nombre_comercial;
            $this->electronica_provincia                = $result->electronica_provincia;
            $this->electronica_canton                   = $result->electronica_canton;
            $this->electronica_distrito                 = $result->electronica_distrito;
            $this->electronica_barrio                   = $result->electronica_barrio;
            $this->electronica_otras_senas              = $result->electronica_otras_senas;
            $this->electronica_telefono                 = $result->electronica_telefono;
            $this->electronica_fax                      = $result->electronica_fax;
            $this->retencion                            = $result->retencion;
            $this->retencion_porcentaje                 = $result->retencion_porcentaje;
            $this->retencion_porcentaje_rigue_hasta     = $result->retencion_porcentaje_rigue_hasta;
            $this->electronico_correo                   = $result->electronico_correo;
            $this->api_access_token                     = $result->api_access_token;
            $this->api_cliente                          = $result->api_cliente;
            $this->api_usuario                          = $result->api_usuario;
            $this->api_password                         = $result->api_password;
            $this->api_path                             = $result->api_path;
            $this->GTIN_distribuidor                    = $result->GTIN_distribuidor;
            $this->cuentaBanco                          = $result->cuentaBanco;
            $this->body_correos                         = $result->body_correos;
            $this->cron_job_correo_crm_actividades_por_vencer = $result->cron_job_correo_crm_actividades_por_vencer;
            
        } else {

            $this->sql     =   $sql;
            $this->error   =   implode(", ", $stmt->errorInfo()) . " " . implode(" , ", $plataforma->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        return $a;
    } // fin de modulos_activos() 








    public function ActualizarInformacionEmpresa()
    {

        // Preparar la consulta SQL para actualizar en sistema_empresa
        $sql = "UPDATE sistema_empresa 
                SET nombre = :nombre, 
                direccion_fk_pais = :direccion_fk_pais, 
                direccion_fk_ccaa = :direccion_fk_ccaa, 
                direccion_fk_provincia = :direccion_fk_provincia, 
                direccion_fk_municipio = :direccion_fk_municipio, 
                telefono_fijo = :telefono_fijo, 
                telefono_movil = :telefono_movil ,
                tipo = :tipo_persona, 
                tipo_residencia = :tipo_residencia, 
                nombre_empresa = :nombre_empresa, 
                nombre_fantasia = :nombre_fantasia, 
                fk_tipo_identificacion_fiscal = :tipo_identificacion_fiscal, 
                numero_identificacion = :numero_identificacion, 
                persona_nombre = :persona_nombre, 
                persona_apellido1 = :persona_apellido1, 
                persona_apellido2 = :persona_apellido2, 
                correo_electronico = :correo_electronico, 
                codigo_postal = :codigo_postal,
                nombre_direccion = :nombre_direccion
                WHERE rowid = :id";

        $plataforma = $this->conexion_db_plataforma();

        $stmt = $plataforma->prepare($sql);

        // Vincular los parámetros a la consulta
        $stmt->bindParam(':nombre', $this->nombre_empresa);

        $stmt->bindParam(':direccion_fk_pais', $this->direccion_fk_pais);
        $stmt->bindParam(':direccion_fk_ccaa', $this->direccion_fk_ccaa);
        $stmt->bindParam(':direccion_fk_provincia', $this->direccion_fk_provincia);
        $stmt->bindParam(':direccion_fk_municipio', $this->direccion_fk_municipio);

        $stmt->bindParam(':telefono_fijo', $this->telefono_fijo);
        $stmt->bindParam(':telefono_movil', $this->telefono_movil);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':tipo_persona', $this->tipo_persona);
        $stmt->bindParam(':tipo_residencia', $this->tipo_residencia);
        $stmt->bindParam(':nombre_empresa', $this->nombre_empresa);
        $stmt->bindParam(':nombre_fantasia', $this->nombre_fantasia);
        $stmt->bindParam(':tipo_identificacion_fiscal', $this->fk_tipo_identificacion_fiscal);
        $stmt->bindParam(':numero_identificacion', $this->numero_identificacion);
        $stmt->bindParam(':persona_nombre', $this->persona_nombre);
        $stmt->bindParam(':persona_apellido1', $this->persona_apellido1);
        $stmt->bindParam(':persona_apellido2', $this->persona_apellido2);
        $stmt->bindParam(':correo_electronico', $this->correo_electronico);
        $stmt->bindParam(':codigo_postal', $this->codigo_postal);
        $stmt->bindParam(':nombre_direccion', $this->nombre_direccion);
        $a =  $stmt->execute();


        // Ejecutar la segunda consulta
        if (!$a) {

            $this->sql     =   $sql;
            $this->error   =   implode(", ", $stmt->errorInfo()) . " " . implode(" , ", $plataforma->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
            $resultado['exito'] = false;
            $resultado['mensaje'] = 'Error al actualizar la información: ' . $this->error;
        } else {
            $resultado['exito'] = true;
            $resultado['mensaje'] = 'Información actualizada correctamente';
        }



        return $resultado;
    }



    public function udpdateAvatar($id, $avatarurl)
    {

        // Preparar la consulta SQL para actualizar el avatar
        $sql = "UPDATE sistema_empresa SET avatar = :avatarUrl WHERE rowid = :id";


        $plataforma = $this->conexion_db_plataforma();

        $stmt = $plataforma->prepare($sql);

        // Vincular los parámetros a la consulta
        $stmt->bindParam(':avatarUrl', $avatarurl);
        $stmt->bindParam(':id', $id);
        // Ejecutar la consulta
        $a =  $stmt->execute();



        // Ejecutar la segunda consulta
        if (!$a) {

            $this->sql     =   $sql;
            $this->error   =   implode(", ", $stmt->errorInfo()) . " " . implode(" , ", $plataforma->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
            $resultado['exito'] = false;
            $resultado['mensaje'] = 'Error al actualizar la información: ' . $this->error;
        } else {
            $resultado['exito'] = true;
            $resultado['mensaje'] = 'Información actualizada correctamente';
        }

        return $resultado;
    }





    /********************************************************
     * 
     * 
     *    Encontrar los detalles de configuracion 
     *    Esto solo deberia existir AQUI 
     * 
     * 
     *********************************************************/
    public function  configuracion_empresa()
    {

        $sql = "select * from fi_configuracion  where entidad = :fk_entidad AND borrado = 0 AND activo = 1 ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':fk_entidad', $this->entidad, PDO::PARAM_INT);
        $a = $stmt->execute();

        if ($a) {
            while ($result = $stmt->fetch(PDO::FETCH_OBJ)) {
                $this->configuracion[$result->configuracion] = $result->valor;
            }
        } else {

            $this->sql     =   $sql;
            $this->error   =   implode(", ", $stmt->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }


        return $this->configuracion;
    } // fin de modulos_activos() 

    public function  configuracion_sistema()
    {

        $sql = "select * from diccionario_configuracion ";
        $stmt = $this->conexion_db_utilitario()->prepare($sql);
        $a = $stmt->execute();

        if ($a) {
            while ($result = $stmt->fetch(PDO::FETCH_OBJ)) {
                $this->configuracion_sistema[$result->configuracion] = $result->valor;
            }
        } else {

            $this->sql     =   $sql;
            $this->error   =   implode(", ", $stmt->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }


        return $this->configuracion;
    }
    public function  cargar_dueno_empresa()
    {
        // Se envía correo para el dueño de la empresa (El que tiene relacion DUEÑO)
        $sqldueno = "SELECT  u.* FROM usuarios u INNER JOIN sistema_empresa_usuarios se ON se.fk_usuario = u.rowid
        WHERE se.fk_empresa = :entidad AND se.fk_tipo_relacion = 1 and activo = 1 LIMIT 1";
        $dbdueno = $this->conexion_db_plataforma()->prepare($sqldueno);
        $dbdueno->bindValue('entidad', $this->entidad, PDO::PARAM_INT);
        $dbdueno->execute();
        $objDueno = $dbdueno->fetch(PDO::FETCH_ASSOC);

        if ($objDueno) {
            $this->usuario_dueno_empresa = $objDueno;
        } else {
            $this->sql     =   $sqldueno;
            $this->error   =   implode(", ", $dbdueno->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }
        return $this->configuracion;
    }







    /***********************************************************************
     * 
     * 
     * 
     * 
     * 
     * 
     *   FALTAN HOMOLOGAR 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     **************************************************************************/


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
    public function obtener_url_avatar_encriptada($entidad) // ROWID en las tablas de fi_usuarios y usuarios
    {
        $key = $this->generar_clave_segura();
        $url_avatar_secure = ENLACE_WEB . 'servir_imagenes_avatar_empresa?img=' . $this->encriptar_rowid($entidad, $key) . '&token=' . $key;
        return $url_avatar_secure;
    }

    public function obtener_url_avatar($entidad) // ROWID en las tablas de fi_usuarios y usuarios
    {
        $key = $this->generar_clave_segura();
        $url_avatar_secure = ENLACE_WEB . 'servir_imagenes_avatar_empresa?img=' . $this->encriptar_rowid($entidad, $key) . '&token=' . $key;
        return $url_avatar_secure;
    }


    //OBTENER LA URL AVATAR DESENCRIPTADA PERO YA CON TODO Y DISCO DIRECTO
    public function obtener_url_avatar_desencriptada($rowid)
    {
        $sql = "SELECT avatar, nombre FROM sistema_empresa WHERE rowid = :rowid";

        $plataforma = $this->conexion_db_plataforma();

        $db = $plataforma->prepare($sql);
        $db->bindValue('rowid', $rowid, PDO::PARAM_STR);
        $db->execute();
        $u = $db->fetch(PDO::FETCH_ASSOC);
        $url_avatar_path = $u['avatar'];
        $nombre_completo = $u['nombre'];
        // Desglosar el nombre del archivo y la extensión
        $file_info = pathinfo($url_avatar_path);
        $filename = $file_info['filename']; // Nombre del archivo sin extensión
        $extension = $file_info['extension']; // Extensión del archivo
        // Crear el nuevo nombre del archivo con el sufijo -150x150
        $thumbnail_filename = $filename . '-150x150.' . $extension;
        // Generar la ruta completa del archivo thumbnail
        $file = ENLACE_FILES_EMPRESAS . 'avatar_empresa/' . $thumbnail_filename;
        // Verificar la URL del avatar o generar imagen de texto
        $file = $this->verificar_url_avatar_path($file, $nombre_completo);
        return $file;
    }


    public function obtener_url_avatar_pdf($rowid)
    {
        $sql = "SELECT avatar, nombre FROM sistema_empresa WHERE rowid = :rowid";

        $plataforma = $this->conexion_db_plataforma();

        $db = $plataforma->prepare($sql);
        $db->bindValue('rowid', $rowid, PDO::PARAM_STR);
        $db->execute();
        $u = $db->fetch(PDO::FETCH_ASSOC);
        $url_avatar_path = $u['avatar'];
        $nombre_completo = $u['nombre'];
        // Desglosar el nombre del archivo y la extensión
        $file_info = pathinfo($url_avatar_path);
        $filename = $file_info['filename']; // Nombre del archivo sin extensión
        $extension = $file_info['extension']; // Extensión del archivo
        // Crear el nuevo nombre del archivo con el sufijo -150x150
        $thumbnail_filename = $filename . '-150x150.' . $extension;
        // Generar la ruta completa del archivo thumbnail
        $file = ENLACE_FILES_EMPRESAS . 'avatar_empresa/' . $thumbnail_filename;
        // Verificar la URL del avatar o generar imagen de texto
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


    
    public function ActualizarConfiguracionImpuesto()
    {
        // Preparar la consulta SQL para actualizar en sistema_empresa
        $sql = "UPDATE sistema_empresa 
                SET 
                retencion                           = :retencion                        , 
                retencion_porcentaje                = :retencion_porcentaje             , 
                retencion_porcentaje_rigue_hasta    = :retencion_porcentaje_rigue_hasta 
                WHERE rowid = :id";

        $plataforma = $this->conexion_db_plataforma();

        $stmt = $plataforma->prepare($sql);

        // Vincular los parámetros a la consulta
        $stmt->bindParam(':retencion'                       , $this->retencion, PDO::PARAM_INT);
        $stmt->bindParam(':retencion_porcentaje'            , $this->retencion_porcentaje, PDO::PARAM_STR);
        $stmt->bindParam(':retencion_porcentaje_rigue_hasta', $this->retencion_porcentaje_rigue_hasta , PDO::PARAM_STR);
        
        $stmt->bindParam(':id', $this->entidad);

        $a =  $stmt->execute();

        // Ejecutar la segunda consulta
        if (!$a) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $stmt->errorInfo()) . " " . implode(" , ", $plataforma->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
            $resultado['exito'] = false;
            $resultado['mensaje'] = 'Error al actualizar la información: ' . $this->error;
        } else {
            $resultado['exito'] = true;
            $resultado['mensaje'] = 'Información actualizada correctamente';
        }
        return $resultado;
    }

    public function ActualizarVerifactum($electronica_certificado, $electronica_certificado_encriptado, $electronica_certificado_clave, $verifactum_produccion)
    {
        $sql = "UPDATE sistema_empresa SET electronica_certificado_clave = :electronica_certificado_clave, verifactum_produccion = :verifactum_produccion, verifactum_produccion_fecha = NOW() ";            
        if($electronica_certificado){
            $sql .= " , electronica_certificado = :electronica_certificado ";
        }
        if($electronica_certificado_encriptado){
            $sql .= " , electronica_certificado_encriptado = :electronica_certificado_encriptado ";
        }
        $sql .=" WHERE rowid = :id ";

        $plataforma = $this->conexion_db_plataforma();

        $stmt = $plataforma->prepare($sql);

        // Vincular los parámetros a la consulta
        if($electronica_certificado){
            $stmt->bindParam(':electronica_certificado', $electronica_certificado);
        }
         if($electronica_certificado_encriptado){
             $stmt->bindParam(':electronica_certificado_encriptado', $electronica_certificado_encriptado);
         }
         $stmt->bindParam(':electronica_certificado_clave', $electronica_certificado_clave);
         $stmt->bindParam(':verifactum_produccion', $verifactum_produccion);
         $stmt->bindParam(':id', $this->id);

        $a =  $stmt->execute();

        // Ejecutar la segunda consulta
        if (!$a) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $stmt->errorInfo()) . " " . implode(" , ", $plataforma->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
            $resultado['exito'] = false;
            $resultado['mensaje'] = 'Error al actualizar la información: ' . $this->error;
        } else {
            $resultado['exito'] = true;
            $resultado['mensaje'] = 'Información actualizada correctamente';
        }
        return $resultado;
    }

    public function ActualizarEmpresa($id)
    {
        $sql = "UPDATE sistema_empresa SET nombre = :nombre, fk_estado = :fk_estado WHERE rowid = :id ";
        
        $plataforma = $this->conexion_db_plataforma();

        $stmt = $plataforma->prepare($sql);

        // Vincular los parámetros a la consulta
         $stmt->bindParam(':nombre', $this->nombre);
         $stmt->bindParam(':fk_estado', $this->activo);
         $stmt->bindParam(':id', $id);

        $a =  $stmt->execute();

        // Ejecutar la segunda consulta
        if (!$a) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $stmt->errorInfo()) . " " . implode(" , ", $plataforma->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
            $resultado['exito'] = false;
            $resultado['mensaje'] = 'Error al actualizar la información: ' . $this->error;
        } else {
            $resultado['exito'] = true;
            $resultado['mensaje'] = 'Información actualizada correctamente';
        }
        return $resultado;
    }

    public function InsertarEmpresa()
    {
        $sql = "INSERT INTO sistema_empresa (nombre, fk_estado)
            VALUES (:nombre, :fk_estado)";
        
        $plataforma = $this->conexion_db_plataforma();

        $stmt = $plataforma->prepare($sql);

        // Vincular los parámetros a la consulta
         $stmt->bindParam(':nombre', $this->nombre);
         $stmt->bindParam(':fk_estado', $this->activo);

        $a =  $stmt->execute();

        // Ejecutar la segunda consulta
        if (!$a) {
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $stmt->errorInfo()) . " " . implode(" , ", $plataforma->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
            $resultado['exito'] = false;
            $resultado['mensaje'] = 'Error al actualizar la información: ' . $this->error;
        } else {
            $resultado['exito'] = true;
            $resultado['mensaje'] = 'Información actualizada correctamente';
        }
        return $resultado;
    }
} // Fin del Entidad 