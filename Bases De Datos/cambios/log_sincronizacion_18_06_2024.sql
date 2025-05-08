SET
    FOREIGN_KEY_CHECKS = 0;

ALTER TABLE
    `log`.`sistema_externo_log`
ADD
    COLUMN `tipo_documento` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT 'Ejemplo: Cotizacion'
AFTER
    `entidad`;

ALTER TABLE
    `log`.`sistema_externo_log`
ADD
    COLUMN `documento_id` bigint NULL DEFAULT NULL COMMENT 'Ejemplo: Numero de cotizacion '
AFTER
    `tipo_documento`;

SET
    FOREIGN_KEY_CHECKS = 1;