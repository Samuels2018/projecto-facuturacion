<?php
if (!$_GET['accion'] || $_GET['accion'] == '') {
    $tpl = "mod_tpl/tpl/plantilla.php";
} else {

switch ($_GET['accion']) {

    case '':if ($usuario->vendedor) {$tpl = "tpl/dashboard.vendedor.php";} else { $tpl = "";}
        break;

    //------------------------MODULO PRODUCTOS-------------------------------------//
    //-------------------------------------------------------------

    //----------------------------------------------------------
    //
    //
    //        🛠Sistema CC Travel
    //
    //
    //----------------------------------------------------------

    case 'listado_turisticos':$tpl = "mod_medida_cctravel_sitios_turisticos/tpl/listado.php";
        break;
    case 'turistico':$tpl = "mod_medida_cctravel_sitios_turisticos/tpl/ver.php";
        break;
    case 'turistico_editar':$tpl = "mod_medida_cctravel_sitios_turisticos/tpl/editar.php";
        break;
    case 'turistico_crear':$tpl = "mod_medida_cctravel_sitios_turisticos/tpl/editar.php";
        break;
    /*case 'agenda':$tpl = "mod_agenda/tpl/agenda.php";
        break;*/
    case 'notificaciones_cc':$tpl = "mod_notificaciones_cctravel/tpl/listado.php";
        break;
    case 'reservas':$tpl = "mod_medida_cctravel_reservas/tpl/listado.php";
        break;
    case 'nueva_reserva':$tpl = "mod_medida_cctravel_reservas/tpl/editar.php";
        break;
    case 'ver_reserva':$tpl = "mod_medida_cctravel_reservas/tpl/ver.php";
        break;
    case 'modificar_reserva':$tpl = "mod_medida_cctravel_reservas/tpl/editar.php";
        break;

    case 'turistico_nueva_cotizacion':$tpl = "mod_medida_cctravel_cotizaciones/tpl/editar.php";
        break;
    case 'turistico_modificar_cotizacion':$tpl = "mod_medida_cctravel_cotizaciones/tpl/editar.php";
        break;
    case 'turistico_ver_cotizacion':$tpl = "mod_medida_cctravel_cotizaciones/tpl/ver.php";
        break;
    case 'consideraciones_cancelaciones':$tpl = "mod_medida_cctravel_consideraciones_cancelaciones/tpl/listado.php";
        break;
    case 'reporte_cotizaciones':$tpl = "mod_medida_cctravel_reportes/tpl/listado_cotizaciones.php";
        break;
    case 'reporte_reservas':$tpl = "mod_medida_cctravel_reportes/tpl/listado_reservas.php";
        break;

    //----------------------------------------------------------
    //
    //
    //            para pruebas
    //
    //
    //----------------------------------------------------------

    // var_dump de la sesión activa
    case 'configuracion_1':$tpl = "mod_configuracion/tpl/configuracion1.tpl";
        break;

    //catalogo de hacienda
    case 'catalogo_hacienda':$tpl = "mod_catalogo_hacienda/tpl/catalogo.php";
        break;

    // tabla inteligente en la lista de productos (no se activa en producción por que puede demorar en cargar, solo para desarrollo/inclusion/revision productos )
    case 'productos_listado_dt':$tpl = "mod_productos/tpl/productos_listado_dt.php";
        break;

    case 'productos_importacion':$tpl = $usuario->validPermissionUser('ver_menu_productos') ? "mod_importacion/tpl/listado.importaciones.php" : "mod_importacion/tpl/listado.importaciones.php";
        break;

    case 'facturas_listado_control':$tpl = "mod_facturacion/tpl/facturas_listado_control.tpl";
        break;

    // tipo de cambio del dolar
    case 'tipo_cambio':$tpl = "mod_tipo_cambio/tpl/tipo_cambio.php";
        break;

 
    ///---------------------------------------------------------
    case 'gasto_listado':$tpl = "mod_gastos/tpl/listado.tpl";
        break;
    case 'proyectos_listado':$tpl = "mod_proyectos/tpl/listado.tpl";
        break;
    case 'nuevo_gasto':$tpl = "mod_gastos/tpl/ver_gasto.tpl";
        break;
    case 'nuevo_proyecto':$tpl = "mod_proyectos/tpl/ver_proyecto.tpl";
        break;
    case 'ver_gasto':$tpl = "mod_gastos/tpl/ver_gasto.tpl";
        break;
    case 'informe_gastos':$tpl = "mod_gastos/tpl/informe_gastos.tpl";
        break;
    case 'expense_type_list':$tpl = "mod_gastos/tpl/tipo_gasto_listado.tpl";
        break;

    ///---------------------------------------------------------
    case 'rh':$tpl = "mod_usuarios/tpl/rh.tpl";
        break;
    case 'nuevo_empleado':$tpl = "mod_usuarios/tpl/ver_empleado.tpl";
        break;
    case 'ver_empleado':$tpl = "mod_usuarios/tpl/ver_empleado.tpl";
        break;
    case 'ver_empleado_agenda':$tpl = "mod_usuarios/tpl/ver_empleado_agenda.tpl";
        break;
    case 'ver_empleado_clave':$tpl = "mod_usuarios/tpl/ver_empleado_clave.tpl";
        break;

    ///---------------------------------------------------------
    case 'crear_planilla':$tpl = "tpl/nominas/crear_nomina.tpl";
        break;

    ///---------------------------------------------------------
    case 'proyectos':$tpl = "mod_proyectos/tpl/listado.tpl";
        break;
    case 'proyecto_nuevo':$tpl = "mod_proyectos/tpl/ver_proyecto.tpl";
        break;
    case 'proyecto_ver':$tpl = "mod_proyectos/tpl/ver_proyecto.tpl";
        break;

    ///---------------------------------------------------------
    case 'contratos':$tpl = "tpl/contratos/listado.tpl";
        break;

    ///------------------------------------------------------------------------
    case 'simplificadas':$tpl = "mod_simplificada/tpl/listado.php";
        break;
    case 'nueva_simplificada':$tpl = "mod_simplificada/tpl/nueva.tpl";
        break;
    case 'simplificada':$tpl = "mod_simplificada/tpl/ver.php";
        break;
    case 'simplificada_validar':$tpl = "mod_simplificada/tpl/ver.php";
        break;

    ///---------------------------------------------------------
    case 'clientes_listado':$tpl = "mod_terceros/tpl/clientes_listado.php";
        break;
    case 'clientes_nuevo':$tpl = "mod_terceros/tpl/clientes_nuevo.php";
        break;
    case 'clientes_editar':$tpl = "mod_terceros/tpl/clientes_nuevo.php";
        break;
    case 'clientes_medico':$tpl = "mod_terceros/tpl/clientes_medico.tpl";
        break;
    case 'eliminar_clientes_medico':$tpl = "mod_terceros/tpl/clientes_medico.tpl";
        break;

        case 'cliente_categorias':$tpl = "mod_cliente_categoria/tpl/listado_cliente_categoria.php";
        break;

        case 'editar_cliente_categoria':$tpl = "mod_cliente_categoria/tpl/editar_cliente_categoria.php";
        break;

        case 'crear_cliente_categoria':$tpl = "mod_cliente_categoria/tpl/editar_cliente_categoria.php";
        break;


        case 'formas_pago':$tpl = "mod_formas_pago/tpl/listado_formas_pago.php";
        break;

        case 'editar_forma_pago':$tpl = "mod_formas_pago/tpl/editar_forma_pago.php";
        break;

        case 'crear_forma_pago':$tpl = "mod_formas_pago/tpl/editar_forma_pago.php";
        break;


        

    ////-----------------------------------------------------

    case 'comerciales_listado':$tpl = "mod_comerciales/tpl/comerciales_listado.tpl";
        break;
    case 'comerciales_nuevo':$tpl = "mod_comerciales/tpl/comerciales_nuevo.tpl";
        break;

    ////-----------------------------------------------------
    case 'proveedores_listado':$tpl = "mod_terceros/tpl/proveedores_listado.php";
        break;
    //    case 'proveedores_nuevo'                  :   $tpl="mod_terceros/tpl/clientes_nuevo.tpl";     break;

    case 'proveedores_nuevo':$tpl = "mod_terceros/tpl/clientes_nuevo.php";
    case 'proveedores_editar':$tpl = "mod_terceros/tpl/clientes_nuevo.php";

        break;

    ///--------------------------------------------------CONFIGURACION-------
    case 'configuracion':$tpl = "mod_configuracion/tpl/configurar_imagenes.php";
        break;

    case 'perfiles_usuarios':$tpl = "mod_configuracion_empresa/tpl/perfiles_usuarios.php";
        break;

        case 'usuarios_listado':$tpl = "mod_usuarios/tpl/listado.php"; 
        break;

        case 'usuario_editar':$tpl = "mod_usuarios/tpl/usuario_editar.php"; 
        break;

        case 'usuario_crear':$tpl = "mod_usuarios/tpl/usuario_editar.php"; 
        break;

    case 'nuevo_perfil':$tpl = "mod_configuracion_empresa/tpl/nuevo_perfil.php";
        break;

    case 'bodega_listado':$tpl = "mod_stock/tpl/bodegas_listado.php";
        break;
    case 'bodega_nueva':$tpl = "mod_stock/tpl/bodega_nueva.tpl";
        break;

    ///---------------------------------------------------------
   

    case 'nueva_factura2':$tpl = "mod_facturacion/tpl/ver_factura2.tpl";
        break;
    case 'validar_factura2':$tpl = "mod_facturacion/tpl/ver_factura2.tpl";
        break;
 

    case 'eliminar_factura':$tpl = "mod_facturacion/tpl/ver_factura.tpl";
        break;
    case 'validar_factura_manual':$tpl = "mod_facturacion/tpl/ver_factura.tpl";
        break;

 

    case 'factura_listado_dev':$notify_ = "Listado de Facturas Emitidas DEV";
        $tpl = "mod_facturacion/tpl/facturas_listado_dev.tpl";
        break;

    case 'facturas_cobrar':$notify_ = "Listado de Facturas Emitidas ";
        $tpl = "mod_cuentas_cobrar/tpl/listado.php";
        break;

    case 'pagar':$tpl = "mod_facturacion/tpl/ver_pago.tpl";
        break;
    case 'pagos_listado':$tpl = "mod_facturacion/tpl/pagos_listado.tpl";
        break;
    case 'nueva_factura.prueba':$tpl = "mod_facturacion/tpl/ver_factura1.tpl";
        break;

    //-----------------------------------------------------------

    case 'ver_viaje':$tpl = "mod_viaje/tpl/ver_viaje.tpl";
        break;
    case 'listado_viaje':$tpl = "mod_viaje/tpl/viajes_listado.tpl";
        break;
    case 'crear_nuevo_viaje':$tpl = "mod_viaje/class/crear_nuevo_viaje.php";
        break;
    //-----------------------------------------------------------

    //---------------------------------------------------------
    //
    //   Listado Nuevo para FacturaElectronica
    //
    case 'factura_listado_credito':$tpl = "mod_notas_credito_debido/tpl/listado.debito.php";
        break;
    case 'factura_listado_debito':$tpl = "mod_notas_credito_debido/tpl/listado.debito.php";
        break;
    case 'ver_nota':

        // ALEXIS SANCHEZ 14.01.21
        if ($_SESSION['licencia'] == "33e75ff09dd601bbe69f351039152189" // api api
             or ($_SESSION['licencia'] == "c4ca4238a0b923820dcc509a6f75849b") // general
             or $_SESSION['licencia'] == "c9f0f895fb98ab9159f51fd0297e236d" // libreria ideas
             or ($_SESSION['licencia'] == "70efdf2ec9b086079795c442636b55fb") // Mi Ferreteria
             or ($_SESSION['licencia'] == "3c59dc048e8850243be8079a5c74d079") // hidalgo2

        ) {
            $tpl = "mod_notas_credito_debido/tpl/ver.nota.exoneracion.php";
            break;
        } else {
            $tpl = "mod_notas_credito_debido/tpl/ver.nota.php";
        }

        break;

    case 'nueva_nota':$tpl = "mod_notas_credito_debido/tpl/nueva.nota.php";
        break;

    case 'nota_validar':
        // ALEXIS SANCHEZ 14.01.21
        if ($_SESSION['licencia'] == "33e75ff09dd601bbe69f351039152189" // api api
             or ($_SESSION['licencia'] == "c4ca4238a0b923820dcc509a6f75849b") // general
             or $_SESSION['licencia'] == "c9f0f895fb98ab9159f51fd0297e236d" // libreria ideas
             or ($_SESSION['licencia'] == "70efdf2ec9b086079795c442636b55fb") // Mi Ferreteria
             or ($_SESSION['licencia'] == "3c59dc048e8850243be8079a5c74d079") // hidalgo2

        ) {
            $tpl = "mod_notas_credito_debido/tpl/ver.nota.exoneracion.php";
        } else {
            $tpl = "mod_notas_credito_debido/tpl/ver.nota.php";

        }

        break;

    ///-----------------------------------------------------------------------------------
    case 'cierre':$tpl = "mod_cierre/index.php";
        break;
    case 'informe_ventas':$tpl = "excel/informes_ventas/index.php";
        break;
    case 'informe_ventas_admin':$tpl = "mod_informes/ventas/exportarExcelAdmin.php";
        break;
    case 'informe_compras_admin':$tpl = "mod_informes/compras/exportarExcelAdmin.php";
        break;
    case 'informe_ventas_categorias':$tpl = "mod_informes/ventas_categorias/index.php";
        break;
    case 'informe_pendientes_pago_admin':$tpl = "mod_informes/pendientes_pago/exportarExcelAdmin.php";
        break;

    case 'informe_compras_simplicadas':$tpl = "mod_informes/compras_simplicadas/exportarExcelAdmin.php";
        break;

    case 'cierre_impresora':$tpl = "mod_cierre/cierre_impresora.tpl";
        break;
    case 'cierre_impresora_seleccionar_rangos':$tpl = "mod_cierre/cierre_impresora_seleccionar_rangos.tpl";
        break;

    // ---------------------------------------------------------------------------
    //Compras

    case 'compras_listado':$tpl = "mod_compras/tpl/compras_listado.tpl";
        break;
    case 'ver_compra':$tpl = "mod_compras/tpl/ver_compra.php";
        break;
    case 'nueva_compra':$tpl = "mod_compras/tpl/ver_compra.tpl";
        break;
    case 'validar_compra':$tpl = "mod_compras/tpl/ver_compra.tpl";
        break;
    case 'pagar_compra':$tpl = "mod_compras/tpl/ver_compra.tpl";
        break;

    // ---------------------------------------------------------------------------
    //Cotizaciones

    case 'listado_cotizaciones':
        $tpl = "mod_cotizaciones/tpl/listado_cotizaciones.php";
        break;
    case 'ver_cotizacion':

        $tpl = "mod_cotizaciones/tpl/ver_cotizacion.php";

        break;
    case 'nueva_cotizacion':

        $tpl = "mod_cotizaciones/tpl/ver_cotizacion.php";

     
        break;

    case 'nueva_cotizacion_dev':

        $tpl = "mod_cotizaciones/tpl/ver_cotizacion2.tpl";

        break;

    //------------------------------------------
    case 'nueva_tarea':$tpl = "mod_crm/tpl/nueva_tarea.php";
        break;
    case 'tareas':$tpl = "mod_crm/tpl/listado_tarea.php";
        break;
    case 'ver_tarea':$tpl = "mod_crm/tpl/tarea.php";
        break;
    case 'tarea_historico':$tpl = "mod_crm/tpl/historico.php";
        break;

    // ---------------------------------------------------------------------------
    //CRM

    case 'contactos_crm':$tpl = $usuario->validPermissionUser('ver_contactos_crm') ? "mod_crm/tpl/contactos_listado.php" : "mod_pagina/tpl/permiso_denegado.php";
        break;
    case 'forecast_crm':$tpl = $usuario->validPermissionUser('ver_actividades_crm') ? "mod_crm_forecast/tpl/forecast_listado.php" : "mod_pagina/tpl/permiso_denegado.php";
        break;
    case 'reporte_forecast':$tpl = $usuario->validPermissionUser('ver_actividades_crm') ? "mod_crm_forecast/tpl/reporte_forecast.php" : "mod_pagina/tpl/permiso_denegado.php";
        break;
    case 'agenda_crm':$tpl = $usuario->validPermissionUser('ver_actividades_crm') ? "mod_crm_agenda/tpl/agenda.php" : "mod_pagina/tpl/permiso_denegado.php";
        break;
    case 'forecast_detalle':$tpl = $usuario->validPermissionUser('ver_actividades_crm') ? "mod_crm_forecast/tpl/forecast_detalle.php" : "mod_pagina/tpl/permiso_denegado.php";
        break;
    case 'editar_contactos_crm':$tpl = $usuario->validPermissionUser('ver_contactos_crm') && $usuario->validPermissionUser('update_contactos_crm') ? "mod_crm/tpl/editar_contactos.php" : "mod_pagina/tpl/permiso_denegado.php";
        break;

    case 'empresas_crm':$tpl = $usuario->validPermissionUser('ver_empresas_crm') ? "mod_crm/tpl/empresas_listado.php" : "mod_pagina/tpl/permiso_denegado.php";
        break;

    case 'editar_empresas_crm':$tpl = $usuario->validPermissionUser('ver_empresas_crm') && $usuario->validPermissionUser('update_empresas_crm') ? "mod_crm/tpl/editar_empresas.php" : "mod_pagina/tpl/permiso_denegado.php";
        break;

    //CONFIGURACION CRM
    case 'actividades_crm':$tpl = $usuario->validPermissionUser('ver_actividades_crm') ? "mod_crm_configuracion/tpl/actividades_listado.php" : "mod_pagina/tpl/permiso_denegado.php";
        break;

    case 'flujo_crm':$tpl = $usuario->validPermissionUser('ver_actividades_crm') ? "mod_crm_configuracion/tpl/flujo_listado.php" : "mod_pagina/tpl/permiso_denegado.php";
        break;

    case 'estado_crm':$tpl = $usuario->validPermissionUser('ver_estados_cotizaciones_crm') ? "mod_crm_configuracion/tpl/estados_cotizaciones_listado.php" : "mod_pagina/tpl/permiso_denegado.php";
        break;

    case 'bancos_detalles':$tpl = "mod_bancos/tpl/listado.tpl";
        break;
    case 'bancos_portada':$tpl = "mod_bancos/tpl/portada.tpl";
        break;

    case 'cinta_contador':$tpl = "mod_contador/tpl/index.php";
        break;
    case 'cinta_contador_resultado':$tpl = "mod_contador/tpl/cierre.php";
        break;

    case 'importar_excel_precios':$tpl = "LectorExcel/lector_excel.php";
        break;

    case 'ver_empleado_permisos':$tpl = "mod_usuarios/tpl/ver_empleado_permisos.php";
        break;

    //------------------------------------------
    //
    //   Sistema  clientes_educativa
    //
    //  case 'clientes_educativa'       :                $tpl="mod_educativo/tpl/ver_educativa.php"; break;

    ///-----------------------------------------------------------------------------
    //
    //    Administrador de Categoría
    //
    case 'categorias_clientes':$tpl = "mod_categorias/tpl/categorias_clientes.php";
        break;

    case 'productos_categorias':$tpl = "mod_categorias/productos/listado.php";
        break;
    case 'ver_productos_categorias':$tpl = "mod_categorias/productos/ver.php";
        break;
    case 'categoria_producto_nueva':$tpl = "mod_categorias/productos/ver.php";
        break;

    ///-----------------------------------------------------------------------------
    //
    //   estado cuenta
    //
    case 'estado_cuenta':$tpl = "mod_estadocuenta/tpl/estado.php";
        break;

    ///-----------------------------------------------------------------------------
    //
    //   Otros conceptos por Cobrar
    //

    case 'otros_conceptos_listado':$tpl = "mod_otros_conceptos/tpl/otros_conceptos_listado.php";
        break;

    case 'otros_conceptos_ver':$tpl = "mod_otros_conceptos/tpl/otros_conceptos_ver.php";
        break;
    case 'otros_conceptos_nuevo':$tpl = "mod_otros_conceptos/tpl/otros_conceptos_ver.php";
        break;

    case 'fisica':$tpl = "mod_toma_fisica/tpl/toma_datos.tpl";
        break;

    case 'informe_utilidades':$tpl = "mod_utilidad/index.php";
        break;

    case 'informe_compras':$tpl = "mod_informes/compras/index.php";
        break;

    case 'compras_por_empresa':$tpl = "mod_informes/compras_por_empresa/tpl/compras_por_empresa.php";
        break;

    //=======================================
    case 'capital_humano':$tpl = "mod_capital_humano/tpl/inicio.tpl";
        break;
    case 'capital_humano_listado_colaboradores':$tpl = "mod_capital_humano/tpl/colaboradores/listado.php";
        break;
    case 'ver_colaborador':$tpl = "mod_capital_humano/tpl/colaboradores/ver_colaborador.php";
        break;
    case 'nuevo_colaborador':$tpl = "mod_capital_humano/tpl/colaboradores/ver_colaborador.php";
        break;

    case 'puestos':$tpl = "mod_capital_humano/tpl/puestos/listado.php";
        break;
    case 'nuevo_puesto':$tpl = "mod_capital_humano/tpl/puestos/nuevo_puesto.php";
        break;
    case 'ver_puesto':$tpl = "mod_capital_humano/tpl/puestos/nuevo_puesto.php";
        break;

    case 'listado_otros_rubros':$tpl = "mod_capital_humano/tpl/otros/listado.php";
        break;
    case 'listado_otros_rubros':$tpl = "mod_capital_humano/tpl/otros/listado.php";
        break;
    case 'nuevo_otro_rubro':$tpl = "mod_capital_humano/tpl/otros/nuevo_rubro.php";
        break;
    case 'editar_otro_rubro':$tpl = "mod_capital_humano/tpl/otros/nuevo_rubro.php";
        break;
    case 'ver_otro_rubro':$tpl = "mod_capital_humano/tpl/otros/nuevo_rubro.php";
        break;

    case 'capital_humano_listado_planillas':$tpl = "mod_capital_humano/tpl/planillas/listado.php";
        break;
    case 'nueva_planilla':$tpl = "mod_capital_humano/tpl/planillas/ver_planilla.php";
        break;
    case 'ver_planilla':$tpl = "mod_capital_humano/tpl/planillas/ver_planilla.php";
        break;

    //-----------------------------------------Contador Clientes
    case 'vendedor_listado':$tpl = "mod_vendedor/listador_clientes.tpl";
        break;
    case 'nuevo_cliente':$tpl = "mod_vendedor/nuevo_cliente.php";
        break;
    case 'editor_financiero':$tpl = "mod_vendedor/nuevo_cliente_configuracion.tpl";
        break;

























    
    
    
    
    
    
    //---------------------------------------------------------
    //
    //   Pongamos Aqui las RUTAS que si utilizamos, la idea es no traer secciones de codigo Basura
    //   Luego eliminamos lo que este en esta seccion hacia arriba!
    //
    //---------------------------------------------------------

        /// por favor seamos lo mas ordenador posibles
        /// Agrupemos por modulos desarrollados

        /*****************************************************
         * 
         *  🚀 Facturacion Electronica 
         *  
         * 
         *****************************************************/
        case 'ver_factura':             $tpl =  "mod_facturacion_europa/tpl/ver_factura.php";   break;
        case 'nueva_factura':           $tpl =  "mod_facturacion_europa/tpl/ver_factura.php";   break;
        case 'factura_listado':           $tpl =  "mod_facturacion_europa/tpl/factura_listado.php";   break;
        case 'validar_factura'  :       $tpl =  "mod_facturacion_europa/tpl/ver_factura.php";   break;



        case 'agentes_listado':      $tpl  = "mod_configuracion_agente/tpl/agentes_listado.php";  break;
        case 'agentes_nuevo':      $tpl  = "mod_configuracion_agente/tpl/agentes_editar.php";  break;
        case 'agentes_editar':      $tpl  = "mod_configuracion_agente/tpl/agentes_editar.php";  break;

        case 'validar_cotizacion'  :    $tpl   =   "mod_cotizaciones/tpl/ver_cotizacion.php";  break;
        
        case 'nuevo_albaran'    :     $tpl  =   "mod_albaranes/tpl/ver_albaran.php"; break;
        case 'ver_albaran'      :    $tpl   =   "mod_albaranes/tpl/ver_albaran.php";  break;
        case 'validar_albaran'  :    $tpl   =   "mod_albaranes/tpl/ver_albaran.php";  break;
        case 'productos_listado':    $tpl   =   "mod_productos/tpl/productos_listado.php";  break;
        case 'productos_editar' :    $tpl   =   "mod_productos/tpl/productos_editar.php";  break;
        case 'productos_movimientos' :    $tpl   =   "mod_productos/tpl/productos_movimientos.php";  break;
        case 'factura_listado2'  :    $tpl   =   "mod_facturacion/tpl/factura_listado.php"; break;
        case 'albaranes_listado':    $tpl   =   "mod_albaranes/tpl/albaranes_listado.php"; break;
        case 'funnel_listado'   :    $tpl   =   "mod_funnel/tpl/funnel_listado.php"; break;
 
        case 'funnel_editar'    :    $tpl   =   "mod_funnel/tpl/funnel_editar.php";  break;
        case 'contactos_crm_listado' :  $tpl   =   "mod_contactos_crm/tpl/contactos_crm_listado.php";  break;
        case 'contactos_crm_editar' :  $tpl   =   "mod_contactos_crm/tpl/contactos_crm_editar.php";  break;
        case 'contactos_crm_nuevo' :  $tpl   =   "mod_contactos_crm/tpl/contactos_crm_editar.php";  break;
        case 'lead_gestion'        :  $tpl   =   "mod_lead/tpl/lead_gestion.php";  break;
        case 'quickbooks'        :  $tpl   =   "mod_quickbooks/tpl/configuracion_quickbooks.php";  break;



        case 'mi_perfil':
            $tpl = "mod_perfiles/tpl/ver_perfil.php";
        break; 



        /*****************************************************
         * 
         *  🚀 CRM 
         *  Listado de URL Depuradas  CISMA
         * 
         *****************************************************/
        /*case 'cisma_cotizaciones'                       :  $tpl   =   "mod_cisma_cotizaciones/tpl/listado.php";            break;
        case 'cisma_cotizaciones_detalle'               :  $tpl   =   "mod_cisma_cotizaciones/tpl/ver_cotizacion.php";   break;
        case 'cisma_cotizaciones_detalle_modificar'     :  $tpl   =   "mod_cisma_cotizaciones/tpl/editar_cotizacion.php";   break;
       
        case 'cisma_atestados'                       :  $tpl   =   "mod_cisma_configuracion/tpl/atestados_listado.php";  break;           break;
       
        case 'cisma_anexos_pdf'                       :  $tpl   =   "mod_cisma_configuracion/tpl/anexos_pdf.php";  break;          
         break;

        case 'cisma_categorias_cotizaciones':
            $tpl = "mod_cisma_configuracion/tpl/categorias_listado.php";
        break;  

        case 'cisma_diccionario_actividades':
            $tpl = "mod_cisma_configuracion/tpl/diccionario_actividades_listado.php";
        break;    

        case 'cisma_cotizaciones_detalle_terminos':
            $tpl = "mod_cisma_cotizaciones/tpl/edit_cotizaciones_detalle_terminos.php";
        break; */


        
        
        
        /** Red house Modulos propios  */  
        
        case 'redhouse_cotizaciones'                       :  $tpl   =   "mod_redhouse_cotizaciones/tpl/listado.php";            break;
        case 'redhouse_cotizaciones_detalle'               :  $tpl   =   "mod_redhouse_cotizaciones/tpl/ver_cotizacion.php";   break;
        case 'redhouse_cotizaciones_detalle_modificar'     :  $tpl   =   "mod_redhouse_cotizaciones/tpl/editar_cotizacion.php";   break;
       
            
        /*Red house modulo de Proyecto*/
        case 'redhouse_proyectos'                       :  $tpl   =   "mod_redhouse_proyecto/tpl/listado.php";            break;
        case 'redhouse_proyecto_detalle'                       :  $tpl   =   "mod_redhouse_proyecto/tpl/ver_proyecto.php";            break;
        

        /*Redhouse Ordenes de compra*/
        case 'redhouse_ordenes_compra'                       :  $tpl   =   "mod_redhouse_ordenes_compra/tpl/listado.php";            break;
        case 'redhouse_ordenes_detalle'                       :  $tpl   =   "mod_redhouse_ordenes_compra/tpl/editar_orden.php";            break;
        case 'redhouse_ordenes_listado'                         : $tpl   =   "mod_redhouse_ordenes_compra/tpl/listado.php";            break;

 
        /*****************************************************
         * 
         *  🚀 CRM 
         *  Listado de URL Depuradas
         * 
         *****************************************************/
        
        case 'ver_oportunidad'      :        $tpl   =   "mod_crm/tpl/ver_oportunidad.php";                  break;
        case 'modificar_oportunidad':        $tpl   =   "mod_crm/tpl/editar_oportunidad.php";               break;
        case 'mis_pendientes'       :        $tpl   =   "mod_crm_actividades/tpl/listado_actividades.php";  break;
        case 'nueva_oportunidad'    :        $tpl   =   "mod_crm/tpl/editar_oportunidad.php";               break;
        case 'agenda'               :        $tpl   =   "mod_crm_agenda/tpl/agenda.php";                    break; 
        case 'oportunidades'        :        $tpl   =   "mod_crm/tpl/listado_oportunidad.php";              break;
        case 'categoria_crm_categorias'        :    $tpl   =   "mod_crm_categorias/tpl/listado_crm_categorias.php";              break; 

        case 'crm_actividades': 
            $tpl   =   "mod_crm_actividades/tpl/listado_actividades_generales.php";
        break;


        case 'empresa': 
            $tpl   =   "mod_empresa/tpl/ver_empresa.php";
        break;

        case 'empresa_listado':
            $tpl   =   "mod_empresa/tpl/empresa_listado.php";
        break;

        case 'perfiles_listado': 
            $tpl   =   "mod_perfiles/tpl/listado_perfiles.php";
        break;

        case 'editar_perfil':
            $tpl   =   "mod_perfiles/tpl/editar_perfil.php";
        break;


        /*Bancos*/
        case 'bancos_listado': 
            $tpl   =   "mod_bancos/tpl/listado_bancos.php";
        break;

        case 'editar_banco':
            $tpl   =   "mod_bancos/tpl/editar_banco.php";
        break;

          /*Prioridades*/
          case 'prioridades_listado': 
            $tpl   =   "mod_diccionario_prioridades/tpl/listado_prioridades.php";
        break;

        case 'listas_precios': 
            $tpl   =   "mod_diccionario_listas_precios/tpl/listado_diccionario_lista_precios.php";
        break;


            /*Monedas*/
            case 'monedas_listado': 
                $tpl   =   "mod_diccionario_moneda/tpl/listado_monedas.php";
            break;

    
        
        
        /*Categorias*/
        case 'categorias_producto_listado': 
            $tpl   =   "mod_diccionario_categoria/tpl/listado_categorias_producto.php";
        break;

        case 'editar_producto_categoria':
            $tpl   =   "mod_categorias/tpl/editar_categoria.php";
        break;

        //Catalogo 

        case 'catalogo_listado':
            $tpl = "mod_catalogo/tpl/catalogo_listado.php";
        break;

        //agente_rutas
        case 'agente_rutas':
            $tpl = "mod_agente_rutas/tpl/listado_agente_rutas.php";
        break;
        //Rutas
        case 'rutas_listado'        :    $tpl   =   "mod_rutas/tpl/listado_rutas.php";              break; 

        
        
        
        
        case 'configuracion_series':$tpl = "mod_facturacion_europa_series/tpl/listado_series.php";      break;


        /* 20241030: Ivan Tapia */
        case 'direcciones': $tpl = "mod_direcciones/tpl/listado_direcciones.php"; break;


        case 'medios_pago':$tpl = "mod_medios_pago/tpl/listado_medios_pago.php";
        break;


        case 'configuracion_parametros':$tpl = "mod_parametros/tpl/listado_parametros.php";
        break;

        case 'medios_pago':$tpl = "mod_medios_pago/tpl/listado_medios_pago.php";
        break;

        case 'reportes_iva':$tpl = "mod_iva/tpl/reporte_iva.php";
        break;

        case 'citas_agenda':$tpl = "mod_citas_agenda/tpl/agenda.php";
        break;


        
        /*****************************************************
         * 
         *  🚀 Cumplimiento del KIT DIGITAL
         *  Listado de URL Depuradas para Kit Digital Juan Carlos y David B 27 Diciembre 2024
         * 
         *****************************************************/
     
         case 'Licencias':$tpl = "mod_licencias/tpl/ver.php";
         break;


         case 'listado_errores':$tpl = "mod_errores/tpl/listado_errores.php";
         break;



    default:    
    $tpl = "mod_tpl/tpl/plantilla.php";
        break;







}

}

 
