CREATE TABLE diccionario_tipo_relacion (
	`rowid` INT NOT NULL AUTO_INCREMENT,
	`etiqueta` VARCHAR(50) NULL DEFAULT NULL,
	PRIMARY KEY (`rowid`)
)
COMMENT='Diccionario de tipos de relacion';

INSERT INTO diccionario_tipo_relacion (`rowid`, `etiqueta`) VALUES
	(1, 'Dueño'),
	(2, 'Gestor'),
	(3, 'Invitado');

ALTER TABLE sistema_empresa_usuarios
ADD CONSTRAINT fk_empresa_relacion
FOREIGN KEY (fk_tipo_relacion) REFERENCES diccionario_tipo_relacion(rowid);



CREATE TABLE `diccionario_configuracion` (
    `rowid` INT NOT NULL AUTO_INCREMENT,
	`configuracion` VARCHAR(50) NULL DEFAULT NULL,
	`valor` TEXT NULL DEFAULT NULL,
	PRIMARY KEY (`rowid`)	
)
COMMENT='Valores de configuración del sistema';

-- Email de Aprobacion o Rechazo
INSERT INTO `diccionario_configuracion` ( `configuracion`, `valor`) VALUES
	('email_user_activacion', '<!DOCTYPE html>\r\n<html lang="es">\r\n<head>\r\n    <meta charset="UTF-8">\r\n    <meta name="viewport" content="width=device-width, initial-scale=1.0">\r\n    <title>Correo Activación</title>\r\n    <style>\r\n        body {\r\n            font-family: Arial, sans-serif;\r\n            line-height: 1.6;\r\n            max-width: 800px;\r\n            margin: 20px auto;\r\n            padding: 0 20px;\r\n            color: #000;\r\n        }\r\n\r\n        h1 {\r\n            color: #0066cc;\r\n            margin-bottom: 20px;\r\n        }\r\n        .footer {\r\n            margin-top: 40px;\r\n            font-size: 0.9em;\r\n            color: #0066cc;\r\n        }\r\n        .footer a {\r\n            color: #0066cc;\r\n            text-decoration: none;\r\n        }\r\n        .footer a:hover {\r\n            text-decoration: underline;\r\n        }\r\n    </style>\r\n</head>\r\n<body>\r\n    <p><strong>Estimado/a usuario:</strong></p>\r\n\r\n    <p>Te comunicamos que se ha registrado un nuevo usuario en <strong>Factuguay</strong>:</p>\r\n    <p>NOMBRE: <strong>[nombre_usuario]</strong></p>\r\n    <p>CORREO: <strong>[correo_usuario]</strong></p>\r\n     <hr/>\r\n     <p>Puede <b>CONFIRMAR</b> el registro, dando click en <a href="[url_activacion]">ESTE ENLACE</a></p>\r\n     <br/>\r\n     <p>Sino, puede <b>RECHAZAR</b> al usuario dando click en <a href="[url_desactivacion]">ESTE ENLACE</a></p>\r\n     <hr/>\r\n    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>\r\n\r\n    <p>Saludos cordiales,</p>\r\n\r\n    <div class="contactinfo">\r\n       <strong>[Nombre de tu empresa]<strong><br>\r\n        [Dirección de tu empresa]<br>\r\n        <a href="tel:[Teléfono]">[Teléfono]</a><br>\r\n        <a href="mailto:[Correo electrónico]">[Correo electrónico]</a></p>\r\n    </div>\r\n\r\n    <div class="footer">\r\n        <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>\r\n        <a href="https://factuguay.es/">https://factuguay.es/</a><br>\r\n        <a href="tel:+34630745478">+34 630 74 54 78</a></p>\r\n    </div>\r\n</body>\r\n</html>'),
	('email_user_bienvenida', '<!DOCTYPE html>\r\n<html lang="es">\r\n<head>\r\n    <meta charset="UTF-8">\r\n    <meta name="viewport" content="width=device-width, initial-scale=1.0">\r\n    <title>Bienvenido a Factuguay</title>\r\n    <style>\r\n        body {\r\n            font-family: Arial, sans-serif;\r\n            line-height: 1.6;\r\n            max-width: 800px;\r\n            margin: 20px auto;\r\n            padding: 0 20px;\r\n            color: #000;\r\n        }\r\n\r\n        h1 {\r\n            color: #0066cc;\r\n            margin-bottom: 20px;\r\n        }\r\n        .footer {\r\n            margin-top: 40px;\r\n            font-size: 0.9em;\r\n            color: #0066cc;\r\n        }\r\n        .footer a {\r\n            color: #0066cc;\r\n            text-decoration: none;\r\n        }\r\n        .footer a:hover {\r\n            text-decoration: underline;\r\n        }\r\n    </style>\r\n</head>\r\n<body>\r\n    <p><strong>Estimado/a usuario:</strong></p>\r\n\r\n    <p>Te comunicamos que te han invitado a unirte a <strong>[Nombre de tu empresa]</strong>:</p>\r\n\r\n    <p>¿QUIEN INVITÓ?: <strong>[nombre_usuario_dueno]</strong></p>\r\n\r\n    <p>NOMBRE: <strong>[nombre_usuario]</strong></p>\r\n    <p>CORREO: <strong>[correo_usuario]</strong></p>\r\n    <p>CONTRASEÑA: <strong>[password_usuario]</strong></p>\r\n    <hr/>\r\n    <p>Agradecemos su confianza en nuestros servicios/productos. Estamos a su disposición para cualquier consulta adicional y esperamos seguir colaborando en el futuro.</p>\r\n\r\n    <p>Saludos cordiales,</p>\r\n\r\n    <br/>\r\n    [Nombre de tu empresa]<br/>\r\n     [Dirección de tu empresa]<br/>\r\n    <a href="tel:[Teléfono]">[Teléfono]</a><br/>\r\n    <a href="mailto:[Correo electrónico]">[Correo electrónico]</a>\r\n\r\n    <br/>\r\n\r\n    <div class="footer">\r\n        <a href="https://factuguay.es/">FactuGuay</a> de Avantec.DS SL<br>\r\n        <a href="https://factuguay.es/">https://factuguay.es/</a><br>\r\n        <a href="tel:+34630745478">+34 630 74 54 78</a></p>\r\n    </div>\r\n</body>\r\n</html>');