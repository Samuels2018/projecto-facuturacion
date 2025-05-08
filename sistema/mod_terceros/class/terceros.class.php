<?php

if (!empty($_POST['action'])):
    session_start();

    //si no hay usuario autenticado, cerrar conexion
    if (!isset($_SESSION['usuario'])) {
        echo acceso_invalido();
        exit(1);
    }

    include_once "../../conf/conf.php";
    include_once ENLACE_SERVIDOR . "mod_terceros/object/terceros.object.php";

    $cliente = new FiTerceros($dbh, $_SESSION['Entidad']);
    switch ($_POST['action']):


        case 'nuevo_cliente_factura':
            $cliente->forma_pago = $_POST['forma_pago'];

            // $cedula =  preg_replace('/\D/', '', $_POST['cedula']);

            $cliente->entidad = $_SESSION['Entidad'];
            $cliente->tipo = $_POST['tipo'];
            $cliente->nombre = $_POST['nombre'];
            $cliente->apellidos = $_POST['apellidos'];
            $cliente->tipo_residencia = $_POST['tipo_residencia'];
            $cliente->tipo_documento = $_POST['tipo_documento'];
            $cliente->electronica_nombre_comercial = $_POST['comercial'];
            $cliente->extranjero = 0;
            $cliente->cedula = $_POST['cedula'];
            $cliente->telefono = $_POST['telefono'];
            $cliente->email = $_POST['email'];

            $cliente->telefono = $_POST['telefono'];
            $cliente->poblacion = $_POST['poblacion'];
            $cliente->fk_poblacion = $_POST['ccaa'];
            $cliente->codigo_postal = $_POST['codigo_postal'];
            $cliente->provincia = $_POST['provincia'];
            $cliente->fk_provincia = $_POST['provincia'];
            $cliente->direccion = $_POST['direccion'];
            $cliente->fk_pais = $_POST['fk_pais'];

            $cliente->cliente = ($_POST['cliente']??0);
            $cliente->proveedor = ($_POST['proveedor']??0);
            $cliente->credito = 0;

            $cliente->creado_fk_usuario = $_SESSION['usuario'];

            $cliente->impuesto_cliente_lleva_retencion = $_POST['impuesto_cliente_lleva_retencion'];
            $cliente->impuesto_cliente_fk_diccionario_regimen_iva = $_POST['impuesto_cliente_fk_diccionario_regimen_iva'];
            $cliente->impuesto_cliente_regimen_iva_tipos_retencion = $_POST['impuesto_cliente_regimen_iva_tipos_retencion'];
            $cliente->impuesto_cliente_aplica_recargo_equivalencia = $_POST['impuesto_cliente_aplica_recargo_equivalencia'];
            
            $result = $cliente->nuevo_factura();

            echo json_encode($result);
            break;


        case 'nuevo_cliente':
            $cliente->forma_pago = $_POST['forma_pago'];

            // $cedula =  preg_replace('/\D/', '', $_POST['cedula']);

            $cliente->entidad = $_SESSION['Entidad'];
            $cliente->nombre = $_POST['nombre'];
            $cliente->apellidos = $_POST['apellidos'];
            $cliente->cedula = $_POST['cedula'];
            $cliente->telefono = $_POST['telefono'];
            $cliente->email = $_POST['email'];
            $cliente->cliente = $_POST['cliente'];
            $cliente->proveedor = $_POST['proveedor'];
            $cliente->credito = $_POST['credito'];
            $cliente->nota = $_POST['nota'];
            $cliente->fecha_nacimiento = $_POST['fecha_nacimiento'];
            $cliente->rx = $_POST['rx'];
            $cliente->addd = $_POST['add'];
            $cliente->tipo = $_POST['tipo'];
            $cliente->DP1 = $_POST['DP1'];
            $cliente->DP2 = $_POST['DP2'];
            $cliente->comercial = $_POST['comercial'];

            $cliente->creado_fk_usuario = $_SESSION['usuario'];

            $cliente->nombre_banco = $_POST['nombre_banco'];
            $cliente->banco_entidad = $_POST['banco_entidad'];
            $cliente->banco_oficina = $_POST['banco_oficina'];
            $cliente->banco_digito_control = $_POST['banco_digito_control'];
            $cliente->banco_cuenta = $_POST['banco_cuenta'];
            $cliente->swift1 = $_POST['swift1'];
            $cliente->swift2 = $_POST['swift2'];

            // ---  Direccion del cliente -- @rojasarmando -- 13-03-2024
            $cliente->direccion = $_POST['direccion'];
            // $cliente->provincia = $_POST['provincia'];
            $cliente->codigo_postal = $_POST['codigo_postal'];
            // $cliente->pais = $_POST['pais'];

            $cliente->fk_pais = $_POST['fk_pais'];
            $cliente->fk_poblacion = $_POST['fk_poblacion'];
            $cliente->direccion_fk_provincia = $_POST['direccion_fk_provincia'];

            // ------------------------------
            $cliente->fk_tipo_identificacion = $_POST['fk_tipo_identificacion'];
            $cliente->fk_tipo_residencia = $_POST['fk_tipo_residencia'];

            $cliente->fk_categoria_cliente = $_POST['fk_categoria_cliente'];
            $cliente->electronica_nombre_comercial = $_POST['electronica_nombre_comercial'];
            $result = $cliente->nuevo();

            echo json_encode($result);
            break;

        case 'modificar_tercero':
            $cliente->forma_pago = $_POST['forma_pago'];

            $cliente->rowid = $_POST['rowid'];
            $cliente->entidad = $_SESSION['Entidad'];
            $cliente->nombre = $_POST['nombre'];
            $cliente->apellidos = $_POST['apellidos'];
            $cliente->cedula = $_POST['cedula'];
            $cliente->telefono = $_POST['telefono'];
            $cliente->email = $_POST['email'];
            $cliente->cliente = $_POST['cliente'];
            $cliente->proveedor = $_POST['proveedor'];
            $cliente->credito = $_POST['credito'];
            $cliente->nota = $_POST['nota'];
            $cliente->fecha_nacimiento = $_POST['fecha_nacimiento'];
            $cliente->rx = $_POST['rx'];
            $cliente->addd = $_POST['add'];
            $cliente->tipo = $_POST['tipo'];
            $cliente->DP1 = $_POST['DP1'];
            $cliente->DP2 = $_POST['DP2'];
            $cliente->comercial = $_POST['comercial'];
            // $cliente->electronica_nombre_comercial = $_POST['apellidos'];
            $cliente->nombre_banco = $_POST['nombre_banco'];
            $cliente->banco_entidad = $_POST['banco_entidad'];
            $cliente->banco_oficina = $_POST['banco_oficina'];
            $cliente->banco_digito_control = $_POST['banco_digito_control'];
            $cliente->banco_cuenta = $_POST['banco_cuenta'];
            $cliente->swift1 = $_POST['swift1'];
            $cliente->swift2 = $_POST['swift2'];
            $cliente->activo = $_POST['activo'];

            // ---  Direccion del cliente -- @rojasarmando -- 13-03-2024
            $cliente->direccion = $_POST['direccion'];
            $cliente->codigo_postal = $_POST['codigo_postal'];
            //  $cliente->provincia = $_POST['provincia'];
            //  $cliente->pais = $_POST['pais'];
            $cliente->fk_pais = $_POST['fk_pais'];
            $cliente->fk_poblacion = $_POST['fk_poblacion'];
            $cliente->direccion_fk_provincia = $_POST['direccion_fk_provincia'];
            // ------------------------------
            $cliente->fk_tipo_identificacion = $_POST['fk_tipo_identificacion'];
            $cliente->fk_tipo_residencia = $_POST['fk_tipo_residencia'];

            $cliente->fk_categoria_cliente = $_POST['fk_categoria_cliente'];
            $cliente->electronica_nombre_comercial = $_POST['electronica_nombre_comercial'];
            $result = $cliente->modificar_tercero();

            echo json_encode($result);
            break;

        case 'eliminar_tercero':

            $cliente->rowid = $_POST['rowid'];
            $cliente->borrado_fk_usuario = $_SESSION['usuario'];
            $cliente->entidad = $_SESSION['Entidad'];
            $result = $cliente->eliminar_tercero();

            echo json_encode($result);

            break;

        case 'validar_correo':

            $cliente->email = $_POST['email'];
            $cliente->entidad = $_SESSION['Entidad'];
            $result = $cliente->validar_correo();

            echo json_encode($result);
            break;

        case 'validar_cedula':

            $cliente->cedula = $_POST['cedula'];
            $cliente->entidad = $_SESSION['Entidad'];
            $result = $cliente->validar_cedula();
            echo json_encode($result);
            break;


        case 'agregar_dato_contacto':
            $datos = new stdClass();
            $datos->fk_diccionario_contacto = $_POST['contacto_tipo'];
            $datos->dato = $_POST['contacto_data'];
            $datos->detalle = $_POST['contacto_detalle'];
            $datos->fk_tercero = $_POST['rowid'];
            $datos->creado_fk_usuario = $_SESSION['usuario'];
            $datos->entidad = $_SESSION['Entidad'];

            $result = $cliente->crear_dato_contacto($datos);

            echo json_encode($result);
            break;
        case 'modificar_dato_contacto':
            $datos = new stdClass();
            $datos->fk_diccionario_contacto = $_POST['contacto_tipo'];
            $datos->dato = $_POST['contacto_data'];
            $datos->detalle = $_POST['contacto_detalle'];
            $datos->creado_fk_usuario = $_SESSION['usuario'];
            $datos->rowid = $_POST['contacto_id'];

            $result = $cliente->modificar_dato_contacto($datos);

            echo json_encode($result);
            break;

        case 'eliminar_dato_contacto':
            $datos = new stdClass();
            $datos->borrado_fk_usuario = $_SESSION['usuario'];
            $datos->rowid = $_POST['rowid'];

            $result = $cliente->eliminar_tipo_contacto($datos);

            echo json_encode($result);
            break;

        case 'obtener_contactos':

            $result = $cliente->obtener_contactos($_POST['rowid']);
            echo json_encode($result);
            break;

        case 'buscar_contactos_cliente':
            //vamos a buscar los contactos de este cliente asociados
            //estos se buscan en la tabla fi_terceros_crm_contactos
            $cliente->rowid = $_POST['fk_tercero'];
            $result = $cliente->obtener_listado_contactos();
            $fk_tercero_selected = $_POST['fk_tercero_selected'];
            echo '<option value="">Seleccione el contacto </option>';
            foreach ($result as $key => $value) {
                $selected = '';
                if (intval($fk_tercero_selected) > 0) {
                    if (intval($result[$key]["rowid"]) ===  intval($fk_tercero_selected)) {
                        $selected = 'selected';
                    }
                }
                echo '<option ' . $selected . ' value="' . $result[$key]["rowid"] . '" required>' . $result[$key]["nombre"] . ' ' . $result[$key]["apellidos"] . '</option>';
            }
            break;


        case 'modificacion_impuestos':
            $datos = new stdClass();
            $datos->impuesto_cliente_fk_diccionario_regimen_iva = $_REQUEST['impuesto_cliente_fk_diccionario_regimen_iva'];
            $datos->impuesto_cliente_aplica_recargo_equivalencia = $_REQUEST['impuesto_cliente_aplica_recargo_equivalencia'];
            $datos->impuesto_cliente_lleva_retencion = $_REQUEST['impuesto_cliente_lleva_retencion'];
            $datos->impuesto_cliente_regimen_iva_tipos_retencion = $_REQUEST['impuesto_cliente_regimen_iva_tipos_retencion'];

            $datos->rowid = $_REQUEST['rowid'];


            $result = $cliente->actualizar_impuestos($datos);

            echo json_encode($result);
            break;

        case 'listar_categoria_clientes':


            // Supongamos que obtienes las categorías desde la función
            $categorias = []; // Array que almacenará las categorías

            // Llamamos a la función obtener_listado_categorias_clientes
            $rows = $cliente->obtener_listado_categorias_clientes($_SESSION['Entidad']);

            // Recorremos los resultados y los formateamos como se necesita
            foreach ($rows as $row) {
                $categorias[] = [
                    'id' => $row['rowid'],        // Asignar el 'rowid' al campo 'id'
                    'nombre' => $row['label']  // Asignar 'nombre_categoria' al campo 'label'
                ];
            }

            // Enviamos el array de categorías como respuesta en formato JSON
            echo json_encode($categorias);
            break;

        case 'condiciones_comerciales':
            $datos = new stdClass();
            $datos->limite_credito = $_REQUEST['limite_credito'];
            $datos->fk_lista = $_REQUEST['fk_lista'];
            //  $datos->saldo_credito = $_REQUEST['saldo_credito'];
            $datos->forma_pago = $_REQUEST['fk_forma_pago'];
            $datos->descuento_pronto_pago = $_REQUEST['descuento_pronto_pago'];
            $datos->dia_pago = $_REQUEST['dia_pago'];
            $datos->mes_no_pago = $_REQUEST['mes_no_pago'];

            $datos->fk_agente = $_REQUEST['fk_agente'];
            $datos->fk_ruta = $_REQUEST['fk_ruta'];

            $datos->descuento_volumen=0;
            $datos->descuento_por_articulo=0;
            $datos->credito_cerrado=0;
            $datos->cliente_moroso=0;
            
            if($_REQUEST['descuento_volumen'] && $_REQUEST['descuento_volumen'] == 'true') { $datos->descuento_volumen=1; }
            if($_REQUEST['descuento_por_articulo'] && $_REQUEST['descuento_por_articulo'] == 'true') { $datos->descuento_por_articulo=1; }
            if($_REQUEST['credito_cerrado'] && $_REQUEST['credito_cerrado'] == 'true') { $datos->credito_cerrado=1; }
            if($_REQUEST['cliente_moroso'] && $_REQUEST['cliente_moroso'] == 'true') { $datos->cliente_moroso=1; }

            $datos->motivo_cierre = isset($_REQUEST['motivo_cierre']) ? $_REQUEST['motivo_cierre'] : null; // Solo si está visible
            $datos->rowid = $_REQUEST['rowid'];

            // Llamar a la función para actualizar las condiciones comerciales
            $result = $cliente->actualizar_condiciones_comerciales($datos);
            
            echo json_encode( $result );
            
            
            break;




        default:
            echo 'Accion no definida';
            break;

    endswitch;
endif;
