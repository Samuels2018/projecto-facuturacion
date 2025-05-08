-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Volcando estructura para tabla licencias_spain_produccion.diccionario_empresas_estados
CREATE TABLE IF NOT EXISTS `diccionario_empresas_estados` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `etiqueta` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clase` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`rowid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Diccionario de tipos de estados en la empresa';

-- Volcando datos para la tabla licencias_spain_produccion.diccionario_empresas_estados: ~4 rows (aproximadamente)
INSERT INTO `diccionario_empresas_estados` (`rowid`, `etiqueta`, `color`, `clase`) VALUES
	(1, 'Activo', NULL, 'success'),
	(2, 'Vencido', NULL, 'info'),
	(3, 'Cancelado', NULL, 'warning'),
	(4, 'Inactivo', NULL, 'warning');

-- Volcando estructura para tabla licencias_spain_produccion.diccionario_kit_digital_estado
CREATE TABLE IF NOT EXISTS `diccionario_kit_digital_estado` (
  `rowid` int DEFAULT NULL,
  `etiqueta` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla licencias_spain_produccion.diccionario_kit_digital_estado: ~11 rows (aproximadamente)
INSERT INTO `diccionario_kit_digital_estado` (`rowid`, `etiqueta`) VALUES
	(1, 'Cliente Solicita'),
	(3, 'PDF Enviado para Firma del Cliente'),
	(4, 'PDF Enviado a Red.Es'),
	(2, 'Registro en Red.Es realizado'),
	(5, 'Aprobacion Kit Digital'),
	(6, 'Aprobacion Realizada'),
	(7, 'Emision Factura '),
	(8, 'Pago Emision Factura '),
	(9, 'Implementacion Realizada'),
	(10, 'Solicitud de Cobro realizada'),
	(11, 'Cobro Realizado');

-- Volcando estructura para tabla licencias_spain_produccion.diccionario_kit_digital_tipo
CREATE TABLE IF NOT EXISTS `diccionario_kit_digital_tipo` (
  `rowid` int DEFAULT NULL,
  `etiqueta` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla licencias_spain_produccion.diccionario_kit_digital_tipo: ~2 rows (aproximadamente)
INSERT INTO `diccionario_kit_digital_tipo` (`rowid`, `etiqueta`) VALUES
	(1, 'Gestion Por Procesos'),
	(2, 'Factura Electronica');

-- Volcando estructura para tabla licencias_spain_produccion.diccionario_usuarios_estado
CREATE TABLE IF NOT EXISTS `diccionario_usuarios_estado` (
  `rowid` int DEFAULT NULL,
  `etiqueta` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logeable` int DEFAULT NULL COMMENT '1 Permite logear / 0 No permite'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tomar los estados de los usuarios';

-- Volcando datos para la tabla licencias_spain_produccion.diccionario_usuarios_estado: ~2 rows (aproximadamente)
INSERT INTO `diccionario_usuarios_estado` (`rowid`, `etiqueta`, `logeable`) VALUES
	(1, 'Activo', 1),
	(2, 'Inactivo', 0);

-- Volcando estructura para tabla licencias_spain_produccion.diccionario_usuario_tipos
CREATE TABLE IF NOT EXISTS `diccionario_usuario_tipos` (
  `rowid` int DEFAULT NULL,
  `etiqueta` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tipos de usuarios de la plataforma\r\n';

-- Volcando datos para la tabla licencias_spain_produccion.diccionario_usuario_tipos: ~3 rows (aproximadamente)
INSERT INTO `diccionario_usuario_tipos` (`rowid`, `etiqueta`) VALUES
	(1, 'Usuario'),
	(2, 'Gestoria'),
	(3, 'Revendedor');

-- Volcando estructura para tabla licencias_spain_produccion.errores
CREATE TABLE IF NOT EXISTS `errores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_code` int NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla licencias_spain_produccion.errores: ~0 rows (aproximadamente)

-- Volcando estructura para tabla licencias_spain_produccion.sistema_empresa
CREATE TABLE IF NOT EXISTS `sistema_empresa` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre_comercial` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion_fk_provincia` int DEFAULT NULL,
  `direccion_fk_municipio` int DEFAULT NULL,
  `telefono_fijo` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono_movil` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fk_estado` int DEFAULT NULL,
  `creado_fecha` datetime NOT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  `fk_sistema_empresa_licencias` int DEFAULT NULL,
  `company_externo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kit_aplica_kit_digital` int NOT NULL DEFAULT '0' COMMENT 'Saber si aplica Kit digital',
  `kit_fk_tipo` int DEFAULT '1',
  `kit_pdf_firmado` int DEFAULT '0',
  `kit_pdf_firmado_url_en_disco` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Donde se guarda en disco',
  `kit_direccion_completa` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kit_codigo_postal` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kit_factura_emitida` int DEFAULT '0',
  `kit_factura_emitida_fecha` datetime DEFAULT NULL,
  `kit_factura_emitida_pagada` int DEFAULT NULL,
  `kit_monto_aprobado` int DEFAULT NULL,
  `kit_monto_comision` int DEFAULT NULL,
  `kit_monto_comision_pagada` int DEFAULT NULL,
  `vendedor_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`),
  KEY `fk_sistema_empresa_licencias` (`fk_sistema_empresa_licencias`),
  CONSTRAINT `FK_sistema_empresa_sistema_empresa_licencias` FOREIGN KEY (`fk_sistema_empresa_licencias`) REFERENCES `sistema_empresa_licencias` (`rowid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='LLeva los datos de la empresa';

-- Volcando datos para la tabla licencias_spain_produccion.sistema_empresa: ~6 rows (aproximadamente)
INSERT INTO `sistema_empresa` (`rowid`, `nombre`, `nombre_comercial`, `direccion_fk_provincia`, `direccion_fk_municipio`, `telefono_fijo`, `telefono_movil`, `website`, `fk_estado`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`, `fk_sistema_empresa_licencias`, `company_externo`, `avatar`, `kit_aplica_kit_digital`, `kit_fk_tipo`, `kit_pdf_firmado`, `kit_pdf_firmado_url_en_disco`, `kit_direccion_completa`, `kit_codigo_postal`, `kit_factura_emitida`, `kit_factura_emitida_fecha`, `kit_factura_emitida_pagada`, `kit_monto_aprobado`, `kit_monto_comision`, `kit_monto_comision_pagada`, `vendedor_fk_usuario`) VALUES
	(1, 'Tech Solutions S.A. DEV', 'Tech Solutions S.A.', 44, NULL, '88493002', '88493002', NULL, 1, '2024-02-09 11:36:49', 1, 0, NULL, NULL, 1, '9341452376515315', '1.png', 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL),
	(2, 'Cisma', 'cismacr.com', 1, 2, '923136587', '630167488', NULL, 1, '2024-02-09 11:36:49', 1, 0, NULL, NULL, 1, '', '', 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL),
	(3, 'Project Master', 'Project Master', 1, 2, '923136587', '630167488', NULL, 1, '2024-02-09 11:36:49', 1, 0, NULL, NULL, 1, '9341452376515315', '3.png', 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL),
	(4, 'Avantec.DS España', 'Avantec.DSE', 41, NULL, '04145761734', '630167488', NULL, 1, '2024-02-09 11:36:49', 1, 0, NULL, NULL, 1, '', '4.png', 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL),
	(5, 'Red House', 'REd House ', 16, 113, '923136587', '630167488', NULL, 1, '2024-02-09 11:36:49', 1, 0, NULL, NULL, 1, '', '5.png', 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL),
	(8, 'Nombre de la empresa', 'Nombre Comercial', 1, 2, '12345', '6788910', NULL, 1, '2024-07-24 08:59:04', 1, 0, NULL, NULL, 1, '1', '', 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL);

-- Volcando estructura para tabla licencias_spain_produccion.sistema_empresa_licencias
CREATE TABLE IF NOT EXISTS `sistema_empresa_licencias` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `user` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pass` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bd` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `server` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`rowid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla licencias_spain_produccion.sistema_empresa_licencias: ~1 rows (aproximadamente)
INSERT INTO `sistema_empresa_licencias` (`rowid`, `user`, `pass`, `bd`, `server`) VALUES
	(1, 'sistema', '3eQFHxWhTTGavMmcYNYe', 'facturas_001', '64.23.179.230');

-- Volcando estructura para tabla licencias_spain_produccion.sistema_empresa_usuarios
CREATE TABLE IF NOT EXISTS `sistema_empresa_usuarios` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_empresa` int NOT NULL DEFAULT '0',
  `fk_usuario` int NOT NULL DEFAULT '0',
  `fk_tipo_relacion` int NOT NULL DEFAULT '0',
  `invitacion_enviada` int NOT NULL DEFAULT '0',
  `invitacion_aceptada` int DEFAULT '0',
  `invitacion_enviada_fecha` datetime DEFAULT NULL,
  `invitacion_aceptada_fecha` datetime DEFAULT NULL,
  `invitacion_fk_usuario_invita` int DEFAULT NULL,
  `activo` int DEFAULT NULL,
  `activo_defecto` int DEFAULT NULL,
  PRIMARY KEY (`rowid`),
  KEY `fk_empresa` (`fk_empresa`),
  KEY `fk_usuario` (`fk_usuario`),
  CONSTRAINT `sistema_empresa_usuarios_ibfk_1` FOREIGN KEY (`fk_usuario`) REFERENCES `usuarios` (`rowid`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `sistema_empresa_usuarios_ibfk_2` FOREIGN KEY (`fk_empresa`) REFERENCES `sistema_empresa` (`rowid`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Esta  tabla  une los usuarios contra los empresas a las que tienen acceso ';

-- Volcando datos para la tabla licencias_spain_produccion.sistema_empresa_usuarios: ~27 rows (aproximadamente)
INSERT INTO `sistema_empresa_usuarios` (`rowid`, `fk_empresa`, `fk_usuario`, `fk_tipo_relacion`, `invitacion_enviada`, `invitacion_aceptada`, `invitacion_enviada_fecha`, `invitacion_aceptada_fecha`, `invitacion_fk_usuario_invita`, `activo`, `activo_defecto`) VALUES
	(1, 1, 1, 1, 1, 1, NULL, NULL, NULL, 1, NULL),
	(2, 1, 2, 1, 1, 1, NULL, NULL, NULL, 1, NULL),
	(3, 2, 3, 1, 1, 1, NULL, NULL, NULL, 1, NULL),
	(4, 2, 4, 1, 1, 1, NULL, NULL, NULL, 1, NULL),
	(5, 2, 5, 1, 1, 1, NULL, NULL, NULL, 1, NULL),
	(6, 3, 6, 1, 1, 1, NULL, NULL, NULL, 1, NULL),
	(7, 3, 7, 1, 1, 1, NULL, NULL, NULL, 1, NULL),
	(8, 4, 8, 1, 1, 1, NULL, NULL, NULL, 1, NULL),
	(9, 4, 9, 1, 1, 1, NULL, NULL, NULL, 1, NULL),
	(10, 4, 10, 1, 1, 1, NULL, NULL, NULL, 1, NULL),
	(11, 4, 11, 1, 1, 1, NULL, NULL, NULL, 1, NULL),
	(12, 5, 12, 1, 1, 1, NULL, NULL, NULL, 1, NULL),
	(13, 5, 13, 1, 1, 1, NULL, NULL, NULL, 1, NULL),
	(14, 1, 11, 1, 1, 1, NULL, NULL, NULL, 1, NULL),
	(15, 8, 11, 1, 1, 1, NULL, NULL, NULL, 1, NULL),
	(16, 8, 15, 1, 1, 1, NULL, NULL, NULL, 1, NULL),
	(30, 5, 36, 1, 0, 1, NULL, NULL, NULL, 1, NULL),
	(31, 5, 37, 1, 0, 1, NULL, NULL, NULL, 1, NULL),
	(32, 5, 38, 1, 0, 1, NULL, NULL, NULL, 1, NULL),
	(33, 5, 39, 1, 0, 1, NULL, NULL, NULL, 1, NULL),
	(34, 5, 40, 1, 0, 1, NULL, NULL, NULL, 1, NULL),
	(35, 5, 41, 1, 0, 1, NULL, NULL, NULL, 1, NULL),
	(36, 5, 42, 1, 0, 1, NULL, NULL, NULL, 1, NULL),
	(37, 5, 43, 1, 0, 1, NULL, NULL, NULL, 1, NULL),
	(38, 5, 44, 1, 0, 1, NULL, NULL, NULL, 1, NULL),
	(39, 5, 45, 1, 0, 1, NULL, NULL, NULL, 1, NULL),
	(40, 4, 46, 1, 0, 1, NULL, NULL, NULL, 1, NULL);

-- Volcando estructura para tabla licencias_spain_produccion.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellidos` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acceso_usuario` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Este es el correo\r\n',
  `acceso_clave` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acceso_correo_estado` set('pendiente','validado') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `acceso_correo_actualizado` datetime DEFAULT NULL COMMENT 'Cuando se cambia el correo se actualiza este campo',
  `acceso_correo_codigo` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'codigo aleatoria de 6 digitos',
  `acceso_correo_actualizado_validado` datetime DEFAULT NULL,
  `usuario_avatar` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usuario_telefono` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Es para notificaciones',
  `fk_estado` int DEFAULT NULL,
  `fk_idioma` int DEFAULT NULL COMMENT 'Apunta al idioma',
  `fk_provincia` int DEFAULT NULL,
  `correo_temporal` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`rowid`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Listado de Usuarios';

-- Volcando datos para la tabla licencias_spain_produccion.usuarios: ~37 rows (aproximadamente)
INSERT INTO `usuarios` (`rowid`, `nombre`, `apellidos`, `acceso_usuario`, `acceso_clave`, `acceso_correo_estado`, `acceso_correo_actualizado`, `acceso_correo_codigo`, `acceso_correo_actualizado_validado`, `usuario_avatar`, `usuario_telefono`, `fk_estado`, `fk_idioma`, `fk_provincia`, `correo_temporal`) VALUES
	(1, 'Demo', 'Demo', 'demo@demo.com', 'demo', 'validado', NULL, '', NULL, '1.png', NULL, 1, 1, NULL, NULL),
	(2, 'Julio', 'Alvarado', 'jalvarado@avancescr.com', '123', 'pendiente', NULL, '', NULL, NULL, NULL, 1, 1, NULL, NULL),
	(3, 'LUIS', 'Fonseca', 'zagrelocigra-5944@yopmail.com', '123', 'validado', '2024-06-06 15:13:14', '', NULL, '3.png', '+452222111', 1, 1, 1, ''),
	(4, 'Bernardo', 'Carvajal', 'bcarvajal@cismacr.com', '123', 'pendiente', NULL, '', NULL, 'https://sistema-dev.avantecds.es/bootstrap/assets/img/1x1/alex.png', NULL, 1, 1, NULL, NULL),
	(5, 'Andrea', 'Arrones', 'arrones@cisma.org', '123', 'pendiente', NULL, '', NULL, 'https://sistema-dev.avantecds.es/bootstrap/assets/img/1x1/Andrea.jpg', NULL, 1, 1, NULL, NULL),
	(6, 'Alex', 'Aguero', 'aaguero@projectmastercr.com', '123', 'pendiente', NULL, '', NULL, '6.png', '123', 1, 1, NULL, NULL),
	(7, 'Pablo', 'Aguero', 'tech@projectmastercr.com', '123', 'pendiente', NULL, '', NULL, '7.jpg', '', 1, 1, 13, NULL),
	(8, 'David', 'Bermejo', 'dbermejo@avancescr.com', '123', 'pendiente', NULL, '', NULL, '8.jpeg', '', 1, 0, 0, NULL),
	(9, 'David', 'Murillo', 'asistente@avancescr.com', '123', 'pendiente', NULL, '', NULL, 'https://sistema-dev.avantecds.es/bootstrap/assets/img/1x1/Andrea.jpg', NULL, 1, 1, NULL, NULL),
	(10, 'Angie', 'Solis', 'asolis@avancescr.com', '123', 'pendiente', NULL, '', NULL, 'https://sistema-dev.avantecds.es/bootstrap/assets/img/1x1/Andrea.jpg', NULL, 1, 1, NULL, NULL),
	(11, 'Lucas', 'Sartori', 'lsartori@avancescr.com', '123', 'pendiente', NULL, '', NULL, 'https://sistema-dev.avantecds.es/bootstrap/assets/img/1x1/Andrea.jpg', NULL, 1, 1, NULL, NULL),
	(12, 'Virya', 'Navarro', 'vnavarro@redhousemkt.com', 'redhouse2024', 'pendiente', NULL, '', NULL, 'https://sistema-dev.avantecds.es/bootstrap/assets/img/1x1/Andrea.jpg', '123', 1, NULL, NULL, NULL),
	(13, 'Maricela', 'Navarroo', 'maricela@redhousemkt.com', 'redhouse2024', 'pendiente', NULL, '', NULL, '13.png', '+5061231234', 0, NULL, NULL, NULL),
	(15, 'Nombre', 'Apellido', 'nombreapellido@demo.com', '1234', 'pendiente', NULL, '', NULL, '15.png', NULL, 1, 1, NULL, NULL),
	(21, 'alberto', 'vargas', 'wordpress.danielvt@gmail.com', '123', 'pendiente', NULL, NULL, NULL, NULL, '123', NULL, NULL, NULL, NULL),
	(23, 'eduardo', 'vargas Tovar', 'kr-lo-ss@gmail.com', '12345', 'validado', NULL, NULL, NULL, NULL, '12345678', 0, NULL, NULL, NULL),
	(26, 'karla', 'varga', 'karla@gmail.com', '123456', 'pendiente', NULL, NULL, NULL, NULL, '123456789', 0, NULL, NULL, NULL),
	(27, 'Gissel', 'Vargas', 'gissel@gmail.com', 'Bo2244ta*', 'pendiente', NULL, NULL, NULL, NULL, '04145761734', 0, NULL, NULL, NULL),
	(28, 'Jean Carlo', 'Varga', 'jean_carlos@gmail.com', '123456789', 'pendiente', NULL, NULL, NULL, NULL, '04145761734', 0, NULL, NULL, NULL),
	(29, 'Emily', 'Chirino', 'emilychirinos@gmail.com', '12345678', 'pendiente', NULL, NULL, NULL, NULL, '04145761734', 0, NULL, NULL, NULL),
	(30, 'skarle', 'vargas', 'skar@gmail.com', '123', 'pendiente', NULL, NULL, NULL, NULL, '20222222', 0, NULL, NULL, NULL),
	(31, 'skarle', 'vargas', 'skarlet123@gmail.com', '123', 'pendiente', NULL, NULL, NULL, NULL, '0255651111', 0, NULL, NULL, NULL),
	(32, 'daniel ib', 'varg to', 'danivar@gmail.com', '123456', 'pendiente', NULL, NULL, NULL, NULL, '025555444', 0, NULL, NULL, NULL),
	(33, 'armando', 'rojas', 'arma@gmail.com', '12345', 'pendiente', NULL, NULL, NULL, NULL, '12345678', 0, NULL, NULL, NULL),
	(34, 'armando', 'rojas', 'arma@gmail.com', '12345', 'pendiente', NULL, NULL, NULL, NULL, '12345678', 0, NULL, NULL, NULL),
	(35, 'armando', 'rojas', 'arma@gmail.com', '12345', 'pendiente', NULL, NULL, NULL, NULL, '12345678', 0, NULL, NULL, NULL),
	(36, 'F', 'Rojas', 'frojas@redhousemkt.com', 'redhouse2024', 'pendiente', NULL, NULL, NULL, NULL, '123', 1, NULL, NULL, NULL),
	(37, 'Maricela', 'Solano', 'msolano@redhousemkt.com', 'redhouse2024', 'pendiente', NULL, NULL, NULL, NULL, '83150308', 1, 1, 0, NULL),
	(38, 'Valeria', 'Castillo', 'vcastillo@redhousemkt.com', 'redhouse2024', 'pendiente', NULL, NULL, NULL, '38.jpeg', '87161143', 1, 0, 0, NULL),
	(39, 'W', 'Orellana', 'worellana@redhousemkt.com', 'redhouse2024', 'pendiente', NULL, NULL, NULL, NULL, '123', 1, NULL, NULL, NULL),
	(40, 'Suzeth ', 'Campos', 'scampos@redhousemkt.com', 'redhouse2024', 'pendiente', NULL, NULL, NULL, '40.png', '89170551', 1, 1, 0, NULL),
	(41, 'N', 'Aleman', 'naleman@redhousemkt.com', 'redhouse2024', 'pendiente', NULL, NULL, NULL, NULL, '123', 1, NULL, NULL, NULL),
	(42, 'S', 'Badilla', 'sbadilla@redhousemkt.com', 'redhouse2024', 'pendiente', NULL, NULL, NULL, NULL, '123', 1, NULL, NULL, NULL),
	(43, 'S', 'Castillo', 'scastillo@redhousemkt.com', 'redhouse2024', 'pendiente', NULL, NULL, NULL, NULL, '123', 1, NULL, NULL, NULL),
	(44, 'Diseño', 'Redhouse', 'diseno@redhousemkt.com', 'redhouse2024', 'pendiente', NULL, NULL, NULL, NULL, '123', 1, NULL, NULL, NULL),
	(45, 'prueba', 'prueba', 'ñoño@redhousemkt.com', 'redhouse2024', 'pendiente', NULL, NULL, NULL, NULL, '123', 0, NULL, NULL, NULL),
	(46, 'da', 'adasd', 'dbermejo@avancescr.com', 'asdasd', 'pendiente', NULL, NULL, NULL, NULL, 'asdasd', 1, NULL, NULL, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
