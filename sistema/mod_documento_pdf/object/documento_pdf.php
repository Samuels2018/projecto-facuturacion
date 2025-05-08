<?php

require_once(ENLACE_SERVIDOR . "mod_entidad/object/Entidad.object.php");

class documento_pdf extends  Seguridad
{
    public $entidad;
    public $objDocumento;
    public $objExtra; // Documentos Extra
    public $db;


    public function __construct($db, $entidad = 1)
    {
        parent::__construct($db, $entidad);
        $this->entidad          = $entidad;
        $this->db               = $db;
        
        require_once(ENLACE_SERVIDOR . "mod_campos_extra_formularios/object/campos.extra.object.php");
        $this->objExtra         = new Extra($db, $entidad);


    }

    public function genera_pdf($param_output = "D", $nombre_documento = "factura", $fk_plantilla = 0)
    {
        include ENLACE_SERVIDOR . "include/mpdf/vendor/autoload.php";
        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4', // Puedes cambiar a 'Letter' si prefieres el formato Carta
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'margin_header' => 10,
            'margin_footer' => 10
        ]);

        $Entidad = new Entidad($this->db, $this->entidad);
        
        $main_file_path_html = ENLACE_SERVIDOR . "mod_documento_pdf/templates/entity_plantilla.html";
        $main_file_path_css = ENLACE_SERVIDOR . "mod_documento_pdf/templates/entity_plantilla.css";
        $entity_file_path_html = '';
        $entity_file_path_css = '';

        if ($fk_plantilla > 0) {
            require_once(ENLACE_SERVIDOR . "mod_documento_pdf/object/plantilla.object.php");
            $plantillaObject = new Plantilla($this->db, $this->entidad);
            $plantillaObject->fetch($fk_plantilla);

            $entity_file_path_html = html_entity_decode($plantillaObject->plantilla_html);
            $entity_file_path_css = html_entity_decode($plantillaObject->plantilla_css);
        }

        if ($entity_file_path_html == '') {
            $html_string = file_get_contents($main_file_path_html);
        } else {
            $html_string = $entity_file_path_html;
        }
        if ($entity_file_path_css == '') {
            $css_string = file_get_contents($main_file_path_css);
        } else {
            $css_string = $entity_file_path_css;
        }

        $mpdf->WriteHTML('<style>' . $css_string . '</style>');

        // $mpdf->SetFooter('<p style="font-family:arial;font-size:10px;text-align:center;">' . date("Y") . ' © Factuguay</p>');
        $mpdf->SetFooter('<table style="width: 100%;">
            <tr>
                <td style="text-align: left; font-family: Arial; font-size: 10px;">Página {PAGENO} de {nbpg}</td>
                <td style="text-align: center; font-family: Arial; font-size: 10px;">© Factuguay</td>
                <td style="text-align: right; font-family: Arial; font-size: 10px;">' . date("d-m-Y") . '</td>
            </tr>
        </table>');

        if (strtolower($this->objDocumento->nombre_clase) == 'factura') {
            $imagen = $this->objDocumento->QR();
            $html_string = str_replace("{{qr}}", $imagen, $html_string);
        } else {
            $html_string = str_replace("{{qr}}", '', $html_string);
        }

        /* Obtengo el detalle del documento y Actualizo el fk_plantilla del Documento si la tuviera*/
        if ($fk_plantilla > 0) {
            $this->objDocumento->actualizar_plantilla($fk_plantilla);
        }
        $sql =
            "SELECT documento.*, documento.label, documento.cantidad, documento.subtotal, p.ref
        FROM " . $this->objDocumento->documento . "_detalle documento
        LEFT JOIN fi_productos p ON p.rowid = documento.fk_producto
        WHERE documento.fk_documento  = :documento_id
        GROUP BY documento.rowid;
        ";
        $db_detalle = $this->db->prepare($sql);
        $db_detalle->bindValue(':documento_id', $this->objDocumento->id, PDO::PARAM_INT);
        $db_detalle->execute();
        /* Obtengo el detalle del documento */
        $tr_detalle = '';
        $tr_detalle_afaderibes_con_subvencion="";

        $data = $db_detalle->fetchAll();
        
        if (count($data) > 0) {

            for ($i = 0; $i < count($data); $i++) {
                $obj = $data[$i];
                $iterador++; // contador de Lineas Grafico
    
                $tr_detalle_equivalencia = '';
                $tr_detalle_retencion = '';
                $tr_detalle_descuento = '';

                if ($obj['impuesto_iva_equivalencia_monto'] > 0) {
                    $tr_detalle_equivalencia = '<br/> <b>RE</b>: ' . numero_simple($obj['impuesto_iva_equivalencia_monto']);
                    $tr_detalle_equivalencia .= '<span class="font-size-menor">(' . numero_simple($obj['impuesto_iva_equivalencia_porcentaje']) . ' %)</span>';
                }
                if ($obj['impuesto_retencion_monto'] > 0) {
                    $tr_detalle_retencion = "<br/> <b>RET</b>: " . numero_simple($obj['impuesto_retencion_monto']);
                    $tr_detalle_retencion .= "<span class='font-size-menor'>( " . numero_simple($obj["impuesto_retencion_porcentaje"]) . " %)</span>";
                }
                if ($obj['descuento_valor_final'] > 0) {
                    $tr_detalle_descuento = numero_simple($obj['descuento_valor_final']);
                    $tr_detalle_descuento_porcentaje = ($obj["descuento_tipo"] == 'porcentual' ? '%' : '');
                    $tr_detalle_descuento .= "<span class='font-size-menor'>( " . numero_simple($obj["descuento_aplicado"]) . " " . $tr_detalle_descuento_porcentaje . ")</span>";
                }
    
                $tr_detalle .= "
                    <tr >
                    <td >" . numero_simple($obj['cantidad']) . " </td>
                    <td >" . ($obj['label']) . " </td>
                    <td >" . numero_simple($obj['precio_original']) . " </td>
                    <td >" . $tr_detalle_descuento . " </td>
                    <td ><b>IVA</b>:" . numero_simple($obj['impuesto_iva_monto']) . " <span class='font-size-menor'>(" . numero_simple($obj['impuesto_iva_porcentaje']) . "%)</span>" . $tr_detalle_equivalencia . $tr_detalle_retencion . "</td>                  
                    <td style='text-align:right'>" . numero_simple($obj['subtotal_pre_retencion']) . " </td>
                    </tr> ";


                $tr_detalle_afaderibes_con_subvencion.="
                    <tr >
                    <td >" . numero_simple($obj['cantidad']) . " </td>
                    <td >" . ($obj['label']) . ( ($obj['impuesto_iva_monto']> 0) ? " * <small>Linea con IVA</small>"  : '' ) . " </td>
                    <td >" . numero_simple($obj['precio_original']) . " </td>
                    <td class='afaderibes_con_subvencion' >" .  (($obj["descuento_tipo"] == 'porcentual') ? (100 - $obj["descuento_aplicado"])."%"  :  numero_simple($obj['precio_original'] - $obj["descuento_aplicado"]) ) . " </td>
                    <td style='text-align:right'>" . numero_simple($obj['subtotal_pre_retencion']) . " </td>
                    </tr> ";
            }
        }

        if(count($data) > 0){
            
            /* Armado de Campos Extras para Detalle Items */
            $tr_detalle_personalizado = '';
            $iterador = 0;

            for ($i = 0; $i < count($data); $i++) {
                $obj = $data[$i];
                
                $iterador++; // contador de Lineas Grafico
                $tr_detalle_equivalencia = '';
                $tr_detalle_retencion = '';
                $tr_detalle_descuento = '';
                $tr_detalle_ivamonto = '';
                if ($obj['impuesto_iva_equivalencia_monto'] > 0) {
                    $tr_detalle_equivalencia = '<br/> <b>RE</b>: ' . numero_simple($obj['impuesto_iva_equivalencia_monto']);
                    $tr_detalle_equivalencia .= '<span class="font-size-menor">(' . numero_simple($obj['impuesto_iva_equivalencia_porcentaje']) . ' %)</span>';
                }
                if ($obj['impuesto_retencion_monto'] > 0) {
                    $tr_detalle_retencion = "<br/> <b>RET</b>: " . numero_simple($obj['impuesto_retencion_monto']);
                    $tr_detalle_retencion .= "<span class='font-size-menor'>( " . numero_simple($obj["impuesto_retencion_porcentaje"]) . " %)</span>";
                }
                if ($obj['descuento_valor_final'] > 0) {
                    $tr_detalle_descuento = numero_simple($obj['descuento_valor_final']);
                    $tr_detalle_descuento_porcentaje = ($obj["descuento_tipo"] == 'porcentual' ? '%' : '');
                    $tr_detalle_descuento .= "<span class='font-size-menor'>( " . numero_simple($obj["descuento_aplicado"]) . " " . $tr_detalle_descuento_porcentaje . ")</span>";
                }
                if ($obj['impuesto_iva_monto'] > 0) {
                    $tr_detalle_ivamonto = "<b>IVA</b>: " . numero_simple($obj['impuesto_iva_monto']);
                    $tr_detalle_ivamonto .= "<span class='font-size-menor'>( " . numero_simple($obj["impuesto_iva_porcentaje"]) . " %)</span>";
                }

                if(strpos($html_string, '{{documento_detalle_cantidad}}') !== false){
                    $tr_detalle_cantidad = '<td class="detalle_cantidad">' . numero_simple($obj['cantidad']) . ' </td>';
                }
                if(strpos($html_string, '{{documento_detalle_nombre}}') !== false){
                    $tr_detalle_nombre = '<td  class="detalle_nombre">' . $obj['label'] . ' </td>';
                }
                if(strpos($html_string, '{{documento_detalle_precio}}') !== false){
                    $tr_detalle_precio = '<td  class="detalle_precio">' . numero_simple($obj['precio_original']) . ' </td>';
                }
                if(strpos($html_string, '{{documento_detalle_descuento}}') !== false){
                    $tr_detalle_descuento = '<td  class="detalle_descuento">' . $tr_detalle_descuento . ' </td>';
                }
                if(strpos($html_string, '{{documento_detalle_impuestos}}') !== false){
                    $tr_detalle_impuestos = '<td  class="detalle_impuestos">' . $tr_detalle_ivamonto . $tr_detalle_equivalencia . $tr_detalle_retencion . '</td>';
                }
                if(strpos($html_string, '{{documento_detalle_retencion}}') !== false){
                    $tr_detalle_retencion = '<td  class="detalle_retencion">' . numero_simple($obj['subtotal_pre_retencion']) . ' </td>';
                }

                if(strlen($tr_detalle_cantidad.$tr_detalle_nombre.$tr_detalle_precio.$tr_detalle_descuento.$tr_detalle_impuestos.$tr_detalle_retencion)>0){
                    $tr_detalle_personalizado .= '<tr>'.$tr_detalle_cantidad.$tr_detalle_nombre.$tr_detalle_precio.$tr_detalle_descuento.$tr_detalle_impuestos.$tr_detalle_retencion.'</tr>';
                }
                $tr_detalle_cantidad = '';
                $tr_detalle_nombre='';
                $tr_detalle_precio='';
                $tr_detalle_descuento='';
                $tr_detalle_impuestos='';
                $tr_detalle_retencion='';
            }
            if(strlen($tr_detalle_personalizado)>0){
                $tr_detalle_personalizado = '<table class="detalle_personalizado">'.$tr_detalle_personalizado.'</table>';
            }
            $html_string = str_replace("{{documento_detalle_cantidad}}", '', $html_string);
            $html_string = str_replace("{{documento_detalle_nombre}}", '', $html_string);
            $html_string = str_replace("{{documento_detalle_precio}}", '', $html_string);
            $html_string = str_replace("{{documento_detalle_descuento}}", '', $html_string);
            $html_string = str_replace("{{documento_detalle_impuestos}}", '', $html_string);
            $html_string = str_replace("{{documento_detalle_retencion}}", '', $html_string);
            /* Armado de Campos Extras para Detalle Items */
        }


        $html_string = str_replace("{{documento_proyecto}}", $this->objDocumento->proyecto_referencia.' - '.$this->objDocumento->proyecto_nombre, $html_string);
        $html_string = str_replace("{{documento_logo_formato}}",$Entidad->obtener_url_avatar_pdf($this->entidad), $html_string);
        $html_string = str_replace("{{documento_tipo}}", $this->objDocumento->documento_txt['singular'], $html_string);
        $html_string = str_replace("{{documento_numero}}", $this->objDocumento->referencia, $html_string);
        $html_string = str_replace("{{documento_fecha}}", obtenerFechaEnLetra($this->objDocumento->fecha), $html_string);

        $html_string = str_replace("{{empresa_razonsocial}}", $this->objDocumento->entidad_razonsocial, $html_string);

        $html_string = str_replace("{{empresa_nombre_comercial}}", $this->objDocumento->entidad_fantasia, $html_string);
        $html_string = str_replace("{{empresa_nif}}", $this->objDocumento->entidad_identificacion, $html_string);
        $html_string = str_replace("{{empresa_direccion}}", $this->objDocumento->entidad_direccion, $html_string);
        $html_string = str_replace("{{empresa_correo}}", $this->objDocumento->entidad_email, $html_string);
        $html_string = str_replace("{{empresa_telefono}}", $this->objDocumento->entidad_telefonofijo, $html_string);

        $html_string = str_replace("{{cliente_nombre}}", $this->objDocumento->fk_tercero_txt, $html_string);
        $html_string = str_replace("{{cliente_nif}}", $this->objDocumento->fk_tercero_identificacion, $html_string);
        $html_string = str_replace("{{cliente_telefono}}", $this->objDocumento->fk_tercero_telefono, $html_string);
        $html_string = str_replace("{{cliente_email}}", $this->objDocumento->fk_tercero_email, $html_string);
        $html_string = str_replace("{{cliente_direccion}}", $this->objDocumento->fk_tercero_direccion, $html_string);

        $html_string = str_replace("{{documento_vencimiento}}", date("d-m-Y", strtotime($this->objDocumento->fecha_vencimiento)), $html_string);
        $html_string = str_replace("{{documento_formapago}}", $this->objDocumento->forma_pago_txt, $html_string);

        $html_string = str_replace("{{documento_subtotal_pre_retencion}}", $this->objDocumento->subtotal_pre_retencion, $html_string);
        $html_string = str_replace("{{documento_iva21}}", $this->objDocumento->IVA_21, $html_string);
        $html_string = str_replace("{{documento_iva10}}", $this->objDocumento->IVA_10, $html_string);
        $html_string = str_replace("{{documento_iva4}}", $this->objDocumento->IVA_4, $html_string);
        $html_string = str_replace("{{documento_impuesto_retencion_irpf}}", $this->objDocumento->impuesto_retencion_irpf, $html_string);
        $html_string = str_replace("{{documento_total}}", '€ ' . $this->objDocumento->total, $html_string);

        $html_string = str_replace("{{documento_detalle}}", $tr_detalle, $html_string);
        $html_string = str_replace("{{documento_detalle_afaderibes_con_subvencion}}", $tr_detalle_afaderibes_con_subvencion, $html_string);

        $html_string = str_replace("{{documento_detalle_personalizado}}", $tr_detalle_personalizado, $html_string);
        
        // Mejora Manejo de Extras en el Formulario
        $this->objExtra->Generar_Formulario($this->objDocumento->documento, $this->objDocumento->id);
        foreach ($this->objExtra->datos as $key => $extra) {
            $html_string = str_replace("{{extra_".$key."_input_etiqueta}}"  , $extra['input_etiqueta']  , $html_string);
            $html_string = str_replace("{{extra_".$key."_input_valor}}"     , $extra['valor']           , $html_string);
        }
        // Mejora Manejo de Extras en el Formulario



        if ($this->objDocumento->configuracion['TEXTO_LATERAL_PDF'] != '') {
            $html_string = str_replace("{{texto_lateral}}", $this->objDocumento->configuracion['TEXTO_LATERAL_PDF'], $html_string);
        } else {
            $html_string = str_replace("{{texto_lateral}}", '', $html_string);
        }

        $tr_ivas = '';

        if ($this->objDocumento->impuesto_retencion_irpf > 0) {
            $tr_ivas .= ' 
            <tr>
                <td colspan="5" style="text-align:right;font-weight:bold">RETENCIÓN (IRPF)</td>
                <td style="text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;">' . numero_simple($this->objDocumento->impuesto_retencion_irpf) . '</td>
            </tr> ';
        }
        if ($this->objDocumento->IVA_21 > 0) {
            $tr_ivas .= '  <tr>
                <td colspan="5" style="text-align:right;font-weight:bold">TOTAL IVA (21%)</td>
                <td style="text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;">' . numero_simple($this->objDocumento->IVA_21) . '</td>
            </tr> ';
        }
        if ($this->objDocumento->IVA_10 > 0) {
            $tr_ivas .= ' 
            <tr>
                <td colspan="5" style="text-align:right;font-weight:bold">TOTAL IVA (10%)</td>
                <td style="text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;">' . numero_simple($this->objDocumento->IVA_10) . '</td>
            </tr>';
        }
        if ($this->objDocumento->IVA_4 > 0) {
            $tr_ivas .= ' 
            <tr>
                <td colspan="5" style="text-align:right;font-weight:bold">TOTAL IVA (4%)</td>
                <td style="text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;">' . numero_simple($this->objDocumento->IVA_4) . '</td>
            </tr> ';
        }
        $html_string = str_replace("{{documento_ivas}}", $tr_ivas, $html_string);


        $html_string = str_replace("{{documento_total}}", numero_simple($this->objDocumento->total), $html_string);

        if ($this->objDocumento->impuesto_iva_equivalencia > 0) {
            $html_string = str_replace("{{documento_re}}", '<tr>
               <td colspan=5 style="text-align:right;font-weight:bold">TOTAL RE</td>
               <td style="text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;">' . numero_simple($this->objDocumento->impuesto_iva_equivalencia) . '</td>
          </tr>', $html_string);
        }


        $html_string = str_replace("{{documento_subtotal_pre_retencion}}", numero_simple($this->objDocumento->subtotal_pre_retencion), $html_string);


        $html_string = str_replace("{{documento_dato_contacto}}", $this->objDocumento->configuracion['PDF_DATO_CONTACTO'], $html_string);
        // $html_string = str_replace("{{documento_nro_cuenta}}", $this->objDocumento->configuracion['PDF_NUMERO_DE_CUENTA'], $html_string);

        if (empty($this->objDocumento->configuracion['PDF_NUMERO_DE_CUENTA'])) {
            $html_string = str_replace("{{documento_nro_cuenta}}", 'Sin número de cuenta definido.', $html_string);
        } else {
            $html_string = str_replace("{{documento_nro_cuenta}}", $this->objDocumento->configuracion['PDF_NUMERO_DE_CUENTA'], $html_string);
        }


        if (isset($this->objDocumento->detalle) && trim($this->objDocumento->detalle) != '') {
            $string_detalle = '<div><span>Observaciones: ' . $this->objDocumento->detalle . '</span></div>';
            $html_string = str_replace("{{footer_detalle}}", $string_detalle, $html_string);
        } else {
            $html_string = str_replace("{{footer_detalle}}", '', $html_string);
        }

        $html_string = str_replace("{{footer_text}}", $this->objDocumento->configuracion['PDF_FOOTER_TEXT'], $html_string);

        $mpdf->curlAllowUnsafeSslRequests = true;

        if ($this->objDocumento->estado == 0) {
            $mpdf->SetWatermarkText('Este documento es un Borrador');
            $mpdf->showWatermarkText = true;
        } elseif ($this->objDocumento->estado == 6 &&  $this->objDocumento->documento == "fi_europa_albaranes_compras" ) {
            $mpdf->SetWatermarkText('Ese albarán está anulado');
            $mpdf->showWatermarkText = true;
        }
         else  if ($this->objDocumento->estado == 3 &&
            (
                $this->objDocumento->documento == "fi_europa_facturas" ||
                $this->objDocumento->documento == "fi_europa_compras" ||
                $this->objDocumento->documento == "fi_europa_presupuestos" ||
                $this->objDocumento->documento == "fi_europa_pedidos"
            )
        ) {
            $mpdf->SetWatermarkText('Este documento está anulado');
            $mpdf->showWatermarkText = true;
        }

        $mpdf->WriteHTML($html_string);
        
        $file = $nombre_documento . ".pdf";

        ob_start();
        switch ($param_output) {
            case 'S':
                // 'S' devuelve el contenido como cadena en lugar de mostrarlo o guardarlo
                $base74Pdf = $mpdf->Output('', 'S');
                return $base74Pdf;
                break;
            case 'D':
                $mpdf->Output($file, 'D');
                $pdfContent = ob_get_contents();
                ob_end_clean();
                $base64Pdf = base64_encode($pdfContent);
                return $base64Pdf;
                break;
            case 'I':
                $mpdf->Output($file, 'I');
                $pdfContent = ob_get_contents();
                ob_end_clean();
                $base64Pdf = base64_encode($pdfContent);
                return $base64Pdf;
                break;
            default:
                echo $html_string;
                return $html_string;
                break;
        }
    }

    public function genera_preview_plantilla($param_output = "D", $nombre_documento = "factura", $fk_plantilla = 0)
    {
        include ENLACE_SERVIDOR . "include/mpdf/vendor/autoload.php";
        $mpdf = new \Mpdf\Mpdf();

        $Entidad = new Entidad($this->db, $this->entidad);

        $main_file_path_html = ENLACE_SERVIDOR . "mod_documento_pdf/templates/entity_plantilla.html";
        $main_file_path_css = ENLACE_SERVIDOR . "mod_documento_pdf/templates/entity_plantilla.css";
        $entity_file_path_html = '';
        $entity_file_path_css = '';

        if ($fk_plantilla > 0) {
            require_once(ENLACE_SERVIDOR . "mod_documento_pdf/object/plantilla.object.php");
            $plantillaObject = new Plantilla($this->db, $this->entidad);
            $plantillaObject->fetch($fk_plantilla);

            $entity_file_path_html = html_entity_decode($plantillaObject->plantilla_html);
            $entity_file_path_css = html_entity_decode($plantillaObject->plantilla_css);
        }

        if ($entity_file_path_html == '') {
            $html_string = file_get_contents($main_file_path_html);
        } else {
            $html_string = $entity_file_path_html;
        }
        if ($entity_file_path_css == '') {
            $css_string = file_get_contents($main_file_path_css);
        } else {
            $css_string = $entity_file_path_css;
        }

        $mpdf->WriteHTML('<style>' . $css_string . '</style>');

        $qr_imagen = file_get_contents(ENLACE_SERVIDOR_FILES."images/QR_EXample.jpg");
        $base_64_qr = "data:image/jpeg;base64,".base64_encode($qr_imagen);

        $html_string = str_replace("{{qr}}", '<img with="100px" height="100px" src="' . $base_64_qr . '" alt="QR Code">', $html_string);

        $tr_detalle = '';

        $tr_detalle_equivalencia = '<br/> <b>RE</b>: ' . numero_simple(100);
        $tr_detalle_equivalencia .= '<span class="font-size-menor">(' . numero_simple(100) . ' %)</span>';
        $tr_detalle_retencion = "<br/> <b>RET</b>: " . numero_simple(100);
        $tr_detalle_retencion .= "<span class='font-size-menor'>( " . numero_simple(100) . " %)</span>";
        $tr_detalle_descuento = numero_simple(100);
        $tr_detalle_descuento .= "<span class='font-size-menor'>( " . numero_simple(100) . " %)</span>";


        $tr_detalle .= "
            <tr >
            <td >" . numero_simple(100) . " </td>
            <td >" . " Etiqueta </td>
            <td >" . numero_simple(100) . " </td>
            <td >" . $tr_detalle_descuento . " </td>
            <td ><b>IVA</b>:" . numero_simple(100) . " <span class='font-size-menor'>(" . numero_simple(100) . "%)</span>" . $tr_detalle_equivalencia . $tr_detalle_retencion . "</td>                  
            <td style='text-align:right'>" . numero_simple(100) . " </td>
            </tr> ";

        $tr_detalle_equivalencia = '';
        $tr_detalle_retencion = '';
        $tr_detalle_descuento = '';
        $tr_detalle_ivamonto = '';

        $tr_detalle_personalizado = '<td class="detalle_cantidad"> 100 </td>';

        $tr_detalle_personalizado .= '<td  class="detalle_nombre">NOMBRE</td>';

        $tr_detalle_personalizado .= '<td  class="detalle_precio">100.00</td>';
        $tr_detalle_personalizado .= '<td  class="detalle_descuento"> descuento </td>';
        $tr_detalle_personalizado .= '<td  class="detalle_impuestos">Impuestos</td>';
        $tr_detalle_personalizado .= '<td  class="detalle_retencion">Retencion</td>';
        $tr_detalle_personalizado = '<table><tr>'.$tr_detalle_personalizado.'</tr></table>';

        $html_string = str_replace("{{documento_proyecto}}", 'Nombre proyecto', $html_string);
        $html_string = str_replace("{{documento_logo_formato}}",$Entidad->obtener_url_avatar_pdf($this->entidad), $html_string);
        $html_string = str_replace("{{documento_tipo}}", 'Factura', $html_string);
        $html_string = str_replace("{{documento_numero}}", 'Referencia', $html_string);
        $html_string = str_replace("{{documento_fecha}}", obtenerFechaEnLetra(date('d-m-Y')), $html_string);

        $html_string = str_replace("{{empresa_razonsocial}}", 'Razón Social', $html_string);

        $html_string = str_replace("{{empresa_nombre_comercial}}", 'Nombre comercial', $html_string);
        $html_string = str_replace("{{empresa_nif}}", 'Nro.Identificacion', $html_string);
        $html_string = str_replace("{{empresa_direccion}}", 'Entidad dirección', $html_string);
        $html_string = str_replace("{{empresa_correo}}", 'entidad_correo@dominio.com', $html_string);
        $html_string = str_replace("{{empresa_telefono}}", '+349999999', $html_string);

        $html_string = str_replace("{{cliente_nombre}}", 'Tercero nombre', $html_string);
        $html_string = str_replace("{{cliente_nif}}", 'Tercero identificacion', $html_string);
        $html_string = str_replace("{{cliente_telefono}}", '+349999999', $html_string);
        $html_string = str_replace("{{cliente_email}}", 'tercero_correo@dominio.com', $html_string);
        $html_string = str_replace("{{cliente_direccion}}", 'tercero dirección', $html_string);

        $html_string = str_replace("{{documento_vencimiento}}", date("d-m-Y"), $html_string);
        $html_string = str_replace("{{documento_formapago}}", 'Forma pago', $html_string);

        $html_string = str_replace("{{documento_subtotal_pre_retencion}}", 100, $html_string);
        $html_string = str_replace("{{documento_iva21}}", 100, $html_string);
        $html_string = str_replace("{{documento_iva10}}", 100, $html_string);
        $html_string = str_replace("{{documento_iva4}}", 100, $html_string);
        $html_string = str_replace("{{documento_impuesto_retencion_irpf}}", 100, $html_string);
        $html_string = str_replace("{{documento_total}}", '€ ' . 100, $html_string);

        $html_string = str_replace("{{documento_detalle}}", $tr_detalle, $html_string);
        $html_string = str_replace("{{documento_detalle_personalizado}}", $tr_detalle_personalizado, $html_string);

        $html_string = str_replace("{{documento_detalle_cantidad}}", '', $html_string);
        $html_string = str_replace("{{documento_detalle_nombre}}", '', $html_string);
        $html_string = str_replace("{{documento_detalle_precio}}", '', $html_string);
        $html_string = str_replace("{{documento_detalle_descuento}}", '', $html_string);
        $html_string = str_replace("{{documento_detalle_impuestos}}", '', $html_string);
        $html_string = str_replace("{{documento_detalle_retencion}}", '', $html_string);

        $html_string = str_replace("{{texto_lateral}}", 'Texto lateral', $html_string);


          // Mejora Manejo de Extras en el Formulario
          $this->objExtra->Generar_Formulario("fi_europa_facturas", 0);
          foreach ($this->objExtra->datos as $key => $extra) {
              $html_string = str_replace("{{extra_".$key."_input_etiqueta}}"  , $extra['input_etiqueta']  , $html_string);
              $html_string = str_replace("{{extra_".$key."_input_valor}}"     , $extra['valor']           , $html_string);
          }
          // Mejora Manejo de Extras en el Formulario




        $tr_ivas = '';

        $tr_ivas .= ' 
        <tr>
            <td colspan="5" style="text-align:right;font-weight:bold">RETENCIÓN (IRPF)</td>
            <td style="text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;">' . numero_simple(100) . '</td>
        </tr> ';

        $tr_ivas .= '  <tr>
            <td colspan="5" style="text-align:right;font-weight:bold">TOTAL IVA (21%)</td>
            <td style="text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;">' . numero_simple(100) . '</td>
        </tr> ';

        $tr_ivas .= ' 
        <tr>
            <td colspan="5" style="text-align:right;font-weight:bold">TOTAL IVA (10%)</td>
            <td style="text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;">' . numero_simple(100) . '</td>
        </tr>';

        $tr_ivas .= ' 
        <tr>
            <td colspan="5" style="text-align:right;font-weight:bold">TOTAL IVA (4%)</td>
            <td style="text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;">' . numero_simple(100) . '</td>
        </tr> ';

        $html_string = str_replace("{{documento_ivas}}", $tr_ivas, $html_string);


        $html_string = str_replace("{{documento_total}}", numero_simple(100), $html_string);

        $html_string = str_replace("{{documento_re}}", '<tr>
           <td colspan=5 style="text-align:right;font-weight:bold">TOTAL RE</td>
           <td style="text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;">' . numero_simple(100) . '</td>
      </tr>', $html_string);


        $html_string = str_replace("{{documento_subtotal_pre_retencion}}", numero_simple(100), $html_string);


        $html_string = str_replace("{{documento_dato_contacto}}", 'Contacto', $html_string);
        $html_string = str_replace("{{documento_nro_cuenta}}", 'Nro cuenta', $html_string);

        $string_detalle = '<div><span>Observaciones: Detalle de observaciones</span></div>';
        $html_string = str_replace("{{footer_detalle}}", $string_detalle, $html_string);

        $html_string = str_replace("{{footer_text}}", 'Pie de página', $html_string);

        $mpdf->curlAllowUnsafeSslRequests = true;

        $mpdf->SetWatermarkText('Ese documento es un Borrador');
        $mpdf->showWatermarkText = true;

        $mpdf->WriteHTML($html_string);
        $mpdf->SetFooter('<p style="font-family:arial;font-size:10px;text-align:center;">' . date("Y") . ' © Factuguay</p>');
        $file = $nombre_documento . ".pdf";

        ob_start();
        switch ($param_output) {
            case 'S':
                // 'S' devuelve el contenido como cadena en lugar de mostrarlo o guardarlo
                $base74Pdf = $mpdf->Output('', 'S');
                return $base74Pdf;
                break;
            case 'D':
                $mpdf->Output($file, 'D');
                $pdfContent = ob_get_contents();
                ob_end_clean();
                $base64Pdf = base64_encode($pdfContent);
                return $base64Pdf;
                break;
            case 'I':
                $mpdf->Output($file, 'I');
                $pdfContent = ob_get_contents();
                ob_end_clean();
                $base64Pdf = base64_encode($pdfContent);
                return $base64Pdf;
                break;
            default:
                echo $html_string;
                return $html_string;
                break;
        }
    }
}
