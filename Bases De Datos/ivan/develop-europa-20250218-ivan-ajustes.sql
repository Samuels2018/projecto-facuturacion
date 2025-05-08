INSERT fi_europa_documento_plantilla(entidad, plantilla_html, plantilla_css, activo, creado_fecha, creado_fk_usuario, borrado, borrado_fecha, 
borrado_fk_usuario, orden, titulo, defecto)
VALUES(1, '&lt;table&gt;
     &lt;tr&gt;
          &lt;td style=&quot;text-align:left;&quot; width=&quot;630px&quot;&gt;
               &lt;p class=&quot;color_principal&quot; style=&quot;font-size:35px;margin-bottom:0px;&quot;&gt;{{documento_tipo}} {{documento_numero}}&lt;/p&gt;               
          &lt;/td&gt;
          &lt;td style=&quot;text-align:right;&quot;  width=&quot;70px&quot;&gt;
               {{qr}}
          &lt;/td&gt;
     &lt;/tr&gt;
&lt;/table&gt;
&lt;hr class=&quot;color_secundario&quot;&gt;
&lt;table  class=&quot;tabla_cabecera&quot; border=0&gt;
     &lt;tr&gt;
          &lt;td width=&quot;50%&quot;&gt;
               &lt;span class=&quot;texto-negrita color_principal&quot;&gt;FECHA:&lt;/span&gt;
               &lt;br&gt;{{documento_fecha}}
               &lt;br&gt;
               &lt;br&gt;
               &lt;span class=&quot;texto-negrita color_principal&quot;&gt;DE:&lt;/span&gt;
               &lt;br&gt;{{empresa_razonsocial}}
               &lt;br&gt;{{empresa_nombre_comercial}}
               &lt;br&gt;{{empresa_nif}}
               &lt;br&gt;{{empresa_telefono}}
               &lt;br&gt;{{empresa_correo}}
               &lt;br&gt;{{empresa_direccion}}
          &lt;/td&gt;
          &lt;td width=&quot;50%&quot; style=&quot;vertical-align: baseline;&quot;&gt;
               &lt;span class=&quot;texto-negrita color_principal&quot;&gt;PARA: &lt;/span&gt;
               &lt;br&gt;{{cliente_nombre}}
               &lt;br&gt;{{cliente_nif}}
               &lt;br&gt;{{cliente_telefono}}
               &lt;br&gt;{{cliente_email}}
               &lt;br&gt;{{cliente_direccion}}
          &lt;/td&gt;
     &lt;/tr&gt;
&lt;/table&gt;


&lt;table class=&#039;tabla2&#039;&gt;
     &lt;thead &gt;
     &lt;tr&gt;
               &lt;th style=&quot;text-align:center;&quot;&gt;PROYECTO&lt;/th&gt;
               &lt;th style=&quot;text-align:center&quot;&gt;TRABAJO&lt;/th&gt;
               &lt;th style=&quot;text-align:center&quot;&gt;CONDICIONES &lt;br&gt;DE PAGO&lt;/th&gt;
               &lt;th style=&quot;text-align:center&quot;&gt;FECHA DE &lt;br&gt;VENCIMIENTO&lt;/th&gt;
     &lt;/tr&gt;
     &lt;/thead&gt;
     &lt;tbody&gt;
          &lt;tr &gt;
               &lt;td width=&quot;28%&quot; style=&quot;border:1px solid #82b29c;border-bottom:3px solid #82b29c;&quot; class=&quot;color_tercero&quot;&gt;{{documento_proyecto}}&lt;/td&gt;
               &lt;td width=&quot;28%&quot; style=&quot;border:1px solid #82b29c;border-bottom:3px solid #82b29c;&quot; class=&quot;color_tercero&quot;&gt;&lt;/td&gt;
               &lt;td width=&quot;28%&quot; style=&quot;border:1px solid #82b29c;border-bottom:3px solid #82b29c;&quot; class=&quot;color_tercero&quot;&gt;{{documento_formapago}}&lt;/td&gt;
               &lt;td width=&quot;16%&quot; style=&quot;text-align:center; border:1px solid #82b29c;border-bottom:3px solid #82b29c;&quot; class=&quot;color_tercero&quot;&gt;
                    {{documento_vencimiento}}
               &lt;/td&gt;
          &lt;/tr&gt;
    &lt;tbody&gt;
&lt;/table&gt;

&lt;br&gt;
&lt;br&gt;
&lt;div style=&#039;position: fixed; top: 112; left: -15; rotate: -90; text-align: left; width: 100%; font-size: 12px; border:1px solid;padding:5px;margin-left:-30px;&#039;&gt;
     {{texto_lateral}}
&lt;/div&gt;
&lt;table class=&#039;tabla3&#039; &gt;
     &lt;thead&gt;
          &lt;tr&gt;
               &lt;th width=&#039;10%&#039; style=&#039;text-align:center&#039;&gt;CANT.&lt;/th&gt;
               &lt;th width=&#039;40%&#039; style=&#039;text-align:center&#039;&gt;DESCRIPCIÓN&lt;/th&gt;
               &lt;th style=&#039;text-align:center&#039;&gt;PRECIO POR UNIDAD&lt;/th&gt;
               &lt;th style=&#039;text-align:center&#039;&gt;DESCUENTO&lt;/th&gt;
               &lt;th style=&#039;text-align:center&#039;&gt;IMPUESTOS&lt;/th&gt;
               &lt;th style=&#039;text-align:center&#039;&gt;TOTAL DE LÍNEA&lt;/th&gt;
          &lt;/tr&gt;
     &lt;/thead&gt;
     &lt;tbody&gt;
          {{documento_detalle}}
          {{documento_adicionales}}
          
          &lt;tr&gt;
               &lt;td colspan=5 style=&#039;text-align:right;font-weight:bold&#039;&gt;BASE IMPONIBLE&lt;/td&gt;
               &lt;td style=&#039;text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;&#039; &gt;{{documento_subtotal_pre_retencion}}&lt;/td&gt;
          &lt;/tr&gt;
          
          {{documento_irpf}}
          
          {{documento_ivas}}

          {{documento_re}}

          &lt;tr&gt;
               &lt;td colspan=5 style=&#039;text-align:right;font-weight:bold&#039;&gt;TOTAL&lt;/td&gt;
               &lt;td style=&#039;text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;&#039;&gt;{{documento_total}}&lt;/td&gt;
          &lt;/tr&gt;

     &lt;/tbody&gt;
&lt;/table&gt;

&lt;div&gt;
     Contacto: {{documento_dato_contacto}}
&lt;/div&gt;

&lt;div class=&#039;observaciones&#039;&gt;
     &lt;span class=&#039;texto-negrita&#039;&gt;Número de Cuenta: {{documento_nro_cuenta}}&lt;/span&gt;
&lt;/div&gt;
{{footer_detalle}}
&lt;div class=&quot;observaciones&quot;&gt;
     &lt;p&gt;
          {{footer_text}}
     &lt;/p&gt;
&lt;/div&gt;', '.tabla2 {
     width: 100%;
     border-collapse: collapse;
}

.tabla2 td {
     border: 1px solid #E6EEE9;
     padding: 7px;
     background-color: #E6EEE9;
     color: #645f8d;
}

.tabla2 th {

     color: #645f8d;
}

.tabla3 {
     width: 100%;
     border-collapse: collapse;
}

.tabla3 th {
     font-size: 8pt;
     padding: 7px;
     color: #645f8d;
}

.tabla3 td {
     border: 1px solid #E6EEE9;
     font-size: 8pt;
     padding: 7px;
     color: #645f8d;
}

.tabla4 {
     width: 100%;
     border-collapse: collapse;
}

.tabla4 th {
     font-size: 8pt;
     padding: 7px;
     color: #645f8d;
}

.tabla4 td {
     border: 1px solid #E6EEE9;
     font-size: 8pt;
     padding: 7px;
     color: #645f8d;
}

.color_principal {
     color: #645f8d;
}

.color_secundario {
     color: #A3B4AC;
}

.color_tercero {
     color: #82b29c;
}

.texto-negrita {
     font-weight: bold;
     font-size: 9pt;
}

body {
     font-size: 9pt;
     /* Tamaño de letra por defecto */
     font-family: centurygothic;
}

.borde-azul {
     border: 1px solid #8080B9;
}

.observaciones {
     margin-top: 20px;
     margin-bottom: 20px;
}

.bordes-laterales {
     border-right: 1px solid #8080B9;
     border-right: 1px solid #8080B9;
}

.tabla_cabecera {
     margin-bottom: 50px;
}

.documento_iva21_show{
     display: none;
}
.font-size-menor{
     font-size:5pt;
}', 1, NOW(), 8, 0, NULL, NULL, 1, 'Pdf', 1)