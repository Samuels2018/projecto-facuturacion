/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80040 (8.0.40-0ubuntu0.24.04.1)
 Source Host           : localhost:3306
 Source Schema         : facturas_RfmtOH7sXs

 Target Server Type    : MySQL
 Target Server Version : 80040 (8.0.40-0ubuntu0.24.04.1)
 File Encoding         : 65001

 Date: 02/12/2024 10:03:28
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for campos_extra_detalle
-- ----------------------------
DROP TABLE IF EXISTS `campos_extra_detalle`;
CREATE TABLE `campos_extra_detalle` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_tipo_campo` int NOT NULL,
  `fk_modulo` int NOT NULL,
  `fk_empresa` int NOT NULL,
  `valor` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `activo` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '1' COMMENT '1 - activo / 0 - inactivo',
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of campos_extra_detalle
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for campos_extra_formularios
-- ----------------------------
DROP TABLE IF EXISTS `campos_extra_formularios`;
CREATE TABLE `campos_extra_formularios` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL,
  `fk_formulario` int NOT NULL,
  `fk_diccionario_campos_extra_tipo` int DEFAULT NULL,
  `input_etiqueta` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `input_descripcion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `input_obligatorio` int NOT NULL DEFAULT '0',
  `input_valor_defecto` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_formulario` (`fk_formulario`) USING BTREE,
  KEY `entidad` (`entidad`) USING BTREE,
  KEY `fk_formulario_2` (`fk_formulario`) USING BTREE,
  KEY `fk_diccionario_campos_extra_tipo` (`fk_diccionario_campos_extra_tipo`) USING BTREE,
  CONSTRAINT `campos_extra_formularios_ibfk_1` FOREIGN KEY (`fk_formulario`) REFERENCES `utilidades_apoyo`.`diccionario_formularios` (`rowid`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `campos_extra_formularios_ibfk_2` FOREIGN KEY (`fk_diccionario_campos_extra_tipo`) REFERENCES `diccionario_campos_extra_tipo` (`rowid`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of campos_extra_formularios
-- ----------------------------
BEGIN;
INSERT INTO `campos_extra_formularios` (`rowid`, `entidad`, `fk_formulario`, `fk_diccionario_campos_extra_tipo`, `input_etiqueta`, `input_descripcion`, `input_obligatorio`, `input_valor_defecto`) VALUES (1, 3, 1, 1, 'Orden Compra', 'Descripcion de la Orden de Compra', 1, '');
COMMIT;

-- ----------------------------
-- Table structure for configuracion_agentes
-- ----------------------------
DROP TABLE IF EXISTS `configuracion_agentes`;
CREATE TABLE `configuracion_agentes` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_tercero` int DEFAULT NULL,
  `fk_agente` int DEFAULT NULL,
  `actual` int NOT NULL DEFAULT '0',
  `creado_fecha` datetime NOT NULL,
  `creado_fk_usuario` int NOT NULL,
  `borrado` int DEFAULT NULL,
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of configuracion_agentes
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for configuracion_php_mailer
-- ----------------------------
DROP TABLE IF EXISTS `configuracion_php_mailer`;
CREATE TABLE `configuracion_php_mailer` (
  `id` int NOT NULL AUTO_INCREMENT,
  `SMTPDebug` int DEFAULT NULL,
  `isSMTP` tinyint(1) DEFAULT NULL,
  `Host` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `SMTPAuth` tinyint(1) DEFAULT NULL,
  `Username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Port` int DEFAULT NULL,
  `verify_peer` tinyint(1) DEFAULT NULL,
  `verify_peer_name` tinyint(1) DEFAULT NULL,
  `allow_self_signed` tinyint(1) DEFAULT NULL,
  `Subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `FromEmail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `FromName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of configuracion_php_mailer
-- ----------------------------
BEGIN;
INSERT INTO `configuracion_php_mailer` (`id`, `SMTPDebug`, `isSMTP`, `Host`, `SMTPAuth`, `Username`, `Password`, `Port`, `verify_peer`, `verify_peer_name`, `allow_self_signed`, `Subject`, `FromEmail`, `FromName`) VALUES (1, 1, 1, 'mail.avancescr.com', 1, 'notificador@avancescr.com', '8!#cK-+S;4-q', 587, 0, 0, 1, 'subject', 'notificador@avancescr.com', 'Notificador');
INSERT INTO `configuracion_php_mailer` (`id`, `SMTPDebug`, `isSMTP`, `Host`, `SMTPAuth`, `Username`, `Password`, `Port`, `verify_peer`, `verify_peer_name`, `allow_self_signed`, `Subject`, `FromEmail`, `FromName`) VALUES (2, 0, 1, 'mail.avancescr.com', 1, 'notificador@avancescr.com', '8!#cK-+S;4-q', 587, 0, NULL, NULL, NULL, 'notificador@avancescr.com', 'Notificador');
COMMIT;

-- ----------------------------
-- Table structure for diccionario_agente_rutas
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_agente_rutas`;
CREATE TABLE `diccionario_agente_rutas` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_ruta` int DEFAULT NULL,
  `entidad` int NOT NULL,
  `fk_agente` int DEFAULT NULL,
  `activo` int DEFAULT NULL,
  `borrado` int DEFAULT '0',
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of diccionario_agente_rutas
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for diccionario_bancos
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_bancos`;
CREATE TABLE `diccionario_bancos` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int DEFAULT NULL,
  `nombre_banco` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` int NOT NULL DEFAULT '1',
  `creado_fecha` datetime DEFAULT NULL,
  `borrado` int DEFAULT '0',
  `borrado_fecha_usuario` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of diccionario_bancos
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for diccionario_campos_extra_tipo
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_campos_extra_tipo`;
CREATE TABLE `diccionario_campos_extra_tipo` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `etiqueta` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `input` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Campos Extras';

-- ----------------------------
-- Records of diccionario_campos_extra_tipo
-- ----------------------------
BEGIN;
INSERT INTO `diccionario_campos_extra_tipo` (`rowid`, `etiqueta`, `input`, `descripcion`) VALUES (1, 'Campo de Texto', 'text', 'Un campo de entrada de texto');
INSERT INTO `diccionario_campos_extra_tipo` (`rowid`, `etiqueta`, `input`, `descripcion`) VALUES (2, 'Área de Texto', 'textarea', 'Un área de texto más grande para entradas de varias líneas');
INSERT INTO `diccionario_campos_extra_tipo` (`rowid`, `etiqueta`, `input`, `descripcion`) VALUES (3, 'Selección', 'select', 'Un campo de selección desplegable');
INSERT INTO `diccionario_campos_extra_tipo` (`rowid`, `etiqueta`, `input`, `descripcion`) VALUES (4, 'Casilla de Verificación', 'checkbox', 'Un campo de casilla de verificación');
INSERT INTO `diccionario_campos_extra_tipo` (`rowid`, `etiqueta`, `input`, `descripcion`) VALUES (5, 'Botón de Opción', 'radio', 'Un campo de botón de opción');
INSERT INTO `diccionario_campos_extra_tipo` (`rowid`, `etiqueta`, `input`, `descripcion`) VALUES (6, 'Fecha', 'date', 'Un campo para seleccionar una fecha');
INSERT INTO `diccionario_campos_extra_tipo` (`rowid`, `etiqueta`, `input`, `descripcion`) VALUES (7, 'Número', 'number', 'Un campo de entrada para números');
INSERT INTO `diccionario_campos_extra_tipo` (`rowid`, `etiqueta`, `input`, `descripcion`) VALUES (8, 'Correo Electrónico', 'email', 'Un campo de entrada para direcciones de correo electrónico');
INSERT INTO `diccionario_campos_extra_tipo` (`rowid`, `etiqueta`, `input`, `descripcion`) VALUES (9, 'Contraseña', 'password', 'Un campo de entrada para contraseñas');
COMMIT;

-- ----------------------------
-- Table structure for diccionario_catalogo
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_catalogo`;
CREATE TABLE `diccionario_catalogo` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int DEFAULT NULL,
  `codigo` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `detalle` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tipo` int NOT NULL DEFAULT '1',
  `activo` int NOT NULL DEFAULT '1',
  `creado_fecha` datetime DEFAULT NULL,
  `borrado` int DEFAULT '0',
  `borrado_fecha_usuario` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=72 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of diccionario_catalogo
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for diccionario_categorias
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_categorias`;
CREATE TABLE `diccionario_categorias` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL DEFAULT '1',
  `activo` int NOT NULL,
  `creado_fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `label` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int DEFAULT '0',
  `borrado_fecha_usuario` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  `fk_parent` int DEFAULT NULL COMMENT 'Sub elemento de esta tabla',
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_categoria_parent_categoria` (`fk_parent`) USING BTREE,
  CONSTRAINT `fk_categoria_parent_categoria` FOREIGN KEY (`fk_parent`) REFERENCES `diccionario_categorias` (`rowid`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=260 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of diccionario_categorias
-- ----------------------------
BEGIN;
INSERT INTO `diccionario_categorias` (`rowid`, `entidad`, `activo`, `creado_fecha`, `label`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`, `fk_parent`) VALUES (253, 4, 1, '2024-11-20 14:19:32', 'Servicios profesionales ', 47, 0, NULL, NULL, NULL);
INSERT INTO `diccionario_categorias` (`rowid`, `entidad`, `activo`, `creado_fecha`, `label`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`, `fk_parent`) VALUES (254, 4, 1, '2024-11-20 14:19:56', 'Servicios Médicos', 47, 0, NULL, NULL, 253);
INSERT INTO `diccionario_categorias` (`rowid`, `entidad`, `activo`, `creado_fecha`, `label`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`, `fk_parent`) VALUES (255, 4, 1, '2024-11-20 14:39:42', 'Farmacéuticos', 47, 0, NULL, NULL, NULL);
INSERT INTO `diccionario_categorias` (`rowid`, `entidad`, `activo`, `creado_fecha`, `label`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`, `fk_parent`) VALUES (256, 4, 1, '2024-11-20 15:03:17', 'Veterinarias', 47, 0, NULL, NULL, NULL);
INSERT INTO `diccionario_categorias` (`rowid`, `entidad`, `activo`, `creado_fecha`, `label`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`, `fk_parent`) VALUES (257, 4, 1, '2024-11-20 15:03:28', 'Medicamentes', 47, 0, NULL, NULL, 256);
INSERT INTO `diccionario_categorias` (`rowid`, `entidad`, `activo`, `creado_fecha`, `label`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`, `fk_parent`) VALUES (258, 3, 1, '2024-11-22 08:32:28', 'Servicios', 2, 1, '2024-11-22 08:34:50', 2, NULL);
INSERT INTO `diccionario_categorias` (`rowid`, `entidad`, `activo`, `creado_fecha`, `label`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`, `fk_parent`) VALUES (259, 3, 1, '2024-11-22 08:34:46', 'Consultorias ', 2, 0, NULL, NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for diccionario_clientes_categorias
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_clientes_categorias`;
CREATE TABLE `diccionario_clientes_categorias` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL,
  `label` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `estado` int NOT NULL DEFAULT '1',
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha_usuario` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of diccionario_clientes_categorias
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for diccionario_contacto
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_contacto`;
CREATE TABLE `diccionario_contacto` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `label` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `activo` int NOT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of diccionario_contacto
-- ----------------------------
BEGIN;
INSERT INTO `diccionario_contacto` (`rowid`, `label`, `activo`) VALUES (1, 'Teléfono Celular', 1);
INSERT INTO `diccionario_contacto` (`rowid`, `label`, `activo`) VALUES (2, 'Correo Electrónico', 1);
INSERT INTO `diccionario_contacto` (`rowid`, `label`, `activo`) VALUES (3, 'Teléfono Fijo', 1);
INSERT INTO `diccionario_contacto` (`rowid`, `label`, `activo`) VALUES (4, 'Página Web', 1);
INSERT INTO `diccionario_contacto` (`rowid`, `label`, `activo`) VALUES (5, 'Facebook', 1);
INSERT INTO `diccionario_contacto` (`rowid`, `label`, `activo`) VALUES (6, 'Extensión', 1);
INSERT INTO `diccionario_contacto` (`rowid`, `label`, `activo`) VALUES (7, 'Dirección', 1);
COMMIT;

-- ----------------------------
-- Table structure for diccionario_crm_actividades
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_crm_actividades`;
CREATE TABLE `diccionario_crm_actividades` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int DEFAULT NULL,
  `nombre` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `color` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `icono` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `activo` int NOT NULL DEFAULT '1' COMMENT '1=activo, 0=inactivo',
  `creado_fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `creado_fk_usuario` int NOT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of diccionario_crm_actividades
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for diccionario_crm_actividades_estado
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_crm_actividades_estado`;
CREATE TABLE `diccionario_crm_actividades_estado` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `etiqueta` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `color` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of diccionario_crm_actividades_estado
-- ----------------------------
BEGIN;
INSERT INTO `diccionario_crm_actividades_estado` (`rowid`, `etiqueta`, `color`) VALUES (1, 'Pendiente', '#FFD700');
INSERT INTO `diccionario_crm_actividades_estado` (`rowid`, `etiqueta`, `color`) VALUES (3, 'Realizada', '#28a745');
INSERT INTO `diccionario_crm_actividades_estado` (`rowid`, `etiqueta`, `color`) VALUES (4, 'Anulada', '#808080');
COMMIT;

-- ----------------------------
-- Table structure for diccionario_crm_oportunidades_categorias
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_crm_oportunidades_categorias`;
CREATE TABLE `diccionario_crm_oportunidades_categorias` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL,
  `etiqueta` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `prioridad` int NOT NULL,
  `estilo` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `activo` int NOT NULL,
  `creado_fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `entidad` (`entidad`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Cateogrias por Entidad';

-- ----------------------------
-- Records of diccionario_crm_oportunidades_categorias
-- ----------------------------
BEGIN;
INSERT INTO `diccionario_crm_oportunidades_categorias` (`rowid`, `entidad`, `etiqueta`, `prioridad`, `estilo`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (1, 3, 'Solar', 1, 'danger', 1, '2024-10-23 02:48:57', NULL, 1, '2024-10-23 11:37:43', 6);
INSERT INTO `diccionario_crm_oportunidades_categorias` (`rowid`, `entidad`, `etiqueta`, `prioridad`, `estilo`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (2, 4, 'IOT', 1, 'warning', 1, '2024-10-23 02:48:57', NULL, 0, '2024-10-23 05:27:47', 8);
INSERT INTO `diccionario_crm_oportunidades_categorias` (`rowid`, `entidad`, `etiqueta`, `prioridad`, `estilo`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (6, 3, 'Deportiva', 3, 'success', 1, '2024-10-23 02:48:57', NULL, 0, NULL, NULL);
INSERT INTO `diccionario_crm_oportunidades_categorias` (`rowid`, `entidad`, `etiqueta`, `prioridad`, `estilo`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (8, 3, 'Protección', 4, 'danger', 1, '2024-10-23 02:48:57', NULL, 0, NULL, NULL);
INSERT INTO `diccionario_crm_oportunidades_categorias` (`rowid`, `entidad`, `etiqueta`, `prioridad`, `estilo`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (9, 3, 'Iluminación', 5, '', 0, '2024-10-23 02:48:57', NULL, 0, NULL, NULL);
INSERT INTO `diccionario_crm_oportunidades_categorias` (`rowid`, `entidad`, `etiqueta`, `prioridad`, `estilo`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (10, 8, 'Categoria 1', 0, 'success', 1, '2024-10-23 02:48:57', NULL, 0, NULL, NULL);
INSERT INTO `diccionario_crm_oportunidades_categorias` (`rowid`, `entidad`, `etiqueta`, `prioridad`, `estilo`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (11, 4, 'Otras', 9, 'success', 1, '2024-10-23 04:33:40', 8, 1, '2024-10-23 05:29:50', 8);
INSERT INTO `diccionario_crm_oportunidades_categorias` (`rowid`, `entidad`, `etiqueta`, `prioridad`, `estilo`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (12, 3, 'Hogar', 5, 'danger', 0, '2024-10-23 11:04:58', 6, 1, '2024-10-23 11:05:28', 6);
COMMIT;

-- ----------------------------
-- Table structure for diccionario_crm_oportunidades_prioridades
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_crm_oportunidades_prioridades`;
CREATE TABLE `diccionario_crm_oportunidades_prioridades` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL,
  `etiqueta` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `prioridad` int NOT NULL,
  `estilo` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `activo` int NOT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado_fecha_usuario` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `entidad` (`entidad`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Cateogrias por Entidad';

-- ----------------------------
-- Records of diccionario_crm_oportunidades_prioridades
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for diccionario_direccion
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_direccion`;
CREATE TABLE `diccionario_direccion` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL COMMENT 'Código del cliente, proveedor o agente',
  `tipo_entidad` int NOT NULL DEFAULT '1' COMMENT '1-->Clientes 2-->Proveedores 3-->Agentes',
  `descripcion` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Una breve descripción de la ubicación de la dirección.',
  `codigo_pais` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Código del país',
  `codigo_postal` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Código postal',
  `codigo_poblacion` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'diccionario_comunidades_autonomas',
  `codigo_provincia` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'diccionario_comunidades_autonomas_provincias',
  `codigo_municipio` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'diccionario_comunidades_autonomas_provincias_municipios',
  `codigo_distrito` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Sólo aplica para Costa Rica',
  `codigo_barrio` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Sólo aplica para Costa Rica',
  `latitud` decimal(15,8) DEFAULT NULL COMMENT 'Latitud',
  `longitud` decimal(15,8) DEFAULT NULL COMMENT 'Longitud',
  `direccion` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Dirección de la entidad',
  `otros_datos` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Otros datos relacionados con la ubicación.',
  `activo` int NOT NULL DEFAULT '1',
  `borrado` int NOT NULL DEFAULT '0',
  `creado_fecha` datetime NOT NULL,
  `creado_fk_usuario` int NOT NULL,
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of diccionario_direccion
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for diccionario_empresas_estados
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_empresas_estados`;
CREATE TABLE `diccionario_empresas_estados` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `etiqueta` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clase` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Diccionario de tipos de estados en la empresa';

-- ----------------------------
-- Records of diccionario_empresas_estados
-- ----------------------------
BEGIN;
INSERT INTO `diccionario_empresas_estados` (`rowid`, `etiqueta`, `color`, `clase`) VALUES (1, 'Activo', NULL, 'success');
INSERT INTO `diccionario_empresas_estados` (`rowid`, `etiqueta`, `color`, `clase`) VALUES (2, 'Vencido', NULL, 'info');
INSERT INTO `diccionario_empresas_estados` (`rowid`, `etiqueta`, `color`, `clase`) VALUES (3, 'Cancelado', NULL, 'warning');
INSERT INTO `diccionario_empresas_estados` (`rowid`, `etiqueta`, `color`, `clase`) VALUES (4, 'Inactivo', NULL, 'warning');
COMMIT;

-- ----------------------------
-- Table structure for diccionario_exonerar
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_exonerar`;
CREATE TABLE `diccionario_exonerar` (
  `codigo` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `label` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`codigo`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of diccionario_exonerar
-- ----------------------------
BEGIN;
INSERT INTO `diccionario_exonerar` (`codigo`, `label`) VALUES ('01', 'Compras Autorizadas');
INSERT INTO `diccionario_exonerar` (`codigo`, `label`) VALUES ('02', 'Ventas Exentas a Diplomaticos');
INSERT INTO `diccionario_exonerar` (`codigo`, `label`) VALUES ('03', 'Orden de Compra(instituciones publicas y otros organizmos)');
INSERT INTO `diccionario_exonerar` (`codigo`, `label`) VALUES ('04', 'Exenciones Direccion General de Hacienda');
INSERT INTO `diccionario_exonerar` (`codigo`, `label`) VALUES ('05', 'Zonas Francas');
INSERT INTO `diccionario_exonerar` (`codigo`, `label`) VALUES ('99', 'Otros');
COMMIT;

-- ----------------------------
-- Table structure for diccionario_extensiones_permitidas
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_extensiones_permitidas`;
CREATE TABLE `diccionario_extensiones_permitidas` (
  `rowid` bigint NOT NULL AUTO_INCREMENT,
  `extension` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `categoria` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `entidad` int NOT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of diccionario_extensiones_permitidas
-- ----------------------------
BEGIN;
INSERT INTO `diccionario_extensiones_permitidas` (`rowid`, `extension`, `categoria`, `activo`, `entidad`) VALUES (1, 'image/png', 'imagen', 1, 0);
INSERT INTO `diccionario_extensiones_permitidas` (`rowid`, `extension`, `categoria`, `activo`, `entidad`) VALUES (2, 'image/gif', 'imagen', 1, 0);
INSERT INTO `diccionario_extensiones_permitidas` (`rowid`, `extension`, `categoria`, `activo`, `entidad`) VALUES (3, 'image/jpeg', 'imagen', 1, 0);
INSERT INTO `diccionario_extensiones_permitidas` (`rowid`, `extension`, `categoria`, `activo`, `entidad`) VALUES (4, 'image/pjpeg', 'imagen', 1, 0);
INSERT INTO `diccionario_extensiones_permitidas` (`rowid`, `extension`, `categoria`, `activo`, `entidad`) VALUES (5, 'text/plain', 'texto', 1, 0);
INSERT INTO `diccionario_extensiones_permitidas` (`rowid`, `extension`, `categoria`, `activo`, `entidad`) VALUES (6, 'text/html', 'html', 1, 0);
INSERT INTO `diccionario_extensiones_permitidas` (`rowid`, `extension`, `categoria`, `activo`, `entidad`) VALUES (7, 'application/x-zip-compressed', 'comprimido', 1, 0);
INSERT INTO `diccionario_extensiones_permitidas` (`rowid`, `extension`, `categoria`, `activo`, `entidad`) VALUES (8, 'application/pdf', 'documento', 1, 0);
INSERT INTO `diccionario_extensiones_permitidas` (`rowid`, `extension`, `categoria`, `activo`, `entidad`) VALUES (9, 'application/msword', 'documento', 1, 0);
INSERT INTO `diccionario_extensiones_permitidas` (`rowid`, `extension`, `categoria`, `activo`, `entidad`) VALUES (10, 'application/vnd.ms-excel', 'documento', 1, 0);
INSERT INTO `diccionario_extensiones_permitidas` (`rowid`, `extension`, `categoria`, `activo`, `entidad`) VALUES (11, 'video/mp4', 'video', 1, 0);
COMMIT;

-- ----------------------------
-- Table structure for diccionario_formas_pago
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_formas_pago`;
CREATE TABLE `diccionario_formas_pago` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int DEFAULT NULL,
  `label` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `importes_iguales` int NOT NULL DEFAULT '0',
  `ultimo_dia` int NOT NULL DEFAULT '0',
  `activo` int NOT NULL,
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha_usuario` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of diccionario_formas_pago
-- ----------------------------
BEGIN;
INSERT INTO `diccionario_formas_pago` (`rowid`, `entidad`, `label`, `importes_iguales`, `ultimo_dia`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`) VALUES (4, 4, 'LKLK', 0, 1, 0, '2024-11-07 14:00:48', 8, 1, '2024-11-07 14:36:01', 8);
INSERT INTO `diccionario_formas_pago` (`rowid`, `entidad`, `label`, `importes_iguales`, `ultimo_dia`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`) VALUES (5, 4, 'No importes Si ultimo', 0, 1, 1, '2024-11-07 14:04:43', 8, 1, '2024-11-19 19:28:00', 47);
INSERT INTO `diccionario_formas_pago` (`rowid`, `entidad`, `label`, `importes_iguales`, `ultimo_dia`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`) VALUES (6, 4, '25% inmediato y el resto a 30, 60 y 90 días', 1, 0, 1, '2024-11-07 14:44:51', 8, 0, NULL, NULL);
INSERT INTO `diccionario_formas_pago` (`rowid`, `entidad`, `label`, `importes_iguales`, `ultimo_dia`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`) VALUES (7, 4, '30, 60 y 90 días ', 1, 0, 1, '2024-11-07 14:46:38', 8, 0, NULL, NULL);
INSERT INTO `diccionario_formas_pago` (`rowid`, `entidad`, `label`, `importes_iguales`, `ultimo_dia`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`) VALUES (8, 4, 'Sin importes ni ultimo', 0, 0, 1, '2024-11-07 14:47:25', 8, 1, '2024-11-19 19:28:38', 47);
INSERT INTO `diccionario_formas_pago` (`rowid`, `entidad`, `label`, `importes_iguales`, `ultimo_dia`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`) VALUES (9, 4, 'uuu', 1, 1, 1, '2024-11-07 14:55:35', 8, 1, '2024-11-07 16:49:52', 8);
INSERT INTO `diccionario_formas_pago` (`rowid`, `entidad`, `label`, `importes_iguales`, `ultimo_dia`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`) VALUES (10, 4, '30 días', 0, 0, 1, '2024-11-07 15:01:15', 8, 0, NULL, NULL);
INSERT INTO `diccionario_formas_pago` (`rowid`, `entidad`, `label`, `importes_iguales`, `ultimo_dia`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`) VALUES (11, 4, '12 meses cuotas iguales, pagaderos al último día de cada mes.', 1, 1, 1, '2024-11-07 15:13:24', 8, 0, NULL, NULL);
INSERT INTO `diccionario_formas_pago` (`rowid`, `entidad`, `label`, `importes_iguales`, `ultimo_dia`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`) VALUES (12, 1, 'Contado', 0, 0, 1, '2024-11-07 15:29:53', 1, 0, NULL, NULL);
INSERT INTO `diccionario_formas_pago` (`rowid`, `entidad`, `label`, `importes_iguales`, `ultimo_dia`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`) VALUES (13, 1, '50% contra entrega y 50% 15 días después.', 0, 0, 1, '2024-11-07 15:36:54', 1, 1, '2024-11-07 15:37:26', 1);
INSERT INTO `diccionario_formas_pago` (`rowid`, `entidad`, `label`, `importes_iguales`, `ultimo_dia`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`) VALUES (14, 1, '30 días', 0, 0, 1, '2024-11-08 09:15:32', 1, 0, NULL, NULL);
INSERT INTO `diccionario_formas_pago` (`rowid`, `entidad`, `label`, `importes_iguales`, `ultimo_dia`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`) VALUES (15, 1, '30/60/90 días', 0, 0, 0, '2024-11-08 09:19:41', 1, 0, NULL, NULL);
INSERT INTO `diccionario_formas_pago` (`rowid`, `entidad`, `label`, `importes_iguales`, `ultimo_dia`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`) VALUES (16, 1, '30/60/90 días ajuste al ultimo dia del mes', 0, 1, 1, '2024-11-08 09:20:43', 1, 0, NULL, NULL);
INSERT INTO `diccionario_formas_pago` (`rowid`, `entidad`, `label`, `importes_iguales`, `ultimo_dia`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`) VALUES (17, 4, '30 dias ajustados al último dia del mes.', 0, 1, 1, '2024-11-08 10:07:48', 8, 0, NULL, NULL);
INSERT INTO `diccionario_formas_pago` (`rowid`, `entidad`, `label`, `importes_iguales`, `ultimo_dia`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`) VALUES (18, 4, 'Contado', 0, 0, 1, '2024-11-19 19:33:53', 47, 0, NULL, NULL);
INSERT INTO `diccionario_formas_pago` (`rowid`, `entidad`, `label`, `importes_iguales`, `ultimo_dia`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`) VALUES (19, 3, '12 meses cuotas iguales, pagaderos al último día de cada mes.', 1, 1, 1, '2024-11-22 08:28:00', 2, 0, NULL, NULL);
INSERT INTO `diccionario_formas_pago` (`rowid`, `entidad`, `label`, `importes_iguales`, `ultimo_dia`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`) VALUES (20, 3, '25% inmediato y el resto a 30, 60 y 90 días', 1, 0, 1, '2024-11-22 08:28:36', 2, 0, NULL, NULL);
INSERT INTO `diccionario_formas_pago` (`rowid`, `entidad`, `label`, `importes_iguales`, `ultimo_dia`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`) VALUES (21, 3, '30 días', 0, 0, 1, '2024-11-22 08:31:16', 2, 0, NULL, NULL);
INSERT INTO `diccionario_formas_pago` (`rowid`, `entidad`, `label`, `importes_iguales`, `ultimo_dia`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`) VALUES (22, 3, '30, 60 y 90 días', 0, 0, 1, '2024-11-22 08:31:41', 2, 0, NULL, NULL);
INSERT INTO `diccionario_formas_pago` (`rowid`, `entidad`, `label`, `importes_iguales`, `ultimo_dia`, `activo`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha_usuario`, `borrado_fk_usuario`) VALUES (23, 3, 'Contado', 1, 0, 1, '2024-11-22 08:32:01', 2, 0, NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for diccionario_formas_pago_detalle
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_formas_pago_detalle`;
CREATE TABLE `diccionario_formas_pago_detalle` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_formapago` int NOT NULL,
  `secuencia` int NOT NULL,
  `porcentaje` decimal(10,2) NOT NULL,
  `dias` int NOT NULL,
  `activo` int NOT NULL,
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha_usuario` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_formapagodetalle_idx` (`fk_formapago`) USING BTREE,
  CONSTRAINT `fk_formapagodetalle_ctr` FOREIGN KEY (`fk_formapago`) REFERENCES `diccionario_formas_pago` (`rowid`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of diccionario_formas_pago_detalle
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for diccionario_iconos
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_iconos`;
CREATE TABLE `diccionario_iconos` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `clase` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of diccionario_iconos
-- ----------------------------
BEGIN;
INSERT INTO `diccionario_iconos` (`rowid`, `descripcion`, `clase`) VALUES (1, 'Wifi', 'fa-wifi');
INSERT INTO `diccionario_iconos` (`rowid`, `descripcion`, `clase`) VALUES (2, 'Celebraciones', 'fa-glass');
INSERT INTO `diccionario_iconos` (`rowid`, `descripcion`, `clase`) VALUES (3, 'Aire Acondicionado', 'fa-snowflake');
INSERT INTO `diccionario_iconos` (`rowid`, `descripcion`, `clase`) VALUES (4, 'Gimnasio', 'fa-dumbbell');
INSERT INTO `diccionario_iconos` (`rowid`, `descripcion`, `clase`) VALUES (5, 'Bus', 'fa-bus');
INSERT INTO `diccionario_iconos` (`rowid`, `descripcion`, `clase`) VALUES (6, 'Restaurant', 'fa-cutlery');
INSERT INTO `diccionario_iconos` (`rowid`, `descripcion`, `clase`) VALUES (7, 'Estacionamiento', 'fa-parking');
INSERT INTO `diccionario_iconos` (`rowid`, `descripcion`, `clase`) VALUES (8, 'Accesibilidad', 'fa-wheelchair');
INSERT INTO `diccionario_iconos` (`rowid`, `descripcion`, `clase`) VALUES (9, 'Piscina', 'fa-swimming-pool');
INSERT INTO `diccionario_iconos` (`rowid`, `descripcion`, `clase`) VALUES (10, 'Avion', 'fa-plane');
INSERT INTO `diccionario_iconos` (`rowid`, `descripcion`, `clase`) VALUES (11, 'Pasaporte', 'fa-passport');
INSERT INTO `diccionario_iconos` (`rowid`, `descripcion`, `clase`) VALUES (12, 'Playa', 'fa-umbrella-beach');
INSERT INTO `diccionario_iconos` (`rowid`, `descripcion`, `clase`) VALUES (13, 'Hotel', 'fa-hotel');
INSERT INTO `diccionario_iconos` (`rowid`, `descripcion`, `clase`) VALUES (14, 'Spa', 'fa-spa');
INSERT INTO `diccionario_iconos` (`rowid`, `descripcion`, `clase`) VALUES (15, 'Mascotas', 'fa-dog');
INSERT INTO `diccionario_iconos` (`rowid`, `descripcion`, `clase`) VALUES (16, 'No Fumadores', 'fa-ban-smoking');
COMMIT;

-- ----------------------------
-- Table structure for diccionario_impuestos
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_impuestos`;
CREATE TABLE `diccionario_impuestos` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL,
  `impuesto` decimal(5,1) NOT NULL DEFAULT '0.0',
  `recargo_equivalencia` decimal(5,1) DEFAULT NULL,
  `impuesto_texto` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pais` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Se mete la abreviacion ISO del Pais',
  `autogen` tinyint(1) DEFAULT NULL,
  `activo` int DEFAULT '0',
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tipos de Impuestos de IVA';

-- ----------------------------
-- Records of diccionario_impuestos
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for diccionario_kit_digital_estado
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_kit_digital_estado`;
CREATE TABLE `diccionario_kit_digital_estado` (
  `rowid` int DEFAULT NULL,
  `etiqueta` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of diccionario_kit_digital_estado
-- ----------------------------
BEGIN;
INSERT INTO `diccionario_kit_digital_estado` (`rowid`, `etiqueta`) VALUES (1, 'Cliente Solicita');
INSERT INTO `diccionario_kit_digital_estado` (`rowid`, `etiqueta`) VALUES (3, 'PDF Enviado para Firma del Cliente');
INSERT INTO `diccionario_kit_digital_estado` (`rowid`, `etiqueta`) VALUES (4, 'PDF Enviado a Red.Es');
INSERT INTO `diccionario_kit_digital_estado` (`rowid`, `etiqueta`) VALUES (2, 'Registro en Red.Es realizado');
INSERT INTO `diccionario_kit_digital_estado` (`rowid`, `etiqueta`) VALUES (5, 'Aprobacion Kit Digital');
INSERT INTO `diccionario_kit_digital_estado` (`rowid`, `etiqueta`) VALUES (6, 'Aprobacion Realizada');
INSERT INTO `diccionario_kit_digital_estado` (`rowid`, `etiqueta`) VALUES (7, 'Emision Factura ');
INSERT INTO `diccionario_kit_digital_estado` (`rowid`, `etiqueta`) VALUES (8, 'Pago Emision Factura ');
INSERT INTO `diccionario_kit_digital_estado` (`rowid`, `etiqueta`) VALUES (9, 'Implementacion Realizada');
INSERT INTO `diccionario_kit_digital_estado` (`rowid`, `etiqueta`) VALUES (10, 'Solicitud de Cobro realizada');
INSERT INTO `diccionario_kit_digital_estado` (`rowid`, `etiqueta`) VALUES (11, 'Cobro Realizado');
COMMIT;

-- ----------------------------
-- Table structure for diccionario_kit_digital_tipo
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_kit_digital_tipo`;
CREATE TABLE `diccionario_kit_digital_tipo` (
  `rowid` int DEFAULT NULL,
  `etiqueta` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of diccionario_kit_digital_tipo
-- ----------------------------
BEGIN;
INSERT INTO `diccionario_kit_digital_tipo` (`rowid`, `etiqueta`) VALUES (1, 'Gestion Por Procesos');
INSERT INTO `diccionario_kit_digital_tipo` (`rowid`, `etiqueta`) VALUES (2, 'Factura Electronica');
COMMIT;

-- ----------------------------
-- Table structure for diccionario_medios_pago
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_medios_pago`;
CREATE TABLE `diccionario_medios_pago` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int DEFAULT NULL,
  `label` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `activo` int NOT NULL,
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha_usuario` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of diccionario_medios_pago
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for diccionario_monedas
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_monedas`;
CREATE TABLE `diccionario_monedas` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int DEFAULT NULL,
  `etiqueta` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `simbolo` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `codigo` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `activo` int NOT NULL DEFAULT '1',
  `borrado` int NOT NULL DEFAULT '0',
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado_fecha_usuario` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of diccionario_monedas
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for diccionario_rutas
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_rutas`;
CREATE TABLE `diccionario_rutas` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `entidad` int NOT NULL,
  `activo` int NOT NULL DEFAULT '1',
  `borrado` int NOT NULL DEFAULT '0',
  `creado_fecha` datetime NOT NULL,
  `creado_fk_usuario` int NOT NULL,
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of diccionario_rutas
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for diccionario_usuario_tipos
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_usuario_tipos`;
CREATE TABLE `diccionario_usuario_tipos` (
  `rowid` int DEFAULT NULL,
  `etiqueta` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tipos de usuarios de la plataforma\r\n';

-- ----------------------------
-- Records of diccionario_usuario_tipos
-- ----------------------------
BEGIN;
INSERT INTO `diccionario_usuario_tipos` (`rowid`, `etiqueta`) VALUES (1, 'Usuario');
INSERT INTO `diccionario_usuario_tipos` (`rowid`, `etiqueta`) VALUES (2, 'Gestoria');
INSERT INTO `diccionario_usuario_tipos` (`rowid`, `etiqueta`) VALUES (3, 'Revendedor');
COMMIT;

-- ----------------------------
-- Table structure for diccionario_usuarios_estado
-- ----------------------------
DROP TABLE IF EXISTS `diccionario_usuarios_estado`;
CREATE TABLE `diccionario_usuarios_estado` (
  `rowid` int DEFAULT NULL,
  `etiqueta` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logeable` int DEFAULT NULL COMMENT '1 Permite logear / 0 No permite'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tomar los estados de los usuarios';

-- ----------------------------
-- Records of diccionario_usuarios_estado
-- ----------------------------
BEGIN;
INSERT INTO `diccionario_usuarios_estado` (`rowid`, `etiqueta`, `logeable`) VALUES (1, 'Activo', 1);
INSERT INTO `diccionario_usuarios_estado` (`rowid`, `etiqueta`, `logeable`) VALUES (2, 'Inactivo', 0);
COMMIT;

-- ----------------------------
-- Table structure for documentos_gasto
-- ----------------------------
DROP TABLE IF EXISTS `documentos_gasto`;
CREATE TABLE `documentos_gasto` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_documento` int DEFAULT NULL COMMENT 'fk_compra y fk_compra_simplificda, fk_factura',
  `tipo` int DEFAULT NULL COMMENT '1= compra, 2=compra simplificada, 3= factura',
  `fk_gasto` int DEFAULT NULL COMMENT 'fk hijo de la tabla fi_gastos_tipos ',
  `entidad` int NOT NULL COMMENT 'fk_empresa',
  `fecha_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of documentos_gasto
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for electronica_comunicacion
-- ----------------------------
DROP TABLE IF EXISTS `electronica_comunicacion`;
CREATE TABLE `electronica_comunicacion` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_factura` int NOT NULL,
  `tipo` int NOT NULL DEFAULT '1' COMMENT '1-->factura 2--> Debito 3--->credito',
  `resultado` set('error','respuesta','recibido','aceptada','correo enviado','no enviado') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `comentario` varchar(600) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `respuesta_xml` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_factura` (`fk_factura`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of electronica_comunicacion
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for email_log
-- ----------------------------
DROP TABLE IF EXISTS `email_log`;
CREATE TABLE `email_log` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_factura` int NOT NULL,
  `email_enviado` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `detalle` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `codigo` int NOT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of email_log
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for errores
-- ----------------------------
DROP TABLE IF EXISTS `errores`;
CREATE TABLE `errores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_code` int NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of errores
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for estadistica_inventario
-- ----------------------------
DROP TABLE IF EXISTS `estadistica_inventario`;
CREATE TABLE `estadistica_inventario` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL,
  `mes` int DEFAULT NULL,
  `ano` int DEFAULT NULL,
  `fk_bodega` int NOT NULL,
  `fk_producto` int NOT NULL,
  `inicial_cantidad` decimal(10,2) NOT NULL COMMENT 'Cantidad inicial',
  `inicial_valor` decimal(10,2) NOT NULL COMMENT 'Valor inicial',
  `entrada_cantidad` decimal(10,2) NOT NULL,
  `entrada_valor` decimal(10,2) NOT NULL,
  `compra_cantidad` decimal(10,2) NOT NULL,
  `compra_valor` decimal(10,2) NOT NULL,
  `devolucion_compra_cantidad` decimal(10,2) NOT NULL,
  `devolucion_compra_valor` decimal(10,2) NOT NULL,
  `salida_cantidad` decimal(10,2) NOT NULL,
  `salida_valor` decimal(10,2) NOT NULL,
  `ventas_cantidad` decimal(10,2) NOT NULL,
  `ventas_valor` decimal(10,2) NOT NULL,
  `devolucion_venta_cantidad` decimal(10,2) NOT NULL,
  `devolucion_venta_valor` decimal(10,2) NOT NULL,
  `existencia_cantidad` decimal(10,2) NOT NULL,
  `existencia_valor` decimal(10,2) NOT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of estadistica_inventario
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for estado_facturas
-- ----------------------------
DROP TABLE IF EXISTS `estado_facturas`;
CREATE TABLE `estado_facturas` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `etiqueta` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'la descripción del estado',
  `color` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'para guardar una clase o comentario aclaratorio',
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of estado_facturas
-- ----------------------------
BEGIN;
INSERT INTO `estado_facturas` (`rowid`, `etiqueta`, `color`) VALUES (1, 'Validado', 'badge badge-light-success');
INSERT INTO `estado_facturas` (`rowid`, `etiqueta`, `color`) VALUES (3, 'Borrador', 'badge badge-light-danger');
COMMIT;

-- ----------------------------
-- Table structure for etiquetas_facturacion
-- ----------------------------
DROP TABLE IF EXISTS `etiquetas_facturacion`;
CREATE TABLE `etiquetas_facturacion` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `label` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `descripcion` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `activo` int NOT NULL DEFAULT '1',
  `entidad` int NOT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of etiquetas_facturacion
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_adjuntos
-- ----------------------------
DROP TABLE IF EXISTS `fi_adjuntos`;
CREATE TABLE `fi_adjuntos` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL,
  `fk_documento` int NOT NULL,
  `tipo_documento` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `activo` int DEFAULT NULL,
  `descripcion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int DEFAULT NULL,
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of fi_adjuntos
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_agentes
-- ----------------------------
DROP TABLE IF EXISTS `fi_agentes`;
CREATE TABLE `fi_agentes` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int DEFAULT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `persona_contacto` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `movil` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `web` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `impuesto` int DEFAULT NULL,
  `comision` int DEFAULT NULL,
  `iva` int DEFAULT NULL,
  `pais` int DEFAULT NULL,
  `cedula` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigo_postal` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provincia` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `domicilio` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observacion` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creado_fecha` datetime NOT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of fi_agentes
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_albaranes
-- ----------------------------
DROP TABLE IF EXISTS `fi_albaranes`;
CREATE TABLE `fi_albaranes` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL DEFAULT '1',
  `moneda` int NOT NULL DEFAULT '1' COMMENT '1 Colones 2-- Dolares',
  `actividad` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `moneda_tipo_cambio` decimal(10,2) NOT NULL DEFAULT '1.00',
  `fk_usuario_crear` int NOT NULL,
  `fk_usuario_validar` int DEFAULT NULL,
  `fecha_validacion` datetime DEFAULT NULL,
  `fecha` date NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `referencia` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tipo` int NOT NULL DEFAULT '1' COMMENT '0 -> contado    1-> Credito',
  `forma_pago` int NOT NULL,
  `detalle` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `notageneral` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `fk_tercero` int DEFAULT NULL,
  `nombre_cliente` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `subtotal` decimal(30,5) DEFAULT NULL,
  `impuesto` decimal(30,5) DEFAULT NULL,
  `total` decimal(30,5) DEFAULT NULL,
  `servicio_mesa` int DEFAULT '0',
  `numero_placa` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `numero_guia` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `recibido` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `estado` int NOT NULL COMMENT '0 Borrador  ----- 1 Validada  3--- abandonada',
  `pagado` decimal(30,5) NOT NULL DEFAULT '0.00000',
  `estado_pagada` int NOT NULL DEFAULT '0' COMMENT '0- No   1-  Si',
  `eliminado` int NOT NULL DEFAULT '0',
  `fecha_creacion_server` timestamp NULL DEFAULT NULL,
  `manual` int DEFAULT NULL,
  `cafe_mesa` int DEFAULT NULL,
  `electronica_enviada` int DEFAULT NULL,
  `electronica_enviada_fecha` datetime DEFAULT NULL,
  `electronica_resultado` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `electronica_resultado_txt` varchar(600) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `electronica_resultado_fecha` datetime DEFAULT NULL,
  `electronica_error` int NOT NULL DEFAULT '0',
  `electronica_error_txt` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `rechequeo_factura` int DEFAULT '0',
  `envio_correo_factura` int DEFAULT '0',
  `envio_correo_factura_fecha` date DEFAULT NULL,
  `envio_correo_factura_correo` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `envio_correo_instant` int DEFAULT '0',
  `envio_correo_instant_fecha` datetime DEFAULT NULL,
  `envio_correo_instant_correo` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `situacion_comprobante` int NOT NULL DEFAULT '1' COMMENT '1-->Normal  2-->Contingencia  3--> Sin Internet',
  `consecutivo` varchar(22) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `pago_1` int DEFAULT NULL,
  `pago_2` int DEFAULT NULL,
  `pago_3` int DEFAULT NULL,
  `pago_4` int DEFAULT NULL,
  `pago_5` int DEFAULT NULL,
  `pago_99` int DEFAULT NULL,
  `clave` varchar(52) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `version` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT '4.3',
  `electronica_tipo` set('tiquete','factura','factura_exportacion') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'factura' COMMENT 'Todos nacen en Factura',
  `TotalServGravados` decimal(18,5) DEFAULT NULL,
  `TotalServExentos` decimal(18,5) DEFAULT NULL,
  `TotalServExonerado` decimal(18,5) DEFAULT NULL,
  `TotalMercanciasGravadas` decimal(18,5) DEFAULT NULL,
  `TotalMercanciasExentas` decimal(18,5) DEFAULT NULL,
  `TotalMercExonerada` decimal(18,5) DEFAULT NULL,
  `TotalGravado` decimal(18,5) DEFAULT NULL,
  `TotalExento` decimal(18,5) DEFAULT NULL,
  `TotalExonerado` decimal(18,5) DEFAULT NULL,
  `TotalVenta` decimal(18,5) DEFAULT NULL,
  `TotalDescuentos` decimal(18,5) DEFAULT NULL,
  `TotalVentaNeta` decimal(18,5) DEFAULT NULL,
  `TotalImpuesto` decimal(18,5) DEFAULT NULL,
  `TotalComprobante` decimal(18,5) DEFAULT NULL,
  `total_gti` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Puede sacarseel reporte tipo GTI',
  `prismart` int DEFAULT '0',
  `lista_impresion` int DEFAULT '0',
  `numeroOrdenFruti` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `plazoOrdenFruti` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_usuario_crear` (`fk_usuario_crear`) USING BTREE,
  KEY `electronica_enviada` (`electronica_enviada`) USING BTREE,
  KEY `entidad` (`entidad`) USING BTREE,
  KEY `estado` (`estado`) USING BTREE,
  KEY `fecha_creacion_server` (`fecha_creacion_server`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_albaranes
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_albaranes_detalle
-- ----------------------------
DROP TABLE IF EXISTS `fi_albaranes_detalle`;
CREATE TABLE `fi_albaranes_detalle` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_entidad` int NOT NULL,
  `fk_albaran` int NOT NULL,
  `tipo` int NOT NULL DEFAULT '1' COMMENT '1-->producto 2--> Servicios',
  `fk_producto` int NOT NULL,
  `label` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `ref` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `label_extra` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `tipo_impuesto` int NOT NULL,
  `cantidad` decimal(16,3) NOT NULL,
  `subtotal` decimal(15,5) NOT NULL,
  `impuesto` decimal(15,5) NOT NULL,
  `exoneracion` int DEFAULT '0',
  `ImpuestoNeto` decimal(18,5) DEFAULT NULL COMMENT 'Usase si tienes exoneracion',
  `total` decimal(15,5) NOT NULL,
  `precio_original` decimal(15,5) NOT NULL,
  `descuento_tipo` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descuento_aplicado` int NOT NULL,
  `descuento_valor_final` decimal(15,5) NOT NULL,
  `precio_costo` decimal(10,2) DEFAULT NULL COMMENT 'precio Unitario IMPUSTO INCLUIDO',
  `CABYS_codigo` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_producto` (`fk_producto`) USING BTREE,
  KEY `fk_albaran` (`fk_albaran`) USING BTREE,
  KEY `fk_entidad` (`fk_entidad`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_albaranes_detalle
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_bodegas
-- ----------------------------
DROP TABLE IF EXISTS `fi_bodegas`;
CREATE TABLE `fi_bodegas` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_empresa` int NOT NULL,
  `tipo` int DEFAULT '1' COMMENT '1 Facturacion    2 Apartado',
  `label` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `nota` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `principal_facturar` int DEFAULT '0',
  `activo` int NOT NULL,
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  `entidad` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_bodegas
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_bodegas_movimientos
-- ----------------------------
DROP TABLE IF EXISTS `fi_bodegas_movimientos`;
CREATE TABLE `fi_bodegas_movimientos` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_bodega` int NOT NULL,
  `fk_producto` int NOT NULL,
  `tipo` int NOT NULL COMMENT '0- meter / 1--- Sacar',
  `valor` int NOT NULL,
  `stock_actual` int NOT NULL COMMENT ' stock actual despues de esta operacion',
  `motivo` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `usuario` int NOT NULL,
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_bodegas_movimientos
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_bodegas_productos_configuracion
-- ----------------------------
DROP TABLE IF EXISTS `fi_bodegas_productos_configuracion`;
CREATE TABLE `fi_bodegas_productos_configuracion` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_producto` int NOT NULL,
  `stock_minimo` int NOT NULL,
  `stock_deseado` int NOT NULL,
  `fk_ubicacion_1` int NOT NULL,
  `fk_ubicacion_2` int NOT NULL,
  `fk_ubicacion_3` int NOT NULL,
  `fk_ubicacion_4` int NOT NULL,
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of fi_bodegas_productos_configuracion
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_bodegas_stock
-- ----------------------------
DROP TABLE IF EXISTS `fi_bodegas_stock`;
CREATE TABLE `fi_bodegas_stock` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_bodega` int NOT NULL,
  `fk_producto` int NOT NULL,
  `stock` int NOT NULL,
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_bodega` (`fk_bodega`) USING BTREE,
  KEY `fk_producto` (`fk_producto`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=374 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_bodegas_stock
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_comerciales
-- ----------------------------
DROP TABLE IF EXISTS `fi_comerciales`;
CREATE TABLE `fi_comerciales` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `nombre_comercial` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `fk_entidad` int NOT NULL,
  `activo` int NOT NULL,
  UNIQUE KEY `rowid` (`rowid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of fi_comerciales
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_compras
-- ----------------------------
DROP TABLE IF EXISTS `fi_compras`;
CREATE TABLE `fi_compras` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL DEFAULT '1',
  `fk_usuario_crear` int NOT NULL,
  `fk_usuario_validar` int NOT NULL,
  `fecha` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `referencia` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `referencia_proveedor` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tipo` int NOT NULL DEFAULT '0' COMMENT '0 -> contado    1-> Credito',
  `forma_pago` int NOT NULL,
  `detalle` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `fk_tercero` int DEFAULT NULL,
  `subtotal_predescuento` int NOT NULL DEFAULT '0',
  `descuento` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `impuesto` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` int NOT NULL COMMENT '0 Borrador  ----- 1 Validada  3--- abandonada',
  `pagado` int NOT NULL DEFAULT '0',
  `estado_pagada` int NOT NULL DEFAULT '0' COMMENT '0- No   1-  Si',
  `eliminado` int NOT NULL DEFAULT '0',
  `fecha_creacion_server` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `_vuelto` int NOT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_usuario_crear` (`fk_usuario_crear`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_compras
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_compras_configuracion
-- ----------------------------
DROP TABLE IF EXISTS `fi_compras_configuracion`;
CREATE TABLE `fi_compras_configuracion` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL DEFAULT '1',
  `siguiente_borrador` int NOT NULL,
  `siguiente_factura` int NOT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='Configuracion de las facturas';

-- ----------------------------
-- Records of fi_compras_configuracion
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_compras_detalle
-- ----------------------------
DROP TABLE IF EXISTS `fi_compras_detalle`;
CREATE TABLE `fi_compras_detalle` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_compra` int NOT NULL,
  `fk_producto` int NOT NULL,
  `label` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `ref` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tipo_impuesto` int NOT NULL,
  `cantidad` int NOT NULL,
  `subtotal` int NOT NULL,
  `impuesto` int NOT NULL,
  `total` int NOT NULL,
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_producto` (`fk_producto`) USING BTREE,
  KEY `fk_factura` (`fk_compra`) USING BTREE,
  KEY `fk_factura_2` (`fk_compra`) USING BTREE,
  KEY `fk_compra` (`fk_compra`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_compras_detalle
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_configuracion
-- ----------------------------
DROP TABLE IF EXISTS `fi_configuracion`;
CREATE TABLE `fi_configuracion` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL DEFAULT '1',
  `configuracion` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `valor` varchar(600) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `activo` int NOT NULL DEFAULT '1',
  `creado_fecha` datetime DEFAULT NULL,
  `borrado` int DEFAULT '0',
  `borrado_fecha_usuario` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=78 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of fi_configuracion
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_configuracion_empresa
-- ----------------------------
DROP TABLE IF EXISTS `fi_configuracion_empresa`;
CREATE TABLE `fi_configuracion_empresa` (
  `fk_entidad` int NOT NULL COMMENT 'Antes se llamaba rowid',
  `electronica_activo` int NOT NULL DEFAULT '1' COMMENT 'Filtro para evitar solicitar token de inactivos',
  `aceptacion_firma` set('php','java') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'java' COMMENT 'Multiple Firmador',
  `ventas_firma` set('php','java') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'java' COMMENT 'Multiple Firmador',
  `utiliza_inventario` int NOT NULL DEFAULT '0' COMMENT '0-> no 1-> Si',
  `permitir_inventario_negativo` int NOT NULL COMMENT '0-> no 1-> Si',
  `integracion_romanas` int NOT NULL DEFAULT '0' COMMENT 'Integraciones Con Romanas',
  `fk_empresa_parent` int DEFAULT NULL,
  `tipo_cuenta` set('demo','produccion') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'produccion',
  `fecha_cobro` date DEFAULT NULL,
  `cobro_monto` int NOT NULL,
  `cobro_moneda` int NOT NULL DEFAULT '1',
  `periocidad_cobro` set('anual','semestral','mensual','gratis') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'anual',
  `funcion_cuenta` set('servicios_profesionales','peluquerias') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'servicios_profesionales',
  `vendedor` int DEFAULT '0',
  `entidad` int NOT NULL,
  `tipo_persona` int DEFAULT NULL COMMENT '1 = fisica \r\n2 = juridica',
  `tipo_residencia` char(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL COMMENT 'E = Extranjero\r\nR = Residente \r\nRUE = Residente Union Europea\r\n',
  `persona_nombre` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `persona_apellido1` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `persona_apellido2` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `nombre_empresa` varchar(400) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `fk_tipo_identificacion_fiscal` int DEFAULT NULL,
  `numero_identificacion` char(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `codigo_postal` char(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `correo_electronico` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `fk_sucursal` int NOT NULL DEFAULT '1',
  `nombre_fantasia` varchar(400) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nombre_direccion` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `cedula_juridica` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `sujeto_impuesto` int NOT NULL,
  `electronica_certificado` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT '0',
  `electronica_certificado_encriptado` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT '0',
  `electronica_certificado_clave` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `electronica_nombre` varchar(600) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `electronica_identificacion_tipo` int NOT NULL COMMENT '1-->Fisico 2-->juridico 3-->Dimex 4-->nite',
  `electronica_identificacion_numero` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `electronica_nombre_comercial` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `electronica_provincia` int NOT NULL,
  `electronica_canton` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `electronica_distrito` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `electronica_barrio` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `electronica_otras_senas` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `electronica_telefono` int NOT NULL,
  `electronica_fax` int NOT NULL,
  `retencion` int DEFAULT '0',
  `retencion_porcentaje` decimal(5,2) NOT NULL DEFAULT '0.00',
  `electronico_correo` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `api_access_token` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `api_cliente` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'api-prod',
  `api_usuario` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `api_password` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `api_path` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'https://api.comprobanteselectronicos.go.cr/recepcion/v1/recepcion/',
  `GTIN_distribuidor` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `cuentaBanco` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `body_correos` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `cron_job_correo_crm_actividades_por_vencer` int NOT NULL,
  UNIQUE KEY `fk_entidad` (`fk_entidad`) USING BTREE,
  KEY `fk_empresa_parent` (`fk_empresa_parent`) USING BTREE,
  KEY `fk_tipo_identificacion_fiscal` (`fk_tipo_identificacion_fiscal`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of fi_configuracion_empresa
-- ----------------------------
BEGIN;
INSERT INTO `fi_configuracion_empresa` (`fk_entidad`, `electronica_activo`, `aceptacion_firma`, `ventas_firma`, `utiliza_inventario`, `permitir_inventario_negativo`, `integracion_romanas`, `fk_empresa_parent`, `tipo_cuenta`, `fecha_cobro`, `cobro_monto`, `cobro_moneda`, `periocidad_cobro`, `funcion_cuenta`, `vendedor`, `entidad`, `tipo_persona`, `tipo_residencia`, `persona_nombre`, `persona_apellido1`, `persona_apellido2`, `nombre_empresa`, `fk_tipo_identificacion_fiscal`, `numero_identificacion`, `codigo_postal`, `correo_electronico`, `fk_sucursal`, `nombre_fantasia`, `nombre_direccion`, `cedula_juridica`, `sujeto_impuesto`, `electronica_certificado`, `electronica_certificado_encriptado`, `electronica_certificado_clave`, `electronica_nombre`, `electronica_identificacion_tipo`, `electronica_identificacion_numero`, `electronica_nombre_comercial`, `electronica_provincia`, `electronica_canton`, `electronica_distrito`, `electronica_barrio`, `electronica_otras_senas`, `electronica_telefono`, `electronica_fax`, `retencion`, `retencion_porcentaje`, `electronico_correo`, `api_access_token`, `api_cliente`, `api_usuario`, `api_password`, `api_path`, `GTIN_distribuidor`, `cuentaBanco`, `body_correos`, `cron_job_correo_crm_actividades_por_vencer`) VALUES (1, 1, 'java', 'java', 1, 0, 0, 10, 'demo', '2018-05-01', 1500, 2, 'gratis', 'peluquerias', 0, 7, 2, 'R', 'Juan Carlos', 'Morales', 'M', 'Avantec.DS SL', 1, 'B70811112', '24403', 'empresa@techsoluciones.com', 1, 'Avantec.DS SL', 'Calle Cuenca 29 2A Ponferrada\r\n', '123123123', 1, '310122786905.p12', '310122786905.p12', '2105', 'AVANCES TECNOLOGICOS V & B SOCIEDAD ANONIMA', 2, '123123123', 'AVANCES TECNOLOGICOS V B SOCIEDAD ANONIMA', 2, '07', '03', '03', 'Palmares', 63114020, 40356809, 1, 15.00, 'dbermejo@avancescr.com', 'https://idp.comprobanteselectronicos.go.cr/auth/realms/rut-stag/protocol/openid-connect/token', 'api-prod', '1', '1', 'https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion', '1235469', 'Cuenta Bancaria: 200-001-002-00-01', '<!DOCTYPE html>\n<html>\n\n<head> </head>\n\n<body>\n    <p>Estimado cliente: %NOMBRECLIENTE%</p>\n    <p>Adjuntamos el PDF correspondientes a los servicios brindados por CAFE CAMINOS.</p>\n    <table>\n        <tbody>\n            <tr>\n                <td>Documento:</td>\n                <td>Factura Electr&oacute;nica</td>\n            </tr>\n            <tr>\n                <td>Consecutivo:</td>\n                <td>%CONSECUTIVO%</td>\n            </tr>\n           \n            <tr>\n                <td>Fecha:</td>\n                <td>%FECHA%</td>\n            </tr>\n        </tbody>\n    </table>\n\n   \n</body>\n\n</html>', 0);
INSERT INTO `fi_configuracion_empresa` (`fk_entidad`, `electronica_activo`, `aceptacion_firma`, `ventas_firma`, `utiliza_inventario`, `permitir_inventario_negativo`, `integracion_romanas`, `fk_empresa_parent`, `tipo_cuenta`, `fecha_cobro`, `cobro_monto`, `cobro_moneda`, `periocidad_cobro`, `funcion_cuenta`, `vendedor`, `entidad`, `tipo_persona`, `tipo_residencia`, `persona_nombre`, `persona_apellido1`, `persona_apellido2`, `nombre_empresa`, `fk_tipo_identificacion_fiscal`, `numero_identificacion`, `codigo_postal`, `correo_electronico`, `fk_sucursal`, `nombre_fantasia`, `nombre_direccion`, `cedula_juridica`, `sujeto_impuesto`, `electronica_certificado`, `electronica_certificado_encriptado`, `electronica_certificado_clave`, `electronica_nombre`, `electronica_identificacion_tipo`, `electronica_identificacion_numero`, `electronica_nombre_comercial`, `electronica_provincia`, `electronica_canton`, `electronica_distrito`, `electronica_barrio`, `electronica_otras_senas`, `electronica_telefono`, `electronica_fax`, `retencion`, `retencion_porcentaje`, `electronico_correo`, `api_access_token`, `api_cliente`, `api_usuario`, `api_password`, `api_path`, `GTIN_distribuidor`, `cuentaBanco`, `body_correos`, `cron_job_correo_crm_actividades_por_vencer`) VALUES (2, 1, 'java', 'java', 1, 0, 0, 10, 'demo', '2018-05-01', 1500, 2, 'gratis', 'peluquerias', 0, 7, NULL, NULL, NULL, NULL, NULL, 'Cisma', NULL, NULL, '', '', 1, 'Cisma', ' Palmares', '123123123', 1, '310122786905.p12', '310122786905.p12', '2105', 'AVANCES TECNOLOGICOS V & B SOCIEDAD ANONIMA', 2, '123123123', 'AVANCES TECNOLOGICOS V B SOCIEDAD ANONIMA', 2, '07', '03', '03', 'Palmares', 63114020, 40356809, 0, 0.00, 'dbermejo@avancescr.com', 'https://idp.comprobanteselectronicos.go.cr/auth/realms/rut-stag/protocol/openid-connect/token', 'api-prod', '1', '1', 'https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion', '1235469', 'Cuenta Bancaria: 200-001-002-00-01', '<!DOCTYPE html>\r\n<html>\r\n\r\n<head> </head>\r\n\r\n<body>\r\n    <p>Estimado cliente: %NOMBRECLIENTE%</p>\r\n    <p>Adjuntamos el PDF correspondientes a los servicios brindados por CAFE CAMINOS.</p>\r\n    <table>\r\n        <tbody>\r\n            <tr>\r\n                <td>Documento:</td>\r\n                <td>Factura Electr&oacute;nica</td>\r\n            </tr>\r\n            <tr>\r\n                <td>Consecutivo:</td>\r\n                <td>%CONSECUTIVO%</td>\r\n            </tr>\r\n           \r\n            <tr>\r\n                <td>Fecha:</td>\r\n                <td>%FECHA%</td>\r\n            </tr>\r\n        </tbody>\r\n    </table>\r\n\r\n   \r\n</body>\r\n\r\n</html>', 0);
INSERT INTO `fi_configuracion_empresa` (`fk_entidad`, `electronica_activo`, `aceptacion_firma`, `ventas_firma`, `utiliza_inventario`, `permitir_inventario_negativo`, `integracion_romanas`, `fk_empresa_parent`, `tipo_cuenta`, `fecha_cobro`, `cobro_monto`, `cobro_moneda`, `periocidad_cobro`, `funcion_cuenta`, `vendedor`, `entidad`, `tipo_persona`, `tipo_residencia`, `persona_nombre`, `persona_apellido1`, `persona_apellido2`, `nombre_empresa`, `fk_tipo_identificacion_fiscal`, `numero_identificacion`, `codigo_postal`, `correo_electronico`, `fk_sucursal`, `nombre_fantasia`, `nombre_direccion`, `cedula_juridica`, `sujeto_impuesto`, `electronica_certificado`, `electronica_certificado_encriptado`, `electronica_certificado_clave`, `electronica_nombre`, `electronica_identificacion_tipo`, `electronica_identificacion_numero`, `electronica_nombre_comercial`, `electronica_provincia`, `electronica_canton`, `electronica_distrito`, `electronica_barrio`, `electronica_otras_senas`, `electronica_telefono`, `electronica_fax`, `retencion`, `retencion_porcentaje`, `electronico_correo`, `api_access_token`, `api_cliente`, `api_usuario`, `api_password`, `api_path`, `GTIN_distribuidor`, `cuentaBanco`, `body_correos`, `cron_job_correo_crm_actividades_por_vencer`) VALUES (3, 1, 'java', 'java', 1, 0, 0, 10, 'demo', '2018-05-01', 1500, 2, 'gratis', 'peluquerias', 0, 7, 1, 'R', 'Jesús', 'Lucía', 'Cortéz', 'Jesús Lucía Cortés', 1, '46868375X', '28042', 'jesus.lucia@jlcconsultores.com', 1, 'Jesús Lucía Cortés', 'Calle Bariloche, 1, 1ºE ', '123123123', 1, 'certificadoDavidBermejo_cert_out.pem', 'No se usa', '5357', 'AVANCES TECNOLOGICOS V & B SOCIEDAD ANONIMA', 2, '123123123', 'AVANCES TECNOLOGICOS V B SOCIEDAD ANONIMA', 2, '07', '03', '03', 'Palmares', 63114020, 40356809, 1, 15.00, 'dbermejo@avancescr.com', 'https://idp.comprobanteselectronicos.go.cr/auth/realms/rut-stag/protocol/openid-connect/token', 'api-prod', '1', '1', 'https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion', '1235469', 'Cuenta Bancaria: 200-001-002-00-01', '<!DOCTYPE html>\r\n<html>\r\n\r\n<head> </head>\r\n\r\n<body>\r\n    <p>Estimado cliente: %NOMBRECLIENTE%</p>\r\n    <p>Adjuntamos el PDF correspondientes a los servicios brindados por CAFE CAMINOS.</p>\r\n    <table>\r\n        <tbody>\r\n            <tr>\r\n                <td>Documento:</td>\r\n                <td>Factura Electr&oacute;nica</td>\r\n            </tr>\r\n            <tr>\r\n                <td>Consecutivo:</td>\r\n                <td>%CONSECUTIVO%</td>\r\n            </tr>\r\n           \r\n            <tr>\r\n                <td>Fecha:</td>\r\n                <td>%FECHA%</td>\r\n            </tr>\r\n        </tbody>\r\n    </table>\r\n\r\n   \r\n</body>\r\n\r\n</html>', 1);
INSERT INTO `fi_configuracion_empresa` (`fk_entidad`, `electronica_activo`, `aceptacion_firma`, `ventas_firma`, `utiliza_inventario`, `permitir_inventario_negativo`, `integracion_romanas`, `fk_empresa_parent`, `tipo_cuenta`, `fecha_cobro`, `cobro_monto`, `cobro_moneda`, `periocidad_cobro`, `funcion_cuenta`, `vendedor`, `entidad`, `tipo_persona`, `tipo_residencia`, `persona_nombre`, `persona_apellido1`, `persona_apellido2`, `nombre_empresa`, `fk_tipo_identificacion_fiscal`, `numero_identificacion`, `codigo_postal`, `correo_electronico`, `fk_sucursal`, `nombre_fantasia`, `nombre_direccion`, `cedula_juridica`, `sujeto_impuesto`, `electronica_certificado`, `electronica_certificado_encriptado`, `electronica_certificado_clave`, `electronica_nombre`, `electronica_identificacion_tipo`, `electronica_identificacion_numero`, `electronica_nombre_comercial`, `electronica_provincia`, `electronica_canton`, `electronica_distrito`, `electronica_barrio`, `electronica_otras_senas`, `electronica_telefono`, `electronica_fax`, `retencion`, `retencion_porcentaje`, `electronico_correo`, `api_access_token`, `api_cliente`, `api_usuario`, `api_password`, `api_path`, `GTIN_distribuidor`, `cuentaBanco`, `body_correos`, `cron_job_correo_crm_actividades_por_vencer`) VALUES (4, 1, 'java', 'java', 1, 0, 0, 10, 'demo', '2018-05-01', 1500, 2, 'gratis', 'peluquerias', 0, 7, 2, 'RUE', '', 'Vargas', 'Tovar', 'MIMACASIJE, SL', 1, 'B19453851', '28042', 'jesus.lucia@jlcconsultores.com', 1, 'MIMACASIJE, SL', 'Calle Bariloche, 1, 1ºE ', '123123123', 1, 'certificadoDavidBermejo_cert_out.pem', 'certificadoDavidBermejo_cert_out.pem', '5357', 'AVANCES TECNOLOGICOS V & B SOCIEDAD ANONIMA', 2, '123123123', 'AVANCES TECNOLOGICOS V B SOCIEDAD ANONIMA', 2, '07', '03', '03', 'Palmares', 63114020, 40356809, 0, 8.00, 'dbermejo@avancescr.com', 'https://idp.comprobanteselectronicos.go.cr/auth/realms/rut-stag/protocol/openid-connect/token', 'api-prod', '1', '1', 'https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion', '1235469', 'Cuenta Bancaria: 200-001-002-00-01', '<!DOCTYPE html>\r\n<html>\r\n\r\n<head> </head>\r\n\r\n<body>\r\n    <p>Estimado cliente: %NOMBRECLIENTE%</p>\r\n    <p>Adjuntamos el PDF correspondientes a los servicios brindados por CAFE CAMINOS.</p>\r\n    <table>\r\n        <tbody>\r\n            <tr>\r\n                <td>Documento:</td>\r\n                <td>Factura Electr&oacute;nica</td>\r\n            </tr>\r\n            <tr>\r\n                <td>Consecutivo:</td>\r\n                <td>%CONSECUTIVO%</td>\r\n            </tr>\r\n           \r\n            <tr>\r\n                <td>Fecha:</td>\r\n                <td>%FECHA%</td>\r\n            </tr>\r\n        </tbody>\r\n    </table>\r\n\r\n   \r\n</body>\r\n\r\n</html>', 0);
INSERT INTO `fi_configuracion_empresa` (`fk_entidad`, `electronica_activo`, `aceptacion_firma`, `ventas_firma`, `utiliza_inventario`, `permitir_inventario_negativo`, `integracion_romanas`, `fk_empresa_parent`, `tipo_cuenta`, `fecha_cobro`, `cobro_monto`, `cobro_moneda`, `periocidad_cobro`, `funcion_cuenta`, `vendedor`, `entidad`, `tipo_persona`, `tipo_residencia`, `persona_nombre`, `persona_apellido1`, `persona_apellido2`, `nombre_empresa`, `fk_tipo_identificacion_fiscal`, `numero_identificacion`, `codigo_postal`, `correo_electronico`, `fk_sucursal`, `nombre_fantasia`, `nombre_direccion`, `cedula_juridica`, `sujeto_impuesto`, `electronica_certificado`, `electronica_certificado_encriptado`, `electronica_certificado_clave`, `electronica_nombre`, `electronica_identificacion_tipo`, `electronica_identificacion_numero`, `electronica_nombre_comercial`, `electronica_provincia`, `electronica_canton`, `electronica_distrito`, `electronica_barrio`, `electronica_otras_senas`, `electronica_telefono`, `electronica_fax`, `retencion`, `retencion_porcentaje`, `electronico_correo`, `api_access_token`, `api_cliente`, `api_usuario`, `api_password`, `api_path`, `GTIN_distribuidor`, `cuentaBanco`, `body_correos`, `cron_job_correo_crm_actividades_por_vencer`) VALUES (5, 1, 'java', 'java', 1, 0, 0, 10, 'demo', '2018-05-01', 1500, 2, 'gratis', 'peluquerias', 0, 7, 1, 'R', 'Marlisa', 'A. Richters', '.', 'Marlisa A. Richters ', 1, 'X2264487E', '24002', 'marichters@hotmail.com', 1, 'Centro de Idiomas Janet ', 'Calle Joaquín Costa nº2  ', '123123123', 1, '310122786905.p12', '310122786905.p12', '2105', 'AVANCES TECNOLOGICOS V & B SOCIEDAD ANONIMA', 2, '123123123', 'AVANCES TECNOLOGICOS V B SOCIEDAD ANONIMA', 2, '07', '03', '03', 'Palmares', 63114020, 40356809, 0, 0.00, 'dbermejo@avancescr.com', 'https://idp.comprobanteselectronicos.go.cr/auth/realms/rut-stag/protocol/openid-connect/token', 'api-prod', '1', '1', 'https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion', '1235469', 'Cuenta Bancaria: 200-001-002-00-01', '<!DOCTYPE html>\r\n<html>\r\n\r\n<head> </head>\r\n\r\n<body>\r\n    <p>Estimado cliente: %NOMBRECLIENTE%</p>\r\n    <p>Adjuntamos el PDF correspondientes a los servicios brindados por CAFE CAMINOS.</p>\r\n    <table>\r\n        <tbody>\r\n            <tr>\r\n                <td>Documento:</td>\r\n                <td>Factura Electr&oacute;nica</td>\r\n            </tr>\r\n            <tr>\r\n                <td>Consecutivo:</td>\r\n                <td>%CONSECUTIVO%</td>\r\n            </tr>\r\n           \r\n            <tr>\r\n                <td>Fecha:</td>\r\n                <td>%FECHA%</td>\r\n            </tr>\r\n        </tbody>\r\n    </table>\r\n\r\n   \r\n</body>\r\n\r\n</html>', 0);
INSERT INTO `fi_configuracion_empresa` (`fk_entidad`, `electronica_activo`, `aceptacion_firma`, `ventas_firma`, `utiliza_inventario`, `permitir_inventario_negativo`, `integracion_romanas`, `fk_empresa_parent`, `tipo_cuenta`, `fecha_cobro`, `cobro_monto`, `cobro_moneda`, `periocidad_cobro`, `funcion_cuenta`, `vendedor`, `entidad`, `tipo_persona`, `tipo_residencia`, `persona_nombre`, `persona_apellido1`, `persona_apellido2`, `nombre_empresa`, `fk_tipo_identificacion_fiscal`, `numero_identificacion`, `codigo_postal`, `correo_electronico`, `fk_sucursal`, `nombre_fantasia`, `nombre_direccion`, `cedula_juridica`, `sujeto_impuesto`, `electronica_certificado`, `electronica_certificado_encriptado`, `electronica_certificado_clave`, `electronica_nombre`, `electronica_identificacion_tipo`, `electronica_identificacion_numero`, `electronica_nombre_comercial`, `electronica_provincia`, `electronica_canton`, `electronica_distrito`, `electronica_barrio`, `electronica_otras_senas`, `electronica_telefono`, `electronica_fax`, `retencion`, `retencion_porcentaje`, `electronico_correo`, `api_access_token`, `api_cliente`, `api_usuario`, `api_password`, `api_path`, `GTIN_distribuidor`, `cuentaBanco`, `body_correos`, `cron_job_correo_crm_actividades_por_vencer`) VALUES (6, 1, 'java', 'java', 0, 1, 0, 1, 'produccion', '2024-11-22', 10, 1, 'anual', 'servicios_profesionales', 0, 7, 1, '1', '1', '1', '1', 'Demo', 1, '1', '28001', 'dbermejo@avancescr.com', 1, '2', 'Aqio', '123', 1, '0', '1', '23', 's', 1, '1', '1', 1, 'a1', '11', '1', '1', 1, 1, 0, 0.00, '1', '1', 'api-prod', '1', '1', 'https://api.comprobanteselectronicos.go.cr/recepcion/v1/recepcion/', '1', '1', '1', 1);
COMMIT;

-- ----------------------------
-- Table structure for fi_configuracion_empresa_modulos_activos
-- ----------------------------
DROP TABLE IF EXISTS `fi_configuracion_empresa_modulos_activos`;
CREATE TABLE `fi_configuracion_empresa_modulos_activos` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_entidad` int NOT NULL,
  `fk_modulo` int NOT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_modulo` (`fk_modulo`) USING BTREE,
  CONSTRAINT `fi_configuracion_empresa_modulos_activos_ibfk_1` FOREIGN KEY (`fk_modulo`) REFERENCES `utilidades_apoyo`.`diccionario_modulos` (`rowid`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Para saber que modulos tienen activos que empresa';

-- ----------------------------
-- Records of fi_configuracion_empresa_modulos_activos
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_cotizacion_detalle
-- ----------------------------
DROP TABLE IF EXISTS `fi_cotizacion_detalle`;
CREATE TABLE `fi_cotizacion_detalle` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_entidad` int NOT NULL,
  `fk_factura` int NOT NULL COMMENT 'apunta al rowid de fi_cotizaciones\r\n',
  `tipo` int NOT NULL DEFAULT '1' COMMENT '1-->producto 2--> Servicios',
  `fk_producto` int NOT NULL,
  `fk_lote` int DEFAULT NULL,
  `num_linea` int DEFAULT NULL,
  `label` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `ref` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `label_extra` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `tipo_impuesto` int NOT NULL,
  `cantidad` decimal(16,3) NOT NULL,
  `subtotal` decimal(15,5) NOT NULL,
  `impuesto` decimal(15,5) NOT NULL,
  `exoneracion` int DEFAULT '0',
  `ImpuestoNeto` decimal(18,5) DEFAULT NULL COMMENT 'Usase si tienes exoneracion',
  `total` decimal(15,5) NOT NULL,
  `precio_original` decimal(15,5) NOT NULL,
  `descuento_tipo` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descuento_aplicado` int NOT NULL,
  `descuento_valor_final` decimal(15,5) NOT NULL,
  `precio_costo` decimal(10,2) DEFAULT NULL COMMENT 'precio Unitario IMPUSTO INCLUIDO',
  `CABYS_codigo` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `aplica_re` int NOT NULL DEFAULT '0' COMMENT '1 = si, 0 = no',
  `monto_impuesto_re` decimal(10,2) DEFAULT NULL COMMENT 'monto recargo equivalencia',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `descripcion` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci COMMENT 'Descripcion del producto',
  `subtotal_2` decimal(15,2) DEFAULT NULL COMMENT 'Subtotal Real',
  `impuesto_id` int DEFAULT '0' COMMENT 'ID del impuesto',
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_producto` (`fk_producto`) USING BTREE,
  KEY `fk_factura` (`fk_factura`) USING BTREE,
  KEY `fk_entidad` (`fk_entidad`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=605 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_cotizacion_detalle
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_cotizaciones
-- ----------------------------
DROP TABLE IF EXISTS `fi_cotizaciones`;
CREATE TABLE `fi_cotizaciones` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL DEFAULT '1',
  `moneda` int NOT NULL DEFAULT '1',
  `moneda_tipo_cambio` decimal(10,2) NOT NULL DEFAULT '0.00',
  `fk_usuario_crear` int NOT NULL,
  `fk_usuario_validar` int DEFAULT NULL,
  `fecha` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `referencia` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Almacena el consecutivo de la cotizacion',
  `referencia_proveedor` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `tipo` int NOT NULL DEFAULT '0' COMMENT '0 -> contado    1-> Credito',
  `forma_pago` int NOT NULL,
  `detalle` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Referencia de la cotización',
  `detalle_publico` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `fk_tercero` int DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `impuesto` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `estado` int NOT NULL COMMENT '0 Borrador  ----- 1 Validada  3--- abandonada',
  `pagado` int NOT NULL DEFAULT '0',
  `estado_pagada` int NOT NULL DEFAULT '0' COMMENT '0- No   1-  Si',
  `eliminado` int NOT NULL DEFAULT '0',
  `fecha_creacion_server` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `_vuelto` int DEFAULT NULL,
  `txt_cliente` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `apodo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `fk_impuesto` int NOT NULL DEFAULT '1' COMMENT 'Apunta a la tabla diccionario_impuestos',
  `fecha_entrega` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `asesor_comercial_txt` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `confirmado` varchar(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT '0' COMMENT 'Confirmada la cotizacion para editar',
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_usuario_crear` (`fk_usuario_crear`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=719 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_cotizaciones
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_cotizaciones_configuracion
-- ----------------------------
DROP TABLE IF EXISTS `fi_cotizaciones_configuracion`;
CREATE TABLE `fi_cotizaciones_configuracion` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL DEFAULT '1',
  `siguiente_borrador` int NOT NULL,
  `siguiente_documento` int NOT NULL,
  `fk_serie` int NOT NULL DEFAULT '1' COMMENT 'No tengo claro si esto existe ya',
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='Configuracion de las facturas';

-- ----------------------------
-- Records of fi_cotizaciones_configuracion
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_custom_labels
-- ----------------------------
DROP TABLE IF EXISTS `fi_custom_labels`;
CREATE TABLE `fi_custom_labels` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `label` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `label_replace` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `entidad` int NOT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `entidad` (`entidad`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of fi_custom_labels
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_europa_cotizaciones
-- ----------------------------
DROP TABLE IF EXISTS `fi_europa_cotizaciones`;
CREATE TABLE `fi_europa_cotizaciones` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL DEFAULT '1',
  `tipo` set('cotizacion') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'cotizacion' COMMENT 'No tan importante en esta seccion',
  `moneda` int DEFAULT '1',
  `moneda_tipo_cambio` decimal(10,2) NOT NULL DEFAULT '0.00',
  `fk_usuario_crear` int NOT NULL,
  `fk_usuario_validar` int DEFAULT NULL,
  `fk_usuario_validar_fecha` datetime DEFAULT NULL,
  `fecha` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `referencia` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Almacena el consecutivo de la cotizacion',
  `referencia_proveedor` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `fk_proyecto` int DEFAULT NULL COMMENT 'Esto es optativo',
  `forma_pago` int NOT NULL,
  `detalle` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Referencia de la cotización',
  `detalle_publico` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `fk_tercero` int DEFAULT NULL,
  `fk_tercero_txt` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `subtotal_pre_retencion` decimal(10,2) DEFAULT NULL COMMENT 'Previo a Impuesto',
  `impuesto_iva` decimal(10,2) DEFAULT NULL COMMENT 'Total Iva Sumado',
  `impuesto_iva_equivalencia` decimal(10,2) DEFAULT NULL,
  `impuesto_retencion_irpf` decimal(10,2) DEFAULT NULL COMMENT 'IRPF cuando aplica',
  `total` decimal(10,2) DEFAULT NULL,
  `IVA_0` decimal(10,2) DEFAULT NULL COMMENT 'Totalizado IVA 0',
  `IVA_10` decimal(10,2) DEFAULT NULL COMMENT 'Totalizado Iva 10',
  `IVA_4` decimal(10,2) DEFAULT '0.00' COMMENT 'Toalizado IVA 4%',
  `IVA_21` decimal(10,2) DEFAULT '0.00',
  `RE_5_2` decimal(10,2) DEFAULT '0.00' COMMENT 'Recargo Equivalencia 5,2',
  `RE_1_4` decimal(10,2) DEFAULT '0.00' COMMENT 'Recargo Equivalencia 1.4 ',
  `RE_0_5` decimal(10,2) DEFAULT '0.00' COMMENT 'Recardo Equivalencia 0.5',
  `RE_0_75` decimal(10,2) DEFAULT '0.00' COMMENT 'Recargo Equivalencia 0.75',
  `estado` int NOT NULL COMMENT '0 Borrador  ----- 1 Validada  3--- abandonada',
  `pagado` int NOT NULL DEFAULT '0' COMMENT 'Monto Pagado',
  `estado_pagada` int NOT NULL DEFAULT '0' COMMENT '0- No   1-  Si',
  `creado_fecha` datetime NOT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_usuario_crear` (`fk_usuario_crear`) USING BTREE,
  KEY `Index 3` (`referencia`) USING BTREE,
  KEY `Index 4` (`fk_usuario_validar`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_europa_cotizaciones
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_europa_cotizaciones_configuracion
-- ----------------------------
DROP TABLE IF EXISTS `fi_europa_cotizaciones_configuracion`;
CREATE TABLE `fi_europa_cotizaciones_configuracion` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL DEFAULT '1',
  `siguiente_borrador` int NOT NULL,
  `siguiente_documento` int NOT NULL,
  `fk_serie` int NOT NULL DEFAULT '1' COMMENT 'Por defecto es 1',
  `fk_serie_modelo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL COMMENT 'Modelo de la Serie por ejemplo -000YEAR-  Reemplazamos YEAR por el año con numeros',
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='Configuracion de las facturas';

-- ----------------------------
-- Records of fi_europa_cotizaciones_configuracion
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_europa_cotizaciones_detalle
-- ----------------------------
DROP TABLE IF EXISTS `fi_europa_cotizaciones_detalle`;
CREATE TABLE `fi_europa_cotizaciones_detalle` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL,
  `fk_factura` int NOT NULL COMMENT 'apunta al rowid de fi_cotizaciones\r\n',
  `fk_producto` int DEFAULT NULL COMMENT 'Si es zona libre esto es NULL',
  `tipo` int NOT NULL DEFAULT '1' COMMENT '1-->producto 2--> Servicios',
  `num_linea` int DEFAULT NULL,
  `label` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `ref` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `label_extra` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `precio_original` decimal(15,5) NOT NULL,
  `precio_costo` decimal(10,2) DEFAULT NULL COMMENT 'precio Unitario IMPUSTO INCLUIDO',
  `precio_unitario` decimal(15,5) NOT NULL,
  `cantidad` decimal(16,3) NOT NULL,
  `descuento_tipo` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `descuento_aplicado` int DEFAULT '0',
  `descuento_valor_final` decimal(15,5) DEFAULT '0.00000',
  `subtotal_pre_retencion` decimal(15,5) DEFAULT NULL,
  `subtotal` decimal(15,5) DEFAULT NULL COMMENT 'precio x cantidad con descuentos incluidos',
  `impuesto_iva_id` int DEFAULT '0' COMMENT 'ID del impuesto',
  `impuesto_iva_monto` decimal(18,5) DEFAULT NULL COMMENT 'Total IVA Sumado',
  `impuesto_iva_porcentaje` decimal(18,5) DEFAULT NULL COMMENT 'IVA Porcentaje',
  `impuesto_iva_equivalencia_aplica` int NOT NULL DEFAULT '0' COMMENT '1 = si, 0 = no',
  `impuesto_iva_equivalencia_monto` decimal(18,5) DEFAULT NULL COMMENT 'Total IVA Sumado',
  `impuesto_iva_equivalencia_porcentaje` decimal(18,5) DEFAULT NULL COMMENT 'Total IVA Sumado',
  `impuesto_retencion_aplica` int NOT NULL DEFAULT '0' COMMENT '1 = si, 0 = no',
  `impuesto_retencion_monto` decimal(10,2) DEFAULT NULL COMMENT 'monto recargo equivalencia',
  `impuesto_retencion_porcentaje` decimal(10,2) DEFAULT NULL COMMENT 'Porcentaje recargo equivalencia',
  `total` decimal(15,5) NOT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `descripcion` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci COMMENT 'Descripcion del producto',
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_producto` (`fk_producto`) USING BTREE,
  KEY `fk_factura` (`fk_factura`) USING BTREE,
  KEY `Index 5` (`num_linea`) USING BTREE,
  KEY `total` (`total`) USING BTREE,
  KEY `entidad` (`entidad`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_europa_cotizaciones_detalle
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_europa_facturas
-- ----------------------------
DROP TABLE IF EXISTS `fi_europa_facturas`;
CREATE TABLE `fi_europa_facturas` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL DEFAULT '1',
  `tipo` set('F1','F2','F3','R1') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT 'F1' COMMENT 'Si es importante',
  `moneda` int DEFAULT '1',
  `moneda_tipo_cambio` decimal(10,2) NOT NULL DEFAULT '0.00',
  `fk_usuario_crear` int NOT NULL,
  `fk_usuario_validar` int DEFAULT NULL,
  `fk_usuario_validar_fecha` datetime DEFAULT NULL,
  `fecha` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `referencia` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Almacena el consecutivo de la cotizacion',
  `referencia_serie` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL COMMENT 'Apunta a la serie, necesario para hacienda',
  `fk_proyecto` int DEFAULT NULL COMMENT 'Esto es optativo',
  `forma_pago` int NOT NULL,
  `detalle` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Texto libre para el usuario (Esto aparece en el PDF)',
  `fk_tercero` int DEFAULT NULL,
  `fk_tercero_txt` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `fk_tercero_identificacion` tinytext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `subtotal_pre_retencion` decimal(10,2) DEFAULT NULL COMMENT 'Previo a Impuesto',
  `impuesto_iva` decimal(10,2) DEFAULT NULL COMMENT 'Total Iva Sumado',
  `impuesto_iva_equivalencia` decimal(10,2) DEFAULT NULL,
  `impuesto_retencion_irpf` decimal(10,2) DEFAULT NULL COMMENT 'IRPF cuando aplica',
  `total` decimal(10,2) DEFAULT NULL,
  `IVA_0` decimal(10,2) DEFAULT NULL COMMENT 'Totalizado IVA 0',
  `IVA_10` decimal(10,2) DEFAULT NULL COMMENT 'Totalizado Iva 10',
  `IVA_4` decimal(10,2) DEFAULT '0.00' COMMENT 'Toalizado IVA 4%',
  `IVA_21` decimal(10,2) DEFAULT '0.00',
  `RE_5_2` decimal(10,2) DEFAULT '0.00' COMMENT 'Recargo Equivalencia 5,2',
  `RE_1_4` decimal(10,2) DEFAULT '0.00' COMMENT 'Recargo Equivalencia 1.4 ',
  `RE_0_5` decimal(10,2) DEFAULT '0.00' COMMENT 'Recardo Equivalencia 0.5',
  `RE_0_75` decimal(10,2) DEFAULT '0.00' COMMENT 'Recargo Equivalencia 0.75',
  `estado` int NOT NULL COMMENT '0 Borrador  ----- 1 Validada  3--- abandonada',
  `estado_hacienda` int NOT NULL DEFAULT '1' COMMENT 'utilidades apoyo diccionario',
  `estado_verifactum_registro` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `estado_verifactum_envio` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Esto Literal De Hacienda',
  `estado_pagada` int NOT NULL DEFAULT '0' COMMENT '0- No   1-  Si',
  `pagado` int NOT NULL DEFAULT '0' COMMENT 'Monto Pagado',
  `creado_fecha` datetime NOT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `fecha_creacion_server` timestamp NULL DEFAULT NULL,
  `envio_correo_factura` int DEFAULT '0',
  `envio_correo_factura_fecha` date DEFAULT NULL,
  `envio_correo_factura_correo` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  `xml_huella` varchar(1000) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `xml_IDVersion` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `xml_IDEmisorFactura` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `xml_huella_sha256` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `xml_FechaHoraHusoGenRegistro` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `xml_hacienda_enviado` int DEFAULT '0',
  `xml_hacienda_enviado_fecha` datetime DEFAULT NULL COMMENT 'Cuando se Mando a Hacienda',
  `verifactum_produccion` int DEFAULT '0',
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_usuario_crear` (`fk_usuario_crear`) USING BTREE,
  KEY `Index 3` (`referencia`) USING BTREE,
  KEY `Index 4` (`fk_usuario_validar`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_europa_facturas
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_europa_facturas_configuracion
-- ----------------------------
DROP TABLE IF EXISTS `fi_europa_facturas_configuracion`;
CREATE TABLE `fi_europa_facturas_configuracion` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL DEFAULT '1',
  `tipo` set('factura','simplificada') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'factura' COMMENT 'Que tipo de Documento es',
  `siguiente_borrador` int NOT NULL,
  `siguiente_documento` int NOT NULL,
  `fk_serie` int NOT NULL DEFAULT '1' COMMENT 'Por defecto es 1',
  `fk_serie_modelo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL COMMENT 'Modelo de la Serie por ejemplo -000YEAR-  Reemplazamos YEAR por el año con numeros',
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='Configuracion de las facturas';

-- ----------------------------
-- Records of fi_europa_facturas_configuracion
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_europa_facturas_detalle
-- ----------------------------
DROP TABLE IF EXISTS `fi_europa_facturas_detalle`;
CREATE TABLE `fi_europa_facturas_detalle` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL,
  `fk_factura` int NOT NULL COMMENT 'apunta al rowid de fi_cotizaciones\r\n',
  `fk_producto` int DEFAULT NULL COMMENT 'Si es zona libre esto es NULL',
  `tipo` int NOT NULL DEFAULT '1' COMMENT '1-->producto 2--> Servicios',
  `num_linea` int DEFAULT NULL,
  `label` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `ref` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `label_extra` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `precio_original` decimal(15,5) NOT NULL,
  `precio_costo` decimal(10,2) DEFAULT NULL COMMENT 'precio Unitario IMPUSTO INCLUIDO',
  `precio_unitario` decimal(15,5) NOT NULL,
  `cantidad` decimal(16,3) NOT NULL,
  `descuento_tipo` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `descuento_aplicado` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT '0',
  `descuento_valor_final` decimal(15,5) DEFAULT '0.00000',
  `subtotal_pre_retencion` decimal(15,5) DEFAULT NULL,
  `subtotal` decimal(15,5) DEFAULT NULL COMMENT 'precio x cantidad con descuentos incluidos',
  `impuesto_iva_id` int DEFAULT '0' COMMENT 'ID del impuesto',
  `impuesto_iva_monto` decimal(18,5) DEFAULT NULL COMMENT 'Total IVA Sumado',
  `impuesto_iva_porcentaje` decimal(18,5) DEFAULT NULL COMMENT 'IVA Porcentaje',
  `impuesto_iva_equivalencia_aplica` int NOT NULL DEFAULT '0' COMMENT '1 = si, 0 = no',
  `impuesto_iva_equivalencia_monto` decimal(18,5) DEFAULT NULL COMMENT 'Total IVA Sumado',
  `impuesto_iva_equivalencia_porcentaje` decimal(18,5) DEFAULT NULL COMMENT 'Total IVA Sumado',
  `impuesto_retencion_aplica` int NOT NULL DEFAULT '0' COMMENT '1 = si, 0 = no',
  `impuesto_retencion_monto` decimal(10,2) DEFAULT NULL COMMENT 'monto recargo equivalencia',
  `impuesto_retencion_porcentaje` decimal(10,2) DEFAULT NULL COMMENT 'Porcentaje recargo equivalencia',
  `total` decimal(15,5) NOT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `descripcion` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci COMMENT 'Descripcion del producto',
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_producto` (`fk_producto`) USING BTREE,
  KEY `fk_factura` (`fk_factura`) USING BTREE,
  KEY `Index 5` (`num_linea`) USING BTREE,
  KEY `total` (`total`) USING BTREE,
  KEY `entidad` (`entidad`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_europa_facturas_detalle
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_europa_facturas_huellas
-- ----------------------------
DROP TABLE IF EXISTS `fi_europa_facturas_huellas`;
CREATE TABLE `fi_europa_facturas_huellas` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_factura` int NOT NULL DEFAULT '0',
  `entidad` int NOT NULL DEFAULT '0',
  `IDEmisorFactura` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `NumSerieFactura` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `FechaExpedicionFactura` date DEFAULT NULL,
  `TipoFactura` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CuotaTotal` decimal(10,2) DEFAULT NULL,
  `ImporteTotal` decimal(10,2) DEFAULT NULL,
  `Huella` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `FechaHoraHusoGenRegistro` datetime DEFAULT NULL,
  `huella_anterior` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `respuesta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Respuesta de Hacienda',
  `respuesta_estado_envio` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `respuesta_estado_registro` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `respuesta_descripcion_registro_descripcion` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `respuesta_codigo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `respuesta_tipo_operacion` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `idx_fk_factura` (`fk_factura`) USING BTREE,
  KEY `idx_entidad` (`entidad`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of fi_europa_facturas_huellas
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_europa_facturas_huellas_comunicacion
-- ----------------------------
DROP TABLE IF EXISTS `fi_europa_facturas_huellas_comunicacion`;
CREATE TABLE `fi_europa_facturas_huellas_comunicacion` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_factura` int DEFAULT NULL,
  `entidad` int DEFAULT NULL,
  `status_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_respuesta` varchar(600) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_fecha` datetime DEFAULT NULL,
  KEY `Index 1` (`rowid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Control de comunicacion con Hacienda';

-- ----------------------------
-- Records of fi_europa_facturas_huellas_comunicacion
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_europa_facturas_pagos
-- ----------------------------
DROP TABLE IF EXISTS `fi_europa_facturas_pagos`;
CREATE TABLE `fi_europa_facturas_pagos` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_factura` int NOT NULL,
  `entidad` int DEFAULT NULL,
  `forma_pago` int NOT NULL,
  `monto` double NOT NULL,
  `comentario` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `usuario` int NOT NULL,
  `fecha_registrado` datetime NOT NULL,
  `fecha_pago` date NOT NULL,
  PRIMARY KEY (`rowid`),
  KEY `fk_factura` (`fk_factura`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_europa_facturas_pagos
-- ----------------------------
BEGIN;
INSERT INTO `fi_europa_facturas_pagos` (`rowid`, `fk_factura`, `entidad`, `forma_pago`, `monto`, `comentario`, `usuario`, `fecha_registrado`, `fecha_pago`) VALUES (1, 131, 4, 1, 1000, '', 8, '2024-11-21 17:10:31', '2024-11-21');
INSERT INTO `fi_europa_facturas_pagos` (`rowid`, `fk_factura`, `entidad`, `forma_pago`, `monto`, `comentario`, `usuario`, `fecha_registrado`, `fecha_pago`) VALUES (2, 131, 4, 1, 300, '', 8, '2024-11-21 17:10:54', '2024-11-21');
COMMIT;

-- ----------------------------
-- Table structure for fi_factura_ICE
-- ----------------------------
DROP TABLE IF EXISTS `fi_factura_ICE`;
CREATE TABLE `fi_factura_ICE` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_factura` int NOT NULL,
  `ocICE` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `ocICERazon` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `ocICEFecha` datetime DEFAULT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of fi_factura_ICE
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_factura_prismart
-- ----------------------------
DROP TABLE IF EXISTS `fi_factura_prismart`;
CREATE TABLE `fi_factura_prismart` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_factura` int NOT NULL,
  `numeroVendedor` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `numeroOrden` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `enviarGLN` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `orden` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `numeroRecepcion` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `notasAdicionales` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of fi_factura_prismart
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_facturas
-- ----------------------------
DROP TABLE IF EXISTS `fi_facturas`;
CREATE TABLE `fi_facturas` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL DEFAULT '1',
  `moneda` int NOT NULL DEFAULT '1' COMMENT '1 Colones 2-- Dolares',
  `actividad` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `moneda_tipo_cambio` decimal(10,2) NOT NULL DEFAULT '1.00',
  `fk_usuario_crear` int NOT NULL,
  `fk_usuario_validar` int DEFAULT NULL,
  `fecha_validacion` datetime DEFAULT NULL,
  `fecha` date NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `referencia` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tipo` int NOT NULL DEFAULT '1' COMMENT '0 -> contado    1-> Credito',
  `forma_pago` int NOT NULL,
  `detalle` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `notageneral` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `fk_tercero` int DEFAULT NULL,
  `nombre_cliente` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `subtotal` decimal(30,5) DEFAULT NULL,
  `impuesto` decimal(30,5) DEFAULT NULL,
  `total` decimal(30,5) DEFAULT NULL,
  `servicio_mesa` int DEFAULT '0',
  `numero_placa` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `numero_guia` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `recibido` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `estado` int NOT NULL COMMENT '0 Borrador  ----- 1 Validada  3--- abandonada',
  `pagado` decimal(30,5) NOT NULL DEFAULT '0.00000',
  `estado_pagada` int NOT NULL DEFAULT '0' COMMENT '0- No   1-  Si',
  `eliminado` int NOT NULL DEFAULT '0',
  `fecha_creacion_server` timestamp NULL DEFAULT NULL,
  `manual` int DEFAULT NULL,
  `cafe_mesa` int DEFAULT NULL,
  `electronica_enviada` int DEFAULT NULL,
  `electronica_enviada_fecha` datetime DEFAULT NULL,
  `electronica_resultado` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `electronica_resultado_txt` varchar(600) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `electronica_resultado_fecha` datetime DEFAULT NULL,
  `electronica_error` int NOT NULL DEFAULT '0',
  `electronica_error_txt` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `rechequeo_factura` int DEFAULT '0',
  `envio_correo_factura` int DEFAULT '0',
  `envio_correo_factura_fecha` date DEFAULT NULL,
  `envio_correo_factura_correo` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `envio_correo_instant` int DEFAULT '0',
  `envio_correo_instant_fecha` datetime DEFAULT NULL,
  `envio_correo_instant_correo` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `situacion_comprobante` int NOT NULL DEFAULT '1' COMMENT '1-->Normal  2-->Contingencia  3--> Sin Internet',
  `consecutivo` varchar(22) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `pago_1` int DEFAULT NULL,
  `pago_2` int DEFAULT NULL,
  `pago_3` int DEFAULT NULL,
  `pago_4` int DEFAULT NULL,
  `pago_5` int DEFAULT NULL,
  `pago_99` int DEFAULT NULL,
  `clave` varchar(52) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `version` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT '4.3',
  `electronica_tipo` set('tiquete','factura') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'factura' COMMENT 'Todos nacen en Factura',
  `TotalServGravados` decimal(18,5) DEFAULT NULL,
  `TotalServExentos` decimal(18,5) DEFAULT NULL,
  `TotalServExonerado` decimal(18,5) DEFAULT NULL,
  `TotalMercanciasGravadas` decimal(18,5) DEFAULT NULL,
  `TotalMercanciasExentas` decimal(18,5) DEFAULT NULL,
  `TotalMercExonerada` decimal(18,5) DEFAULT NULL,
  `TotalGravado` decimal(18,5) DEFAULT NULL,
  `TotalExento` decimal(18,5) DEFAULT NULL,
  `TotalExonerado` decimal(18,5) DEFAULT NULL,
  `TotalVenta` decimal(18,5) DEFAULT NULL,
  `TotalDescuentos` decimal(18,5) DEFAULT NULL,
  `TotalVentaNeta` decimal(18,5) DEFAULT NULL,
  `TotalImpuesto` decimal(18,5) DEFAULT NULL,
  `TotalComprobante` decimal(18,5) DEFAULT NULL,
  `total_gti` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Puede sacarseel reporte tipo GTI',
  `prismart` int DEFAULT '0',
  `lista_impresion` int DEFAULT '0',
  `numeroOrdenFruti` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `plazoOrdenFruti` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_usuario_crear` (`fk_usuario_crear`) USING BTREE,
  KEY `electronica_enviada` (`electronica_enviada`) USING BTREE,
  KEY `entidad` (`entidad`) USING BTREE,
  KEY `estado` (`estado`) USING BTREE,
  KEY `fecha_creacion_server` (`fecha_creacion_server`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_facturas
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_facturas_borrar
-- ----------------------------
DROP TABLE IF EXISTS `fi_facturas_borrar`;
CREATE TABLE `fi_facturas_borrar` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL DEFAULT '1',
  `moneda` int NOT NULL DEFAULT '1' COMMENT '1 Euros 2-- Dolares',
  `actividad` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `moneda_tipo_cambio` decimal(10,2) NOT NULL DEFAULT '1.00',
  `fk_usuario_crear` int NOT NULL,
  `fk_usuario_validar` int NOT NULL,
  `fecha_validacion` datetime DEFAULT NULL,
  `fecha` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `referencia` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tipo` int NOT NULL DEFAULT '1' COMMENT '0 -> contado    1-> Credito',
  `forma_pago` int NOT NULL,
  `detalle` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `notageneral` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `fk_tercero` int DEFAULT NULL,
  `nombre_cliente` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `subtotal` decimal(30,5) NOT NULL,
  `impuesto` decimal(30,5) NOT NULL,
  `total` decimal(30,5) NOT NULL,
  `servicio_mesa` int DEFAULT '0',
  `numero_placa` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `numero_guia` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `recibido` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `estado` int NOT NULL COMMENT '0 Borrador  ----- 1 Validada  3--- abandonada',
  `pagado` decimal(30,5) NOT NULL DEFAULT '0.00000',
  `estado_pagada` int NOT NULL DEFAULT '0' COMMENT '0- No   1-  Si',
  `eliminado` int NOT NULL DEFAULT '0',
  `fecha_creacion_server` timestamp NULL DEFAULT NULL,
  `manual` int NOT NULL,
  `cafe_mesa` int NOT NULL,
  `electronica_enviada` int NOT NULL,
  `electronica_enviada_fecha` datetime NOT NULL,
  `electronica_resultado` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `electronica_resultado_txt` varchar(600) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `electronica_resultado_fecha` datetime DEFAULT NULL,
  `electronica_error` int NOT NULL DEFAULT '0',
  `electronica_error_txt` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `rechequeo_factura` int DEFAULT '0',
  `envio_correo_factura` int DEFAULT '0',
  `envio_correo_factura_fecha` date DEFAULT NULL,
  `envio_correo_factura_correo` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `envio_correo_instant` int DEFAULT '0',
  `envio_correo_instant_fecha` datetime DEFAULT NULL,
  `envio_correo_instant_correo` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `situacion_comprobante` int NOT NULL DEFAULT '1' COMMENT '1-->Normal  2-->Contingencia  3--> Sin Internet',
  `consecutivo` varchar(22) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `pago_1` int DEFAULT NULL,
  `pago_2` int DEFAULT NULL,
  `pago_3` int DEFAULT NULL,
  `pago_4` int DEFAULT NULL,
  `pago_5` int DEFAULT NULL,
  `pago_99` int DEFAULT NULL,
  `clave` varchar(52) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `version` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT '4.3',
  `electronica_tipo` set('tiquete','factura') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'factura' COMMENT 'Todos nacen en Factura',
  `TotalServGravados` decimal(18,5) DEFAULT NULL,
  `TotalServExentos` decimal(18,5) DEFAULT NULL,
  `TotalServExonerado` decimal(18,5) DEFAULT NULL,
  `TotalMercanciasGravadas` decimal(18,5) DEFAULT NULL,
  `TotalMercanciasExentas` decimal(18,5) DEFAULT NULL,
  `TotalMercExonerada` decimal(18,5) DEFAULT NULL,
  `TotalGravado` decimal(18,5) DEFAULT NULL,
  `TotalExento` decimal(18,5) DEFAULT NULL,
  `TotalExonerado` decimal(18,5) DEFAULT NULL,
  `TotalVenta` decimal(18,5) DEFAULT NULL,
  `TotalDescuentos` decimal(18,5) DEFAULT NULL,
  `TotalVentaNeta` decimal(18,5) DEFAULT NULL,
  `TotalImpuesto` decimal(18,5) DEFAULT NULL,
  `TotalComprobante` decimal(18,5) DEFAULT NULL,
  `total_gti` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Puede sacarseel reporte tipo GTI',
  `prismart` int DEFAULT '0',
  `lista_impresion` int DEFAULT '0',
  `numeroOrdenFruti` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `plazoOrdenFruti` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_usuario_crear` (`fk_usuario_crear`) USING BTREE,
  KEY `electronica_enviada` (`electronica_enviada`) USING BTREE,
  KEY `entidad` (`entidad`) USING BTREE,
  KEY `estado` (`estado`) USING BTREE,
  KEY `fecha_creacion_server` (`fecha_creacion_server`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_facturas_borrar
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_facturas_configuracion
-- ----------------------------
DROP TABLE IF EXISTS `fi_facturas_configuracion`;
CREATE TABLE `fi_facturas_configuracion` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL DEFAULT '1',
  `siguiente_borrador` int NOT NULL,
  `siguiente_factura` int NOT NULL,
  `siguiente_borrador_nc` int NOT NULL DEFAULT '1',
  `siguiente_nc` int NOT NULL DEFAULT '1',
  `siguiente_borrrador_nd` int NOT NULL DEFAULT '1',
  `siguiente_nd` int NOT NULL DEFAULT '1',
  `siguiente_borrador_ticket` int DEFAULT '1',
  `siguiente_ticket` int DEFAULT '1',
  `siguiente_recepcion_aceptada` int NOT NULL DEFAULT '1',
  `siguiente_recepcion_rechazada` int NOT NULL DEFAULT '1',
  `siguiente_simplificada_borrador` int NOT NULL DEFAULT '1' COMMENT 'version 4.3',
  `siguiente_simplificada` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=536 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='Configuracion de las facturas';

-- ----------------------------
-- Records of fi_facturas_configuracion
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_facturas_detalle
-- ----------------------------
DROP TABLE IF EXISTS `fi_facturas_detalle`;
CREATE TABLE `fi_facturas_detalle` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_entidad` int NOT NULL,
  `fk_factura` int NOT NULL,
  `tipo` int NOT NULL DEFAULT '1' COMMENT '1-->producto 2--> Servicios',
  `fk_producto` int NOT NULL,
  `label` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `ref` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `label_extra` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `tipo_impuesto` int NOT NULL,
  `cantidad` decimal(16,3) NOT NULL,
  `subtotal` decimal(15,5) NOT NULL,
  `impuesto` decimal(15,5) NOT NULL,
  `exoneracion` int DEFAULT '0',
  `ImpuestoNeto` decimal(18,5) DEFAULT NULL COMMENT 'Usase si tienes exoneracion',
  `total` decimal(15,5) NOT NULL,
  `precio_original` decimal(15,5) NOT NULL,
  `descuento_tipo` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descuento_aplicado` int NOT NULL,
  `descuento_valor_final` decimal(15,5) NOT NULL,
  `precio_costo` decimal(10,2) DEFAULT NULL COMMENT 'precio Unitario IMPUSTO INCLUIDO',
  `CABYS_codigo` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_producto` (`fk_producto`) USING BTREE,
  KEY `fk_factura` (`fk_factura`) USING BTREE,
  KEY `fk_entidad` (`fk_entidad`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_facturas_detalle
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_facturas_exonerar
-- ----------------------------
DROP TABLE IF EXISTS `fi_facturas_exonerar`;
CREATE TABLE `fi_facturas_exonerar` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL,
  `fk_factura` int NOT NULL,
  `fk_detalle` int NOT NULL,
  `tipo_documento` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tipo de documentos de exoneracion o autorizacion. 01 Compras autorizadas. 02 Ventas exentas a diplomaticos. 03 Orden de Compra (instituciones publicas y otros organismos) 04 Exenciones Direccion General de Hacienda. 05 Zonas Francas. 99 Otros',
  `numero_documento` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'Numero de documento de exoneracion o autorizacion.',
  `nombre_institucion` varchar(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'Nombre de la institucion o dependencia que emitio la exoneracion',
  `fecha_emision` datetime NOT NULL,
  `monto_impuesto` decimal(18,5) NOT NULL COMMENT 'Monto del impuesto exonerado o autorizado sin impuesto',
  `porcentaje_compra` int NOT NULL COMMENT 'Procentaje de la compra autorizada o exonerada',
  `ImpuestoNeto` decimal(20,5) DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of fi_facturas_exonerar
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_funnel
-- ----------------------------
DROP TABLE IF EXISTS `fi_funnel`;
CREATE TABLE `fi_funnel` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int DEFAULT NULL,
  `titulo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icono` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creado_fecha` datetime NOT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `entidad` (`entidad`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of fi_funnel
-- ----------------------------
BEGIN;
INSERT INTO `fi_funnel` (`rowid`, `entidad`, `titulo`, `descripcion`, `color`, `icono`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (5, 3, 'PM', 'Unico Funnel Project Master', '#7bea7d', 'fa fa-dumbbell', '2024-04-16 05:45:22', 4, 0, NULL, NULL);
INSERT INTO `fi_funnel` (`rowid`, `entidad`, `titulo`, `descripcion`, `color`, `icono`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (10, 4, 'Españas', 'Proyectos Españas', '#b13535', 'fa fa-dog', '2024-06-22 09:10:15', 8, 0, NULL, NULL);
INSERT INTO `fi_funnel` (`rowid`, `entidad`, `titulo`, `descripcion`, `color`, `icono`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (11, 4, 'Costa Rica', 'Proyectos Costa Rica', '#40b041', 'fa fa-plane', '2024-07-08 10:27:51', 8, 0, NULL, NULL);
INSERT INTO `fi_funnel` (`rowid`, `entidad`, `titulo`, `descripcion`, `color`, `icono`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (12, 1, 'España', 'Funel de España', '#000000', 'fa fa-plane', '2024-09-02 15:21:00', 1, 0, NULL, NULL);
INSERT INTO `fi_funnel` (`rowid`, `entidad`, `titulo`, `descripcion`, `color`, `icono`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (13, 3, 'FARAGAUSS', 'Faragaus Funnel', '#600b0b', 'fa fa-snowflake', '2024-10-18 12:09:45', 6, 0, NULL, NULL);
INSERT INTO `fi_funnel` (`rowid`, `entidad`, `titulo`, `descripcion`, `color`, `icono`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (14, 3, 'Prueba Daniel', 'Prueba Daniel', '#512f2f', 'fa fa-snowflake', '2024-11-07 16:35:30', 6, 0, NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for fi_funnel_detalle
-- ----------------------------
DROP TABLE IF EXISTS `fi_funnel_detalle`;
CREATE TABLE `fi_funnel_detalle` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_funnel` int NOT NULL DEFAULT '0',
  `etiqueta` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `estilo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `posicion` int NOT NULL DEFAULT '0',
  `canvan_mostrar_como_columna` int NOT NULL,
  `creado_fecha` datetime NOT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_funnel` (`fk_funnel`) USING BTREE,
  KEY `creado_fk_usuario` (`creado_fk_usuario`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of fi_funnel_detalle
-- ----------------------------
BEGIN;
INSERT INTO `fi_funnel_detalle` (`rowid`, `fk_funnel`, `etiqueta`, `estilo`, `descripcion`, `posicion`, `canvan_mostrar_como_columna`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (1, 5, 'Prospectos', 'info', 'Oportunidad sin oferta\r\n', 1, 1, '2024-06-07 00:07:46', 6, 0, NULL, NULL);
INSERT INTO `fi_funnel_detalle` (`rowid`, `fk_funnel`, `etiqueta`, `estilo`, `descripcion`, `posicion`, `canvan_mostrar_como_columna`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (2, 5, 'Cotizado ', 'success', 'Cuando presentamos oferta (Pipeline de oportunidades por vendedor, por mes, por año)\r\n\r\n', 2, 1, '2024-06-07 00:07:46', 6, 0, NULL, NULL);
INSERT INTO `fi_funnel_detalle` (`rowid`, `fk_funnel`, `etiqueta`, `estilo`, `descripcion`, `posicion`, `canvan_mostrar_como_columna`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (3, 5, 'Orden de Compra', 'success', 'Cuando recibimos el pedido pero aún falta facturar.  (Pedidos en mano)\r\n\r\n', 3, 1, '2024-06-07 00:07:46', 6, 0, NULL, NULL);
INSERT INTO `fi_funnel_detalle` (`rowid`, `fk_funnel`, `etiqueta`, `estilo`, `descripcion`, `posicion`, `canvan_mostrar_como_columna`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (4, 5, 'Facturado', 'success', 'Es para llevar la facturación por vendedor, mes, trimestre, año\r\n\r\n\r\n', 4, 1, '2024-06-07 00:07:46', 6, 0, NULL, NULL);
INSERT INTO `fi_funnel_detalle` (`rowid`, `fk_funnel`, `etiqueta`, `estilo`, `descripcion`, `posicion`, `canvan_mostrar_como_columna`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (5, 5, 'Perdido', 'danger', 'Para saber nuestra tasa de acierto\r\n\r\n', 5, 1, '2024-06-07 00:07:46', 6, 0, NULL, NULL);
INSERT INTO `fi_funnel_detalle` (`rowid`, `fk_funnel`, `etiqueta`, `estilo`, `descripcion`, `posicion`, `canvan_mostrar_como_columna`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (6, 10, 'Recibido', 'danger', 'Recibido Pendiente de accion por Avantec.DS\r\n', 1, 1, '2024-06-07 00:07:46', 8, 0, NULL, NULL);
INSERT INTO `fi_funnel_detalle` (`rowid`, `fk_funnel`, `etiqueta`, `estilo`, `descripcion`, `posicion`, `canvan_mostrar_como_columna`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (7, 10, 'Contactado', 'danger', 'Contactado \r\n', 2, 1, '2024-06-07 00:07:46', 8, 0, NULL, NULL);
INSERT INTO `fi_funnel_detalle` (`rowid`, `fk_funnel`, `etiqueta`, `estilo`, `descripcion`, `posicion`, `canvan_mostrar_como_columna`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (8, 10, 'Cotizado', 'danger', 'Contactado \r\n', 3, 1, '2024-06-07 00:07:46', 8, 0, NULL, NULL);
INSERT INTO `fi_funnel_detalle` (`rowid`, `fk_funnel`, `etiqueta`, `estilo`, `descripcion`, `posicion`, `canvan_mostrar_como_columna`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (9, 10, 'Ganado', 'danger', 'Ganado', 4, 1, '2024-06-07 00:07:46', 8, 0, NULL, NULL);
INSERT INTO `fi_funnel_detalle` (`rowid`, `fk_funnel`, `etiqueta`, `estilo`, `descripcion`, `posicion`, `canvan_mostrar_como_columna`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (10, 10, 'Perdido', 'danger', 'Ganado', 5, 1, '2024-06-07 00:07:46', 8, 0, NULL, NULL);
INSERT INTO `fi_funnel_detalle` (`rowid`, `fk_funnel`, `etiqueta`, `estilo`, `descripcion`, `posicion`, `canvan_mostrar_como_columna`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (11, 10, 'En salida', NULL, 'Salida', 6, 1, '2024-06-25 15:05:44', 8, 1, '2024-06-25 15:16:04', 8);
INSERT INTO `fi_funnel_detalle` (`rowid`, `fk_funnel`, `etiqueta`, `estilo`, `descripcion`, `posicion`, `canvan_mostrar_como_columna`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (12, 10, 'Adidas 1500', NULL, 'aa', 6, 1, '2024-06-25 17:57:28', 8, 1, '2024-06-25 17:57:32', 8);
INSERT INTO `fi_funnel_detalle` (`rowid`, `fk_funnel`, `etiqueta`, `estilo`, `descripcion`, `posicion`, `canvan_mostrar_como_columna`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (13, 11, 'Recibido', NULL, 'Recibido Pendiente de accion', 1, 1, '2024-07-08 10:30:42', 8, 0, NULL, NULL);
INSERT INTO `fi_funnel_detalle` (`rowid`, `fk_funnel`, `etiqueta`, `estilo`, `descripcion`, `posicion`, `canvan_mostrar_como_columna`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (14, 11, 'Contactado', NULL, 'Contactado...', 2, 1, '2024-07-08 10:33:01', 8, 0, NULL, NULL);
INSERT INTO `fi_funnel_detalle` (`rowid`, `fk_funnel`, `etiqueta`, `estilo`, `descripcion`, `posicion`, `canvan_mostrar_como_columna`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (15, 11, 'Cotizado', NULL, 'Cotizado', 3, 1, '2024-07-08 10:33:36', 8, 0, NULL, NULL);
INSERT INTO `fi_funnel_detalle` (`rowid`, `fk_funnel`, `etiqueta`, `estilo`, `descripcion`, `posicion`, `canvan_mostrar_como_columna`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (16, 11, 'Ganado', NULL, 'Ganado', 4, 1, '2024-07-08 10:33:44', 8, 0, NULL, NULL);
INSERT INTO `fi_funnel_detalle` (`rowid`, `fk_funnel`, `etiqueta`, `estilo`, `descripcion`, `posicion`, `canvan_mostrar_como_columna`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (17, 11, 'Perdido', NULL, 'Perdido', 5, 1, '2024-07-08 10:33:55', 8, 0, NULL, NULL);
INSERT INTO `fi_funnel_detalle` (`rowid`, `fk_funnel`, `etiqueta`, `estilo`, `descripcion`, `posicion`, `canvan_mostrar_como_columna`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (18, 13, 'prospecto', NULL, 'descripcion prospecto', 1, 1, '2024-10-18 12:10:25', 6, 0, NULL, NULL);
INSERT INTO `fi_funnel_detalle` (`rowid`, `fk_funnel`, `etiqueta`, `estilo`, `descripcion`, `posicion`, `canvan_mostrar_como_columna`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (19, 13, 'Cotizado', NULL, 'Descripcion cotizado', 2, 1, '2024-10-18 12:10:35', 6, 0, NULL, NULL);
INSERT INTO `fi_funnel_detalle` (`rowid`, `fk_funnel`, `etiqueta`, `estilo`, `descripcion`, `posicion`, `canvan_mostrar_como_columna`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (20, 13, 'Completado', NULL, 'Descripcion Completado', 3, 1, '2024-10-18 12:10:44', 6, 0, NULL, NULL);
INSERT INTO `fi_funnel_detalle` (`rowid`, `fk_funnel`, `etiqueta`, `estilo`, `descripcion`, `posicion`, `canvan_mostrar_como_columna`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (21, 14, 'Prospecto', NULL, 'Prospecto', 1, 1, '2024-11-07 16:36:19', 6, 0, NULL, NULL);
INSERT INTO `fi_funnel_detalle` (`rowid`, `fk_funnel`, `etiqueta`, `estilo`, `descripcion`, `posicion`, `canvan_mostrar_como_columna`, `creado_fecha`, `creado_fk_usuario`, `borrado`, `borrado_fecha`, `borrado_fk_usuario`) VALUES (22, 14, 'En proceso', NULL, 'En proceso', 2, 1, '2024-11-07 16:37:23', 6, 0, NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for fi_gastos
-- ----------------------------
DROP TABLE IF EXISTS `fi_gastos`;
CREATE TABLE `fi_gastos` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL DEFAULT '1',
  `fk_usuario_crear` int NOT NULL,
  `fk_gasto` int NOT NULL,
  `fecha` date NOT NULL,
  `recibo_numero` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `detalle` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `fk_tercero` int NOT NULL,
  `valor` int NOT NULL,
  `pagado` int NOT NULL DEFAULT '0',
  `fk_usuario_pagar` int NOT NULL,
  `eliminado` int NOT NULL DEFAULT '0',
  `eliminado_hora` datetime NOT NULL,
  `fk_usuario_eliminar` int NOT NULL COMMENT 'guarda quien elimino el gasto',
  `fk_proyecto` int NOT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_gasto` (`fk_gasto`) USING BTREE,
  KEY `fk_usuario_pagar` (`fk_usuario_pagar`) USING BTREE,
  KEY `fk_usuario_pagar_2` (`fk_usuario_pagar`) USING BTREE,
  KEY `fk_usuario_crear` (`fk_usuario_crear`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_gastos
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_gastos_tipos
-- ----------------------------
DROP TABLE IF EXISTS `fi_gastos_tipos`;
CREATE TABLE `fi_gastos_tipos` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_parent` int DEFAULT NULL,
  `nombre` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `entidad` int NOT NULL DEFAULT '1',
  `require_cedula` int NOT NULL DEFAULT '0' COMMENT '1- Require Cedula para D151 ',
  `activo` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=949 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_gastos_tipos
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_notas_credito
-- ----------------------------
DROP TABLE IF EXISTS `fi_notas_credito`;
CREATE TABLE `fi_notas_credito` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_documento_modifica` int NOT NULL,
  `fk_documento_modifica_tipo` set('factura','nota_credito','nota_debito') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `fk_tipo_documento_referenciado` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `codigoNota` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT '01',
  `razon` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT 'A criterio del usuario',
  `entidad` int NOT NULL DEFAULT '1',
  `moneda` int NOT NULL DEFAULT '1' COMMENT '1 Colones 2-- Dolares',
  `moneda_tipo_cambio` decimal(10,2) NOT NULL DEFAULT '1.00',
  `actividad` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `fk_usuario_crear` int NOT NULL,
  `fk_usuario_validar` int NOT NULL,
  `fecha` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `referencia` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tipo` set('debito','credito') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Credito  - Debito',
  `detalle` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `fk_tercero` int DEFAULT NULL,
  `subtotal` decimal(15,5) NOT NULL,
  `impuesto` decimal(15,5) NOT NULL,
  `total` decimal(15,5) NOT NULL,
  `estado` int NOT NULL COMMENT '0 Borrador  ----- 1 Validada  3--- abandonada',
  `pagado` int NOT NULL DEFAULT '0',
  `estado_pagada` int NOT NULL DEFAULT '0' COMMENT '0- No   1-  Si',
  `eliminado` int NOT NULL DEFAULT '0',
  `fecha_creacion_server` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `electronica_enviada` int NOT NULL,
  `electronica_enviada_fecha` datetime NOT NULL,
  `electronica_resultado` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `electronica_resultado_txt` varchar(600) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `electronica_resultado_fecha` datetime DEFAULT NULL,
  `electronica_error` int NOT NULL DEFAULT '0',
  `electronica_error_txt` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `envio_correo_factura` int DEFAULT '0',
  `envio_correo_factura_fecha` datetime DEFAULT NULL,
  `envio_correo_factura_correo` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_usuario_crear` (`fk_usuario_crear`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_notas_credito
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_notas_credito_detalle
-- ----------------------------
DROP TABLE IF EXISTS `fi_notas_credito_detalle`;
CREATE TABLE `fi_notas_credito_detalle` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_entidad` int NOT NULL,
  `fk_nota` int NOT NULL,
  `tipo` int NOT NULL DEFAULT '1' COMMENT '1-->producto 2--> Servicios',
  `fk_producto` int NOT NULL,
  `label` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `ref` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tipo_impuesto` int NOT NULL,
  `cantidad` int NOT NULL,
  `subtotal` decimal(15,5) NOT NULL,
  `impuesto` decimal(15,5) NOT NULL,
  `exoneracion` int NOT NULL DEFAULT '0',
  `ImpuestoNeto` decimal(18,5) DEFAULT NULL COMMENT 'Usase si tienes exoneracion	',
  `total` decimal(15,5) NOT NULL,
  `precio_original` decimal(15,5) NOT NULL,
  `descuento_tipo` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descuento_aplicado` int NOT NULL,
  `descuento_valor_final` decimal(15,5) NOT NULL,
  `precio_costo` decimal(10,2) NOT NULL COMMENT 'precio Unitario IMPUSTO INCLUIDO',
  `CABYS_codigo` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_producto` (`fk_producto`) USING BTREE,
  KEY `fk_factura` (`fk_nota`) USING BTREE,
  KEY `fk_entidad` (`fk_entidad`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_notas_credito_detalle
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_notificacion_general
-- ----------------------------
DROP TABLE IF EXISTS `fi_notificacion_general`;
CREATE TABLE `fi_notificacion_general` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `activar` int NOT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of fi_notificacion_general
-- ----------------------------
BEGIN;
INSERT INTO `fi_notificacion_general` (`rowid`, `activar`) VALUES (1, 2);
COMMIT;

-- ----------------------------
-- Table structure for fi_oportunidades
-- ----------------------------
DROP TABLE IF EXISTS `fi_oportunidades`;
CREATE TABLE `fi_oportunidades` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int DEFAULT '0',
  `fk_funnel` int DEFAULT '0',
  `fk_funnel_detalle` int DEFAULT '0',
  `fk_contacto` int DEFAULT NULL,
  `fk_tercero` int DEFAULT '0',
  `fk_tercero_contacto` int DEFAULT NULL,
  `fk_estado` int DEFAULT '0',
  `fk_categoria` int DEFAULT NULL,
  `fk_prioridad` int DEFAULT NULL,
  `fecha` date DEFAULT NULL COMMENT 'Fecha de la oportunidad > No necesariamente es la misma fecha de la creacion',
  `fecha_cierre` date DEFAULT NULL,
  `tiempo_entrega` int DEFAULT NULL,
  `validez_oferta` int DEFAULT NULL,
  `consecutivo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `etiqueta` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nota` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `fk_usuario_asignado` int DEFAULT '0',
  `fk_usuario_modificado` int DEFAULT NULL,
  `modificado_fecha` datetime DEFAULT NULL,
  `creado_fecha` datetime NOT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  `tags` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `posicion_funnel` int DEFAULT '0',
  `importe` decimal(10,0) DEFAULT NULL,
  `importe_fk_moneda` int NOT NULL DEFAULT '1',
  `importe_dolarizado` decimal(10,2) DEFAULT NULL COMMENT 'Monto Dolarizado para poder graficar los totales sin importar la moneda utilizada',
  `tipo_oferta` int DEFAULT NULL,
  `campo_extra_1` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `campo_extra_2` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `campo_extra_3` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `campo_extra_4` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `campo_extra_5` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `campo_extra_6` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `campo_extra_7` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `campo_extra_8` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `campo_extra_9` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `campo_extra_10` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_cambio` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_tercero` (`fk_tercero`) USING BTREE,
  KEY `fk_usuario_modificado` (`fk_usuario_modificado`) USING BTREE,
  KEY `fk_usuario_asignado` (`fk_usuario_asignado`) USING BTREE,
  KEY `fk_funnel` (`fk_funnel`) USING BTREE,
  KEY `fk_funnel_detalle` (`fk_funnel_detalle`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of fi_oportunidades
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_oportunidades_actividades
-- ----------------------------
DROP TABLE IF EXISTS `fi_oportunidades_actividades`;
CREATE TABLE `fi_oportunidades_actividades` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL,
  `fk_oportunidad` int NOT NULL COMMENT 'apunta a fi_oportunidades',
  `fk_diccionario_actividad` int DEFAULT NULL COMMENT 'apunta a la tabla diccionario_crm_actividades',
  `creado_fecha` datetime NOT NULL,
  `vencimiento_fecha` datetime DEFAULT NULL COMMENT 'Cuando se vence la tarea',
  `actividad_se_vencio` int NOT NULL DEFAULT '0' COMMENT 'cantidad de dias en que se ha vencido la tarea',
  `creado_usuario` int NOT NULL,
  `comentario` varchar(600) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `fk_usuario_asignado` int NOT NULL COMMENT 'puede ser o no el usuario que debe hacer la actividad',
  `fk_estado` int NOT NULL,
  `fk_cotizacion` int DEFAULT NULL COMMENT 'Asociada a fi_cotizacion',
  `comentario_cierre` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `tipo` set('timeline','tarea') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'timeline' COMMENT 'El timeline mustra todos el tarea solo las tareas',
  `consecutivo` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `entidad` (`entidad`) USING BTREE,
  KEY `fk_cotizacion` (`fk_cotizacion`) USING BTREE,
  KEY `fk_cotizacion_2` (`fk_cotizacion`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=442 DEFAULT CHARSET=latin1 COMMENT='Contiene Timeline y actividades';

-- ----------------------------
-- Records of fi_oportunidades_actividades
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_oportunidades_configuracion
-- ----------------------------
DROP TABLE IF EXISTS `fi_oportunidades_configuracion`;
CREATE TABLE `fi_oportunidades_configuracion` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL DEFAULT '1',
  `siguiente_oportunidad` int NOT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='Configuracion de las oportunidades';

-- ----------------------------
-- Records of fi_oportunidades_configuracion
-- ----------------------------
BEGIN;
INSERT INTO `fi_oportunidades_configuracion` (`rowid`, `entidad`, `siguiente_oportunidad`) VALUES (1, 1, 5);
COMMIT;

-- ----------------------------
-- Table structure for fi_oportunidades_movimientos
-- ----------------------------
DROP TABLE IF EXISTS `fi_oportunidades_movimientos`;
CREATE TABLE `fi_oportunidades_movimientos` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_oportunidad` int NOT NULL,
  `fk_oportunidad_detalle` int NOT NULL,
  `modificado_fecha` timestamp NOT NULL,
  `modificado_fk_usuario` int NOT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=298 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of fi_oportunidades_movimientos
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_oportunidades_recurso_humano
-- ----------------------------
DROP TABLE IF EXISTS `fi_oportunidades_recurso_humano`;
CREATE TABLE `fi_oportunidades_recurso_humano` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_oportunidad` int NOT NULL,
  `fk_usuario` int NOT NULL,
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_cotizacion` (`fk_oportunidad`) USING BTREE,
  KEY `fk_usuario` (`fk_usuario`) USING BTREE,
  KEY `borrado_fk_usuario` (`borrado_fk_usuario`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='REcurso humano (para el PDF y reportes) ';

-- ----------------------------
-- Records of fi_oportunidades_recurso_humano
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_oportunidades_servicios
-- ----------------------------
DROP TABLE IF EXISTS `fi_oportunidades_servicios`;
CREATE TABLE `fi_oportunidades_servicios` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_oportunidad` int NOT NULL COMMENT 'apunta a fi_oportunidades',
  `fk_producto` int DEFAULT NULL COMMENT 'Apunta a fk_producto, puede ser un servicio',
  `cantidad` int DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `precio_real` decimal(10,2) DEFAULT NULL,
  `precio_subtotal` decimal(10,2) NOT NULL,
  `tipo_descuento` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'El tipo de descuento puede ser: ''absoluto'' , ''porcentual'' ',
  `monto_descuento` decimal(10,2) DEFAULT NULL,
  `precio_tipo_impuesto` decimal(10,2) DEFAULT '0.00',
  `precio_total` decimal(10,2) DEFAULT NULL,
  `creado_fecha` datetime NOT NULL,
  `creado_usuario` int NOT NULL,
  `comentario` varchar(600) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `fk_estado` int NOT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_oportunidad` (`fk_oportunidad`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of fi_oportunidades_servicios
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_productos
-- ----------------------------
DROP TABLE IF EXISTS `fi_productos`;
CREATE TABLE `fi_productos` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL DEFAULT '1',
  `tipo` int NOT NULL DEFAULT '1' COMMENT '1 -- Productos , 2 Servicios',
  `unidad` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `ref` varchar(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `label` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `impuesto_fk` int DEFAULT NULL COMMENT 'impuesto por defecto para el producto',
  `descripcion` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `codigo_barras` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL COMMENT 'muy utilizado en el POS ',
  `tosell` int DEFAULT NULL,
  `tobuy` int DEFAULT NULL,
  `fk_user_autor` int DEFAULT NULL,
  `stock` int DEFAULT NULL,
  `stock_minimo_alerta` int DEFAULT NULL,
  `descuento_maximo` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Mejora Aplicada solo a IDTEC',
  `notas` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Utilizar para manejar detalles internos que no vera el cliente',
  `diccionario_1` int DEFAULT NULL,
  `diccionario_2` int DEFAULT NULL,
  `diccionario_3` int DEFAULT NULL,
  `diccionario_4` int DEFAULT NULL,
  `diccionario_5` int DEFAULT NULL,
  `diccionario_6` int DEFAULT NULL,
  `diccionario_7` int DEFAULT NULL,
  `diccionario_8` int DEFAULT NULL,
  `diccionario_9` int DEFAULT NULL,
  `diccionario_10` int DEFAULT NULL,
  `fk_bodega_base` int DEFAULT NULL,
  `romana` int DEFAULT '0' COMMENT 'Integra con Romana',
  `eliminado` int NOT NULL DEFAULT '0',
  `CABYS_listo` int NOT NULL DEFAULT '0' COMMENT 'Modificacion CABYS',
  `CABYS_codigo` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `CABYS_impuesto` int DEFAULT NULL,
  `CABYS_descripcion` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `CABYS_FECHA` datetime DEFAULT NULL,
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  `conart` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `unidad_medida` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `familia_articulo` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `autogen` int DEFAULT '0',
  `ubicacion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `fk_parent_categoria_producto` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=664 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_productos
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_productos_compuesto
-- ----------------------------
DROP TABLE IF EXISTS `fi_productos_compuesto`;
CREATE TABLE `fi_productos_compuesto` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_product_padre` int NOT NULL,
  `fk_product_hijo` int NOT NULL,
  `cantidad` int NOT NULL,
  `gratis` int NOT NULL COMMENT '0-- No   1-- Gratis',
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_product_padre` (`fk_product_padre`) USING BTREE,
  KEY `fk_product_hijo` (`fk_product_hijo`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of fi_productos_compuesto
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_productos_imagenes
-- ----------------------------
DROP TABLE IF EXISTS `fi_productos_imagenes`;
CREATE TABLE `fi_productos_imagenes` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_producto` int NOT NULL,
  `id_externo` int DEFAULT NULL,
  `label` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `activo` int NOT NULL,
  `descripcion` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_producto` (`fk_producto`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of fi_productos_imagenes
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_productos_lote
-- ----------------------------
DROP TABLE IF EXISTS `fi_productos_lote`;
CREATE TABLE `fi_productos_lote` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_producto` int NOT NULL COMMENT 'seria el producto principal para tomar la descripcion, nombre, imagen y sku',
  `fk_producto_lote` int NOT NULL COMMENT 'seria el producto como el que se añadio a la lista de productos en lote',
  `cantidad` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of fi_productos_lote
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_productos_politica_descuentos
-- ----------------------------
DROP TABLE IF EXISTS `fi_productos_politica_descuentos`;
CREATE TABLE `fi_productos_politica_descuentos` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL,
  `tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'indica si es monto o unidad',
  `fk_producto` int NOT NULL,
  `activo` int NOT NULL DEFAULT '1',
  `borrado` int NOT NULL DEFAULT '0',
  `creado_fecha` datetime NOT NULL,
  `creado_fk_usuario` int NOT NULL,
  `borrado_fecha_usuario` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  `fecha_inicial` date DEFAULT NULL,
  `fecha_final` date DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of fi_productos_politica_descuentos
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_productos_politica_descuentos_detalle
-- ----------------------------
DROP TABLE IF EXISTS `fi_productos_politica_descuentos_detalle`;
CREATE TABLE `fi_productos_politica_descuentos_detalle` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_politica` int NOT NULL,
  `base_imponible` decimal(10,2) NOT NULL,
  `porcentaje_descuento` decimal(10,2) NOT NULL,
  `activo` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of fi_productos_politica_descuentos_detalle
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_productos_precios_clientes
-- ----------------------------
DROP TABLE IF EXISTS `fi_productos_precios_clientes`;
CREATE TABLE `fi_productos_precios_clientes` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_producto` int NOT NULL,
  `fk_usuario` int DEFAULT NULL COMMENT 'Posiblemente se paso de una estructura anterior pero no va\r\n',
  `impuesto` decimal(18,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(18,5) NOT NULL,
  `total` decimal(18,5) NOT NULL,
  `moneda` int NOT NULL,
  `fecha` datetime NOT NULL,
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  `nota` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `fk_lista` int DEFAULT NULL COMMENT 'Apunta al rowid de fi_productos_precios_clientes_listas',
  `porcentaje_utilidad` int DEFAULT NULL,
  `porcentaje_descuento` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_producto` (`fk_producto`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=498 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_productos_precios_clientes
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_productos_precios_clientes_bak
-- ----------------------------
DROP TABLE IF EXISTS `fi_productos_precios_clientes_bak`;
CREATE TABLE `fi_productos_precios_clientes_bak` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_producto` int NOT NULL,
  `fk_usuario` int NOT NULL,
  `impuesto` int NOT NULL,
  `subtotal` decimal(18,5) NOT NULL,
  `total` decimal(18,5) NOT NULL,
  `moneda` int NOT NULL,
  `fecha` datetime NOT NULL,
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_producto` (`fk_producto`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_productos_precios_clientes_bak
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_productos_precios_clientes_listas
-- ----------------------------
DROP TABLE IF EXISTS `fi_productos_precios_clientes_listas`;
CREATE TABLE `fi_productos_precios_clientes_listas` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL,
  `etiqueta` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `activo` int NOT NULL DEFAULT '1',
  `borrado` int NOT NULL DEFAULT '0',
  `creado_fecha` datetime NOT NULL,
  `creado_fk_usuario` int NOT NULL,
  `borrado_fecha_usuario` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of fi_productos_precios_clientes_listas
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_productos_precios_costo
-- ----------------------------
DROP TABLE IF EXISTS `fi_productos_precios_costo`;
CREATE TABLE `fi_productos_precios_costo` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_producto` int NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `impuesto` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'e es de excento, EL PRECIO TRAE EL IMPUESTO SUMADO',
  `fecha` datetime NOT NULL,
  `nota` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=338 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of fi_productos_precios_costo
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_productos_test
-- ----------------------------
DROP TABLE IF EXISTS `fi_productos_test`;
CREATE TABLE `fi_productos_test` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL DEFAULT '1',
  `tipo` int NOT NULL DEFAULT '1' COMMENT '1 -- Productos , 2 Servicios',
  `unidad` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `ref` varchar(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `label` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `codigo_barras` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'muy utilizado en el POS ',
  `tosell` int NOT NULL,
  `tobuy` int NOT NULL,
  `fk_user_autor` int NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `stock_minimo_alerta` int NOT NULL,
  `descuento_maximo` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Mejora Aplicada solo a IDTEC',
  `notas` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `diccionario_1` int NOT NULL,
  `diccionario_2` int NOT NULL,
  `diccionario_3` int NOT NULL,
  `diccionario_4` int NOT NULL,
  `diccionario_5` int NOT NULL,
  `diccionario_6` int NOT NULL,
  `diccionario_7` int NOT NULL,
  `diccionario_8` int NOT NULL,
  `diccionario_9` int NOT NULL,
  `diccionario_10` int NOT NULL,
  `fk_bodega_base` int NOT NULL DEFAULT '0',
  `romana` int NOT NULL DEFAULT '0' COMMENT 'Integra con Romana',
  `eliminado` int NOT NULL DEFAULT '0',
  `CABYS_listo` int NOT NULL DEFAULT '0' COMMENT 'Modificacion CABYS',
  `CABYS_codigo` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `CABYS_impuesto` int DEFAULT NULL,
  `CABYS_descripcion` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `CABYS_FECHA` datetime DEFAULT NULL,
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_productos_test
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_terceros
-- ----------------------------
DROP TABLE IF EXISTS `fi_terceros`;
CREATE TABLE `fi_terceros` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int DEFAULT '1',
  `tipo` set('fisica','juridica','dimex','nite') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT 'fisica',
  `extranjero` int DEFAULT '0',
  `nombre` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `apellidos` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `cedula` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT '',
  `telefono` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `fk_sucursal` int DEFAULT NULL,
  `rx` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `addd` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `tipo_lente` int DEFAULT NULL,
  `DP1` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `DP2` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `proveedor` int DEFAULT '0',
  `cliente` int DEFAULT '1',
  `credito` int NOT NULL DEFAULT '0',
  `nota` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `creado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `activo` int NOT NULL DEFAULT '1',
  `comercial` int DEFAULT NULL COMMENT 'guardamos el id del vendedor',
  `electronica_nombre_comercial` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  `movil` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `fax` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `persona_contacto` varchar(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `direccion` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `poblacion` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `codigo_postal` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `provincia` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `nombre_banco` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `banco_entidad` varchar(4) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `banco_oficina` varchar(4) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `banco_digito_control` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `banco_cuenta` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `swift1` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `swift2` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `rut` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `pais` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `tipo_proveedor` int DEFAULT NULL,
  `web` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `forma_pago` int DEFAULT NULL,
  `autogen` int NOT NULL DEFAULT '0',
  `fk_moneda` int NOT NULL DEFAULT '1',
  `fk_categoria_cliente` int DEFAULT NULL,
  `impuesto_cliente_lleva_retencion` int DEFAULT '0' COMMENT 'Indica si el cliente está sujeto a retención de impuestos',
  `impuesto_cliente_lleva_retencion_porcentaje` int DEFAULT NULL COMMENT 'Indica si el cliente está sujeto a retención de impuestos',
  `impuesto_cliente_regimen_iva_tipos_retencion` int DEFAULT NULL COMMENT 'Referencia a la tabla utilidades_apoyo regimen_iva_tipos_retencion ',
  `impuesto_cliente_fk_diccionario_regimen_iva` int DEFAULT NULL COMMENT 'Referencia a la tabla utilidades_apoyo.diccionario_Regimen_iva',
  `impuesto_cliente_aplica_recargo_equivalencia` int DEFAULT NULL COMMENT 'Indica si se aplica recargo de equivalencia al cliente',
  `fk_lista_precio` int DEFAULT NULL,
  `limite_credito` decimal(10,2) DEFAULT NULL,
  `saldo_credito` decimal(10,2) DEFAULT NULL,
  `dia_pago` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `mes_no_pago` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `aplicar_descuento_volumen` int DEFAULT NULL,
  `aplicar_descuento_por_articulo` int DEFAULT NULL,
  `descuento_pronto_pago` decimal(10,2) DEFAULT NULL,
  `moroso` int DEFAULT NULL,
  `credito_cerrado` int DEFAULT NULL,
  `motivo_cierre` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `tipo_lente` (`tipo_lente`) USING BTREE,
  KEY `fk_categoria_cliente` (`fk_categoria_cliente`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=945 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_terceros
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_terceros_contactos
-- ----------------------------
DROP TABLE IF EXISTS `fi_terceros_contactos`;
CREATE TABLE `fi_terceros_contactos` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL DEFAULT '1' COMMENT 'Para filtrar la empresa',
  `fk_tercero` int NOT NULL,
  `fk_diccionario_contacto` int NOT NULL,
  `dato` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `detalle` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='ESte guarda los diferentes tipos de contacto';

-- ----------------------------
-- Records of fi_terceros_contactos
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_terceros_crm_contactos
-- ----------------------------
DROP TABLE IF EXISTS `fi_terceros_crm_contactos`;
CREATE TABLE `fi_terceros_crm_contactos` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_tercero` int DEFAULT NULL,
  `entidad` int NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellidos` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pais_c` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `puesto_t` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paginaweb` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `extension` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `x_twitter` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitud` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creado_fecha` datetime NOT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of fi_terceros_crm_contactos
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_terceros_exonerar
-- ----------------------------
DROP TABLE IF EXISTS `fi_terceros_exonerar`;
CREATE TABLE `fi_terceros_exonerar` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL,
  `fk_tercero` int NOT NULL,
  `tipoDocumento` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `numeroDocumento` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nombreInstitucion` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `fechaEmision` date NOT NULL,
  `fechaVencimiento` date DEFAULT NULL COMMENT 'Agregado en la mejora	',
  `porcentaje` int NOT NULL DEFAULT '0' COMMENT 'Cambio Exoneracion',
  `activo` int NOT NULL DEFAULT '1',
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of fi_terceros_exonerar
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_terceros_test
-- ----------------------------
DROP TABLE IF EXISTS `fi_terceros_test`;
CREATE TABLE `fi_terceros_test` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `entidad` int NOT NULL DEFAULT '1',
  `tipo` set('fisica','juridica','dimex','nite') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT 'fisica',
  `extranjero` int NOT NULL DEFAULT '0',
  `nombre` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `apellidos` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `cedula` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `telefono` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `fk_sucursal` int NOT NULL,
  `rx` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `addd` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tipo_lente` int NOT NULL,
  `DP1` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `DP2` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `proveedor` int NOT NULL DEFAULT '0',
  `cliente` int NOT NULL DEFAULT '1',
  `credito` int NOT NULL,
  `nota` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `creado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `activo` int NOT NULL DEFAULT '1',
  `comercial` int DEFAULT NULL COMMENT 'guardamos el id del vendedor',
  `electronica_nombre_comercial` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `tipo_lente` (`tipo_lente`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of fi_terceros_test
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_tipo_cambio
-- ----------------------------
DROP TABLE IF EXISTS `fi_tipo_cambio`;
CREATE TABLE `fi_tipo_cambio` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `venta` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL,
  `flecha_sincronizacion` datetime NOT NULL,
  `entidad` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2048 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of fi_tipo_cambio
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_tipo_cambio_para_borrar
-- ----------------------------
DROP TABLE IF EXISTS `fi_tipo_cambio_para_borrar`;
CREATE TABLE `fi_tipo_cambio_para_borrar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'Nombre del tipo de cambio',
  `tipo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'Tipo de cambio',
  `cotejamiento` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL COMMENT 'Cotejamiento de la columna',
  `atributos` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL COMMENT 'Atributos adicionales',
  `nulo` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Indica si permite nulos',
  `predeterminado` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL COMMENT 'Valor predeterminado',
  `comentarios` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci COMMENT 'Comentarios adicionales',
  `extra` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL COMMENT 'Información extra',
  `entidad` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'Entidad relacionada',
  `accion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'Acción relacionada',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- ----------------------------
-- Records of fi_tipo_cambio_para_borrar
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_usuarios
-- ----------------------------
DROP TABLE IF EXISTS `fi_usuarios`;
CREATE TABLE `fi_usuarios` (
  `rowid` int NOT NULL,
  `entidad` int NOT NULL DEFAULT '1',
  `nombre` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `apellidos` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL COMMENT 'Campo Abandonado',
  `fk_sucursal` int DEFAULT NULL,
  `identificacion` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `telefono` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `movil` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `activo` int NOT NULL COMMENT '0- no /1 - Activo',
  `avatar` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `email_host` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `email_port` int DEFAULT NULL,
  `email_user_name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `email_password` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `firma` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tablas de Accesos! ';

-- ----------------------------
-- Records of fi_usuarios
-- ----------------------------
BEGIN;
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (1, 1, 'David', 'Bermejo', NULL, NULL, NULL, NULL, NULL, 1, '1.png', 'mail.avancescr.com', 587, 'notificador@avancescr.com', '8!#cK-+S;4-q', NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (2, 1, 'Jesús', 'Lucia Cortés', NULL, NULL, NULL, NULL, NULL, 1, '2.jpg', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (3, 2, 'LUIS', 'Fonseca', NULL, NULL, NULL, NULL, NULL, 1, '3.png', 'mail.avancescr.com', 587, 'notificador@avancescr.com', '8!#cK-+S;4-q', NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (4, 2, 'Marlisa', 'Ritcher', NULL, NULL, NULL, NULL, NULL, 1, '4.jpg', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (5, 2, 'Andrea', 'Arrones', NULL, NULL, NULL, NULL, NULL, 1, 'https://sistema-dev.avantecds.es/bootstrap/assets/img/1x1/Andrea.jpg', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (6, 3, 'Alex', 'Aguero', NULL, NULL, NULL, NULL, NULL, 1, '6.png', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (7, 3, 'Pablo', 'Aguero', NULL, NULL, NULL, NULL, NULL, 1, '7.jpg', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (8, 4, 'David', 'Bermejo', NULL, NULL, NULL, NULL, NULL, 1, '8.png', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (9, 4, 'David', 'Murillo', NULL, NULL, NULL, NULL, NULL, 1, 'https://sistema-dev.avantecds.es/bootstrap/assets/img/1x1/Andrea.jpg', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (10, 4, 'Angie', 'Solis', 'asolis@avancescr.com', NULL, NULL, '+5061231234', '+5061231234', 1, 'https://sistema-dev.avantecds.es/bootstrap/assets/img/1x1/Andrea.jpg', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (11, 4, 'Lucas', 'Sartori', NULL, NULL, NULL, NULL, NULL, 1, 'https://sistema-dev.avantecds.es/bootstrap/assets/img/1x1/Andrea.jpg', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (12, 5, 'Virya', 'Navarro', NULL, NULL, NULL, NULL, NULL, 1, '12.png', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (13, 5, 'Maricela', 'Navarroo', 'maricela@redhousemkt.com', NULL, NULL, '+5061231234', '+5061231234', 0, '13.png', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (15, 8, 'Nombre Ejemplo 1', 'Nombre Ejemplo 2 ', NULL, NULL, NULL, NULL, NULL, 1, '1.png', '', 0, '', '', NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (36, 5, 'F', 'Rojas', 'frojas@redhousemkt.com', NULL, NULL, '123', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (37, 5, 'Maricela', 'Solano', 'msolano@redhousemkt.com', NULL, NULL, '123', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (38, 5, 'Valeria', 'Castillo', 'vcastillo@redhousemkt.com', NULL, NULL, '123', NULL, 1, '38.jpeg', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (39, 5, 'W', 'Orellana', 'worellana@redhousemkt.com', NULL, NULL, '123', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (40, 5, 'Suzeth ', 'Campos', 'scampos@redhousemkt.com', NULL, NULL, '123', NULL, 1, '40.png', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (41, 5, 'N', 'Aleman', 'naleman@redhousemkt.com', NULL, NULL, '123', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (42, 5, 'S', 'Badilla', 'sbadilla@redhousemkt.com', NULL, NULL, '123', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (43, 5, 'S', 'Castillo', 'scastillo@redhousemkt.com', NULL, NULL, '123', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (44, 5, 'Diseño', 'Redhouse', 'diseno@redhousemkt.com', NULL, NULL, '123', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (45, 5, 'prueba', 'prueba', 'ñoño@redhousemkt.com', NULL, NULL, '123', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (46, 4, 'da', 'adasd', 'dbermejo@avancescr.com', NULL, NULL, 'asdasd', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `fi_usuarios` (`rowid`, `entidad`, `nombre`, `apellidos`, `email`, `fk_sucursal`, `identificacion`, `telefono`, `movil`, `activo`, `avatar`, `email_host`, `email_port`, `email_user_name`, `email_password`, `firma`) VALUES (47, 4, 'Juan Carlos', 'Morales', 'jcmm@ice.co.cr', NULL, NULL, '88493002', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for fi_usuarios_perfiles
-- ----------------------------
DROP TABLE IF EXISTS `fi_usuarios_perfiles`;
CREATE TABLE `fi_usuarios_perfiles` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `etiqueta` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `entidad` int DEFAULT NULL,
  `creado_fecha` datetime DEFAULT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int DEFAULT '0',
  `borrado_fecha_usuario` datetime DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of fi_usuarios_perfiles
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fi_usuarios_perfiles_relacion
-- ----------------------------
DROP TABLE IF EXISTS `fi_usuarios_perfiles_relacion`;
CREATE TABLE `fi_usuarios_perfiles_relacion` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_usuario` int NOT NULL,
  `fk_usuario_perfil` int NOT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of fi_usuarios_perfiles_relacion
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for sistema_empresa
-- ----------------------------
DROP TABLE IF EXISTS `sistema_empresa`;
CREATE TABLE `sistema_empresa` (
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
  `kit_pdf_firmado_url_en_disco` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Donde se guarda en disco',
  `kit_direccion_completa` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kit_codigo_postal` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kit_factura_emitida` int DEFAULT '0',
  `kit_factura_emitida_fecha` datetime DEFAULT NULL,
  `kit_factura_emitida_pagada` int DEFAULT NULL,
  `kit_monto_aprobado` int DEFAULT NULL,
  `kit_monto_comision` int DEFAULT NULL,
  `kit_monto_comision_pagada` int DEFAULT NULL,
  `vendedor_fk_usuario` int DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_sistema_empresa_licencias` (`fk_sistema_empresa_licencias`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='LLeva los datos de la empresa';

-- ----------------------------
-- Records of sistema_empresa
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for sistema_empresa_licencias
-- ----------------------------
DROP TABLE IF EXISTS `sistema_empresa_licencias`;
CREATE TABLE `sistema_empresa_licencias` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `user` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pass` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bd` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `server` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of sistema_empresa_licencias
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for sistema_empresa_usuarios
-- ----------------------------
DROP TABLE IF EXISTS `sistema_empresa_usuarios`;
CREATE TABLE `sistema_empresa_usuarios` (
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
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_empresa` (`fk_empresa`) USING BTREE,
  KEY `fk_usuario` (`fk_usuario`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Esta  tabla  une los usuarios contra los empresas a las que tienen acceso ';

-- ----------------------------
-- Records of sistema_empresa_usuarios
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for sistema_externo_ignorado
-- ----------------------------
DROP TABLE IF EXISTS `sistema_externo_ignorado`;
CREATE TABLE `sistema_externo_ignorado` (
  `id` int NOT NULL AUTO_INCREMENT,
  `externo_id` int DEFAULT NULL,
  `externo_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `company` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of sistema_externo_ignorado
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for sistema_externo_sincronizaciones
-- ----------------------------
DROP TABLE IF EXISTS `sistema_externo_sincronizaciones`;
CREATE TABLE `sistema_externo_sincronizaciones` (
  `rowid` int NOT NULL AUTO_INCREMENT,
  `fk_entidad` int NOT NULL,
  `fk_sistema_a_sincronizar` int NOT NULL,
  `creado_fecha` datetime NOT NULL,
  `creado_fk_usuario` int DEFAULT NULL,
  `borrado` int NOT NULL DEFAULT '0',
  `borrado_fecha` datetime DEFAULT NULL,
  `borrado_fk_usuario` int DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'URL del sistema o servicio externo',
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Token para endpoints',
  PRIMARY KEY (`rowid`) USING BTREE,
  KEY `fk_sistema_a_sincronizar` (`fk_sistema_a_sincronizar`) USING BTREE,
  KEY `fk_entidad` (`fk_entidad`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Indica que Empresa usa que Sincronizacion';

-- ----------------------------
-- Records of sistema_externo_sincronizaciones
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for sistema_externo_sync
-- ----------------------------
DROP TABLE IF EXISTS `sistema_externo_sync`;
CREATE TABLE `sistema_externo_sync` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tabla_sistema` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sistema_id` int DEFAULT NULL,
  `sistema_externo_tabla` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sistema_externo_id` int DEFAULT NULL,
  `sistema_externo_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'por ejemplo en taxcode usa id tipo string',
  `company` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizar` int NOT NULL DEFAULT '0' COMMENT '0 nada| 1 sistema',
  `procesado` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Esto es para que no se repita el procesamiento de forma automatica si hay algun error, ejemplo si faltan campos',
  `error_autocreacion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'la idea es identificar que campo hace falta rellenar que el sistema lo requiera y no se le este pasando de forma automatica por su contraparte',
  `nombre_sistema_externo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sincronizacion_ultima_fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sincronizacion_ultima_direccion` set('Entrante','Saliente') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `entidad` int DEFAULT '3',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `sistema_externo_id` (`sistema_externo_id`,`sistema_externo_tabla`,`company`) USING BTREE,
  UNIQUE KEY `code` (`sistema_externo_code`,`sistema_externo_tabla`,`company`) USING BTREE,
  UNIQUE KEY `sistema_id` (`sistema_id`,`tabla_sistema`,`company`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1485 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of sistema_externo_sync
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for token
-- ----------------------------
DROP TABLE IF EXISTS `token`;
CREATE TABLE `token` (
  `id` int NOT NULL AUTO_INCREMENT,
  `token` varchar(900) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `refresh_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `company` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `expire` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `entidad` int DEFAULT '3',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=374 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of token
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for usuarios
-- ----------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
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
  PRIMARY KEY (`rowid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Listado de Usuarios';

-- ----------------------------
-- Records of usuarios
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Procedure structure for add_missing_columns
-- ----------------------------
DROP PROCEDURE IF EXISTS `add_missing_columns`;
delimiter ;;
CREATE PROCEDURE `add_missing_columns`()
BEGIN
    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'fi_europa_facturas' AND COLUMN_NAME = 'entidad') THEN
        ALTER TABLE fi_europa_facturas ADD COLUMN `entidad` INT(10) NOT NULL DEFAULT '1';
    END IF;

    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'fi_europa_facturas' AND COLUMN_NAME = 'tipo') THEN
        ALTER TABLE fi_europa_facturas ADD COLUMN `tipo` SET('F1','F2','F3','R1') NOT NULL DEFAULT 'F1' COMMENT 'Si es importante' COLLATE 'utf8mb3_unicode_ci';
    END IF;

    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'fi_europa_facturas' AND COLUMN_NAME = 'actividad') THEN
        ALTER TABLE fi_europa_facturas ADD COLUMN `actividad` VARCHAR(50) NULL DEFAULT 'F1' COLLATE 'utf8mb3_unicode_ci';
    END IF;

    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'fi_europa_facturas' AND COLUMN_NAME = 'moneda') THEN
        ALTER TABLE fi_europa_facturas ADD COLUMN `moneda` INT(10) NULL DEFAULT '1';
    END IF;

    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'fi_europa_facturas' AND COLUMN_NAME = 'moneda_tipo_cambio') THEN
        ALTER TABLE fi_europa_facturas ADD COLUMN `moneda_tipo_cambio` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
    END IF;

    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'fi_europa_facturas' AND COLUMN_NAME = 'fk_usuario_crear') THEN
        ALTER TABLE fi_europa_facturas ADD COLUMN `fk_usuario_crear` INT(10) NOT NULL;
    END IF;

    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'fi_europa_facturas' AND COLUMN_NAME = 'fk_usuario_validar') THEN
        ALTER TABLE fi_europa_facturas ADD COLUMN `fk_usuario_validar` INT(10) NULL DEFAULT NULL;
    END IF;

    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'fi_europa_facturas' AND COLUMN_NAME = 'fk_usuario_validar_fecha') THEN
        ALTER TABLE fi_europa_facturas ADD COLUMN `fk_usuario_validar_fecha` DATETIME NULL DEFAULT NULL;
    END IF;

    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'fi_europa_facturas' AND COLUMN_NAME = 'fecha') THEN
        ALTER TABLE fi_europa_facturas ADD COLUMN `fecha` DATE NOT NULL;
    END IF;

    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'fi_europa_facturas' AND COLUMN_NAME = 'fecha_vencimiento') THEN
        ALTER TABLE fi_europa_facturas ADD COLUMN `fecha_vencimiento` DATE NOT NULL;
    END IF;

    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'fi_europa_facturas' AND COLUMN_NAME = 'referencia') THEN
        ALTER TABLE fi_europa_facturas ADD COLUMN `referencia` VARCHAR(20) NOT NULL COMMENT 'Almacena el consecutivo de la cotizacion' COLLATE 'utf8mb3_unicode_ci';
    END IF;

    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'fi_europa_facturas' AND COLUMN_NAME = 'referencia_serie') THEN
        ALTER TABLE fi_europa_facturas ADD COLUMN `referencia_serie` VARCHAR(20) NULL DEFAULT NULL COMMENT 'Apunta a la serie, necesario para hacienda' COLLATE 'utf8mb3_unicode_ci';
    END IF;

    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'fi_europa_facturas' AND COLUMN_NAME = 'referencia_proveedor') THEN
        ALTER TABLE fi_europa_facturas ADD COLUMN `referencia_proveedor` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb3_unicode_ci';
    END IF;

    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'fi_europa_facturas' AND COLUMN_NAME = 'fk_proyecto') THEN
        ALTER TABLE fi_europa_facturas ADD COLUMN `fk_proyecto` INT(10) NULL DEFAULT NULL COMMENT 'Esto es optativo';
    END IF;

    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'fi_europa_facturas' AND COLUMN_NAME = 'forma_pago') THEN
        ALTER TABLE fi_europa_facturas ADD COLUMN `forma_pago` INT(10) NOT NULL;
    END IF;

    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'fi_europa_facturas' AND COLUMN_NAME = 'detalle') THEN
        ALTER TABLE fi_europa_facturas ADD COLUMN `detalle` LONGTEXT NOT NULL COMMENT 'Referencia de la cotización' COLLATE 'utf8mb3_unicode_ci';
    END IF;

    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'fi_europa_facturas' AND COLUMN_NAME = 'notageneral') THEN
        ALTER TABLE fi_europa_facturas ADD COLUMN `notageneral` TEXT NULL DEFAULT NULL COLLATE 'utf8mb3_unicode_ci';
    END IF;

    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'fi_europa_facturas' AND COLUMN_NAME = 'detalle_publico') THEN
        ALTER TABLE fi_europa_facturas ADD COLUMN `detalle_publico` TEXT NULL DEFAULT NULL COLLATE 'utf8mb3_unicode_ci';
    END IF;

    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'fi_europa_facturas' AND COLUMN_NAME = 'fk_tercero') THEN
        ALTER TABLE fi_europa_facturas ADD COLUMN `fk_tercero` INT(10) NULL DEFAULT NULL;
    END IF;

    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'fi_europa_facturas' AND COLUMN_NAME = 'nombre_cliente') THEN
        ALTER TABLE fi_europa_facturas ADD COLUMN `nombre_cliente` VARCHAR(500) NULL DEFAULT NULL COLLATE 'utf8mb3_unicode_ci';
    END IF;

    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'fi_europa_facturas' AND COLUMN_NAME = 'subtotal') THEN
        ALTER TABLE fi_europa_facturas ADD COLUMN `subtotal` DECIMAL(30,5) NULL DEFAULT NULL;
    END IF;

    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'fi_europa_facturas' AND COLUMN_NAME = 'impuesto') THEN
        ALTER TABLE fi_europa_facturas ADD COLUMN `impuesto` DECIMAL(30,5) NULL DEFAULT NULL;
    END IF;

    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'fi_europa_facturas' AND COLUMN_NAME = 'pago_1') THEN
        ALTER TABLE fi_europa_facturas ADD COLUMN `pago_1` INT(10) NULL DEFAULT NULL;
    END IF;


END
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;