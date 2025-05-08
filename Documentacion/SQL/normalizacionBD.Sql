
SET @entidad = ENTIDA_AQUI;


INSERT INTO fi_configuracion_empresa_modulos_activos (fk_entidad, fk_modulo)
SELECT @entidad , fk_modulo FROM fi_configuracion_empresa_modulos_activos WHERE fk_entidad = 1;



INSERT INTO `diccionario_bancos` ( `entidad`, `nombre_banco`, `activo`, `creado_fecha`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`) VALUES
	( @entidad, 'Banco Santander', 1, NULL, 0, NULL, NULL),
	( @entidad, 'Banco Sabadell', 1, NULL, 0, NULL, NULL),
	( @entidad, 'CaixaBank', 1, NULL, 0, NULL, NULL),
	( @entidad, 'BBVA', 1, NULL, 0, NULL, NULL);


INSERT INTO `diccionario_clientes_categorias` ( `entidad`, `label`, `estado`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`) VALUES
	(@entidad, 'Autónomos.', 1, '2025-02-06 17:02:09', 8, 0, NULL, NULL),
	(@entidad, 'PYMES', 1, '2025-02-06 17:02:17', 8, 0, NULL, NULL),
	(@entidad, 'Párticulares.', 1, '2025-02-06 17:02:41', 8, 0, NULL, NULL),
	(@entidad, 'Grandes empresas', 1, '2025-02-06 18:24:41', 8, 0, NULL, NULL);



-- Dumping data for table prod_factuguay_facturas_v2.diccionario_impuestos: ~28 rows (approximately)
INSERT INTO `diccionario_impuestos` ( `entidad`, `impuesto`, `recargo_equivalencia`, `impuesto_texto`, `pais`, `autogen`, `activo`) VALUES

	( @entidad , 21.0, 5.2, 'General', NULL, 0, 1),
	( @entidad , 10.0, 1.4, 'Reducido', NULL, 0, 1),
	( @entidad , 4.0, 0.5, 'Super Reducido', NULL, 0, 1),
	( @entidad , 0.0, 0.0, 'Exento', NULL, 0, 1);



INSERT INTO `diccionario_catalogo` ( `entidad`, `codigo`, `detalle`, `tipo`, `activo`, `creado_fecha`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`, `creado_fk_usuario`) VALUES
	(@entidad , 'und', 'Unidades', 1, 1, '2024-09-30 10:59:16', 0, NULL, NULL, 1),
	(@entidad , 'Gramos', 1, 1, '2024-09-30 10:59:28', 0, '2025-02-05 23:59:38', 2, 1),
	(@entidad , 'kilogramos', 1, 1, '2024-09-30 11:00:02', 0, '2025-02-05 23:52:12', 2, 1),
	(@entidad , 'Servicios profesionales', 2, 1, '2024-09-30 11:09:08', 0, NULL, NULL, 1);




INSERT INTO `diccionario_crm_oportunidades_categorias` ( `entidad`, `etiqueta`, `prioridad`, `estilo`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES
	(@entidad , 'RRSS', 3, 'info', 1, '2025-02-10 10:31:11', 3, 0, NULL, NULL),
	(@entidad , 'Otros', 2, 'warning', 1, '2025-02-10 10:31:52', 3, 0, NULL, NULL),
	(@entidad , 'Referencia Boca a Boca', 2, 'warning', 1, '2025-02-10 10:32:10', 3, 0, NULL, NULL),
	(@entidad , 'BNI', 3, 'danger', 1, '2025-02-10 10:32:38', 3, 0, NULL, NULL);

-- Dumping data for table prod_factuguay_facturas_v2.diccionario_crm_oportunidades_prioridades: ~15 rows (approximately)
INSERT INTO `diccionario_crm_oportunidades_prioridades` (`entidad`, `etiqueta`, `prioridad`, `estilo`, `activo`, `borrado`, `creado_fecha`, `creado_fk_usuario`, `borrado_fecha_usuario`, `borrado_fk_usuario`) VALUES
	(@entidad , 'ALTA', 1, 'Danger', 1, 0, NULL, NULL, NULL, NULL),
	(@entidad , 'MEDIA', 2, 'Warning', 1, 0, NULL, NULL, NULL, NULL),
	(@entidad , 'BAJA', 3, 'Success', 1, 0, NULL, NULL, NULL, NULL);

-- Dumping data for table prod_factuguay_facturas_v2.fi_bodegas: ~4 rows (approximately)
INSERT INTO `fi_bodegas` ( `tipo`, `label`, `nota`, `principal_facturar`, `bodega_defecto`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`, `entidad`) VALUES
	( NULL, '1', 'Almacen ', 0, 0, 1, '2025-02-06 01:12:52', 1, 0, NULL, NULL, @entidad);



  



INSERT INTO `diccionario_crm_actividades` ( `entidad`, `nombre`, `color`, `icono`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado_fecha`, `borrado_fk_usuario`, `borrado`) VALUES
	( @entidad, 'Llamada Telefónica', '#10774F', 'fas fa-phone', 1, '2025-02-27 06:25:22', 13, NULL, NULL, 0),
	( @entidad, 'Enviar Email', '#ffffff', 'fas fa-envelope', 1, '2025-02-27 06:25:38', 13, NULL, NULL, 0),
	( @entidad, 'Reunión Por Teams', '#ffffff', 'fas fa-user', 1, '2025-02-27 06:25:56', 13, NULL, NULL, 0),
	( @entidad, 'Visita en Sitio', '#0718DB', 'fas fa-home', 1, '2025-02-27 06:26:11', 13, NULL, NULL, 0);
	


  INSERT INTO `diccionario_categorias` ( `entidad`, `activo`, `creado_fecha`, `label`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`, `fk_parent`) VALUES
	( @entidad, 1, '2024-11-19 18:52:04', 'Categoria 1 ',  1, 1, '2024-12-25 12:18:34', 2, NULL),
	( @entidad, 1, '2024-11-19 19:10:38', 'Categoria 2' ,  1, 1, '2024-12-25 12:18:08', 2, NULL);







INSERT INTO `fi_configuracion` (`entidad`, `configuracion`, `valor`, `activo`, `creado_fecha`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`, `creado_fk_usuario`) VALUES
	(@entidad, ''quickbooks_modo'', ''development'', 1, NULL, 0, NULL, NULL, NULL),
	(@entidad, ''moneda_base'', ''EUR'', 1, NULL, 0, NULL, NULL, NULL),
	(@entidad, ''quickbooks_client_id'', ''AB6YDIes5ItZ2GwYNt1RlJHHxTlrm@entidadSJMxPZaVWEqN8iXABIOm'', 1, NULL, 0, NULL, NULL, NULL),
	(@entidad, ''quickbooks_client_secret'', ''B5m1hBRNyyu8XgpQzxhgvDDf56Rh2RFGNO38vZGS'', 1, NULL, 0, NULL, NULL, NULL),
	(@entidad, ''PDF_DATO_CONTACTO'', ''jcmm@ice.co.cr - www.avantecds.eu - +506 88493002'', 1, NOW(), 0, NULL, NULL, NULL),
	(@entidad, ''PDF_FOOTER_TEXT'', ''Le recordamos que los datos incorporados en este documento y que nos han sido facilitado en su momento forman parte de un fichero con Datos de\nCarácter Personal cuyo responsable es Compañia para QA .S.A.. con el domicilio social en C/ CUENCA2, 292 PLANTA 22, PUERTA A 244032-LEÓN\ndonde Ud. Podrá ejecutar en todo momento losderechos de acceso, modificación, cancelación o en su caso el de opocición.'', 1, NOW(), 0, NULL, NULL, NULL),
	(@entidad, ''PDF_NUMERO_DE_CUENTA'', ''BBVAESMM 018 0688 12 02008001771\nIBAN ES123456789890123456789011\nSWIFT (BIC) BBVAESMM\nABA CODE BBVAESMM 0182 0688 12323465566772\n'', 1, NOW(), 0, NULL, NULL, NULL),
	(@entidad, ''TEXTO_LATERAL_PDF'', ''INSCRITA EN EL REGISTRO MERCANTIL DE Alajuela EN EL TOMO 1.0025, FOLIO 25, HOJA AL-28-525'', 1, NOW(), 0, NULL, NULL, NULL),
	(@entidad, ''sistema_transacciones_multimoneda'', ''0'', 1, NOW(), 0, NULL, NULL, NULL),
	(@entidad, ''sistema_transacciones_fk_moneda'', ''13'', 1, NOW(), 0, NULL, NULL, NULL),
	(@entidad, ''email_body_factura'', ''<!DOCTYPE html>\r\n<html lang="es">\r\n<head>\r\n    <meta charset="UTF-8">\r\n    <meta name="viewport" content="width=device-width, initial-scale=1.0">\r\n    <style>\r\n        body {\r\n            font-family: Arial, sans-serif;\r\n            line-height: 1.6;\r\n            max-width: 800px;\r\n            margin: 20px auto;\r\n            padding: 0 20px;\r\n            color: #000;\r\n        }\r\n\r\n        h1 {\r\n            color: #0066cc;\r\n            margin-bottom: 20px;\r\n        }\r\n\r\n        .details {\r\n            margin: 20px 0;\r\n        }\r\n\r\n        .details-list {\r\n            list-style-type: none;\r\n            padding-left: 20px;\r\n        }\r\n\r\n        .contact-info {\r\n            margin-top: 30px;\r\n        }\r\n\r\n        .footer {\r\n            margin-top: 40px;\r\n            font-size: 0.9em;\r\n            color: #0066cc;\r\n        }\r\n\r\n        .footer a {\r\n            color: #0066cc;\r\n            text-decoration: none;\r\n        }\r\n\r\n        .footer a:hover {\r\n            text-decoration: underline;\r\n        }\r\n    </style>\r\n</head>\r\n<body>\r\n\r\n    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>\r\n\r\n    <p>Adjunto a este correo encontrará la factura electrónica en formato PDF correspondiente al servicio/producto ofrecido el [Fecha]. Por favor, revise detenidamente la información contenida en la factura y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>\r\n\r\n    <div class="details">\r\n        <p><strong>Detalles principales de la factura:</strong></p>\r\n        <ul class="details-list">\r\n            <li>- Número de [tipo_documento]: [Número de documento]</li>\r\n            <li>- Fecha de emisión: [Fecha]</li>\r\n            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>\r\n            <li>- Importe total: [Importe en euros]</li>\r\n        </ul>\r\n    </div>\r\n\r\n    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>\r\n\r\n    <p>Saludos cordiales,</p>\r\n\r\n    <div class="contact-info">\r\n        <p>[Nombre de tu empresa]<br>\r\n        [Dirección de tu empresa]<br>\r\n        <a href="tel:[Teléfono]">[Teléfono]</a><br>\r\n        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>\r\n    </div>\r\n\r\n    <div class="footer">\r\n        <p>Factura emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>\r\n        <a href="https://factuguay.es/">https://factuguay.es/</a><br>\r\n        <a href="tel:+34630745478">+34 630 74 54 78</a></p>\r\n    </div>\r\n</body>\r\n</html>'', 1, NOW(), 0, NULL, NULL, NULL),
	(@entidad, ''email_body_Presupuesto'', ''<!DOCTYPE html>\r\n<html lang="es">\r\n<head>\r\n    <meta charset="UTF-8">\r\n    <meta name="viewport" content="width=device-width, initial-scale=1.0">\r\n    <style>\r\n        body {\r\n            font-family: Arial, sans-serif;\r\n            line-height: 1.6;\r\n            max-width: 800px;\r\n            margin: 20px auto;\r\n            padding: 0 20px;\r\n            color: #000;\r\n        }\r\n\r\n        h1 {\r\n            color: #0066cc;\r\n            margin-bottom: 20px;\r\n        }\r\n\r\n        .details {\r\n            margin: 20px 0;\r\n        }\r\n\r\n        .details-list {\r\n            list-style-type: none;\r\n            padding-left: 20px;\r\n        }\r\n\r\n        .contact-info {\r\n            margin-top: 30px;\r\n        }\r\n\r\n        .footer {\r\n            margin-top: 40px;\r\n            font-size: 0.9em;\r\n            color: #0066cc;\r\n        }\r\n\r\n        .footer a {\r\n            color: #0066cc;\r\n            text-decoration: none;\r\n        }\r\n\r\n        .footer a:hover {\r\n            text-decoration: underline;\r\n        }\r\n    </style>\r\n</head>\r\n<body>\r\n\r\n    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>\r\n\r\n    <p>Adjunto a este correo encontrará el presupuesto en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles</p>\r\n\r\n    <div class="details">\r\n        <p><strong>Detalles principales de la Presupuesto:</strong></p>\r\n        <ul class="details-list">\r\n            <li>- Número de [tipo_documento]: [Número de documento]</li>\r\n            <li>- Fecha de emisión: [Fecha]</li>\r\n            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>\r\n            <li>- Importe total: [Importe en euros]</li>\r\n        </ul>\r\n    </div>\r\n\r\n    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>\r\n\r\n    <p>Saludos cordiales,</p>\r\n\r\n    <div class="contact-info">\r\n        <p>[Nombre de tu empresa]<br>\r\n        [Dirección de tu empresa]<br>\r\n        <a href="tel:[Teléfono]">[Teléfono]</a><br>\r\n        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>\r\n    </div>\r\n\r\n    <div class="footer">\r\n        <p>Presupuesto emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>\r\n        <a href="https://factuguay.es/">https://factuguay.es/</a><br>\r\n        <a href="tel:+34630745478">+34 630 74 54 78</a></p>\r\n    </div>\r\n</body>\r\n</html>'', 1, NOW(), 0, NULL, NULL, NULL),
	(@entidad, ''email_body_pedido'', ''<!DOCTYPE html>\r\n<html lang="es">\r\n<head>\r\n    <meta charset="UTF-8">\r\n    <meta name="viewport" content="width=device-width, initial-scale=1.0">\r\n    <style>\r\n        body {\r\n            font-family: Arial, sans-serif;\r\n            line-height: 1.6;\r\n            max-width: 800px;\r\n            margin: 20px auto;\r\n            padding: 0 20px;\r\n            color: #000;\r\n        }\r\n\r\n        h1 {\r\n            color: #0066cc;\r\n            margin-bottom: 20px;\r\n        }\r\n\r\n        .details {\r\n            margin: 20px 0;\r\n        }\r\n\r\n        .details-list {\r\n            list-style-type: none;\r\n            padding-left: 20px;\r\n        }\r\n\r\n        .contact-info {\r\n            margin-top: 30px;\r\n        }\r\n\r\n        .footer {\r\n            margin-top: 40px;\r\n            font-size: 0.9em;\r\n            color: #0066cc;\r\n        }\r\n\r\n        .footer a {\r\n            color: #0066cc;\r\n            text-decoration: none;\r\n        }\r\n\r\n        .footer a:hover {\r\n            text-decoration: underline;\r\n        }\r\n    </style>\r\n</head>\r\n<body>\r\n\r\n    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>\r\n\r\n    <p>Adjunto a este correo encontrará el pedido registrado en nuestro sistema en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>\r\n\r\n    <div class="details">\r\n        <p><strong>Detalles principales de la Pedido:</strong></p>\r\n        <ul class="details-list">\r\n            <li>- Número de [tipo_documento]: [Número de documento]</li>\r\n            <li>- Fecha de emisión: [Fecha]</li>\r\n            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>\r\n            <li>- Importe total: [Importe en euros]</li>\r\n        </ul>\r\n    </div>\r\n\r\n    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>\r\n\r\n    <p>Saludos cordiales,</p>\r\n\r\n    <div class="contact-info">\r\n        <p>[Nombre de tu empresa]<br>\r\n        [Dirección de tu empresa]<br>\r\n        <a href="tel:[Teléfono]">[Teléfono]</a><br>\r\n        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>\r\n    </div>\r\n\r\n    <div class="footer">\r\n        <p>Pedido emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>\r\n        <a href="https://factuguay.es/">https://factuguay.es/</a><br>\r\n        <a href="tel:+34630745478">+34 630 74 54 78</a></p>\r\n    </div>\r\n</body>\r\n</html>'', 1, NOW(), 0, NULL, NULL, NULL),
	(@entidad, ''email_body_albaran_compra'', ''<!DOCTYPE html>\r\n<html lang="es">\r\n<head>\r\n    <meta charset="UTF-8">\r\n    <meta name="viewport" content="width=device-width, initial-scale=1.0">\r\n    <title>Albarán</title>\r\n    <style>\r\n        body {\r\n            font-family: Arial, sans-serif;\r\n            line-height: 1.6;\r\n            max-width: 800px;\r\n            margin: 20px auto;\r\n            padding: 0 20px;\r\n            color: #000;\r\n        }\r\n\r\n        h1 {\r\n            color: #0066cc;\r\n            margin-bottom: 20px;\r\n        }\r\n\r\n        .details {\r\n            margin: 20px 0;\r\n        }\r\n\r\n        .details-list {\r\n            list-style-type: none;\r\n            padding-left: 20px;\r\n        }\r\n\r\n        .contact-info {\r\n            margin-top: 30px;\r\n        }\r\n\r\n        .footer {\r\n            margin-top: 40px;\r\n            font-size: 0.9em;\r\n            color: #0066cc;\r\n        }\r\n\r\n        .footer a {\r\n            color: #0066cc;\r\n            text-decoration: none;\r\n        }\r\n\r\n        .footer a:hover {\r\n            text-decoration: underline;\r\n        }\r\n    </style>\r\n</head>\r\n<body>\r\n\r\n    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>\r\n\r\n    <p>Adjunto a este correo encontrará el albarán de ventas registrado en nuestro sistema en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>\r\n\r\n    <div class="details">\r\n        <p><strong>Detalles principales de la Albarán:</strong></p>\r\n        <ul class="details-list">\r\n            <li>- Número de [tipo_documento]: [Número de documento]</li>\r\n            <li>- Fecha de emisión: [Fecha]</li>\r\n            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>\r\n            <li>- Importe total: [Importe en euros]</li>\r\n        </ul>\r\n    </div>\r\n\r\n    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>\r\n\r\n    <p>Saludos cordiales,</p>\r\n\r\n    <div class="contact-info">\r\n        <p>[Nombre de tu empresa]<br>\r\n        [Dirección de tu empresa]<br>\r\n        <a href="tel:[Teléfono]">[Teléfono]</a><br>\r\n        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>\r\n    </div>\r\n\r\n    <div class="footer">\r\n        <p>Albarán emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>\r\n        <a href="https://factuguay.es/">https://factuguay.es/</a><br>\r\n        <a href="tel:+34630745478">+34 630 74 54 78</a></p>\r\n    </div>\r\n</body>\r\n</html>'', 1, NOW(), 0, NULL, NULL, NULL),
	(@entidad, ''email_body_albaran_venta'', ''<!DOCTYPE html>\r\n<html lang="es">\r\n<head>\r\n    <meta charset="UTF-8">\r\n    <meta name="viewport" content="width=device-width, initial-scale=1.0">\r\n    <title>Albarán</title>\r\n    <style>\r\n        body {\r\n            font-family: Arial, sans-serif;\r\n            line-height: 1.6;\r\n            max-width: 800px;\r\n            margin: 20px auto;\r\n            padding: 0 20px;\r\n            color: #000;\r\n        }\r\n\r\n        h1 {\r\n            color: #0066cc;\r\n            margin-bottom: 20px;\r\n        }\r\n\r\n        .details {\r\n            margin: 20px 0;\r\n        }\r\n\r\n        .details-list {\r\n            list-style-type: none;\r\n            padding-left: 20px;\r\n        }\r\n\r\n        .contact-info {\r\n            margin-top: 30px;\r\n        }\r\n\r\n        .footer {\r\n            margin-top: 40px;\r\n            font-size: 0.9em;\r\n            color: #0066cc;\r\n        }\r\n\r\n        .footer a {\r\n            color: #0066cc;\r\n            text-decoration: none;\r\n        }\r\n\r\n        .footer a:hover {\r\n            text-decoration: underline;\r\n        }\r\n    </style>\r\n</head>\r\n<body>\r\n\r\n    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>\r\n\r\n    <p>Adjunto a este correo encontrará el albarán de ventas registrado en nuestro sistema en formato PDF. Por favor, revise detenidamente la información contenida y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>\r\n\r\n    <div class="details">\r\n        <p><strong>Detalles principales de la Albarán:</strong></p>\r\n        <ul class="details-list">\r\n            <li>- Número de [tipo_documento]: [Número de documento]</li>\r\n            <li>- Fecha de emisión: [Fecha]</li>\r\n            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>\r\n            <li>- Importe total: [Importe en euros]</li>\r\n        </ul>\r\n    </div>\r\n\r\n    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>\r\n\r\n    <p>Saludos cordiales,</p>\r\n\r\n    <div class="contact-info">\r\n        <p>[Nombre de tu empresa]<br>\r\n        [Dirección de tu empresa]<br>\r\n        <a href="tel:[Teléfono]">[Teléfono]</a><br>\r\n        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>\r\n    </div>\r\n\r\n    <div class="footer">\r\n        <p>Albarán emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>\r\n        <a href="https://factuguay.es/">https://factuguay.es/</a><br>\r\n        <a href="tel:+34630745478">+34 630 74 54 78</a></p>\r\n    </div>\r\n</body>\r\n</html>'', 1, NOW(), 0, NULL, NULL, NULL),
	(@entidad, ''email_body_compra'', ''<!DOCTYPE html>\r\n<html lang="es">\r\n<head>\r\n    <meta charset="UTF-8">\r\n    <meta name="viewport" content="width=device-width, initial-scale=1.0">\r\n    <title>Compra</title>\r\n    <style>\r\n        body {\r\n            font-family: Arial, sans-serif;\r\n            line-height: 1.6;\r\n            max-width: 800px;\r\n            margin: 20px auto;\r\n            padding: 0 20px;\r\n            color: #000;\r\n        }\r\n\r\n        h1 {\r\n            color: #0066cc;\r\n            margin-bottom: 20px;\r\n        }\r\n\r\n        .details {\r\n            margin: 20px 0;\r\n        }\r\n\r\n        .details-list {\r\n            list-style-type: none;\r\n            padding-left: 20px;\r\n        }\r\n\r\n        .contact-info {\r\n            margin-top: 30px;\r\n        }\r\n\r\n        .footer {\r\n            margin-top: 40px;\r\n            font-size: 0.9em;\r\n            color: #0066cc;\r\n        }\r\n\r\n        .footer a {\r\n            color: #0066cc;\r\n            text-decoration: none;\r\n        }\r\n\r\n        .footer a:hover {\r\n            text-decoration: underline;\r\n        }\r\n    </style>\r\n</head>\r\n<body>\r\n\r\n    <p><strong>Estimado/a [Nombre del Cliente]:</strong></p>\r\n\r\n    <p>Adjunto a este correo encontrará la Compra electrónica en formato PDF correspondiente al servicio/producto ofrecido el [Fecha]. Por favor, revise detenidamente la información contenida en la Compra y no dude en ponerse en contacto con nosotros si tiene alguna pregunta o requiere más detalles.</p>\r\n\r\n    <div class="details">\r\n        <p><strong>Detalles principales de la Compra:</strong></p>\r\n        <ul class="details-list">\r\n            <li>- Número de [tipo_documento]: [Número de documento]</li>\r\n            <li>- Fecha de emisión: [Fecha]</li>\r\n            <li>- Fecha de vencimiento: [Fecha Vencimiento]</li>\r\n            <li>- Importe total: [Importe en euros]</li>\r\n        </ul>\r\n    </div>\r\n\r\n    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>\r\n\r\n    <p>Saludos cordiales,</p>\r\n\r\n    <div class="contact-info">\r\n        <p>[Nombre de tu empresa]<br>\r\n        [Dirección de tu empresa]<br>\r\n        <a href="tel:[Teléfono]">[Teléfono]</a><br>\r\n        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>\r\n    </div>\r\n\r\n    <div class="footer">\r\n        <p>Compra emitida mediante el sistema <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>\r\n        <a href="https://factuguay.es/">https://factuguay.es/</a><br>\r\n        <a href="tel:+34630745478">+34 630 74 54 78</a></p>\r\n    </div>\r\n</body>\r\n</html>'', 1, NOW(), 0, NULL, NULL, NULL);

INSERT INTO `fi_europa_documento_plantilla` (`entidad`, `plantilla_html`, `plantilla_css`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES
(@entidad, ''<table>
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


<table class="tabla2">
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
<div style="position: fixed; top: 112; left: -15; rotate: -90; text-align: left; width: 100%; font-size: 12px; border:1px solid;padding:5px;margin-left:-30px;">
     {{texto_lateral}}
</div>
<table class="tabla3" >
     <thead>
          <tr>
               <th width="10%" style="text-align:center">CANT.</th>
               <th width="40%" style="text-align:center">DESCRIPCIÓN</th>
               <th style="text-align:center">PRECIO POR UNIDAD</th>
               <th style="text-align:center">DESCUENTO</th>
               <th style="text-align:center">IMPUESTOS</th>
               <th style="text-align:center">TOTAL DE LÍNEA</th>
          </tr>
     </thead>
     <tbody>
          {{documento_detalle}}
          {{documento_adicionales}}
          
          <tr>
               <td colspan=5 style="text-align:right;font-weight:bold">BASE IMPONIBLE</td>
               <td style="text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;" >{{documento_subtotal_pre_retencion}}</td>
          </tr>
          
          {{documento_irpf}}
          
          {{documento_ivas}}

          {{documento_re}}

          <tr>
               <td colspan=5 style="text-align:right;font-weight:bold">TOTAL</td>
               <td style="text-align:right;border:1px solid #82b29c;border-bottom:3px solid #82b29c;">{{documento_total}}</td>
          </tr>

     </tbody>
</table>

<div>
     Contacto: {{documento_dato_contacto}}
</div>

<div class="observaciones">
     <span class="texto-negrita">Número de Cuenta: {{documento_nro_cuenta}}</span>
</div>
{{footer_detalle}}
<div class="observaciones">
     <p>
          {{footer_text}}
     </p>
</div>'',
''.tabla2 {
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
}'', 1, NOW(), NULL, 0, NULL, NULL);





ALTER TABLE `diccionario_extensiones_permitidas` CHANGE `extension` `extension` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
