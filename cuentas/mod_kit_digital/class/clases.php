<?php

if (!empty($_POST['action'])) :
    session_start();
    include_once("../../conf/conf.php");
    require_once ENLACE_SERVIDOR . "mod_empresa/object/empresa.object.php";
    require_once ENLACE_SERVIDOR . 'mod_utilidad/object/utilidades.object.php';
    require_once ENLACE_SERVIDOR_CUENTAS.'mod_kit_digital/object/kit_digital.object.php';
    
    switch ($_POST['action']):

        // Create company (example)
        // Create company
        case 'crearEmpresa':
            $Empresa = new kit_digital($dbh);

            // Set properties for Empresa object from POST data, including the new fields 'tipo' and 'cedula'
            $Empresa->nombre = !empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : null;
            $Empresa->nombre_comercial = !empty($_REQUEST['nombre_comercial']) ? $_REQUEST['nombre_comercial'] : null;
            $Empresa->direccion_fk_provincia = !empty($_REQUEST['direccion_fk_provincia']) ? $_REQUEST['direccion_fk_provincia'] : null;
            $Empresa->direccion_fk_municipio = !empty($_REQUEST['direccion_fk_municipio']) ? $_REQUEST['direccion_fk_municipio'] : null;
            $Empresa->telefono_fijo = !empty($_REQUEST['telefono_fijo']) ? $_REQUEST['telefono_fijo'] : null;
            $Empresa->telefono_movil = !empty($_REQUEST['telefono_movil']) ? $_REQUEST['telefono_movil'] : null;
            $Empresa->website = !empty($_REQUEST['website']) ? $_REQUEST['website'] : null;
            $Empresa->fk_estado = 1;
            $Empresa->creado_fk_usuario = $_SESSION['user_id'];
            $Empresa->kit_aplica_kit_digital = !empty($_REQUEST['kit_aplica_kit_digital']) ? $_REQUEST['kit_aplica_kit_digital'] : null;
            $Empresa->kit_fk_tipo = !empty($_REQUEST['kit_fk_tipo']) ? $_REQUEST['kit_fk_tipo'] : null;
            $Empresa->kit_pdf_firmado = !empty($_REQUEST['kit_pdf_firmado']) ? $_REQUEST['kit_pdf_firmado'] : null;
            $Empresa->kit_pdf_firmado_url_en_disco = !empty($_REQUEST['kit_pdf_firmado_url_en_disco']) ? $_REQUEST['kit_pdf_firmado_url_en_disco'] : null;
            $Empresa->kit_direccion_completa = !empty($_REQUEST['kit_direccion_completa']) ? $_REQUEST['kit_direccion_completa'] : null;
            $Empresa->kit_codigo_postal = !empty($_REQUEST['kit_codigo_postal']) ? $_REQUEST['kit_codigo_postal'] : null;
            $Empresa->vendedor_fk_usuario = !empty($_REQUEST['vendedor_fk_usuario']) ? $_REQUEST['vendedor_fk_usuario'] : null;
            $Empresa->notas_empresa = !empty($_REQUEST['notas_empresa']) ? $_REQUEST['notas_empresa'] : null;
          

            // New fields for 'tipo' and 'cedula'
            $Empresa->tipo = !empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : null;
            $Empresa->cedula = !empty($_REQUEST['cedula']) ? $_REQUEST['cedula'] : null;

            // Call the insertarEmpresa method to insert data
            $resultado = $Empresa->insertarEmpresa();

            // Return the result in JSON format
            echo json_encode($resultado);
            break;

        case 'actualizarEmpresa':
            $Empresa = new kit_digital($dbh);

            // Set properties for Empresa object from POST data, including the new fields 'tipo' and 'cedula'
            $Empresa->id = $_REQUEST['fiche'];
            $Empresa->nombre = !empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : null;
            $Empresa->nombre_comercial = !empty($_REQUEST['nombre_comercial']) ? $_REQUEST['nombre_comercial'] : null;
            $Empresa->direccion_fk_provincia = !empty($_REQUEST['direccion_fk_provincia']) ? $_REQUEST['direccion_fk_provincia'] : null;
            $Empresa->direccion_fk_municipio = !empty($_REQUEST['direccion_fk_municipio']) ? $_REQUEST['direccion_fk_municipio'] : null;
            $Empresa->telefono_fijo = !empty($_REQUEST['telefono_fijo']) ? $_REQUEST['telefono_fijo'] : null;
            $Empresa->telefono_movil = !empty($_REQUEST['telefono_movil']) ? $_REQUEST['telefono_movil'] : null;
            $Empresa->website = !empty($_REQUEST['website']) ? $_REQUEST['website'] : null;
            $Empresa->fk_estado = !empty($_REQUEST['estado_empresa']) ? $_REQUEST['estado_empresa'] : null;
            $Empresa->kit_aplica_kit_digital = !empty($_REQUEST['kit_aplica_kit_digital']) ? $_REQUEST['kit_aplica_kit_digital'] : null;
            $Empresa->kit_fk_tipo = !empty($_REQUEST['kit_fk_tipo']) ? $_REQUEST['kit_fk_tipo'] : null;
            $Empresa->kit_pdf_firmado = !empty($_REQUEST['kit_pdf_firmado']) ? $_REQUEST['kit_pdf_firmado'] : null;
            $Empresa->kit_pdf_firmado_url_en_disco = !empty($_REQUEST['kit_pdf_firmado_url_en_disco']) ? $_REQUEST['kit_pdf_firmado_url_en_disco'] : null;
            $Empresa->kit_direccion_completa = !empty($_REQUEST['kit_direccion_completa']) ? $_REQUEST['kit_direccion_completa'] : null;
            $Empresa->kit_codigo_postal = !empty($_REQUEST['kit_codigo_postal']) ? $_REQUEST['kit_codigo_postal'] : null;
            $Empresa->vendedor_fk_usuario = !empty($_REQUEST['vendedor_fk_usuario']) ? $_REQUEST['vendedor_fk_usuario'] : null;
            $Empresa->notas_empresa = !empty($_REQUEST['notas_empresa']) ? $_REQUEST['notas_empresa'] : null;
            

            // New fields for 'tipo' and 'cedula'
            $Empresa->tipo = !empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : null;
            $Empresa->cedula = !empty($_REQUEST['cedula']) ? $_REQUEST['cedula'] : null;

            // Call the actualizarEmpresa method to update data
            $resultado = $Empresa->actualizarEmpresa();

            // Return the result in JSON format
            echo json_encode($resultado);
            break;


        case 'guardarKitDigital':
            $Empresa = new kit_digital($dbh);
        
            // Set empresa_id from 'fiche' in POST data
            $empresa_id = isset($_POST['fiche']) ? $_POST['fiche'] : null;
            $Empresa->rowid = $empresa_id;
        
            // Set properties for the Empresa object from POST data
            $Empresa->kit_aplica_kit_digital = !empty($_POST['kit_aplica_kit_digital']) ? $_POST['kit_aplica_kit_digital'] : 0;
            $Empresa->kit_fk_tipo = !empty($_POST['kit_fk_tipo']) ? $_POST['kit_fk_tipo'] : null;
            $Empresa->kit_pdf_firmado = !empty($_POST['kit_pdf_firmado']) ? $_POST['kit_pdf_firmado'] : 0;
            $Empresa->kit_monto_aprobado = !empty($_POST['kit_monto_aprobado']) ? $_POST['kit_monto_aprobado'] : 0;
            $Empresa->fk_kit_digital_estado = !empty($_POST['fk_kit_digital_estado']) ? $_POST['fk_kit_digital_estado'] : 0;

            // Handle file upload for PDF if provided
            if (!empty($_FILES['kit_pdf_firmado_url_en_disco']['name'])) {
                // Define the upload directory path based on empresa_id
                $uploadDir = '../../../files_empresas/kit_digital/empresa_' . $empresa_id . '/';
        
                // Ensure the directory exists, create if not
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
        
                // Set the target file path
                $fileName = basename($_FILES['kit_pdf_firmado_url_en_disco']['name']);
                $targetFilePath = $uploadDir . $fileName;
        
                // Move file to the specified directory
                if (move_uploaded_file($_FILES['kit_pdf_firmado_url_en_disco']['tmp_name'], $targetFilePath)) {
                    $Empresa->kit_pdf_firmado_url_en_disco = $fileName; // Store only the filename
                } else {
                    echo json_encode([
                        'exito' => 0,
                        'mensaje' => 'Error al subir el archivo PDF. Intente nuevamente.'
                    ]);
                    exit;
                }
            }
            // Always update the existing record
            $resultado = $Empresa->actualizarKitDigital();
        
            // Return the result in JSON format
            echo json_encode($resultado);
        break;
        


        case 'guardarComisiones':
            $Empresa = new kit_digital($dbh);
        
            // Set empresa_id from 'fiche' in POST data
            $empresa_id = isset($_POST['fiche']) ? $_POST['fiche'] : null;
            $Empresa->rowid = $empresa_id;
        
            // Set properties for the Empresa object from POST data
            $Empresa->kit_monto_comision = !empty($_POST['kit_monto_comision']) ? $_POST['kit_monto_comision'] : null;
            $Empresa->kit_monto_comision_pagada = !empty($_POST['kit_monto_comision_pagada']) ? $_POST['kit_monto_comision_pagada'] : null;
            $Empresa->kit_factura_emitida = !empty($_POST['kit_factura_emitida']) ? $_POST['kit_factura_emitida'] : 0;
            $Empresa->kit_factura_emitida_fecha = !empty($_POST['kit_factura_emitida_fecha']) ? $_POST['kit_factura_emitida_fecha'] : null;
            $Empresa->kit_factura_emitida_pagada = !empty($_POST['kit_factura_emitida_pagada']) ? $_POST['kit_factura_emitida_pagada'] : 0;
        
            // Update the record
            $resultado = $Empresa->actualizarComisiones();
        
            // Return the result in JSON format
            echo json_encode($resultado);
        break;
        


        // Retrieve autonomous communities based on selected country
        case 'BuscarComunidadesAutonomas':
            $Utilidades = new Utilidades($dbh);
            $fk_pais = $_REQUEST['fk_pais'];
            $comunidadesAutonomas = $Utilidades->obtener_comunidades_autonomas($fk_pais);
            $html = '<option value="">Seleccionar Población</option>';
            foreach ($comunidadesAutonomas as $comunidad) {
                $html .= '<option value="' . $comunidad->id . '">' . $comunidad->nombre . '</option>';
            }
            echo $html;
            break;

        // Retrieve provinces based on selected autonomous community
        case 'BuscarProvincias':
            $Utilidades = new Utilidades($dbh);
            $fk_comunidad_autonoma = $_REQUEST['fk_comunidad_autonoma'];
            $provincias = $Utilidades->obtener_provincias($fk_comunidad_autonoma);
            $html = '<option value="">Seleccionar Población</option>';
            foreach ($provincias as $provincia) {
                $html .= '<option value="' . $provincia->id . '">' . $provincia->provincia . '</option>';
            }
            echo $html;
            break;

        // Retrieve municipalities based on selected province
        case 'BuscarMunicipios':
            $Utilidades = new Utilidades($dbh);
            $fk_provincia = $_REQUEST['fk_provincia'];
            $municipios = $Utilidades->obtener_municipios($fk_provincia);
            $html = '<option value="">Seleccionar Municipios</option>';
            foreach ($municipios as $municipio) {
                $html .= '<option value="' . $municipio->id . '">' . $municipio->municipio . '</option>';
            }
            echo $html;
            break;

        // Update company tax settings (example)
        /*case 'ActualizarEmpresaImpuestos':
            $Empresa = new empresa($dbh);
            $Empresa->retencion = $_REQUEST['retencionvalor'];
            $Empresa->retencion_porcentaje = $_REQUEST['retencion_porcentaje'];
            $Empresa->id = $_SESSION['Entidad'];
            $resultado = $Empresa->ActualizarConfiguraciónEmpresa();
            echo json_encode($resultado);
            break;*/

    endswitch;
endif;
?>
