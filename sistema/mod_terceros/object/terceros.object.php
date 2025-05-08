<?php

include_once(ENLACE_SERVIDOR . "mod_seguridad/object/seguridad.object.php");



class FiTerceros extends  Seguridad
{
    private $db;

    public $rowid;
    public $entidad;
    public $tipo;
    public $extranjero;
    public $nombre;
    public $apellidos;
    public $fecha_nacimiento;
    public $cedula;
    public $telefono;
    public $email;
    public $fk_sucursal;
   
    public $proveedor;
    public $cliente;
    public $credito;
    public $nota;
    public $creado;
    public $activo;
    public $comercial;
    public $electronica_nombre_comercial;
    public $creado_fecha;
    public $creado_fk_usuario;
    public $borrado;
    public $borrado_fecha;
    public $borrado_fk_usuario;
    public $nombre_banco;
    public $banco_oficina;
    public $banco_entidad;
    public $banco_digito_control;
    public $banco_cuenta;
    public $swift1;
    public $swift2;
    public $forma_pago;
    public $fk_categoria_cliente;

    // --- Direcciones del cliente - @rojasarmando - 13-06-2024
    public $codigo_postal;
    public $direccion;
    // public $provincia;
    // public $pais;
    public $fk_pais;
    public $fk_poblacion;
    public $direccion_fk_provincia;

    //--------------------
    public $fk_tipo_identificacion;
    public $fk_tipo_residencia;

    public $impuesto_cliente_fk_diccionario_regimen_iva;
    public $impuesto_cliente_aplica_recargo_equivalencia;
    public $impuesto_cliente_lleva_retencion;
    public $impuesto_cliente_regimen_iva_tipos_retencion;
    public $impuesto_cliente_lleva_retencion_porcentaje;


    public $obtener_listado_terceros;

    public function __construct($db, $entidad = 1)
    {
        $this->db       = $db;
        $this->entidad = $entidad;
    }
    //Aqui obtenemos el listado de categorias que pueden usar los clientes
    public function obtener_listado_categorias_clientes($entidad)
    {
        $query = "SELECT * FROM  diccionario_clientes_categorias WHERE entidad = :entidad AND estado = 1 AND borrado = 0 ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':entidad', $entidad, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    //Obtenmer los terceros contacto
    public function obtener_listado_contactos()
    {
        if (intval($this->rowid) > 0) {
            $query = 'SELECT * FROM fi_terceros_crm_contactos WHERE fk_tercero = :fk_tercero AND entidad = :entidad AND borrado = 0  ORDER BY nombre';

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':fk_tercero', $this->rowid, PDO::PARAM_INT);
            $stmt->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        } else {

            $query = 'SELECT * FROM fi_terceros_crm_contactos WHERE  entidad = :entidad AND borrado = 0  ORDER BY nombre';

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }



    public function fetch($id)
    {
        $query = "SELECT ft.*, 
                COALESCE(concat(fu.nombre, ' ', fu.apellidos), 'sin creador asignado') as nombre_creador 
        FROM fi_terceros ft 
        LEFT JOIN fi_usuarios fu ON fu.rowid = ft.creado_fk_usuario 
        WHERE ft.rowid = :rowid AND ft.entidad = :entidad";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':rowid', $id,PDO::PARAM_INT);
        $stmt->bindParam(':entidad', $this->entidad,PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->rowid = $row['rowid'];
        $this->entidad = $row['entidad'];
        $this->tipo = $row['tipo'];
        $this->fk_tipo_identificacion = $row['fk_tipo_identificacion'];
        $this->extranjero = $row['extranjero'];
        $this->nombre = $row['nombre'];
        $this->apellidos = $row['apellidos'];
        $this->fecha_nacimiento = $row['fecha_nacimiento'];
        $this->cedula = $row['cedula'];
        $this->telefono = $row['telefono'];
        $this->email = $row['email'];
        $this->fk_sucursal = $row['fk_sucursal'];
        
        $this->proveedor = $row['proveedor'];
        $this->cliente = $row['cliente'];
        $this->credito = $row['credito'];
        $this->nombre_creador = $row['nombre_creador'];
        $this->nota = $row['nota'];
        $this->creado = $row['creado'];
        $this->activo = $row['activo'];
        $this->comercial = $row['comercial'];
        $this->electronica_nombre_comercial = $row['electronica_nombre_comercial'];
        $this->nombre_banco = $row['nombre_banco'];
        $this->banco_entidad = $row['banco_entidad'];
        $this->banco_oficina = $row['banco_oficina'];
        $this->banco_digito_control = $row['banco_digito_control'];
        $this->banco_cuenta = $row['banco_cuenta'];
        $this->swift1 = $row['swift1'];
        $this->swift2 = $row['swift2'];
        $this->forma_pago = $row['forma_pago'];
        // $this->poblacion = $row['poblacion'];

        // --- Direcciones del cliente - @rojasarmando - 13-06-2024
        $this->direccion = $row['direccion'];
        // $this->provincia = $row['provincia'];
        // $this->pais = $row['pais'];
        $this->fk_poblacion = $row['fk_poblacion'];
        $this->direccion_fk_provincia = $row['direccion_fk_provincia'];
        $this->fk_pais = $row['fk_pais'];
        $this->codigo_postal = $row['codigo_postal'];
        //--------------------
        $this->fk_categoria_cliente = $row['fk_categoria_cliente'];
        $this->fk_tipo_residencia = $row['fk_tipo_residencia'];

        // --- Campos de impuestos
        $this->impuesto_cliente_fk_diccionario_regimen_iva = $row['impuesto_cliente_fk_diccionario_regimen_iva'];
        $this->impuesto_cliente_aplica_recargo_equivalencia = $row['impuesto_cliente_aplica_recargo_equivalencia'];
        $this->impuesto_cliente_lleva_retencion = $row['impuesto_cliente_lleva_retencion'];
        $this->impuesto_cliente_regimen_iva_tipos_retencion = $row['impuesto_cliente_regimen_iva_tipos_retencion'];
        $this->impuesto_cliente_lleva_retencion_porcentaje = $row['impuesto_cliente_lleva_retencion_porcentaje'];

        // --- Nuevos campos agregados por ALTER TABLE
        $this->fk_moneda = $row['fk_moneda'];
        $this->forma_pago = $row['forma_pago'];
        $this->fk_lista_precio = $row['fk_lista_precio'];
        $this->limite_credito = $row['limite_credito'];
        $this->saldo_credito = $row['saldo_credito'];
        $this->dia_pago = $row['dia_pago'];
        $this->mes_no_pago = $row['mes_no_pago'];
 
        $this->moroso = $row['moroso'];
        $this->credito_cerrado = $row['credito_cerrado'];
        $this->motivo_cierre = $row['motivo_cierre'];
        $this->electronica_nombre_comercial = $row['electronica_nombre_comercial'];
        $this->creado_fecha = date('d/m/Y', strtotime($row['creado_fecha']));  
    }


    public function nuevo_factura()
    {
        $sql = "INSERT INTO fi_terceros
        (entidad, tipo, nombre, apellidos, cedula,
        telefono, email, cliente, proveedor, credito, electronica_nombre_comercial,
        creado_fecha, creado_fk_usuario, forma_pago, impuesto_cliente_lleva_retencion, impuesto_cliente_fk_diccionario_regimen_iva, impuesto_cliente_regimen_iva_tipos_retencion, impuesto_cliente_aplica_recargo_equivalencia, poblacion, codigo_postal, provincia, fk_pais, fk_poblacion, direccion_fk_provincia, direccion, fk_tipo_residencia, fk_tipo_identificacion
        )
        VALUES
        (:entidad, :tipo, :nombre, :apellidos, :cedula,
        :telefono, :email, :cliente, :proveedor, :credito, :electronica_nombre_comercial,
        now(), :creado_fk_usuario, :forma_pago, :impuesto_cliente_lleva_retencion, :impuesto_cliente_fk_diccionario_regimen_iva, :impuesto_cliente_regimen_iva_tipos_retencion, :impuesto_cliente_aplica_recargo_equivalencia, :poblacion, :codigo_postal, :provincia, :fk_pais, :fk_poblacion, :fk_provincia, :direccion, :fk_tipo_residencia, :fk_tipo_identificacion
        )";
        $db = $this->db->prepare($sql);

        $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $db->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $db->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $db->bindValue(':apellidos', $this->apellidos, PDO::PARAM_STR);
        $db->bindValue(':electronica_nombre_comercial', $this->electronica_nombre_comercial, PDO::PARAM_STR);
        $db->bindValue(':cedula', $this->cedula, PDO::PARAM_STR);
        $db->bindValue(':telefono', $this->telefono, PDO::PARAM_STR);
        $db->bindValue(':email', $this->email, PDO::PARAM_STR);
        $db->bindValue(':cliente', $this->cliente, PDO::PARAM_INT);
        $db->bindValue(':proveedor', $this->proveedor, PDO::PARAM_INT);
        $db->bindValue(':credito', $this->credito ?? 0, PDO::PARAM_INT);
        $db->bindValue(':creado_fk_usuario', $this->creado_fk_usuario, PDO::PARAM_INT);
        $db->bindValue(':forma_pago', $this->forma_pago, PDO::PARAM_INT);

        $db->bindValue(':poblacion', $this->poblacion, PDO::PARAM_STR);
        $db->bindValue(':codigo_postal', $this->codigo_postal, PDO::PARAM_STR);
        $db->bindValue(':provincia', $this->provincia, PDO::PARAM_STR);
        $db->bindValue(':fk_pais', $this->fk_pais, PDO::PARAM_STR);
        $db->bindValue(':fk_poblacion', $this->fk_poblacion, PDO::PARAM_INT);
        $db->bindValue(':fk_provincia', $this->fk_provincia, PDO::PARAM_INT);
        $db->bindValue(':direccion', $this->direccion, PDO::PARAM_STR);
        $db->bindValue(':fk_tipo_residencia', $this->tipo_residencia, PDO::PARAM_INT);
        $db->bindValue(':fk_tipo_identificacion', $this->tipo_documento, PDO::PARAM_INT);
        

        $db->bindValue(':impuesto_cliente_lleva_retencion', $this->impuesto_cliente_lleva_retencion, PDO::PARAM_INT);
        $db->bindValue(':impuesto_cliente_fk_diccionario_regimen_iva', $this->impuesto_cliente_fk_diccionario_regimen_iva, PDO::PARAM_INT);
        $db->bindValue(':impuesto_cliente_regimen_iva_tipos_retencion', $this->impuesto_cliente_regimen_iva_tipos_retencion, PDO::PARAM_INT);
        $db->bindValue(':impuesto_cliente_aplica_recargo_equivalencia', $this->impuesto_cliente_aplica_recargo_equivalencia, PDO::PARAM_INT);

        $result = $db->execute();
        $consulta = array();

        if ($result) {
            $consulta['error'] = 0;
            $consulta['datos'] = $consulta;
            $consulta['lastid'] =  $this->db->lastInsertId();
        } else {
            $a = implode('-', $db->errorInfo());
            $a .= implode('-', $this->db->errorInfo());
            $consulta['error'] = 1;
            $consulta['datos'] = $a;
        }

        return $consulta;
    }

    public function nuevo()
    {
        $sql = "INSERT INTO fi_terceros
        (entidad, tipo, nombre, apellidos, cedula,
        telefono, email, cliente, proveedor, credito,
        nota, fecha_nacimiento,  comercial,fk_sucursal, electronica_nombre_comercial,
        creado_fecha, creado_fk_usuario, nombre_banco, banco_entidad, banco_oficina, banco_digito_control, banco_cuenta, swift1, swift2 , forma_pago, fk_categoria_cliente
        ,direccion, fk_pais, fk_poblacion, direccion_fk_provincia, codigo_postal, fk_tipo_identificacion, fk_tipo_residencia
        )
        VALUES
        (:entidad, :tipo, :nombre, :apellidos, :cedula,
        :telefono, :email, :cliente, :proveedor, :credito, :nota,
        :fecha_nacimiento,  :comercial,0, :electronica_nombre_comercial,
        now(), :creado_fk_usuario, :nombre_banco, :banco_entidad, :banco_oficina, :banco_digito_control, :banco_cuenta, :swift1, :swift2 ,   :forma_pago, :fk_categoria_cliente
        ,:direccion, :fk_pais, :fk_poblacion, :direccion_fk_provincia, :codigo_postal, :fk_tipo_identificacion, :fk_tipo_residencia
        )";
        $db = $this->db->prepare($sql);

        $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $db->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $db->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $db->bindValue(':apellidos', $this->apellidos, PDO::PARAM_STR);
        $db->bindValue(':cedula', $this->cedula, PDO::PARAM_STR);
        $db->bindValue(':telefono', $this->telefono, PDO::PARAM_STR);
        $db->bindValue(':email', $this->email, PDO::PARAM_STR);
        $db->bindValue(':cliente', $this->cliente, PDO::PARAM_INT);
        $db->bindValue(':proveedor', $this->proveedor, PDO::PARAM_INT);
        $db->bindValue(':credito', $this->credito ?? 0, PDO::PARAM_INT);
        $db->bindValue(':nota', $this->nota, PDO::PARAM_STR);
        $db->bindValue(':fecha_nacimiento', $this->fecha_nacimiento ?? null, PDO::PARAM_STR);
      
        $db->bindValue(':comercial', $this->comercial ? $this->comercial : 0, PDO::PARAM_INT);
        $db->bindValue(':electronica_nombre_comercial', $this->electronica_nombre_comercial ?
            $this->electronica_nombre_comercial : '', PDO::PARAM_STR);
        $db->bindValue(':creado_fk_usuario', $this->creado_fk_usuario, PDO::PARAM_INT);
        $db->bindValue(':nombre_banco', $this->nombre_banco, PDO::PARAM_INT);
        $db->bindValue(':banco_entidad', $this->banco_entidad, PDO::PARAM_STR);
        $db->bindValue(':banco_oficina', $this->banco_oficina, PDO::PARAM_STR);
        $db->bindValue(':banco_digito_control', $this->banco_digito_control, PDO::PARAM_STR);
        $db->bindValue(':banco_cuenta', $this->banco_cuenta, PDO::PARAM_STR);
        $db->bindValue(':swift1', $this->swift1, PDO::PARAM_STR);
        $db->bindValue(':swift2', $this->swift2, PDO::PARAM_STR);
        $db->bindValue(':forma_pago', $this->forma_pago, PDO::PARAM_INT);

        // --- DIrecciones del cliente - @rojasarmando - 13-06-2024
        $db->bindValue(':direccion', $this->direccion, PDO::PARAM_STR);
        // $db->bindValue(':provincia', $this->provincia, PDO::PARAM_STR);
        // $db->bindValue(':pais', $this->pais, PDO::PARAM_STR);

        $db->bindValue(':fk_pais', $this->fk_pais, PDO::PARAM_INT);
        $db->bindValue(':fk_poblacion', $this->fk_poblacion, PDO::PARAM_INT);
        $db->bindValue(':direccion_fk_provincia', $this->direccion_fk_provincia, PDO::PARAM_INT);

        $db->bindValue(':codigo_postal', $this->codigo_postal, PDO::PARAM_STR);
        //--------------------
        $db->bindValue(':fk_categoria_cliente', $this->fk_categoria_cliente, PDO::PARAM_INT);

        $db->bindValue(':fk_tipo_identificacion', $this->fk_tipo_identificacion, PDO::PARAM_INT);
        $db->bindValue(':fk_tipo_residencia', $this->fk_tipo_residencia, PDO::PARAM_INT);

        $result = $db->execute();
        $consulta = array();

        if ($result) {
            $consulta['error'] = 0;
            $consulta['datos'] = $consulta;
            $consulta['lastid'] =  $this->db->lastInsertId();
        } else {
            $a = implode('-', $db->errorInfo());
            $a .= implode('-', $this->db->errorInfo());
            $consulta['error'] = 1;
            $consulta['datos'] = $a;
        }

        return $consulta;
    }

    public function modificar_tercero()
    {
        // Asumiendo que $idTercero es el identificador único del tercero que queremos actualizar
        $sql = "UPDATE fi_terceros
            SET 
                tipo = :tipo,
                nombre = :nombre,
                apellidos = :apellidos,
                cedula = :cedula,
                telefono = :telefono,
                email = :email,
                cliente = :cliente,
                proveedor = :proveedor,
                credito = :credito,
                nota = :nota,
                fecha_nacimiento = :fecha_nacimiento,
              
                comercial = :comercial,
                fk_sucursal = 0,
                electronica_nombre_comercial = :electronica_nombre_comercial,
                nombre_banco = :nombre_banco,
                banco_entidad = :banco_entidad,
                banco_oficina = :banco_oficina,
                banco_digito_control = :banco_digito_control,
                banco_cuenta = :banco_cuenta,
                swift1 = :swift1,
                swift2 = :swift2 ,
                forma_pago = :forma_pago,
                activo = :activo ,
                direccion = :direccion,
                fk_pais = :fk_pais,
                fk_poblacion = :fk_poblacion,
                direccion_fk_provincia = :direccion_fk_provincia,
                codigo_postal = :codigo_postal,
                fk_categoria_cliente = :fk_categoria_cliente,
                fk_tipo_identificacion = :fk_tipo_identificacion,
                fk_tipo_residencia = :fk_tipo_residencia
            WHERE rowid = :rowid and entidad = :entidad " ;
        $db = $this->db->prepare($sql);
        $db->bindValue(':forma_pago', $this->forma_pago, PDO::PARAM_INT);

        // Aquí asignamos los valores de los parámetros como en la función original
        $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $db->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $db->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $db->bindValue(':apellidos', $this->apellidos, PDO::PARAM_STR);
        $db->bindValue(':cedula', $this->cedula, PDO::PARAM_STR);
        $db->bindValue(':telefono', $this->telefono, PDO::PARAM_STR);
        $db->bindValue(':email', $this->email, PDO::PARAM_STR);
        $db->bindValue(':cliente', $this->cliente, PDO::PARAM_INT);
        $db->bindValue(':proveedor', $this->proveedor, PDO::PARAM_INT);
        $db->bindValue(':credito', $this->credito ?? 0, PDO::PARAM_INT);
        $db->bindValue(':nota', $this->nota, PDO::PARAM_STR);
        $db->bindValue(':fecha_nacimiento', $this->fecha_nacimiento ?? null, PDO::PARAM_STR);
        
        $db->bindValue(':comercial', $this->comercial ? $this->comercial : 0, PDO::PARAM_INT);
        $db->bindValue(':electronica_nombre_comercial', $this->electronica_nombre_comercial ?? '', PDO::PARAM_STR);
        $db->bindValue(':rowid', $this->rowid, PDO::PARAM_INT);
        $db->bindValue(':nombre_banco', $this->nombre_banco, PDO::PARAM_STR);
        $db->bindValue(':banco_entidad', $this->banco_entidad, PDO::PARAM_STR);
        $db->bindValue(':banco_oficina', $this->banco_oficina, PDO::PARAM_STR);
        $db->bindValue(':banco_digito_control', $this->banco_digito_control, PDO::PARAM_STR);
        $db->bindValue(':banco_cuenta', $this->banco_cuenta, PDO::PARAM_STR);
        $db->bindValue(':swift1', $this->swift1, PDO::PARAM_STR);
        $db->bindValue(':swift2', $this->swift2, PDO::PARAM_STR);
        $db->bindValue(':activo', $this->activo, PDO::PARAM_INT);
        $db->bindValue(':fk_categoria_cliente', $this->fk_categoria_cliente, PDO::PARAM_INT);

        $db->bindValue(':fk_tipo_identificacion', $this->fk_tipo_identificacion, PDO::PARAM_INT);
        $db->bindValue(':fk_tipo_residencia', $this->fk_tipo_residencia, PDO::PARAM_INT);

        // --- DIrecciones del cliente - @rojasarmando - 13-06-2024
        $db->bindValue(':direccion', $this->direccion, PDO::PARAM_STR);
        //   $db->bindValue(':provincia', $this->provincia, PDO::PARAM_STR);
        //   $db->bindValue(':pais', $this->pais, PDO::PARAM_STR);
        $db->bindValue(':fk_pais', $this->fk_pais, PDO::PARAM_INT);
        $db->bindValue(':fk_poblacion', $this->fk_poblacion, PDO::PARAM_INT);
        $db->bindValue(':direccion_fk_provincia', $this->direccion_fk_provincia, PDO::PARAM_INT);

        $db->bindValue(':codigo_postal', $this->codigo_postal, PDO::PARAM_STR);
        //--------------------



        $result = $db->execute();
        $consulta = array();

        if ($result) {
            $consulta['error'] = 0;
            $consulta['datos'] = $consulta;
        } else {
            $a = implode('-', $db->errorInfo());
            $a .= implode('-', $this->db->errorInfo());
            $consulta['error'] = 1;
            $consulta['datos'] = $a;
        }

        return $consulta;
    }

    public function eliminar_tercero()
    {
        // Asumiendo que $idTercero es el identificador único del tercero que queremos actualizar
        $sql = "UPDATE fi_terceros
            SET activo = 0,
            borrado_fk_usuario = :borrado_fk_usuario,
            borrado = 1,
            borrado_fecha = now()
            WHERE rowid = :rowid
            AND entidad =:entidad
            AND emitio_facturas = 0
            ";
        $db = $this->db->prepare($sql);

        // Aquí asignamos el valor del parámetro para el ID del tercero
        $db->bindValue(':rowid', $this->rowid, PDO::PARAM_INT);
        $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $db->bindValue(':borrado_fk_usuario', $this->borrado_fk_usuario, PDO::PARAM_INT);

        $result = $db->execute();
        $consulta = array();

        if ($result) {
            if ( $db->rowCount() > 0 ){
                $consulta['error'] = 0;
                $consulta['datos'] = 'Tercero marcado como eliminado exitosamente.';
            }else{
                $consulta['error'] = 1;
                $consulta['datos'] = 'Ya no es posible eliminar este cliente porque tiene Facturas fiscalizadas.';
            }
        } else {
            $a = implode('-', $db->errorInfo());
            $a .= implode('-', $this->db->errorInfo());
            $consulta['error'] = 1;
            $consulta['datos'] = $a;
        }

        return $consulta;
    }

    public function validar_correo()
    {
        $sql = "SELECT COUNT(*) AS count FROM fi_terceros WHERE email = :email AND entidad=:entidad";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
        $stmt->bindValue(':entidad', $this->entidad, PDO::PARAM_STR);
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


    public function validar_cedula()
    {
        $sql = "SELECT COUNT(*) AS count FROM fi_terceros WHERE cedula = :cedula AND entidad=:entidad";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':cedula', $this->cedula, PDO::PARAM_STR);
        $stmt->bindValue(':entidad', $this->entidad, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $response = array();
        if ($result['count'] > 0) {
            $response['error'] = 1;
            $response['datos'] = 'Documento Identificaci&oacute;n ya se encuentra Registrado';
        } else {
            $response['error'] = 0;
            $response['datos'] = 'Documento Identificaci&oacute;n es &uacute;nico';
        }

        return $response;
    }


    function obtener_tipo_contacto()
    {

        $sql = "SELECT * FROM diccionario_contacto WHERE (activo = 1) ORDER BY label ASC";

        $db = $this->db->prepare($sql);
        $db->execute();

        return $db->fetchAll(PDO::FETCH_OBJ);
    }

    public function crear_dato_contacto($datos)
    {
        $sql = "INSERT INTO fi_terceros_contactos (fk_tercero, fk_diccionario_contacto, dato, detalle, 
        creado_fk_usuario, creado_fecha , entidad )
        VALUES
        (:fk_tercero, :fk_diccionario_contacto, :dato, :detalle,
        :creado_fk_usuario, NOW() , :entidad )";

        $db = $this->db->prepare($sql);
        $db->bindValue(':fk_tercero', $datos->fk_tercero, PDO::PARAM_INT);
        $db->bindValue(':fk_diccionario_contacto', $datos->fk_diccionario_contacto, PDO::PARAM_INT);
        $db->bindValue(':dato', $datos->dato, PDO::PARAM_STR);
        $db->bindValue(':detalle', $datos->detalle, PDO::PARAM_STR);
        $db->bindValue(':creado_fk_usuario', $datos->creado_fk_usuario, PDO::PARAM_INT);
        $db->bindValue(':entidad'   , $datos->entidad   , PDO::PARAM_INT);

        $result = $db->execute();
        $consulta = array();

        if ($result) {
            $consulta['error'] = 0;
            $consulta['datos'] = $consulta;
        } else {
            $a = implode('-', $db->errorInfo());
            $a .= implode('-', $this->db->errorInfo());
            $consulta['error'] = 1;
            $consulta['datos'] = $a;
        }

        return $consulta;
    }
    public function modificar_dato_contacto($datos)
    {
        $sql = "UPDATE fi_terceros_contactos SET fk_diccionario_contacto = :fk_diccionario_contacto, dato = :dato, detalle = :detalle
        WHERE rowid = :contacto_id and entidad = :entidad ";

        $db = $this->db->prepare($sql);
        $db->bindValue(':fk_diccionario_contacto'   , $datos->fk_diccionario_contacto, PDO::PARAM_INT);
        $db->bindValue(':dato'                      , $datos->dato      , PDO::PARAM_STR);
        $db->bindValue(':detalle'                   , $datos->detalle   , PDO::PARAM_STR);
        $db->bindValue(':contacto_id'               , $datos->rowid     , PDO::PARAM_INT);
        $db->bindValue(':entidad'                   , $this->entidad   , PDO::PARAM_INT);
        

        $result = $db->execute();
        $consulta = array();

        if ($result) {
            $consulta['error'] = 0;
            $consulta['datos'] = $consulta;
        } else {
            $a = implode('-', $db->errorInfo());
            $a .= implode('-', $this->db->errorInfo());
            $consulta['error'] = 1;
            $consulta['datos'] = $a;
        }

        return $consulta;
    }
    public function eliminar_tipo_contacto($datos)
    {
        $sql = "UPDATE fi_terceros_contactos SET borrado = 1,
        borrado_fk_usuario = :borrado_fk_usuario,
        borrado_fecha = NOW() WHERE rowid = :rowid and entidad = :entidad ";

        $db = $this->db->prepare($sql);
        $db->bindValue(':rowid', $datos->rowid, PDO::PARAM_INT);
        $db->bindValue(':borrado_fk_usuario', $datos->borrado_fk_usuario, PDO::PARAM_INT);
        $db->bindValue(':entidad'                  , $this->entidad   , PDO::PARAM_INT);

        $result = $db->execute();
        $consulta = array();

        if ($result) {
            $consulta['error'] = 0;
            $consulta['datos'] = $consulta;
        } else {
            $a = implode('-', $db->errorInfo());
            $a .= implode('-', $this->db->errorInfo());
            $consulta['error'] = 1;
            $consulta['datos'] = $a;
        }

        return $consulta;
    }



    //Actualizar impuestos del contacto
    public function actualizar_impuestos($datos)
    {

        $sql = "UPDATE fi_terceros SET 
            impuesto_cliente_fk_diccionario_regimen_iva = :impuesto_cliente_fk_diccionario_regimen_iva,
            impuesto_cliente_aplica_recargo_equivalencia = :impuesto_cliente_aplica_recargo_equivalencia,
            impuesto_cliente_lleva_retencion = :impuesto_cliente_lleva_retencion,
            impuesto_cliente_regimen_iva_tipos_retencion = :impuesto_cliente_regimen_iva_tipos_retencion
            WHERE rowid = :rowid 
            and entidad = :entidad 
            ";

        $db = $this->db->prepare($sql);
        $db->bindValue(':impuesto_cliente_fk_diccionario_regimen_iva', $datos->impuesto_cliente_fk_diccionario_regimen_iva, PDO::PARAM_INT);
        $db->bindValue(':impuesto_cliente_aplica_recargo_equivalencia', $datos->impuesto_cliente_aplica_recargo_equivalencia, PDO::PARAM_INT);
        $db->bindValue(':impuesto_cliente_lleva_retencion', $datos->impuesto_cliente_lleva_retencion, PDO::PARAM_INT);
        $db->bindValue(':impuesto_cliente_regimen_iva_tipos_retencion', $datos->impuesto_cliente_regimen_iva_tipos_retencion, PDO::PARAM_INT);
        $db->bindValue(':rowid' , $datos->rowid, PDO::PARAM_INT);
        $db->bindValue(':entidad'                                      , $this->entidad   , PDO::PARAM_INT);


        $result = $db->execute();
        $consulta = array();

        if ($result) {

            $consulta['error'] = 0;
            $consulta['datos'] = $consulta;
        } else {
            $a = implode('-', $db->errorInfo());
            $a .= implode('-', $this->db->errorInfo());
            $consulta['error'] = 1;
            $consulta['datos'] = $a;
        }

        return $consulta;
    }

    //



    // Con esta logica asi, si neceistamos 3 veces la lista, tendiramos que invocar 3 veces la lista.
    // por favor carguemos los atributos de los objetos para evitar esto

    public function obtener_listado_terceros($proveedor = '')
    {
        // Inicia la consulta base
        $sql = "SELECT ft.rowid, CONCAT_WS(' ', ft.nombre, ft.apellidos) AS nombre_cliente  
                FROM fi_terceros ft  
                WHERE borrado = 0  
                AND entidad = :entidad ";

        // Si $proveedor no está vacío, agregamos la condición de proveedor = 1
        if (!empty($proveedor)) {
            $sql .= "AND ft.proveedor = 1 ";
        } else {
            $sql .= "  AND ft.cliente = 1  ";
        }

        // Ordenar por nombre
        $sql .= "ORDER BY nombre ASC;";

        // Preparar la consulta
        $db = $this->db->prepare($sql);
        $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);

        // Ejecutar la consulta
        $result = $db->execute();

        if ($result) {
            $consulta['error'] = 0;
            $consulta['datos'] = $consulta;
        } else {
            // Capturar los errores si la consulta falla
            $a = implode('-', $db->errorInfo());
            $a .= implode('-', $this->db->errorInfo());
            $consulta['error'] = 1;
            $consulta['datos'] = $a;
        }

        // Obtener los resultados
        $this->obtener_listado_terceros = $db->fetchAll(PDO::FETCH_OBJ);


        return $this->obtener_listado_terceros;
    }


    public function obtener_contactos($id)
    {
        $sql = "SELECT rowid, concat(nombre,' ',apellidos) as nombre_contacto,pais_c,puesto_t, email, facebook, instagram, x_twitter, linkedin, whatsapp,DATE_FORMAT(fecha_nacimiento, '%e %M') as cumpleaños, fk_tercero FROM fi_terceros_crm_contactos WHERE fk_tercero = :fk_tercero AND borrado = 0 and entidad = :entidad ";
        $db = $this->db->prepare($sql);
        $db->bindValue(':fk_tercero', $id, PDO::PARAM_INT);
        $db->bindValue(':entidad'                  , $this->entidad   , PDO::PARAM_INT);
        $db->execute();
        return $db->fetchAll(PDO::FETCH_OBJ);
    }




    // Actualizar condiciones comerciales del contacto
    public function actualizar_condiciones_comerciales($datos)
    {

        $query = "SELECT IFNULL(SUM(total - pagado),0) as saldo_factura
        FROM fi_europa_facturas f 
        WHERE f.estado = 1 AND f.fk_tercero = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $datos->rowid);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $sql = "UPDATE fi_terceros SET 
            fk_moneda = :fk_moneda,
            forma_pago = :forma_pago,
            fk_lista_precio = :fk_lista_precio,
            limite_credito = :limite_credito,
            dia_pago = :dia_pago,
            mes_no_pago = :mes_no_pago,
 
             moroso = :moroso,
            credito_cerrado = :credito_cerrado,
            motivo_cierre = :motivo_cierre,
            saldo_credito = ( :limite_credito - :saldo_credito ),
            fk_agente = (CASE WHEN :fk_agente = 0 THEN NULL ELSE :fk_agente END),
            fk_ruta = (CASE WHEN :fk_ruta = 0 THEN NULL ELSE :fk_ruta END)
            WHERE rowid = :rowid and entidad = :entidad ";

        $db = $this->db->prepare($sql);
        $db->bindValue(':fk_moneda', $datos->fk_moneda, PDO::PARAM_INT);
        $db->bindValue(':forma_pago', $datos->forma_pago, PDO::PARAM_INT);
        $db->bindValue(':fk_lista_precio', $datos->fk_lista, PDO::PARAM_INT);
        $db->bindValue(':limite_credito', $datos->limite_credito, PDO::PARAM_STR); // Como es decimal
        $db->bindValue(':dia_pago', $datos->dia_pago, PDO::PARAM_STR); // varchar(100)
        $db->bindValue(':mes_no_pago', $datos->mes_no_pago, PDO::PARAM_STR); // varchar(100)
          $db->bindValue(':moroso', $datos->cliente_moroso, PDO::PARAM_INT); // Es INT(1)
        $db->bindValue(':credito_cerrado', $datos->credito_cerrado, PDO::PARAM_INT); // Es INT(1)
        $db->bindValue(':motivo_cierre', $datos->motivo_cierre, PDO::PARAM_STR); // varchar(100)
        $db->bindValue(':rowid', $datos->rowid, PDO::PARAM_INT);

        $db->bindValue(':fk_agente', $datos->fk_agente, PDO::PARAM_INT);
        $db->bindValue(':fk_ruta', $datos->fk_ruta, PDO::PARAM_INT);
        $db->bindValue(':entidad'                  , $this->entidad   , PDO::PARAM_INT);

        $db->bindValue(':saldo_credito', $row['saldo_factura'], PDO::PARAM_STR);

        $result = $db->execute();
        $consulta = array();

        if ($result) {
            // Si la consulta fue exitosa, asignamos los valores adecuados.
            $this->id = $datos->rowid;
            $resultado['id']      = $datos->rowid;
            $resultado['exito']   = 1; // Cambié esto a 1 ya que parece que $a no estaba siendo definido en tu código.
            $resultado['mensaje'] = "Condiciones comerciales actualizadas correctamente";
        } else {
            // Si la consulta falla, capturamos los errores y los almacenamos.
            $resultado['exito'] = 0;
            $resultado['mensaje'] = implode(", ", $db->errorInfo())." ". implode(", ", $this->db->errorInfo()); // Asegúrate de que $db esté correctamente inicializado.
        }

        // Retornamos el resultado en lugar de $consulta, ya que estamos utilizando $resultado.
        return $resultado;
    }

    // Dejar de Utilizar ASAP    
    public function get_cliente_default()
    {

        $query_configuracion = "SELECT IFNULL(valor,0) as valor FROM fi_configuracion WHERE entidad = :entidad AND configuracion = 'cliente_defecto' ";
        $stmtconfiguracion = $this->db->prepare($query_configuracion);
        $stmtconfiguracion->bindParam(':entidad', $_SESSION["Entidad"], PDO::PARAM_INT);
        $stmtconfiguracion->execute();
        $rowConfiguracion = $stmtconfiguracion->fetch(PDO::FETCH_ASSOC);
        if ($rowConfiguracion["valor"] != 0) {
            $this->fetch($rowConfiguracion["valor"]);
        }
    }
}
