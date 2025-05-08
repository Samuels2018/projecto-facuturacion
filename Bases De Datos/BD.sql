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


-- Volcando estructura de base de datos para api_davivienda
CREATE DATABASE IF NOT EXISTS `api_davivienda` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `api_davivienda`;

-- Volcando estructura para tabla api_davivienda.davivienda_api
CREATE TABLE IF NOT EXISTS `davivienda_api` (
  `id` int NOT NULL AUTO_INCREMENT,
  `url_auth_url_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `url_auth_token_refresh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `url_pago_consulta` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `url_pago_vincula` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `url_pago_vincula_en_un_paso` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `token_usuario` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `token_clave` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `json_idUsuario` varchar(255) DEFAULT NULL,
  `json_idCaja` varchar(255) DEFAULT NULL,
  `json_Nombre_caja` varchar(255) DEFAULT NULL,
  `json_telefono` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla api_davivienda.davivienda_api: ~1 rows (aproximadamente)
INSERT INTO `davivienda_api` (`id`, `url_auth_url_token`, `url_auth_token_refresh`, `url_pago_consulta`, `url_pago_vincula`, `url_pago_vincula_en_un_paso`, `token_usuario`, `token_clave`, `json_idUsuario`, `json_idCaja`, `json_Nombre_caja`, `json_telefono`) VALUES
	(1, 'https://crux-taurus-servicio-integracion-test.azurewebsites.net/api/Integracion/ObtenerToken ', 'https://crux-taurus-servicio-integracion-test.azurewebsites.net/api/Integracion/RefrescarToken', 'https://crux-taurus-servicio-integracion-test.azurewebsites.net/api/Pago/ConsultaPago', 'https://crux-taurus-servicio-integracion-test.azurewebsites.net/api/Pago/VincularPago', 'https://crux-taurus-servicio-integracion-test.azurewebsites.net/api/Pago/VincularPagoEnUnPaso', 'integracion@valledepazcr.com', 'IntegracionValle24', '6FB0C76B-9B41-44E6-97CC-9B0C4DAE1666', '6897E769-D6A7-4194-BAB6-64BD062463DF', 'Caja Integracion Valle de Paz', '89206518');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;


ALTER TABLE log_sistema ADD COLUMN usuario_nombre VARCHAR(400) NULL DEFAULT '';