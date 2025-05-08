-- Se agrega la columna agente_txt en las tablas Factura, Presupuesto, Albaran, etc.
ALTER TABLE fi_europa_facturas ADD COLUMN agente_txt VARCHAR(400) NULL DEFAULT '';
ALTER TABLE fi_europa_albaranes_compras ADD COLUMN agente_txt VARCHAR(400) NULL DEFAULT '';
ALTER TABLE fi_europa_albaranes_ventas ADD COLUMN agente_txt VARCHAR(400) NULL DEFAULT '';
ALTER TABLE fi_europa_compras ADD COLUMN agente_txt VARCHAR(400) NULL DEFAULT '';
ALTER TABLE fi_europa_pedidos ADD COLUMN agente_txt VARCHAR(400) NULL DEFAULT '';
ALTER TABLE fi_europa_presupuestos ADD COLUMN agente_txt VARCHAR(400) NULL DEFAULT '';

-- INSERT EN LA TABLA FI_CONFIGURACION PARA TODAS LAS EMPRESAS --
SET @entidad = 9;
INSERT INTO `fi_configuracion` (`entidad`, `configuracion`, `valor`, `activo`, `creado_fecha`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`, `creado_fk_usuario`) VALUES(@entidad, 'quickbooks_modo', 'development', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'moneda_base', 'EUR', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'quickbooks_client_id', 'AB6YDIes5ItZ2GwYNt1RlJHHxTlrm@entidadSJMxPZaVWEqN8iXABIOm', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'quickbooks_client_secret', 'B5m1hBRNyyu8XgpQzxhgvDDf56Rh2RFGNO38vZGS', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'PDF_DATO_CONTACTO', 'jcmm@ice.co.cr - www.avantecds.eu - +506 88493002', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'PDF_FOOTER_TEXT', 'Le recordamos que los datos incorporados en este documento y que nos han sido facilitado en su momento forman parte de un fichero con Datos de
Carácter Personal cuyo responsable es Compañia para QA .S.A.. con el domicilio social en C/ CUENCA2, 292 PLANTA 22, PUERTA A 244032-LEÓN
donde Ud. Podrá ejecutar en todo momento losderechos de acceso, modificación, cancelación o en su caso el de opocición.', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'PDF_NUMERO_DE_CUENTA', 'BBVAESMM 018 0688 12 02008001771
IBAN ES123456789890123456789011
SWIFT (BIC) BBVAESMM
ABA CODE BBVAESMM 0182 0688 12323465566772
', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'TEXTO_LATERAL_PDF', 'INSCRITA EN EL REGISTRO MERCANTIL DE Alajuela EN EL TOMO 1.0025, FOLIO 25, HOJA AL-28-525', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'sistema_transacciones_multimoneda', '0', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'sistema_transacciones_fk_moneda', '13', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_factura', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará la factura electrónica en formato PDF correspondiente al servicio/producto ofrecido el [Fecha]. Por favor, revise detenidamente la información contenida en la factura y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la factura:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Factura emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_Presupuesto', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el presupuesto en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles</p>

    <div class="details">
        <p><strong>Detalles principales de la Presupuesto:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Presupuesto emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_pedido', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el pedido registrado en nuestro sistema en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Pedido:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Pedido emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_albaran_compra', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albarán</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el albarán de ventas registrado en nuestro sistema en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Albarán:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Albarán emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_albaran_venta', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albarán</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el albarán de ventas registrado en nuestro sistema en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Albarán:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Albarán emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_compra', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará la Compra electrónica en formato PDF correspondiente al servicio/producto ofrecido el [Fecha]. Por favor, revise detenidamente la información contenida en la Compra y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Compra:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Compra emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL);
SET @entidad = 1;
INSERT INTO `fi_configuracion` (`entidad`, `configuracion`, `valor`, `activo`, `creado_fecha`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`, `creado_fk_usuario`) VALUES(@entidad, 'quickbooks_modo', 'development', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'moneda_base', 'EUR', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'quickbooks_client_id', 'AB6YDIes5ItZ2GwYNt1RlJHHxTlrm@entidadSJMxPZaVWEqN8iXABIOm', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'quickbooks_client_secret', 'B5m1hBRNyyu8XgpQzxhgvDDf56Rh2RFGNO38vZGS', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'PDF_DATO_CONTACTO', 'jcmm@ice.co.cr - www.avantecds.eu - +506 88493002', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'PDF_FOOTER_TEXT', 'Le recordamos que los datos incorporados en este documento y que nos han sido facilitado en su momento forman parte de un fichero con Datos de
Carácter Personal cuyo responsable es Compañia para QA .S.A.. con el domicilio social en C/ CUENCA2, 292 PLANTA 22, PUERTA A 244032-LEÓN
donde Ud. Podrá ejecutar en todo momento losderechos de acceso, modificación, cancelación o en su caso el de opocición.', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'PDF_NUMERO_DE_CUENTA', 'BBVAESMM 018 0688 12 02008001771
IBAN ES123456789890123456789011
SWIFT (BIC) BBVAESMM
ABA CODE BBVAESMM 0182 0688 12323465566772
', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'TEXTO_LATERAL_PDF', 'INSCRITA EN EL REGISTRO MERCANTIL DE Alajuela EN EL TOMO 1.0025, FOLIO 25, HOJA AL-28-525', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'sistema_transacciones_multimoneda', '0', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'sistema_transacciones_fk_moneda', '13', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_factura', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará la factura electrónica en formato PDF correspondiente al servicio/producto ofrecido el [Fecha]. Por favor, revise detenidamente la información contenida en la factura y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la factura:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Factura emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_Presupuesto', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el presupuesto en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles</p>

    <div class="details">
        <p><strong>Detalles principales de la Presupuesto:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Presupuesto emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_pedido', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el pedido registrado en nuestro sistema en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Pedido:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Pedido emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_albaran_compra', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albarán</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el albarán de ventas registrado en nuestro sistema en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Albarán:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Albarán emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_albaran_venta', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albarán</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el albarán de ventas registrado en nuestro sistema en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Albarán:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Albarán emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_compra', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará la Compra electrónica en formato PDF correspondiente al servicio/producto ofrecido el [Fecha]. Por favor, revise detenidamente la información contenida en la Compra y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Compra:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Compra emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL);
SET @entidad = 2;
INSERT INTO `fi_configuracion` (`entidad`, `configuracion`, `valor`, `activo`, `creado_fecha`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`, `creado_fk_usuario`) VALUES(@entidad, 'quickbooks_modo', 'development', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'moneda_base', 'EUR', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'quickbooks_client_id', 'AB6YDIes5ItZ2GwYNt1RlJHHxTlrm@entidadSJMxPZaVWEqN8iXABIOm', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'quickbooks_client_secret', 'B5m1hBRNyyu8XgpQzxhgvDDf56Rh2RFGNO38vZGS', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'PDF_DATO_CONTACTO', 'jcmm@ice.co.cr - www.avantecds.eu - +506 88493002', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'PDF_FOOTER_TEXT', 'Le recordamos que los datos incorporados en este documento y que nos han sido facilitado en su momento forman parte de un fichero con Datos de
Carácter Personal cuyo responsable es Compañia para QA .S.A.. con el domicilio social en C/ CUENCA2, 292 PLANTA 22, PUERTA A 244032-LEÓN
donde Ud. Podrá ejecutar en todo momento losderechos de acceso, modificación, cancelación o en su caso el de opocición.', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'PDF_NUMERO_DE_CUENTA', 'BBVAESMM 018 0688 12 02008001771
IBAN ES123456789890123456789011
SWIFT (BIC) BBVAESMM
ABA CODE BBVAESMM 0182 0688 12323465566772
', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'TEXTO_LATERAL_PDF', 'INSCRITA EN EL REGISTRO MERCANTIL DE Alajuela EN EL TOMO 1.0025, FOLIO 25, HOJA AL-28-525', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'sistema_transacciones_multimoneda', '0', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'sistema_transacciones_fk_moneda', '13', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_factura', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará la factura electrónica en formato PDF correspondiente al servicio/producto ofrecido el [Fecha]. Por favor, revise detenidamente la información contenida en la factura y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la factura:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Factura emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_Presupuesto', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el presupuesto en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles</p>

    <div class="details">
        <p><strong>Detalles principales de la Presupuesto:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Presupuesto emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_pedido', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el pedido registrado en nuestro sistema en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Pedido:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Pedido emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_albaran_compra', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albarán</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el albarán de ventas registrado en nuestro sistema en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Albarán:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Albarán emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_albaran_venta', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albarán</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el albarán de ventas registrado en nuestro sistema en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Albarán:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Albarán emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_compra', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará la Compra electrónica en formato PDF correspondiente al servicio/producto ofrecido el [Fecha]. Por favor, revise detenidamente la información contenida en la Compra y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Compra:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Compra emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL);
SET @entidad = 3;
INSERT INTO `fi_configuracion` (`entidad`, `configuracion`, `valor`, `activo`, `creado_fecha`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`, `creado_fk_usuario`) VALUES(@entidad, 'quickbooks_modo', 'development', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'moneda_base', 'EUR', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'quickbooks_client_id', 'AB6YDIes5ItZ2GwYNt1RlJHHxTlrm@entidadSJMxPZaVWEqN8iXABIOm', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'quickbooks_client_secret', 'B5m1hBRNyyu8XgpQzxhgvDDf56Rh2RFGNO38vZGS', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'PDF_DATO_CONTACTO', 'jcmm@ice.co.cr - www.avantecds.eu - +506 88493002', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'PDF_FOOTER_TEXT', 'Le recordamos que los datos incorporados en este documento y que nos han sido facilitado en su momento forman parte de un fichero con Datos de
Carácter Personal cuyo responsable es Compañia para QA .S.A.. con el domicilio social en C/ CUENCA2, 292 PLANTA 22, PUERTA A 244032-LEÓN
donde Ud. Podrá ejecutar en todo momento losderechos de acceso, modificación, cancelación o en su caso el de opocición.', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'PDF_NUMERO_DE_CUENTA', 'BBVAESMM 018 0688 12 02008001771
IBAN ES123456789890123456789011
SWIFT (BIC) BBVAESMM
ABA CODE BBVAESMM 0182 0688 12323465566772
', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'TEXTO_LATERAL_PDF', 'INSCRITA EN EL REGISTRO MERCANTIL DE Alajuela EN EL TOMO 1.0025, FOLIO 25, HOJA AL-28-525', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'sistema_transacciones_multimoneda', '0', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'sistema_transacciones_fk_moneda', '13', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_factura', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará la factura electrónica en formato PDF correspondiente al servicio/producto ofrecido el [Fecha]. Por favor, revise detenidamente la información contenida en la factura y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la factura:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Factura emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_Presupuesto', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el presupuesto en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles</p>

    <div class="details">
        <p><strong>Detalles principales de la Presupuesto:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Presupuesto emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_pedido', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el pedido registrado en nuestro sistema en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Pedido:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Pedido emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_albaran_compra', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albarán</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el albarán de ventas registrado en nuestro sistema en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Albarán:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Albarán emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_albaran_venta', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albarán</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el albarán de ventas registrado en nuestro sistema en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Albarán:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Albarán emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_compra', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará la Compra electrónica en formato PDF correspondiente al servicio/producto ofrecido el [Fecha]. Por favor, revise detenidamente la información contenida en la Compra y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Compra:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Compra emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL);
SET @entidad = 4;
INSERT INTO `fi_configuracion` (`entidad`, `configuracion`, `valor`, `activo`, `creado_fecha`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`, `creado_fk_usuario`) VALUES(@entidad, 'quickbooks_modo', 'development', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'moneda_base', 'EUR', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'quickbooks_client_id', 'AB6YDIes5ItZ2GwYNt1RlJHHxTlrm@entidadSJMxPZaVWEqN8iXABIOm', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'quickbooks_client_secret', 'B5m1hBRNyyu8XgpQzxhgvDDf56Rh2RFGNO38vZGS', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'PDF_DATO_CONTACTO', 'jcmm@ice.co.cr - www.avantecds.eu - +506 88493002', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'PDF_FOOTER_TEXT', 'Le recordamos que los datos incorporados en este documento y que nos han sido facilitado en su momento forman parte de un fichero con Datos de
Carácter Personal cuyo responsable es Compañia para QA .S.A.. con el domicilio social en C/ CUENCA2, 292 PLANTA 22, PUERTA A 244032-LEÓN
donde Ud. Podrá ejecutar en todo momento losderechos de acceso, modificación, cancelación o en su caso el de opocición.', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'PDF_NUMERO_DE_CUENTA', 'BBVAESMM 018 0688 12 02008001771
IBAN ES123456789890123456789011
SWIFT (BIC) BBVAESMM
ABA CODE BBVAESMM 0182 0688 12323465566772
', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'TEXTO_LATERAL_PDF', 'INSCRITA EN EL REGISTRO MERCANTIL DE Alajuela EN EL TOMO 1.0025, FOLIO 25, HOJA AL-28-525', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'sistema_transacciones_multimoneda', '0', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'sistema_transacciones_fk_moneda', '13', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_factura', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará la factura electrónica en formato PDF correspondiente al servicio/producto ofrecido el [Fecha]. Por favor, revise detenidamente la información contenida en la factura y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la factura:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Factura emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_Presupuesto', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el presupuesto en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles</p>

    <div class="details">
        <p><strong>Detalles principales de la Presupuesto:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Presupuesto emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_pedido', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el pedido registrado en nuestro sistema en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Pedido:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Pedido emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_albaran_compra', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albarán</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el albarán de ventas registrado en nuestro sistema en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Albarán:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Albarán emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_albaran_venta', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albarán</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el albarán de ventas registrado en nuestro sistema en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Albarán:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Albarán emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_compra', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará la Compra electrónica en formato PDF correspondiente al servicio/producto ofrecido el [Fecha]. Por favor, revise detenidamente la información contenida en la Compra y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Compra:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Compra emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL);
SET @entidad = 5;
INSERT INTO `fi_configuracion` (`entidad`, `configuracion`, `valor`, `activo`, `creado_fecha`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`, `creado_fk_usuario`) VALUES(@entidad, 'quickbooks_modo', 'development', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'moneda_base', 'EUR', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'quickbooks_client_id', 'AB6YDIes5ItZ2GwYNt1RlJHHxTlrm@entidadSJMxPZaVWEqN8iXABIOm', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'quickbooks_client_secret', 'B5m1hBRNyyu8XgpQzxhgvDDf56Rh2RFGNO38vZGS', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'PDF_DATO_CONTACTO', 'jcmm@ice.co.cr - www.avantecds.eu - +506 88493002', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'PDF_FOOTER_TEXT', 'Le recordamos que los datos incorporados en este documento y que nos han sido facilitado en su momento forman parte de un fichero con Datos de
Carácter Personal cuyo responsable es Compañia para QA .S.A.. con el domicilio social en C/ CUENCA2, 292 PLANTA 22, PUERTA A 244032-LEÓN
donde Ud. Podrá ejecutar en todo momento losderechos de acceso, modificación, cancelación o en su caso el de opocición.', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'PDF_NUMERO_DE_CUENTA', 'BBVAESMM 018 0688 12 02008001771
IBAN ES123456789890123456789011
SWIFT (BIC) BBVAESMM
ABA CODE BBVAESMM 0182 0688 12323465566772
', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'TEXTO_LATERAL_PDF', 'INSCRITA EN EL REGISTRO MERCANTIL DE Alajuela EN EL TOMO 1.0025, FOLIO 25, HOJA AL-28-525', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'sistema_transacciones_multimoneda', '0', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'sistema_transacciones_fk_moneda', '13', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_factura', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará la factura electrónica en formato PDF correspondiente al servicio/producto ofrecido el [Fecha]. Por favor, revise detenidamente la información contenida en la factura y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la factura:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Factura emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_Presupuesto', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el presupuesto en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles</p>

    <div class="details">
        <p><strong>Detalles principales de la Presupuesto:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Presupuesto emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_pedido', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el pedido registrado en nuestro sistema en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Pedido:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Pedido emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_albaran_compra', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albarán</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el albarán de ventas registrado en nuestro sistema en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Albarán:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Albarán emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_albaran_venta', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albarán</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el albarán de ventas registrado en nuestro sistema en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Albarán:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Albarán emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_compra', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará la Compra electrónica en formato PDF correspondiente al servicio/producto ofrecido el [Fecha]. Por favor, revise detenidamente la información contenida en la Compra y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Compra:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Compra emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL);
SET @entidad = 8;
INSERT INTO `fi_configuracion` (`entidad`, `configuracion`, `valor`, `activo`, `creado_fecha`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`, `creado_fk_usuario`) VALUES(@entidad, 'quickbooks_modo', 'development', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'moneda_base', 'EUR', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'quickbooks_client_id', 'AB6YDIes5ItZ2GwYNt1RlJHHxTlrm@entidadSJMxPZaVWEqN8iXABIOm', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'quickbooks_client_secret', 'B5m1hBRNyyu8XgpQzxhgvDDf56Rh2RFGNO38vZGS', 1, NULL, 0, NULL, NULL, NULL),
		(@entidad, 'PDF_DATO_CONTACTO', 'jcmm@ice.co.cr - www.avantecds.eu - +506 88493002', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'PDF_FOOTER_TEXT', 'Le recordamos que los datos incorporados en este documento y que nos han sido facilitado en su momento forman parte de un fichero con Datos de
Carácter Personal cuyo responsable es Compañia para QA .S.A.. con el domicilio social en C/ CUENCA2, 292 PLANTA 22, PUERTA A 244032-LEÓN
donde Ud. Podrá ejecutar en todo momento losderechos de acceso, modificación, cancelación o en su caso el de opocición.', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'PDF_NUMERO_DE_CUENTA', 'BBVAESMM 018 0688 12 02008001771
IBAN ES123456789890123456789011
SWIFT (BIC) BBVAESMM
ABA CODE BBVAESMM 0182 0688 12323465566772
', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'TEXTO_LATERAL_PDF', 'INSCRITA EN EL REGISTRO MERCANTIL DE Alajuela EN EL TOMO 1.0025, FOLIO 25, HOJA AL-28-525', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'sistema_transacciones_multimoneda', '0', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'sistema_transacciones_fk_moneda', '13', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_factura', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará la factura electrónica en formato PDF correspondiente al servicio/producto ofrecido el [Fecha]. Por favor, revise detenidamente la información contenida en la factura y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la factura:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Factura emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_Presupuesto', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el presupuesto en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles</p>

    <div class="details">
        <p><strong>Detalles principales de la Presupuesto:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Presupuesto emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_pedido', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el pedido registrado en nuestro sistema en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Pedido:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Pedido emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_albaran_compra', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albarán</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el albarán de ventas registrado en nuestro sistema en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Albarán:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Albarán emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_albaran_venta', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albarán</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará el albarán de ventas registrado en nuestro sistema en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Albarán:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Albarán emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL),
		(@entidad, 'email_body_compra', '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            color: #000;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .details {
            margin: 20px 0;
        }

        .details-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .contact-info {
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #0066cc;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>

    <p>Adjunto a este correo encontrará la Compra electrónica en formato PDF correspondiente al servicio/producto ofrecido el [Fecha]. Por favor, revise detenidamente la información contenida en la Compra y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>

    <div class="details">
        <p><strong>Detalles principales de la Compra:</strong></p>
        <ul class="details-list">
            <li>- Número de [tipo_documento]: [Número de documento]</li>
            <li>- Fecha de emisión: [Fecha]</li>
            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>
            <li>- Importe total: [Importe en euros]</li>
        </ul>
    </div>

    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>

    <p>Saludos cordiales,</p>

    <div class="contact-info">
        <p>[Nombre de tu empresa]<br>
        [Dirección de tu empresa]<br>
        <a href="tel:[Teléfono]">[Teléfono]</a><br>
        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>
    </div>

    <div class="footer">
        <p>Compra emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>
        <a href="https://factuguay.es/">https://factuguay.es/</a><br>
        <a href="tel:+34630745478">+34 630 74 54 78</a></p>
    </div>
</body>
</html>', 1, NOW(), 0, NULL, NULL, NULL);





-- INSERT EN LA TABLA FI_EUROPA_DOCUMENTO_PLANTILLA PARA TODAS LAS EMPRESAS --
SET @entidad = 9;
INSERT INTO `fi_europa_documento_plantilla` (`entidad`, `plantilla_html`, `plantilla_css`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES(@entidad, '<table>
     <tr>
          <td style="text-align:left;" width="630px">
               <p class="color_principal" style="font-size:35px;margin-bottom:0px;">{{documento_tipo}} {{documento_numero}}</p>               
          </td>
          <td style="text-align:right;"  width="70px">
               {{qr}}
          </td>
     </tr>
</table>
<hr class="color_secundario">
<table  class="tabla_cabecera" border=0>
     <tr>
          <td width="50%">
               <span class="texto-negrita color_principal">FECHA:</span>
               <br>{{documento_fecha}}
               <br>
               <br>
               <span class="texto-negrita color_principal">DE:</span>
               <br>{{empresa_razonsocial}}
               <br>{{empresa_nombre_comercial}}
               <br>{{empresa_nif}}
               <br>{{empresa_telefono}}
               <br>{{empresa_correo}}
               <br>{{empresa_direccion}}
          </td>
          <td width="50%" style="vertical-align: baseline;">
               <span class="texto-negrita color_principal">PARA: </span>
               <br>{{cliente_nombre}}
               <br>{{cliente_nif}}
               <br>{{cliente_telefono}}
               <br>{{cliente_email}}
               <br>{{cliente_direccion}}
          </td>
     </tr>
</table>


<table class='tabla2'>
     <thead >
     <tr>
               <th style="text-align:center;">PROYECTO</th>
               <th style="text-align:center">TRABAJO</th>
               <th style="text-align:center">CONDICIONES <br>DE PAGO</th>
               <th style="text-align:center">FECHA DE <br>VENCIMIENTO</th>
     </tr>
     </thead>
     <tbody>
          <tr >
               <td width="28%" style="border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero">{{documento_proyecto}}</td>
               <td width="28%" style="border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero"></td>
               <td width="28%" style="border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero">{{documento_formapago}}</td>
               <td width="16%" style="text-align:center; border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero">
                    {{documento_vencimiento}}
               </td>
          </tr>
    <tbody>
</table>

<br>
<br>
<div style='position: fixed; top: 112; left: -15; rotate: -90; text-align: left; width: 100%; font-size: 12px; border:1px solid;padding:5px;margin-left:-30px;'>
     {{texto_lateral}}
</div>
<table class='tabla3' >
     <thead>
          <tr>
               <th width='10%' style='text-align:center'>CANT.</th>
               <th width='40%' style='text-align:center'>DESCRIPCIÓN</th>
               <th style='text-align:center'>PRECIO POR UNIDAD</th>
               <th style='text-align:center'>DESCUENTO</th>
               <th style='text-align:center'>IMPUESTOS</th>
               <th style='text-align:center'>TOTAL DE LÍNEA</th>
          </tr>
     </thead>
     <tbody>
          {{documento_detalle}}
          {{documento_adicionales}}
          
          <tr>
               <td colspan=5 style='text-align:right;font-weight:bold'>BASE IMPONIBLE</td>
               <td style='text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;' >{{documento_subtotal_pre_retencion}}</td>
          </tr>
          
          {{documento_irpf}}
          
          {{documento_ivas}}

          {{documento_re}}

          <tr>
               <td colspan=5 style='text-align:right;font-weight:bold'>TOTAL</td>
               <td style='text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;'>{{documento_total}}</td>
          </tr>

     </tbody>
</table>

<div>
     Contacto: {{documento_dato_contacto}}
</div>

<div class='observaciones'>
     <span class='texto-negrita'>Número de Cuenta: {{documento_nro_cuenta}}</span>
</div>
{{footer_detalle}}
<div class="observaciones">
     <p>
          {{footer_text}}
     </p>
</div>.tabla2 {
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
}', 1, NOW(), NULL, 0, NULL, NULL);
SET @entidad = 1;
INSERT INTO `fi_europa_documento_plantilla` (`entidad`, `plantilla_html`, `plantilla_css`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES(@entidad, '<table>
     <tr>
          <td style="text-align:left;" width="630px">
               <p class="color_principal" style="font-size:35px;margin-bottom:0px;">{{documento_tipo}} {{documento_numero}}</p>               
          </td>
          <td style="text-align:right;"  width="70px">
               {{qr}}
          </td>
     </tr>
</table>
<hr class="color_secundario">
<table  class="tabla_cabecera" border=0>
     <tr>
          <td width="50%">
               <span class="texto-negrita color_principal">FECHA:</span>
               <br>{{documento_fecha}}
               <br>
               <br>
               <span class="texto-negrita color_principal">DE:</span>
               <br>{{empresa_razonsocial}}
               <br>{{empresa_nombre_comercial}}
               <br>{{empresa_nif}}
               <br>{{empresa_telefono}}
               <br>{{empresa_correo}}
               <br>{{empresa_direccion}}
          </td>
          <td width="50%" style="vertical-align: baseline;">
               <span class="texto-negrita color_principal">PARA: </span>
               <br>{{cliente_nombre}}
               <br>{{cliente_nif}}
               <br>{{cliente_telefono}}
               <br>{{cliente_email}}
               <br>{{cliente_direccion}}
          </td>
     </tr>
</table>


<table class='tabla2'>
     <thead >
     <tr>
               <th style="text-align:center;">PROYECTO</th>
               <th style="text-align:center">TRABAJO</th>
               <th style="text-align:center">CONDICIONES <br>DE PAGO</th>
               <th style="text-align:center">FECHA DE <br>VENCIMIENTO</th>
     </tr>
     </thead>
     <tbody>
          <tr >
               <td width="28%" style="border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero">{{documento_proyecto}}</td>
               <td width="28%" style="border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero"></td>
               <td width="28%" style="border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero">{{documento_formapago}}</td>
               <td width="16%" style="text-align:center; border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero">
                    {{documento_vencimiento}}
               </td>
          </tr>
    <tbody>
</table>

<br>
<br>
<div style='position: fixed; top: 112; left: -15; rotate: -90; text-align: left; width: 100%; font-size: 12px; border:1px solid;padding:5px;margin-left:-30px;'>
     {{texto_lateral}}
</div>
<table class='tabla3' >
     <thead>
          <tr>
               <th width='10%' style='text-align:center'>CANT.</th>
               <th width='40%' style='text-align:center'>DESCRIPCIÓN</th>
               <th style='text-align:center'>PRECIO POR UNIDAD</th>
               <th style='text-align:center'>DESCUENTO</th>
               <th style='text-align:center'>IMPUESTOS</th>
               <th style='text-align:center'>TOTAL DE LÍNEA</th>
          </tr>
     </thead>
     <tbody>
          {{documento_detalle}}
          {{documento_adicionales}}
          
          <tr>
               <td colspan=5 style='text-align:right;font-weight:bold'>BASE IMPONIBLE</td>
               <td style='text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;' >{{documento_subtotal_pre_retencion}}</td>
          </tr>
          
          {{documento_irpf}}
          
          {{documento_ivas}}

          {{documento_re}}

          <tr>
               <td colspan=5 style='text-align:right;font-weight:bold'>TOTAL</td>
               <td style='text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;'>{{documento_total}}</td>
          </tr>

     </tbody>
</table>

<div>
     Contacto: {{documento_dato_contacto}}
</div>

<div class='observaciones'>
     <span class='texto-negrita'>Número de Cuenta: {{documento_nro_cuenta}}</span>
</div>
{{footer_detalle}}
<div class="observaciones">
     <p>
          {{footer_text}}
     </p>
</div>.tabla2 {
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
}', 1, NOW(), NULL, 0, NULL, NULL);
SET @entidad = 2;
INSERT INTO `fi_europa_documento_plantilla` (`entidad`, `plantilla_html`, `plantilla_css`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES(@entidad, '<table>
     <tr>
          <td style="text-align:left;" width="630px">
               <p class="color_principal" style="font-size:35px;margin-bottom:0px;">{{documento_tipo}} {{documento_numero}}</p>               
          </td>
          <td style="text-align:right;"  width="70px">
               {{qr}}
          </td>
     </tr>
</table>
<hr class="color_secundario">
<table  class="tabla_cabecera" border=0>
     <tr>
          <td width="50%">
               <span class="texto-negrita color_principal">FECHA:</span>
               <br>{{documento_fecha}}
               <br>
               <br>
               <span class="texto-negrita color_principal">DE:</span>
               <br>{{empresa_razonsocial}}
               <br>{{empresa_nombre_comercial}}
               <br>{{empresa_nif}}
               <br>{{empresa_telefono}}
               <br>{{empresa_correo}}
               <br>{{empresa_direccion}}
          </td>
          <td width="50%" style="vertical-align: baseline;">
               <span class="texto-negrita color_principal">PARA: </span>
               <br>{{cliente_nombre}}
               <br>{{cliente_nif}}
               <br>{{cliente_telefono}}
               <br>{{cliente_email}}
               <br>{{cliente_direccion}}
          </td>
     </tr>
</table>


<table class='tabla2'>
     <thead >
     <tr>
               <th style="text-align:center;">PROYECTO</th>
               <th style="text-align:center">TRABAJO</th>
               <th style="text-align:center">CONDICIONES <br>DE PAGO</th>
               <th style="text-align:center">FECHA DE <br>VENCIMIENTO</th>
     </tr>
     </thead>
     <tbody>
          <tr >
               <td width="28%" style="border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero">{{documento_proyecto}}</td>
               <td width="28%" style="border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero"></td>
               <td width="28%" style="border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero">{{documento_formapago}}</td>
               <td width="16%" style="text-align:center; border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero">
                    {{documento_vencimiento}}
               </td>
          </tr>
    <tbody>
</table>

<br>
<br>
<div style='position: fixed; top: 112; left: -15; rotate: -90; text-align: left; width: 100%; font-size: 12px; border:1px solid;padding:5px;margin-left:-30px;'>
     {{texto_lateral}}
</div>
<table class='tabla3' >
     <thead>
          <tr>
               <th width='10%' style='text-align:center'>CANT.</th>
               <th width='40%' style='text-align:center'>DESCRIPCIÓN</th>
               <th style='text-align:center'>PRECIO POR UNIDAD</th>
               <th style='text-align:center'>DESCUENTO</th>
               <th style='text-align:center'>IMPUESTOS</th>
               <th style='text-align:center'>TOTAL DE LÍNEA</th>
          </tr>
     </thead>
     <tbody>
          {{documento_detalle}}
          {{documento_adicionales}}
          
          <tr>
               <td colspan=5 style='text-align:right;font-weight:bold'>BASE IMPONIBLE</td>
               <td style='text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;' >{{documento_subtotal_pre_retencion}}</td>
          </tr>
          
          {{documento_irpf}}
          
          {{documento_ivas}}

          {{documento_re}}

          <tr>
               <td colspan=5 style='text-align:right;font-weight:bold'>TOTAL</td>
               <td style='text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;'>{{documento_total}}</td>
          </tr>

     </tbody>
</table>

<div>
     Contacto: {{documento_dato_contacto}}
</div>

<div class='observaciones'>
     <span class='texto-negrita'>Número de Cuenta: {{documento_nro_cuenta}}</span>
</div>
{{footer_detalle}}
<div class="observaciones">
     <p>
          {{footer_text}}
     </p>
</div>.tabla2 {
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
}', 1, NOW(), NULL, 0, NULL, NULL);
SET @entidad = 3;
INSERT INTO `fi_europa_documento_plantilla` (`entidad`, `plantilla_html`, `plantilla_css`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES(@entidad, '<table>
     <tr>
          <td style="text-align:left;" width="630px">
               <p class="color_principal" style="font-size:35px;margin-bottom:0px;">{{documento_tipo}} {{documento_numero}}</p>               
          </td>
          <td style="text-align:right;"  width="70px">
               {{qr}}
          </td>
     </tr>
</table>
<hr class="color_secundario">
<table  class="tabla_cabecera" border=0>
     <tr>
          <td width="50%">
               <span class="texto-negrita color_principal">FECHA:</span>
               <br>{{documento_fecha}}
               <br>
               <br>
               <span class="texto-negrita color_principal">DE:</span>
               <br>{{empresa_razonsocial}}
               <br>{{empresa_nombre_comercial}}
               <br>{{empresa_nif}}
               <br>{{empresa_telefono}}
               <br>{{empresa_correo}}
               <br>{{empresa_direccion}}
          </td>
          <td width="50%" style="vertical-align: baseline;">
               <span class="texto-negrita color_principal">PARA: </span>
               <br>{{cliente_nombre}}
               <br>{{cliente_nif}}
               <br>{{cliente_telefono}}
               <br>{{cliente_email}}
               <br>{{cliente_direccion}}
          </td>
     </tr>
</table>


<table class='tabla2'>
     <thead >
     <tr>
               <th style="text-align:center;">PROYECTO</th>
               <th style="text-align:center">TRABAJO</th>
               <th style="text-align:center">CONDICIONES <br>DE PAGO</th>
               <th style="text-align:center">FECHA DE <br>VENCIMIENTO</th>
     </tr>
     </thead>
     <tbody>
          <tr >
               <td width="28%" style="border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero">{{documento_proyecto}}</td>
               <td width="28%" style="border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero"></td>
               <td width="28%" style="border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero">{{documento_formapago}}</td>
               <td width="16%" style="text-align:center; border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero">
                    {{documento_vencimiento}}
               </td>
          </tr>
    <tbody>
</table>

<br>
<br>
<div style='position: fixed; top: 112; left: -15; rotate: -90; text-align: left; width: 100%; font-size: 12px; border:1px solid;padding:5px;margin-left:-30px;'>
     {{texto_lateral}}
</div>
<table class='tabla3' >
     <thead>
          <tr>
               <th width='10%' style='text-align:center'>CANT.</th>
               <th width='40%' style='text-align:center'>DESCRIPCIÓN</th>
               <th style='text-align:center'>PRECIO POR UNIDAD</th>
               <th style='text-align:center'>DESCUENTO</th>
               <th style='text-align:center'>IMPUESTOS</th>
               <th style='text-align:center'>TOTAL DE LÍNEA</th>
          </tr>
     </thead>
     <tbody>
          {{documento_detalle}}
          {{documento_adicionales}}
          
          <tr>
               <td colspan=5 style='text-align:right;font-weight:bold'>BASE IMPONIBLE</td>
               <td style='text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;' >{{documento_subtotal_pre_retencion}}</td>
          </tr>
          
          {{documento_irpf}}
          
          {{documento_ivas}}

          {{documento_re}}

          <tr>
               <td colspan=5 style='text-align:right;font-weight:bold'>TOTAL</td>
               <td style='text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;'>{{documento_total}}</td>
          </tr>

     </tbody>
</table>

<div>
     Contacto: {{documento_dato_contacto}}
</div>

<div class='observaciones'>
     <span class='texto-negrita'>Número de Cuenta: {{documento_nro_cuenta}}</span>
</div>
{{footer_detalle}}
<div class="observaciones">
     <p>
          {{footer_text}}
     </p>
</div>.tabla2 {
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
}', 1, NOW(), NULL, 0, NULL, NULL);
SET @entidad = 4;
INSERT INTO `fi_europa_documento_plantilla` (`entidad`, `plantilla_html`, `plantilla_css`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES(@entidad, '<table>
     <tr>
          <td style="text-align:left;" width="630px">
               <p class="color_principal" style="font-size:35px;margin-bottom:0px;">{{documento_tipo}} {{documento_numero}}</p>               
          </td>
          <td style="text-align:right;"  width="70px">
               {{qr}}
          </td>
     </tr>
</table>
<hr class="color_secundario">
<table  class="tabla_cabecera" border=0>
     <tr>
          <td width="50%">
               <span class="texto-negrita color_principal">FECHA:</span>
               <br>{{documento_fecha}}
               <br>
               <br>
               <span class="texto-negrita color_principal">DE:</span>
               <br>{{empresa_razonsocial}}
               <br>{{empresa_nombre_comercial}}
               <br>{{empresa_nif}}
               <br>{{empresa_telefono}}
               <br>{{empresa_correo}}
               <br>{{empresa_direccion}}
          </td>
          <td width="50%" style="vertical-align: baseline;">
               <span class="texto-negrita color_principal">PARA: </span>
               <br>{{cliente_nombre}}
               <br>{{cliente_nif}}
               <br>{{cliente_telefono}}
               <br>{{cliente_email}}
               <br>{{cliente_direccion}}
          </td>
     </tr>
</table>


<table class='tabla2'>
     <thead >
     <tr>
               <th style="text-align:center;">PROYECTO</th>
               <th style="text-align:center">TRABAJO</th>
               <th style="text-align:center">CONDICIONES <br>DE PAGO</th>
               <th style="text-align:center">FECHA DE <br>VENCIMIENTO</th>
     </tr>
     </thead>
     <tbody>
          <tr >
               <td width="28%" style="border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero">{{documento_proyecto}}</td>
               <td width="28%" style="border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero"></td>
               <td width="28%" style="border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero">{{documento_formapago}}</td>
               <td width="16%" style="text-align:center; border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero">
                    {{documento_vencimiento}}
               </td>
          </tr>
    <tbody>
</table>

<br>
<br>
<div style='position: fixed; top: 112; left: -15; rotate: -90; text-align: left; width: 100%; font-size: 12px; border:1px solid;padding:5px;margin-left:-30px;'>
     {{texto_lateral}}
</div>
<table class='tabla3' >
     <thead>
          <tr>
               <th width='10%' style='text-align:center'>CANT.</th>
               <th width='40%' style='text-align:center'>DESCRIPCIÓN</th>
               <th style='text-align:center'>PRECIO POR UNIDAD</th>
               <th style='text-align:center'>DESCUENTO</th>
               <th style='text-align:center'>IMPUESTOS</th>
               <th style='text-align:center'>TOTAL DE LÍNEA</th>
          </tr>
     </thead>
     <tbody>
          {{documento_detalle}}
          {{documento_adicionales}}
          
          <tr>
               <td colspan=5 style='text-align:right;font-weight:bold'>BASE IMPONIBLE</td>
               <td style='text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;' >{{documento_subtotal_pre_retencion}}</td>
          </tr>
          
          {{documento_irpf}}
          
          {{documento_ivas}}

          {{documento_re}}

          <tr>
               <td colspan=5 style='text-align:right;font-weight:bold'>TOTAL</td>
               <td style='text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;'>{{documento_total}}</td>
          </tr>

     </tbody>
</table>

<div>
     Contacto: {{documento_dato_contacto}}
</div>

<div class='observaciones'>
     <span class='texto-negrita'>Número de Cuenta: {{documento_nro_cuenta}}</span>
</div>
{{footer_detalle}}
<div class="observaciones">
     <p>
          {{footer_text}}
     </p>
</div>.tabla2 {
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
}', 1, NOW(), NULL, 0, NULL, NULL);
SET @entidad = 5;
INSERT INTO `fi_europa_documento_plantilla` (`entidad`, `plantilla_html`, `plantilla_css`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES(@entidad, '<table>
     <tr>
          <td style="text-align:left;" width="630px">
               <p class="color_principal" style="font-size:35px;margin-bottom:0px;">{{documento_tipo}} {{documento_numero}}</p>               
          </td>
          <td style="text-align:right;"  width="70px">
               {{qr}}
          </td>
     </tr>
</table>
<hr class="color_secundario">
<table  class="tabla_cabecera" border=0>
     <tr>
          <td width="50%">
               <span class="texto-negrita color_principal">FECHA:</span>
               <br>{{documento_fecha}}
               <br>
               <br>
               <span class="texto-negrita color_principal">DE:</span>
               <br>{{empresa_razonsocial}}
               <br>{{empresa_nombre_comercial}}
               <br>{{empresa_nif}}
               <br>{{empresa_telefono}}
               <br>{{empresa_correo}}
               <br>{{empresa_direccion}}
          </td>
          <td width="50%" style="vertical-align: baseline;">
               <span class="texto-negrita color_principal">PARA: </span>
               <br>{{cliente_nombre}}
               <br>{{cliente_nif}}
               <br>{{cliente_telefono}}
               <br>{{cliente_email}}
               <br>{{cliente_direccion}}
          </td>
     </tr>
</table>


<table class='tabla2'>
     <thead >
     <tr>
               <th style="text-align:center;">PROYECTO</th>
               <th style="text-align:center">TRABAJO</th>
               <th style="text-align:center">CONDICIONES <br>DE PAGO</th>
               <th style="text-align:center">FECHA DE <br>VENCIMIENTO</th>
     </tr>
     </thead>
     <tbody>
          <tr >
               <td width="28%" style="border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero">{{documento_proyecto}}</td>
               <td width="28%" style="border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero"></td>
               <td width="28%" style="border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero">{{documento_formapago}}</td>
               <td width="16%" style="text-align:center; border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero">
                    {{documento_vencimiento}}
               </td>
          </tr>
    <tbody>
</table>

<br>
<br>
<div style='position: fixed; top: 112; left: -15; rotate: -90; text-align: left; width: 100%; font-size: 12px; border:1px solid;padding:5px;margin-left:-30px;'>
     {{texto_lateral}}
</div>
<table class='tabla3' >
     <thead>
          <tr>
               <th width='10%' style='text-align:center'>CANT.</th>
               <th width='40%' style='text-align:center'>DESCRIPCIÓN</th>
               <th style='text-align:center'>PRECIO POR UNIDAD</th>
               <th style='text-align:center'>DESCUENTO</th>
               <th style='text-align:center'>IMPUESTOS</th>
               <th style='text-align:center'>TOTAL DE LÍNEA</th>
          </tr>
     </thead>
     <tbody>
          {{documento_detalle}}
          {{documento_adicionales}}
          
          <tr>
               <td colspan=5 style='text-align:right;font-weight:bold'>BASE IMPONIBLE</td>
               <td style='text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;' >{{documento_subtotal_pre_retencion}}</td>
          </tr>
          
          {{documento_irpf}}
          
          {{documento_ivas}}

          {{documento_re}}

          <tr>
               <td colspan=5 style='text-align:right;font-weight:bold'>TOTAL</td>
               <td style='text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;'>{{documento_total}}</td>
          </tr>

     </tbody>
</table>

<div>
     Contacto: {{documento_dato_contacto}}
</div>

<div class='observaciones'>
     <span class='texto-negrita'>Número de Cuenta: {{documento_nro_cuenta}}</span>
</div>
{{footer_detalle}}
<div class="observaciones">
     <p>
          {{footer_text}}
     </p>
</div>.tabla2 {
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
}', 1, NOW(), NULL, 0, NULL, NULL);
SET @entidad = 8;
INSERT INTO `fi_europa_documento_plantilla` (`entidad`, `plantilla_html`, `plantilla_css`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES(@entidad, '<table>
     <tr>
          <td style="text-align:left;" width="630px">
               <p class="color_principal" style="font-size:35px;margin-bottom:0px;">{{documento_tipo}} {{documento_numero}}</p>               
          </td>
          <td style="text-align:right;"  width="70px">
               {{qr}}
          </td>
     </tr>
</table>
<hr class="color_secundario">
<table  class="tabla_cabecera" border=0>
     <tr>
          <td width="50%">
               <span class="texto-negrita color_principal">FECHA:</span>
               <br>{{documento_fecha}}
               <br>
               <br>
               <span class="texto-negrita color_principal">DE:</span>
               <br>{{empresa_razonsocial}}
               <br>{{empresa_nombre_comercial}}
               <br>{{empresa_nif}}
               <br>{{empresa_telefono}}
               <br>{{empresa_correo}}
               <br>{{empresa_direccion}}
          </td>
          <td width="50%" style="vertical-align: baseline;">
               <span class="texto-negrita color_principal">PARA: </span>
               <br>{{cliente_nombre}}
               <br>{{cliente_nif}}
               <br>{{cliente_telefono}}
               <br>{{cliente_email}}
               <br>{{cliente_direccion}}
          </td>
     </tr>
</table>


<table class='tabla2'>
     <thead >
     <tr>
               <th style="text-align:center;">PROYECTO</th>
               <th style="text-align:center">TRABAJO</th>
               <th style="text-align:center">CONDICIONES <br>DE PAGO</th>
               <th style="text-align:center">FECHA DE <br>VENCIMIENTO</th>
     </tr>
     </thead>
     <tbody>
          <tr >
               <td width="28%" style="border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero">{{documento_proyecto}}</td>
               <td width="28%" style="border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero"></td>
               <td width="28%" style="border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero">{{documento_formapago}}</td>
               <td width="16%" style="text-align:center; border:1px solid #82b29c;border-bottom:3px solid #82b29c;" class="color_tercero">
                    {{documento_vencimiento}}
               </td>
          </tr>
    <tbody>
</table>

<br>
<br>
<div style='position: fixed; top: 112; left: -15; rotate: -90; text-align: left; width: 100%; font-size: 12px; border:1px solid;padding:5px;margin-left:-30px;'>
     {{texto_lateral}}
</div>
<table class='tabla3' >
     <thead>
          <tr>
               <th width='10%' style='text-align:center'>CANT.</th>
               <th width='40%' style='text-align:center'>DESCRIPCIÓN</th>
               <th style='text-align:center'>PRECIO POR UNIDAD</th>
               <th style='text-align:center'>DESCUENTO</th>
               <th style='text-align:center'>IMPUESTOS</th>
               <th style='text-align:center'>TOTAL DE LÍNEA</th>
          </tr>
     </thead>
     <tbody>
          {{documento_detalle}}
          {{documento_adicionales}}
          
          <tr>
               <td colspan=5 style='text-align:right;font-weight:bold'>BASE IMPONIBLE</td>
               <td style='text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;' >{{documento_subtotal_pre_retencion}}</td>
          </tr>
          
          {{documento_irpf}}
          
          {{documento_ivas}}

          {{documento_re}}

          <tr>
               <td colspan=5 style='text-align:right;font-weight:bold'>TOTAL</td>
               <td style='text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;'>{{documento_total}}</td>
          </tr>

     </tbody>
</table>

<div>
     Contacto: {{documento_dato_contacto}}
</div>

<div class='observaciones'>
     <span class='texto-negrita'>Número de Cuenta: {{documento_nro_cuenta}}</span>
</div>
{{footer_detalle}}
<div class="observaciones">
     <p>
          {{footer_text}}
     </p>
</div>.tabla2 {
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
}', 1, NOW(), NULL, 0, NULL, NULL);